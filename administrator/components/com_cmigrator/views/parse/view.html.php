<?php

/**
 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/library/parser.php');

class CMigratorViewParse extends JView {

    public function display($tpl = null) {
        $session = JFactory::getSession();
        
        JToolBarHelper::title(JText::_('COM_CMIGRATOR_PARSE'), 'article.png');

        $post = JRequest::get('post');
        
        $content_ids = $post['content-ids'];
        
        $images_ids = $post['images-ids'];
        
        $content_counter = $post['content-counter'];
        
        $images_counter = $post['images-counter'];
        
        $conf_id = $post['config-id'];

        $status = array("content" => array("counter" => $content_counter, "ids" => $content_ids), "images" => array("counter" => $images_counter, "ids" => $images_ids));

        $result = $this->setUpResult($status, $conf_id);
        
        $err_list = $session->get('err_list');
        
        $this->assignRef('err_list', $err_list);

        $this->assignRef('result', $result);

        parent::display($tpl);
    }
    
    private function setUpResult($result, $conf_id) {
        $return = array();

        foreach ($result as $key => $value) {
            $links = $this->getLinks($value['ids'], $conf_id);
            if ($value['counter'] > 0) {
                $links = implode(', ', $links);
                $return[$key] = "SUCCESS!<br />Number of articles changed: " . $value['counter'] . "<br />Article ids:<br />" . $links;
            } else {
                $return[$key] = "No changes made!";
            }
        }

        return $return;
    }
    
    public function getLinks($ids, $conf_id) {
        $parser = null;
        $links = array();
        $ids = explode(',', $ids);

        $settings = CMigrateHelper::getSettings($conf_id);
        if ($settings) {
            $parser = new CMigratorParser($settings->get('cms', ''), $settings->get('content_selected', ''));
        } else {
            return array();
        }

        foreach ($ids as $link_id) {
            $links[] = $parser->makeLink($link_id);
        }

        return $links;
    }
}
