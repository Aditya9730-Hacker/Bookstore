install.<?php
// db.php - Update these credentials for your environment
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'statmod_db';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die('DB Connect Error: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
?>