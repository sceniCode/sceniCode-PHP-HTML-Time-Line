# sceniCode-PHP-HTML-Time-Line
PHP based responsive  HTML/CSS/PHP timeline.

# Demo

Please visit http://scenicode.com/timeline for a simple demo

# Usage

See demo.php for a basic example.

Basically:

1) Include the sc_timeline.php file, and include the sceniCode-timeline.css style sheet

2) Make a sc_timeline_list object. 

    $timeline_data = new sc_timeline_list();

3) Add events (in order) to the sc_timeline_list object:

    $timeline_data->add(new sc_year(2000), new sc_timeline_event("1 Jan 2001","Event Title", "Optional-image-url.jpg", "A description of the event, including HTML here");

Or you can add them more simply with the adder() method:

    $timeline_data->adder("2000", "Jan 2000", "Event title", "optional-image.jpg", "HTML Event Description");

4) Then create a sc_timeline_output_html object, and echo the table of contents and the actual timeline:

    <?php
    // init new timeline output html obj.
    $printer = new sc_timeline_output_html($timeline_data);
    // output the table of contents
    echo $printer->table_of_contents();
    // output the timeline
    echo $printer->timeline();
    ?>



