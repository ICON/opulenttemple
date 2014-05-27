<?php

/*
 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @author Petar Тuovic , email: petar@compojoom.com
 * @since 0.3
 */

jimport('joomla.user.helper');

class WordPressHelper {
    
    public static function getCategories($db, $prefix, $start = -1, $limit = -1) {
        $query = 'SELECT term.term_id, term.name, tax.parent, tax.term_taxonomy_id '.
                 'FROM '. $prefix. 'terms as term '.
                 'INNER JOIN '. $prefix . 'term_taxonomy as tax ON term.term_id = tax.term_id '.
                 'WHERE tax.taxonomy = "category" ';
        if($start != -1 && $limit != -1) {
            $query .= 'LIMIT '. $start . ',' . $limit;
        }
        $db->setQuery($query);
        
        return $db->loadObjectList();
    }

    public static function getCategory($post, $cats, $no_category) {
        $catid = $no_category;
        
        if($cats != -1) {
            if (count($cats) == 1 && $cats[0]->help_text == 'no-categories') {
                return $cats[0]->joomla_id;
            }
            if ($post->post_type == "post") {
                if($post->term_taxonomy_id == -1) {
                    foreach ($cats as $cat) {
                        if ($cat->help_text == 'posts') {
                            $catid = $cat->joomla_id;
                            break;
                        }
                    }
                } else {
                    foreach ($cats as $cat) {
                        if ($post->term_taxonomy_id == $cat->help_text) {
                            $catid = $cat->joomla_id;
                            break;
                        }
                    }
                    if ($catid == $no_category) {
                        $catid = $cat->joomla_id;
                    }
                }
            } else if ($post->post_type == "page") {
                foreach ($cats as $cat) {
                    if ($cat->help_text == 'pages') {
                        $catid = $cat->joomla_id;
                        break;
                    }
                }
            }
        }
        
        return $catid;
    }

    public static function getAuthor($user_conn, $post, $user) {
        $author = $user->id;
        
        if ($user_conn) {
            foreach ($user_conn as $user_connection) {
                if ($user_connection->cms_id == $post->post_author) {
                    $author = $user_connection->joomla_id;
                    break;
                }
            }
        }
        
        return $author;
    }
    
    public static function getPosts($db, $prefix, $start = -1, $limit = -1) {
        $query = 'SELECT * '.
                 'FROM '. $prefix. 'posts '.
                 'WHERE `post_type`="post" AND `post_status`="publish" ';
        if($start != -1 && $limit != -1) {
            $query .= 'LIMIT '. $start . ',' . $limit;
        }
        
        $db->setQuery($query);
        $posts = $db->loadObjectList();
        
        return $posts;
    }
    
    public static function getPostsPrepare($db, $prefix, &$posts) {
        $query = 'SELECT wpr.object_id AS id, wpr.term_taxonomy_id AS tax_id '.
                 'FROM '. $prefix. 'terms AS wpt '.
                 'INNER JOIN ' . $prefix . 'term_taxonomy AS wptt ON wpt.term_id = wptt.term_id ' .
                 'INNER JOIN ' . $prefix . 'term_relationships AS wpr ON wpr.term_taxonomy_id = wptt.term_taxonomy_id '.
                 'WHERE wptt.taxonomy="category" '.
                 'GROUP BY wpr.object_id';
        
        $db->setQuery($query);
        $relations = $db->loadObjectList();
        
        if($relations) {
            foreach ($posts as $post) {
                foreach ($relations as $relation) {
                    if ($post->ID == $relation->id) {
                        $post->term_taxonomy_id = $relation->tax_id;
                        break;
                    }
                }
                if (!isset($post->term_taxonomy_id)) {
                    $post->term_taxonomy_id = -1;
                }
            }
        }
    }
    
    public static function getPages($db, $prefix, $start = -1, $limit = -1) {
        $query = 'SELECT * '.
                 'FROM '. $prefix . 'posts '.
                 'WHERE `post_status`="publish" AND `post_type`="page" ';
        if($start != -1 && $limit != -1) {
            $query .= 'LIMIT '. $start . ',' . $limit;
        }
        
        $db->setQuery($query);
        $pages = $db->loadObjectList();
        return $pages;
    }
    
    public static function getComments($db, $prefix, $start = -1, $limit = -1) {
        $query = 'SELECT * '.
                 'FROM '. $prefix . 'comments AS c '.
                 'INNER JOIN #__cmigrator_articles AS a ON c.comment_post_ID=a.cms_id '.
                 'WHERE c.comment_approved="1" ';
        if($start != -1 && $limit != -1) {
            $query .= 'LIMIT '. $start . ',' . $limit;
        }
        
        $db->setQuery($query);
        $comments = $db->loadObjectList();        
        
        return $comments;
    }


    public static function getNumberOfPosts($db, $prefix) {
        $query = $db->getQuery(true);
        
        $query->select('COUNT(*)');
        $query->from($prefix . 'posts');
        $query->where('post_status="publish" AND post_type="post"');
        $db->setQuery($query);
        
        return $db->loadResult();
    }
    
    public static function getNumberOfPages($db, $prefix) {
        $query = $db->getQuery(true);
        
        $query->select('COUNT(*)');
        $query->from($prefix . 'posts');
        $query->where('post_status="publish" AND post_type="page"');
        $db->setQuery($query);
        
        return $db->loadResult();
    }
    
    public static function getNumberOfCategories($db, $prefix) {
        $query = $db->getQuery(true);
        
        $query->select('COUNT(*)');
        $query->from($prefix . "terms as term");
        $query->innerJoin($prefix.'term_taxonomy as tax ON term.term_id = tax.term_id' );
        $query->where('tax.taxonomy = "category"');
        $db->setQuery($query);
        
        return $db->loadResult();
    }
    
    public static function getNumberOfUsers($db, $prefix) {
        $query = $db->getQuery(true);
        
        $query->select('COUNT(*)');
        $query->from($prefix.'users AS u');
        $db->setQuery($query);
        
        return $db->loadResult();
    }
    
    public static function getNumberOfComments($db, $prefix) {
        $query = $db->getQuery(true);
        
        $query->select('COUNT(*)');
        $query->from($prefix . 'comments AS c');
        $query->innerJoin($prefix . 'posts AS p ON c.comment_post_ID=p.ID');
        $query->where('c.comment_approved="1"');
        $db->setQuery($query);
        
        return $db->loadResult();
    }

    public static function checkTableExist($db, $prefix) {
        $query = "SHOW TABLES LIKE " . $db->quote( $prefix . 'posts');
    	$db->setQuery($query);

        return ($db->loadResult()) ? true : false;
    }

    public static function userMigrationWordPress($db, $prefix, $caller, $start, $limit) {
        jimport('joomla.user.helper');
        $admin = JFactory::getUser();
        $users = null;
        $uid = null;
        $gid = null;
        $usergroups = null;
        $role = null;
        $fname = '';
        $lname = '';
        $i = 0;
        $dispatcher = JDispatcher::getInstance();
        $fname_arr = array();
        $lname_arr = array();
        
        // getting users and their rols
        $query = 'SELECT u.ID as id,u.user_login as username,u.user_pass as password,u.user_email as email,u.user_registered as created,um.meta_value as role '.
                 'FROM '. $prefix. 'users AS u '.
                 'INNER JOIN '. $prefix . 'usermeta AS um ON u.ID=um.user_id '.
                 'WHERE u.user_status=0 AND um.meta_key="' . $prefix . 'capabilities" '.
                 'ORDER BY u.ID ';
        if($start != -1 && $limit != -1) {
            $query .= 'LIMIT '. $start . ',' . $limit;
        }
        
        $db->setQuery($query);
        $users = $db->loadAssocList();
        
        // getting users first name
        $query = 'SELECT u.ID as id,um.meta_value as fname '.
                 'FROM '. $prefix. 'users AS u '.
                 'INNER JOIN '. $prefix . 'usermeta AS um ON u.ID=um.user_id '.
                 'WHERE u.user_status=0 AND um.meta_key="first_name" '.
                 'ORDER BY u.ID ';
        if($start != -1 && $limit != -1) {
            $query .= 'LIMIT '. $start . ',' . $limit;
        }
        
        $db->setQuery($query);
        $fname_arr = $db->loadAssocList();
        
        // getting users last name
        $query = 'SELECT u.ID as id,um.meta_value as lname '.
                 'FROM '. $prefix. 'users AS u '.
                 'INNER JOIN '. $prefix . 'usermeta AS um ON u.ID=um.user_id '.
                 'WHERE u.user_status=0 AND um.meta_key="last_name" '.
                 'ORDER BY u.ID ';
        if($start != -1 && $limit != -1) {
            $query .= 'LIMIT '. $start . ',' . $limit;
        }
        
        $db->setQuery($query);
        $lname_arr = $db->loadAssocList();
        
        // get all usergroups
        
        $query = $db->getQuery(true);
        $query->select('id,title');
        $query->from('#__usergroups');
        $db->setQuery($query);
        $usergroups = $db->loadObjectList();
        
        for ($id = 0; $id < count($users); $id++) {
            if(isset($fname_arr[$id]['fname'])) {
                $fname = $fname_arr[$id]['fname'];
            }
            
            if(isset($lname_arr[$id]['lname'])) {
                $lname = $lname_arr[$id]['lname'];
            }
            
            $parts = explode('"', $users[$id]['role']);
            $role = $parts[1];
            
            if ($role !== null) {
                $users[$id]["name"] = $fname.$lname;
                $users[$id]["usertype"] = "deprecated";
                $users[$id]["password_clear"] = ' ';
                
                if (empty($users[$id]["name"])) {
                    $users[$id]["name"] = $users[$id]['username'];
                }
                
                $process = true;
                if(!$caller->cleanMigration) {
                    $olduser = $caller->checkUserExists($users[$id]['username']);
                    if($olduser) {
                        $process = false;
                        $query = $db->getQuery(true);
                        $query->insert('`#__cmigrator_users`');
                        $query->set('`cms_id`=' . $users[$id]["id"]);
                        $query->set('`joomla_id`=' . $olduser->id);
                        $db->setQuery($query);
                        $db->query();
                    }
                } else {
                    if ($users[$id]['username'] == $admin->username) {
                        $process = false;
                        $query = $db->getQuery(true);
                        $query->insert('`#__cmigrator_users`');
                        $query->set('`cms_id`=' . $users[$id]['id']);
                        $query->set('`joomla_id`=' . $admin->id);
                        $db->setQuery($query);
                        $db->query();
                    }
                }
                if($process) {                    
                    $query = $db->getQuery(true);
                    $query->insert('#__users');
                    $query->columns('`name`,`username`,`email`,`password`,`usertype`,`block`,`sendEmail`,`registerDate`,`activation`')
                          ->values($db->quote($users[$id]['name']) . ',' . $db->quote($users[$id]['username']) . ',' . $db->quote($users[$id]['email']) . ',' . $db->quote($users[$id]['password']) . ',' . $db->quote($users[$id]['usertype']) . ',0,1,' . $db->quote($users[$id]['created']) . ',0');
                    $db->setQuery($query);
                    $db->query();
                    $uid = $db->insertid();

                    $query = $db->getQuery(true);
                    $query->insert('`#__cmigrator_users`');
                    $query->set('`cms_id`=' . $users[$id]["id"]);
                    $query->set('`joomla_id`=' . $uid);
                    $db->setQuery($query);
                    $db->query();

                    foreach ($usergroups as $group) {
                        if ((strtolower($role) == strtolower($group->title)) || ($role == 'subscriber' && $group->title == 'Registered') || ($role == 'contributor' && $group->title == 'Publisher')) {
                            $gid = $group->id;
                        }
                    }
                    $users[$id]["id"] = $uid;
                    JUserHelper::addUserToGroup($uid, $gid);
                    $dispatcher->trigger('onUserAfterSave', array($users[$id], false, true, $caller->getError()));
                }
                
                $i++;
                $role = null;
                $fname = '';
                $lname = '';
            }
        }
        
        return ($start + $i);
    }
    
    public static function parseContent($db, $articles, $table) {
        $changed = array();
        $fulltext = '';
        $introtext = '';
        $counter = 0;
        $update = false;

        if ($table) {
            foreach ($articles as $article) {
                $fulltext = $article->fulltext;
                $introtext = $article->introtext;
                
                // fix title
                $title = htmlspecialchars_decode($article->title, ENT_QUOTES);
                
                $patern = '/( ?\[caption[^\]]*\])|( ?\[\/caption\]|)|( ?\<blockquote[^\]]*\>)|( ?\<\/blockquote\>)|( ?\<code[^\]]*\>[^\<]*\<\/code\>)|( ?\<ins[^\]]*\>[^\<]*\<\/ins\>)|( ?\<del[^\]]*\>[^\<]*\<\/del\>)|( class=(\"|\')[^\"\'\>]+(\"|\'))|( id=(\"|\')[^\"\'\>]+(\"|\'))/i';

                $before = $fulltext;
                $fulltext = preg_replace($patern, '', $fulltext);
                if ($fulltext != $before)
                    $update = true;

                $parts = preg_split('/<!--more-->/i', $fulltext);
                if (count($parts) > 1) {
                    $before = $fulltext;
                    $fulltext = $parts[1];
                    $introtext = $parts[0];
                    $update = true;
                }
                
                $fulltext = preg_replace("/\n\n+/", "\n\n", $fulltext);
                $fulltexts = preg_split('/\n\s*\n/', $fulltext, -1, PREG_SPLIT_NO_EMPTY);
                if (count($fulltexts) > 1) {
                    $fulltext = '';
                    foreach ($fulltexts as $temp) {
                        $fulltext .= '<p>' . trim($temp, "\n") . "</p>\n";
                    }
                    $update = true;
                }
                
                $introtext = preg_replace("/\n\n+/", "\n\n", $introtext);
                $introtexts = preg_split('/\n\s*\n/', $introtext, -1, PREG_SPLIT_NO_EMPTY);
                if (count($introtexts) > 1) {
                    $introtext = '';
                    foreach ($introtexts as $temp) {
                        $introtext .= '<p>' . trim($temp, "\n") . "</p>\n";
                    }
                    $update = true;
                }
                
                $query = $db->getQuery(true);
                $query->update($table);
                $query->set('`title`=' . $db->quote($title));
                if ($update) {
                    $query->set('`fulltext`=' . $db->quote($fulltext));
                    $query->set('`introtext`=' . $db->quote($introtext));
                    $changed[] = $article->id;
                    $counter++;
                }
                $query->where('`id`=' . $article->id);
                $db->setQuery($query);
                $db->query();

                $update = false;
            }

            return array("counter" => $counter, "ids" => $changed);
        } else return array();
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
    
    public static function formatComments(&$comments, $component) {
        foreach ($comments as $comment) {
            $comment->id = $comment->comment_ID;
            $comment->contentid = $comment->comment_post_ID;
            $comment->component = $component;
            $comment->ip = $comment->comment_author_IP;
            $comment->userid = $comment->user_id;
            $comment->date = $comment->comment_date;
            $comment->name = $comment->comment_author;
            $comment->email = $comment->comment_author_email;
            $comment->karma = $comment->comment_karma;
            $comment->voteyes = null;
            $comment->voteno = null;
            $comment->website = $comment->comment_author_url;
            $comment->comment = $comment->comment_content;
            $comment->parent = $comment->comment_parent;
        }
    }
}