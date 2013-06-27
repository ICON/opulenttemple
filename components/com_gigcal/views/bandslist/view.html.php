<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the GigCal Band Component
 */
class GigCalViewBandslist extends JView
{
  public $config;

  // Overwriting JView display method
  function display($tpl = null) 
  {
    $app =& JFactory::getApplication();
    
    // get/set limits for pagination
    $this->limit = $app->getUserStateFromRequest("global.list.limit", 'limit', $app->getCfg('list_limit'), 'int');
    $this->limitstart = JRequest::getInt('limitstart', 0);
    $this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);

    if(isset($_POST['keywordfilter'])) 
      $_SESSION['keywordfilter'] = $_POST['keywordfilter'];
  
    $keywordfilter = isset($_SESSION['keywordfilter'])?$_SESSION['keywordfilter']:'';

    if(isset($_POST['bandfilter']))
      $_SESSION['bandfilter'] = ($_POST['bandfilter'] == "0") ? "" : $_POST['bandfilter'];

    // Load common data
    $db =& JFactory::GetDBO();
    $db->setQuery('SELECT * FROM #__gigcal_config WHERE active=1');
    $this->config = $db->LoadAssoc();

    $db->setQuery('SELECT fieldname FROM #__gigcal_menu_fields WHERE published=1 ORDER BY ordering');
    $this->menus = $db->loadAssocList();

    $id = JRequest::getInt('id', JRequest::getInt('band_id', 0));
    $where = '';
    if ($id > 0)
      $where = ' AND id='.$id;
    else
    {
      if(isset($_SESSION['bandfilter']) && $_SESSION['bandfilter'] != "") 
        $where .= " AND id=".$_SESSION['bandfilter'];
  
      if($keywordfilter != '')
        $where .= " AND notes like '%".$keywordfilter."%'";
    }
    $query = 'SELECT id, bandname, featured, city, state, contactname, contactemail, contactphone, website, notes '
                  .'FROM #__gigcal_bands WHERE published=1'.$where.' ORDER BY bandname';
//error_log($query);
    $db->setQuery($query, $this->limitstart, $this->limit);
    $this->bands = $db->loadAssocList();

    $db->setQuery('SELECT FOUND_ROWS()');
    
    jimport('joomla.html.pagination');
    $this->pageNav = new JPagination($db->loadResult(), $this->limitstart, $this->limit);

    $this->filterControlHTML='';
//    if(count($this->bands) > 1)
    {
      $this->filterControlHTML = "\n".'<form action="index.php?option=com_gigcal&amp;task=bandslist&amp;limit='.$this->limit.
					'&amp;limitstart='.$this->limitstart.'" method="post" name="filterForm" id="filterForm" class="filterForm">'."\n".
      					'<table>'."\n".'  <tbody>'."\n".'    <tr>'."\n".'      <td>Filter: </td>'."\n".
					'      <td><input type="text" name="keywordfilter" value="'.$keywordfilter.
					'" class="inputbox" onChange="submit()" /></td>'."\n".
					'      <td><select name="bandfilter" size="1" onChange="submit()">'."\n".
					'    <option value="0">- Select band -</option>'."\n";

      $db->setQuery('SELECT id, bandname FROM #__gigcal_bands WHERE published=1 ORDER BY bandname');
      foreach ($db->loadAssocList() as $band) {
        $this->filterControlHTML .= '    <option value="'.$band['id'].'"';
        if(isset($_SESSION['bandfilter']) && ($band['id'] == $_SESSION['bandfilter']))
          $this->filterControlHTML .= ' "selected" ';
        $this->filterControlHTML .= '>'.$band['bandname'].'</option>'."\n";
      }
      $this->filterControlHTML .= '</select></td>'."\n".'    </tr>'."\n".'  </tbody>'."\n".'</table>'."\n".'</form>';
    }

    // Check for errors.
    if (count($errors = $this->get('Errors'))) 
    {
      JError::raiseError(500, implode('<br />', $errors));
      return false;
    }

    // Display the view
    parent::display($tpl);
  }
}

