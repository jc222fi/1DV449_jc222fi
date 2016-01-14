<?php
require_once("controller/TwickrtagsController.php");

ini_set('display_errors', 'OFF');
session_start();

$controller = new \controller\TwickrtagsController();
$controller->doApp();
