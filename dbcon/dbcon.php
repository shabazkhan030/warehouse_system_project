<?php 
$hostname="localhost";
$username="root";
$password="";
$database="d03a876f";

$dbcon=mysqli_connect($hostname,$username,$password,$database);
if(!$dbcon){
	echo mysqli_error($dbcon);
}
?>
