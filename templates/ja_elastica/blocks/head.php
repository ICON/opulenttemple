<?php
/**
 * ------------------------------------------------------------------------
 * JA T3 System plugin for Joomla 1.7
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
<script type="text/javascript">
var siteurl='<?php echo JURI::base(true) ?>/';
var tmplurl='<?php echo JURI::base(true)."/templates/".T3_ACTIVE_TEMPLATE ?>/';
var isRTL = <?php echo $this->isRTL()?'true':'false' ?>;
</script>
<jdoc:include type="head" />
<script type="text/javascript" src="/templates/ja_elastica/js/jquery.tweet.js"></script>
<script type="text/javascript" src="/templates/ja_elastica/js/fadeslideshow.js"></script>
<script type="text/javascript" src="/templates/ja_elastica/js/fancyzoom/fancyzoom.min.js"></script>
<script type="text/javascript" charset="utf-8">
// fancy zoom
(function($) {
  $(document).ready(function() {
    $('div.zoom a').fancyZoom({scaleImg: true, closeOnClick: true, directory:'/templates/ja_elastica/js/fancyzoom/images'});
  });
// show-hide
  $("a.show-info").live("click", function() {
    var link = $(this);
    var info = link.data("info");
    var close_link = $(".hide-info[data-info=" + info + "]");
    if (link.hasClass("hide-others")) {
      $(".info").hide();
      $(".hide-info").hide();
      $(".show-info").show();
    }
    link.hide();
    $(info).show();
    close_link.show();
    return false;
  });

  $("a.hide-info").live("click", function() {
    var info = $(this).data("info");
    var close_link = $(".hide-info[data-info=" + info + "]");
    $(info).hide();
    $(".show-info[data-info=" + info + "]").show();
    close_link.hide();
    return false;
  });
// randomizing hero image
  var heroimg = [
    '/templates/ja_elastica/images/hero_images/hero_img1.jpg',
    '/templates/ja_elastica/images/hero_images/hero_img2.jpg',
    '/templates/ja_elastica/images/hero_images/hero_img3.jpg',
    '/templates/ja_elastica/images/hero_images/hero_img4.jpg',
    '/templates/ja_elastica/images/hero_images/hero_img5.jpg',
    '/templates/ja_elastica/images/hero_images/hero_img6.jpg',
    '/templates/ja_elastica/images/hero_images/hero_img7.jpg',
  	'/templates/ja_elastica/images/hero_images/hero_img8.jpg',
  	'/templates/ja_elastica/images/hero_images/hero_img9.jpg' 
  ];
  var random = Math.floor(Math.random()*heroimg.length);
  var current = heroimg[random];
  $(document).ready(function() {
    $(".frontpage #ja-header .main-inner1").css("backgroundImage", "url(" + current + ")");
    // jquery.tweet
    $(".tweet").tweet({
      join_text: "auto",
      username: "opulenttemple",
      avatar_size: 48,
      count: 3,
      auto_join_text_default: "",
      auto_join_text_ed: "",
      auto_join_text_ing: "",
      auto_join_text_reply: "",
      auto_join_text_url: "",
      loading_text: "loading tweets..."
    })
    // IE6 detector
    if($.browser.msie && $.browser.version < 8) { $("#ie-overlay").show(); }
  });
  // tweet.js
})(jQuery);
</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes"/>
<meta name="HandheldFriendly" content="true" />

<?php if (T3Common::mobile_device_detect()=='iphone'):?>
<meta name="apple-touch-fullscreen" content="YES" />
<?php endif;?>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,300,600' rel='stylesheet' type='text/css'>
<link href="/templates/ja_elastica/favicon.ico" rel="shortcut icon" type="image/x-icon" />

<?php JHTML::stylesheet ('', 'templates/system/css/system.css') ?>
<?php JHTML::stylesheet ('', 'templates/system/css/general.css') ?>
