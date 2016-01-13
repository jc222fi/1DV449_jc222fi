<?php
session_start();
if(isset($_GET['getMyFaces'])){
    echo $_SESSION["faceDetection"];
}