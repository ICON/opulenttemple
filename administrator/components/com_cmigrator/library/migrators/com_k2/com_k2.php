<?php

/**
 * Description of CMigratorMigrationToK2
 * 
 * This is basic class for creating any migration class for import to K2 Content.
 * 
 * @since version 0.3
 * @abstract
 * @version 0.1
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 *
 * @author Petar Tuovic , email : petar@compojoom.com
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cmigrator'.DS.'library'.DS.'migration.php';

abstract class CMigratorMigrationToK2 extends CMigratorMigration {
    
    public $item_params = null;
    public $cat_params = null;
    
    public function __construct($id) {
        //  create connection to the cms database
        parent::__construct($id);
        $this->item_params = '{"catItemTitle":"","catItemTitleLinked":"","catItemFeaturedNotice":"","catItemAuthor":"","catItemDateCreated":"","catItemRating":"","catItemImage":"","catItemIntroText":"","catItemExtraFields":"","catItemHits":"","catItemCategory":"","catItemTags":"","catItemAttachments":"","catItemAttachmentsCounter":"","catItemVideo":"","catItemVideoWidth":"","catItemVideoHeight":"","catItemAudioWidth":"","catItemAudioHeight":"","catItemVideoAutoPlay":"","catItemImageGallery":"","catItemDateModified":"","catItemReadMore":"","catItemCommentsAnchor":"","catItemK2Plugins":"","itemDateCreated":"","itemTitle":"","itemFeaturedNotice":"","itemAuthor":"","itemFontResizer":"","itemPrintButton":"","itemEmailButton":"","itemSocialButton":"","itemVideoAnchor":"","itemImageGalleryAnchor":"","itemCommentsAnchor":"","itemRating":"","itemImage":"","itemImgSize":"","itemImageMainCaption":"","itemImageMainCredits":"","itemIntroText":"","itemFullText":"","itemExtraFields":"","itemDateModified":"","itemHits":"","itemCategory":"","itemTags":"","itemAttachments":"","itemAttachmentsCounter":"","itemVideo":"","itemVideoWidth":"","itemVideoHeight":"","itemAudioWidth":"","itemAudioHeight":"","itemVideoAutoPlay":"","itemVideoCaption":"","itemVideoCredits":"","itemImageGallery":"","itemNavigation":"","itemComments":"","itemTwitterButton":"","itemFacebookButton":"","itemGooglePlusOneButton":"","itemAuthorBlock":"","itemAuthorImage":"","itemAuthorDescription":"","itemAuthorURL":"","itemAuthorEmail":"","itemAuthorLatest":"","itemAuthorLatestLimit":"","itemRelated":"","itemRelatedLimit":"","itemRelatedTitle":"","itemRelatedCategory":"","itemRelatedImageSize":"","itemRelatedIntrotext":"","itemRelatedFulltext":"","itemRelatedAuthor":"","itemRelatedMedia":"","itemRelatedImageGallery":"","itemK2Plugins":""}';
        $this->cat_params = '{"inheritFrom":"0","theme":"","num_leading_items":"2","num_leading_columns":"1","leadingImgSize":"Large","num_primary_items":"4","num_primary_columns":"2","primaryImgSize":"Medium","num_secondary_items":"4","num_secondary_columns":"1","secondaryImgSize":"Small","num_links":"4","num_links_columns":"1","linksImgSize":"XSmall","catCatalogMode":"0","catFeaturedItems":"1","catOrdering":"","catPagination":"2","catPaginationResults":"1","catTitle":"1","catTitleItemCounter":"1","catDescription":"1","catImage":"1","catFeedLink":"1","catFeedIcon":"1","subCategories":"1","subCatColumns":"2","subCatOrdering":"","subCatTitle":"1","subCatTitleItemCounter":"1","subCatDescription":"1","subCatImage":"1","itemImageXS":"","itemImageS":"","itemImageM":"","itemImageL":"","itemImageXL":"","catItemTitle":"1","catItemTitleLinked":"1","catItemFeaturedNotice":"0","catItemAuthor":"1","catItemDateCreated":"1","catItemRating":"0","catItemImage":"1","catItemIntroText":"1","catItemIntroTextWordLimit":"","catItemExtraFields":"0","catItemHits":"0","catItemCategory":"1","catItemTags":"1","catItemAttachments":"0","catItemAttachmentsCounter":"0","catItemVideo":"0","catItemVideoWidth":"","catItemVideoHeight":"","catItemAudioWidth":"","catItemAudioHeight":"","catItemVideoAutoPlay":"0","catItemImageGallery":"0","catItemDateModified":"0","catItemReadMore":"1","catItemCommentsAnchor":"1","catItemK2Plugins":"1","itemDateCreated":"1","itemTitle":"1","itemFeaturedNotice":"1","itemAuthor":"1","itemFontResizer":"1","itemPrintButton":"1","itemEmailButton":"1","itemSocialButton":"1","itemVideoAnchor":"1","itemImageGalleryAnchor":"1","itemCommentsAnchor":"1","itemRating":"1","itemImage":"1","itemImgSize":"Large","itemImageMainCaption":"1","itemImageMainCredits":"1","itemIntroText":"1","itemFullText":"1","itemExtraFields":"1","itemDateModified":"1","itemHits":"1","itemCategory":"1","itemTags":"1","itemAttachments":"1","itemAttachmentsCounter":"1","itemVideo":"1","itemVideoWidth":"","itemVideoHeight":"","itemAudioWidth":"","itemAudioHeight":"","itemVideoAutoPlay":"0","itemVideoCaption":"1","itemVideoCredits":"1","itemImageGallery":"1","itemNavigation":"1","itemComments":"1","itemTwitterButton":"1","itemFacebookButton":"1","itemGooglePlusOneButton":"1","itemAuthorBlock":"1","itemAuthorImage":"1","itemAuthorDescription":"1","itemAuthorURL":"1","itemAuthorEmail":"0","itemAuthorLatest":"1","itemAuthorLatestLimit":"5","itemRelated":"1","itemRelatedLimit":"5","itemRelatedTitle":"1","itemRelatedCategory":"0","itemRelatedImageSize":"0","itemRelatedIntrotext":"0","itemRelatedFulltext":"0","itemRelatedAuthor":"0","itemRelatedMedia":"0","itemRelatedImageGallery":"0","itemK2Plugins":"1","catMetaDesc":"","catMetaKey":"","catMetaRobots":"","catMetaAuthor":""}';
    }
    
    public function migrateCategories($start, $limit) {
        // must be implemented
        JError::raiseError('003', 'Function "migrateCategories()" not implemented!');
    }

    public function migrateContent($start, $limit) {
        // must be implemented
        JError::raiseError('003', 'Function "migrateContent()" not implemented!');
    }

    public function migrateUsers($start, $limit) {
        // must be implemented
        JError::raiseError('003', 'Function "migrateUsers()" not implemented!');
    }

    public function migrateTags($start, $limit) {
        // must be implemented
        JError::raiseError('003', 'Function "migrateTags()" not implemented!');
    }
    
    public function migrateComments($start, $limit) {
        // must be implemented
        JError::raiseError('003', 'Function "migrateComments()" not implemented!');
    }

    public function tablesExist() {
        // must be implemented
        JError::raiseError('003', 'Function "tablesExist()" not implemented!');
    }
    
    public function getNumberOfContents() {
        // must be implemented
        JError::raiseError('003', 'Function "getNumberOfContents()" not implemented!');
    }
    
    public function getNumberOfCategories() {
        // must be implemented
        JError::raiseError('003', 'Function "getNumberOfCategories()" not implemented!');
    }
    
    public function getNumberOfTags() {
        // must be implemented
        JError::raiseError('003', 'Function "getNumberOfTags()" not implemented!');
    }
    
    public function getNumberOfUsers() {
        // must be implemented
        JError::raiseError('003', 'Function "getNumberOfUsers()" not implemented!');
    }
    
    public function getNumberOfComments() {
        // must be implemented
        JError::raiseError('003', 'Function "getNumberOfComments()" not implemented!');
    }
    
    public function deleteContent() {
        $query = $this->db->getQuery(true);
        $query->delete();
        $query->from('#__k2_items');
        $this->db->setQuery($query);
        return $ret = $this->db->query();
    }
    
    public function deleteCategories() {
        $query = $this->db->getQuery(true);
        $query->delete();
        $query->from('#__k2_categories');
        $this->db->setQuery($query);
        return $this->db->query();
    }
    
    public function deleteTags() {
        $query = $this->db->getQuery(true);
        $query->delete();
        $query->from('#__k2_tags');
        $this->db->setQuery($query);
        $this->db->query();
        $query = $this->db->getQuery(true);
        $query->delete();
        $query->from('#__k2_tags_xref');
        $this->db->setQuery($query);
        return $this->db->query();
    }
    
    
    /*return true on success*/
    public function insertArticle($article) {
        $ret = false;
        $query = $this->db->getQuery(true);
        
        $query->insert('#__k2_items')->
                columns("`title`, `alias`, `catid`, `published`, `introtext`,
                      `fulltext`, `video`, `gallery`, `extra_fields`, `extra_fields_search`, `created`, 
                      `created_by`, `created_by_alias`, `checked_out`, `checked_out_time`, `modified`, 
                      `modified_by`, `publish_up`, `publish_down`, `trash`, `access`, `ordering`, 
                      `featured`, `featured_ordering`, `image_caption`, `image_credits`, `video_caption`, 
                      `video_credits`, `hits`, `params`, `metadesc`, `metadata`, `metakey`, `plugins`, `language`")->
                values($this->db->quote($article->title) . "," . $this->db->quote($article->alias) . "," . $article->catid . ",1," . $this->db->quote($article->introtext) . ",
                      " . $this->db->quote($article->fulltext) . ",'','','[]',''," . $this->db->quote($article->created) . "," . $this->db->quote($article->created_by) . ",
                      '',0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0," . $this->db->quote($article->created) . ",'0000-00-00 00:00:00',
                      0,1,1,1,1,'','','','',0,'" . $this->item_params . "','','','','','*'");
        $this->db->setQuery($query);
        $ret = $this->db->query();
        
        $joomla_id = $this->db->insertid();
        
        $query = $this->db->getQuery(true);
        $query->insert('`#__cmigrator_articles`');
        $query->set('`cms_id`=' . $article->id);
        $query->set('`joomla_id`=' . $joomla_id);
        $this->db->setQuery($query);
        $this->db->query();
        
        return $this->db->insertid();
    }
    
    public function insertCategory($category) {
        $query = $this->db->getQuery(true);

        $query->insert('#__k2_categories');
        $query->set('name=' . $this->db->quote($category->name));
        $query->set('alias=' . $this->db->quote($category->alias));
        $query->set('description=' . $this->db->quote($category->description));
        $query->set('parent=' . $category->parent);
        $query->set('extraFieldsGroup=""');
        $query->set('published=1');
        $query->set('access=1');
        $query->set('ordering=1');
        $query->set('image=""');
        $query->set('params=' . $this->db->quote($this->cat_params));
        $query->set('trash=0');
        $query->set('plugins=""');
        $query->set('language="*"');
        $this->db->setQuery($query);
        $this->db->query();
        
        return $this->db->insertid();
    }
    
    public function insertTag($tag) {
        $query = $this->db->getQuery(true);
        $query->insert('`#__k2_tags_xref`');
        $query->set('`tagID`=' . $this->db->quote($tag->tagID));
        $query->set('`itemID`=' . $this->db->quote($tag->itemID));
        $this->db->setQuery($query);
        return $this->db->query();
    }
    
    public function insertComments($comments) {
        foreach ($comments as $comment) {
            if(!$this->insertComment($comment)) {
                return false;
            }
        }
        return true;
    }
    
    public function noCategories() {
        $user = JFactory::getUser();
        $category = new JObject();
        $category->name = "Import data";
        $cat_temp = $this->checkCategoryExists($category->name);
        if($cat_temp) {
            $category->name = $cat_temp;
        }
        $category->alias = JFilterOutput::stringURLSafe($category->name);
        $category->parent = 0;
        $category->author = $user->id;
        $category->description = "Imported data for no categories selection.";
        $id = $this->insertCategory($category);
        
        $query = $this->db->getQuery(true);
        $query->insert('#__cmigrator_categories');
        $query->values($this->db->quote($id) . ',' . $this->db->quote($id) . ',' . $category->parent . ', "no-categories"');
        $this->db->setQuery($query);
        $this->db->query();
        return $id;
    }
    
    public function checkArticleExists($name, &$counter = 0) {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__k2_items');
        if($counter != 0) {
            $query->where('`title`='.$this->db->quote($name.' (CMigrator copy '.$counter.')'));
        } else {
            $query->where('`title`='.$this->db->quote($name));
        }
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        
        if($result) {
            $counter++;
            $this->checkArticleExists($name, $counter);
            return $name.' (CMigrator copy '. $counter.')';
        } else {
            return null;
        }
    }
    
    public function checkCategoryExists($name, &$counter = 0) {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__k2_categories');
        if($counter != 0) {
            $query->where('`name`='.$this->db->quote($name.' (CMigrator copy '.$counter.')'));
        } else {
            $query->where('`name`='.$this->db->quote($name));
        }
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        
        if($result) {
            $counter++;
            $this->checkArticleExists($name, $counter);
            return $name.' (CMigrator copy '. $counter.')';
        } else {
            return null;
        }
    }
    
    public function checkTagExists($tag) {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__k2_tags');
        $query->where('`name`='.$this->db->quote($tag->name));
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        
        return $result;
    }
}