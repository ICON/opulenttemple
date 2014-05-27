<?php
/**
 * Description of CMigratorParsing
 * 
 * This is basic class for creating any parser class.
 * Following methods must be implemented!
 * 
 * @since version 0.3
 * @abstract
 * @version 0.1
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 *
 * @author Petar Tuovic, email : petar@compojoom.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

abstract class CMigratorParsing extends JObject {
    public $db = null;
    
    public function __construct() {
        $this->db = JFactory::getDBO();
    }
    
    public abstract function parseContent($start, $limit);
    public abstract function parseImages($start, $limit);
    public abstract function setArticles($start, $limit);
    public abstract function makeLink($id);
    public abstract function getTotal();

    public function parseImagesPrepare($articles) {
        $img_base = JPATH_ROOT . DS . 'images' . DS . 'cmigration' . DS;
        $img_folder = JURI::root() . 'images/cmigration/';
        $image = null;
        $article_parts = array();
        $patern = '/images\\' . DS . 'cmigration/i';
        $counter = 0;
        $bool_change = array();
        $article_return = array();
        
        if (!JFolder::exists($img_base)) {
            JFolder::create($img_base);
        }

        foreach ($articles as $article) {
            $new_article = '';
            $err = null;
            
            // split article into the pieces, before and after src= tag for article fulltext
            $article_parts = preg_split('/\<img [^\>]* src\=(\"|\')[^\"\'\>]+/i', $article->fulltext);
            
            // is there any <img> tag in a file?
            if (count($article_parts) > 1) {

                // get all images in article
                preg_match_all('/\<img [^\>]* src\=(\"|\')[^\"\'\>]+/i', $article->fulltext, $media);
                $data = preg_replace('/(\<img [^\>]*)(src)(\"|\'|\=\"|\=\')(.*)/i', "$4", $media[0]);
                // dont forget <img * until src
                $prev = preg_replace('/(\<img [^\>]*)(src)(\"|\'|\=\"|\=\')(.*)/i', "$1", $media[0]);
                
                // let us put images into /images folder
                $i = 0;
                foreach ($data as $url) {
                    $parts = explode('/', $url);
                    if (preg_match($patern, $url) === 0) {
                        $headers = @get_headers($url);
                        if ($headers[0] == 'HTTP/1.1 404 Not Found' || $headers[0] == 'HTTP/1.1 301 Moved Permanently') {
                            $err = JText::_('COM_CMIGRATOR_IMAGE') . $parts[count($parts) - 1] . JText::_('COM_CMIGRATOR_FILE_DELETED');
                            $bool_change[$i] = false;
                        } else {
                            // getting file name
                            $file_name = preg_replace('/([a-zA-Z0-9\_\-]+\.[a-zA-Z0-9]+)(\?)/i', "$1", $parts[count($parts) - 1]);
                            $decode_name = urldecode($file_name);
                            
                            // rebuilding url
                            $new_url = '';
                            $temp = array();
                            
                            for($j = 0; $j < count($parts)-1; $j++) {
                                $temp[] = $parts[$j];
                            }                            
                            $new_url .= implode('/', $temp) . '/' . $file_name;
                            
                            if ($this->checkImage($file_name)) {
                                $image = @fopen($new_url, 'r');
                                $dest = $img_base . $decode_name;
                                if (!JFile::write($dest, $image)) {
                                    $err = $parts[count($parts) - 1] . JText::_('COM_CMIGRATOR_FILE_PERM');
                                    break;
                                }
                                $bool_change[$i] = true;
                                $counter++;
                                fclose($image);
                                $parts = '';
                            } else {
                                $err = JText::_('COM_CMIGRATOR_IMAGE') . $parts[count($parts) - 1] . JText::_('COM_CMIGRATOR_NOT_RIGHT_FORMAT');
                                $bool_change[$i] = false;
                            }
                        }
                    } else {
                        $bool_change[$i] = false;
                    }
                    $i++;
                }

                for ($i = 0; $i < (count($article_parts) - 1); $i++) {
                    if ($bool_change[$i]) {
                        $parts = explode('/', $data[$i]);
                        $file_name = preg_replace('/([a-zA-Z0-9\_\-]+\.[a-zA-Z0-9]+)(\?)/i', "$1", $parts[count($parts) - 1]);
                        $decode_name = urldecode($file_name);
                        $dest = $img_folder . $decode_name;
                    } else {
                        $dest = $data[$i];
                    }

                    $new_article .= $article_parts[$i]. $prev[$i] . ' src="' . $dest;
                }
                $new_article .= $article_parts[count($article_parts) - 1];
                
                array_push($article_return, array("original" => $article->fulltext, "new" => $new_article, "counter" => $counter, "id" => $article->id, "err" => array()));
            }
        }
        
        return $article_return;
    }
    
    private function checkImage($image) {
        $image_extensions_allowed = array('.jpg', '.jpeg', '.png', '.gif', '.bmp');
                
        foreach ($image_extensions_allowed as $ext) {
            if ($this->endsWith($image, $ext)) return true;
        }
        
        return false;
    }
    
    private function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        $start = $length * -1;
        return (substr($haystack, $start) === $needle);
    }
}