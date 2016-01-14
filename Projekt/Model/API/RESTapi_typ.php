<?php
session_start();
//Used to send json object with face coordinates to javascript file, and nothing else
if(isset($_GET['getMyFaces'])){
    echo $_SESSION["faceDetection"];
}