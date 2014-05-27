<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	Submissions
* @copyright	G. Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU GPL v3 or later
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');



?>
<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>
	<?php
	$compat = '1.6';
	$version = new JVersion();
	if ($version->isCompatible('3.0'))
		$compat = '3.0';
	?>
	<div>

		<!-- BRICK : toolbar_sing -->
		<?php echo $this->renderToolbar();?>
	</div>
	<?php if ($compat == '3.0'): ?>
	<div class="row-fluid">
		<div id="contents" class="span12">
			<div>

				<!-- BRICK : details -->
				<?php echo $this->loadTemplate('details'); ?>
			</div>
			<div>

				<!-- BRICK : data -->
				<?php echo $this->loadTemplate('data'); ?>
			</div>
		</div>
	</div>
	<?php elseif ($compat == '1.6'): ?>
	<div>
		<div>

			<!-- BRICK : details -->
			<?php echo $this->loadTemplate('details'); ?>
		</div>
		<div>

			<!-- BRICK : data -->
			<?php echo $this->loadTemplate('data'); ?>
		</div>
	</div>
	<?php endif; ?>

		<input name="_download" type="hidden" id="_download" value=""/>

	<?php 
		$jinput = JFactory::getApplication()->input;
		echo JDom::_('html.form.footer', array(
		'dataObject' => $this->item,
		'values' => array(
					'id' => $this->state->get('submission.id')
				)));
	?>
</form>
