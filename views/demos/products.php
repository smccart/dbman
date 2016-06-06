<h2>Product Catalog</h2>
<div class="example-info">
	<p>
		<i class="fa fa-info-circle"></i>
		This example shows how DBMan can be used to create an administration panel for store products. This example utilizes fields like date, boolean, password, image, and dynamic dropdown.
	</p>
</div>
<?php

function onInsert($recID) {

}
function onUpdate($recID) {

}
	
function onDelete($recID) {

}


//initialize
$dbman = array();


//configurations
$dbman['url'] = "/examples/products/";
$dbman['vars'] = '';
$dbman['debug'] = false;

//database connection
$dbman['db']['server'] = $config['db_server'];
$dbman['db']['user'] = $config['db_user'];
$dbman['db']['password'] = $config['db_password'];
$dbman['db']['name'] = $config['db_name'];
$dbman['db']['table'] = "shop_products";

//permissions
$dbman['permissions']['import'] = true;
$dbman['permissions']['calendar'] = false;

//search
$dbman['search'][] = array('label' => 'ID', 'id' => 'id');
$dbman['search'][] = array('label' => 'Title', 'id' => 'title');
$dbman['search'][] = array('label' => 'Description', 'id' => 'descr');

//list
$dbman['columns'][] = array('label' => 'Product', 'id' => 'title', 'type' => 'text');
$dbman['columns'][] = array('label' => 'Price', 'id' => 'price', 'type' => 'text', 'prefix' => '$');
$dbman['columns'][] = array('label' => 'Active', 'id' => 'active', 'type' => 'boolean', 'yesValue' => 1, 'noValue' => 0, 'format' => 'On,OFF');
$dbman['columns'][] = array('label' => 'Inventory', 'id' => 'title', 'type' => 'text');
$dbman['columns'][] = array('label' => 'Date Created', 'id' => 'date_created', 'type' => 'date');


//fields
$dbman['fields'][] = array('label' => 'Product Information', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Title', 'value' => '', 'id' => 'title', 'type' => 'text', 'caption' => 'The title of the gallery image.', 'required' => false );
$dbman['fields'][] = array('label' => 'Alias', 'value' => '', 'id' => 'alias', 'type' => 'text', 'caption' => 'Alias used for search engine friendly URLs.', 'required' => true );
$dbman['fields'][] = array('label' => 'Active', 'value' => 1, 'id' => 'active', 'type' => 'boolean', 'format' => 'Yes,No', 'required' => false, 'caption' => 'Is this gallery image active?');

$dbman['fields'][] = array('label' => 'Product Images', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Thumbnail', 'id' => 'thumb', 'type' => 'file', 'file_path' => './images/uploads/products/thumbs/', 'caption' => 'The gallery image or photo.');
$dbman['fields'][] = array('label' => 'Image', 'id' => 'img', 'type' => 'file', 'file_path' => './images/uploads/products/', 'caption' => 'The gallery image or photo.');

$dbman['fields'][] = array('label' => 'Product Details', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Price', 'value' => '', 'id' => 'price', 'type' => 'text', 'caption' => 'The title of the gallery image.', 'required' => false );
$dbman['fields'][] = array('label' => 'Inventory', 'value' => '', 'id' => 'inventory', 'type' => 'text', 'caption' => 'The title of the gallery image.', 'required' => false );
$dbman['fields'][] = array('label' => 'Backorder', 'value' => '', 'id' => 'backorder', 'type' => 'text', 'caption' => 'The title of the gallery image.', 'required' => false );
$dbman['fields'][] = array('label' => 'Date Created', 'id' => 'date_created', 'type' => 'date', 'value' => date('Y-m-d'), 'caption' => 'The date the member was registered.', 'required' => false );

$dbman['fields'][] = array('label' => 'Product Description', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Intro', 'value' => '', 'id' => 'intro', 'type' => 'textarea', 'caption' => 'The title of the gallery image.', 'required' => false );
$dbman['fields'][] = array('label' => 'Description', 'value' => '', 'id' => 'descr', 'type' => 'html', 'caption' => 'The description of the image.', 'required' => false );

$dbman['fields'][] = array('label' => 'Product Attributes', 'type' => 'header');
$dbman['fields'][] = array('label' => 'Size', 'id' => 'size', 'type' => 'dynamic', 'query' => 'Select * from shop_product_sizes', 'dynamic_type' => 'dropdown', 'dynamic_value' => 'id', 'dynamic_label' => 'name', 'caption' => 'The membership type.', 'required' => false );
$dbman['fields'][] = array('label' => 'Status', 'id' => 'status', 'type' => 'dynamic', 'query' => 'Select * from shop_product_status', 'dynamic_type' => 'dropdown', 'dynamic_value' => 'id', 'dynamic_label' => 'name', 'caption' => 'The membership type.', 'required' => false );
$dbman['fields'][] = array('label' => 'Group', 'value' => 1, 'id' => 'groups', 'type' => 'dynamic', 'query' => 'Select * from shop_groups', 'dynamic_type' => 'checkbox', 'dynamic_value' => 'id', 'dynamic_label' => 'name', 'caption' => 'The membership type.', 'required' => false );


//events for callback functions
$dbman['events']['insert'] = "onInsert";
$dbman['events']['delete'] = "onDelete";
$dbman['events']['update'] = "onUpdate";

//output
new dbman($dbman);

?>

