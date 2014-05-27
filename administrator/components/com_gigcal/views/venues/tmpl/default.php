<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('script','system/multiselect.js', false, true);

$user    = JFactory::getUser();
$userId    = $user->get('id');
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
//$canOrder  = $user->authorise('core.edit.state', 'com_gigcal.category');
//$saveOrder  = $listOrder == 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_gigcal&view=venues'); ?>" method="post" name="adminForm" id="adminForm">
  <fieldset id="filter-bar">
    <div class="filter-search fltlft">
      <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
      <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_GIGCAL_SEARCH_IN_TITLE'); ?>" />
      <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
      <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
    </div>
    <div class="filter-select fltrt">
      <select name="filter_published" class="inputbox" onchange="this.form.submit()">
        <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
        <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
      </select>
    </div>
  </fieldset>
  <div class="clr"> </div>

  <table class="adminlist">
    <thead>
      <tr>
        <th width="1%">
          <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort',  'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort',  'JFEATURED', 'a.featured', $listDirn, $listOrder, NULL, 'desc'); ?>
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort',  'COM_GIGCAL_DEFAULT', 'a.thedefault', $listDirn, $listOrder, NULL, 'desc'); ?>
        </th>
        <th class="left">
          <?php echo JHtml::_('grid.sort',  'COM_GIGCAL_VENUE_NAME_LABEL', 'a.venuename', $listDirn, $listOrder); ?>
        </th>
        <th width="20%">
          <?php echo JHtml::_('grid.sort',  'COM_GIGCAL_VENUE_CITY_LABEL', 'a.city', $listDirn, $listOrder); ?>
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort',  'COM_GIGCAL_VENUE_STATE_LABEL', 'a.state', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          Gigs Played<br/>Last Gig
        </th>
        <th width="1%" class="nowrap">
          <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
        </th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="10">
          <?php echo $this->pagination->getListFooter(); ?>
        </td>
      </tr>
    </tfoot>
    <tbody>
    <?php foreach ($this->items as $i => $item) :
      $ordering  = ($listOrder == 'a.ordering');
      $canCreate  = $user->authorise('core.create',  'com_gigcal.venue');
      $canEdit  = $user->authorise('core.edit',    'com_gigcal.venue');
      $canCheckin  = $user->authorise('core.manage',  'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
      $canChange  = $user->authorise('core.edit.state',  'com_gigcal.venue') && $canCheckin;
      $numGigs  = '-';
      $lastGig  = '';
      if (array_key_exists($item->id, $this->venueGigSummary))
      {
        $summary = $this->venueGigSummary[$item->id];
        $numGigs = $summary['numGigs'];
        $lastGig = '<a href="'.JRoute::_('index.php?option=com_gigcal&view=gigs&filter_venue_id='.(int) $item->id).'">'.
			date("Y, M d", $summary['lastGig']).'</a>';
      }
      ?>
      <tr class="row<?php echo $i % 2; ?>">
        <td class="center">
          <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td class="center">
          <?php echo JHtml::_('jgrid.published', $item->published, $i, 'venues.', $canChange); ?>
        </td>
        <td class="center">
          <?php echo JHtml::_('venue.featured', $item->featured, $i, $canChange); ?>
        </td>
        <td class="center">
          <?php echo JHtml::_('jgrid.isdefault', $item->thedefault!='0', $i, 'venues.', $canChange && $item->thedefault!='1');?>
        </td>
        <td class="left">
          <?php if ($item->checked_out) : ?>
            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'venues.', $canCheckin); ?>
          <?php endif; ?>
          <?php if ($canEdit) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_gigcal&task=venue.edit&id='.(int) $item->id); ?>">
              <?php echo $this->escape($item->venuename); ?></a>
          <?php else : ?>
              <?php echo $this->escape($item->venuename); ?>
          <?php endif; ?>
          <p class="smallsub">
            <?php //echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></p>
        </td>
        <td class="left">
          <?php echo $this->escape($item->city); ?>
        </td>
        <td class="center">
          <?php echo $this->escape($item->state); ?>
        </td>
        <td class="center">
          <?php echo $numGigs; ?><br/><?php echo $lastGig; ?>
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
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>
