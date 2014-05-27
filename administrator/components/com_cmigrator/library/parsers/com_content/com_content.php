<?php

/**
 * Description of CMigratorParserJoomla
 * 
 * Abstract class for com_content.
 * 
 * @abstract
 * @since version 0.3
 * @version 0.1
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 *
 * @author Petar Tuovic, email : petar@compojoom.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

abstract class CMigratorParserJoomla extends CMigratorParsing {
    public function __construct() {
        parent::__construct();
    }
    
    public function parseContent($start, $limit) {}
    public function setArticles($start, $limit) {}
    public function parseImages($start, $limit) {}
    public function makeLink($id) {
        $link = '<a target="_blank" href="' . JURI::root() . 'index.php?option=com_content&view=article&id=' . $id . '">' . $id . '</a>';
        return $link;
    }    
    
    public static function getContent($db, $start = -1, $limit = -1) {
        $query = 'SELECT * '.
                 'FROM #__content AS cont '.
                 'INNER JOIN #__cmigrator_articles AS cmig ON cmig.joomla_id=cont.id ';
        if ($start != -1 && $limit != -1) {
            $query .= 'LIMIT ' . $start . ',' . $limit;
        }
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    public function getTotal(){
        $query = $this->db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from('#__content AS cont');
        $query->innerJoin('#__cmigrator_articles AS cmig ON cont.id=cmig.joomla_id');
        $this->db->setQuery($query);
        return $this->db->loadResult();
    }
}