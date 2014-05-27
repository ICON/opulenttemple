<script language="javascript" type="text/javascript">
   function submitbutton(pressbutton) {
   var form = document.adminForm;
   if (pressbutton == "cancel") {
     submitform( pressbutton );
     return;
   }
<?php if($_REQUEST['view']=="gig") { ?>

  if(form.gigtime.value == "") {
  alert("gig must have a time.")
  } else if(form.gigdate.value == "") {
  alert("gig must have a date.")
  } else if(form.gigcal_venues_id.value == "") {
  alert("gig must have a venue, create a venue first.")
  } else if(form.gigcal_bands_id.value == "") {
  alert("gig must have a band, create a band first.")
  } else {



<?php } if($_REQUEST['view']=="band") { ?>

if(form.bandname.value == "") {
  alert("Band must have a name.")
  } else if(form.city.value == "") {
  alert("Band must have a city.")
  } else {

<?php } if($_REQUEST['view']=="venue") { ?>

  if(form.venuename.value == "") {
  alert("Venue must have a name.")
  } else if(form.address1.value == "") {
  alert("Venue must have a street address.")
  } else if(form.city.value == "") {
  alert("Venue must have a city.")
  } else if(form.zip.value == "") {
  alert("Venue must have a zip.")
  } else if(form.country.value == "") {
  alert("Venue must have a country.")
  } else {

<?php } ?>

   submitform( pressbutton );
   }
</script>


<script type="text/javascript" name="expand">
<!--
function expand(thistag,tag2,tag3) {
   styleObj = document.getElementById(thistag).style;
   if (styleObj.display=='none') {styleObj.display = '';}
   else {styleObj.display = 'none';}

   styleObj2 = document.getElementById(tag2).style;
   if (styleObj2.display=='none') {styleObj2.display = '';}
   else {styleObj2.display = 'none';}

   styleObj3 = document.getElementById(tag3).style;
   if (styleObj3.display=='none') {styleObj3.display = '';}
   else {styleObj3.display = 'none';}
}
// -->
</script>

<SCRIPT LANGUAGE="JavaScript">
<!-- Idea by:  Nic Wolfe (Nic@TimelapseProductions.com) -->
<!-- Web URL:  http://fineline.xs.mw -->

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=500,height=600,left = 490,top = 262');");
}
// End -->
</script>
