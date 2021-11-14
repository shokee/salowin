<?php
//db details
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'school';

/*$dbHost = 'localhost';
$dbUsername = 'inspavo_schoolap';
$dbPassword = 'schoolapp@123~';
$dbName = 'inspavo_school';*/

//Javior Database detail----------
/*$dbHost = 'localhost';
$dbUsername = 'handelst_schoolA';
$dbPassword = 'school@2017~';
$dbName = 'handelst_school';*/

//Connect and select the database
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
   die("Connection failed: " . $db->connect_error);
}
?>