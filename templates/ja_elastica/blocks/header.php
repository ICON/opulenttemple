<?php
/**
 * ------------------------------------------------------------------------
 * JA Elastica Template for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// No direct access
defined('_JEXEC') or die;
?>
<div class="nav_wrapper clearfix">
  <?php
  $app = & JFactory::getApplication();
  $siteName = $app->getCfg('sitename');
  if ($this->getParam('logoType', 'image')=='image'): ?>
  <h1 class="logo">
      <a href="<?php JURI::base(true) ?>" title="<?php echo $siteName; ?>">
      <img src="<?php echo 'templates/'.T3_ACTIVE_TEMPLATE.'/images/OT_logo.png' ?>" alt="<?php echo $siteName; ?>" />
    </a>
  </h1>
  <?php else:
  $logoText = (trim($this->getParam('logoText'))=='') ? $siteName : JText::_(trim($this->getParam('logoText')));
  $sloganText = JText::_(trim($this->getParam('sloganText'))); ?>
  <div class="logo-text">
      <h1><a href="<?php JURI::base(true) ?>" title="<?php echo $siteName; ?>"><span><?php echo $logoText; ?></span></a></h1>
      <p class="site-slogan"><?php echo $sloganText;?></p>
  </div>
  <?php endif; ?>

  <?php if (($jamenu = $this->loadMenu())) : ?>
  <div id="ja-mainnav" class="clearfix">
    <?php $jamenu->genMenu (); ?>
  </div>
  <?php endif;?>
</div>

<?php if($this->countModules('search') || $this->countModules('social')) : ?>
<div id="ja-top" class="clearfix">
  <?php if($this->countModules('search')) : ?>
  <div id="ja-search">
    <span class="search-btn">Search</span>
    <jdoc:include type="modules" name="search" />
  </div>
  <script type="text/javascript">
    // toggle search box active when click on search button
    $$('.search-btn').addEvent ('mouseenter', function () {
      // focus on search box
      $('mod-search-searchword').focus();
    });
    $('mod-search-searchword').addEvents ({
      'blur': function () {$('ja-search').removeClass ('active');},
      'focus': function () {$('ja-search').addClass ('active');}
    });
  </script>
  <?php endif; ?>
</div>
<?php endif;?>
<div id="ja-social">
  <!--jdoc:include type="modules" name="social" /-->
  <div class="social clearfix">
    <a href="http://facebook.com/opulenttemple" target="_blank" class="fb"></a>
    <a href="http://twitter.com/opulenttemple" target="_blank" class="twitter"></a>
    <a href="/news?format=feed&type=rss" target="_blank" class="rss"></a>
  </div>
</div>
<ul class="no-display">
    <li><a href="<?php echo $this->getCurrentURL();?>#ja-content" title="<?php echo JText::_("SKIP_TO_CONTENT");?>"><?php echo JText::_("SKIP_TO_CONTENT");?></a></li>
</ul>
