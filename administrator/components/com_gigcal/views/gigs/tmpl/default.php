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
$jconfig = new JConfig();
?>

<form action="<?php echo JRoute::_('index.php?option=com_gigcal&view=gigs'); ?>" method="post" name="adminForm" id="adminForm">
  <fieldset id="filter-bar">
    <div class="filter-search fltlft">
      <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
      <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_GIGCAL_SEARCH_IN_TITLE'); ?>" />
      <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
      <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
    </div>
    <div class="filter-select fltrt">
      <select name="filter_band_id" class="inputbox" onchange="this.form.submit()">
        <option value=""><?php echo JText::_('COM_GIGCAL_SELECT_BAND_NAME');?></option>
        <?php echo JHtml::_('select.options', $this->bandNameOptions, 'id', 'bandname', $this->state->get('filter.band_id'), true);?>
      </select>

      <select name="filter_venue_id" class="inputbox" onchange="this.form.submit()">
        <option value=""><?php echo JText::_('COM_GIGCAL_SELECT_VENUE_NAME');?></option>
        <?php echo JHtml::_('select.options', $this->venueNameOptions, 'id', 'venuename', $this->state->get('filter.venue_id'), true);?>
      </select>

      <select name="filter_published" class="inputbox" onchange="this.form.submit()">
        <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
        <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
      </select>
<!--
      <select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
        <option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
        <?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_gigcal'), 'value', 'text', $this->state->get('filter.category_id'));?>
      </select>
-->    </div>
    <div class="filter-select fltrt">
      <table>
        <tr>
          <td><input type="radio" name="filter_scope" value="-1" style="margin:0px 0px;" onchange="this.form.submit()"
             <?php echo $this->state->get('filter.scope', -1) == -1 ? 'checked="checked"' : '' ?>></td>
          <td><input type="radio" name="filter_scope" value="0" style="margin:0px 0px;" onchange="this.form.submit()"
             <?php echo $this->state->get('filter.scope', -1) == 0 ? 'checked="checked"' : '' ?>></td>
          <td><input type="radio" name="filter_scope" value="1" style="margin:0px 0px;" onchange="this.form.submit()"
             <?php echo $this->state->get('filter.scope', -1) == 1 ? 'checked="checked"' : '' ?>></td>
        </tr>
        <tr>
          <td style="margin:0px 0px;">past</td>
          <td style="margin:0px 0px;">both</td>
          <td style="margin:0px 0px;">future</td>
        </tr>
      </table>
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
        <th class="left" colspan="2">
          <?php echo JHtml::_('grid.sort',  'COM_GIGCAL_GIG_DATE_LABEL', 'a.gigdate', $listDirn, $listOrder); ?>
        </th>
        <th class="left">
          <?php echo JHtml::_('grid.sort',  'COM_GIGCAL_GIG_TITLE_LABEL', 'a.gigtitle', $listDirn, $listOrder); ?>
        </th>
        <th class="left">
          <?php echo JHtml::_('grid.sort',  'COM_GIGCAL_GIG_BAND_LABEL', 'bandname', $listDirn, $listOrder); ?>
        </th>
        <th class="left">
          <?php echo JHtml::_('grid.sort',  'COM_GIGCAL_GIG_VENUE_LABEL', 'venuename', $listDirn, $listOrder); ?>
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
//      $item->cat_link  = JRoute::_('index.php?option=com_categories&extension=com_gigcal&task=edit&type=other&cid[]='. $item->catid);
      $canCreate  = $user->authorise('core.create',  'com_gigcal.gig');
      $canEditGig  = $user->authorise('core.edit',    'com_gigcal.gig');
      $canEditBand  = $user->authorise('core.edit',    'com_gigcal.band');
      $canEditVenue  = $user->authorise('core.edit',    'com_gigcal.venue');
      $canCheckin  = $user->authorise('core.manage',  'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
      $canChange  = $user->authorise('core.edit.state',  'com_gigcal.gig') && $canCheckin;
      ?>
      <tr class="row<?php echo $i % 2; ?>">
        <td class="center">
          <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td class="center">
          <?php echo JHtml::_('jgrid.published', $item->published, $i, 'gigs.', $canChange); ?>
        </td>
        <td class="center">
          <?php echo JHtml::_('gig.featured', $item->featured, $i, $canChange); ?>
        </td>
        <td width="110" class="left">
          <?php 
            if ($item->checked_out) 
            {
              echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'gigs.', $canCheckin); 
              date_default_timezone_set($jconfig->offset); // restore default timezone
            }
          ?>
          <?php if ($canEditGig) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_gigcal&task=gig.edit&id='.(int) $item->id); ?>">
              <?php echo date('Y M d (D)', $item->gigdate); ?></a>
          <?php else : ?>
              <?php echo date('Y M d (D)', $item->gigdate); ?>
          <?php endif; ?>
          <p class="smallsub">
            <?php //echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></p>
        </td>
        <td width="100" class="left">
          <?php if ($canEditGig) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_gigcal&task=gig.edit&id='.(int) $item->id); ?>">
              <?php echo date('g:i a', $item->gigdate); ?></a>
          <?php else : ?>
              <?php echo date('g:i a', $item->gigdate); ?>
          <?php endif; ?>
        </td>
        <td class="left">
          <?php if ($canEditGig) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_gigcal&task=gig.edit&id='.(int) $item->id); ?>">
              <?php echo $this->escape($item->gigtitle); ?></a>
          <?php else : ?>
              <?php echo $this->escape($item->gigtitle); ?>
          <?php endif; ?>
        </td>
        <td class="left">
          <?php if ($canEditBand) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_gigcal&task=band.edit&id='.(int) $item->band_id); ?>">
              <?php echo $item->bandname; ?></a>
          <?php else : ?>
              <?php echo $item->bandname; ?>
          <?php endif; ?>
        </td>
        <td class="left">
          <?php if ($canEditVenue) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_gigcal&task=venue.edit&id='.(int) $item->venue_id); ?>">
              <?php echo $item->venuename; ?></a>
          <?php else : ?>
              <?php echo $item->venuename; ?>
          <?php endif; ?>
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
