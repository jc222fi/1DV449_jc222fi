<?php
require_once("controller/TwickrtagsController.php");
require_once("settings.php");

ini_set('display_errors', 'ON');
session_start();

$controller = new \controller\TwickrtagsController();
$controller->doApp();
