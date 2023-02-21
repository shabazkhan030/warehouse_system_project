<?php
session_start();
require('../dbcon/dbcon.php');
require('../func/functions.php');
$response=array(
    "status"=>0,
    "message"=>"Oops!!..Something Went Wrong"
); 
if(isset($_POST["email"]) && isset($_POST["pass"])){
	$email=$_POST["email"];
	$pass=safeInput($dbcon,$_POST["pass"]);
    if(!empty($email) && !empty($pass)){
    	$qry=mysqli_query($dbcon,"SELECT * FROM personal_admin WHERE email='$email' AND status='1'");
		if($qry){
			$rows=mysqli_num_rows($qry);
			if($rows > 0){
			  $arr=mysqli_fetch_assoc($qry);
              if(password_verify($pass,$arr["password"])){
				$_SESSION["isLogedIn"]=base64_encode($arr['id']*88888);
				$_SESSION["role"]=$arr['role'];
				$response["status"]=1;
				$response["message"]="Successfully Logged In";
			   }else{
			   	$response["message"]="Wrong Password Please enter again...";
			   }
			}else{
               $response["message"]="Oops!! Email ID not found...";
			}

		}else{
			$response["message"]="Oops Somethig Went Wrong...";
		}
    }else{
    	$response["message"]="Email & Password Cannot be Empty...";
    }
  echo json_encode($response);
}
?>