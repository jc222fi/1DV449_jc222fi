<?php
require_once("Controller/TwickrtagsController.php");


require_once("settings.php");

ini_set('display_errors', 'ON');
session_start();

$controller = new \controller\TwickrtagsController();
$controller->doApp();
