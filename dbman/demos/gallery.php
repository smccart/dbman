<h2>Image Gallery</h2>

<div class="example-info">
	<p>
		<i class="fa fa-info-circle"></i>
		This example shows how DBMan can be used to manage data associated with an image gallery. This example utilizes fields like date, boolean, and image.
	</p>
</div>

<?php

//initialize
$dbman = array();

//configurations
$dbman['url'] = "/examples/gallery/";
$dbman['vars'] = '';

//database connection
$dbman['db']['server'] = $config['db_server'];
$dbman['db']['user'] = $config['db_user'];
$dbman['db']['password'] = $config['db_password'];
$dbman['db']['name'] = $config['db_name'];
$dbman['db']['table'] = "gallery";

//permissions
$dbman['permissions']['import'] = false;
$dbman['permissions']['calendar'] = false;

//search
$dbman['search'][] = array('label' => 'ID', 'id' => 'id');
$dbman['search'][] = array('label' => 'Title', 'id' => 'title');
$dbman['search'][] = array('label' => 'Description', 'id' => 'descr');

//list
$dbman['columns'][] = array('label' => 'Image', 'id' => 'img', 'type' => 'image', 'file_path' => '/uploads/', 'width' => '60', 'height' => '60');
$dbman['columns'][] = array('label' => 'Title', 'id' => 'title', 'type' => 'text');
$dbman['columns'][] = array('label' => 'Date Added', 'id' => 'date_created', 'type' => 'date');
$dbman['columns'][] = array('label' => 'Active', 'id' => 'active', 'type' => 'boolean', 'yesValue' => 1, 'noValue' => 0);

//fields
$dbman['fields'][] = array('label' => 'Gallery Information', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Image', 'id' => 'img', 'type' => 'file', 'file_path' => '/uploads/', 'upload_path' => './uploads/', 'caption' => 'The gallery image or photo.');
$dbman['fields'][] = array('label' => 'Title', 'value' => '', 'id' => 'title', 'type' => 'text', 'caption' => 'The title of the gallery image.', 'required' => true );
$dbman['fields'][] = array('label' => 'Active', 'value' => 1, 'id' => 'active', 'type' => 'boolean', 'format' => 'Yes,No', 'required' => true, 'caption' => 'Is this gallery image active?');
$dbman['fields'][] = array('label' => 'Date Created', 'id' => 'date_created', 'type' => 'date', 'value' => date('Y-m-d'), 'caption' => 'The date the image was added to the gallery.', 'required' => true );
$dbman['fields'][] = array('label' => 'Description', 'value' => '', 'id' => 'descr', 'type' => 'textarea', 'caption' => 'The description of the image.', 'required' => true );

//output
new dbman($dbman);

?>

