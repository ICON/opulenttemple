<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

require_once JPATH_COMPONENT.DS.'views'.DS.'nav.php';

echo $this->menuHTML;
//getHeadingHTML($this->config, 'cal');

// Let's insert some CSS here
//echo '<style type="text/css">'.$this->config['cal_css'].'</style>';

$months   = array( 
	1  => $this->config['cal_january'],
	2  => $this->config['cal_february'], 
	3  => $this->config['cal_march'],     
	4  => $this->config['cal_april'],
	5  => $this->config['cal_may'],       
	6  => $this->config['cal_june'],     
	7  => $this->config['cal_july'],      
	8  => $this->config['cal_august'],
	9  => $this->config['cal_september'], 
	10 => $this->config['cal_october'],  
	11 => $this->config['cal_november'], 
	12 => $this->config['cal_december']);
	
$weekdays = array(  
	$this->config['cal_sunday'], 
	$this->config['cal_monday'], 
	$this->config['cal_tuesday'], 
	$this->config['cal_wendsday'], 
	$this->config['cal_thursday'], 
	$this->config['cal_friday'], 
	$this->config['cal_saturday']);
	
$first_dow     = $this->config['cal_first_day'] % 7;
$first_day     = mktime( 0, 0, 0, $this->month, 1, $this->year ); // layout issues resolved
$days_in_month = date( 't', $first_day );
$day_offset    = date( 'w', $first_day )-$first_dow;

echo '<style type="text/css">'.$this->config['cal_css'].'</style>';

echo '<div id="gigcal">';
echo '  <div id="gigcal_intro">';
if($this->config['cal_text'] != '')
  echo $this->config['cal_text']; 
echo "  </div>";

$this->monthminusone = ($this->month - 1);
$this->monthplusone = ($this->month + 1);
$this->yearminusone = ($this->year - 1);
$this->yearplusone = ($this->year + 1);

echo '<div id="gigcal_navigation">';
echo '<p class="gigcal_nextmonth">';
echo '<a href="'.JRoute::_("index.php?option=com_gigcal&task=cal&month=".$this->monthminusone."&year=".$this->year).'">'.$this->config['cal_leftarrowmark'].'</a>';
echo '<span>'.$months[$this->month].'</span>';
echo '<a href="'.JRoute::_("index.php?option=com_gigcal&task=cal&month=".$this->monthplusone."&year=".$this->year).'">'.$this->config['cal_rightarrowmark'].'</a></p>';

echo '<p class="gigcal_nextyear">';
echo '<a href="'.JRoute::_("index.php?option=com_gigcal&task=cal&month=".$this->month."&year=".$this->yearminusone).'">'.$this->config['cal_leftarrowmark'].'</a>';
echo '<span>'.$this->year.'</span>';
echo '<a href="'.JRoute::_("index.php?option=com_gigcal&task=cal&month=".$this->month."&year=".$this->yearplusone).'">'.$this->config['cal_rightarrowmark'].'</a></p>';

if ($this->config['cal_date_jumper'] == 1) 
{
  echo '<form action="index.php" name="date_jumper" id="date_jumper">';
  echo '<input type="hidden" name="option" value="com_gigcal">';
  echo '<input type="hidden" name="task" value="cal">';
//  echo '<input type="hidden" name="Itemid" value="'.$itemid.'">';
  
  echo '  <select id="month" name="month">';
  for($i=1; $i<=12; $i++)
    echo '   <option value="'.$i.'" '.(($this->month==$i)?'selected':'').'>'.$months[$i].'</option>';
  echo '  </select>';

  echo '<select name="year" id="year">';
  for($i=2004; $i<=2020; $i++)
    echo '<option value="'.$i.'"'.(($this->year==$i)?'selected':'').'>'.$i.'</option>';
  echo '</select>';

  echo '<input id="submit" name="submit" value="Go!" type="submit" />';

  echo '</form>';
}
echo '</div>';






echo '<div id="gigcal_wrapper">';
echo '<table>';
echo '<caption title="">'.$months[$this->month].'&nbsp;'.$this->year.'</caption>';
echo '<thead><tr>';
for($i=0; $i<7;$i++)
{
  $weekday = $weekdays[($i+$first_dow)%7];
  echo '<th scope="col" title="'.$weekday.'">'.$weekday.'</th>';
}
echo '</tr></thead>';

echo '<tbody>';
echo '<tr>';

for ($i = 0; $i < $day_offset; $i++)
  echo '<td class="gigcal_empty">&nbsp;</td>';

// Make some boxes, fill with data in $gigs array
for ($day = 1; $day <= $days_in_month; $day++ ) {
  echo '<td ';
  if((($day_offset+$first_dow)%7 == 0) || (($day_offset+$first_dow)%7 == 6)) {
    if (($day == date("j")) && ($this->month == date("n")) && ($this->year == date("Y"))) {	
      echo 'class="gigcal_weekend_current';
    }
    else { 
      echo 'class="gigcal_weekend"'; 
    }
  }	
  else { 
    if (($day == date("j")) && ($this->month == date("n")) && ($this->year == date("Y"))) { 
      echo 'class="gigcal_current"'; 
    }
  }

  echo '><span class="gigcal_daynumber">'.$day.'</span>';

  // print any gigs falling on this day
  $daystart = mktime(0, 0, 0, $this->month , $day, $this->year);
  $dayend   = mktime(0, 0, 0, $this->month , $day + 1, $this->year);
  foreach($this->gigs as $gig) {
    $gigdate=$gig['gigdate'];
    if(($gigdate >= $daystart) && ($gigdate < $dayend)) {
      echo '<div class="gigcal_event">';
//      gigContentBuilder ('cal', $gig, $this->config, $this->fieldnames);
      gigContentBuilder ($this->config, 'cal', $gig);
      echo '</div>';
    }
  }

  echo '</td>';
  $day_offset++;
  
  // if end of week, close the current week's cell, and start a new row if not end of month
  if ($day_offset == 7) {
    $day_offset = 0;
    if( $day != $days_in_month ) { 
      echo '</tr><tr>';
    }
  }
}

// fill in the rest w/ empty boxes
if ($day_offset > 0 ) {
  for ($i = $day_offset; $i < 7; $i++)
    echo '<td class="gigcal_empty">&nbsp;</td>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';
echo '</div>';

echo $this->menuHTML;

