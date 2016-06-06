<h2>Member Profiles</h2>
<div class="example-info">
	<p>
		<i class="fa fa-info-circle"></i>
		This example shows how DBMan can be used to manage member or customer profile information. This example utilizes fields like html, date, boolean, and image. It also displays how conditionals can be used to hide/show a field.
	</p>
</div>
<?php

//initialize

$dbman = array();

//configurations
$dbman['url'] = "/examples/members/";
$dbman['vars'] = '';


//database connection
$dbman['db']['server'] = $config['db_server'];
$dbman['db']['user'] = $config['db_user'];
$dbman['db']['password'] = $config['db_password'];
$dbman['db']['name'] = $config['db_name'];
$dbman['db']['table'] = "members";

//permissions
$dbman['permissions']['import'] = false;

//search
$dbman['search'][] = array('label' => 'ID', 'id' => 'id');
$dbman['search'][] = array('label' => 'Name', 'id' => 'name');
$dbman['search'][] = array('label' => 'Email', 'id' => 'email');
$dbman['search'][] = array('label' => 'Bio', 'id' => 'bio');

//list
$dbman['columns'][] = array('label' => 'Name', 'id' => 'name');
$dbman['columns'][] = array('label' => 'Email', 'id' => 'email');
$dbman['columns'][] = array('label' => 'Date Created', 'id' => 'date_created', 'type' => 'date');
$dbman['columns'][] = array('label' => 'Active', 'id' => 'active', 'type' => 'boolean', 'yesValue' => 1, 'noValue' => 0);
$dbman['columns'][] = array('label' => 'Type', 'id' => 'type_id', 'query' => 'Select name from member_types where id = {recID}');


//fields
$dbman['fields'][] = array('label' => 'Member Information', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Name', 'value' => '', 'id' => 'name', 'type' => 'text', 'caption' => 'The first and last name of the member.', 'required' => true );
$dbman['fields'][] = array('label' => 'Active', 'value' => 1, 'id' => 'active', 'type' => 'boolean', 'format' => 'Yes,No', 'required' => true, 'caption' => 'Is this member\'s account active?');
$dbman['fields'][] = array('label' => 'Email', 'id' => 'email', 'type' => 'text', 'caption' => 'The email address of the member.', 'required' => false );
$dbman['fields'][] = array('label' => 'Password', 'id' => 'password', 'type' => 'password', 'caption' => 'A secure password for the member.', 'required' =>  true);
$dbman['fields'][] = array('label' => 'Date Created', 'id' => 'date_created', 'type' => 'date', 'value' => date('Y-m-d'), 'caption' => 'The date the member was registered.', 'required' => true );
$dbman['fields'][] = array('label' => 'Member Profile', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Use Bio', 'value' => 1, 'id' => 'use_bio', 'type' => 'boolean', 'required' => true, 'caption' => 'Include bio description on profile page?');
$dbman['fields'][] = array('label' => 'Bio', 'id' => 'bio', 'condition_field' => 'use_bio', 'condition_value' => 1, 'type' => 'html', 'caption' => 'The descriptive paragraph for the member\'s profile.', 'required' => false );
$dbman['fields'][] = array('label' => 'Additional Information', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Avatar Photo', 'id' => 'avatar', 'type' => 'file', 'file_path' => './uploads/', 'caption' => 'A picture of yourself to use as your avatar.');
$dbman['fields'][] = array('label' => 'Membership Type', 'value' => 1, 'id' => 'type_id', 'type' => 'dynamic', 'query' => 'Select * from member_types', 'dynamic_type' => 'dropdown', 'dynamic_value' => 'id', 'dynamic_label' => 'name', 'caption' => 'The membership type.', 'required' => true );


//children
/*
$dbman['child']['header'] = "Related Items";
$dbman['child']['table'] = $config['tables']['users'];
$dbman['child']['id'] = "parent_id";
$dbman['child']['fields'][] = array('label' => 'Name', 'id' => 'name', 'type' => 'text', 'caption' => 'sd fasdfasdf asdf asdf adsf ', 'required' =>  false);
$dbman['child']['fields'][] = array('label' => 'Email', 'id' => 'email', 'type' => 'text', 'caption' => 'sd fasdfasdf asdf asdf adsf ', 'required' =>  false);
$dbman['child']['fields'][] = array('label' => 'Sent', 'value' => 0, 'id' => 'sent', 'type' => 'boolean', 'required' => true, 'yesValue' => 1, 'noValue' => 0, 'caption' => 'Has the email been sent for this invitee.');
*/
//output
new dbman($dbman);

?>