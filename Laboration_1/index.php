<?php

session_start();

//Starting page
echo "<!DOCTYPE html>
      <html>
        <head>
          <meta charset='utf-8'>
          <title>Laboration 1</title>
        </head>
        <body>
            <h2>Laboration 1</h2>
            <form method='POST' action='Scraper.php'>
                <input type='text' name='source'>
                <input type='submit' value='Submit'>
            </form>
        </body>
      </html>";

