<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	Cpanel
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
<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm">
	<?php
	$selectors = 'style="width: 50%;"';
	$compat = '1.6';
	$version = new JVersion();
	if ($version->isCompatible('3.0')){
		$compat = '3.0';
		
		$selectors = 'class="span6"';
	}
	?>

	<div <?php if ($compat == '3.0'){ ?>class="row-fluid" <?php } ?>>
		<div <?php echo $selectors ?>>
			<?php echo JDom::_('html.menu.cpanel', array(
				'list' => $this->menu
			)); ?>
			<div class="clearfix"></div>
		</div>
		<div <?php echo $selectors ?>>
			<?php echo JText::_("JFORMS_COMPONENT_DESCRIPTION"); ?>
			<span id="btn_changelog_modal" class="btn btn-info">
				CHANGELOG
			</span>
			<a class="btn btn-success" target="_blank" alt="PayPal - The safer, easier way to pay online!" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=girotomaselli@hotmail.it&lc=US&item_name=com_jforms&currency_code=EUR&bn=PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest">
				Donation
			</a>
		</div>
	</div>

	<?php 
		$jinput = JFactory::getApplication()->input;
		echo JDom::_('html.form.footer', array(
		'values' => array(
					'view' => $jinput->get('view', 'cpanel'),
					'layout' => $jinput->get('layout', 'default'),

				)));
	?>
</form>

<?php
// add modal bootstrap for popup form
echo JDom::_('html.fly.bootstrap.modal', array(
		'domId' => 'changelog_modal',
		'title' => JText::_("JFORMS_CHANGELOG_LIST"),
		'body' => JformsHelper::colorise(JPATH_COMPONENT_ADMINISTRATOR.'/CHANGELOG.php'),
		'footer' => '<a class="btn btn-cancel" data-dismiss="modal" aria-hidden="true">'. JText::_("JFORMS_CLOSE") .'</a>'
	));
?>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#btn_changelog_modal').on('click',function(){
		jQuery('#changelog_modal').modal('toggle');
	});
});
</script>