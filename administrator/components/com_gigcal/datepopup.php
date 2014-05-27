<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>gigCalendar's Date Formats Explained</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>

<table width="100%">
 <tbody><tr>
  <th>gigCalendar's Date / Time Formats Explained</th></tr>
<tr>
	<td valign="top">
<table align="center" cellpadding="8" cellspacing="0" border="1" width="80%">
  <tr>
    <th><strong>Variable </strong></th>
    <th><strong>Meaning </strong></th>
    <th><strong>Example </strong></th>
  </tr>
  <tr>
    <td>%weekday </td>
    <td>Day of the week from language file </td>
    <td>Wednesday </td>
  </tr>
  <tr>
    <td>%wkdy</td>
    <td>Abbreviated weekday from language file </td>
    <td>Wed</td>
  </tr>
  <tr>
    <td>%month </td>
    <td>Name of the month from language file </td>
    <td>December </td>
  </tr>
  <tr>
    <td>%mon </td>
    <td>Abbreviated month from language file </td>
    <td>Dec </td>
  </tr>
  <tr>
    <td>%nmonth </td>
    <td>Number of the month </td>
    <td>1 </td>
  </tr>
  <tr>
    <td>%2nmonth </td>
    <td>Number of the month in 2-digit format </td>
    <td>01 </td>
  </tr>
  <tr>
    <td>%day </td>
    <td>Number of the day </td>
    <td>19 </td>
  </tr>
  <tr>
    <td valign="top">%ordday </td>
    <td valign="top">Ordinal Number of the day (1 <em>st </em>, 2 <em>st </em>, 3 <em>st </em>, 4 <em>st </em>...) </td>
    <td valign="top">19th </td>
  </tr>
  <tr>
    <td>%year </td>
    <td>Number of the year in 4-digit format </td>
    <td>2001 </td>
  </tr>
  <tr>
    <td>%2year </td>
    <td>Number of the year in 2-digit format </td>
    <td>01 </td>
  </tr>
  <tr>
    <td colspan="3"><hr align="center" width="80%" noshade></td>
    </tr>
  <tr>
    <td>%hour </td>
    <td>Hour of the day, in 12-hour (AM/PM) format </td>
    <td>10 </td>
  </tr>
  <tr>
    <td>%24hour </td>
    <td>Hour of the day, in 24-hour format </td>
    <td>22 </td>
  </tr>
  <tr>
    <td>%minute </td>
    <td>The number of minutes </td>
    <td>44 </td>
  </tr>
  <tr>
    <td>%ampm </td>
    <td>"am" or "pm" </td>
    <td>pm </td>
  </tr>
  <tr>
    <td>%AMPM </td>
    <td> "AM" or "PM" </td>
    <td>PM </td>
  </tr>
</table>
<p> Commonly, the date format is derived from the gigConfig setting by defining it with the variables above. Here are some examples of how the date format would be used:</p>
<table border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
    <td>%day/%mon/%year:%24hour:%minute</td>
    <td>=</td>
    <td>19/Dec/2001:14:44 </td>
  </tr>
  <tr>
    <td>%wkdy %mon %day %24hour:%minute CST %year</td>
    <td>=</td>
    <td>Wed Dec 19 14:44 CST 2001 </td>
  </tr>
  <tr>
    <td>%mon %day %24hour:%minute CST %year</td>
    <td>=</td>
    <td>Dec 19 CST 14:44 </td>
  </tr>
  <tr>
    <td>%wkdy, %mon. %ordday</td>
    <td>=</td>
    <td>Sat, Dec. 19th</td>
  </tr>
</table><p>In addition to these options, anything from PHP's <a href="http://php.net/date" target="_blank">date() syntax </a> is also valid. </p>
</td></tr></tbody></table>
</body>
</html>