<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Use your database password
$databasename = 'users';
$conn = mysqli_connect($host, $username,$password,$databasename);
if (!$conn) {
  die('Connection failed ' . mysqli_error($conn));
}

// else {
//     echo "connection Succesfull";
// }
?>