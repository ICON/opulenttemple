<?php
/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2012 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @author Petar Tuovic , email: petar@compojoom.com

**/

defined('_JEXEC') or die('Restricted access');

class CMigratorParser extends JObject{

    private $parser;

    public function __construct($cms, $option) {
        $name = 'CMigratorParser'.ucfirst($cms);
        
        require_once('parsers/'.$option.'/'.$cms.'.php');

        try {
            $this->parser = new $name();
        } catch(Exception $e) {
            $appl = JFactory::getApplication();
            $appl->redirect('index.php?option=com_cmigrator&view=cpanel', $e->getMessage() . JText::_('COM_CMIGRATOR_PARSING_ABORTED'), 'error');
        }
    }
    
    public function parseContent($start, $limit) {
        return $this->parser->parseContent($start, $limit);
    }
            
    public function parseImages($start, $limit) {
        return $this->parser->parseImages($start, $limit);
    }
    
    public function getTotal() {
        return $this->parser->getTotal();
    }
    
    public function makeLink($id) {
        return $this->parser->makeLink($id);
    }
}