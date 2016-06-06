<?php
/**************************************************************************************
* Class: DBMAN - Database Management Utility Class
* Author: Sean McCart
****************************************************************************************/

//start a session to hold config data so we can use direct ajax calls


//DIRECT AJAX CALLS
if(isset($_GET['ajax']))
{

	session_start();
	if(isset($_SESSION['dbman']))
	{
		new dbman($_SESSION['dbman'], $_GET['ajax']);
	}
	else
	{
		echo "ERROR: There is no configuration instance available in the session";
		//$_SESSION['test'] = "test";
		//echo var_dump($_SESSION);
	}
}

//DBMAN CLASS
class dbman 
{

	//DBMAN CONSTRUCTOR
	function dbman($config, $ajax = false) 
	{
		//save config array to session so its available in direct ajax requests
		$_SESSION['dbman'] = $config;
		$_SESSION['test'] = "test";
		
		//assign configuration data
		$this->config = $config;
		
		//debug - for debugging purposes, shows all errors
		$this->_debug = (isset($config['debug'])) ? $config['debug'] : false;
		
		//connection information
		$dbServer = (isset($config['db']['server'])) ? $config['db']['server'] : "localhost";
		$dbUser = (isset($config['db']['user'])) ? $config['db']['user'] : "root";
		$dbName = (isset($config['db']['name'])) ? $config['db']['name'] : "zeitgeist";
		$dbPass =(isset($config['db']['password'])) ? $config['db']['password'] : "root";
		$this->_dbTable = (isset($config['db']['table'])) ? $config['db']['table'] : NULL;
		$this->_recsUniqueID = (isset($config['db']['unique'])) ? $config['db']['unique'] : "id";
		$this->_recsQuery = (isset($config['db']['query'])) ? $config['db']['query'] : "SELECT * FROM ".$this->_dbTable;
		
		//set dbman location
		$this->_dbmanPath = (isset($config['dbman_path'])) ? $config['dbman_path'] : "/dbman/";
		$this->_dbmanURL = "http://".$_SERVER['HTTP_HOST'].$this->_dbmanPath;
		
		//html editor path
		$this->_htmlEditorBasePath = (isset($config['fckeditor_path'])) ? $config['fckeditor_path'] : "/dbman/fckeditor/";
		$this->_htmlEditorInclude = $this->_htmlEditorBasePath."fckeditor_php5.php";
		
		//temp path (used for uploading CSV files, must be writable)
		$this->_tempPath = (isset($config['temp_path'])) ? $config['temp_path'] : "temp/";
		
		//icon path (path to dbman icon images)
		$this->_iconPath = (isset($config['icon_path'])) ? $config['icon_path'] : $this->_dbmanURL."images/";
		
		//row link
		$this->_recRowLink = (isset($config['row_link'])) ? $config['row_link'] : NULL;
		
		//icons
		$this->_deleteIcon = (isset($config['icons']['delete'])) ? $config['icons']['delete'] : $this->_iconPath."delete.png";
		$this->_ascIcon = (isset($config['icons']['sort_asc'])) ? $config['icons']['sort_asc'] : $this->_iconPath."sort_asc.gif";
		$this->_descIcon = (isset($config['icons']['sort_desc'])) ? $config['icons']['sort_desc'] : $this->_iconPath."sort_desc.gif";

		//calendar fields
		$this->_calendarFields = (isset($config['calendar'])) ? $config['calendar'] : NULL;
		
		//permissions
		$this->_allowDelete = (isset($config['permissions']['delete'])) ? $config['permissions']['delete'] : true;
		$this->_allowEdit = (isset($config['permissions']['edit'])) ? $config['permissions']['edit'] : true;
		$this->_allowView = (isset($config['permissions']['view'])) ? $config['permissions']['view'] : true;
		$this->_allowCreate = (isset($config['permissions']['create'])) ? $config['permissions']['create'] : true;
		$this->_allowImport = (isset($config['permissions']['import'])) ? $config['permissions']['import'] : true;
		$this->_allowList = (isset($config['permissions']['list'])) ? $config['permissions']['list'] : true;
		$this->_childAllowDelete = (isset($config['permissions']['child_delete'])) ? $config['permissions']['child_delete'] : true;
		$this->_childAllowCreate = (isset($config['permissions']['child_create'])) ? $config['permissions']['child_create'] : true;
		
		
		
		//show/hide elements
		$this->_showTabs = (isset($config['display']['tabs'])) ? $config['display']['tabs'] : true;
		$this->_showLegend = (isset($config['display']['legend'])) ? $config['display']['legend'] : true;
		
		//list settings
		$this->_recsLimit = (isset($config['list']['limit'])) ? $config['list']['limit'] : 20;
		$this->_defaultSortDirection = (isset($config['list']['sort_dir'])) ? $config['list']['sort_dir'] : "ASC";
		$this->_defaultSortColumn = (isset($config['list']['sort_column'])) ? $config['list']['sort_column'] : 0;
		
		//general settings
		$this->_baseURL = (isset($config['url'])) ? $config['url'] : 'index.php';
		$this->_URLvars = (isset($config['vars'])) ? $config['vars'] : '';
		
		//defaults
		$this->_defaultFilter = (isset($config['defaults']['filter'])) ? $config['defaults']['filter'] : 0;
		$this->_defaultAction = (isset($config['defaults']['action'])) ? $config['defaults']['action'] : "list";
		
		
		//fields
		$this->_fields = (isset($config['fields'])) ? $config['fields'] : array();
		
		//records
		$this->_recs = (isset($config['columns'])) ? $config['columns'] : array();
		
		//search
		$this->_searchQuery = "Select * FROM ".$this->_dbTable." WHERE {searchField} LIKE '%{criteria}%'";
		$this->_searchFields = (isset($config['search'])) ? $config['search'] : NULL;
		
		//figure out the main action being taken
		$this->set_action();
	
		//set sorting variables
		$this->set_sort();
		
		//set filter variables
		$this->set_filter();
		
		//actions
		$actions = (isset($config['actions'])) ? $config['actions'] : array();
		if(!isset($actions['view']) && $this->_allowView) $actions['view'] = array('label' => 'View', 'icon' => 'view', 'url' => "$this->_baseURL?$this->_URLvars&action=view&recID={recID}&sort={$this->sortOrder}&sortDir={$this->sortDir}");
		if(!isset($actions['edit']) && $this->_allowEdit) $actions['edit'] = array('label' => 'Edit', 'icon' => 'edit', 'url' => "$this->_baseURL?$this->_URLvars&action=edit&recID={recID}&sort={$this->sortOrder}&sortDir={$this->sortDir}");
		if(!isset($actions['delete']) && $this->_allowDelete) $actions['delete'] = array('label' => 'Delete', 'icon' => 'delete', 'url' => 'javascript:verifyDelete({recID});');
		$this->_actions = $actions;
			
		//child data	
		$this->_childFields = (isset($config['child']['fields'])) ? $config['child']['fields'] : NULL;
		$this->_childParentID = (isset($config['child']['id'])) ? $config['child']['id'] : NULL;
		$this->_childTable = (isset($config['child']['table'])) ? $config['child']['table'] : NULL;
		$this->_childHeader = (isset($config['child']['header'])) ? $config['child']['header'] : NULL;
		
		
		//events
		$this->_onUpdate = (isset($config['events']['update'])) ? $config['events']['update'] : NULL;
		$this->_onDelete = (isset($config['events']['delete'])) ? $config['events']['delete'] : NULL;
		$this->_onInsert = (isset($config['events']['insert'])) ? $config['events']['insert'] : NULL;
		
		//active record id
		$this->_recID = 0;
		
		//make db connection
		$this->db_connection($dbServer, $dbUser, $dbPass, $dbName);
		
		//output based on request type
		if($ajax === false)
		{
			$this->full_request();
		}
		else
		{
			$this->ajax_request($ajax);
		}
	}
	
	//db_connection - makes a connection to a database and host
	function db_connection($host, $user, $password, $database) 
	{
		$this->_conn = @mysql_connect($host, $user, $password) or die("DB CONNECTION FAILED");
		@mysql_select_db($database, $this->_conn) or die("DB SELECTION FAILED");
	}
	
	//run a query on the database
	function query($query) 
	{
		$query = mysql_query($query);
		$errorNum = mysql_errno($this->_conn);
		if($errorNum != 0){
			echo mysql_errno($this->_conn) . ": " . mysql_error($this->_conn) . "\n";
			$this->_queryError = mysql_error($this->_conn);
		}
		
		return $query;
	}
	
	//ajax request - outputs only requested data (STILL UNDER DEVELOPMENT)
	function ajax_request($type)
	{
		//start building output
		$output = "";
		
		//perform an action
		switch($this->action)
		{
			case "insert" :
				
				$recID = $this->insert_rec();
				$output .= "Success. record has been inserted.";
				
				break;
			
			case "delete" :
				
				break;
			
			case "update" :
			
				break;
				
			case "list" :
				$this->output_list();
				
				break;
		}
		
		//output
		echo $output;
	}
	
	//full request - outputs the entire dbman page
	function full_request()
	{
	
		//set active record ID if available
		if(isset($_GET['recID']))
		{
			$this->_recID = $_GET['recID'];
		}
		else
		{
			$result = $this->query($this->_recsQuery." ORDER BY {$this->_recs[$this->sortOrder]['id']} {$this->sortDir}");
			$row = mysql_fetch_array($result);
			$recID = $row[$this->_recsUniqueID];
			$this->_recID = $recID;
		}
	
		//if action is defined then perform an action
		switch($this->action)
		{
			case "update" :
			
				$this->update_rec($_GET['recID']);
				$this->redirect($_GET['recID'], "update");

				break;
			
			case "delete" :
			
				$this->delete_rec($_GET['recID']);
				$this->redirect($_GET['recID'], "delete");
			
				break;
				
			case "insert_bulk" :
			
				$recIDList = $this->insert_bulk();
				$this->redirect($recIDList, "insert_bulk");
			
				break;
				
			case "insert" :
			
				$recID = $this->insert_rec();
				$this->redirect($recID, "insert");
				
				break;
				
			case "list" :
			
				echo "<div id=\"dbman\"> \n";
				$this->output_header();
				echo "<div id=\"frame\"> \n";
				$this->output_list();
				echo "</div> \n";
				$this->output_legend();
				echo "</div> \n";
			
				break;
				
			case "calendar" :
			
				echo "<div id=\"dbman\"> \n";
				$this->output_header();
				echo "<div id=\"frame\"> \n";
				$this->output_calendar();
				echo "</div> \n";
				$this->output_legend();
				echo "</div> \n";
			
				break;
				
			case "edit" :
			
				echo "<div id=\"dbman\"> \n";
				$this->output_header();
				echo "<div id=\"frame\"> \n";
				$this->output_form('edit');
				echo "</div> \n";
				echo "</div> \n";
			
				break;
				
			case "create" :
			
				echo "<div id=\"dbman\"> \n";
				$this->output_header();
				echo "<div id=\"frame\"> \n";
				$this->output_form('create');
				echo "</div> \n";
				echo "</div> \n";
			
				break;
				
			case "view" :
			
				echo "<div id=\"dbman\"> \n";
				$this->output_header();
				echo "<div id=\"frame\"> \n";
				$this->output_view();
				echo "</div> \n";
				echo "</div> \n";
			
				break;
				
			case "import" :
			
				echo "<div id=\"dbman\"> \n";
				$this->output_header();
				echo "<div id=\"frame\"> \n";
				$this->output_import();
				echo "</div> \n";
				echo "</div> \n";
				
		}
	}
	
	//output_success_message - outputs a success message after a form submit
	function output_success_message($message)
	{
		echo "<div class=\"success\">$message</div>";
	}
	
	//output_error_message - outputs an error message after a form submit
	function output_error_message($message)
	{
		echo "<div class=\"error\">$message</div>";
	} 
	
	//redirect - outputs javascript that will cause the page to redirect after a number of seconds
	function redirect($recID = "", $event = "")
	{
		if(isset($this->_URLAfterFormSubmit)){
			$link = $this->_URLAfterFormSubmit;
		}else{
			$link =  $this->_baseURL."?".$this->_URLvars;
		}
		
		echo "<script language=\"javascript\" type=\"text/javascript\"> \n";
		if(isset($this->_queryError))
		{
			echo "document.location = \"{$link}&error={$this->_queryError}\"";
		}
		else
		{
			if(!$this->_debug) echo "document.location = \"{$link}&success=1&recID={$recID}&event={$event}\"";
		}	
		echo "</script>";
	}
	
	//upload_files - uploads files that were sent via the form
	function upload_files($recID)
	{
		//set function vars
		$fields = $this->_fields;
		$fieldsCount = count($fields);
		
		//replace {recID} with the record ID
		for($i=0; $i<$fieldsCount; $i++)
		{
			if($fields[$i]['type'] == 'file')
			{
				$filePath = str_replace("{recID}",$recID,$fields[$i]['upload_path']);
				//if file path does not exist create it
				if(!file_exists($filePath)) mkdir($filePath);
			}
		}
		
		//upload the files
		for($i=0; $i<$fieldsCount; $i++)
		{
			if($fields[$i]['type'] == 'file')
			{
				$fileField = 'form_' .$fields[$i]['id'];
				if($_FILES[$fileField]['name'] != "")
				{
					$uploadfile = $filePath . $_FILES[$fileField]['name'];
					move_uploaded_file($_FILES[$fileField]['tmp_name'], $uploadfile);
					chmod($uploadfile, 0777);
				} 
			}
		}
	}

	//write_files - write files
	function write_files()
	{
		//set function vars 
		$fields = $this->_fields;
		$fieldsCount = count($fields);
		
		//write the files
		for($i=0; $i<$fieldsCount; $i++)
		{
			if($fields[$i]['type'] == 'file_content')
			{
				$file = $fields[$i]['path'].$_POST['form_' .$fields[$i]['file_name_field']].'.'.$fields[$i]['file_extension'];
				$fh = fopen($file, 'w');
				$content = stripslashes($_POST['form_' .$fields[$i]['id']]);
				fwrite($fh, $content);
				fclose($fh);
				chmod($file, 0777);
			}
		} 
	}

	//delete file 
	function delete_file($file)
	{
		@unlink($file);
	}
	
	//delete_rec - deletes a record from the table
	function delete_rec($recID)
	{
	
		//set function vars
		$fields = $this->_fields;
		$fieldsCount = count($fields);
		
		$recID = trim($recID, ",");
		$recID = explode(",", $recID);
		
		foreach($recID as $ID)
		{	
			$deleteQuery = "DELETE FROM ".$this->_dbTable." WHERE ".$this->_recsUniqueID." = ".$ID;
			$this->query($deleteQuery);
			if(isset($this->_onDelete)) call_user_func($this->_onDelete, $ID);
		}
	}
	
	//insert_bulk - handles a CSV import to insert multiple records
	function insert_bulk()
	{
		$newRecIDs = array();
		$csvFile = $this->_tempPath.$_POST['csvFileName'];
		if (($handle = fopen($csvFile, "r")) !== FALSE) 
		{
			$rowCount = 0;
			while (($data = fgetcsv($handle, 0, ",")) !== FALSE) 
			{
				if($rowCount != 0)
				{
					//set data row
					$row = array();
					$csvFields = explode(',', $_POST['csvFieldList']);
					$inc = 0;
					$emptyRow = true;
					foreach($csvFields as $csvField)
					{
						if($data[$inc] != "") $emptyRow = false;
						$row[$csvField] = $data[$inc];
		    			$inc++;
					}
				
					if(!$emptyRow)
					{
						//set fields and values for insert query
						$fields = array();
						$values = "";
						$inc = 0;
						foreach($this->_fields as $field)
						{
							if($field['type'] != 'header')
							{
								$fields[] = $field['id'];
								$selectFieldValue = $_POST['form_'.$field['id'].'_select'];
								if($selectFieldValue == '__custom__')
								{
									$values .= "'".$_POST['form_'.$field['id']]."'";
								}
								elseif($selectFieldValue == '__default__')
								{
									$values .= "'".$field['value']."'";
								}
								else
								{
									$values .= "'".$row[$selectFieldValue]."'";
								}
								if($inc < count($this->_fields) - 1)
								{
									$values .= ", ";
								}
							}
							$inc++;
						}
						$fields = implode($fields, ',');
						
						//run insert query
						$insertQuery = "INSERT INTO ".$this->_dbTable." ($fields) VALUES ($values)";
						//echo $insertQuery."<hr/>";
						$this->query($insertQuery);
						
						//get new rec id
						$maxIDresult = $this->query("SELECT MAX({$this->_recsUniqueID}) as maxID FROM {$this->_dbTable}");
						$maxIDrow = mysql_fetch_array($maxIDresult);
						$newRecIDs[] = $maxIDrow['maxID'];
					}
				}
		    	$rowCount++;
			}
		    fclose($handle);
		}
		
		//return all new record ids
		$newRecIDs = implode($newRecIDs, ',');
		return $newRecIDs;
	}
		
	//update_rec - updates a record in the table
	function update_rec($recID)
	{
		//set function vars
		$fields = $this->_fields;
		$fieldsCount = count($fields);
		
		//upload any files if necessary
		$this->upload_files($recID);
		
		//write any files if necessary
		$this->write_files();
		
		//start the update statement
		$updateQuery = "UPDATE ".$this->_dbTable." SET ";
		
		$formFieldValues = array();
		
		//loop through fields to create the update statement
		for($i=0; $i<$fieldsCount; $i++){
		
			//don't perform any actions if the field is a header
			if($fields[$i]['type'] != 'header' && $fields[$i]['type'] != 'file_content'){
			
				//put comma after statement unless it's the last statement
				if($i == ($fieldsCount - 1) || $fields[($fieldsCount - 1)]['type'] == 'file_content' && $i == ($fieldsCount - 2)){
					$comma = '';
				}else{
					$comma = ',';
				}
				
				//set the fileField variable
				if($fields[$i]['type'] == 'file'){
					$fileField = "form_" .$fields[$i]['id'];
					
					if($_FILES[$fileField]['name'] != ""){
						$formValue = $_FILES[$fileField]['name'];
					}else{
						if(isset($_POST['form_'.$fields[$i]['id'].'REMOVE'])){
							$formValue = "";
						}else{
							$formValue = $_POST['form_'.$fields[$i]['id'].'HIDDEN'];
						}
					}
				
				//set the date field value
				}else if($fields[$i]['type'] == "date"){
				
					$yearValue = $_POST["form_year" .$fields[$i]['id']];
					$monthValue = $_POST["form_month" .$fields[$i]['id']];
					$dayValue = $_POST["form_day" .$fields[$i]['id']];
					$hourValue = $_POST["form_hour" .$fields[$i]['id']];
					$minuteValue = $_POST["form_minute" .$fields[$i]['id']];
					$secondValue = $_POST["form_second" .$fields[$i]['id']];
					$formValue = date('Y-m-d h:i:s',mktime($hourValue, $minuteValue, $secondValue, $monthValue, $dayValue, $yearValue));
					
				//set the text field value
				}else{
				
					$formValue = $_POST['form_'.$fields[$i]['id']];
					
				}
				
				//if this is a dynamic list with a type of checkboxes then turn the field value into a comma seperated list
				if($fields[$i]['type'] == 'dynamic'){
					 if($fields[$i]['dynamic_type'] == "checkbox"){
					 
					 	if($formValue != "") $formValue = implode(",", $formValue);
					 	
					 }
					 else if($fields[$i]['dynamic_type'] == "colors"){
					 	if($formValue != "") $formValue = implode(",", $formValue);
					 }
				}
				
				$fieldArray = array('name' => $fields[$i]['id'], 'value' => $formValue);
				$formFieldValues[] = $fieldArray;
				
				//escape any dangerous characters
				$formValue = addslashes($formValue);
				
				
				$updateQuery .= $fields[$i]['id'] ." = '$formValue' $comma ";
			
			}
		}
		
		$updateQuery .= "where ".$this->_recsUniqueID." = ".$recID;
		$this->query($updateQuery);
		
		if(isset($this->_onUpdate)) call_user_func($this->_onUpdate, $recID);
		
	}
	
	//output_import - outputs a page allowing the user to import data
	function output_import() 
	{
	
		//if GET variable "upload" exists upload the CSV file
		if(isset($_GET['upload']))
		{
			//upload the file
			if($_FILES['form_csvFile']['name'] != "")
			{
				$uploadFile = $this->_tempPath . $_FILES['form_csvFile']['name'];
				$csvFileName = $_FILES['form_csvFile']['name'];
				move_uploaded_file($_FILES['form_csvFile']['tmp_name'], $uploadFile);
				chmod($uploadFile, 0777);	
			}
			//detect data in CSV
			$csvFields = array();
			$csvRowCount = 0;
			if (($handle = fopen($uploadFile, "r")) !== FALSE) 
			{
			
				$inc = 0;
				while (($data = fgetcsv($handle, 0, ",")) !== FALSE) 
				{
					$cols = count($data);
					if($inc == 0)
					{
						for ($c=0; $c < $cols; $c++) 
			   			{
			    			if($data[$c] != "") $csvFields[] = $data[$c];
			    		}
					}
					else
					{
						$emptyRow = true;
						for ($c=0; $c < $cols; $c++) 
			   			{
			    			if($data[$c] != "") $emptyRow = false;
			    		}
						if(!$emptyRow) $csvRowCount++;
					}
			    	$inc++;
				}
			    fclose($handle);
			}
			$csvFieldsCount = count($csvFields);
			$csvFieldsList = implode($csvFields, ' | ');
		}
	
		//variable to store final output
		$output = "";
		
		//create form and table header
		$output .= "<table cellpadding=\"0\" cellspacing=\"0\"> \n";
		$output .= "<tr><td colspan=\"2\" class=\"headerCell\">Import Data from CSV File</td></tr> \n";
		
		//start upload form
		$output .= "<form name=\"uploadCSVForm\" action=\"$this->_baseURL?$this->_URLvars&action=import&upload=1\" method=\"post\" enctype=\"multipart/form-data\"> \n";
		
		//input cells
		$output .= "<tr><td class=\"labelCell\">CSV File</td><td class=\"fieldCell\"><input type=\"file\" name=\"form_csvFile\" /> <div class=\"fieldCaption\">Choose a CSV file which contains the data you wish to import.</div></td></tr> \n";
		
		//form controls
		$output .= "<tr><td colspan=\"2\" align=\"right\" class=\"controlsCell\"><input type=\"submit\" class=\"button\" value=\"Load CSV File\" /></td></tr> \n";

		//close upload form
		$output .= "</form>";

		//CSV Field assignment
		if(isset($csvFields))
		{
			//start form
			$output .= "<form action=\"$this->_baseURL?$this->_URLvars&action=insert_bulk\" method=\"post\"> \n";
			
			//output header and file info
			$output .= "<tr><td colspan=\"2\" class=\"headerCell\">Import Settings ($csvFileName)</td></tr> \n";
			$output .= "<tr><td colspan=\"2\" class=\"controlsCell\"><div class=\"importStat\">File Name <span>$csvFileName</span></div><div class=\"importStat\">Columns <span>$csvFieldsCount</span></div><div class=\"importStat\">Rows <span>$csvRowCount</span></div><div class=\"importStat\">Fields <span>$csvFieldsList</span></div></td></tr> \n";
			
			//open content cell
			$output .= "<tr><td class=\"fieldCell\" colspan=\"2\">\n";
			
			//javascript
			$output .= "<script type=\"text/javascript\">\n";
			$output .= "	function onAssignField(field, target) { \n";
			$output .= "		var ddValue = target.value; \n";
			$output .= "		var customField = document.getElementById('custom_'+field);\n";
			$output .= "		if(ddValue == '__custom__') { \n";
			$output .= "			customField.style.display = 'block'; \n";
			$output .= "		}else{ \n";
			$output .= "			customField.style.display = 'none'; \n";
			$output .= "		}";
			$output .= "	}; \n";
			$output .= "</script> \n";
			
			//hidden form values
			$output .= "<input type=\"hidden\" name=\"csvFileName\" value=\"$csvFileName\" /> \n";
			$output .= "<input type=\"hidden\" name=\"csvFieldList\" value=\"".implode($csvFields, ',')."\" /> \n";

			//build field assignment table
			$output .= "<div class=\"subFrame\"><table cellpadding=\"0\" cellspacing=\"0\"> \n";

			foreach($this->_fields as $field)
			{
				if($field['type'] == 'header')
				{
					//display a header
					$output .= "<tr><td class=\"subHeaderCell\" colspan=\"2\">".$field['label']."</td></tr> \n";
				}
				else
				{	
					//build field dropdown
					$match = false;
					$dropdown = "<select name=\"form_".$field['id']."_select\" onchange=\"onAssignField('".$field['id']."', this);\">\n";
					$selected = "";
					foreach($csvFields as $csvField)
					{
						//check for name match
						if(strpos($field['id'], $csvField) !== false && $match == false || strpos($csvField, $field['id']) !== false && $match == false)
						{
							//match found - set the option as selected
							$match = true;
							$selected = "selected=\"selected\"";
						}
						else
						{
							$selected = "";
						}
						$dropdown .= "<option value=\"$csvField\" $selected>$csvField</option>\n";
					}	
					$dropdown .= "<option value=\"\">_________________________________</option>\n";
					$dropdown .= "<option value=\"__custom__\">Custom Value</option>\n";
					if(!$match) $selected = "selected=\"selected\"";
					$dropdown .= "<option value=\"__default__\" $selected>Default Value</option>\n";
					$dropdown .= "</select>\n";
					
					//build custom value field
					$inputField = $this->build_input_field($field);
					$custom = "<div style=\"display:none\" id=\"custom_".$field['id']."\">$inputField</div> \n";
					
					//display field assignment row
					$output .= "<tr><td width=\"125\" class=\"labelCell\" valign=\"top\">".$field['label']."</td><td class=\"fieldCell\">$dropdown $custom</td></tr> \n";
				}
			}
			$output .= "</table></div>";
			
			//close content cell
			$output .= "</td></tr>";
			
			//submit button
			$output .= "<tr><td colspan=\"2\" align=\"right\" class=\"controlsCell\"><input type=\"submit\" class=\"button\" value=\"Import Data\" /></td></tr> \n";
			
			//close form
			$output .= "</form> \n";
		}
		
		//close table and form
		$output .= "</table>";

		echo $output;
	}
	
	//output_calendar - outputs a calendar containing specified records from a database table
	function output_calendar() 
	{
		
		//output the necessary javascript
		$this->output_javascript();
		
		//get the calendar fields
		$calendarFields = $this->_calendarFields;
		
		//This gets today's date 
		$date =time () ;
		
		//This puts the day, month, and year in seperate variables 
		$day = date('d', $date) ; 
		$month = date('m', $date) ; 
		$year = date('Y', $date) ;
		
		//if query string variables exist use those
		if(isset($_GET['month'])) $month = $_GET['month'];
		if(isset($_GET['year'])) $year = $_GET['year'];
		
		//Here we generate the first day of the month 
		$first_day = mktime(0,0,0,$month, 1, $year) ; 
		
		//This gets us the month name 
		$title = date('F', $first_day) ; 
		
		//Here we find out what day of the week the first day of the month falls on 
		$day_of_week = date('D', $first_day) ; 
		
		//Once we know what day of the week it falls on, we know how many blank days occure before it. If the first day of the week is a Sunday then it would be zero
		switch($day_of_week){ 
			case "Sun": $blank = 0; break; 
			case "Mon": $blank = 1; break; 
			case "Tue": $blank = 2; break; 
			case "Wed": $blank = 3; break; 
			case "Thu": $blank = 4; break; 
			case "Fri": $blank = 5; break; 
			case "Sat": $blank = 6; break; 
		}
		
		//We then determine how many days are in the current month
		$days_in_month = cal_days_in_month(0, $month, $year) ; 
		
		//set the next month
		$nextMonth = $month + 1;
		$nextYear = $year;
		if($nextMonth == 13) 
		{
			$nextMonth = 1;
			$nextYear = $year + 1;
		}
		
		//set the previous month
		$prevMonth = $month - 1;
		$prevYear = $year;
		if($prevMonth == 0) 
		{
			$prevMonth = 12;
			$prevYear = $year - 1;
		}
		
		//Here we start building the table heads 
		echo "<form name=\"selectForm\">";
		echo "<table cellpadding=\"0\" cellspacing=\"0\">";
		echo "<tr><td colspan=\"7\" class=\"headerCell\"> <div style=\"float:left;margin-right:15px;\">$title $year</div> <div style=\"float:left\" ><a href=\"$this->_baseURL?$this->_URLvars&action=calendar&month={$prevMonth}&year={$prevYear}\" class=\"button\" style=\"margin-right:5px;\">&laquo; Previous Month</a> <a href=\"$this->_baseURL?$this->_URLvars&action=calendar&month={$nextMonth}&year={$nextYear}\" class=\"button\">Next Month &raquo;</a></div></td></tr>";
		echo "<tr><td class=\"controlsCell\" width=\"14%\"><b>S</b></td><td class=\"controlsCell\" width=\"14%\"><b>M</b></td><td class=\"controlsCell\"width=\"14%\"><b>T</b></td><td class=\"controlsCell\" width=\"14%\"><b>W</b></td><td class=\"controlsCell\" width=\"14%\"><b>T</b></td><td class=\"controlsCell\" width=\"14%\"><b>F</b></td><td class=\"controlsCell\" width=\"14%\"><b>S</b></td></tr>";
		
		//This counts the days in the week, up to 7
		$day_count = 1;
		
		echo "<tr>";
		//first we take care of those blank days
		while ( $blank > 0 ) 
		{ 
			echo "<td class=\"dataCell\" height=\"75\"></td>"; 
			$blank = $blank-1; 
			$day_count++;
		}

		//sets the first day of the month to 1 
		$day_num = 1;
		//count up the days, untill we've done all of them in the month
		while ( $day_num <= $days_in_month ) 
		{ 
		
			echo "<td class=\"dataCell\" height=\"75\" valign=\"top\"> <div class=\"dbman_calendar_day\">$day_num</div>";
			for($i=0;$i<count($calendarFields);$i++)
			{
				$valueField = $calendarFields[$i]['id'];
				$uniqueID = $this->_recsUniqueID;
				$table = $this->_dbTable;
				$dateField = $calendarFields[$i]['date_field'];
				$prevDay = $day_num - 1;
				$nextDay = $day_num + 1;
				$dateResult = $this->query("SELECT {$valueField}, {$uniqueID}, {$dateField} FROM {$table} WHERE {$dateField} >= '{$year}-{$month}-{$day_num}' AND {$dateField} < '{$year}-{$month}-{$nextDay}'");
				while($dateRow =  mysql_fetch_array($dateResult))
				{
					
					
					echo "<div><input type=\"checkbox\" name=\"deleteCB\" value=\"{$dateRow[1]}\"/><div id=\"actions{$dateRow[1]}\" onmouseover=\"showActions({$dateRow[1]});\" onmouseout=\"hideActions({$dateRow[1]});\" style=\"display:none;position:absolute;margin-left:25px;margin-top:-35px\">";
					
					$this->output_actions($dateRow[1]);
					
					echo "</div> <a href=\"#\" onmouseover=\"showActions({$dateRow[1]});\" onmouseout=\"hideActions({$dateRow[1]});\">".$dateRow[0]."</a></div>";
					
					
				}
			}
			
			echo "</td>"; 
			$day_num++; 
			$day_count++;
			
			//Make sure we start a new row every week
			if ($day_count > 7)
			{
				echo "</tr><tr>";
				$day_count = 1;
			}
		}
		
		//Finaly we finish out the table with some blank details if needed
		while ( $day_count >1 && $day_count <=7 ) 
		{ 
			echo "<td> </td>"; 
			$day_count++; 
		} 
		
		echo "</tr><tr><td colspan=\"7\" style=\"text-align:right\" class=\"controlsCell\"> \n";
		if($this->_allowDelete) echo "<input type=\"button\" class=\"subButton\" value=\"Delete Selected\" style=\"float:right;\" onclick=\"javascript:verifyDelete();\"/> ";
		echo "</td></tr> \n";
		
		echo "</table></form> \n";
		
	}
	
	//output_legend - outputs the action controls associated with a particular record
	function output_legend() 
	{
		if($this->_showLegend)
		{
			//build output
			$output = "<div class=\"legend\"><span class=\"legendLabel\">Icon Legend:</span>  \n";
			
			//loop through actions
			foreach($this->_actions as $action)
			{
				$output .="<img src=\"".$this->_iconPath.$action['icon'].".png\" border=0 title=\"".$action['label']."\" class=\"legendIcon\"><span class=\"legendLabel\">".$action['label']."</span>";
				//$output .="<i class=\"fa fa-trash\"></i><span class=\"legendLabel\">".$action['label']."</span>";
			}
			
			//close output
			$output .= "</div> <div style=\"clear:both\"></div> \n";
			
			echo $output;
		}
	}
	
	//output_actions - outputs the action controls associated with a particular record
	function output_actions($recID) 
	{
		//start building output
		$output = "";
		
		//loop through actions
		$inc = 0;
		foreach($this->_actions as $action)
		{
			//replace {recID) in the url with the actual record ID
			$action['url'] = str_replace("{recID}",$recID,$action['url']);
				
			//build icon link
			$output .= "<a href=\"".$action['url']."\" onmouseover=\"show_iconHint('".$recID.$inc."');\" onmouseout=\"hide_iconHint('".$recID.$inc."');\"><img src=\"".$this->_iconPath.$action['icon'].".png\" border=0 title=\"".$action['label']."\"></a> ";
			$output .= "<div id=\"".$recID.$inc."\" class=\"iconHint\"><b>{$action['label']}</b></div>";
			
			$inc++;
		}
		
		echo $output;
	}
	
	//output_filters - outputs the filter dropdown box
	function output_filters()
	{
		if(isset($this->_filters)){
			
			$filters = $this->_filters;
			$filtersCount = count($filters);
			
			echo "Filter By ";
			
			//begin creation of dropdown filter box
			$output = "<select name=\"filterDropdown\" class=\"input\" onchange=\"filter(this.value)\"> \n";
			
			$output .= "<option value=\"0\">All Records</option> \n";
			
			//loop through filters and output them as dropdown options
			for($i=0; $i<$filtersCount; $i++)
			{
				//identify if this option is the current filter
				if($this->filter == $i+1)
				{
					$selected = "selected";
				}
				else
				{
					$selected = "";
				}
				$filterID = $i + 1;
				$output .= "<option value=\"{$filterID }\" {$selected}>{$filters[$i]['label']}</option> \n";
			}
			
			$output .= "</select> \n";
			
			echo $output;
		}
		
	}
	
	//set_sort - sets the sort variables based on the query string
	function set_sort()
	{
		if(isset($_GET['sort'])){
			$this->sortOrder = $_GET['sort'];
		}else{
			$this->sortOrder = $this->_defaultSortColumn;
		}
		//set the sort direction
		if(isset($_GET['sortDir'])){
			$this->sortDir = $_GET['sortDir'];
		}else{
			$this->sortDir = $this->_defaultSortDirection;
		}	
	}
	
	//set_filter - sets the filter variables based on the query string
	function set_filter()
	{
		if(isset($_GET['filter'])){
			$this->filter = $_GET['filter'];
		}else{
			$this->filter = $this->_defaultFilter;
		}
	}
	
	//set_action - sets the action variables based on the query string
	function set_action()
	{
		if(isset($_GET['action'])){
			$this->action = $_GET['action'];
		}else{
			$this->action = $this->_defaultAction;
		}
	}
	
	//output_javascript - outputs any necessary javascript for the data view pages such as 'list' and 'calendar'
	function output_javascript()
	{
		//output browse script
		echo "<script language='JavaScript'>
			function verifyDelete(ID){
			
				if(ID == undefined)
				{
					ID='';
					for (var i=0; i < document.selectForm.deleteCB.length; i++)
					{
					   if (document.selectForm.deleteCB[i].checked)
						{
						  ID = ID + document.selectForm.deleteCB[i].value + ',';
						}
					}
				}
				
				if (confirm('Are you sure you want to delete?')) {
						location.href='$this->_baseURL?$this->_URLvars&sort={$this->sortOrder}&sortDir={$this->sortDir}&filter={$this->filter}&action=delete&recID=' + ID;
				}
			}
			
			function showActions(ID)
			{
				document.getElementById('actions'+ID).style.display = '';
			}
			
			function hideActions(ID)
			{
				document.getElementById('actions'+ID).style.display = 'none';
			}
			
			function filter(ID)
			{
				
				location.href='$this->_baseURL?$this->_URLvars&sort={$this->sortOrder}&sortDir={$this->sortDir}&action=list&filter=' + ID;
		
			}
			
			function toggleSelectAll()
			{
				
				if(document.getElementById('selectAll').checked == true)
				{
					 document.selectForm.deleteCB.checked = true;
					for (var i=0; i < document.selectForm.deleteCB.length; i++)
					{
					   document.selectForm.deleteCB[i].checked = true;
					}
					
				}
				else
				{
					document.selectForm.deleteCB.checked = false;
					for (var i=0; i < document.selectForm.deleteCB.length; i++)
					{
					   document.selectForm.deleteCB[i].checked = false;
					}
				}
			}
			
			function show_iconHint(div)
			{
				div = document.getElementById(div);
				div.style.display = 'inline';
			}
			
			function hide_iconHint(div)
			{
				div = document.getElementById(div);
				div.style.display = 'none';
			}
			
			</script> \n";	
	}
	
	//list_recs - outputs a list of specified records from a database table
	function output_list() 
	{
		
		//set function vars
		$recs = $this->_recs;
		$recsCount = count($recs);
		$uniqueID = $this->_recsUniqueID;
		$colSpan = $recsCount + 2;
		
		//set results page
		if(isset($_GET['r']))
		{
			$resultsPage = $_GET['r'];
		}
		else
		{
			$resultsPage = 1;
		}
		
		//set search criteria if available
		if(isset($_POST['criteria'])){
			$criteria = $_POST['criteria'];
			$searchField = $_POST['searchField'];
		}else{
			if(isset($_GET['criteria'])){
				$criteria = $_GET['criteria'];
				$searchField = $_GET['searchField'];
			}else{
				$criteria = NULL;
				$searchField = NULL;
			}
		}

		
		//set the query variable to either a search, filter, or full query
		if($criteria != NULL)
		{
			$query = $this->_searchQuery;
			$query = str_replace("{criteria}",$criteria,$query);
			$query = str_replace("{searchField}",$searchField,$query);
		}
		else if($this->filter != 0)
		{
			$query = $this->_filters[$this->filter-1]['query'];;
		}
		else
		{
			$query = $this->_recsQuery;
		}
		
		//output the necessary javascript for this page
		$this->output_javascript();
		
		//paging results code
		$limit = $this->_recsLimit;
		$start = $this->paging_get_start($limit, $resultsPage);
		$count = mysql_num_rows($this->query($query, $this->_conn));
		$pages = $this->paging_get_pages($count, $limit);
		
		//perform record query
		$result = $this->query($query." ORDER BY ".$this->_recs[$this->sortOrder]['id']." {$this->sortDir} LIMIT ".$start.", ".$limit);
		
		//start the table
		
		echo "<table cellpadding=\"0\" cellspacing=\"0\"> \n";
		echo "<tr> \n";
		echo " \n";
		if($this->_allowDelete) echo "<td width=\"20\" class=\"headerCell\"><input type=\"checkbox\" onmouseover=\"show_iconHint('selectAllHint');\" onmouseout=\"hide_iconHint('selectAllHint');\" name=\"selectAll\" id=\"selectAll\" onclick=\"toggleSelectAll();\" /> <div id=\"selectAllHint\" class=\"iconHint\"><b>Select All</b></div></td> \n";

		//output record header cells
		for($i=0; $i<$recsCount; $i++){
			echo "<td onclick=\"document.location='{$this->_baseURL}?r=".$resultsPage."&sort=$i&sortDir={$this->sortDir}&filter={$this->filter}&criteria=$criteria&searchField=$searchField&$this->_URLvars'\" class=\"headerCell\">";
			
			
			if($this->sortOrder == $i){
				echo "<strong>{$recs[$i]['label']}</strong>\n";
				$ascLink = "<a href=\"{$this->_baseURL}?r={$resultsPage}&sort={$i}&sortDir=ASC&criteria={$criteria}&searchField={$searchField}&{$this->_URLvars}\"><img src=\"{$this->_ascIcon}\" border=0 title=\"Ascending\"></a>";
				$descLink = "<a href=\"{$this->_baseURL}?r={$resultsPage}&sort={$i}&sortDir=DESC&criteria={$criteria}&searchField={$searchField}&{$this->_URLvars}\"><img src=\"{$this->_descIcon}\" border=0 title=\"Descending\"></a>";
				echo "<div class=\"sortArrows\">$ascLink$descLink</div> \n";
			}
			else
			{
				echo "{$recs[$i]['label']} \n";
			}
			echo "</td> \n";
		
		
		}
		
		//output Actions header cell
		if(count($this->_actions)) echo "<td class=\"headerCell\" align=\"center\">Actions</td> \n";
			
		//close the table row
		echo "</tr> \n";
		
		//output the sub header row which holds the next/previous results paging and the search form
		echo "<tr><td colspan=\"$colSpan\" class=\"controlsCell\"> \n
				<div style=\"float:left;width:50%;margin-top:5px\"> \n";
		
		$this->output_filters();
		
		if(isset($_POST['criteria'])) {
			echo 'Search Results for "'.$_POST['criteria'].'"';
		}
		
		echo "</div><div style=\"float:right;width:50%;text-align:right\"> \n";

		$this->output_search_form();
		
		echo "</div></td></tr> \n";
		
		//if no records are found...
		if($count == 0){
			echo "<tr> \n";
			echo "<td colspan=\"{$colSpan}\" class=\"dataCell\"> No records were found.</td> \n";
			echo "</tr> \n";
		}
		echo "<form name=\"selectForm\">";
		//loop through the records
		while($row =  mysql_fetch_array($result)){
			
			
			
			$ID = $row[$uniqueID];
			
			

			if(isset($this->_recRowLink)){
				
				$rowLink = $this->_recRowLink;
				
				if(isset($this->_fields))
				{
					$formFieldValues = array();
					for($i=0; $i<count($this->_fields); $i++){
						$fieldArray = array('name' => $this->_fields[$i]['id'], 'value' => $row[$this->_fields[$i]['id']]);
						$formFieldValues[] = $fieldArray;
					}
					
					for($i=0;$i<count($formFieldValues);$i++)
					{
						$rowLink = str_replace("{".$formFieldValues[$i]['name']."}",$formFieldValues[$i]['value'],$rowLink);
					}
				}
				
				$rowLink = str_replace("{recID}",$ID,$rowLink);
				
				$cellEvents = "onmouseover=\"this.style.cursor='pointer';\" onclick=\"document.location='".$rowLink."';\"";
			}else{
				$cellEvents = "";
			}
				
			//output the record row with highlight functionality
			echo "<tr class=\"dataRow\">";
			if($this->_allowDelete) echo "<td class=\"dataCell\" valign=\"top\" $cellEvents><input type=\"checkbox\" name=\"deleteCB\" value=\"{$ID}\"/></td>";
			//loop through the data columns
			for($i=0; $i<$recsCount; $i++){
				
				$var = $row[$recs[$i]['id']];
				
				echo "<td class=\"dataCell\" valign=\"top\" $cellEvents>\n";
				eval("\$value = \$var;");

				echo $this->display_data($recs[$i], $value, $ID);

				echo "&nbsp;</td>\n";
			
			}
			
			//if there are actions display the action column
			if(count($this->_actions))
			{
				echo "<td class=\"dataCell\" align=\"center\" nowrap>";
				$this->output_actions($ID);
				echo "</td> \n";
			}
			echo "</tr>";
			
		}
		
		//output the paging

		echo "<tr><td colspan=\"$colSpan\" class=\"controlsCell\"> \n";
		echo "<div style=\"float:left;width:50%\"><div class=\"paging\">\n";
		if($pages > 1){
			$pagelist = $this->paging_output_page_list($resultsPage, $pages, $this->sortOrder, $this->sortDir, $criteria, $searchField, $this->filter, $this->_URLvars);
			echo $pagelist;	
		}
		echo "</div></div><div style=\"float:right;width:50%;text-align:right;\"> \n";
		if($this->_allowDelete) echo "<input type=\"button\" class=\"subButton\" value=\"Delete Selected\" style=\"float:right;\" onclick=\"javascript:verifyDelete();\"/> ";

		echo "</div></td></tr> \n";

		echo "</form>";
		
		//close main table
		echo "</table>";
	}
	
	//output child data
	function output_child_data()
	{
		//start building output
		$output = "";
		$output .= "<div class=\"subFrame\">\n";
		
		//set parent id
		$parentID = $this->_recID;
	
		//set properties
		$childTable = $this->_childTable;
		$parentField = $this->_childParentID;
		$childFields = $this->_childFields;
		
		//set permissions
		$allowDelete = $this->_childAllowDelete;
		$allowCreate = $this->_childAllowCreate;
		
		//get existing data
		$queryResult = $this->query("SELECT * FROM $childTable WHERE $parentField = $parentID");
		
		//build records table
		$output .= "<table cellpadding=\"0\" cellspacing=\"0\"><tr>\n";
		
		//show header columns
		foreach($childFields as $field)
		{
			$output .= "<td class=\"subHeaderCell\">".$field['label']."</td>\n";
		}
		if($allowDelete && $this->action != "view") $output .= "<td class=\"subHeaderCell\" align=\"center\">Actions</td>\n";
		$output .= "</tr>\n";
		
		//loop through data
		while($row =  mysql_fetch_array($queryResult))
		{
			$output .= "<tr>\n";
			foreach($childFields as $field)
			{
				$output .= "<td class=\"subDataCell\">".$row[$field['id']]."</td>\n";
			}
			if($allowDelete && $this->action != "view") $output .= "<td class=\"subDataCell\" align=\"center\"><a href=\"#\" id=\"deleteChild\"><img src=\"".$this->_deleteIcon."\" border=\"0\" title=\"Delete\" /></a></td>\n";
			$output .= "</tr>\n";
		}
		$output .= "</table>";
		$output .= "</div>";
		
		//display create form
		if($allowCreate && $this->action != "view")
		{
			$output .= "<div class=\"subFrame\" style=\"margin-top:5px\">\n";
			$output .= "<div class=\"subHeaderCell\"><a href=\"#\" id=\"createChildToggle\" class=\"expandIcon\"></a> Create Child</div>\n";
			$output .= "<div id=\"createChild\" style=\"display:none\">";
			$output .= "<table>\n";			
			//loop through child fields
			foreach($childFields as $field)
			{
				$inputField = $this->build_input_field($field);
				$output .= "<tr><td class=\"labelCell\" width=\"125\">".$field['label']."</td><td class=\"fieldCell\">$inputField</td></tr>\n";
			}
			$output .= "<tr><td class=\"controlsCell\" colspan=\"2\" align=\"right\"><input type=\"button\" id=\"addChild\" class=\"subButton\" value=\"Add\" /></td></tr>\n";
			$output .= "</table></div></div>\n";	
		}
		
		echo $output;
	}
	
	//build form script
	function build_form_script($formType = "create")
	{
		//start building output
		$output = "<script type=\"text/javascript\">\n";
		
		//conditional fields - show/hide fields based on corresponding values
		$output .= "function conditions() {\n";
		foreach($this->_fields as $field)
		{
			if(isset($field['condition_field']))
			{
				$condField = "form_".$field['condition_field'];
				$condValue = $field['condition_value'];
				$fieldID = $field['id'];
				$output .= "if( $('#$condField:checked').val() == '$condValue'){\n";
				$output .= "	$('#row_$fieldID').fadeIn();\n";
				$output .= "}else{\n";
				$output .= "	$('#row_$fieldID').hide();\n";
				$output .= "}\n";
			}
		}
		$output .= "};\n";
		
		//on document ready handler
		$output .= "\$(document).ready(function() {\n";
		
		//run conditions
		$output .= "conditions();\n";
		
		//input value change event
		$output .= "\$('#dbman input, #dbman select').change(function() {\n";
		$output .= "conditions();\n";
		$output .= "});\n";
		
		//close document ready handler
		$output .= "});\n";
		
		//close script tag
		$output .= "</script>\n";
		
		//return output
		return $output;
	}
	
	//output form - outputs the specified form
	function output_form($formType) 
	{
		//set function vars
		$fields = $this->_fields;
		$table = $this->_dbTable;
		$uniqueID = $this->_recsUniqueID;
		$fieldsCount = count($fields);
	
		
	
		//set vars for particular form
		if($formType == 'edit'){
			
			//get record for editing
			$editQuery = "Select * from $table WHERE $uniqueID = ".$this->_recID;
			$editResult = $this->query($editQuery);
			$editRow = mysql_fetch_array($editResult);
			
			//set form action to update
			$formaction = 'update&recID=' .$editRow[$uniqueID];
			
		}else{
		
			//set form action to insert
			$formaction = 'insert';
			
		}
		
		//output required fields javascript check
		
		$checkFieldsScript = "";
		for($i=0;$i<$fieldsCount;$i++){
			if(isset($fields[$i]['required'])){
				if($fields[$i]['required'] == true && $fields[$i]['type'] != "date"){
					$checkFieldsScript .= "if(document.dbmanForm.form_".$fields[$i]['id'].".value == ''){ \n".
											"alert('".$fields[$i]['label']." is required'); \n".
											"document.dbmanForm.form_".$fields[$i]['id'].".focus(); \n".
											"return false; \n".
											"}";
				}
				if($fields[$i]['type'] == "password"){
					$checkFieldsScript .= "if(document.dbmanForm.form_{$fields[$i]['id']}.value != document.dbmanForm.form_confirm{$fields[$i]['id']}.value){ \n".
											"alert('".$fields[$i]['label']." does not match'); \n".
											"document.dbmanForm.form_".$fields[$i]['id'].".focus(); \n".
											"return false; \n".
											"}";
				}
			}
		}
		
		$filterFieldScript = "";
		$link =  $this->_baseURL."?".$this->_URLvars."&action={$this->action}&refresh=1";
		if($formType == 'edit') $link .= "&recID={$editRow[$uniqueID]}";
		for($i=0;$i<$fieldsCount;$i++){
			if(isset($fields[$i]['filter'])){
					$filterFieldScript .= "if(selectField.name == 'form_{$fields[$i]['filter']}'){
												var filterValue = selectField.value;
												//document.location = '{$link}&filter{$fields[$i]['filter']}='+filterValue;
												document.dbmanForm.action = '{$link}&filter{$fields[$i]['filter']}='+filterValue;
												document.dbmanForm.submit();
											}; \n";
			}
		}

		$listSearchScript = "";
		for($i=0;$i<$fieldsCount;$i++){
			if(isset($fields[$i]['listType'])){
				if($fields[$i]['listType'] == "search"){
					$listSearchScript .= "
										if(field.name == 'form_{$fields[$i]['id']}preview'){
											
											var searchResults = listDataArray{$fields[$i]['id']}.filter(searchListArray, field);
											
											for(i=0;i<listDataArray{$fields[$i]['id']}.length;i++)
											{
												var element = document.getElementById('form_{$fields[$i]['id']}option'+listDataArray{$fields[$i]['id']}[i][0]);
												element.style.display = 'none';
											}
											
											for(i=0;i<searchResults.length;i++)
											{
												var element = document.getElementById('form_{$fields[$i]['id']}option'+searchResults[i][0]);
												element.style.display = '';
											}
										
										};
											\n";
				}
			}
		}

		$output = "<script language=\"JavaScript\" type=\"text/javascript\">
		
					var blockOptionsHide = false;
		
					function check_required_fields()
					{
						{$checkFieldsScript}
					} 
					
					function selectFieldChange(selectField)
					{
						{$filterFieldScript}
					} 
					
					function listSearchChange(field)
					{
						{$listSearchScript}
					}
					
					function searchListArray(element, index, array)
					{
						var found = false;
						var criteria = this.value;
						criteria = criteria.toLowerCase();
						if(element[1].toLowerCase().indexOf(criteria) != -1 && criteria != '' && criteria.length > 2) found = true;
						
						return (found);	
					}
					
					function showListSearch(id)
					{
						var element = document.getElementById(id);
						element.style.display = '';
					}
					
					function hideListSearch(id)
					{
						if(blockOptionsHide == false)
						{
							var element = document.getElementById(id);
							element.style.display = '';
						}
					}
					
					function focusListSearchOptions()
					{
						blockOptionsHide = true;
					}
					
					function blurListSearchOptions()
					{
						blockOptionsHide = false;
					}
					
					function setListSearchValue(name, value, label)
					{
						var previewField = eval('document.dbmanForm.form_'+name+'preview');
						previewField.value = label;
						
						var valueField = eval('document.dbmanForm.form_'+name);
						valueField.value = value;
						
						var optionsDiv = document.getElementById('form_'+name+'options');
						optionsDiv.style.display = 'none';
					}
					
					</script> \n";
		echo $output;
		
		
		//include any script required for form
		echo $this->build_form_script();
	
		//Show the forms
		//output start of table
		echo "<table cellpadding=\"0\" cellspacing=\"0\"> \n";
		
		//set form id
		$formID = "form_".$formType;
		
		//output form tag
		echo "<form name=\"dbmanForm\" id=\"$formID\" action=\"$this->_baseURL?$this->_URLvars&action=$formaction\" method=\"post\" enctype=\"multipart/form-data\" onSubmit=\"return check_required_fields();\"> \n";
		
		//loop through fields
		for($i=0; $i<$fieldsCount; $i++){
		
			//if the form is edit then set the field value
			if($formType == 'edit' && $fields[$i]['type'] != 'header' && $fields[$i]['type'] != 'file_content'){
				
				$inputValue = $editRow[$fields[$i]['id']];
				
			}else if($formType == 'edit' && $fields[$i]['type'] == 'file_content'){
			
				$filename = $fields[$i]['path'].$editRow[$fields[$i]['file_name_field']].'.'.$fields[$i]['file_extension'];
				$inputValue = fread($fp = fopen($filename, 'r'), filesize($filename));
			
			}else{
				if(isset($fields[$i]['value'])){
					$inputValue = $fields[$i]['value'];
				}else{
					$inputValue = "";
				}
			}
			
			//if this is a page refresh use the variables from the previous page
			if(isset($_GET['refresh']) && isset($fields[$i]['id']) && isset($_POST['form_'.$fields[$i]['id']])) 
			{
				$inputValue = $_POST['form_'.$fields[$i]['id']];
			}
			
			$this->_fields[$i]['value'] = $inputValue;
			
			//show the field label IF the field is not a type of hidden
			if($fields[$i]['type'] != 'hidden'){
		
				
				
				
				if($fields[$i]['type'] == 'header'){
				
					//show the header
					echo "<tr><td class=\"headerCell\" colspan=\"2\"> \n";
					echo $fields[$i]['label'];
				
				}else if($fields[$i]['type'] == 'html'){
				
					echo "<tr id=\"row_{$fields[$i]['id']}\"><td width=\"125\" class=\"labelCell\" valign=\"top\"> \n";
					echo $fields[$i]['label'];
					//echo "</td><td></td></tr><tr><td colspan=\"2\"> \n";
					echo "</td><td> \n";
					
				}else{
					
					$required = false;
					
					if(isset($fields[$i]['required']))
					{
						if($fields[$i]['required'] == 'true' || $fields[$i]['required'] == true)
						{
							$required = true;	
						}
					}
					
					//show the regular label
					echo "<tr id=\"row_{$fields[$i]['id']}\"><td width=\"125\" class=\"labelCell\" valign=\"top\"> \n";
					if($required) echo "<span style=\"color:red\" onmouseover=\"show_iconHint('{$i}required');\" onmouseout=\"hide_iconHint('{$i}required');\">* </span> \n";
					echo "<div id=\"{$i}required\" class=\"iconHint\"><span style=\"color:red\">* Required Field</span></div>";
					echo $fields[$i]['label'];
					echo "</td><td class=\"fieldCell\"> \n";
				}
				
				//output input field
				echo $this->build_input_field($fields[$i], $inputValue);
								
				//start new row
				echo "</td></tr> \n";
		
		
			//if the field is hidden
			}else{
				
				//show hidden field
				echo "<input type=\"hidden\" name=\"form_{$fields[$i]['id']}\" value=\"{$inputValue}\"> \n";
			}
			
		}
		
		//output child data
		if(isset($this->_childFields))
		{
			echo "<tr><td class=\"headerCell\" colspan=\"2\">".$this->_childHeader."</td></tr>\n";
			echo "<tr><td class=\"fieldCell\" colspan=\"2\">\n";
			$this->output_child_data();
			echo "</td></tr>\n";
		}
		
		//output submit buttons
		if($formType == 'create'){
		
			echo "<tr><td align=\"right\" colspan=\"2\" class=\"controlsCell\"><input type='button' class='button' value='Cancel' name='cancel' /> <input type='submit' class='button' value='Submit' name='submitForm' /> </td></tr>";
		
		}else if ($formType == 'edit'){
			
			if(isset($this->_URLAfterFormSubmit)){
				$link = $this->_URLAfterFormSubmit;
			}else{
				$link =  $this->_baseURL."?".$this->_URLvars;
			}
			echo "<tr><td align=\"right\" colspan=\"2\" class=\"controlsCell\"><input type='submit' class='button' value='Update' name='submitForm'> <input type='button' class='button' onclick=\"javascript:document.location='$link';\" value='Cancel'></td></tr>";
		
		}
		
		//close table
		echo "</form></table>";
		

	}

	//output view - outputs the view
	function output_view() 
	{
		//set function vars
		$fields = $this->_fields;
		$table = $this->_dbTable;
		$uniqueID = $this->_recsUniqueID;
		$fieldsCount = count($fields);
		$fieldValue = "";

		//get record for viewing
		$viewQuery = "Select * from $table WHERE $uniqueID = ".$this->_recID;
		$viewResult = $this->query($viewQuery);
		$viewRow = mysql_fetch_array($viewResult);
		
		//output start of table
		echo "<table cellpadding=\"0\" cellspacing=\"0\"> \n";
	
		//loop through fields
		for($i=0; $i<$fieldsCount; $i++){
		
			//if the form is edit then set the field value
			if($fields[$i]['type'] != 'header' && $fields[$i]['type'] != 'file_content'){
				
				$fieldValue = $viewRow[$fields[$i]['id']];
				$rawValue = $fieldValue;
				$this->_fields[$i]['value'] = $fieldValue;
				
			}else if($fields[$i]['type'] == 'file_content'){
			
				$filename = $fields[$i]['path'].$viewRow[$fields[$i]['file_name_field']].'.'.$fields[$i]['file_extension'];
				$fieldValue = fread($fp = fopen($filename, 'r'), filesize($filename));
				$rawValue = $fieldValue;
			}
			
			
			
			//show the field label IF the field is not a type of hidden
			if($fields[$i]['type'] != 'hidden'){
				
				
				if($fields[$i]['type'] == 'header'){
				
					//show the header
					echo "<tr><td class=\"headerCell\" colspan=\"2\"> \n";
					echo $fields[$i]['label'];
				
				}else if($fields[$i]['type'] == 'html'){
				
					echo "<tr><td width=\"125\" class=\"labelCell\" valign=\"top\"> \n";
					echo $fields[$i]['label'];
					echo "</td><td> \n";
					
				}else{
									
					//show the regular label
					echo "<tr><td width=\"125\" class=\"labelCell\" valign=\"top\"> \n";
					echo $fields[$i]['label'];
					echo "</td><td class=\"fieldCell\"> \n";
				}
								
				echo $this->display_data($fields[$i], $fieldValue, $this->_recID);
				
				//start new row
				echo "</td></tr> \n";
		
		
			}
			
		}
		
		//output child data
		if(isset($this->_childFields))
		{
			echo "<tr><td class=\"headerCell\" colspan=\"2\">".$this->_childHeader."</td></tr>\n";
			echo "<tr><td class=\"fieldCell\" colspan=\"2\">\n";
			$this->output_child_data();
			echo "</td></tr>\n";
		}
		
		//back button
		echo "<tr><td align=\"right\" colspan=\"2\" class=\"controlsCell\"><input type='button' class='button' onclick=\"javascript:history.back();\" value='Back'></td></tr>";

		//close table
		echo "</table>";
	}

	//output search form
	function output_search_form()
	{
		if(isset($this->_searchFields))
		{
			//set function vars
			$searchFields = $this->_searchFields;
			$searchFieldsCount = count($searchFields);
			
			$output = "";
			
			//output form tag
			$output .= "<form name=\"search\" action=\"{$this->_baseURL}?{$this->_URLvars}&action=list\" method=\"post\"> \n";
		
			//build dropdown
			$criteria = (isset($_POST['criteria'])) ? $_POST['criteria'] : "";
		
			$output .= "<input type=\"text\" name=\"criteria\" class=\"input\" size=\"18\" value=\"{$criteria}\"> \n";
			$output .= " <select name=\"searchField\" class=\"input\"> \n";
			for($i=0;$i<$searchFieldsCount;$i++){
				$output .= "<option value=\"".$searchFields[$i]['id']."\">".$searchFields[$i]['label']."</option> \n";
			}
			$output .= "</select> \n";
			
			//build submit button
			$output .= " <input type=\"submit\" name=\"submit_search\" class=\"subButton\" value=\"Search\"></form> \n";
			
			//output code
			echo $output;
		}
	}

	//output header
	function output_header()
	{
   		if(isset($_GET['success']))  $this->output_success_message("The record has been successfully updated.");
		if(isset($_GET['error']))  $this->output_error_message("Doh! That didn't go too well. Maybe this will help...<br/>".$_GET['error']);

		$output = "";

		//tabs
		if($this->_showTabs)
		{
			$output .= "<div id=\"tabs\"> \n";
			if($this->action == 'list'){$active = "active";}else{$active="";};	
			if($this->_allowList)$output .= "<a href=\"$this->_baseURL?$this->_URLvars&action=list\" class=\"{$active}\">List</a> \n";
			if($this->action == 'calendar'){$active = "active";}else{$active="";};	
			if(isset($this->_calendarFields)) $output .= "<a href=\"$this->_baseURL?$this->_URLvars&action=calendar\" class=\"{$active}\">Calendar</a> \n";
			if($this->action == 'edit'){$active = "active";}else{$active="";};	
			if($this->_allowEdit)$output .= "<a href=\"$this->_baseURL?$this->_URLvars&action=edit\" class=\"{$active}\">Edit</a> \n";
			if($this->action == 'view'){$active = "active";}else{$active="";};	
			if($this->_allowView)$output .= "<a href=\"$this->_baseURL?$this->_URLvars&action=view\" class=\"{$active}\">View</a> \n";
			if($this->action == 'create'){$active = "active";}else{$active="";};	
			if($this->_allowCreate)$output .= "<a href=\"$this->_baseURL?$this->_URLvars&action=create\" class=\"{$active}\">Create</a> \n";
			if($this->action == 'import'){$active = "active";}else{$active="";};	
			if($this->_allowImport)$output .= "<a href=\"$this->_baseURL?$this->_URLvars&action=import\" class=\"{$active}\">Import</a> \n";
			$output .= " </div> \n";
		}
		
		echo $output;
	}
	
	//format date
	function format_date($raw_date, $format)
	{
	
		if ( ($raw_date == '0001-01-01 00:00:00') || empty($raw_date) ) return false;
	
		$year = substr($raw_date, 0, 4);
		$month = (int)substr($raw_date, 5, 2);
		$day = (int)substr($raw_date, 8, 2);
		$hour = (int)substr($raw_date, 11, 2);
		$minute = (int)substr($raw_date, 14, 2);
		$second = (int)substr($raw_date, 17, 2);
	
		if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
		  return date($format, mktime($hour, $minute, $second, $month, $day, $year));
		} else {
		  return str_replace('2037' . '$', $year, date($format, mktime($hour, $minute, $second, $month, $day, 2037)));
		}
		
	}
	
	//paging
	function paging_get_start($limit, $resultsPage)
	{
		if ($resultsPage == "1"){
			$start = 0;
		}else{
      		$start = ($resultsPage-1) * $limit;
      	}
     	return $start;
    }

	function paging_get_pages($count, $limit)
	{
		$pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
		return $pages;
	}

	function paging_output_page_list($curpage, $pages, $sort, $sortDir, $criteria, $searchField, $filter, $URLvars = '')
	{
		
		$page_list  = "Page ".$curpage." of ".$pages." &nbsp; &nbsp; &nbsp; ";
		
		if (($curpage-1) > 0){
			$page_list .= "<a href=\"".$this->_baseURL."?r=".($curpage-1)."&sort={$sort}&sortDir={$sortDir}&filter={$filter}&criteria=$criteria&searchField=$searchField&".$URLvars."\" title=\"Previous Page\" class=\"pagingButton\">&laquo; Previous</a> ";
		}

		if($pages < 20){
			for ($i=1; $i<=$pages; $i++){
				if ($i == $curpage){
					$page_list .= "<a href=\"".$this->_baseURL."?r=".$i."&sort={$sort}&sortDir={$sortDir}&filter={$filter}&criteria=$criteria&searchField=$searchField&".$URLvars."\" title=\"Page ".$i."\" class=\"pagingButton_active\">".$i."</a>";
				}else{
					$page_list .= "<a href=\"".$this->_baseURL."?r=".$i."&sort={$sort}&sortDir={$sortDir}&filter={$filter}&criteria=$criteria&searchField=$searchField&".$URLvars."\" title=\"Page ".$i."\" class=\"pagingButton\">".$i."</a>";
				}
				$page_list .= " ";
			}
		}
		
		if (($curpage+1) <= $pages){
			$page_list .= "<a href=\"".$this->_baseURL."?r=".($curpage+1)."&sort={$sort}&sortDir={$sortDir}&filter={$filter}&criteria=$criteria&searchField=$searchField&".$URLvars."\" title=\"Next Page\" class=\"pagingButton\">&raquo; Next</a> ";
		}
		if($pages <= 1){
			$page_list = "";
		}
		return $page_list;
    }
	
	//build_input_field - builds and returns an input field
	function build_input_field($field, $value = "")
	{
		//set output variable
		$output = "";
		
		//set rec id
		$recID = $this->_recID;
		
		//output a prefix if available
		if(isset($field['prefix'])) $output .= $field['prefix'];
		
		//set disable variable
		$disabled = "";
		if(isset($field['disabled']))
		{
			if($field['disabled']) $disabled = "disabled=\"disabled\"";
		}
		
		//set current input value if available
		$inputValue = $value;
		
		//set width and height parameters for fields
		$defaultWidth = "250px";
		$defaultTextAreaWidth = "85%";
		$defaultTextAreaHeight = "125px";
		
		//switch based on field type
		switch($field['type'])
		{
			//show field for header
			case "header":
			
			break;
			
			//show field for text
			case "text":
				
				$width = (isset($field['width'])) ? $field['width'] : $defaultWidth;
				$output .= "<input style=\"width:{$width}\" type=\"text\" name=\"form_" .$field['id'] ."\" id=\"form_" .$field['id'] ."\" class=\"input\" value=\"$inputValue\" $disabled /> \n";
			
			break;
			
			//show field for custom
			case "custom":
	
				$output .= $field['customInput'];
			
			break;
			
			//show field for textarea
			case "textarea":
				
				$width = (isset($field['width'])) ? $field['width'] : $defaultTextAreaWidth;
				$height = (isset($field['height'])) ? $field['height'] : $defaultTextAreaHeight;
				$output .= "<textarea style=\"width:{$width};height:{$height}\" id=\"form_" .$field['id'] ."\" name=\"form_" .$field['id'] ."\" class=\"input\" $disabled>{$inputValue}</textarea> \n";
	
			break;
			
			//show field for file_content
			case "file_content":
				
				$width = (isset($field['width'])) ? $field['width'] : $defaultTextAreaWidth;
				$height = (isset($field['height'])) ? $field['height'] : $defaultTextAreaHeight;
				$output .= "<textarea style=\"width:{$width};height:{$height}\" id=\"form_" .$field['id'] ."\" name=\"form_" .$field['id'] ."\" class=\"input\" $disabled>{$inputValue}</textarea> \n";
	
			break;
			
			//show field for password
			case "password":
				
				$width = (isset($field['width'])) ? $field['width'] : $defaultWidth;
				$output .= "<input type=\"password\" style=\"width:{$width}\" id=\"form_" .$field['id'] ."\" name=\"form_" .$field['id'] ."\" class=\"input\" value=\"$inputValue\" $disabled> \n";
				$output .= "<br/><span style=\"font-size:10px;\">Confirm {$field['label']}:</span><br/><input type=\"password\" style=\"width:{$width}\" name=\"form_confirm" .$field['id'] ."\" class=\"input\" value=\"$inputValue\"> \n";
			
			break;
			
			//show field for yesno
			case "boolean":
				
				$boolFormat = (isset($field['format'])) ? $field['format'] : "True,False";
				$boolFormat = explode(',', $boolFormat);
				$trueValue = 1;
				$falseValue = 0;
				$trueLabel = $boolFormat[0];
				$falseLabel = $boolFormat[1];
				
				if($inputValue == 1)
				{
					$yesChecked = 'checked="checked"';
					$noChecked = '';
				}
				else
				{
					$noChecked = 'checked="checked"';
					$yesChecked = '';
				}
				$output .= "$trueLabel <input id=\"form_" .$field['id'] ."\" type=\"radio\" name=\"form_{$field['id']}\" id=\"form_{$field['id']}\" value=\"$trueValue\" $yesChecked $disabled/> $falseLabel <input type=\"radio\" id=\"form_" .$field['id'] ."\" name=\"form_" .$field['id'] ."\" value=\"$falseValue\" $noChecked $disabled/>";

			
			break;
			
			//show field for html editor
			case "html":

				$output .= "<script src=\"//cdn.ckeditor.com/4.4.7/standard/ckeditor.js\"></script> \n";
				$output .= "<div style=\"width:97%\"><textarea id=\"form_" .$field['id'] ."\" name=\"form_" .$field['id'] ."\" class=\"input\" $disabled>{$inputValue}</textarea></div> \n";
				$output .= "<script> CKEDITOR.replace( 'form_".$field['id']."' );</script> \n";




			break;
			
			//show field for file
			case "file":
				
				$width = (isset($field['width'])) ? $field['width'] : $defaultWidth;
				
				if($inputValue == "") //create form
				{
					$output .= "<input type=\"file\" id=\"form_" .$field['id'] ."\" style=\"width:{$width}\" name=\"form_{$field['id']}\" class=\"input\" $disabled> ";
					$output .= "<input type=\"hidden\" name=\"form_{$field['id']}HIDDEN\" value=\"\">";
				
				}
				else //edit form
				{
					$filePath = str_replace("{recID}",$recID,$field['file_path']);
					$output .= "<input type=\"file\" style=\"width:{$width}\" id=\"form_" .$field['id'] ."\" name=\"form_{$field['id']}\" class=\"input\" $disabled> <a href=\"{$filePath}{$inputValue}\" target=\"_blank\">$inputValue</a><input type=\"hidden\" name=\"form_{$field['id']}HIDDEN\" value=\"{$inputValue}\">";
					$output .= "&nbsp; &nbsp;<input type=\"checkbox\" name=\"form_".$field['id']."REMOVE\" $disabled />Remove";
				}
			
			break;
			
			//show field for date
			case "date":
				
				$inputValueYear = substr($inputValue, 0, 4);
				$inputValueMonth = substr($inputValue, 5, 2);
				$inputValueDay = substr($inputValue, 8, 2);
				$inputValueHour = substr($inputValue, 11, 2);
				$inputValueMinute = substr($inputValue, 14, 2);
				$inputValueSecond = substr($inputValue, 17, 2);
				
				//output month field
				$output .= "<select name=\"form_month{$field['id']}\" class=\"input\" $disabled> \n";
				for($month=1;$month<=12;$month++){
					if($inputValueMonth == $month){
						$selected = "selected";
					}else{
						$selected = "";
					}
					$textMonth = date("F", mktime(0, 0, 0, $month, 1, 2000));
					$output .= "<option value=\"{$month}\" {$selected}>{$textMonth}</option> \n";
				}
				$output .= "</select> \n";
				
				//output day field
				$output .= "<select name=\"form_day{$field['id']}\" class=\"input\" $disabled> \n";
				for($day=1;$day<=31;$day++){
					if($inputValueDay == $day){
						$selected = "selected";
					}else{
						$selected = "";
					}
					$output .= "<option value=\"{$day}\" {$selected}>{$day}</option> \n";
				}
				$output .= "</select> \n";
				
				//output year field
				$output .= "<select name=\"form_year{$field['id']}\" class=\"input\" $disabled> \n";
				for($year=2000;$year<=2025;$year++){
					if($inputValueYear == $year){
						$selected = "selected";
					}else{
						$selected = "";
					}
					$output .= "<option value=\"{$year}\" {$selected}>{$year}</option> \n";
				}
				$output .= "</select> \n";
				
				
				if($inputValueHour != "" && $inputValueHour != NULL)
				{
					//output hour field
					$output .= " - <select name=\"form_hour{$field['id']}\" class=\"input\" $disabled> \n";
					for($hour=1;$hour<=12;$hour++){
						if($inputValueHour == $hour){
							$selected = "selected";
						}else{
							$selected = "";
						}
						$output .= "<option value=\"{$hour}\" {$selected}>{$hour}</option> \n";
					}
					$output .= "</select>";
					
					//output minute field
					$output .= ":<select name=\"form_minute{$field['id']}\" class=\"input\" $disabled> \n";
					for($minute=0;$minute<=60;$minute++){
						if($inputValueMinute == $minute){
							$selected = "selected";
						}else{
							$selected = "";
						}
						$output .= "<option value=\"{$minute}\" {$selected}>{$minute}</option> \n";
					}
					$output .= "</select>";
					
					//output second field
					$output .= ":<select name=\"form_second{$field['id']}\" class=\"input\" $disabled> \n";
					for($second=0;$second<=60;$second++){
						if($inputValueSecond == $second){
							$selected = "selected";
						}else{
							$selected = "";
						}
						$output .= "<option value=\"{$second}\" {$selected}>{$second}</option> \n";
					}
					$output .= "</select> \n";
				}
				else
				{
					$output .= "<input type=\"hidden\" name=\"form_hour{$field['id']}\" value=\"0\" />";
					$output .= "<input type=\"hidden\" name=\"form_minute{$field['id']}\" value=\"0\" />";
					$output .= "<input type=\"hidden\" name=\"form_second{$field['id']}\" value=\"0\" />";
				}
			
			break;
				
			//show field for dynamic list
			case "dynamic":
	
				$listQuery = $field['query'];
				
				//create a javascript array containing all dynamic list data
				$output .= "<script type=\"text/javascript\"> var listDataArray{$field['id']} = new Array(); </script> \n";
				
				//if this filed is supposed to be filtered using the value of another field do so here
				if(isset($field['filter']))
				{
					
					if(isset($_GET['filter'.$field['filter']]))
					{
					   $filterValue = $_GET['filter'.$field['filter']];
					}
					else
					{
						if($this->_fields[$i-1]['value'] != "" && $this->_fields[$i-1]['value'] != NULL)
						{
					  	 	$filterValue = $this->_fields[$i-1]['value'];
						}
						else
						{
							$filterValue = 0;
						}
					}
					
					$listQuery = str_replace("{filter}",$filterValue, $listQuery);
				}
				
				//run the list query
				$listResult = $this->query($listQuery);
				if($field['dynamic_type'] == 'dropdown'){
					$output .= "<select onchange=\"selectFieldChange(this)\" name='form_" .$field['id'] ."' class=\"input\" style=\"width:250px\" $disabled> \n";
					if(!isset($field['filter'])) $output .= "<option value=\"\"></option> \n";	
				}else if($field['dynamic_type'] == 'list'){
					$output .= "<select onchange=\"selectFieldChange(this)\" name='form_" .$field['id'] ."' multiple=\"multiple\" class=\"input\" style=\"width:250px\" $disabled> \n";
				}else if($field['dynamic_type'] == 'search'){
					$listSearchResult = $this->query($listQuery);
					$listSearchValue = "";
					while($listSearchRow =  mysql_fetch_array($listSearchResult)){
						if($listSearchRow[$field['dynamic_value']] == $inputValue) {
							$listSearchValue = $listSearchRow[$field['dynamic_label']];
							break;
						}
					}
					$output .= "<input type=\"text\" value=\"$listSearchValue\" onfocus=\"showListSearch('form_{$field['id']}options')\" onblur=\"hideListSearch('form_{$field['id']}options')\" onkeyup=\"listSearchChange(this)\" name='form_" .$field['id'] ."preview' class=\"input\" style=\"width:{$width}\" $disabled /> \n";
					$output .= "<input type=\"hidden\" onchange=\"selectFieldChange(this)\" name='form_" .$field['id'] ."' value=\"{$inputValue}\"/> \n";
					$output .= "<div style=\"display:none;position:absolute;z-index:1000\" onmouseover=\"focusListSearchOptions()\" onmouseout=\"blurListSearchOptions()\" id=\"form_" .$field['id'] ."options\" > \n";
	
				}
				
				
				$inc = 0;
				while($listRow =  mysql_fetch_array($listResult)){
					$listLabel = $listRow[$field['dynamic_label']];
					$listValue = $listRow[$field['dynamic_value']];
					
					//append value to javascript array
					$output .= "<script type=\"text/javascript\"> listDataArray{$field['id']}[{$inc}] = new Array('{$listValue}', '{$listLabel}'); </script> \n";
					
					if($field['dynamic_type'] == 'dropdown'){
						if($inputValue == $listValue){
							$isselected = "selected";
						}else{
							$isselected = '';
						}
						$output .= "<option value=\"$listValue\" $isselected>$listLabel</option> \n";
					}
					else if($field['dynamic_type'] == 'checkbox')
					{
						$inputValueArray = explode(",",$inputValue);
						if(in_array($listValue, $inputValueArray)){
							$ischecked = "checked";
						}else{
							$ischecked = "";
						}
						$output .= "<input name=\"form_".$field['id']."[]\" type=\"checkbox\" value=\"$listValue\" $ischecked $disabled> $listLabel <br/>\n";
					}
					else if($field['dynamic_type'] == 'search')
					{
						$output .= "<div id=\"form_{$field['id']}option{$listValue}\" class=\"input\" style=\"width:{$width};display:none\" onclick=\"setListSearchValue('{$field['id']}', '$listValue', '$listLabel')\"> $listLabel </div>\n";
					}
					else if($field['dynamic_type'] == 'colors')
					{
						$inputValueArray = explode(",",$inputValue);
						if(in_array($listValue, $inputValueArray)){
							$ischecked = "checked";
						}else{
							$ischecked = "";
						}
						$hexValue = $listRow[$field['hexValue']];
						$output .= "<div style=\"float:left;margin:5px;\"><div style=\"background:$hexValue;width:100px;height:30px;\"></div><input name=\"form_".$field['id']."[]\" type=\"radio\" value=\"$listValue\" $ischecked $disabled> <span style=\"font-size:10px\">$listLabel</span> </div>\n";
					}
					
					$inc ++;
				}
				
				if($field['dynamic_type'] == 'dropdown'){
					$output .= "</select> \n";
				}
				else if($field['dynamic_type'] == 'search'){
					$output .= "</div> \n";
				}
				else if($field['dynamic_type'] == 'colors'){
					$output .= "<div style=\"clear:both\"></div><br/> \n";
				}
				
			break;
		} //end switch statement
		
			
		//if field is disabled output a hidden field with the value
		if(isset($field['disabled']))
		{
			if($field['disabled'] == true)
			{
				$output .= "<input type=\"hidden\" name=\"form_{$field['id']}\" value=\"{$inputValue}\"> \n";
			}
		}

		//output a suffix if available
		if(isset($field['suffix'])) $output .= $field['suffix'];

		//output caption if available
		if(isset($field['caption'])) $output .= "<div class=\"fieldCaption\">".$field['caption']."</div> \n";
		
		//return final output
		return $output;
	}
	
	//display data - builds and formats data for visual display
	function display_data($field, $data, $recID = 0)
	{
		//set raw value
		$raw = $data;
		
		//set the manipulated value
		$value = $data;
		
		//set the type of field (text, date, password, etc)
		$type = (isset($field['type'])) ? $field['type'] : "text";
		
		//set the limit the amount of characters displayed
		$limit = (isset($field['type'])) ? $field['type'] : 0;
		
		//format the data if necessary
		switch($type)
		{
			case "header":
				$value = "";
				break;
							//show field for password
			case "password":
				
				$value =  "**************";
				break;
				
			case "file":
				$filePath = str_replace("{recID}",$recID,$field['file_path']);
				$value = "<a href=\"{$filePath}{$value}\" target=\"_blank\">$value</a>";
				break;
		
			case "date" :
				
				$dateFormat = (isset($field['format'])) ? $field['format'] : "m/d/Y";
				$value = $this->format_date($raw, $dateFormat);
				break;
			
			case "image" :
			
				$filePath = str_replace("{recID}",$recID,$field['file_path']);
				$width = (isset($field['width'])) ? $field['width'] : "90";
				$value = "<img src=\"{$filePath}{$value}\" width=\"{$width}\"/>";
				
				break;
				
			case "boolean" :
			
				$boolFormat = (isset($field['format'])) ? $field['format'] : "True,False";
				$boolFormat = explode(',', $boolFormat);
				if($raw == 0 || $raw == "false")
				{
					$value = $boolFormat[1];
				}
				elseif($raw == 1 || $raw == "true")
				{
					$value = $boolFormat[0];
				}
				break;
		}
		
		//perform query if necessary
		if(isset($field['query']))
		{
			//insert dynamic values in query
			$query = str_replace('{recID}', $recID, $field['query']);
			$query = str_replace('{value}', $raw, $query);
			$result = $this->query($query);
			$row = mysql_fetch_array($result);
			$value = $row[0];
		}
		
		//hyperlink if necessary
		if(isset($field['link']))
		{
			$url = str_replace('{value}', $raw, $field['link']);
			$url = str_replace('{recID}', $recID, $url);
			$value = "<a href=\"".$url."\">".$value."</a>";
		}
		if(strpos($value, 'http://') === 0)
		{
			$value = "<a href=\"".$value."\" target=\"_blank\">".$value."</a>";
		}
		
		//build final output
		$output = "";
		
		//add prefix if necessary
		$output .= (isset($field['prefix'])) ? $field['prefix'] : "";
		
		//insert newly formatted value
		$output .= $value;
		
		//add suffix if necessary
		$output .= (isset($field['suffix'])) ? $field['suffix'] : "";
		
		return $output;
	}
	
	//build insert query - builds a insert query based on POST vars
	function insert_rec($child = false)
	{
		//determine the data to use
		if($child)
		{
			$table = $this->_childTable;
			$fields = $this->_childFields;
			$uniqueID = $this->_childParentID;
		}
		else
		{
			$table = $this->_dbTable;
			$fields = $this->_fields;
			$uniqueID = $this->_recsUniqueID;
		}
	

		
		//write any files if necessary
		$this->write_files();
		
		//get field names and values
		$cols = array();
		$vals = array();
		foreach($fields as $field)
		{
			//dont do anything if this is just a header
			if($field['type'] != "header" && $field['type'] != 'file_content')
			{
				//set the form field name
				$formField = 'form_'.$field['id'];
				
				//set value based on post data
				$value = (isset($_POST[$formField])) ? $_POST[$formField] : "";

				//get column name
				$column = $field['id'];
				
				//format value based on data type
				if($field['type'] == 'file')
				{
					//file field
					$value = "'".$_FILES[$formField]['name']."'";
				}
				else if($field['type'] == "date")
				{
					//date
					$yearValue = $_POST["form_year" .$field['id']];
					$monthValue = $_POST["form_month" .$field['id']];
					$dayValue = $_POST["form_day" .$field['id']];
					
					//time
					if(isset($_POST["form_hour" .$field['id']]))
					{
						$hourValue = $_POST["form_hour" .$field['id']];
						$minuteValue = $_POST["form_minute" .$field['id']];
						$secondValue = $_POST["form_second" .$field['id']];
						$value = "'".date('Y-m-d h:i:s',mktime($hourValue, $minuteValue, $secondValue, $monthValue, $dayValue, $yearValue))."'";
					}
					else
					{
						$value = "'".date('Y-m-d',mktime(0, 0, 0, $monthValue, $dayValue, $yearValue))."'";
					}
				}
				else if($field['type'] == 'dynamic')
				{
					//if this is a dynamic list with a type of checkboxes then turn the field value into a comma seperated list
					
					if($field['dynamic_type'] == "checkbox" && $value != "") $value = "'".implode(",", $value)."'";
					
				}
				else
				{
					//sanitize data
					$value = addSlashes($value);
					
					//wrap with single quotes
					$value = "'".$value."'";
				}
				

				//save to arrays
				$cols[] = $column;
				$vals[] = $value;
			}
		}
		
		//implode data
		$cols = implode(',', $cols);
		$vals = implode(',', $vals);
		
		//build the query
		$query = "INSERT INTO $table ($cols) VALUES ($vals)";
		
		//run the query
		$this->query($query);
		
		//determine the new record ID
		$maxIDresult = $this->query("SELECT MAX({$uniqueID}) as maxID FROM {$table}");
		$maxIDrow = mysql_fetch_array($maxIDresult);
		$recID = $maxIDrow['maxID'];
		
		//upload any files if necessary
		$this->upload_files($recID);
		
		if(isset($this->_onInsert)) call_user_func($this->_onInsert, $recID);
		
		//return the new record id
		return $recID;
	}
	
	
}

?>