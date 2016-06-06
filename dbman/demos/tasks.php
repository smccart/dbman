<h2>Task List</h2>
<div class="example-info">
	<p>
		<i class="fa fa-info-circle"></i>
		This example shows how DBMan can be used to create a simple task list system that allows users to create, edit, and view a list of tasks. This example utilizes fields like text, date, and boolean.
	</p>
</div>
<?php

//initialize
$dbman = array();

//configurations
$dbman['url'] = "/examples/tasks/";
$dbman['vars'] = '';


//database connection
$dbman['db']['server'] = $config['db_server'];
$dbman['db']['user'] = $config['db_user'];
$dbman['db']['password'] = $config['db_password'];
$dbman['db']['name'] = $config['db_name'];
$dbman['db']['table'] = "tasks";

//permissions
$dbman['permissions']['import'] = false;
$dbman['permissions']['calendar'] = false;

//search
$dbman['search'][] = array('label' => 'ID', 'id' => 'id');
$dbman['search'][] = array('label' => 'Task', 'id' => 'title');
$dbman['search'][] = array('label' => 'Notes', 'id' => 'notes');

//list
$dbman['columns'][] = array('label' => 'Task', 'id' => 'title', 'type' => 'text');
$dbman['columns'][] = array('label' => 'Deadline', 'id' => 'deadline', 'type' => 'date');
$dbman['columns'][] = array('label' => 'Completed', 'id' => 'completed', 'type' => 'boolean', 'yesValue' => 1, 'noValue' => 0, 'format' => 'Complete,-----');

//calendar
$dbman['calendar'][] = array('label' => 'Task', 'id' => 'title', 'date_field' => 'deadline');

//list configs
$dbman['list']['sort_column'] = 1;
$dbman['list']['sort_dir'] = 'desc';

//fields
$dbman['fields'][] = array('label' => 'Task Information', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Task', 'value' => '', 'id' => 'title', 'type' => 'text', 'caption' => 'The title of the expense item.', 'required' => true );
$dbman['fields'][] = array('label' => 'Completed', 'value' => 0, 'id' => 'completed', 'type' => 'boolean', 'format' => 'Yes,No', 'required' => true, 'caption' => 'Is this gallery image active?');
$dbman['fields'][] = array('label' => 'Deadline', 'id' => 'deadline', 'type' => 'date', 'value' => date('Y-m-d'), 'caption' => 'The date the image was added to the gallery.', 'required' => true );
$dbman['fields'][] = array('label' => 'Notes', 'value' => '', 'id' => 'notes', 'type' => 'textarea', 'caption' => 'Any notes pertaining to the expense item.', 'required' => true );

//output
new dbman($dbman);

?>

