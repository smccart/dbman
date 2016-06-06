<h2>File Management</h2>
<div class="example-info">
	<p>
		<i class="fa fa-info-circle"></i>
		This example shows how DBMan can be used to manage a set of data associated with actual files that live on the server. This example utilizes fields like date, boolean, and file.
	</p>
</div>
<?php

//initialize
$dbman = array();

//configurations
$dbman['url'] = "/examples/files/";
$dbman['vars'] = '';
$dbman['debug'] = false;

//database connection
$dbman['db']['server'] = $config['db_server'];
$dbman['db']['user'] = $config['db_user'];
$dbman['db']['password'] = $config['db_password'];
$dbman['db']['name'] = $config['db_name'];
$dbman['db']['table'] = "files";

//permissions
$dbman['permissions']['import'] = false;
$dbman['permissions']['calendar'] = false;

//search
$dbman['search'][] = array('label' => 'ID', 'id' => 'id');
$dbman['search'][] = array('label' => 'Title', 'id' => 'title');

//list
$dbman['columns'][] = array('label' => 'File Title', 'id' => 'title', 'type' => 'text', 'suffix' => '.txt');
$dbman['columns'][] = array('label' => 'Date Added', 'id' => 'date_created', 'type' => 'date');
$dbman['columns'][] = array('label' => 'Active', 'id' => 'active', 'type' => 'boolean', 'yesValue' => 1, 'noValue' => 0);

//fields
$dbman['fields'][] = array('label' => 'File Information', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Title', 'value' => '', 'id' => 'title', 'suffix' => '.txt', 'type' => 'text', 'caption' => 'The title of the gallery image.', 'required' => true );
$dbman['fields'][] = array('label' => 'Active', 'value' => 1, 'id' => 'active', 'type' => 'boolean', 'format' => 'Yes,No', 'required' => true, 'caption' => 'Is this gallery image active?');
$dbman['fields'][] = array('label' => 'Date Created', 'id' => 'date_created', 'type' => 'date', 'value' => date('Y-m-d'), 'caption' => 'The date the image was added to the gallery.', 'required' => true );
$dbman['fields'][] = array('label' => 'File Contents', 'id' => 'content', 'type' => 'file_content', 'file_name_field' => 'title', 'file_extension' => 'txt', 'path' => './uploads/files/', 'caption' => 'The title of the gallery image.', 'required' => true );


//output
new dbman($dbman);

?>

