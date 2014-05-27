<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

require JPATH_ADMINISTRATOR.'/components/com_gigcal/entity_decode.php';
 
/**
 * Import Controller
 */
class GigCalControllerImport extends JControllerForm
{
  private $bandlist = array();
  private $venuelist = array();
  private $giglist = array();
  private $localTimeZone;
  private $dataTimeZone;

  public function data() 
  {
    $clearall = JRequest::getCmd('clearall') == 'on';

    JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_gigcal/tables');

    $this->localTimeZone = new DateTimeZone(date_default_timezone_get());
    $this->dataTimeZone = new DateTimeZone($_REQUEST['timezone']);

    if (!$clearall)
    {
      // Initialize the name maps
      $db =& JFactory::getDBO();
      $db->setQuery('SELECT id, bandname FROM #__gigcal_bands');
      $this->bandlist = $db->loadAssocList('bandname');
      $db->setQuery('SELECT id, venuename FROM #__gigcal_venues');
      $this->venuelist = $db->loadAssocList('venuename');
      $db->setQuery('SELECT id, gigname FROM #__gigcal_gigs_import');
      $this->giglist = $db->loadAssocList('gigname');
    }
    $filename = $_FILES['file1']['tmp_name'];
    $file = file_get_contents($filename);
    $xmlstring = entity_decode($file);
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($xmlstring);    
    if ($xml)
    {
      $version = $xml->version;
      if ($version == '1.0' || $version == '1.0.4' || $version == '1.1')
        $this->_ImportData1($xml, $clearall);
      else
        JError::raiseError(500, 'Cannot import data in this ('.$version.') format');
    }
    else
    {
      echo 'XML data load error';
      foreach(libxml_get_errors() as $error)
        echo '<br />&nbsp;&nbsp;&nbsp;'.$error->message;
    }
  }

  public function settings() 
  {
    JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_gigcal/tables');

    $filename = $_FILES['file2']['tmp_name'];
    $file = file_get_contents($filename);
    $xmlstring = entity_decode($file);
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($xmlstring);    
    if ($xml)
    {
      $version = $xml->version;
      if ($version == '1.0' || $version == '1.0.4' || $version == '1.1')
        $this->_ImportSettings1($xml);
      else
        JError::raiseError(500, 'Cannot import data in this ('.$version.') format');
    }
    else
    {
      echo 'XML data load error';
      foreach(libxml_get_errors() as $error)
        echo '<br />&nbsp;&nbsp;&nbsp;'.$error->message;
    }
  }

  private function _ImportData1($xml, $clearall)
  {
    echo 'Importing gigcal '.$xml->version.' format data<br/><br/>';

    $this->_import1Bands($xml->gigcal_bands, $clearall);
    $this->_import1Venues($xml->gigcal_venues, $clearall);
    $this->_import1Gigs($xml->gigcal_gigs, $clearall);
  }

  private function _ImportSettings1($xml)
  {
    echo 'Importing gigcal '.$xml->version.' format settings<br/><br/>';

    $this->_importFields1($xml->gigcal_list_fields, 'list');
    $this->_importFields1($xml->gigcal_alist_fields, 'alist');
    $this->_importFields1($xml->gigcal_cal_fields, 'cal');
    $this->_importFields1($xml->gigcal_menu_fields, 'menu');
    $this->_importFields1($xml->gigcal_upcom_fields, 'upcom');
    $this->_importConfig1($xml->gigcal_config);
  }

  private function _Import1Bands($bands, $clearall)
  {
    $numbandsimported = 0;
    $bandtable =& JTable::getInstance('Band', 'GigCalTable');
    foreach ($bands->row as $band)
    {
      if ($clearall)
      {
	echo 'Clearing all bands<br/>';

        $db =& JFactory::getDBO();
        $db->setQuery('DELETE FROM #__gigcal_bands');
        $db->query();
        $db->setQuery('ALTER TABLE #__gigcal_bands AUTO_INCREMENT=1');
        $db->query();
        $clearall = false;
      }
      $bandtable->reset();
      if (array_key_exists((string)$band->bandname, $this->bandlist))
      {
        // Update the existing band record
        echo 'Updating band : '.$band->bandname.'<br/>';
        $bandtable->load($this->bandlist[(string)$band->bandname]['id']);
      }
      else
      {
        // import a new band record
        echo 'Importing band : '.(string)$band->bandname.'<br/>';
        $bandtable->set('id', null);
        $bandtable->set('bandname', (string)$band->bandname);
        $bandtable->set('published', 1);
      }
      $bandtable->set('website', (string)$band->website);
      $bandtable->set('contactname', (string)$band->contactname);
      $bandtable->set('contactphone', (string)$band->contactphone);
      $bandtable->set('contactemail', (string)$band->contactemail);
      $bandtable->set('city', (string)$band->city);
      $bandtable->set('state', (string)$band->state);
      $bandtable->set('notes', (string)$band->notes);
      if ($bandtable->store())
      {
        $numbandsimported++;
        $this->bandlist[(string)$band->bandname] = array('id'=>$bandtable->get('id'), 'bandname'=>$bandtable->get('bandname'));
      }
      else
        echo '   ERROR: '.$bandtable->getError().'<br/>';
    }
    echo $numbandsimported.' band(s) imported/updated<br/></br>';
  }

  private function _Import1Venues($venues, $clearall)
  {
    $numvenuesimported = 0;
    $venuetable =& JTable::getInstance('Venue', 'GigCalTable');
    foreach ($venues->row as $venue)
    {
      if ($clearall)
      {
	echo 'Clearing all venues<br/>';

        $db =& JFactory::getDBO();
        $db->setQuery('DELETE FROM #__gigcal_venues');
        $db->query();
        $db->setQuery('ALTER TABLE #__gigcal_venues AUTO_INCREMENT=1');
        $db->query();
        $clearall = false;
      }
      $venuetable->reset();
      if (array_key_exists((string)$venue->venuename, $this->venuelist))
      {
        // Update the existing venue record
        echo 'Updating venue : '.$venue->venuename.'<br/>';
        $venuetable->load($this->venuelist[(string)$venue->venuename]['id']);
      }
      else
      {
        // import a new venue record
        echo 'Importing venue : '.(string)$venue->venuename.'<br/>';
        $venuetable->set('id', null);
        $venuetable->set('venuename', (string)$venue->venuename);
        $venuetable->set('published', 1);
      }
      $venuetable->set('address1', (string)$venue->address1);
      $venuetable->set('address2', (string)$venue->address2);
      $venuetable->set('city', (string)$venue->city);
      $venuetable->set('state', (string)$venue->state);
      $venuetable->set('zip', (string)$venue->zip);
      $venuetable->set('country', (string)$venue->country);
      $venuetable->set('website', (string)$venue->website);
      $venuetable->set('phone', (string)$venue->phone);
      $venuetable->set('fax', (string)$venue->fax);
      $venuetable->set('contactname', (string)$venue->contactname);
      $venuetable->set('contactphone', (string)$venue->contactphone);
      $venuetable->set('contactemail', (string)$venue->contactemail);
      $venuetable->set('info', (string)$venue->info);
      if ($venuetable->store())
      {
        $numvenuesimported++;
        $this->venuelist[(string)$venue->venuename] = array('id'=>$venuetable->get('id'), 'venuename'=>$venuetable->get('venuename'));
      }
      else
        echo '   ERROR: '.$venuetable->getError().'<br/>';
    }
    echo $numvenuesimported.' venue(s) imported/updated<br/></br>';
  }

  private function _Import1Gigs($gigs, $clearall)
  {
    $numgigsimported = 0;
    $gigtable =& JTable::getInstance('Gig', 'GigCalTable');
    foreach ($gigs->row as $gig)
    {
      if ($clearall)
      {
        echo 'Clearing all gigs<br/>';

        $db =& JFactory::getDBO();
        $db->setQuery('DELETE FROM #__gigcal_gigs');
        $db->query();
        $db->setQuery('ALTER TABLE #__gigcal_gigs AUTO_INCREMENT=1');
        $db->query();
        $clearall=false;
      }
      $gigtable->reset();
      $band_id = $this->bandlist[(string)$gig->bandname]['id'];
      $venue_id = $this->venuelist[(string)$gig->venuename]['id'];
      $name = $band_id.'|'.$venue_id.'|'.(int)$gig->gigdate;
      if (array_key_exists($name, $this->giglist))
      {
        // Update the existing gig record
        echo 'Updating gig : '.$name.'<br/>';
        $gigtable->load($this->giglist[$name]['id']);
      }
      else
      {
        // import a new gig record
        echo 'Importing gig : '.$name.'<br/>';
        $gigtable->set('id', null);
        $gigtable->set('published', 1);
      }

      // Calculate the time offset asuming the data is being loaded that was prviously saved in a different time zone
      // This makes the final display show the same as it did on the original data source
      $localDateTime = new DateTime(gmdate('Y-m-d H:i:s', (int)$gig->gigdate));
      $dataDateTime = new DateTime(gmdate('Y-m-d H:i:s', (int)$gig->gigdate), $this->dataTimeZone);
      $localOffset = $this->dataTimeZone->getOffset($localDateTime);
      $dataOffset = $this->localTimeZone->getOffset($dataDateTime);
      $offset = $localOffset - $dataOffset;

      $gigtable->set('gigdate', date('Y-m-d H:i', (int)$gig->gigdate + $offset));

      $gigtable->set('band_id', $band_id);
      $gigtable->set('venue_id', $venue_id);
      $gigtable->set('gigtitle', (string)$gig->gigtitle);
      $gigtable->set('covercharge', (string)$gig->covercharge);
      $gigtable->set('saleslink', (string)$gig->saleslink);
      $gigtable->set('info', (string)$gig->info);
      if ($gigtable->store())
      {
        $numgigsimported++;
        $this->giglist[$name] = array('id'=>$gigtable->get('id'), 'name'=>$name);
      }
      else
        echo '   ERROR: '.$gigtable->getError().'<br/>';
    }
    echo $numgigsimported.' gig(s) imported/updated<br/></br>';
  }

  private function _ImportFields1($fields, $name)
  {
    $clearall = true;
    $numfieldsimported = 0;
    $table =& JTable::getInstance($name.'_Fields', 'GigCalTable');
    foreach ($fields->row as $field)
    {
      if ($clearall)
      {
        $db =& JFactory::getDBO();
        $db->setQuery('DELETE FROM #__gigcal_'.$name.'_fields');
        $db->query();
        $db->setQuery('ALTER TABLE #__gigcal_'.$name.'_fields AUTO_INCREMENT = 1');
        $db->query();
        $clearall = false;
      }
      $table->reset();
      // import a new record
      echo 'Importing '.$name.'_field : '.(string)$field->fieldname.'<br/>';
      $table->set('id', null);
      $table->set('fieldname', (string)$field->fieldname);
      $table->set('ordering', (string)$field->ordering);
      $table->set('published', (string)$field->published);
      if ($table->store())
        $numfieldsimported++; 
      else
        echo '   ERROR: '.$table->getError().'<br/>';
    }
    echo $numfieldsimported.' '.$name.'_field(s) imported<br/></br>';
  }

  private function _ImportConfig1($fields)
  {
    $numfieldsimported = 0;
    $table =& JTable::getInstance('Config', 'GigCalTable');
    foreach ($fields->row as $field)
    {
      $table->reset();
      $tablefields = $table->getFields();

      foreach ($field as $key=>$data)
        if (in_array(strtolower($key), array_map('strtolower', array_keys($tablefields))))
          $table->set($key, (string)$data);
        else
          echo 'Key '.$key.' not recognised - ignoring<br/>';

      if ($table->store())
        $numfieldsimported++; 
      else
        echo '   ERROR: '.$table->getError().'<br/>';
    }
    echo $numfieldsimported.' config(s) imported<br/></br>';
  }


}


