<?php 
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../index.php");
}
include "../dbcon/dbcon.php";
include "../func/functions.php";
if(isset($_POST["surname"]) && isset($_POST["firstname"]) && isset($_POST["email"]) && isset($_POST["password"]))
{
$response=array(
    "status"=>0,
    "message"=>"Oops Something Went Wrong..!!!"
);
      $created_data=date("Y-m-d");
      $statusSelect=safeInput($dbcon,$_POST["statusSelect"]);
      $surname=safeInput($dbcon,$_POST["surname"]);
      $firstname=safeInput($dbcon,$_POST["firstname"]);
      $cid=$_POST["selectcompany"];
      $email=safeInput($dbcon,$_POST["email"]);
      $password=safeInput($dbcon,$_POST["password"]);
      $rpassword=safeInput($dbcon,$_POST["rpassword"]);
      $role=$_POST["role"];
      $passOk=0;

      $closed_date=($statusSelect == 1) ? '' : date("Y-m-d");
      if($password == $rpassword){
		   $passOk=1;
	  }
	  if(isset($_POST["aid"])){
	  	$pid=(isset($_POST['pid'])) ? base64_decode($_POST['pid'])/88888 : "";
	  	$aid=$_POST["aid"];
	  	if(!empty($surname) && !empty($firstname) && !empty($email)){
	  		if($passOk == 1){
			  		if(strlen($password) > 4){
		               $hashed=PasswordHash($password);
		               $prevQRY=mysqli_query($dbcon,"SELECT * FROM personal_admin WHERE id='$aid'");
		               $prevROW=mysqli_fetch_assoc($prevQRY);  

		               $qry=mysqli_query($dbcon,"UPDATE personal_admin SET surname='$surname',firstname='$firstname',cid='$cid',status='$statusSelect',password='$hashed',closed_date='$closed_date' WHERE id='$aid'");
		               if($qry){
		               	$updateQRY=mysqli_query($dbcon,"SELECT * FROM personal_admin WHERE id='$aid'");
           	            $updateROW=mysqli_fetch_assoc($updateQRY);
           	            $total_changes=updatedRowPersonalAdmin($dbcon,$aid,$pid,$prevROW,$updateROW);
						   	$fieldname=json_decode($_POST['fieldname']);
						    $fieldvalue=json_decode($_POST['fieldvalue']);
						    $checkboxvalue=json_decode($_POST['checkboxvalue']);
					         if(count($fieldname) != 0){
					            $qryDel=mysqli_query($dbcon,"DELETE FROM personal_admin_fields WHERE pid='$aid'");		    	 
								for($i=0;$i < count($fieldname);$i++){
									$fieldname_s=safeInput($dbcon,$fieldname[$i]);
									$fieldvalue_s=safeInput($dbcon,$fieldvalue[$i]);
									$checkboxvalue_s=safeInput($dbcon,$checkboxvalue[$i]);
									$qrytextField=mysqli_query($dbcon,"INSERT INTO personal_admin_fields(pid,textname,textval,visible) VALUES('$aid','$fieldname_s','$fieldvalue_s','$checkboxvalue_s')");
									if($qrytextField){
										$response["status"]=1;
			                            $response["message"]="Successfully Updated Data.!!!";
									}else{
										$response["message"]="Something Went Wrong..!!";
									}
								}
							}else{
								$response["status"]=1;
								$response["message"]="Successfully Updated Data.!!";
							}
		               }
			  		}else{
			  			$prevQRY=mysqli_query($dbcon,"SELECT * FROM personal_admin WHERE id='$aid'");
		                $prevROW=mysqli_fetch_assoc($prevQRY);
			  			$qry=mysqli_query($dbcon,"UPDATE personal_admin SET surname='$surname',firstname='$firstname',email='$email',role='$role',cid='$cid',status='$statusSelect',closed_date='$closed_date' WHERE id='$aid'");
		               if($qry){
		               	$updateQRY=mysqli_query($dbcon,"SELECT * FROM personal_admin WHERE id='$aid'");
           	            $updateROW=mysqli_fetch_assoc($updateQRY);
           	            $total_changes=updatedRowPersonalAdmin($dbcon,$aid,$pid,$prevROW,$updateROW);
						   	$fieldname=json_decode($_POST['fieldname']);
						    $fieldvalue=json_decode($_POST['fieldvalue']);
						    $checkboxvalue=json_decode($_POST['checkboxvalue']);
					         if(count($fieldname) != 0){
					            $qryDel=mysqli_query($dbcon,"DELETE FROM personal_admin_fields WHERE pid='$aid'");		    	 
								for($i=0;$i < count($fieldname);$i++){
									$fieldname_s=safeInput($dbcon,$fieldname[$i]);
									$fieldvalue_s=safeInput($dbcon,$fieldvalue[$i]);
									$checkboxvalue_s=safeInput($dbcon,$checkboxvalue[$i]);
									$qrytextField=mysqli_query($dbcon,"INSERT INTO personal_admin_fields(pid,textname,textval,visible) VALUES('$aid','$fieldname_s','$fieldvalue_s','$checkboxvalue_s')");
									if($qrytextField){
										$response["status"]=1;
			                            $response["message"]="Successfully Updated Data.!!!";
									}else{
										$response["message"]="Something Went Wrong..!!";
									}
								}
							}else{
								$response["status"]=1;
								$response["message"]="Successfully Updated Data.!!";
							}
		               }
			  		}
			  }else{
			  	$response["message"]="Password not matched..!!";
			  }
	  	}
        
	  }else{
	  	if(!empty($surname) && !empty($firstname) && !empty($email) && !empty($password) && !empty($rpassword)){ 

			if($passOk == 1){
				$Email_exists=mysqli_query($dbcon,"SELECT id,email FROM personal_admin WHERE email='$email'");
				if(mysqli_num_rows($Email_exists) == 0){
		            $hashed=PasswordHash($password);
					$qry=mysqli_query($dbcon,"INSERT INTO personal_admin(surname,firstname,email,role,cid,status,password,created_date,closed_date) VALUES('$surname','$firstname','$email','$role','$cid','$statusSelect','$hashed','$created_data','')");
					if($qry){
					   $insert_id=mysqli_insert_id($dbcon);
					   	$fieldname=json_decode($_POST['fieldname']);
					    	$fieldvalue=json_decode($_POST['fieldvalue']);
					    	$checkboxvalue=json_decode($_POST['checkboxvalue']);
				         if(count($fieldname) != 0){		    	 
							for($i=0;$i < count($fieldname);$i++){
								$fieldname_s=safeInput($dbcon,$fieldname[$i]);
								$fieldvalue_s=safeInput($dbcon,$fieldvalue[$i]);
								$checkboxvalue_s=safeInput($dbcon,$checkboxvalue[$i]);
								$qrytextField=mysqli_query($dbcon,"INSERT INTO personal_admin_fields(pid,textname,textval,visible) VALUES('$insert_id','$fieldname_s','$fieldvalue_s','$checkboxvalue_s')");
								if($qrytextField){
									$response["status"]=1;
		                            $response["message"]="Successfully Inserted Data.!!!";
								}else{
									$response["message"]="Something Went Wrong..!!";
								}
							}
						}else{
							$response["status"]=1;
							$response["message"]="Successfully Inserted Data.!!";
						}
					}
				}else{
				    $response["message"]="Email Id Already registered..!!";
				}
			}else{
				$response["message"]="Password not matched..!!";
			}
		}else{
	       $response["message"]="Please fill all the field";
		}
	  }

   echo json_encode($response);
}
// =======ADMIN TABLE LIST FETCH=========
if(isset($_POST["getRequest"])){
   $response=array();
   $qry=mysqli_query($dbcon,"SELECT pa.*,c.company_name FROM personal_admin pa INNER JOIN company c ON pa.cid = c.id");
   if($qry){
   	$rows=mysqli_num_rows($qry);
   	if($rows > 0){
	    while($arr=mysqli_fetch_assoc($qry)){
	      $cldate=$arr["closed_date"];
	      $foramtclDate=date("d.m.Y",strtotime($cldate));
	      $st=$arr["status"];
	      $status="disabled";
	      if($st == 1){
               $status="aktiv";
	      }
	      if($cldate == "0000-00-00"){
	        $foramtclDate='';
	      }
	     $output=["id"=>$arr["id"],"aid"=>base64_encode(88888*$arr["id"]),"sname"=>$arr["surname"],"fname"=>$arr["firstname"],"email"=>$arr["email"],"cname"=>$arr["company_name"],"role"=>$arr['role'],"crdate"=>date("d.m.Y",strtotime($arr["created_date"])),"cldate"=>$foramtclDate,"status"=>$status];
           $response[]=$output;
	    }
   	}else{
   	  $response['empty']=['empty'];
   	}
   }
   echo json_encode($response);
}
if(isset($_POST["getTextField"])){
	$response=array();
	$pid=base64_decode($_POST["pid"])/88888;
	$qry=mysqli_query($dbcon,"SELECT * FROM personal_admin_fields WHERE pid='$pid'");
	if($qry){
		$rows=mysqli_num_rows($qry);
	   	if($rows > 0){
			while($arr=mysqli_fetch_assoc($qry)){
	          $output=[
	             "id"=>$arr["id"],
	             "pid"=>$arr["pid"],
	             "textname"=>$arr["textname"],
	             "textval"=>$arr["textval"],
	             "visible"=>$arr["visible"]
	          ];
	          $response[]=$output;
			}
		}else{
			$response["empty"]=["empty"];
		}
    }
    echo json_encode($response);
}

if(isset($_POST["paHistoryTable"])){
   $response=array();
   $aid=intval($_POST["aid"]);
   $qry=mysqli_query($dbcon,"SELECT ph.*,pa.firstname,pa.surname FROM history_personal_admin ph INNER JOIN personal_admin pa ON ph.updated_by=pa.id WHERE ph.aid='$aid';");
   if($qry){
   	$rows=mysqli_num_rows($qry);
   	if($rows > 0){
	    while($arr=mysqli_fetch_assoc($qry)){
	     $output=[
	     	"id"=>$arr["id"],
	     	"position"=>$arr["position"],
	     	"before"=>$arr["befora"],
	     	"after"=>$arr["afte"],
	     	"surname"=>$arr["surname"],
	     	"firstname"=>$arr["firstname"],
	     	"update_on"=>date("d.m.Y",strtotime($arr["updated_on"]))
	     ];
           $response[]=$output;
	    }
   	}else{
   	  $response['empty']=['empty'];
   	}
   }
   echo json_encode($response);
}
?>