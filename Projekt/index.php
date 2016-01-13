<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <link rel="stylesheet" href="Style/Style.css" />
        <title>Project</title>
    </head>
    <body>
        <div class='container'>
            <div class='header'><h1></h1></div>
            <div class='form'>
                <form method='post' action='API/APIservice.php'>
                        <input class='search-field' type='text' name='tag'>
                        <input type='submit' value='Combine'>
                </form>
            </div>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="/jQuery/PirateMode.js"></script>
    </body>
</html>