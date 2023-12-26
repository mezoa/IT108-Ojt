
<?php
$host = 'localhost';
$port = '5432';
$dbname = 'ojt';
$user = 'postgres';
$password = 'admin';

$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conn = pg_connect($connection_string);

if (!$conn) {
    echo "Failed to connect to the database.";
    exit();
}
?>
