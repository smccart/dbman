<h2>Event Calendar</h2>
<div class="example-info">
	<p>
		<i class="fa fa-info-circle"></i>
		This example shows how DBMan can be used to create a simple calendar of events system. This example utilizes fields like date, boolean, and text as well as utilizing the calendar feature.
	</p>
</div>
<?php

//initialize
$dbman = array();

//configurations
$dbman['url'] = "/examples/events/";
$dbman['vars'] = '';

//database connection
$dbman['db']['server'] = $config['db_server'];
$dbman['db']['user'] = $config['db_user'];
$dbman['db']['password'] = $config['db_password'];
$dbman['db']['name'] = $config['db_name'];
$dbman['db']['table'] = "events";

//permissions
$dbman['permissions']['import'] = false;

//defalts
$dbman['defaults']['action'] = 'calendar';

//search
$dbman['search'][] = array('label' => 'ID', 'id' => 'id');
$dbman['search'][] = array('label' => 'Event Title', 'id' => 'title');
$dbman['search'][] = array('label' => 'Event Description', 'id' => 'descr');

//list
$dbman['columns'][] = array('label' => 'Event Title', 'id' => 'title', 'type' => 'text');
$dbman['columns'][] = array('label' => 'Event Date', 'id' => 'event_date', 'type' => 'date');
$dbman['columns'][] = array('label' => 'Start Time', 'id' => 'start_time', 'type' => 'text');
$dbman['columns'][] = array('label' => 'End Time', 'id' => 'end_time', 'type' => 'text');

//calendar
$dbman['calendar'][] = array('label' => 'Title', 'id' => 'title', 'date_field' => 'event_date');

//fields
$dbman['fields'][] = array('label' => 'Event Information', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Title', 'value' => '', 'id' => 'title', 'type' => 'text', 'caption' => 'The title of the event.', 'required' => true );
$dbman['fields'][] = array('label' => 'Active', 'value' => 1, 'id' => 'active', 'type' => 'boolean', 'format' => 'Yes,No', 'required' => true, 'caption' => 'Is this event active?');
$dbman['fields'][] = array('label' => 'Event Date', 'id' => 'event_date', 'type' => 'date', 'value' => date('Y-m-d'), 'caption' => 'The date of the event.', 'required' => true );
$dbman['fields'][] = array('label' => 'Start Time', 'value' => '', 'id' => 'start_time', 'type' => 'text', 'caption' => 'The start time of the event. Please include AM or PM.', 'required' => true );
$dbman['fields'][] = array('label' => 'End Time', 'value' => '', 'id' => 'end_time', 'type' => 'text', 'caption' => 'The end time of the event. Please include AM or PM.', 'required' => true );
$dbman['fields'][] = array('label' => 'Event Description', 'value' => '', 'id' => 'descr', 'type' => 'textarea', 'caption' => 'The description of the event.', 'required' => true );

//output
new dbman($dbman);

?>

