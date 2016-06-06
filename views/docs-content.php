<h2 id="initialization">Initialization</h2>
<pre class="docs_code">
require '../dbman.php';
$dbman = array();
</pre>
<p>
These lines initialize DBMAN by including the dbman.php file and creating a new array to store configuration data.
</p>
<hr>

<h2 id="configuration">General Configurations</h2>
<pre class="docs_code">
$dbman['url'] = &quot;&quot;;
$dbman['vars'] = 'view=demos/members';
</pre>
<p>
Configurations are general settings which are associated with a particular instance of DBMAN.
</p>
<strong>Configurations</strong>
<table class="table table-bordered">
	<tr><td>url</td><td>the url of the current page which contains DBMAN</td></tr>
	<tr><td>vars</td><td>url query string of variables to be passed through all links in DBMAN </td></tr>
	<tr><td>dbman_path</td><td>the server path to the DBMAN files</td></tr>
	<tr><td>list_limit</td><td>the maximum amount of records to show on a page in list view (default: 20)</td></tr>
</table>
<hr>

<h2 id="display">Display</h2>
<pre class="docs_code">
$dbman['display']['tabs'] = true;
$dbman['display']['legend'] = false;
</pre>
<p>
Display configurations provide the ability to show/hide functionality and features.
</p>
<strong>Display Properties</strong>
<table class="table table-bordered">
	<tr><td>tabs</td><td>show/hide the tab navigation</td></tr>
	<tr><td>legend</td><td>show/hide the icon legend</td></tr>
</table>
<hr>


<h2 id="database">Database Connection</h2>
<pre class="docs_code">
$dbman['db']['server'] = 'my_server';
$dbman['db']['user'] = 'my_admin';
$dbman['db']['password'] = 'my_password';
$dbman['db']['name'] = 'my_database';
$dbman['db']['table'] = 'my_table';
$dbman['db']['unique'] = 'my_unique_id';
$dbman['db']['query'] = 'Select field1, field2 FROM my_table WHERE active = 1';
</pre>
<p>
Provides database connectivity variables. These values typically point to global variables located in an external PHP file.
</p>
<strong>Connection Properties</strong>
<table class="table table-bordered">
	<tr><td colspan="2" class="docs_header_cell">Required Properties</td></tr>
	<tr><td>server</td><td>the name of the MySQL server</td></tr>
	<tr><td>user</td><td>the user for the database connection</td></tr>
	<tr><td>password</td><td>the password for the database connection</td></tr>
	<tr><td>name</td><td>the name of the database</td></tr>
	<tr><td>table</td><td>the name of the database table being connected</td></tr>
	<tr><td colspan="2" class="docs_header_cell">Optional Properties</td></tr>
	<tr><td>unique</td><td>the name of the unique identifier field in the table (defaults to 'id')</td></tr>
	<tr><td>query</td><td>the query string used when accessing data from the table (defaults to 'Select * From $table')</td></tr>
</table>

<hr>

<h2 id="permissions">Permissions</h2>
<pre class="docs_code">
$dbman['permissions']['delete'] = false;
$dbman['permissions']['edit'] = false;
$dbman['permissions']['create'] = false;
</pre>
<p>Permissions are all the different security settings that can be used with a particular instance of DBMAN. This allows developers to distinguish which functionality is accessible. By default all permissions are set to true.</p>


<strong>Permission Properties</strong>
<table class="table table-bordered">
	<tr><td colspan="2" class="docs_header_cell">Optional Properties</td></tr>
	<tr><td>delete</td><td>access to delete existing records in the table</td></tr>
	<tr><td>edit</td><td>access to modify existing records in the table</td></tr>
	<tr><td>create</td><td>access to create new records in the table</td></tr>
	<tr><td>view</td><td>access to view the information and data associated with a particular record</td></tr>
	<tr><td>list</td><td>access to list and search records</td></tr>
	<tr><td>import</td><td>access to import data from a CSV file</td></tr>
	<tr><td>child_delete</td><td>access to delete child records</td></tr>
	<tr><td>child_create</td><td>access to create child records</td></tr>
</table>

<hr>

<h2 id="list">List Options</h2>
<pre class="docs_code">
$dbman['list']['sort_column'] = 1;
$dbman['list']['sort_dir'] = 'asc';
$dbman['list']['limit'] = 30;
</pre>
<p>
List configurations allow you to customize the interaction of the list display.
</p>
<strong>List Properties</strong>
<table class="table table-bordered">
	<tr><td>limit</td><td>the limit of records to be displayed on a single page (pagination)</td></tr>
	<tr><td>sort_column</td><td>the column used for sorting (numeric value)</td></tr>
	<tr><td>sort_dir</td><td>the direction for the sorting (ASC or DESC)</td></tr>
</table>
<hr>

<h2 id="columns">Columns</h2>
<pre class="docs_code">
$dbman['columns'][] = array('label' =&gt; 'Name', 'id' =&gt; 'name');
$dbman['columns'][] = array('label' =&gt; 'Email', 'id' =&gt; 'email');
$dbman['columns'][] = array('label' =&gt; 'Date Created', 'id' =&gt; 'date_created', 'type' =&gt; 'date');
$dbman['columns'][] = array('label' =&gt; 'Bio', 'id' =&gt; 'bio', 'limit' =&gt; 100);
$dbman['columns'][] = array('label' =&gt; 'Active', 'id' =&gt; 'active', 'type' =&gt; 'boolean', 'yesValue' =&gt; 1, 'noValue' =&gt; 0);
$dbman['columns'][] = array('label' =&gt; 'Type', 'id' =&gt; 'type_id', 'type' =&gt; 'boolean', 'yesValue' =&gt; 1, 'noValue' =&gt; 0);
</pre>
<p>Columns are shown in the list view to display records in a spreadsheet format which are then searchable and sortable.</p>

<strong>Column Attributes</strong>
<table class="table table-bordered">
	<tr><td colspan="2" class="docs_header_cell">Required Attributes</td></tr>
	<tr><td>label</td><td>a text label used to describe the field</td></tr>
	<tr><td>id</td><td>the name of the field in the database</td></tr>
	<tr><td colspan="2" class="docs_header_cell">Optional Attributes</td></tr>
	<tr><td>type</td><td>data type (text, boolean, date, password, image) defaults to 'text'</td></tr>
	<tr><td>limit</td><td>limit data to character count</td></tr>
	<tr><td>link</td><td>hyperlink data value</td></tr>
	<tr><td>format</td><td>data format options - date: "m/d/Y h:i:s", boolean: "Yes,No"</td></tr>
	<tr><td>query</td><td>transform raw numeric data to corresponding labels in a separate table</td></tr>
	<tr><td>img_width</td><td>width of thumbnail image for field type of 'image'</td></tr>
	<tr><td>img_height</td><td>height of thumbnail image for field type of 'image'</td></tr>
</table>

<h2 id="actions">Actions</h2>
<pre class="docs_code">
$dbman['actions'][] = array('label' =&gt; 'My Custom Action', 'icon' =&gt; 'expand', 'url' =&gt; 'mypage.php?id={recID}');
</pre>
<p>Actions are displayed as icons in the list view next to each record. These typically included view, edit, and delete. Custom actions can be added to provide links to additional functionality associated with the unique ID of a particular record. The value of the record's unique ID is available using {recID}</p>

<strong>Action Attributes</strong>
<table class="table table-bordered">
	<tr><td colspan="2" class="docs_header_cell">Required Attributes</td></tr>
	<tr><td>label</td><td>the label used to identify the action being taken (displayed on mouseover of the icon)</td></tr>
	<tr><td>icon</td><td>an icon to be used to represent this action, icons are located in the images directory (do not include file extension)</td></tr>
	<tr><td>url</td><td>the url to open when the user clicks the icon, use {recID} as the unique identifier (example: index.php?id={recID})</td></tr>
</table>

<h2 id="search">Search</h2>
<pre class="docs_code">
$dbman['search'][] = array('label' =&gt; 'ID', 'id' =&gt; 'id');
$dbman['search'][] = array('label' =&gt; 'Name', 'id' =&gt; 'name');
$dbman['search'][] = array('label' =&gt; 'Email', 'id' =&gt; 'email');
</pre>
<p>Search fields are fields that are searchable within the list view of DBMAN. The selection of fields are displayed in a dropdown allowing the user to select which fields to search within.</p>

<strong>Search Attributes</strong>
<table class="table table-bordered">
	<tr><td colspan="2" class="docs_header_cell">Required Attributes</td></tr>
	<tr><td>label</td><td>the label used to identify the field being searched (displayed in dropdown)</td></tr>
	<tr><td>id</td><td>the name of the table field which will be searchable</td></tr>
</table>

<h2 id="fields">Fields</h2>
<pre class="docs_code">
$dbman['fields'][] = array('label' =&gt; 'Member Information', 'type' =&gt; 'header');
$dbman['fields'][] = array('label' =&gt; 'Name', 'value' =&gt; 'Sean', 'id' =&gt; 'name', 'type' =&gt; 'text', 'caption' =&gt; 'The first and last name of the member.', 'required' =&gt; true );
$dbman['fields'][] = array('label' =&gt; 'Active', 'value' =&gt; 1, 'id' =&gt; 'active', 'type' =&gt; 'boolean', 'required' =&gt; true, 'caption' =&gt; 'Is this member's account active?');
$dbman['fields'][] = array('label' =&gt; 'Email', 'id' =&gt; 'email', 'type' =&gt; 'text', 'caption' =&gt; 'The email address of the member.', 'required' =&gt; false );
$dbman['fields'][] = array('label' =&gt; 'Password', 'id' =&gt; 'password', 'type' =&gt; 'password', 'caption' =&gt; 'A secure password for the member.', 'required' =&gt;  true);
$dbman['fields'][] = array('label' =&gt; 'Date Created', 'id' =&gt; 'date_created', 'type' =&gt; 'date', 'value' =&gt; date('Y-m-d'), 'caption' =&gt; 'The date the member was registered.', 'required' =&gt; true );
$dbman['fields'][] = array('label' =&gt; 'Use Bio', 'value' =&gt; 0, 'id' =&gt; 'use_bio', 'type' =&gt; 'boolean', 'required' =&gt; true, 'caption' =&gt; 'Include bio description on profile page?');
$dbman['fields'][] = array('label' =&gt; 'Bio', 'id' =&gt; 'bio', 'condition_field' =&gt; 'use_bio', 'condition_value' =&gt; 1, 'type' =&gt; 'textarea', 'caption' =&gt; 'The descriptive paragraph for the member's profile.', 'required' =&gt; false );
</pre>
<p>Fields are the individual table fields and their associated data types which will be displayed on create/edit/view pages.</p>

<strong>Field Attributes</strong>
<table class="table table-bordered">
	<tr><td colspan="2" class="docs_header_cell">Required Attributes</td></tr>
	<tr><td>label</td><td>a text label used to describe the field</td></tr>
	<tr><td>id</td><td>the name of the field in the database</td></tr>
	<tr><td>type</td><td>the type of field to display</td></tr>
	<tr><td colspan="2" class="docs_header_cell">Optional Attributes</td></tr>
	<tr><td>caption</td><td>a brief description of the field</td></tr>
	<tr><td>required</td><td>require the field to have a value upon submit</td></tr>
	<tr><td>value</td><td>the default value for this field</td></tr>
	<tr><td>prefix</td><td>any text that should be displayed before the field value</td></tr>
	<tr><td>suffix</td><td>any text that should be displayed after the field value</td></tr>
	<tr><td>link</td><td>hyperlink data value</td></tr>
	<tr><td>format</td><td>format options based on field type - date: "m/d/Y h:i:s", boolean: "Yes,No"</td></tr>
	<tr><td>upload_path</td><td>used with file upload fields as the upload destination path</td></tr>
	<tr><td>file_path</td><td>used with file upload fields as the URL destination path</td></tr>
	<tr><td>condition_field</td><td>name of field to use when determining if the field should be shown</td></tr>
	<tr><td>condition_value</td><td>value of condition field</td></tr>
	<tr><td>query</td><td>a MySQL query to pull data which can be fed to a dynamic field type</td></tr>
	<tr><td>filter</td><td>?</td></tr>
	<tr><td>dynamic_type</td><td>the type of selection input used for the dynamic list field type, options are dropdown, search, checkbox, select, and color</td></tr>
	<tr><td>dynamic_value</td><td>the unique ID field which holds the input values to be associated with each selection item</td></tr>
	<tr><td>dynamic_label</td><td>the field which holds labels which correspond to their unique ids, this is the value the user will see in the dropdown</td></tr>

</table>
<strong>Field Data Types</strong>
<table class="table table-bordered">
	<tr><td colspan="2" class="docs_header_cell">Basic Field Types</td></tr>
	<tr><td>header</td><td>not an input field and has no relation to any data, only a header label for display purposes</td></tr>
	<tr><td>text</td><td>standard text input</td></tr>
	<tr><td>textarea</td><td>standard textarea</td></tr>
	<tr><td>password</td><td>double text input which must be matched</td></tr>
	<tr><td>date</td><td>a date or datetime</td></tr>
	<tr><td>boolean</td><td>yes or no</td></tr>
	<tr><td>custom</td><td>work in progress</td></tr>
	<tr><td>html</td><td><a href="ckeditor.com" target="_blank">ckeditor</a> is used as the HTML editor via a CDN hosted script, no local files are necessary</td></tr>
	<tr><td>dynamic</td><td>selection field using data from a specified database table</td></tr>

	<tr><td colspan="2" class="docs_header_cell">Dynamic Field Types</td></tr>
	<tr><td>dropdown</td><td>selection input fed by dynamic data</td></tr>
	<tr><td>search</td><td>selection input fed by dynamic data</td></tr>
	<tr><td>checkbox</td><td>selection input fed by dynamic data</td></tr>
	<tr><td>select</td><td>selection input fed by dynamic data</td></tr>
	<tr><td>color</td><td>selection input fed by dynamic data</td></tr>
	
	<tr><td colspan="2" class="docs_header_cell">File Content Types</td></tr>
	<tr><td>file</td><td>uploads a file upon submission of the form</td></tr>
	<tr><td>file_content</td><td>writes a file, does not save to database</td></tr>
</table>



<!--
<h2>Children</h2>
<pre class="docs_code">
$dbman['child']['header'] = &quot;Related Items&quot;;
$dbman['child']['table'] = $config['tables']['users'];
$dbman['child']['id'] = &quot;parent_id&quot;;
$dbman['child']['fields'][] = array('label' =&gt; 'Name', 'id' =&gt; 'name', 'type' =&gt; 'text', 'caption' =&gt; 'sd fasdfasdf asdf asdf adsf ', 'required' =&gt;  false);
$dbman['child']['fields'][] = array('label' =&gt; 'Email', 'id' =&gt; 'email', 'type' =&gt; 'text', 'caption' =&gt; 'sd fasdfasdf asdf asdf adsf ', 'required' =&gt;  false);
$dbman['child']['fields'][] = array('label' =&gt; 'Sent', 'value' =&gt; 0, 'id' =&gt; 'sent', 'type' =&gt; 'boolean', 'required' =&gt; true, 'yesValue' =&gt; 1, 'noValue' =&gt; 0, 'caption' =&gt; 'Has the email been sent for this invitee.');
</pre>
-->

<h2 id="output">Output</h2>
<pre class="docs_code">
new dbman($dbman);
</pre>
<p>This line outputs the DBMAN instance by calling the DBMAN constructor and passing all configuration data using the array created above.</p>


<h2 id="summary">All Together Now</h2>
<pre class="docs_code">
<?php 
highlight_string("<?php

//initialize
require '../dbman.php';
\$dbman = array();

//configurations
\$dbman['url'] = '?index.php';
\$dbman['vars'] = 'view=demos/members';

//database connection
\$dbman['db']['server'] = 'localhost';
\$dbman['db']['user'] = 'admin';
\$dbman['db']['password'] = 'pass';
\$dbman['db']['name'] = 'mydatabase';
\$dbman['db']['table'] = 'mytable';

//search
\$dbman['search'][] = array('label' => 'ID', 'id' => 'id');
\$dbman['search'][] = array('label' => 'Name', 'id' => 'name');
\$dbman['search'][] = array('label' => 'Email', 'id' => 'email');
\$dbman['search'][] = array('label' => 'Bio', 'id' => 'bio');

//list
\$dbman['columns'][] = array('label' => 'Name', 'id' => 'name');
\$dbman['columns'][] = array('label' => 'Email', 'id' => 'email');
\$dbman['columns'][] = array('label' => 'Date Created', 'id' => 'date_created', 'type' => 'date');
\$dbman['columns'][] = array('label' => 'Bio', 'id' => 'bio', 'limit' => 100);
\$dbman['columns'][] = array('label' => 'Active', 'id' => 'active', 'type' => 'boolean', 'yesValue' => 1, 'noValue' => 0);
\$dbman['columns'][] = array('label' => 'Type', 'id' => 'type_id', 'type' => 'boolean', 'yesValue' => 1, 'noValue' => 0);

//actions
\$dbman['actions'][] = array('label' => 'Call Tags', 'icon' => 'temp', 'url' => '/admin/calltags/{recID}');
\$dbman['actions'][] = array('label' => 'Work Orders', 'icon' => 'temp', 'url' => '/admin/orders/{recID}');

//fields
\$dbman['fields'][] = array('label' => 'Member Information', 'type' => 'header');
\$dbman['fields'][] = array('label' => 'Name', 'value' => 'Sean', 'id' => 'name', 'type' => 'text', 'caption' => 'The first and last name of the member.', 'required' => true );
\$dbman['fields'][] = array('label' => 'Active', 'value' => 1, 'id' => 'active', 'type' => 'boolean', 'required' => true, 'caption' => 'Is this member\'s account active?');
\$dbman['fields'][] = array('label' => 'Email', 'id' => 'email', 'type' => 'text', 'caption' => 'The email address of the member.', 'required' => false );
\$dbman['fields'][] = array('label' => 'Password', 'id' => 'password', 'type' => 'password', 'caption' => 'A secure password for the member.', 'required' =>  true);
\$dbman['fields'][] = array('label' => 'Date Created', 'id' => 'date_created', 'type' => 'date', 'value' => date('Y-m-d'), 'caption' => 'The date the member was registered.', 'required' => true );
\$dbman['fields'][] = array('label' => 'Use Bio', 'value' => 1, 'id' => 'use_bio', 'type' => 'boolean', 'required' => true, 'caption' => 'Include bio description on profile page?');
\$dbman['fields'][] = array('label' => 'Bio', 'id' => 'bio', 'condition_field' => 'use_bio', 'condition_value' => 1, 'type' => 'textarea', 'caption' => 'The descriptive paragraph for the member\'s profile.', 'required' => false );
\$dbman['fields'][] = array('label' => 'Additional Information', 'type' => 'header');
\$dbman['fields'][] = array('label' => 'Avatar Photo', 'id' => 'avatar', 'type' => 'file', 'file_path' => '/docs/uploads/', 'caption' => 'A picture of yourself to use as your avatar.');

//children
\$dbman['child']['header'] = 'Related Items';
\$dbman['child']['table'] = 'mytable';
\$dbman['child']['id'] = 'parent_id';
\$dbman['child']['fields'][] = array('label' => 'Name', 'id' => 'name', 'type' => 'text', 'caption' => 'sd fasdfasdf asdf asdf adsf ', 'required' =>  false);
\$dbman['child']['fields'][] = array('label' => 'Email', 'id' => 'email', 'type' => 'text', 'caption' => 'sd fasdfasdf asdf asdf adsf ', 'required' =>  false);
\$dbman['child']['fields'][] = array('label' => 'Sent', 'value' => 0, 'id' => 'sent', 'type' => 'boolean', 'required' => true, 'yesValue' => 1, 'noValue' => 0, 'caption' => 'Has the email been sent for this invitee.');

//output
new dbman($dbman);

?>");
?>
</pre>
