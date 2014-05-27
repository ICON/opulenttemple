<?php

/**
 * Description of CMigratorParseDrupal
 * 
 * Drupal parser class for Joomla! content.
 * 
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

require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'helpers' . DS . 'helper.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'parsing.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'helpers' . DS . 'drupal.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'parsers' . DS . 'com_content' . DS . 'com_content.php';

class CMigratorParserDrupal extends CMigratorParserJoomla {

    private $articles = array();

    public function __construct() {
        parent::__construct();
    }

    public function parseContent($start, $limit) {
        // all items already formated
        $this->setArticles($start, $limit);
        return array("processed" => ($start + count($this->articles)),"counter" => 0, "ids" => array());
    }

    public function setArticles($start, $limit) {
        $this->articles = $this->getContent($this->db, $start, $limit);
        return true;
    }

    public function parseImages($start, $limit) {
        $ids = array();
        $this->setArticles($start, $limit);
        $temps = DrupalHelper::parseImages($this->db, $this->articles, '#__content', $this);
        foreach($temps['ids'] as $id) {
            $ids[] = $id;
        }
        return array("processed" => ($start + count($this->articles)),"counter" => $temps['counter'], "ids" => $ids, "err" => $temps['err']);
    }
}