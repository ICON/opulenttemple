<?php
/**
 * ------------------------------------------------------------------------
 * JA T3 System Plugin for Joomla 2.5
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
<div class="ja-copyright">
  &copy; Opulent Temple 
  <script>
  now = new Date();
  document.write(now.getFullYear());
  </script>
  &nbsp;&nbsp;|&nbsp;&nbsp;All Rights Reserved
  &nbsp;&nbsp;|&nbsp;&nbsp;Site by <a href="http://www.iconicreations.com" target="_blank">ICONICreations</a>
</div>

<?php if($this->countModules('footnav')) : ?>
<div class="ja-footnav">
    <jdoc:include type="modules" name="footnav" />
</div>
<?php endif; ?> 
