<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//echo 'items[<pre>'.print_r($this->items,true).'</pre>]';
$url = JRoute::_('index.php?option=com_gigcal&view=config&fields='.$fields);

function GetCfgName($name)
{
  $names = array ("Calendar View"=>"cal", 
		"List View"=>"list", 
		"Archived List View"=>"alist", 
		"RSS Feeds"=>"rss", 
		"Bands List"=>"bandslist", 
		"Venues List"=>"venueslist");

  return 'menu_'.$names[$name];
}

?>

<form action="<?php echo $url ?>" method="post" name="adminForm" id="adminForm">
  <div class="clr"> </div>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="1%">
          <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
        </th>
        <th width="60">
          <?php echo JText::_('JSTATUS'); ?>
        </th>
        <th>
          <?php echo JText::_('JGRID_HEADING_ORDERING'); ?>
        </th>
        <th width="100" class="left">
          <?php echo JText::_('COM_GIGCAL_FIELD_NAME_LABEL'); ?>
        </th>
        <th width="*" class="left">
          <?php echo JText::_('COM_GIGCAL_TABLE_HEADER_LABEL'); ?>
        </th>
        <th width="50" class="nowrap">
          <?php echo JText::_('JGRID_HEADING_ID'); ?>
        </th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($this->items as $i => $item) :
      ?>
      <tr class="row<?php echo $i % 2; ?>">
        <td class="center">
          <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td class="center">
          <?php echo JHtml::_('jgrid.published', $item->published, $i, 'config.'); ?>
        </td>
        <td class="order" style="width:60px;">
          <span><?php echo $this->pagination->orderUpIcon($i, true, 'config.orderup', 'JLIB_HTML_MOVE_UP'); ?></span>
          <span><?php echo $this->pagination->orderDownIcon($i, count($this->items), true, 'config.orderdown', 'JLIB_HTML_MOVE_DOWN'); ?></span>
          <?php //echo $item->ordering; ?>
        </td>
        <td class="left">
          <?php echo $item->fieldname; ?>
        </td>

        <td class="left">
        <?php echo '<input type="text" name="'.GetCfgName($item->fieldname).'" size="20" value="'.$this->config[GetCfgName($item->fieldname)].'">';?>
        </td>

        <td class="center">
          <?php echo (int) $item->id; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
<table width="680">
 <tr>
  <td></td>
  <td><b>Other Menu Settings</b></td>
 </tr>

 <tr>
  <td><div align="right"><b>Menu Delimiter</b></div></td>
  <td>
   <input type="text" name="menu_delim" size="10" value="<?php echo $this->config['menu_delim']; ?>">
  </td>
 </tr>
 
<tr>
  <td valign="top"><div align="right"><b>Show menu on details pages</b></div></td>
  <td>
   <input type="hidden" name="menu_details" value="0" />
   <input type="checkbox" value="1" name="menu_details" <?php if($this->config['menu_details']==1) echo 'checked'; ?>>
  </td>
 </tr>

 <tr>
  <td valign="top"><div align="right"><b>Show menu on top</b></div></td>
  <td>
   <input type="hidden" name="menu_top" value="0" />
   <input type="checkbox" value="1" name="menu_top" <?php if($this->config['menu_top']==1) echo 'checked'; ?>>
  </td>
 </tr>

 <tr>
  <td valign="top"><div align="right"><b>Show menu on bottom</b></div></td>
  <td>
   <input type="hidden" name="menu_bottom" value="0" />
   <input type="checkbox" value="1" name="menu_bottom" <?php if($this->config['menu_bottom']==1) echo 'checked'; ?>>
  </td>
 </tr>
</table>
</form>

