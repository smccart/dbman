<?php

class url {
	
	
	public $view;
	public $subview;
	public $path;
	public $vars;
	
	function url(){
	
		//get uri
		$url = $_SERVER['REQUEST_URI'];
		
		//set _GET variables
		//this will recreate _GET variables based on the query string variables in the URI
		$varsStr = explode('?', $url);
		if(isset($varsStr[1])) {
			$varsArr = explode('&', $varsStr[1]);
			foreach($varsArr as $var) {
				$varSplit = explode('=', $var);
				$varID = $varSplit[0];
				if($varID != "") {
					$varValue = $varSplit[1];
					$this->vars[$varID] = $varValue;
					$_GET[$varID] = $varValue;
				}
			}
		}
		

		
		//determine if this is index.php access or SEO url access
		if(strpos($url, 'index.php')) {
			$this->view = $this->vars['v'];
			$this->subview = $this->vars['sub'];
			$this->path = array();
		}else{
			$path_array = explode("/",$url);
			array_shift($path_array);
			$this->path = $path_array;
			//set the view (first value in path array)
			if($this->path[0] == "") {
				$this->view = "default";
			} else {
				$this->view = $this->path[0];
			}
			//set subview (second value in path array)
			if(isset($this->path[1])) {
				$this->subview = $this->path[1];
			} else {
				$this->subview = "default";
			}
			
		}
	
	}
	
}

?>