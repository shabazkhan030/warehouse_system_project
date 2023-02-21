<?php 
session_start();
if(isset($_SESSION["company"]["cid"])){
   unset($_SESSION['company']);
   header("location:company.php");  
}
?>