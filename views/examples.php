    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container inc1">
        <h1><i class="fa fa-database"></i> dbman <small>examples</small></h1>
        <p>There are plenty of examples available that can be easily used as templates for your own projects. These examples showcase the different ways you can use dbMan to manage all types of content and data.</p>
      </div>
    </div>


<div class="container" id="examples">



	<div class="row">
		<div class="col-md-3 examples-nav">
			
			<!-- side nav -->
			
				<ul class="nav nav-tabs nav-stacked" data-spy="affix" data-offset-top="250">
			    <li<?php if($url->subview == 'members' || $url->subview == "default") echo ' class="active"';?>><a href="/examples/members">Member Profiles</a></li>
			    <li<?php if($url->subview == 'tasks') echo ' class="active"';?>><a href="/examples/tasks">Task List</a></li>
			    <li<?php if($url->subview == 'gallery') echo ' class="active"';?>><a href="/examples/gallery">Image Gallery</a></li>
			    <li<?php if($url->subview == 'events') echo ' class="active"';?>><a href="/examples/events">Events Calendar</a></li>
			    <li<?php if($url->subview == 'products') echo ' class="active"';?>><a href="/examples/products">Product Catalog</a></li>
			    <li<?php if($url->subview == 'cms') echo ' class="active"';?>><a href="/examples/files">File Management</a></li>

				</ul>
			
		</div>
		<div class="col-md-9">
			<div id="demo">
			<?php 
			if($url->subview == 'default'){
				require 'demos/members.php';
			} else {
				require 'demos/'.$url->subview.'.php';
			}
			?>
			</div>
	</div>
</div>