<?php


//Configuration variables for database connect (used for examples only)
$config = array();
$config['db_server'] = "localhost";
$config['db_user'] = "root";
$config['db_password'] = "root";
$config['db_name'] = "dbman_demo";

//include the dbman class
require '../dbman.php';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DBMAN - Database Management Interface Utility</title>

    <!-- Bootstrap (for demo only) -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <!-- DBMan Styles -->
    <link href="../dbman.css" rel="stylesheet">
    
	
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     
     <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    
    <style>
    .example-info {
		background:linear-gradient(#f8f8f8 0%, #fafafa 100%);
		padding: 20px;
		border-radius: 5px;
		margin-top: 30px;
		margin-bottom: 30px;
		color: #333;
		border: 1px solid #ececec;
	}
    </style>
    
  </head>

  <body>

	<nav class="navbar navbar-default">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="#">DBMan - Database Management Utility</a>
	    </div>
	
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	
	      <ul class="nav navbar-nav navbar-right">
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Examples <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <li><a href="?v=tasks">Task List</a></li>
	            <li><a href="?v=members">Members</a></li>
	            <li><a href="?v=gallery">Image Gallery</a></li>
	            <li><a href="?v=products">Product Catalog</a></li>
	            <li><a href="?v=files">File Management</a></li>
	             <li><a href="?v=events">Events Calendar</a></li>
	          </ul>
	        </li>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
    
    
    <div class="container">
	    <div class="row">
		    <div class="col-md-12">
				<!-- include the view -->
		      	<?php 
		      	if(isset($_GET['v'])) {
			      	require $_GET['v'].'.php'; 
		      	} else {
			      	require 'members.php'; 
		      	}
		      	
		      	?>
		    </div>
	    </div>
    </div>

	  </body>
</html>

