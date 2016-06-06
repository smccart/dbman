<?php
//include the core file which handles all PHP includes
require 'core/core.php';

//send a OK header letting the browser know this is not an error page
header("HTTP/1.1 200 OK");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DBMAN - Database Management Interface Utility</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <!-- Custom Styles -->
    <link href="/css/styles.css" rel="stylesheet">
    
    <!-- DBMAN Styles -->
    <link href="/dbman/dbman.css" rel="stylesheet">
    
    <!-- Google Fonts-->
	<link href='http://fonts.googleapis.com/css?family=Oxygen:700' rel='stylesheet' type='text/css'>
	
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    
    <!-- Jquery UI -->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    
    <!-- Jquery Easing -->
     <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
     
     <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    
    <!-- Custom JS -->
    <script src="/js/site.js"></script>
    
  </head>

  <body data-spy="scroll" data-target="#scrollspy">

		<!-- Fixed navbar -->
		<nav class="navbar navbar-inverse navbar-fixed-top">
		  <div class="container">
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		        <span class="sr-only">Toggle navigation</span>
		        <i class="fa fa-bars"></i>
		      </button>
		      <a class="navbar-brand" href="/"><i class="fa fa-database"></i> dbman</a>
		    </div>
		    <div id="navbar" class="navbar-collapse collapse">
		      <ul class="nav navbar-nav navbar-right">
		        <li <?php if($url->view == 'default') echo 'class="active"'; ?>><a href="/"> Home</a></li>
		        <li <?php if($url->view == 'docs') echo 'class="active"'; ?>><a href="/docs"> Documentation</a></li>
		        <li <?php if($url->view == 'examples') echo 'class="active"'; ?>><a href="/examples"> Examples</a></li>
		        <li <?php if($url->view == 'download') echo 'class="active"'; ?>><a href="/download"> Download</a></li>
		        
		      </ul>
		    </div><!--/.nav-collapse -->
		  </div>
		</nav> <!-- /nav -->
    
		<!-- include the view -->
      	<?php require 'views/'.$url->view.'.php'; ?>


	  	<!-- Footer -->
	  	
	  	

		<footer>
			<div class="container">
				<hr>
				<div class="row">
					<div class="col-md-6">
				 		<p>&copy; DBMan (Database Management Utility) 2015</p>
					</div>
					<div class="col-md-6 text-right">
				 		<p>Developed by Sean McCart <a href="http://seanmccart.com">(SeanMcCart.com)</a></p>
					</div>
				</div>
			</div>
		</footer>


	  </body>
</html>

