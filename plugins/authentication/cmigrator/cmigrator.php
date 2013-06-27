<?php

/**
 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * */
// No direct access
defined('_JEXEC') or die;

class plgAuthenticationCmigrator extends JPlugin {

    private $itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    private $bool = false;

    function onUserAuthenticate($credentials, $options, &$response) {
        $response->type = 'Joomla';
        $password = $credentials['password'];
        // Joomla does not like blank passwords
        if (empty($password)) {
            $response->status = JAuthentication::STATUS_FAILURE;
            $response->error_message = JText::_('JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED');
            return false;
        }

        // Get a database object
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id, password');
        $query->from('#__users');
        $query->where('username=' . $db->Quote($credentials['username']));

        $db->setQuery($query);
        $result = $db->loadObject();

        if ($result) {
            if ($this->joomlaAuth($password, $result) || $this->drupalAuth($password, $result) || $this->wordpressAuth($password, $result)) {

                if ($this->bool) {
                    if ($this->changePassword($credentials['username'], $password)) {
                        // TODO: add counter for success
                    }
                }

                $user = JUser::getInstance($result->id); // Bring this in line with the rest of the system
                $response->email = $user->email;
                $response->fullname = $user->name;
                if (JFactory::getApplication()->isAdmin()) {
                    $response->language = $user->getParam('admin_language');
                } else {
                    $response->language = $user->getParam('language');
                }
                $response->status = JAuthentication::STATUS_SUCCESS;
            } else {
                $response->status = JAuthentication::STATUS_FAILURE;
                $response->error_message = JText::_('JGLOBAL_AUTH_INVALID_PASS');
            }
        } else {
            $response->status = JAuthentication::STATUS_FAILURE;
            $response->error_message = JText::_('JGLOBAL_AUTH_NO_USER');
        }
    }

    // -- Joomla functions for authentication --

    private function joomlaAuth($password, $result) {
        $parts = explode(':', $result->password);
        $crypt = $parts[0];
        $salt = @$parts[1];
        $testcrypt = JUserHelper::getCryptedPassword($password, $salt);
        return $crypt == $testcrypt;
    }

    // -- Joomla functions end --
    // -- Drupal functions for authentication --

    private function drupalAuth($password, $result) {
        if (substr($result->password, 0, 2) == 'U$') {
            // This may be an updated password from user_update_7000(). Such hashes
            // have 'U' added as the first character and need an extra md5() (see the
            // Drupal 7 documentation).
            $stored_hash = substr($result->password, 1);
            $password = md5($password);
        } else {
            $stored_hash = $result->password;
        }

        $type = substr($stored_hash, 0, 3);
        switch ($type) {
            case '$S$':
                // A normal Drupal 7 password using sha512.
                $hash = $this->passwordCrypt('sha512', $password, $stored_hash);
                break;
            case '$H$':
            // phpBB3 uses "$H$" for the same thing as "$P$".
            case '$P$':
                // A phpass password generated using md5.  This is an
                // imported password or from an earlier Drupal version.
                $hash = $this->passwordCrypt('md5', $password, $stored_hash);
                break;
            default:
                return false;
        }
        $this->bool = true;
        return ($hash && $stored_hash == $hash);
    }

    private function passwordCrypt($algo, $password, $setting) {
        // The first 12 characters of an existing hash are its setting string.
        $setting = substr($setting, 0, 12);

        if ($setting[0] != '$' || $setting[2] != '$') {
            return false;
        }
        $count_log2 = strpos($this->itoa64, $setting[3]);
        // Hashes may be imported from elsewhere, so we allow != DRUPAL_HASH_COUNT
        if ($count_log2 < 7 || $count_log2 > 30) {
            return false;
        }
        $salt = substr($setting, 4, 8);
        // Hashes must have an 8 character salt.
        if (strlen($salt) != 8) {
            return false;
        }

        // Convert the base 2 logarithm into an integer.
        $count = 1 << $count_log2;

        // We rely on the hash() function being available in PHP 5.2+.
        $hash = hash($algo, $salt . $password, true);
        do {
            $hash = hash($algo, $hash . $password, true);
        } while (--$count);

        $len = strlen($hash);
        $output = $setting . $this->passwordBase64Encode($hash, $len);
        // passwordBase64Encode() of a 16 byte MD5 will always be 22 characters.
        // passwordBase64Encode() of a 64 byte sha512 will always be 86 characters.
        $expected = 12 + ceil((8 * $len) / 6);
        return (strlen($output) == $expected) ? substr($output, 0, 55) : false;
    }

    // -- Drupal functions end --
    // -- Wordpress functions for authentication --

    private function wordpressAuth($password, $result) {
        $hash = $this->cryptPrivate($password, $result->password);
        $this->bool = true;
        return $hash == $result->password;
    }

    private function cryptPrivate($password, $setting) {
        $output = '*0';
        if (substr($setting, 0, 2) == $output)
            $output = '*1';

        $id = substr($setting, 0, 3);
        # We use "$P$", phpBB3 uses "$H$" for the same thing
        if ($id != '$P$' && $id != '$H$')
            return $output;

        $count_log2 = strpos($this->itoa64, $setting[3]);
        if ($count_log2 < 7 || $count_log2 > 30)
            return $output;

        $count = 1 << $count_log2;

        $salt = substr($setting, 4, 8);
        if (strlen($salt) != 8)
            return $output;

        # We're kind of forced to use MD5 here since it's the only
        # cryptographic primitive available in all versions of PHP
        # currently in use.  To implement our own low-level crypto
        # in PHP would result in much worse performance and
        # consequently in lower iteration counts and hashes that are
        # quicker to crack (by non-PHP code).
        if (PHP_VERSION >= '5') {
            $hash = md5($salt . $password, true);
            do {
                $hash = md5($hash . $password, true);
            } while (--$count);
        } else {
            $hash = pack('H*', md5($salt . $password));
            do {
                $hash = pack('H*', md5($hash . $password));
            } while (--$count);
        }

        $output = substr($setting, 0, 12);
        $output .= $this->passwordBase64Encode($hash, 16);

        return $output;
    }

    // -- Wordpress functions end --

    private function passwordBase64Encode($input, $count) {
        $output = '';
        $i = 0;
        do {
            $value = ord($input[$i++]);
            $output .= $this->itoa64[$value & 0x3f];
            if ($i < $count) {
                $value |= ord($input[$i]) << 8;
            }
            $output .= $this->itoa64[($value >> 6) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            if ($i < $count) {
                $value |= ord($input[$i]) << 16;
            }
            $output .= $this->itoa64[($value >> 12) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            $output .= $this->itoa64[($value >> 18) & 0x3f];
        } while ($i < $count);

        return $output;
    }

    // Update password to Joomla! like pass

    private function changePassword($username, $password) {
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);
        $salt = JUserHelper::genRandomPassword(32);
        $newpass = JUserHelper::getCryptedPassword($password, $salt);

        $query->update('#__users');
        $query->set('`password`=' . $db->quote($newpass . ":" . $salt));
        $query->where('`username`=' . $db->quote($username));
        $db->setQuery($query);
        return $db->query();
    }

}