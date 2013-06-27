<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<?php

require_once JPATH_COMPONENT.DS.'views'.DS.'nav.php';

  //echo $this->menuHTML;
  echo '<h2 class="contentheading">Upcoming Events</h2>';
  
if (count($this->gigs) > 0) {
  gigsTableBuilder($this->config, 'list');

  foreach($this->gigs as $gig)
    gigContentBuilder ($this->config, 'list', $gig);

  echo '</tfoot></table>';

  echo '<table width="100%"><tr><td width="250">';
  echo $this->pageNav->getResultsCounter();
  echo '</td><td width="*"><div class="pagination">'.$this->pageNav->getPagesLinks().'</div>';
  echo '</td><td width="100"><form action="#" method="post">'.$this->pageNav->getLimitBox().'</form>';
  echo '</td></tr></table>';
  echo $this->menuHTML;
}


