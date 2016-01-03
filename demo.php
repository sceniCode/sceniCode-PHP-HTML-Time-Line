<?php

require_once("php-inc/sc_timeline.php");


// init the timeline list
$timeline_data = new sc_timeline_list();

// add data to the timeline list
$timeline_data->add(new sc_year( 2011), new sc_timeline_event("2 May 2011","An example event" , "img1.jpg", "A description of an event here. Can include HTML if required"));
$timeline_data->add(new sc_year( 2011), new sc_timeline_event("2 Aug 2011","An example event 2" , "img2.jpg", "A description of an event here. Can include HTML if required"));
$timeline_data->add(new sc_year( 2011), new sc_timeline_event("22 Aug 2011","An example event 2" , "img3.jpg", "Another new description of an event here"));

$timeline_data->add(new sc_year( 2012), new sc_timeline_event("2 May 2011","An example event" , "img1.jpg", "A description of an event here. Can include HTML if required"));
$timeline_data->add(new sc_year( 2012), new sc_timeline_event("2 Aug 2011","An example event 2" , "img2.jpg", "A description of an event here. Can include HTML if required"));
$timeline_data->add(new sc_year( 2012), new sc_timeline_event("22 Aug 2011","An example event 2" , "img3.jpg", "Another new description of an event here"));


$timeline_data->add(new sc_year( 2013), new sc_timeline_event("2 May 2011","An example event" , "img1.jpg", "A description of an event here. Can include HTML if required"));
$timeline_data->add(new sc_year( 2013), new sc_timeline_event("2 Aug 2011","An example event 2" , "img2.jpg", "A description of an event here. Can include HTML if required"));

?>
<html>
<head>
<title>Example Timeline Page</title>
<!-- include the css file -->
<link rel="stylesheet" href="css/sceniCode-timeline.css" >

</head>

<body>

<?php
// init new timeline output html obj.
$printer = new sc_timeline_output_html($timeline_data);
// output the table of contents
echo $printer->table_of_contents();
// output the timeline
echo $printer->timeline();
?>

</body>

</html>




