<?php
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../index.php");
} 
include "../dbcon/dbcon.php";
include "../func/functions.php";
if(isset($_POST["VLMainForm"])){
	$response=array(
       "status"=>0,
       "vid"=>0,
       "message"=>"Oops Something Went Wrong"
	);
	$pid=base64_decode($_POST["pid"])/88888;
	$cid=base64_decode($_POST["cid"])/99999;
	$selectStatus=$_POST["selectStatus"];
	$hallmark=safeInput($dbcon,$_POST["hallmark"]);
	$vehicleType=$_POST["vehicleType"];
	$carname=safeInput($dbcon,$_POST["carname"]);
	$conYear=date("Y-m-d",strtotime($_POST["conYear"]));
	$keyNum=safeInput($dbcon,$_POST["keyNum"]);
	$milOrg=safeInput($dbcon,$_POST["milOrg"]);
	$milEmp=safeInput($dbcon,$_POST["milEmp"]);
	$serLast=date("Y-m-d",strtotime($_POST["serLast"]));
	$serNext=date("Y-m-d",strtotime($_POST["serNext"]));
	$tuvLast=date("Y-m-d",strtotime($_POST["tuvLast"]));
    $tuvNext=date("Y-m-d",strtotime($_POST["tuvNext"]));
	$numKeys=safeInput($dbcon,$_POST["numKeys"]);
	$serNext=safeInput($dbcon,$_POST["serNext"]);
	$created_date=date("Y-m-d H:i");
	$last_updated_on=date("Y-m-d H:i:s");
    $uploadDir='../img/vehicle_uploads/';
    $uploadedFile='';
    $uploadOK=0;

	$errFile = $succFile = $errTextfield = $succTextfield = $errsubtext = $succsubtext = $succheck=$errorcheck=$statusVehicle=$succCom=$errorCom=0;
    $vid=0;
    if(!empty($hallmark) && !empty($carname)  && !empty($keyNum) && !empty($conYear)){
    	if(isset($_POST["vid"])){
    		$vid=base64_decode($_POST["vid"])/55555;
    		$prevQRY=mysqli_query($dbcon,"SELECT * FROM vehicle_list WHERE id='$vid'");
           	$prevROW=mysqli_fetch_assoc($prevQRY);
            $update="UPDATE vehicle_list SET hallmark='$hallmark',vehicle_type='$vehicleType',car_name='$carname',key_number='$keyNum',mileage_original='$milOrg',mileage_employee='$milEmp',service_last='$serLast',service_next='$serNext',tuv_last='$tuvLast',tuv_next='$tuvNext',number_of_keys='$numKeys',last_updated_by='$pid',last_updated_on='$last_updated_on' WHERE id='$vid'";
            $qry=mysqli_query($dbcon,$update);
            if($qry){
            	$updateQRY=mysqli_query($dbcon,"SELECT * FROM vehicle_list WHERE id='$vid'");
           	    $updateROW=mysqli_fetch_assoc($updateQRY);
           	    $total_changes=updatedRowVehicleList($dbcon,$cid,$pid,$vid,$prevROW,$updateROW);
            	if(isset($_FILES["carimg"])){
		        	for($i=0;$i<count($_FILES["carimg"]["name"]);$i++){
		        		$fileName = basename($_FILES["carimg"]["name"][$i]);
			            $fileName=rand(1000000000,9999999999)."_".$fileName;  
			            $targetFilePath = $uploadDir . $fileName; 
			            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
			            $allowTypes = array('jpg', 'png', 'jpeg','webp'); 
			            if(in_array($fileType, $allowTypes)){ 
			                 if($_FILES["carimg"]["size"][$i] > 5000000 || $_FILES["carimg"]["size"][$i] < 1000){
			                    $response['message'] = 'Max upload size 600kb min upload Size 1kb'; 
			                 }else{
			                    if(move_uploaded_file($_FILES["carimg"]["tmp_name"][$i], $targetFilePath)){ 
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
			            	$qry=mysqli_query($dbcon,"INSERT INTO vehicle_image(img,vid,cid) VALUES('$uploadedFile','$vid','$cid')");
			            	if($qry){
	                            $succFile++;
			            	}else{
	                            $errFile++;
			            	}
			            }
		        	}
		        }
		        if(isset($_POST["mainTextvalue"])){
		        	    $maintxtDelete=mysqli_query($dbcon,"DELETE FROM vehicle_list_extra_field WHERE vid='$vid'");
			        	$mainTextvalue=json_decode($_POST["mainTextvalue"]);
			        	$mainTextLabel=json_decode($_POST["mainTextLabel"]);
			        	$mainTextCheckbox=json_decode($_POST["mainTextCheckbox"]);
			        	$refrenceIdField=json_decode($_POST["refrencefield"]);
			        	for($i=0;$i < count((array)$mainTextLabel);$i++){ 
			        	 $mainTextLabel_s=safeInput($dbcon,$mainTextLabel[$i]);
			        	 $mainTextvalue_s=safeInput($dbcon,$mainTextvalue[$i]);
			        	 $mainTextCheckbox_s=$mainTextCheckbox[$i];
			        	 if(!empty($refrenceIdField[$i])){
			        	 	$refID=$refrenceIdField[$i];
                           $qry=mysqli_query($dbcon,"INSERT INTO vehicle_list_extra_field(textname,textval,vid,cid,visible,reference_id_field) VALUES('$mainTextLabel_s','$mainTextvalue_s','$vid','$cid','$mainTextCheckbox_s','$refID')");
			        	 }else{
			        	 	$refID=date("YmdHis");
			        	 	$qry=mysqli_query($dbcon,"INSERT INTO vehicle_list_extra_field(textname,textval,vid,cid,visible,reference_id_field) VALUES('$mainTextLabel_s','$mainTextvalue_s','$vid','$cid','$mainTextCheckbox_s','$refID')");
			        	 }
			             
		                 if($qry){
		                 	$succTextfield++;
		                 }else{
		                 	$errTextfield++;
		                 }
		        	}
		        }
		        if(isset($_POST["labelname"])){	
		          $last_updated_on=date("Y-m-d H:i:s"); 
		          $labelname=$_POST["labelname"];       
		          for($i=0;$i < count($labelname);$i++){
		          	 $labelname_s=safeInput($dbcon,$labelname[$i]);
		          	 $labelvalue=$_POST[$labelname_s];
		          	 $view=$_POST["showAll"][$i];
		          	 $qry='';
		          	 if(isset($_POST["checkbox_id"][$i])){
		          	 	$id=$_POST["checkbox_id"][$i];
                      $qry=mysqli_query($dbcon,"UPDATE vehicle_list_checkbox SET label_name='$labelname_s',label_value='$labelvalue',cid='$cid',view='$view',pid='$pid',last_updated_on='$last_updated_on' WHERE id='$id' AND vid='$vid'");
		          	 }else{
		          	 	if(isset($_POST["reference_id"][$i])){
		          	 	   $reference_id=$_POST["reference_id"][$i];
		          	 	   $qry=mysqli_query($dbcon,"INSERT INTO vehicle_list_checkbox(label_name,label_value,cid,view,pid,vid,last_updated_on,reference_id) VALUES('$labelname_s','$labelvalue','$cid','$view','$pid','$vid','$last_updated_on','$reference_id')");
		          	    }else{
		          	       $reference_id=date("YmdHis");
		          	 	   $qry=mysqli_query($dbcon,"INSERT INTO vehicle_list_checkbox(label_name,label_value,cid,view,pid,vid,last_updated_on,reference_id) VALUES('$labelname_s','$labelvalue','$cid','$view','$pid','$vid','$last_updated_on','$reference_id')");
		          	    }
		          	 }
		          	 if($qry){
		          	 	$succheck++;
		          	 }else{
		          	 	$errorcheck++;
		          	 }
		          }
		        }
		        if(isset($_POST["commentLabel"])){	
		        	$created_data=date("Y-m-d H:i:s");
		        	if(!empty($_POST["commentLabel"])){
		        	   $sbtxtDelete=mysqli_query($dbcon,"DELETE FROM vehicle_list_comment WHERE vid='$vid'");
		        	   $comLabel=json_decode($_POST["commentLabel"]);
		        	   $comtext=json_decode($_POST["commentText"]);
		        	   for($i=0;$i < count((array)$comLabel);$i++){ 
		        	   	$comLabel_s=safeInput($dbcon,$comLabel[$i]);
		        	   	$comtext_s=safeInput($dbcon,$comtext[$i]);
                        $insertComment=mysqli_query($dbcon,"INSERT INTO vehicle_list_comment(com_label,com_text,vid,cid,pid,created_date) VALUES('$comLabel_s','$comtext_s','$vid','$cid','$pid','$created_data')");
	                        if($insertComment){
                              $succCom++;    
			        	    }else{
			        	       $errorCom++;
			        	    }
                        }
		        	}
		        }
		        if(isset($_POST["stextLabel"])){
		        	if(!empty($_POST["stextLabel"])){
		        		$sbtxtDelete=mysqli_query($dbcon,"DELETE FROM vehicle_list_subtext WHERE vid='$vid'");
		        		$subtxt=json_decode($_POST["subtext"]);
		        		$stextLabel=json_decode($_POST["stextLabel"]);
			        	for($i=0;$i < count((array)$subtxt);$i++){ 
			        	 $subtext=safeInput($dbcon,$subtxt[$i]);
			        	 $label=safeInput($dbcon,$stextLabel[$i]);
				        	 if(!empty($label)){
				        	 	 $qry=mysqli_query($dbcon,"INSERT INTO vehicle_list_subtext(textlabel,textvalue,vid,cid) VALUES('$label','$subtext','$vid','$cid')");
	                             if($qry){
	                             	$succsubtext++;
	                             }else{
	                             	$errsubtext++;
	                             }
				        	 }
			        	}
		        	}
		        }
                $qrystatus=mysqli_query($dbcon,"SELECT avaibility FROM vehicle_list WHERE id='$vid'");
                $arrstatus=mysqli_fetch_assoc($qrystatus);
                if($arrstatus["avaibility"] == 1){
                	$qryStatusUpdate=mysqli_query($dbcon,"UPDATE vehicle_list SET status='$selectStatus' WHERE id='$vid'");
                	$statusVehicle="";
                }else{
                    $statusVehicle="Cannot change Status while vehicle is in use..";
                } 
            }
    	}else{
    	    $insert="INSERT INTO vehicle_list(hallmark,vehicle_type,car_name,construction_year,key_number,mileage_original,mileage_employee,service_last,service_next,tuv_last,tuv_next,number_of_keys,cid,status,created_date,created_by,last_updated_by,last_updated_on) VALUES('$hallmark','$vehicleType','$carname','$conYear','$keyNum','$milOrg','$milEmp','$serLast','$serNext','$tuvLast','$tuvNext','$numKeys','$cid','$selectStatus','$created_date','$pid','$pid','$last_updated_on')";
    	    $qry=mysqli_query($dbcon,$insert);
	    	if($qry){
	    		$vid=mysqli_insert_id($dbcon);
	    		if(isset($_FILES["carimg"])){
		        	for($i=0;$i<count($_FILES["carimg"]["name"]);$i++){
		        		$fileName = basename($_FILES["carimg"]["name"][$i]);
			            $fileName=rand(1000000000,9999999999)."_".$fileName;  
			            $targetFilePath = $uploadDir . $fileName; 
			            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
			            $allowTypes = array('jpg', 'png', 'jpeg'); 
			            if(in_array($fileType, $allowTypes)){ 
			                 if($_FILES["carimg"]["size"][$i] > 5000000 || $_FILES["carimg"]["size"][$i] < 1000){
			                    $response['message'] = 'Max upload size 600kb min upload Size 1kb'; 
			                 }else{
			                    if(move_uploaded_file($_FILES["carimg"]["tmp_name"][$i], $targetFilePath)){ 
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
			            	$qry=mysqli_query($dbcon,"INSERT INTO vehicle_image(img,vid,cid) VALUES('$uploadedFile','$vid','$cid')");
			            	if($qry){
	                            $succFile++;
			            	}else{
	                            $errFile++;
			            	}
			            }
		        	}
		        }
		        if(isset($_POST["mainTextvalue"])){
			        	$mainTextvalue=json_decode($_POST["mainTextvalue"]);
			        	$mainTextLabel=json_decode($_POST["mainTextLabel"]);
			        	$mainTextCheckbox=json_decode($_POST["mainTextCheckbox"]);
			        	$refrenceIdField=json_decode($_POST["refrencefield"]);
			        	for($i=0;$i < count((array)$mainTextLabel);$i++){ 
			        	 $mainTextLabel_s=safeInput($dbcon,$mainTextLabel[$i]);
			        	 $mainTextvalue_s=safeInput($dbcon,$mainTextvalue[$i]);
			        	 $mainTextCheckbox_s= $mainTextCheckbox[$i];

			        	 if(!empty($refrenceIdField[$i])){
			        	 	$refID=$refrenceIdField[$i];
			        	 	$qry=mysqli_query($dbcon,"INSERT INTO vehicle_list_extra_field(textname,textval,vid,cid,visible,reference_id_field) VALUES('$mainTextLabel_s','$mainTextvalue_s','$vid','$cid','$mainTextCheckbox_s','$refID')");
			        	 }else{
			        	 	$refID=date("YmdHis");
			        	 	$qry=mysqli_query($dbcon,"INSERT INTO vehicle_list_extra_field(textname,textval,vid,cid,visible,reference_id_field) VALUES('$mainTextLabel_s','$mainTextvalue_s','$vid','$cid','$mainTextCheckbox_s','$refID')");
			        	 }
			             
		                 if($qry){
		                 	$succTextfield++;
		                 }else{
		                 	$errTextfield++;
		                 }
		        	}
		        }
		        if(isset($_POST["labelname"])){	
		             $last_updated_on=date("Y-m-d H:i:s");          
		          for($i=0;$i < count($_POST["labelname"]);$i++){
		          	 $labelname=$_POST["labelname"][$i];
		          	 $labelvalue=$_POST[$labelname];
		          	 $view=$_POST["showAll"][$i];
		          	 $qry='';
                     if(isset($_POST["reference_id"][$i])){
                        $reference_id=$_POST["reference_id"][$i];
                        $qry=mysqli_query($dbcon,"INSERT INTO vehicle_list_checkbox(label_name,label_value,cid,view,pid,vid,last_updated_on,reference_id) VALUES('$labelname','$labelvalue','$cid','$view','$pid','$vid','$last_updated_on','$reference_id')");
                     }else{
                     	$reference_id=date("YmdHis");
                        $qry=mysqli_query($dbcon,"INSERT INTO vehicle_list_checkbox(label_name,label_value,cid,view,pid,vid,last_updated_on,reference_id) VALUES('$labelname','$labelvalue','$cid','$view','$pid','$vid','$last_updated_on','$reference_id')");
                     }
		          	 if($qry){
		          	 	$succheck++;
		          	 }else{
		          	 	$errorcheck++;
		          	 }
		          }
		        }
		        if(isset($_POST["commentLabel"])){	
		        	$created_data=date("Y-m-d H:i:s");
		        	if(!empty($_POST["commentLabel"])){
		        	   $comLabel=json_decode($_POST["commentLabel"]);
		        	   $comtext=json_decode($_POST["commentText"]);
		        	   for($i=0;$i < count((array)$comLabel);$i++){ 
		        	   	$comLabel_s=safeInput($dbcon,$comLabel[$i]);
		        	   	$comtext_s=safeInput($dbcon,$comtext[$i]);
                        $insertComment=mysqli_query($dbcon,"INSERT INTO vehicle_list_comment(com_label,com_text,vid,cid,pid,created_date) VALUES('$comLabel_s','$comtext_s','$vid','$cid','$pid','$created_data')");
	                        if($insertComment){
                              $succCom++;    
			        	    }else{
			        	       $errorCom++;
			        	    }
                        }
		        	}
		        }
		        if(isset($_POST["stextLabel"])){
		        	if(!empty($_POST["stextLabel"])){
		        		$subtxt=json_decode($_POST["subtext"]);
		        		$stextLabel=json_decode($_POST["stextLabel"]);
			        	for($i=0;$i < count((array)$stextLabel);$i++){ 
			        	 $subtext=safeInput($dbcon,$subtxt[$i]);
			        	 $label=safeInput($dbcon,$stextLabel[$i]);
				        	 if(!empty($label)){
				        	 	 $qry=mysqli_query($dbcon,"INSERT INTO vehicle_list_subtext(textlabel,textvalue,vid,cid) VALUES('$label','$subtext','$vid','$cid')");
	                             if($qry){
	                             	$succsubtext++;
	                             }else{
	                             	$errsubtext++;
	                             }
				        	 }
			        	}
		        	}
		        }
	    	}
    	}
        if($errsubtext == 0 && $errTextfield == 0 && $errFile == 0 && $errorCom== 0){
        	$response["status"]=1;
        	$response["message"]="successfully Inserted Data .You have 0 Errors ".$statusVehicle;
        	$response["vid"]=base64_encode($vid*55555);
        }else{
        	$response["message"]="You have ".$errsubtext." Errors in subtextfield And".$errTextfield." Errors in textfield ".$errFile." Errors in Files ".$statusVehicle." Errors in Files ".$errorCom;
        }
    }else{
    	$response["message"]="Field Cannot be empty";
    }
    echo json_encode($response);
}

if(isset($_POST["getMaintextBox"])){
$response=array();
	$vid=base64_decode($_POST['vid'])/55555;
	$qry=mysqli_query($dbcon,"SELECT * FROM vehicle_list_extra_field WHERE vid='$vid'");
	if($qry){
     $rows=mysqli_num_rows($qry);
     if($rows > 0){
     	while($arr=mysqli_fetch_assoc($qry)){
     		$output=[
                 "id"=>$arr["id"],
                 "textname"=>$arr["textname"],
                 "textval"=>$arr["textval"],
                 "vid"=>$arr["vid"],
                 "cid"=>$arr["cid"],
                 "visible"=>$arr["visible"],
                 "reference_id_field"=>$arr["reference_id_field"]
     		];
     		$response[]=$output;
     	}
     }else{
     	$response['empty']=['empty'];
     }
	}
	echo json_encode($response);
}
// if(isset($_POST["getsubtextBox"])){
// $response=array();
// 	$vid=base64_decode($_POST['vid'])/55555;
// 	$qry=mysqli_query($dbcon,"SELECT * FROM vehicle_list_subtext WHERE vid='$vid'");
// 	if($qry){
//      $rows=mysqli_num_rows($qry);
//      if($rows > 0){
//      	while($arr=mysqli_fetch_assoc($qry)){
//      		$output=[
//                  "id"=>$arr["id"],
//                  "textvalue"=>$arr["textvalue"],
//                  "vid"=>$arr["vid"],
//                  "cid"=>$arr["cid"]
//      		];
//      		$response[]=$output;
//      	}
//      }else{
//      	$response['empty']=['empty'];
//      }
// 	}
// 	echo json_encode($response);
// }
if(isset($_POST["getVehicleImage"])){
$response=array();
	$vid=base64_decode($_POST['vid'])/55555;
	$qry=mysqli_query($dbcon,"SELECT * FROM vehicle_image WHERE vid='$vid'");
	if($qry){
     $rows=mysqli_num_rows($qry);
     if($rows > 0){
     	while($arr=mysqli_fetch_assoc($qry)){
     		$output=[
                 "id"=>$arr["id"],
                 "img"=>$arr["img"],
                 "vid"=>$arr["vid"],
                 "cid"=>$arr["cid"]
     		];
     		$response[]=$output;
     	}
     }else{
     	$response['empty']=['empty'];
     }
	}
	echo json_encode($response);
}
if(isset($_POST['transport_vehicle'])){
	$response=[
       "status"=>0,
       "message"=>"Oops Something Went Wrong"
	];
	$cid=$_POST["cid"];
	$pid=base64_decode($_POST["pid"])/88888;
	$vid=base64_decode($_POST["vid"])/55555;
    
	$qry=mysqli_query($dbcon,"SELECT avaibility  FROM vehicle_list WHERE id='$vid'");
	if($qry){
		$arr=mysqli_fetch_assoc($qry);
		if($arr["avaibility"] != 0){
			$qryUpdate=mysqli_query($dbcon,"UPDATE vehicle_list SET cid='$cid',created_by='$pid' WHERE id='$vid'");
			if($qryUpdate){
				$response["status"]=1;
				$response["message"]="Successfully Transported vehicle";
			}else{
				$response["message"]="Oops Something Went Wrong While Transported vehicle";
			}
		}else{
			$response["message"]="Sorry, You cannot transport this vehicle because Vehicle is Already Using By employee..!!";
		}
	}

	echo json_encode($response);
}

if(isset($_POST["delete_vehicle_image"])){
	$response=[
		"status"=>0,
		"message"=>"Oops Something Went Wrong Please Try Again.."
	];
	$img_id=$_POST["img_id"];
	$qry=mysqli_query($dbcon,"SELECT * FROM vehicle_image WHERE id='$img_id'");
	if($qry){
		if(mysqli_num_rows($qry) > 0){
			$arr=mysqli_fetch_assoc($qry);
			if(unlink("../img/vehicle_uploads/".$arr["img"])){
				$deleteImage=mysqli_query($dbcon,"DELETE FROM vehicle_image WHERE id='$img_id'");
				if($deleteImage){
					$response["status"]=1;
					$response["message"]="Successfully Removed Vehicle Image";
				}
			}else{
				$response["message"]="Error In removing File From Server..!!";
			}
		}else{
			$response["message"]="Oops Image not Exists";
		}
	}
	echo json_encode($response);
}

if(isset($_POST["saveChecbox"])){
	$response=[
		"status"=>1,
		"message"=>"Oops Something Went wrong"
	];
	$chbxLabel=safeInput($dbcon,$_POST["chbxValue"]);
	$id=$_POST["id"];

	$qry=mysqli_query($dbcon,"UPDATE vehicle_list_checkbox SET label_name='$chbxLabel' WHERE id='$id'");
	if($qry){
      $response["status"]=1;
      $response["message"]="Successfully Changed name...!!";
	}
	echo json_encode($response);
}
if(isset($_POST["removeComment"])){
	$response=[
		"status"=>1,
		"message"=>"Oops Something Went wrong"
	];
	$id=$_POST["id"];
	$qry=mysqli_query($dbcon,"DELETE FROM vehicle_list_comment WHERE id='$id'");
	if($qry){
	  $response["status"]=1;
      $response["message"]="Successfully Deleted Comment...!!";
	}
	echo json_encode($response);
}
if(isset($_POST["saveComment"])){
	$response=[
		"status"=>1,
		"message"=>"Oops Something Went wrong"
	];
	$id=$_POST["id"];
	$label=$_POST["label"];
	$qry=mysqli_query($dbcon,"UPDATE vehicle_list_comment SET com_label='$label' WHERE id='$id'");
	if($qry){
	  $response["status"]=1;
      $response["message"]="Successfully Saved Comment...!!";
	}
	echo json_encode($response);
}
if(isset($_POST["removeSubtext"])){
	$response=[
		"status"=>1,
		"message"=>"Oops Something Went wrong"
	];
	$id=$_POST["id"];
	$qry=mysqli_query($dbcon,"DELETE FROM vehicle_list_subtext WHERE id='$id'");
	if($qry){
	  $response["status"]=1;
      $response["message"]="Successfully Deleted Comment...!!";
	}
	echo json_encode($response);	
}
if(isset($_POST["vehicleHistoryTable"])){
	   $response=array();
   $cid=intval(base64_decode($_POST["cid"])/99999);
   $vid=intval(base64_decode($_POST["vid"])/55555);
   $qry=mysqli_query($dbcon,"SELECT hv.*,pa.firstname,pa.surname FROM history_vehicle hv INNER JOIN personal_admin pa ON hv.pid=pa.id WHERE hv.vid='$vid' AND hv.cid='$cid'");
   if($qry){
   	$rows=mysqli_num_rows($qry);
   	if($rows > 0){
	    while($arr=mysqli_fetch_assoc($qry)){
	    	$before='';
	    	$after='';
	      if(preg_match("/^[0-9]{4}-(0[0-9]|1[0-2])-(0[0-9]|[0-2][0-9]|3[0-1])$/",$arr["befora"])) {
	      	$before=($arr["befora"] == '0000-00-00')? "00.00.0000" : date("d.m.Y",strtotime($arr["befora"]));
			   $after=($arr["afte"] == '0000-00-00')? "00.00.0000" : date("d.m.Y",strtotime($arr["afte"]));;
			}else{
			   $before=$arr["befora"];
			   $after=$arr["afte"];
			}
	     $output=[
	     	"id"=>$arr["id"],
	     	"position"=>$arr["position"],
	     	"before"=>$before,
	     	"after"=>$after,
	     	"surname"=>$arr["surname"],
	     	"firstname"=>$arr["firstname"],
	     	"update_on"=>date("d.m.Y | H:i",strtotime($arr["update_on"]))
	     ];
           $response[]=$output;
	    }
   	}else{
   	  $response['empty']=['empty'];
   	}
   }
   echo json_encode($response);
}
if(isset($_POST["fetchVehicleInfo"])){
	$response=[];
    $cid=$_POST["cid"];
    $pageno=(isset($_POST["pageno"])) ? $_POST["pageno"] : 1;
    $limit=(isset($_POST["limit"])) ? $_POST["limit"] : 10;
    $start=($pageno * $limit)-$limit;
    $qry=mysqli_query($dbcon,"SELECT vl.*,e.firstname,e.surname FROM vehicle_list vl LEFT JOIN employee e ON vl.emp_vh_id=e.id WHERE vl.cid='$cid' LIMIT ".$start.",".$limit);
    $rows=mysqli_num_rows($qry);
		if($rows > 0){
			while($arr=mysqli_fetch_assoc($qry)){
			  $vehicle_return=true;
							// $employee_name='';
			  $status=($arr["status"] == 1) ? "<p class='text-light bg-green p-5 text-center'>aktiv</p>" : "<p class='text-light bg-red p-5 text-center'>geschlossen</p>";
		     $employee_name=($arr["avaibility"] == 1 || $arr["emp_vh_id"] == 0 ) ? "-------------" : $arr["firstname"]." ".$arr["surname"];
		     $service_last=($arr["service_last"] == "0000-00-00") ? "00.00.0000" : date("d.m.Y",strtotime($arr["service_last"]));
		     $service_next=($arr["service_next"] == "0000-00-00") ? "00.00.0000" : date("d.m.Y",strtotime($arr["service_next"]));
		     $tuv_last=($arr["tuv_last"] == "0000-00-00") ? "00.00.0000" : date("d.m.Y",strtotime($arr["tuv_last"]));
		     $tuv_next=($arr["tuv_next"] == "0000-00-00") ? "00.00.0000" : date("d.m.Y",strtotime($arr["tuv_next"]));

            $output=[
               "id"=>"00".$arr["id"],
               "vid"=>base64_encode($arr['id']*55555),
               "hallmark"=>$arr["hallmark"],
               "vehicle_type"=>$arr["vehicle_type"],
               "car_name"=>$arr["car_name"],
               "service_last"=>$service_last,
               "service_next"=>$service_next,
               "tuv_last"=>$tuv_last,
               "tuv_next"=>$tuv_next,
               "employee_name"=>$employee_name,
               "status"=>$status  
            ];
            $response[]=$output;
         }
      }else{
      	$response[]="empty";
      }
   echo json_encode($response);
}
if(isset($_POST["page"])){
$response=[];
  $cid=$_POST["cid"];
  $qry=mysqli_query($dbcon,"SELECT * FROM vehicle_list WHERE cid='$cid'");
  $count=mysqli_num_rows($qry);
  $limit=(isset($_POST["limit"])) ? $_POST["limit"] : 10; 
  $pageno =ceil($count/$limit);
  for($i =1;$i <= $pageno;$i++){
    $output=$i;
    $response[]=$output;
  } 
  echo json_encode($response);
}
 ?>