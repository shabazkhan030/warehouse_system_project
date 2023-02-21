<?php 
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../index.php");
}
include "../dbcon/dbcon.php";
include "../func/functions.php";
if(isset($_POST["cname"]) && isset($_POST["chrb"]) && isset($_POST["cemail"]) && isset($_POST["cphone"]) && isset($_POST["ctaxno"]) && isset($_POST["ccity"]))
{
$response=array(
    "status"=>0,
    "message"=>"Oops Something Went Wrong..!!!"
);

  $companyName=safeInput($dbcon,$_POST["cname"]);
  $hrb=safeInput($dbcon,$_POST["chrb"]);
  $email=safeInput($dbcon,$_POST["cemail"]);
  $phone=safeInput($dbcon,$_POST["cphone"]);
  $taxNo=safeInput($dbcon,$_POST["ctaxno"]);
  $street=safeInput($dbcon,$_POST["cstreet"]);
  $houseNO=safeInput($dbcon,$_POST["chno"]);
  $city=safeInput($dbcon,$_POST["ccity"]);
  $zip=safeInput($dbcon,$_POST["czip"]);
  $created_data=date("Y.m.d");
  $pid=base64_decode($_POST["pid"])/88888;

  $uploadDir='../img/company_uploads/';
  $uploadedFile='';
  $uploadOK=0;
	if(!empty($companyName) && !empty($hrb) && !empty($email) && !empty($phone) && !empty($taxNo) && !empty($street) && !empty($houseNO) && !empty($city) && !empty($zip))
	{
		if(isset($_POST["cid"]))
		{
			$cid=base64_decode($_POST["cid"])/99999;
		    if(!empty($_FILES["logofile"]["name"])){
			    $img_query=mysqli_query($dbcon,"SELECT logo FROM company WHERE id='$cid'");
			    $arr_img=mysqli_fetch_assoc($img_query);
			    $prev_img=$arr_img["logo"];
        		$fileName = basename($_FILES["logofile"]["name"]);
	            $fileName=rand(1000000000,9999999999)."_".$fileName;  
	            $targetFilePath = $uploadDir . $fileName; 
	            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
	            $allowTypes = array('jpg', 'png', 'jpeg'); 
	            if(in_array($fileType, $allowTypes)){ 
	                 if($_FILES["logofile"]["size"] > 600000 || $_FILES["logofile"]["size"] < 1000){
	                    $response['message'] = 'Max upload size 600kb min upload Size 1kb'; 
	                 }else{
	                    if(move_uploaded_file($_FILES["logofile"]["tmp_name"], $targetFilePath)){ 
	                        $uploadedFile = $fileName; 
	                        $uploadOK=1;
	                    }else{
	                        $response['message'] = 'Entschuldigung, beim Hochladen Ihrer Datei ist ein Fehler aufgetreten.'; 
	                    }
	                 }  
	            }else{
	                $response['message'] = 'Leider dürfen nur JPG-, JPEG- und PNG-Dateien hochgeladen werden.'; 
	            }

	            if($uploadOK == 1){
	            	unlink($uploadDir.$prev_img);
	            	$prevQRY=mysqli_query($dbcon,"SELECT * FROM company WHERE id='$cid'");
           	        $prevROW=mysqli_fetch_assoc($prevQRY);
	            	$sql="UPDATE company SET logo='$uploadedFile',company_name='$companyName',hrb='$hrb',email='$email',phone='$phone',tax_number='$taxNo',street='$street',house_no='$houseNO',city='$city',zip_code='$zip',created_date='$created_data' WHERE id='$cid'";
	            	$qry=mysqli_query($dbcon,$sql);
	            	if($qry){
	            		$eid=0;
	            	    $updateQRY=mysqli_query($dbcon,"SELECT * FROM company WHERE id='$cid'");
           	            $updateROW=mysqli_fetch_assoc($updateQRY);
           	            $total_changes=updatedRowCompany($dbcon,$cid,$pid,$prevROW,$updateROW);
					   	$fieldname=json_decode($_POST['fieldname']);
					    $fieldvalue=json_decode($_POST['fieldvalue']);
					    $checkboxvalue=json_decode($_POST['checkboxvalue']);
				        if(count($fieldname) != 0){
				            $qry=mysqli_query($dbcon,"DELETE FROM company_fields WHERE cid='$cid'");
				            if($qry){
				            	for($i=0;$i < count($fieldname);$i++){
									$fieldname_s=safeInput($dbcon,$fieldname[$i]);
									$fieldvalue_s=safeInput($dbcon,$fieldvalue[$i]);
									$checkboxvalue_s=safeInput($dbcon,$checkboxvalue[$i]);
									$qrytextField=mysqli_query($dbcon,"INSERT INTO company_fields(cid,textname,ctextval,visible) VALUES('$cid','$fieldname_s','$fieldvalue_s','$checkboxvalue_s')");
										if($qrytextField){
											$response["status"]=1;
				                     $response["message"]="Erfolgreich aktualisiert.!!!";
										}else{
											$response["message"]="Etwas ist schief gelaufen..!!";
										}  
								   }
				            }	    	 

						}else{
							$response["status"]=1;
							$response["message"]="Successfully Updated Company.!!";
						}
	                }
	            }
            }else{
				$prevQRY=mysqli_query($dbcon,"SELECT * FROM company WHERE id='$cid'");
				$prevROW=mysqli_fetch_assoc($prevQRY);
				$sql="UPDATE company SET company_name='$companyName',hrb='$hrb',email='$email',phone='$phone',tax_number='$taxNo',street='$street',house_no='$houseNO',city='$city',zip_code='$zip',created_date='$created_data' WHERE id='$cid'";
				$qry=mysqli_query($dbcon,$sql);
				if($qry){
					$updateQRY=mysqli_query($dbcon,"SELECT * FROM company WHERE id='$cid'");
					$updateROW=mysqli_fetch_assoc($updateQRY);          	 
					$total_changes=updatedRowCompany($dbcon,$cid,$pid,$prevROW,$updateROW);
					$fieldname=json_decode($_POST['fieldname']);
					$fieldvalue=json_decode($_POST['fieldvalue']);
					$checkboxvalue=json_decode($_POST['checkboxvalue']);
					if(count($fieldname) != 0){
						$qryDel=mysqli_query($dbcon,"DELETE FROM company_fields WHERE cid='$cid'");
						if($qryDel){
							for($i=0;$i < count($fieldname);$i++){
								$fieldname_s=safeInput($dbcon,$fieldname[$i]);
								$fieldvalue_s=safeInput($dbcon,$fieldvalue[$i]);
								$checkboxvalue_s=safeInput($dbcon,$checkboxvalue[$i]);
								$qrytextField=mysqli_query($dbcon,"INSERT INTO company_fields(cid,textname,ctextval,visible) VALUES('$cid','$fieldname_s','$fieldvalue_s','$checkboxvalue_s')");
								if($qrytextField){
									$response["status"]=1;
									$response["message"]="Erfolgreich aktualisiert";
								}else{
									$response["message"]="Etwas ist schief gelaufen..!!";
								}  
							}
						}	    	 

					}else{
						$response["status"]=1;
						$response["message"]="Erfolgreich aktualisiert";
					}
				}
            } 
		}else{
			$Email_exists=mysqli_query($dbcon,"SELECT id,email FROM company WHERE email='$email'");
			if(mysqli_num_rows($Email_exists) == 0){
		        if(!empty($_FILES["logofile"]["name"])){ 
		            $fileName = basename($_FILES["logofile"]["name"]);
		            $fileName=rand(1000000000,9999999999)."_".$fileName;  
		            $targetFilePath = $uploadDir . $fileName; 
		            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
		            $allowTypes = array('jpg', 'png', 'jpeg'); 
		            if(in_array($fileType, $allowTypes)){ 
		                 if($_FILES["logofile"]["size"] > 600000 || $_FILES["logofile"]["size"] < 1000){
		                    $response['message'] = 'Max upload size 600kb min upload Size 1kb'; 
		                 }else{
		                    if(move_uploaded_file($_FILES["logofile"]["tmp_name"], $targetFilePath)){ 
		                        $uploadedFile = $fileName; 
		                        $uploadOK=1;
		                    }else{
		                        $response['message'] = 'Sorry, there was an error uploading your file.'; 
		                    }
		                 }  
		            }else{
		                $response['message'] = 'Sorry, only  JPG, JPEG, & PNG files are allowed to upload.'; 
		            }
		            if($uploadOK == 1){
						
		 				$qry=mysqli_query($dbcon,"INSERT INTO company(logo,company_name,hrb,email,phone,tax_number,street,house_no,city,zip_code,created_date,pid) VALUES('$uploadedFile','$companyName','$hrb','$email','$phone','$taxNo','$street','$houseNO','$city','$zip','$created_data','$pid')");
						if($qry){
						    $cid=mysqli_insert_id($dbcon);
						    $fieldname=(array) json_decode($_POST['fieldname']);
						    $fieldvalue=json_decode($_POST['fieldvalue']);
						    $checkboxvalue=json_decode($_POST['checkboxvalue']);
					        if(count($fieldname) != 0){		    	 
								for($i=0;$i < count($fieldname);$i++){
									$fieldname_s=safeInput($dbcon,$fieldname[$i]);
									$fieldvalue_s=safeInput($dbcon,$fieldvalue[$i]);
									$checkboxvalue_s=safeInput($dbcon,$checkboxvalue[$i]);
									$qrytextField=mysqli_query($dbcon,"INSERT INTO company_fields(cid,textname,ctextval,visible) VALUES('$cid','$fieldname_s','$fieldvalue_s','$checkboxvalue_s')");
									if($qrytextField){
										$response["status"]=1;
			                            $response["message"]="Successfully Created Company.!!!";
									}else{
										$response["message"]="Something Went Wrong..!!";
									}  
								}
							}else{
								$response["status"]=1;
								$response["message"]="Successfully Created Company.!!";
							}
						}
		            }
		        }else{
		        	$response['message'] = 'Please Insert Company Logo..!!'; 
		        }
			}else{
			    $response["message"]="Sorry Company Already registered with the given Email ID..!!";
			}
		}

	}else{
	   $response["message"]="Bitte füllen Sie alle Felder aus";
	}
   echo json_encode($response);
}

if(isset($_POST["getcompanyList"])){
   $response=array();
   $qry=mysqli_query($dbcon,"SELECT * FROM company");
   if($qry){
   	$rows=mysqli_num_rows($qry);
   	if($rows > 0){
	    while($arr=mysqli_fetch_assoc($qry)){
	     $output=[
	     	"id"=>$arr["id"],"cid"=>base64_encode(99999*$arr["id"]),"cname"=>$arr["company_name"],"hrb"=>$arr["hrb"],"tax_number"=>$arr["tax_number"],"email"=>$arr["email"],"phone"=>$arr["phone"],"street"=>$arr["street"],"house_no"=>$arr["house_no"],"city"=>$arr["city"],"zip_code"=>$arr["zip_code"]
	     ];
           $response[]=$output;
	    }
   	}else{
   	  $response['empty']=['empty'];
   	}
   }
   echo json_encode($response);
}
if(isset($_POST["fetchCompany"])){
	$response=array();
	$pid=base64_decode($_SESSION['isLogedIn'])/88888;
	if($_SESSION["role"]=='super_admin'){
     $qry=mysqli_query($dbcon,"SELECT id,logo,company_name FROM company");
	}else{
     $qry=mysqli_query($dbcon,"SELECT p.id AS personal,c.id,c.logo,c.company_name FROM personal_admin p INNER JOIN company c ON p.cid=c.id WHERE p.id='$pid'");
	}
	if($qry){
		$rows=mysqli_num_rows($qry);
		if($rows > 0){
			while($arr=mysqli_fetch_assoc($qry)){
			    $output=["cid"=>base64_encode(99999*$arr["id"]),"logo"=>$arr["logo"],"cname"=>$arr["company_name"]];
			    $response[]=$output;
		    }
		}else{
			$response["empty"]=["empty"];
		}
	}
	echo json_encode($response);
}
if(isset($_POST["companyLogin"])){
$response=["status"=>0];
	$cid=intval(base64_decode($_POST["cid"])/99999);
	$qry=mysqli_query($dbcon,"SELECT id,company_name,logo FROM company WHERE id='$cid'");
	if($qry){
		$arr=mysqli_fetch_assoc($qry);
		$output=["cid"=>base64_encode($arr["id"]*99999),"cname"=>$arr["company_name"],"clogo"=>$arr["logo"]];
		$_SESSION["company"]=$output;
        $response["status"]=1;
        $response["message"]="Oops Something Went Wrong";
	}
	echo json_encode($response);
}

if(isset($_POST["getTextField"])){
	$response=array();
	$cid=base64_decode($_POST["cid"])/99999;
	$qry=mysqli_query($dbcon,"SELECT * FROM company_fields WHERE cid='$cid'");
	if($qry){
		$rows=mysqli_num_rows($qry);
	   	if($rows > 0){
			while($arr=mysqli_fetch_assoc($qry)){
	          $output=[
	             "id"=>$arr["id"],
	             "cid"=>$arr["cid"],
	             "textname"=>$arr["textname"],
	             "ctextval"=>$arr["ctextval"],
	             "visible"=>$arr["visible"]
	          ];
	          $response[]=$output;
			}
		}else{
			$response[]=["empty"];
		}
    }
    echo json_encode($response);
}

if(isset($_POST["companyHistoryTable"])){
   $response=array();
   $cid=intval(base64_decode($_POST["cid"])/99999);
   $qry=mysqli_query($dbcon,"SELECT hc.*,pa.firstname,pa.surname FROM history_company hc INNER JOIN personal_admin pa ON hc.pid=pa.id WHERE hc.cid='$cid'");
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



