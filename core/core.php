<?php

session_start();

require 'config.php';
require 'db.php';
require 'url.php';
require 'dbman/dbman.php';

//set URL path and view
$url = new url();

//create db connection
$db = new db($config['db_server'], $config['db_user'], $config['db_password'], $config['db_name']);


?>