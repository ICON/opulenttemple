<?php

/*
 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @author Petar Тuovic , email: petar@compojoom.com
 * @since 0.3
 */

class DrupalHelper {

    public static function getCategories($db, $prefix, $start, $limit) {
        $query = 'SELECT content.type AS type, content.name AS name, content.description AS description ' .
                'FROM ' . $prefix . 'node_type as content ' .
                'WHERE content.disabled = 0 ';
        if ($start != -1 && $limit != -1) {
            $query .= 'LIMIT ' . $start . ',' . $limit;
        }
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public static function getCategory($content_types, $post, $no_category) {
        $catid = $no_category;

        foreach ($content_types as $type) {
            if (strcmp($type->help_text, $post->type) == 0) {
                $catid = $type->joomla_id;
                break;
            } elseif($no_category == 0) {
                $catid = $type->joomla_id;
            }
        }

        return $catid;
    }

    public static function getAuthor($user_conn, $post, $user) {
        $author = $user->id;

        if ($user_conn) {
            foreach ($user_conn as $user_connection) {
                if ($user_connection->cms_id == $post->uid) {
                    $author = $user_connection->joomla_id;
                    break;
                }
            }
        }

        return $author;
    }

    public static function getNodes($db, $prefix, $start = -1, $limit = -1) {
        $query = 'SELECT n.nid AS id,n.uid AS uid,n.type AS type,n.title AS title,n.created AS created,n.changed AS changed,t.body_value AS body,t.body_summary AS summary ' .
                'FROM ' . $prefix . 'node AS n ' .
                'INNER JOIN ' . $prefix . 'field_data_body AS t ON n.nid = t.entity_id ' .
                'WHERE n.status = 1 ';
        if ($start != -1 && $limit != -1) {
            $query .= 'LIMIT ' . $start . ',' . $limit;
        }

        $db->setQuery($query);
        $nodes = $db->loadObjectList();
        return $nodes;
    }

    public static function getNumberOfNodes($db, $prefix) {
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from($prefix . 'node AS n');
        $query->innerJoin($prefix . 'field_data_body AS t ON n.nid = t.entity_id');
        $query->where('n.status = 1');
        $db->setQuery($query);

        return $db->loadResult();
    }

    public static function getNumberOfCategories($db, $prefix) {
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from($prefix . "node_type as content");
        $query->where('content.disabled = 0');

        $db->setQuery($query);
        return $db->loadResult();
    }

    public static function getNumberOfUsers($db, $prefix) {
        $query = $db->getQuery(true);

        $query->select('COUNT(*)');
        $query->from($prefix . 'users AS u');
        $query->innerJoin($prefix . 'users_roles AS ur ON u.uid=ur.uid');
        $query->innerJoin($prefix . 'role AS r ON r.rid=ur.rid');
        $query->where('u.status=1');
        $db->setQuery($query);

        return $db->loadResult();
    }

    public static function checkTableExist($db, $prefix) {
        $query = "SHOW TABLES LIKE " . $db->quote($prefix . 'node_type');
        $db->setQuery($query);

        return ($db->loadResult()) ? true : false;
    }

    public static function userMigrationDrupal($db, $prefix, $caller, $start, $limit) {
        $admin = JFactory::getUser();
        $config = JFactory::getConfig();
        $time = new DateTime($config->getValue('config.offset_user'));
        $users = null;
        $uid = null;
        $gid = null;
        $usergroups = null;
        $table = null;
        $dispatcher = JDispatcher::getInstance();
        $i = 0;

        $query = 'SELECT u.uid as id,u.name as name,u.pass as password,u.mail as email,u.created as created,r.name as role ' .
                'FROM ' . $prefix . 'users AS u ' .
                'INNER JOIN ' . $prefix . 'users_roles AS ur ON u.uid=ur.uid ' .
                'INNER JOIN ' . $prefix . 'role AS r ON r.rid=ur.rid ' .
                'WHERE u.status = 1 ';
        if ($start != -1 && $limit != -1) {
            $query .= 'LIMIT ' . $start . ',' . $limit;
        }

        $db->setQuery($query);
        $users = $db->loadObjectList();

        $query = $db->getQuery(true);
        $query->select('name');
        $query->from($prefix . 'role');
        $query->where('name!="administrator" AND name!="authenticated user" AND name!="anonymous user"');
        $db->setQuery($query);
        $usergroups = $db->loadObjectList();

        foreach ($usergroups as $group) {
            $query = $db->getQuery(true);
            $query->insert('#__usergroups');
            $query->set('parent_id=1');
            $query->set('title=' . $db->quote($group->name));
            $db->setQuery($query);
            $db->query();
        }

        $table = JTable::getInstance('Usergroup', 'JTable');
        $table->rebuild();

        // get all usergroups

        $query = $db->getQuery(true);
        $query->select('id,title');
        $query->from('#__usergroups');
        $db->setQuery($query);
        $usergroups = $db->loadObjectList();

        foreach ($users as $user) {
            $time->setTimestamp($user->created);
            $created = $time->format('Y-m-d H:i:s');
            $user->created = $created;
            $user->usertype = 'deprecated';
            $user->username = $user->name;
            $process = true;
            
            if (!$caller->cleanMigration) {
                $olduser = $caller->checkUserExists($user->username);
                if ($olduser) {
                    $process = false;
                    $query = $db->getQuery(true);
                    $query->insert('`#__cmigrator_users`');
                    $query->set('`cms_id`=' . $user->id);
                    $query->set('`joomla_id`=' . $olduser->id);
                    $db->setQuery($query);
                    $db->query();
                }
            } else {
                if ($user->username == $admin->username) {
                    $process = false;
                    $query = $db->getQuery(true);
                    $query->insert('`#__cmigrator_users`');
                    $query->set('`cms_id`=' . $user->id);
                    $query->set('`joomla_id`=' . $admin->id);
                    $db->setQuery($query);
                    $db->query();
                }
            }
            if ($process) {
                $user->password_clear = ' ';
                $dispatcher->trigger('onUserBeforeSave', array(array(), true, get_object_vars($user)));
                $query = $db->getQuery(true);
                $query->insert('#__users');
                $query->columns('`name`,`username`,`email`,`password`,`usertype`,`block`,`sendEmail`,`registerDate`,`activation`')
                      ->values($db->quote($user->name) . ',' . $db->quote($user->username) . ',' . $db->quote($user->email) . ',' . $db->quote($user->password) . ',' . $db->quote($user->usertype) . ',0,1,' . $db->quote($user->created) . ',0');
                $db->setQuery($query);
                $db->query();
                $uid = $db->insertid();

                $query = $db->getQuery(true);
                $query->insert('`#__cmigrator_users`');
                $query->set('`cms_id`=' . $user->id);
                $query->set('`joomla_id`=' . $uid);
                $db->setQuery($query);
                $db->query();

                foreach ($usergroups as $group) {
                    if ((strtolower($user->role) == strtolower($group->title)) || ($group->title == 'Registered' && $user->role == 'authenticated user')) {
                        $gid = $group->id;
                    }
                }

                $user->id = $uid;
                $query = $db->getQuery(true);
                $query->insert('`#__user_usergroup_map`');
                $query->set('`user_id`=' . $uid);
                $query->set('`group_id`=' . $gid);
                $db->setQuery($query);
                $db->query();

                $dispatcher->trigger('onUserAfterSave', array(get_object_vars($user), false, true, $caller->getError()));
            }

            $i++;
        }

        return ($start + $i);
    }

    public static function parseImages($db, $articles, $table, $parent) {
        $articless = $parent->parseImagesPrepare($articles);
        $changed = array();
        $i = 0;
        $err = array();

        foreach ($articless as $article) {
            if ($article['original'] != $article['new'] && $article['counter'] > 0) {
                $query = $db->getQuery(true);
                $query->update($table);
                $query->set('`fulltext`=' . $db->quote($article['new']));
                $query->where('`id`=' . $article['id']);
                $db->setQuery($query);
                $db->query();
                $i++;
                $changed[] = $article['id'];
                $err[] = $article['err'];
            }
        }

        return array("counter" => $i, "ids" => $changed, "err" => $err);
    }

}