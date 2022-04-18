<?php
include '../../connect.php';
// Routes
$tpl 	     = '../../includes/templates/'; // Template Directory
$lang 	     = '../../includes/languages/'; // Language Directory
$func   	 = '../../includes/functions/'; // Functions Directory
$css     	 = '../../layout/css/'; // Css Directory
$js      	 = '../../layout/js/'; // Js Directory
$controller  = '../../controller/';
include $func . 'functions.php';
include $func . 'Calendar.php';
include $tpl . 'header.php';