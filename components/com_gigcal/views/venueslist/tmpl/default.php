<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT.DS.'views'.DS.'nav.php';

echo $this->menuHTML;

// Let's insert some CSS here
echo '<style type="text/css">'.$this->config['details_css'].'</style>';

echo $this->filterControlHTML;

if (count($this->venues) > 0) {
  foreach($this->venues as $venue)
    detailsBuilder($this->config, 'venue', $venue);

  echo '</tfoot></table>';

  echo '<table width="100%"><tr><td width="250">';
  echo $this->pageNav->getResultsCounter();
  echo '</td><td width="*"><div class="pagination">'.$this->pageNav->getPagesLinks().'</div>';
  echo '</td><td width="100"><form action="#" method="post">'.$this->pageNav->getLimitBox().'</form>';
  echo '</td></tr></table>';
  echo $this->menuHTML;
}

