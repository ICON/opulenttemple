<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//echo 'items[<pre>'.print_r($this->items,true).'</pre>]';
$url = JRoute::_('index.php?option=com_gigcal&view=config&fields='.$fields);
?>

<form action="<?php echo $url ?>" method="post" name="adminForm" id="adminForm">
<table>
 <tr>
  <td>
   <input type="hidden" name="rss_all" value="0" />
   <input type="checkbox" value="1" name="rss_all" <?php if($this->config['rss_all']==1) echo 'checked'; ?>>
  </td>
  <td><b>Show Feed for All Gigs</b></td>
 </tr>
 <tr>
  <td>
   <input type="hidden" name="rss_band" value="0" />
   <input type="checkbox" value="1" name="rss_band" <?php if($this->config['rss_band']==1) echo 'checked'; ?>>
  </td>
  <td><b>Show Feed per Band</b></td>
 </tr>
 <tr>
  <td>
   <input type="hidden" name="rss_venue" value="0" />
   <input type="checkbox" value="1" name="rss_venue" <?php if($this->config['rss_venue']==1) echo 'checked'; ?>>
  </td>
  <td><b>Show Feed per Venue</b></td>
 </tr>
 <tr>
  <td colspan="2">
   <strong>NOTE:</strong> Per Band and Per Venue feeds will NOT display if only one venue or band exists.<br /><br />

   <b>Links about RSS</b>
   <ul>
   	<li><a href="http://www.whatisrss.com/" target="_blank" title="What is RSS?">What is RSS?</a> [whatisrss.com]</li>
   	<li><a href="http://www.faganfinder.com/search/rss.shtml" target="_blank" title="All About RSS">All About RSS</a> [faganfinder.com]</li>
   </ul>
  </td>
 </tr>
</table>

  <div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>

