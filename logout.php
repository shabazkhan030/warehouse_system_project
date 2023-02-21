<?php
session_start();
 if(isset($_SESSION['isLogedIn'])){
    session_destroy();
    header("location:index.php");
 }
 ?>