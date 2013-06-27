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
<?php if ($this->isIE() && ($this->isRTL())) { ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php } else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php } ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">

<head>
  <?php //gen head base on theme info
  $this->showBlock ('head');
  ?>

  <?php
  $blocks = T3Common::node_children($this->getBlocksXML ('head'), 'block');
  foreach ($blocks as $block) :
      $this->showBlock ($block);
  endforeach;
  ?>

  <?php echo $this->showBlock ('css') ?>
  <script type="text/javascript">
  // google analytics
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-33751158-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body id="bd" class="<?php if (!T3Common::mobile_device_detect()):?>bd<?php endif;?> <?php echo $this->getBodyClass();?>">
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=323060101122245";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
<a name="Top" id="Top"></a>
<div id="ie-overlay" style="display:none">
  <div class="overlay_message">
    <p>Oops! Did you know your browser is seriously <a href="http://www.ie6countdown.com/" target="_blank">out-of-date</a>? Please upgrade to a better browser below... you won't believe how much better the internet will be! Trust us. And while you're at it, time to get rid of your VCR and pager too!</p>
    <ul class="browsers">
      <li><a href="http://www.google.com/chrome" target="_blank"><img src="/templates/ja_elastica/images/logo_chrome.png"></a></li>
      <li><a href="http://www.mozilla.org" target="_blank"><img src="/templates/ja_elastica/images/logo_firefox.png"></a></li>
      <li class="last"><a href="http://www.apple.com/safari" target="_blank"><img src="/templates/ja_elastica/images/logo_safari.png"></a></li>
    </ul> 
  </div>
</div>
<div id="ja-wrapper">
  <?php
  $blks = &$this->getBlocksXML ('top');
  $blocks = &T3Common::node_children($blks, 'block');
  foreach ($blocks as $block) :
      $this->showBlock ($block);
  endforeach;
  ?>

  <!-- MAIN CONTAINER -->
  <div id="ja-container" class="wrap <?php echo $this->getColumnWidth('cls_w')?$this->getColumnWidth('cls_w'):'ja-mf'; ?> clearfix">
    <div id="ja-main-wrap" class="main clearfix">
      <div id="ja-main" class="clearfix">
        <?php if (!$this->getParam ('hide_content_block', 0)): ?>
          <div id="ja-content" class="ja-content ja-masonry">
            <?php
            //content-top
            if($this->hasBlock('content-top')) :
            $block = &$this->getBlockXML ('content-top');
            ?>
            <div id="ja-content-top" class="ja-content-top clearfix">
              <?php $this->showBlock ($block); ?>
            </div>
            <?php endif; ?>
        
            <div id="ja-content-main" class="ja-content-main clearfix">
              <?php echo $this->loadBlock ('message') ?>
              <?php echo $this->showBlock ('content') ?>
            </div>
          
            <?php
            //content-bottom
            if($this->hasBlock('content-bottom')) :
            $block = &$this->getBlockXML ('content-bottom');
            ?>
            <div id="ja-content-bottom" class="ja-content-bottom clearfix">
              <?php $this->showBlock ($block); ?>
            </div>
            <?php endif; ?>
          
          </div>
        <?php endif ?>
        <?php if ($this->hasBlock('right1')):
          $block = &$this->getBlockXML('right1');
          ?>
        
            <?php $this->showBlock ($block); ?>
        
        <?php endif ?>
      </div>
      <?php if ($this->hasBlock('right2')):
        $block = &$this->getBlockXML('right2');
        ?>
          <?php $this->showBlock ($block); ?>
      <?php endif ?>      
    </div>
  </div>
    <!-- //MAIN CONTAINER -->

    <?php
    $blks = &$this->getBlocksXML ('bottom');
    $blocks = &T3Common::node_children($blks, 'block');
    foreach ($blocks as $block) :
        if (T3Common::getBrowserSortName() == 'ie' && T3Common::getBrowserMajorVersion() == 7) echo "<div class=\"clearfix\"></div>";
        $this->showBlock ($block);
    endforeach;
    ?>

</div>
<?php if ($this->isIE6()) : ?>
    <?php $this->showBlock('ie6/ie6warning') ?>
<?php endif; ?>

<?php $this->showBlock('debug') ?>
</body>

</html>