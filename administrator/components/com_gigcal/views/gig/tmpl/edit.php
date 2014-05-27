<?php
/**
 * @version    $Id: edit.php 20549 2011-02-04 15:01:51Z chdemko $
 * @package    Joomla.Administrator
 * @subpackage  com_gigcal
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
  Joomla.submitbutton = function(task)
  {
    if (task == 'gig.cancel' || document.formvalidator.isValid(document.id('gig-form'))) {
      <?php echo $this->form->getField('info')->save(); ?>
      Joomla.submitform(task, document.getElementById('gig-form'));
    }
    else {
      alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
    }
  }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_gigcal&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="gig-form" class="form-validate">
  <div class="width-60 fltlft">
    <fieldset class="adminform">
      <legend><?php echo empty($this->item->id) ? JText::_('COM_GIGCAL_NEW_GIG') : JText::sprintf('COM_GIGCAL_EDIT_GIG', $this->item->id); ?></legend>
      <ul class="adminformlist">

      <li><?php echo $this->form->getLabel('gigdate'); ?>
      <?php echo $this->form->getInput('gigdate'); ?></li>

      <li><?php echo $this->form->getLabel('gigtitle'); ?>
      <?php echo $this->form->getInput('gigtitle'); ?></li>

      <li><?php echo $this->form->getLabel('band_id'); ?>
      <?php echo $this->form->getInput('band_id'); ?></li>

      <li><?php echo $this->form->getLabel('venue_id'); ?>
      <?php echo $this->form->getInput('venue_id'); ?></li>

      <li><?php echo $this->form->getLabel('covercharge'); ?>
      <?php echo $this->form->getInput('covercharge'); ?></li>

      <li><?php echo $this->form->getLabel('saleslink'); ?>
      <?php echo $this->form->getInput('saleslink'); ?></li>

<!--      <li><?php echo $this->form->getLabel('catid'); ?>
      <?php echo $this->form->getInput('catid'); ?></li>
-->
      <li><?php echo $this->form->getLabel('featured'); ?>
      <?php echo $this->form->getInput('featured'); ?></li>

      <li><?php echo $this->form->getLabel('published'); ?>
      <?php echo $this->form->getInput('published'); ?></li>      

      <li><?php echo $this->form->getLabel('id'); ?>
      <?php echo $this->form->getInput('id'); ?></li>
      </ul>

      <?php echo $this->form->getLabel('info'); ?>
      <div class="clr"></div>
      <?php echo $this->form->getInput('info'); ?>

    </fieldset>
  </div>
  <div class="width-40 fltrt">
    <?php echo JHtml::_('sliders.start','newsfeed-sliders-'.$this->item->id, array('useCookie'=>1)); ?>

    <?php echo JHtml::_('sliders.panel',JText::_('JGLOBAL_FIELDSET_PUBLISHING'), 'publishing-details'); ?>

    <fieldset class="panelform">
      <ul class="adminformlist">
        <li><?php echo $this->form->getLabel('created_by'); ?>
        <?php echo $this->form->getInput('created_by'); ?></li>

        <li><?php echo $this->form->getLabel('created_by_alias'); ?>
        <?php echo $this->form->getInput('created_by_alias'); ?></li>

        <li><?php echo $this->form->getLabel('created'); ?>
        <?php echo $this->form->getInput('created'); ?></li>

        <?php if ($this->item->modified_by) : ?>
          <li><?php echo $this->form->getLabel('modified_by'); ?>
          <?php echo $this->form->getInput('modified_by'); ?></li>

          <li><?php echo $this->form->getLabel('modified'); ?>
          <?php echo $this->form->getInput('modified'); ?></li>
        <?php endif; ?>
      </ul>
    </fieldset>

    <?php echo JHtml::_('sliders.end'); ?>

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
  <div class="clr"></div>
</form>
