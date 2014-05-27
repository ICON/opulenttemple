<?php
/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

**/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/library/parser.php');

class CMigratorControllerParse extends JController {
    
    public function display($cachable = false, $urlparams = false) {
        parent::display($cachable, $urlparams);
    }
    
    public function start() {
        $session = JFactory::getSession();
        $session->set('err_list',array());
        $post = JFactory::getApplication()->input;        
        $config = $post->get('config');
        $settings = CMigrateHelper::getSettings($config);
        $parser = new CMigratorParser($settings->get('cms',''), $settings->get('content_selected',''));
        $response = array();
        
        $response += array('total' => $parser->getTotal());
                
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the MIME type for JSON output.
        $document->setMimeEncoding('application/json');

        // Change the suggested filename.
        JResponse::setHeader('Content-Disposition', 'attachment;filename=element' . '.json"');

        echo json_encode($response);

        // stop joomla from further processing
        jexit(0);
    }
    
    public function images() {
        $session = JFactory::getSession();
        $post = JFactory::getApplication()->input;        
        $start = $post->get('start');
        $limit = $post->get('limit');
        $config = $post->get('config');
        $settings = CMigrateHelper::getSettings($config);
        ini_set('max_execution_time', '3600');
        $parser = new CMigratorParser($settings->get('cms',''), $settings->get('content_selected',''));
        $response = array();
        
        $response += $parser->parseImages($start, $limit);
        if($response['err']) {
            $session->set('err_list', $session->get('err_list') + $response['err']);
        }        
        
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the MIME type for JSON output.
        $document->setMimeEncoding('application/json');

        // Change the suggested filename.
        JResponse::setHeader('Content-Disposition', 'attachment;filename=element' . '.json"');

        echo json_encode($response);

        // stop joomla from further processing
        jexit(0);
    }
    
    public function content() {
        $post = JFactory::getApplication()->input;        
        $start = $post->get('start');
        $limit = $post->get('limit');
        $config = $post->get('config');
        $settings = CMigrateHelper::getSettings($config);
        ini_set('max_execution_time', '3600');
        $parser = new CMigratorParser($settings->get('cms',''), $settings->get('content_selected',''));
        $response = array();
        
        $response += $parser->parseContent($start, $limit);
                
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the MIME type for JSON output.
        $document->setMimeEncoding('application/json');

        // Change the suggested filename.
        JResponse::setHeader('Content-Disposition', 'attachment;filename=element' . '.json"');

        echo json_encode($response);

        // stop joomla from further processing
        jexit(0);
    }
}