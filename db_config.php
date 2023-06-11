<?php

// Create connection
$conn = mysqli_connect('localhost','root','','php-crud');

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
