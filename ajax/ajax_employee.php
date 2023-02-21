<?php
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../index.php");
} 
include "../dbcon/dbcon.php";
include "../func/functions.php";
if(isset($_POST["createEmployeeData"]) || isset($_POST["editEmployeeData"]))
{
$response=array(
    "status"=>0,
    "message"=>"Oops Something Went Wrong..!!!"
);    
      $cid=base64_decode($_POST["cid"])/99999;
      $pid=base64_decode($_POST["pid"])/88888;
      $esname=safeInput($dbcon,$_POST["esname"]);
      $efname=safeInput($dbcon,$_POST["efname"]);
      $eemail=safeInput($dbcon,$_POST["eemail"]);
      $edob=safeInput($dbcon,$_POST["edob"]);
      $ephone=safeInput($dbcon,$_POST["ephone"]);
      $estreet=safeInput($dbcon,$_POST["estreet"]);
      $ehno=safeInput($dbcon,$_POST["ehno"]);
      $ecity=safeInput($dbcon,$_POST["ecity"]);
      $ezip=safeInput($dbcon,$_POST["ezip"]);
      $e_comment=safeInput($dbcon,$_POST["e_comment"]);
      $created_data=date("Y-m-d H:i:s");
      $selectStatus=($_POST["selectStatus"] == 0 ) ? 0 : 1;
	if(!empty($cid) && !empty($pid) && !empty($esname) && !empty($efname)){
		// && !empty($eemail) && !empty($edob) && !empty($ephone) && !empty($estreet) && !empty($ehno) && !empty($ecity) && !empty($ezip)
           if(!isset($_POST["eid"])){
					$qry=mysqli_query($dbcon,"INSERT INTO employee(cid,surname,firstname,email,date_of_birth,phone_number,street,house_no,city,zip,ecomment,status,created_or_update_on,created_or_update_by) VALUES('$cid','$esname','$efname','$eemail','$edob','$ephone','$estreet','$ehno','$ecity','$ezip','$e_comment','$selectStatus','$created_data','$pid')");
					if($qry){
						$eid=mysqli_insert_id($dbcon);
						 if(isset($_POST["fieldname"])){
		                 	$qry=mysqli_query($dbcon,"DELETE FROM employee_extra_field WHERE eid='$eid'");
		                 	$fieldname=json_decode($_POST['fieldname']);
							$fieldvalue=json_decode($_POST['fieldvalue']);
							$checkboxvalue=json_decode($_POST['checkboxvalue']);
		                 	for($i=0;$i<count($fieldname);$i++){
		           				$fieldname_s=safeInput($dbcon,$fieldname[$i]);
								$fieldvalue_s=safeInput($dbcon,$fieldvalue[$i]);
								$checkboxvalue_s=safeInput($dbcon,$checkboxvalue[$i]);

								$qryExtraFieldEmp=mysqli_query($dbcon,"INSERT INTO employee_extra_field(eid,label_name,label_value,view) VALUES('$eid','$fieldname_s','$fieldvalue_s','$checkboxvalue_s')");
		                 	}
		                 }
						$response["status"]=1;
	                    $response["message"]="Successfully Inserted Data.!!!";
					}else{
						$response["message"]="Oops Something went wrong...!!";
					} 
           }else{
           	  $eid=intval(base64_decode($_POST["eid"])/77777);
           	  $prevQRY=mysqli_query($dbcon,"SELECT * FROM employee WHERE id='$eid'");
           	  $prevROW=mysqli_fetch_assoc($prevQRY);
           	  $qry='';
               if($selectStatus == 0){
               	   if(getvehicleLog($dbcon,$eid) == 'Nein' && getEquipment($dbcon,$eid) == 'Nein' && gettankChip($dbcon,$eid) == 'Nein' && getUTAfuelcard($dbcon,$eid) == 'Nein'){
   	   	           	  $qry=mysqli_query($dbcon,"UPDATE employee SET cid='$cid',surname='$esname',firstname='$efname',email='$eemail',date_of_birth='$edob',phone_number='$ephone',street='$estreet',house_no='$ehno',city='$ecity',zip='$ezip',ecomment='$e_comment',status='$selectStatus',created_or_update_on='$created_data',created_or_update_by='$pid' WHERE id='$eid'");
               	   }else{
                       $response["message"]="Sorry You cannot change Employee Status";
                       $qry=false;
               	   }
               }else{
					$qry=mysqli_query($dbcon,"UPDATE employee SET cid='$cid',surname='$esname',firstname='$efname',email='$eemail',date_of_birth='$edob',phone_number='$ephone',street='$estreet',house_no='$ehno',city='$ecity',zip='$ezip',ecomment='$e_comment',status='$selectStatus',created_or_update_on='$created_data',created_or_update_by='$pid' WHERE id='$eid'");

               }
           	  if($qry){   
                 if(isset($_POST["fieldname"])){
                 	$qry=mysqli_query($dbcon,"DELETE FROM employee_extra_field WHERE eid='$eid'");
                 	$fieldname=json_decode($_POST['fieldname']);
					$fieldvalue=json_decode($_POST['fieldvalue']);
					$checkboxvalue=json_decode($_POST['checkboxvalue']);
                 	for($i=0;$i<count($fieldname);$i++){
           				$fieldname_s=safeInput($dbcon,$fieldname[$i]);
						$fieldvalue_s=safeInput($dbcon,$fieldvalue[$i]);
						$checkboxvalue_s=safeInput($dbcon,$checkboxvalue[$i]);

						$qryExtraFieldEmp=mysqli_query($dbcon,"INSERT INTO employee_extra_field(eid,label_name,label_value,view) VALUES('$eid','$fieldname_s','$fieldvalue_s','$checkboxvalue_s')");
                 	}
                 } 
           	  	 $updateQRY=mysqli_query($dbcon,"SELECT * FROM employee WHERE id='$eid'");
           	     $updateROW=mysqli_fetch_assoc($updateQRY);   
                 $total_changes=updatedRowEmployee($dbcon,$eid,$cid,$pid,$prevROW,$updateROW);
                 ($total_changes == 0) ? $response["message"]="No changes are made" : $response["message"]="Successfully Changes ".$total_changes." Columns";
                $response["status"]=1;

           	  }
           }
	}else{
       $response["message"]="Please fill all the field";
	}
   echo json_encode($response);
}
if(isset($_POST["getEmployeeList"])){
   $response=array();
   $cid=intval(base64_decode($_POST["cid"])/99999);
   if($cid == 0){
       $response['empty']=$cid;
   }else{
   	  $limit=(isset($_POST["limit"])) ? $_POST["limit"] : 10;
      $pageno=(isset($_POST["pageno"])) ? $_POST["pageno"] : 1;
      $start=($pageno * $limit)-$limit;
   	$qry=mysqli_query($dbcon,"SELECT e.*,pa.firstname AS afname,pa.surname AS asname FROM employee e INNER JOIN personal_admin pa ON e.created_or_update_by=pa.id WHERE e.cid='$cid' LIMIT ".$start.",".$limit);
		   if($qry){
		   	$rows=mysqli_num_rows($qry);
		   	if($rows > 0){
			    while($arr=mysqli_fetch_assoc($qry)){
			    	$date=($arr["created_or_update_on"] == '0000-00-00 00:00:00') ? "00.00.0000" : date("d.m.Y",strtotime($arr["created_or_update_on"]));
			     $output=[
			     	"id"=>$arr["id"],
			     	"eid"=>base64_encode(77777*$arr["id"]),
			     	"sname"=>$arr["surname"],
			     	"fname"=>$arr["firstname"],
			     	"eqp"=>(getEquipment($dbcon,$arr['id']) == 'Ja') ? '<p class="d-inline-block bg-green p-5 ml-10 text-light">Ja</p>' : '<p class="d-inline-block bg-red p-5 ml-10 text-light">Nein</p>',
			     	"tc"=>(gettankChip($dbcon,$arr['id']) == 'Ja') ? '<p class="d-inline-block bg-green p-5 ml-10 text-light">Ja</p>' : '<p class="d-inline-block bg-red p-5 ml-10 text-light">Nein</p>',
			     	"ufc"=>(getUTAfuelcard($dbcon,$arr['id']) == 'Ja') ? '<p class="d-inline-block bg-green p-5 ml-10 text-light">Ja</p>' : '<p class="d-inline-block bg-red p-5 ml-10 text-light">Nein</p>',
			     	"vl"=>(getvehicleLog($dbcon,$arr['id']) == 'Ja') ? '<p class="d-inline-block bg-green p-5 ml-10 text-light">Ja</p>' : '<p class="d-inline-block bg-red p-5 ml-10 text-light">Nein</p>',
			     	"dl"=>getDrivingLicense($dbcon,$arr['id']),
			     	"created_updated_by"=>$date."|".$arr["afname"]." ".$arr["asname"],
			     	"status"=>($arr["status"] == 0) ? '<p class="d-inline-block bg-red p-5 ml-10 text-light">disabled</p>' : '<p class="d-inline-block bg-green p-5 ml-10 text-light">Aktiv</p>'
			     ];
		           $response[]=$output;
			    }
		   	}else{
		   	  $response['empty']=['empty'];
		   	}
	   }
    }

   echo json_encode($response);
}
// PAGINATION OF EMPLOYEE TABLE
if(isset($_POST["page"])){
$response=[];
  $cid=base64_decode($_POST["cid"])/99999;
  $qry=mysqli_query($dbcon,"SELECT * FROM employee WHERE cid='$cid'");
  $count=mysqli_num_rows($qry);
  $limit=$_POST["limit"]; 
  $pageno =ceil($count/$limit);
  for($i =1;$i <= $pageno;$i++){
    $output=$i;
    $response[]=$output;
  } 
  echo json_encode($response);
}
if(isset($_POST["empTableList"])){
   $response=array();
   $eid=intval(base64_decode($_POST["eid"])/77777);
   $qry=mysqli_query($dbcon,"SELECT he.*,pa.firstname,pa.surname FROM history_employee he INNER JOIN personal_admin pa ON he.pid=pa.id WHERE eid='$eid'");
   if($qry){
   	$rows=mysqli_num_rows($qry);
   	if($rows > 0){
	    while($arr=mysqli_fetch_assoc($qry)){
	     if($arr["position"] == "status"){
	     	($arr["befora"] == 0) ? $arr["befora"]="disabled" : $arr["befora"]="active";
            ($arr["afte"] == 0) ? $arr["afte"]="disabled": $arr["afte"]="active";
	     }
	     $output=[
	     	"id"=>$arr["id"],
	     	"position"=>$arr["position"],
	     	"before"=>$arr["befora"],
	     	"after"=>$arr["afte"],
	     	"surname"=>$arr["surname"],
	     	"firstname"=>$arr["firstname"],
	     	"update_on"=>date("d.m.Y H:i",strtotime($arr["updated_on"]))
	     ];
           $response[]=$output;
	    }
   	}else{
   	  $response['empty']=['empty'];
   	}
   }
   echo json_encode($response);
}
if(isset($_POST["optionfromselect"])){
	$response=array();
	$qry=mysqli_query($dbcon,"SELECT id,article FROM inventory");
	if($qry){
		$rows=mysqli_num_rows($qry);
		if($rows > 0){
			while($arr=mysqli_fetch_assoc($qry)){
				$output=[
                   "id"=>$arr["id"],
                   "article"=>$arr["article"]
				];
				$response[]=$output;
			}
		}else{
			$response["empty"]=["empty"];
		}
	}
	echo json_encode($response);
}
if(isset($_POST["workIssueSubmit"])){
$response=array(
   "status"=>0,
   "message"=>"Oops Something Went Wrong.."
);

	 $cid=base64_decode($_POST["cid"])/99999;
    $pid=base64_decode($_POST["pid"])/88888;
    $eid=base64_decode($_POST["eid"])/77777;
    $error=0;
    $success=0;
    $created_datetime=date("Y-m-d H:i:s");
    for($i=0;$i<count($_POST["issueItem"]);$i++){
      	$issueItem=$_POST["issueItem"][$i];
	      	if($_POST["weid"][$i] != ''){
	      		 $id=$_POST["weid"][$i];
	      		 $slctSign=mysqli_query($dbcon,"SELECT staff_signature,signed_by,employee_signature FROM work_equipment_issue WHERE id='$id'");
	      		 $sign=mysqli_fetch_assoc($slctSign);
	      		 $fileStaff='';
	      		 $fileEmpl='';
	      		 $signStaff='';
	      		 $signed_by=0;
	      		 if($sign["staff_signature"] == ''){
	      		 	$signStaff=$_POST['signature_staff_handover'][$i];
	      		 	$signed_by=(empty($_POST['signature_staff_handover'][$i])) ? 0 : $pid;
	      		 }else{
	      		 	$fileStaff=$sign["staff_signature"];
	      		 	$signed_by=$sign["signed_by"];
	      		 }
	      		 if($sign["employee_signature"] == ''){
	      		 	$signEmpl=$_POST['signature_employee_handover'][$i];
	      		 }else{
	      		 	$fileEmpl=$sign["employee_signature"];
	      		 }
	      		 if(!empty($signStaff)){
	      		 		$fileStaff="signaturestaf_".date("Ymdhis").$i.".png";
	                  $uploadPath="../img/signature_uploads/";
	                 file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
	      		 }
	      		 if(!empty($signEmpl)){
	      		 	   $fileEmpl="signatureEmpl_".date("Ymdhis").$i.".png";
					      $uploadPath="../img/signature_uploads/";
					      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
	      		 }
	             $qryUpdate=mysqli_query($dbcon,"UPDATE work_equipment_issue SET staff_signature='$fileStaff',signed_by='$signed_by',employee_signature='$fileEmpl' WHERE id='$id'");
	             if($qryUpdate){
	             	$success++;
	             }else{
	             	$error++;
	             }
	      	}else{
	      		if($_POST["slectedCrowd"][$i] != 0){
	   			 $slectedCrowd=$_POST["slectedCrowd"][$i];
	      		 $signStaff=($_POST['signature_staff_handover'][$i] == '') ?  '' : $_POST['signature_staff_handover'][$i];
	      		 $signEmpl=($_POST['signature_employee_handover'][$i] == '') ? '' : $_POST['signature_employee_handover'][$i];
	      		 $fileStaff='';
	      		 $fileEmpl='';
	      		 $signed_by=0;
	      		 if(!empty($signStaff)){
	      		 		$fileStaff="signaturestaf_".date("Ymdhis").$i.".png";
	                  $uploadPath="../img/signature_uploads/";
	                 file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
	                 $signed_by=$pid;
	      		 }
	      		 if(!empty($signEmpl)){
	      		 	   $fileEmpl="signatureEmpl_".date("Ymdhis").$i.".png";
					      $uploadPath="../img/signature_uploads/";
					      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
	      		 }
	            if(!empty($issueItem) && $issueItem != 0){
	            	 $issueDate=(isset($_POST["issueDate"][$i])) ? $_POST["issueDate"][$i] : '';
	      		    if(!empty($issueDate)){
		      		    if(getInvetoryDetails($dbcon,$issueItem,$slectedCrowd)){
		      		    	$print_comment='§ 2 Die Arbeitsmittel sind vom Arbeitnehmer ausschließlich im Rahmen seiner Tätigkeit für die Firma zu benutzen. Eine Nutzung für private Zwecke ist nicht gestattet. Der Arbeitnehmer ist nicht berechtigt, Arbeitsmittel Dritten zu überlassen oder Zugang zu gewähren.
<br><br>
§ 3 Der Arbeitnehmer hat dafür Sorge zu tragen, dass Schäden, die an den Arbeitsmitteln auftreten, unverzüglich dem Unternehmer gemeldet werden.
<br><br>
§ 4 Schäden, die auf normalen Verschleiß zurückzuführen sind, werden auf Kosten der Firma beseitigt. Bei unsachgemäßem Gebrauch und bei Verlust der Arbeitsmittel trägt der Arbeitnehmer bei nachgewiesener grober Fahrlässigkeit 100% der Wiederbeschaffungskosten und 50 % der Wiederbeschaffungskosten, wenn eine grobe Fahrlässigkeit nach den bekannten Begleitumständen nicht ausgeschlossen werden kann. Die Firma ist berechtigt, die Kosten vom Lohn in Abzug zu bringen.
<br><br>
§ 5 Der Arbeitnehmer bestätigt mit seiner Unterschrift unter diese Vereinbarung, dass er die Arbeitsmittel von der Firma in funktionsfähigem und mangelfreiem Zustand erhalten hat.
<br><br>
§ 6 Endet das Arbeitsverhältnis, hat der Arbeitnehmer die Arbeitsmittel unaufgefordert zurückzugeben. Auch während des Bestands des Arbeitsverhältnisses hat der Arbeitnehmer einer Rückgabeaufforderung durch die Firma unverzüglich Folge zu leisten. Ein Zurückbehaltungsrecht';
		      		    	$qry=mysqli_query($dbcon,"INSERT INTO work_equipment_issue(article,data,article_crowd,selected_crowd,staff_signature,signed_by,employee_signature,pid,cid,eid,print_heading,print_comment,created_datetime) VALUES('$issueItem','$issueDate','$slectedCrowd','$slectedCrowd','$fileStaff','$signed_by','$fileEmpl','$pid','$cid','$eid','WORK EQUIPMENT','$print_comment','$created_datetime')");
				            if($qry){
				            	$success++; 	
				            }
		      		    }
		      	    }else{
		      	    	$error++;
		      	    }		        
			      }
	      	}else{
			       $error++;
		      }      
	      }
    }
	   if($success > 0){
	   	$response["status"]=1;
	      $response["message"]="You have Successfully Inserted ".$success." workEquipment data.Error Found ".$error; 
	   }else{
	      $response["message"]="There is a problem while inserting WorkEquipment row.Please make sure that no field is empty"; 
	   }

    echo json_encode($response);
}
if(isset($_POST["workreturnSubmit"])){
$response=array(
   "status"=>0,
   "message"=>"Oops Something Went Wrong.."
);
	 $cid=base64_decode($_POST["cid"])/99999;
    $pid=base64_decode($_POST["pid"])/88888;
    $eid=base64_decode($_POST["eid"])/77777;
    $error=0;
    $success=0;
    $created_datetime=date("Y-m-d H:i:s");
    for($i=0;$i<count($_POST["returnItem"]);$i++){

        	if(isset($_POST["returnCrowd"][$i]) && $_POST["returnCrowd"][$i] != 0){
      	$returnItem=$_POST["returnItem"][$i];

	      	if($_POST["werid"][$i] != ''){
	      		 $id=$_POST["werid"][$i];
	      		 $slctSign=mysqli_query($dbcon,"SELECT staff_signature,signed_by,employee_signature FROM work_equipment_return WHERE id='$id'");
	      		 $sign=mysqli_fetch_assoc($slctSign);
	      		 $fileStaff='';
	      		 $fileEmpl='';
	      		 $signStaff='';
	      		 $signed_by=0;
	      		 if($sign["staff_signature"] == ''){
	      		 	$signStaff=$_POST['signature_staff_return'][$i];
	      		 	$signed_by=(empty($_POST['signature_staff_return'][$i])) ? 0 : $pid;
	      		 }else{
	      		 	$fileStaff=$sign["staff_signature"];
	      		 	$signed_by=$sign["signed_by"];
	      		 }
	      		 if($sign["employee_signature"] == ''){
	      		 	$signEmpl=$_POST['signature_employee_return'][$i];
	      		 }else{
	      		 	$fileEmpl=$sign["employee_signature"];
	      		 }
	      		 if(!empty($signStaff)){
	      		 		$fileStaff="signstafret_".date("Ymdhis").$i.".png";
	                  $uploadPath="../img/signature_uploads/";
	                 file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
	      		 }
	      		 if(!empty($signEmpl)){
	      		 	   $fileEmpl="signEmplret_".date("Ymdhis").$i.".png";
					      $uploadPath="../img/signature_uploads/";
					      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
	      		 }
	             $qryUpdate=mysqli_query($dbcon,"UPDATE work_equipment_return SET staff_signature='$fileStaff',signed_by='$signed_by',employee_signature='$fileEmpl' WHERE id='$id'");
	             //article='$returnItem', here i remove article update once issued
	             if($qryUpdate){
	             	$success++;
	             }else{
	             	$error++;
	             }
	      	}else{
	      		 $lost=$_POST["lost"][$i];
	   			 $slectedCrowd=$_POST["returnCrowd"][$i];
	      		 $signStaff=($_POST['signature_staff_return'][$i] == '') ?  '' : $_POST['signature_staff_return'][$i];
	      		 $signEmpl=($_POST['signature_employee_return'][$i] == '') ? '' : $_POST['signature_employee_return'][$i];
	      		 $fileStaff='';
	      		 $fileEmpl='';
	      		 $signed_by=0;
	      		 if(!empty($signStaff)){
	      		 		$fileStaff="signstafret_".date("Ymdhis").$i.".png";
	                  $uploadPath="../img/signature_uploads/";
	                 file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
	                 $signed_by=$pid;
	      		 }
	      		 if(!empty($signEmpl)){
	      		 	   $fileEmpl="signEmplret_".date("Ymdhis").$i.".png";
					      $uploadPath="../img/signature_uploads/";
					      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
	      		 }
	            if(!empty($returnItem) && $returnItem != 0){
	            	// $success++;
	            	 $returnDate=$_POST["returnDate"][$i];
	      		    if(!empty($returnDate)){
	      		    		// $success++;
	      		    	      $ri=explode('#',$returnItem);
							      $article_id=$ri[0];
							      $weid=$ri[1];
		      		    if(InvetoryReturn($dbcon,$article_id,$weid,$slectedCrowd,$lost,$eid)){
                        $print_comment='2Handover check forms contain various details like the vehicle identification number (VIN), date, time, etc. There is various information that can be mentioned in the handover check form like engine condition, lights, tires, windows, airbags, gear, brakes, etc. Just add your logo, change the color scheme, or add a background image to match your brand.';
		      		    	$qry=mysqli_query($dbcon,"INSERT INTO work_equipment_return(article,data_return,article_crowd,staff_signature,signed_by,employee_signature,pid,cid,eid,print_heading,print_comment,lost,created_datetime) VALUES('$article_id','$returnDate','$slectedCrowd','$fileStaff','$signed_by','$fileEmpl','$pid','$cid','$eid','WORK EQUIPMENT','$print_comment','$lost','$created_datetime')");
				            if($qry){
				            	$success++;
				            	$response["status"]=1;
				            }
		      		    }
		      	    }else{
		      	    	$error++;
		      	    }	
			        }else{
			      	    $error++;
			        }
	      	}      
	      }else{
			   $error++;
		   }
    }
	   if($success > 0){
	   	$response["status"]=1;
	      $response["message"]="You have Successfully Inserted ".$success." workEquipment data.Error Found ".$error; 
	   }else{
	      $response["message"]="There is a problem while inserting WorkEquipment row.Please make sure that no field is empty"; 
	   }

    echo json_encode($response);
}
if(isset($_POST["getPopulatedOption"])){
	$response=array();
	$selartcle=$_POST["selectedArticle"];
	$qry=mysqli_query($dbcon,"SELECT * FROM inventory WHERE id='$selartcle'");
  if($qry){
    $arr=mysqli_fetch_assoc($qry);
    $items=$arr["crowd"] - ($arr["output_item"] + $arr["defect"]+$arr["lost"]);
    $output='';
    if($items == 0){
    	 $output='<option value="0" >N/A</option>';
       $response[]=$output;
    }else{
    	  for($i=1;$i <= $items;$i++){
	       $output='<option value="'.$i.'" >'.$i.'</option>';
	       $response[]=$output;
	    }
    }
  }
  echo json_encode($response);
}

if(isset($_POST["getPopulatedReturnOption"])){
$response=array();
	$selartcle=explode('#',$_POST["selectedreturnArticle"]);
	$article_id=$selartcle[0];
	$weid=$selartcle[1];
	$eid=base64_decode($_POST["eid"])/77777;
	$qry=mysqli_query($dbcon,"SELECT article_crowd FROM work_equipment_issue WHERE article='$article_id' AND id='$weid' AND eid='$eid'");
	if($qry){
		$article_crowd=0;
		while($arr=mysqli_fetch_assoc($qry)){
			$article_crowd=$article_crowd+$arr["article_crowd"];
		}
		if($article_crowd != 0){
	    	 for($i=1;$i <= $article_crowd;$i++){
		       $output='<option value="'.$i.'" >'.$i.'</option>';
		       $response[]=$output;
		    }
		}else{
			$output='<option value="0" >N/A</option>';
			 $response[]=$output;
		}
	}
  echo json_encode($response);
}

if(isset($_POST["handoverTCSubmit"])){
$response=array(
   "status"=>0,
   "message"=>"Oops Something Went Wrong.."
);
	 $cid=base64_decode($_POST["cid"])/99999;
    $pid=base64_decode($_POST["pid"])/88888;
    $eid=base64_decode($_POST["eid"])/77777;

    $created_datetime=date("Y-m-d H:i:s");
      $success=0;
      $error=0;
     for($i=0;$i<count($_POST["hallmarkHandover"]);$i++){
     	if($_POST["hallmarkHandover"][$i] != '0'){
     		$hall_vlid=explode('#',$_POST["hallmarkHandover"][$i]); 
     		$hallhndovr=$hall_vlid[0];
     		$vlpid=(isset($hall_vlid[1])) ? $hall_vlid[1] : 0; 	
        if($_POST["htcid"][$i] != ''){
	  	  	    $id=$_POST["htcid"][$i];
	   		 $slctSign=mysqli_query($dbcon,"SELECT signature_staff,signed_by,signature_employee FROM tank_chip_handover WHERE id='$id'");
	   		 $sign=mysqli_fetch_assoc($slctSign);
	   		 $fileStaff='';
	   		 $fileEmpl='';
	   		 $signStaff='';
	   		 $signed_by=0;
	   		 if($sign["signature_staff"] == ''){
	   		 	$signStaff=$_POST['signature_staff_handover'][$i];
	   		 	$signed_by=(empty($_POST['signature_staff_handover'][$i])) ? 0 : $pid;
	   		 }else{
	   		 	$fileStaff=$sign["signature_staff"];
	   		 	$signed_by=$sign["signed_by"];
	   		 }
	   		 if($sign["signature_employee"] == ''){
	   		 	$signEmpl=$_POST['signature_employee_handover'][$i];
	   		 }else{
	   		 	$fileEmpl=$sign["signature_employee"];
	   		 }
	   		 if(!empty($signStaff)){
	   		 		$fileStaff="signaturestaf_".date("Ymdhis").$i.".png";
	               $uploadPath="../img/signature_uploads/";
	              file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
	   		 }
	   		 if(!empty($signEmpl)){
	   		 	   $fileEmpl="signatureEmpl_".date("Ymdhis").$i.".png";
				      $uploadPath="../img/signature_uploads/";
				      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
	   		 }
	          $qryUpdate=mysqli_query($dbcon,"UPDATE tank_chip_handover SET signature_staff='$fileStaff',signed_by='$signed_by',signature_employee='$fileEmpl' WHERE id='$id'");
	          if($qryUpdate){
	          	$success++;
	          	$response["status"]=1;
	          }else{
	          	$error++;
	          }
        }else{
     		$htext1=($_POST["htext"][$i] != '') ? safeInput($dbcon,$_POST["htext"][$i]) : '';
     		$htext2=($_POST["htext2"][$i] != '') ? safeInput($dbcon,$_POST["htext2"][$i]) : '';
     	   $hdate=(isset($_POST["hDate"][$i])) ? $_POST["hDate"][$i] : ''; 
        	  $signStaff=($_POST['signature_staff_handover'][$i] == '') ?  '' : $_POST['signature_staff_handover'][$i];
	        $signEmpl=($_POST['signature_employee_handover'][$i] == '') ? '' : $_POST['signature_employee_handover'][$i];
	        $fileStaff='';
	        $fileEmpl='';
	        $signed_by=0;
    		 if(!empty($signStaff)){
   		 		$fileStaff="signstafhand_".date("Ymdhis").$i.".png";
               $uploadPath="../img/signature_uploads/";
              file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
              $signed_by=$pid;
   		 }
   		 if(!empty($signEmpl)){
   		 	   $fileEmpl="signEmplhand_".date("Ymdhis").$i.".png";
			      $uploadPath="../img/signature_uploads/";
			      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
   		 }
   		 if(!empty($htext1) && !empty($hdate) && !empty($hallhndovr)){
   		 	$print_comment='3Handover check forms contain various details like the vehicle identification number (VIN), date, time, etc. There is various information that can be mentioned in the handover check form like engine condition, lights, tires, windows, airbags, gear, brakes, etc. Just add your logo, change the color scheme, or add a background image to match your brand.';
   		 	$qry=mysqli_query($dbcon,"INSERT INTO tank_chip_handover(hallmark,text1,text2,data_handover,signature_staff,signed_by,signature_employee,vlpid,pid,cid,eid,print_heading,print_comment,created_datetime) VALUES('$hallhndovr','$htext1','$htext2','$hdate','$fileStaff','$signed_by','$fileEmpl','$vlpid','$pid','$cid','$eid','Ausgabe Protokol Tankchip','$print_comment','$created_datetime')");
   		 	if($qry){
   		 		$success++;
   		 		$response["status"]=1;
   		 	}else{
   		 		$error++;
   		 	}
   		 }else{
   		 	$error++;
   		 }
        }
     	}
     }
     $errorMessage=($error > 0) ? "Please make sure that you have inserted data correctly.." : "";
     if($success > 0){
     	$response["message"]="Sucessfully Inserted...!!!.Error Found=".$error." ".$errorMessage;
     }else{
     	$response["message"]="there is an error While uploading data...!!!";
     }

	echo json_encode($response);
}
if(isset($_POST["returnTCSubmit"])){
$response=array(
   "status"=>0,
   "message"=>"Oops Something Went Wrong.."
);
	$cid=base64_decode($_POST["cid"])/99999;
    $pid=base64_decode($_POST["pid"])/88888;
    $eid=base64_decode($_POST["eid"])/77777;

    $created_datetime=date("Y-m-d H:i:s");
      $success=0;
      $error=0;
     for($i=0;$i<count($_POST["hallmarkreturn"]);$i++){
     	if($_POST["hallmarkreturn"][$i] != '0'){
     		$hall_vlid=explode('#',$_POST["hallmarkreturn"][$i]); 
     		$hallreturn=$hall_vlid[0];
     		$handoverID=(isset($hall_vlid[1])) ? $hall_vlid[1] : 0;  	
        if(!empty($_POST["rtcid"][$i])){
	  	  	    $id=$_POST["rtcid"][$i];
	   		 $slctSign=mysqli_query($dbcon,"SELECT signature_staff,signed_by,signature_employee FROM tank_chip_return WHERE id='$id'");
	   		 $sign=mysqli_fetch_assoc($slctSign);
	   		 $fileStaff='';
	   		 $fileEmpl='';
	   		 $signStaff='';
	   		 $signed_by=0;
	   		 if($sign["signature_staff"] == ''){
	   		 	$signStaff=$_POST['signature_staff_return'][$i];
	   		 	$signed_by=(empty($_POST['signature_staff_return'][$i])) ? 0 : $pid;
	   		 }else{
	   		 	$fileStaff=$sign["signature_staff"];
	   		 	$signed_by=$sign["signed_by"];
	   		 }
	   		 if($sign["signature_employee"] == ''){
	   		 	$signEmpl=$_POST['signature_employee_return'][$i];
	   		 }else{
	   		 	$fileEmpl=$sign["signature_employee"];
	   		 }
	   		 if(!empty($signStaff)){
	   		 		$fileStaff="signaturestaf_".date("Ymdhis").$i.".png";
	               $uploadPath="../img/signature_uploads/";
	              file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
	   		 }
	   		 if(!empty($signEmpl)){
	   		 	   $fileEmpl="signatureEmpl_".date("Ymdhis").$i.".png";
				      $uploadPath="../img/signature_uploads/";
				      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
	   		 }
	          $qryUpdate=mysqli_query($dbcon,"UPDATE tank_chip_return SET signature_staff='$fileStaff',signed_by='$signed_by',signature_employee='$fileEmpl' WHERE id='$id'");
	          if($qryUpdate){
	          	$success++;
	          	$response["status"]=1;
	          }else{
	          	$error++;
	          }
        }else{
     		$rtext1=($_POST["rtext"][$i] != '') ? safeInput($dbcon,$_POST["rtext"][$i]) : '';
     		$rtext2=($_POST["rtext2"][$i] != '') ? safeInput($dbcon,$_POST["rtext2"][$i]) : '';
     	   $rdate=(isset($_POST["rDate"][$i])) ? $_POST["rDate"][$i] : ''; 
        	  $signStaff=($_POST['signature_staff_return'][$i] == '') ?  '' : $_POST['signature_staff_return'][$i];
	        $signEmpl=($_POST['signature_employee_return'][$i] == '') ? '' : $_POST['signature_employee_return'][$i];
	        $fileStaff='';
	        $fileEmpl='';
	        $signed_by=0;
    		 if(!empty($signStaff)){
   		 		$fileStaff="signstafret_".date("Ymdhis").$i.".png";
               $uploadPath="../img/signature_uploads/";
              file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
              $signed_by=$pid;
   		 }
   		 if(!empty($signEmpl)){
   		 	   $fileEmpl="signEmplret_".date("Ymdhis").$i.".png";
			      $uploadPath="../img/signature_uploads/";
			      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
   		 }
   		 if(!empty($rtext1) && !empty($rdate)){
   		 	  	$success++;
   		 	$print_comment='4Handover check forms contain various details like the vehicle identification number (VIN), date, time, etc. There is various information that can be mentioned in the handover check form like engine condition, lights, tires, windows, airbags, gear, brakes, etc. Just add your logo, change the color scheme, or add a background image to match your brand.';
   		 	$qry=mysqli_query($dbcon,"INSERT INTO tank_chip_return(hallmark,text1,text2,data_return,signature_staff,signed_by,signature_employee,pid,cid,eid,print_heading,print_comment,created_datetime) VALUES('$hallreturn','$rtext1','$rtext2','$rdate','$fileStaff','$signed_by','$fileEmpl','$pid','$cid','$eid','Rückgabe Protokol Tankchip','$print_comment','$created_datetime')");
   		 	if($qry){
   		 		// if($handoverID != 0){
                  $qryhandover=mysqli_query($dbcon,"UPDATE tank_chip_handover SET issue='0',check_return='1' WHERE id='$handoverID'");
   		 		// }else{
                //   $qryhandover=mysqli_query($dbcon,"UPDATE tank_chip_handover SET tc_return='1' WHERE id='$handoverID'");
   		 		// }

   		 		$success++;
   		 		$response["status"]=1;
   		 	}else{
   		 		$error++;
   		 	}
   		 }else{
   		 	$error++;
   		 }
        }
     	}
     }
     $errorMessage=($error > 0) ? "Please make sure that you have inserted data correctly.." : "";
     if($success > 0){
     	$response["message"]="Sucessfully Inserted...!!!.Error Found=".$error." ".$errorMessage;
     }else{
     	$response["message"]="there is an error While uploading data...!!!";
     }

	echo json_encode($response);
}
if(isset($_POST["handoverUTASubmit"])){
$response=array(
   "status"=>0,
   "message"=>"Oops Something Went Wrong.."
);
	$cid=base64_decode($_POST["cid"])/99999;
    $pid=base64_decode($_POST["pid"])/88888;
    $eid=base64_decode($_POST["eid"])/77777;

    $created_datetime=date("Y-m-d H:i:s");
      $success=0;
      $error=0;
      $signed_by=0;
     for($i=0;$i<count($_POST["hallmarkHandover"]);$i++){
     	if($_POST["hallmarkHandover"][$i] != '0'){
     		// $hallhndovr=$_POST["hallmarkHandover"][$i]; 
     		$hall_vlid=explode('#',$_POST["hallmarkHandover"][$i]); 
     		$hallhndovr=$hall_vlid[0];
     		$vlpid=(isset($hall_vlid[1])) ? $hall_vlid[1] : 0;   	
        if($_POST["hutaid"][$i] != ''){
	  	  	 $id=$_POST["hutaid"][$i];
	   		 $slctSign=mysqli_query($dbcon,"SELECT signature_staff,signed_by,signature_employee FROM uta_fuel_card_handover WHERE id='$id'");
	   		 $sign=mysqli_fetch_assoc($slctSign);
	   		 $fileStaff='';
	   		 $fileEmpl='';
	   		 $signStaff='';
	   		 if($sign["signature_staff"] == ''){
	   		 	$signStaff=$_POST['signature_staff_handover'][$i];
	   		 	$signed_by=(empty($_POST['signature_staff_handover'][$i])) ? 0 : $pid;
	   		 }else{
	   		 	$fileStaff=$sign["signature_staff"];
	   		 	$signed_by=$sign["signed_by"];
	   		 }
	   		 if($sign["signature_employee"] == ''){
	   		 	$signEmpl=$_POST['signature_employee_handover'][$i];
	   		 }else{
	   		 	$fileEmpl=$sign["signature_employee"];
	   		 }
	   		 if(!empty($signStaff)){
	   		 	   $fileStaff="signaturestafuta_".date("Ymdhis").$i.".png";
	               $uploadPath="../img/signature_uploads/";
	              file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
	   		 }
	   		 if(!empty($signEmpl)){
	   		 	   $fileEmpl="signatureEmpluta_".date("Ymdhis").$i.".png";
				   $uploadPath="../img/signature_uploads/";
				   file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
	   		 }
	          $qryUpdate=mysqli_query($dbcon,"UPDATE uta_fuel_card_handover SET signature_staff='$fileStaff',signed_by='$signed_by',signature_employee='$fileEmpl' WHERE id='$id'");
	          if($qryUpdate){
	          	$success++;
	          	$response["status"]=1;
	          }else{
	          	$error++;
	          }
        }else{
     		$htext=($_POST["htext"][$i] != '') ? safeInput($dbcon,$_POST["htext"][$i]) : '';
     		$htext2=($_POST["htext2"][$i] != '') ? safeInput($dbcon,$_POST["htext2"][$i]) : '';
     	    $hdate=(isset($_POST["hDate"][$i])) ? $_POST["hDate"][$i] : ''; 
        	$signStaff=($_POST['signature_staff_handover'][$i] == '') ?  '' : $_POST['signature_staff_handover'][$i];
	        $signEmpl=($_POST['signature_employee_handover'][$i] == '') ? '' : $_POST['signature_employee_handover'][$i];
	        $fileStaff='';
	        $fileEmpl='';
    		 if(!empty($signStaff)){
   		 	   $fileStaff="signstafhanduta_".date("Ymdhis").$i.".png";
               $uploadPath="../img/signature_uploads/";
               file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
               $signed_by=$pid;
   		 }
   		 if(!empty($signEmpl)){
   		 	   $fileEmpl="signEmplhanduta_".date("Ymdhis").$i.".png";
			      $uploadPath="../img/signature_uploads/";
			      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
   		 }
   		 if(!empty($htext) && !empty($hdate)){
   		 	$print_comment='5Handover check forms contain various details like the vehicle identification number (VIN), date, time, etc. There is various information that can be mentioned in the handover check form like engine condition, lights, tires, windows, airbags, gear, brakes, etc. Just add your logo, change the color scheme, or add a background image to match your brand.';
   		 	$qry=mysqli_query($dbcon,"INSERT INTO uta_fuel_card_handover(hallmark,text1,text2,data_handover,signature_staff,signed_by,signature_employee,vlpid,pid,cid,eid,print_heading,print_comment,created_datetime) VALUES('$hallhndovr','$htext','$htext2','$hdate','$fileStaff','$signed_by','$fileEmpl','$vlpid','$pid','$cid','$eid','Übergabeprotokoll UTA Tankkarte','$print_comment','$created_datetime')");
   		 	if($qry){
   		 		$success++;
   		 		$response["status"]=1;
   		 	}else{
   		 		$error++;
   		 	}
   		 }else{
   		 	$error++;
   		 }
        }
     	}
     }
     if($success > 0){
       	$response["message"]="Sucessfully Inserted...!!!";
     }else{
      	$response["message"]="there is an error While uploading data...!!! Please fill all mandatory field";
     }

	echo json_encode($response);
}
if(isset($_POST["returnUTASubmit"])){
$response=array(
   "status"=>0,
   "message"=>"Oops Something Went Wrong.."
);
	$cid=base64_decode($_POST["cid"])/99999;
    $pid=base64_decode($_POST["pid"])/88888;
    $eid=base64_decode($_POST["eid"])/77777;

    $created_datetime=date("Y-m-d H:i:s");
      $success=0;
      $error=0;
      $signed_by=0;
     for($i=0;$i<count($_POST["hallmarkreturn"]);$i++){
     	if($_POST["hallmarkreturn"][$i] != '0'){
     		// $hallreturn=$_POST["hallmarkreturn"][$i]; 
   	     	$hall_vlid=explode('#',$_POST["hallmarkreturn"][$i]); 
     		$hallreturn=$hall_vlid[0];
     		$handoverID=(isset($hall_vlid[1])) ? $hall_vlid[1] : 0;
        if($_POST["rutaid"][$i] != ''){
	  	  	    $id=$_POST["rutaid"][$i];
	   		 $slctSign=mysqli_query($dbcon,"SELECT signature_staff,signed_by,signature_employee FROM uta_fuel_card_return WHERE id='$id'");
	   		 $sign=mysqli_fetch_assoc($slctSign);
	   		 $fileStaff='';
	   		 $fileEmpl='';
	   		 $signStaff='';
	   		 if($sign["signature_staff"] == ''){
	   		 	$signStaff=$_POST['signature_staff_return'][$i];
	   		 	$signed_by=(empty($_POST['signature_staff_return'][$i])) ? 0 : $pid;
	   		 }else{
	   		 	$fileStaff=$sign["signature_staff"];
	   		 	$signed_by=$pid;
	   		 }
	   		 if($sign["signature_employee"] == ''){
	   		 	$signEmpl=$_POST['signature_employee_return'][$i];
	   		 }else{
	   		 	$fileEmpl=$sign["signature_employee"];
	   		 }
	   		 if(!empty($signStaff)){
	   		 		$fileStaff="signaturestaf_".date("Ymdhis").$i.".png";
	               $uploadPath="../img/signature_uploads/";
	              file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
	   		 }
	   		 if(!empty($signEmpl)){
	   		 	   $fileEmpl="signatureEmpl_".date("Ymdhis").$i.".png";
				      $uploadPath="../img/signature_uploads/";
				      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
	   		 }
	          $qryUpdate=mysqli_query($dbcon,"UPDATE uta_fuel_card_return SET signature_staff='$fileStaff',signed_by='$signed_by',signature_employee='$fileEmpl' WHERE id='$id'");
	          if($qryUpdate){
	          	$success++;
	          	$response["status"]=1;

	          }else{
	          	$error++;
	          }
        }else{
     		$rtext=($_POST["rtext"][$i] != '') ? safeInput($dbcon,$_POST["rtext"][$i]) : '';
     		$rtext2=($_POST["rtext2"][$i] != '') ? safeInput($dbcon,$_POST["rtext2"][$i]) : '';
     	   $rdate=(isset($_POST["rDate"][$i])) ? $_POST["rDate"][$i] : ''; 
        	  $signStaff=($_POST['signature_staff_return'][$i] == '') ?  '' : $_POST['signature_staff_return'][$i];
	        $signEmpl=($_POST['signature_employee_return'][$i] == '') ? '' : $_POST['signature_employee_return'][$i];
	        $fileStaff='';
	        $fileEmpl='';
    		 if(!empty($signStaff)){
   		 		$fileStaff="signstafret_".date("Ymdhis").$i.".png";
               $uploadPath="../img/signature_uploads/";
              file_put_contents($uploadPath.$fileStaff,file_get_contents($signStaff));
              $signed_by=$pid;
   		 }
   		 if(!empty($signEmpl)){
   		 	   $fileEmpl="signEmplret_".date("Ymdhis").$i.".png";
			      $uploadPath="../img/signature_uploads/";
			      file_put_contents($uploadPath.$fileEmpl,file_get_contents($signEmpl));
   		 }
   		 if(!empty($rtext) && !empty($rdate)){
   		 	  	$success++;
   		 	  	$print_comment='Return check forms contain various details like the vehicle identification number (VIN), date, time, etc. There is various information that can be mentioned in the handover check form like engine condition, lights, tires, windows, airbags, gear, brakes, etc. Just add your logo, change the color scheme, or add a background image to match your brand.';
   		 	$qry=mysqli_query($dbcon,"INSERT INTO uta_fuel_card_return(hallmark,text1,text2,data_return,signature_staff,signed_by,signature_employee,pid,cid,eid,print_heading,print_comment,created_datetime) VALUES('$hallreturn','$rtext','$rtext2','$rdate','$fileStaff','$signed_by','$fileEmpl','$pid','$cid','$eid','Rückgabe UTA Tankkarte','$print_comment','$created_datetime')");
   		 	if($qry){
   		 		if($handoverID != 0){
                  $qryhandover=mysqli_query($dbcon,"UPDATE uta_fuel_card_handover SET issue='0',check_return='1' WHERE id='$handoverID'");
   		 		}
   		 		$success++;
   		 		$response["status"]=1;
   		 	}else{
   		 		$error++;
   		 	}
   		 }else{
   		 	$error++;
   		 }
        }
     	}
     }
     if($success > 0){
     	$response["message"]="Successfully Inserted Data";
     }else{
     	$response["message"]="there is an error While uploading data...!!!";
     }

	echo json_encode($response);
}
if(isset($_POST["createvehicleHandover"]) || isset($_POST["createvehiclereturn"])){
	$response=[
	   "status"=>0,
       "message"=>"Oops Something Went Wrong"
	];
	$eid=base64_decode($_POST["eid"])/77777;
	$data=date("Y-m-d H:i:s");
	$created_id=0;
	$employee_id_vl=0;
	if(isset($_POST["createvehicleHandover"])){
		$stand=1;
		$isAvailable=0;
		$data=(!empty($_POST["returndate"])) ? date("Y-m-d H:i:s",strtotime($_POST["returndate"])) : '0000-00-00 00-00-00';
		$created_id=(isset($_POST["crid"])) ? $_POST["crid"] : rand(10,100).date("Ymdhis");
		$employee_id_vl=$eid;
	}
	if(isset($_POST["createvehiclereturn"])){
		$stand=2;
		$data=(!empty($_POST["returndate"])) ? date("Y-m-d H:i:s",strtotime($_POST["returndate"])) : '0000-00-00 00-00-00';
		$isAvailable=1;
		$created_id=$_POST['crid'];
	}
	
	$last_change=date("Y-m-d H:i:s");
	$cid=base64_decode($_POST["cid"])/99999;
    $pid=base64_decode($_POST["pid"])/88888;
    $eid=base64_decode($_POST["eid"])/77777;
	$hallmark=$_POST["hallmark"];
	$carname=safeInput($dbcon,$_POST["carname"]);
	$kms=safeInput($dbcon,$_POST["kms"]);
	$vtype=$_POST["vtype"];
	$keys=safeInput($dbcon,$_POST["keys"]);
	$puov=$_POST["puov"];
	$lbm=$_POST["lbm"];
	$hoff=$_POST["hoff"];
    $error=0;
    $success=0;
   if($hallmark != 0){
	   if(!empty($kms) && !empty($carname) && !empty($keys)){
	   	if(isset($_POST["vlid"])){
	   		 $vlid=safeInput($dbcon,$_POST["vlid"]);
             $qry1=mysqli_query($dbcon,"UPDATE vehicle_log_protocol SET car_name='$carname',kms='$kms',data='$data',no_of_key='$keys',created_id='$created_id',pid='$pid',last_change='$last_change' WHERE id='$vlid'");
             if($qry1){
             	$created_date=date("Y-m-d H:i:s");
             	if(isset($_POST["mainLabel"])){
             		if($stand == 1){
             			$deleteMain=mysqli_query($dbcon,"DELETE FROM vehicle_log_extra_field WHERE vlid='$vlid' AND stand='$stand'");
             			$mainLabel=json_decode($_POST["mainLabel"]);
             			$mainValue=json_decode($_POST["mainValue"]);
             			for($i=0;$i < count($mainLabel);$i++){
             				$mainLabel_s=$mainLabel[$i];
                        $mainValue_s=$mainValue[$i];
                        $qryMain=mysqli_query($dbcon,"INSERT INTO vehicle_log_extra_field(label_name,label_value,vlid,cid,pid,stand,created_date) VALUES('$mainLabel_s','$mainValue_s','$vlid','$cid','$pid','$stand','$created_date')");
                        if($qryMain){
                        	$success++;
                        }else{
                        	$error++;
                        }
             			}
             		}else{
             			$deleteMain=mysqli_query($dbcon,"DELETE FROM vehicle_log_extra_field WHERE vlid='$vlid' AND stand='$stand'");
             			$mainLabel=json_decode($_POST["mainLabel"]);
             			$mainValue=json_decode($_POST["mainValue"]);
             			for($i=0;$i < count($mainLabel);$i++){
             				$mainLabel_s=$mainLabel[$i];
                        $mainValue_s=$mainValue[$i];
                        $qryMain=mysqli_query($dbcon,"INSERT INTO vehicle_log_extra_field(label_name,label_value,vlid,cid,pid,stand,created_date) VALUES('$mainLabel_s','$mainValue_s','$vlid','$cid','$pid','$stand','$created_date')");
                        if($qryMain){
                        	$success++;
                        }else{
                        	$error++;
                        }
             			}
             		}
             	}
             	$qry2='1';
             	if($stand == 1){
             		if(isset($_POST["checkboxLabel"])){
             			$checkboxLabel=json_decode($_POST["checkboxLabel"]);
             			for($i=0;$i < count($checkboxLabel);$i++){
             				$checkboxLabel_s=$checkboxLabel[$i];
                        $checkboxValue_s=$_POST[$checkboxLabel_s];
                        $qry2=mysqli_query($dbcon,"UPDATE vehicle_log_checkbox_handover SET label_value='$checkboxValue_s',cid='$cid',pid='$pid' WHERE vlid='$vlid' AND label_name='$checkboxLabel_s'");
             			}
             		}
             	}else{
             		if(isset($_POST["checkboxLabel"])){
             			$checkboxLabel=json_decode($_POST["checkboxLabel"]);
             			for($i=0;$i < count($checkboxLabel);$i++){
             				$checkboxLabel_s=$checkboxLabel[$i];
                        $checkboxValue_s=$_POST[$checkboxLabel_s];
                        $qry2=mysqli_query($dbcon,"UPDATE vehicle_log_checkbox_return SET label_value='$checkboxValue_s',cid='$cid',pid='$pid' WHERE vlid='$vlid' AND label_name='$checkboxLabel_s'");
             			}
             		}
             	}
             	if($qry2){
		      		$success++;
		      	}else{
		      		$error=$error++;
		      	}
		      	$qry3=mysqli_query($dbcon,"UPDATE vehicle_log_checkbox2 SET private_use='$puov',log_book='$lbm',home_office='$hoff',pid='$pid' WHERE vlid='$vlid'");
		      	if($qry3){
			      	$success++;
			      }else{
			      	$error++;
			      }

			      if(isset($_POST["checkbox2"])){
			      	$qrydelprevious=mysqli_query($dbcon,"DELETE FROM vehicle_log_checkbox2_extra WHERE vlid='$vlid' AND stand='$stand'");
			      	if($qrydelprevious){
				      	for($i=0;$i < count($_POST["checkbox2"]);$i++){
				      		$label=safeInput($dbcon,$_POST["checkbox2"][$i]);
				      		$sel_value=$_POST[$label];
				      		$qry4=mysqli_query($dbcon,"INSERT INTO vehicle_log_checkbox2_extra(checkbox_label,checkbox_value,vlid,stand) VALUES('$label','$sel_value','$vlid','$stand')");
				      		if($qry4){
				      			$success++;
				      		}else{
				      			$error++;
				      		}
				      	}
			      	}
		         }
		         if(isset($_POST["signature_fleet_mngr"]) && !empty($_POST["signature_fleet_mngr"])){
		      		$signaturefm=$_POST["signature_fleet_mngr"];
		      	   $filenamefm="signfleet_mngr".date("Ymdhis").".png";
		            $uploadPathfm="../img/signature_uploads/";
		            file_put_contents($uploadPathfm.$filenamefm,file_get_contents($signaturefm));
		            if(file_exists($uploadPathfm.$filenamefm)){
		            	$qry5=mysqli_query($dbcon,"UPDATE vehicle_log_protocol SET sign_fleet_manager='$filenamefm',signed_by='$pid' WHERE id='$vlid'");
		            }else{
		            	$error++;
		            }	
			      }
			      if(isset($_POST["signature_vh_mngr"]) && !empty($_POST["signature_vh_mngr"])){
		      		$signature=$_POST["signature_vh_mngr"];
		      	   $filename="signvh_mngr".date("Ymdhis").".png";
		            $uploadPath="../img/signature_uploads/";
		            file_put_contents($uploadPath.$filename,file_get_contents($signature));
		            if(file_exists($uploadPath.$filename)){
		            	$qry6=mysqli_query($dbcon,"UPDATE vehicle_log_protocol SET sign_vehicle_manager='$filename' WHERE id='$vlid'");
		            }else{
		            	$error++;
		            }
			      }
               if(isset($_POST["comLabel"])){
               	$qryDeleteCom=mysqli_query($dbcon,"DELETE FROM vehicle_log_comment WHERE vlid='$vlid' AND stand='$stand'");
               	$comLabel=json_decode($_POST["comLabel"]);
               	$comText=json_decode($_POST["comText"]);
               	for($i=0;$i < count($comLabel);$i++){
                    $com_label_s=safeInput($dbcon,$comLabel[$i]);
                    $comText_s=safeInput($dbcon,$comText[$i]);
                    $qrycom=mysqli_query($dbcon,"INSERT INTO vehicle_log_comment(com_label,com_value,cid,vlid,pid,stand,created_date) VALUES('$com_label_s','$comText_s','$cid','$vlid','$pid','$stand','$created_date')");
                    if($qrycom){
                    	  $success++;
                    }else{
                       $error++;
                    }
               	}
               }
			      if(isset($_POST["subtxtLabel"])){
			      	$qrydelprevsbtxt=mysqli_query($dbcon,"DELETE FROM vehicle_log_subtext WHERE vlid='$vlid' AND stand='$stand'");
		      		$subtxtLabel=json_decode($_POST["subtxtLabel"]);
		      		$subtxtvalue=json_decode($_POST["subtxtvalue"]);
		      		for($i=0;$i < count($subtxtLabel);$i++){
		      			$subtxtLabel_s=safeInput($dbcon,$subtxtLabel[$i]);
		      			$subtxtvalue_s=safeInput($dbcon,$subtxtvalue[$i]);
		      			if(!empty($subtxtLabel_s)){
		      				$qry7=mysqli_query($dbcon,"INSERT INTO vehicle_log_subtext(subtext_label,subtext_value,vlid,stand,cid) VALUES('$subtxtLabel_s','$subtxtvalue_s','$vlid','$stand','$cid')");
		      				if($qry7){
		      					$success++;
		      				}else{
		      					$error++;
		      				}
		      			}
		      	 	}
		      	}
		      	if(isset($_FILES["carimg"])){
		      		$uploadDir='../img/vehicle_uploads/';
					   $uploadedFile='';
					   $uploadOK=0;
		        	   for($i=0;$i<count($_FILES["carimg"]["name"]);$i++){
		        	   $uploaded_on=date("Y-m-d H:i:s");
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
			            	$qry=mysqli_query($dbcon,"INSERT INTO vehicle_log_image(uploaded_image,vlid,cid,uploaded_by,uploaded_on) VALUES('$uploadedFile','$vlid','$cid','$pid','$uploaded_on')");
			            	if($qry){
	                            $success++;
			            	}else{
	                            $error++;
			            	}
			            }
	        	      }
	            }
             }
		   }else{
		   	  $qry1='';
		   	  $vlid=0;
		   	  if($stand == 1){
			        $qry1=mysqli_query($dbcon,"INSERT INTO vehicle_log_protocol(hallmark,vehicle_type,car_name,kms,data,no_of_key,pid,created_id,stand,cid,eid,last_change) VALUES('$hallmark','$vtype','$carname','$kms','$data','$keys','$pid','$created_id','$stand','$cid','$eid','$last_change')");
			        $vlid=mysqli_insert_id($dbcon);
			        $qryreturnvehicle=mysqli_query($dbcon,"INSERT INTO vehicle_log_protocol(created_id,stand,eid,cid) VALUES('$created_id',2,'$eid','$cid')");
			        $employee_id_vl=$eid;
		   	  }else{
		   	  	  if($data == '1970-01-01 01:00:00'){
		   	  	  	  $response["status"]=0;
		   	  	  	  $response["message"]="Please Insert date";
		   	  	  }else{
	                 $qry1=mysqli_query($dbcon,"UPDATE vehicle_log_protocol SET hallmark='$hallmark',vehicle_type='$vtype',car_name='$carname',kms='$kms',data='$data',no_of_key='$keys',pid='$pid',stand='$stand',cid='$cid',eid='$eid',last_change='$last_change' WHERE created_id='$created_id' AND stand='$stand'");
	                 $updated_id=mysqli_query($dbcon,"SELECT id,created_id FROM vehicle_log_protocol WHERE created_id='$created_id' AND stand='$stand'");
	                 $updatedRow=mysqli_fetch_assoc($updated_id);
	                 $vlid=$updatedRow["id"]; 
	                 $employee_id_vl=0;
		   	  	  }
		   	  }

			      if($qry1){	
			      	$created_date=date("Y-m-d H:i:s");
			      	if(isset($_POST["mainLabel"])){
			      		$mainLabel=json_decode($_POST["mainLabel"]);
			      		$mainValue=json_decode($_POST["mainValue"]);
			      		for($i=0;$i < count($mainLabel);$i++){
			      			$mainLabel_s=safeInput($dbcon,$mainLabel[$i]);
			      			$mainValue_s=safeInput($dbcon,$mainValue[$i]);

			      			$qry9=mysqli_query($dbcon,"INSERT INTO vehicle_log_extra_field(label_name,label_value,vlid,cid,pid,stand,created_date) VALUES('$mainLabel_s','$mainValue_s','$vlid','$cid','$pid','$stand','$created_date')");
			      				if($qry9){
						      		$success++;
						      	}else{
						      		$error++;
						      	}
			      		}	
			      	}
			      	if(isset($_POST["checkboxLabel"])){
			      		$checkboxLabel=json_decode($_POST["checkboxLabel"]);
			      		for($i=0;$i < count($checkboxLabel);$i++){
			      			$checkboxlabel=safeInput($dbcon,$checkboxLabel[$i]);
			      			$checkboxvalue=safeInput($dbcon,$_POST[$checkboxLabel[$i]]);
			      			$qry2='';
                        if($stand == 1){
                        	$qry2=mysqli_query($dbcon,"INSERT INTO vehicle_log_checkbox_handover(label_name,label_value,cid,pid,vlid,created_date) VALUES('$checkboxlabel','$checkboxvalue','$cid','$pid','$vlid','$created_date')");
                        }else{
                        	$qry2=mysqli_query($dbcon,"INSERT INTO vehicle_log_checkbox_return(label_name,label_value,cid,pid,vlid,created_date) VALUES('$checkboxlabel','$checkboxvalue','$cid','$pid','$vlid','$created_date')");
                        }
			      			
		      				if($qry2){
					      		$success++;
					      	}else{
					      		$error++;
					      	}
			      		}		
			      	}
			      	$qry3=mysqli_query($dbcon,"INSERT INTO vehicle_log_checkbox2(private_use,log_book,home_office,stand,pid,cid,eid,vlid) VALUES('$puov','$lbm','$hoff','$stand','$pid','$cid','$eid','$vlid')");
			      	if($qry3){
			      		$success++;
			      	}else{
			      		$error++;
			      	}
			      	if(isset($_POST["checkbox2"])){
				      	for($i=0;$i < count($_POST["checkbox2"]);$i++){
				      		$label=safeInput($dbcon,$_POST["checkbox2"][$i]);
				      		$sel_value=$_POST["chckbox2_slct_val"][$i];
				      		$qry4=mysqli_query($dbcon,"INSERT INTO vehicle_log_checkbox2_extra(checkbox_label,checkbox_value,vlid,stand) VALUES('$label','$sel_value','$vlid','$stand')");
				      		if($qry4){
				      			$success++;
				      		}else{
				      			$error++;
				      		}
				      	}
			         }

			      	if(isset($_POST["signature_fleet_mngr"]) && !empty($_POST["signature_fleet_mngr"])){
			      		$signaturefm=$_POST["signature_fleet_mngr"];
			      	   $filenamefm="signfleet_mngr".date("Ymdhis").".png";
			            $uploadPathfm="../img/signature_uploads/";
			            file_put_contents($uploadPathfm.$filenamefm,file_get_contents($signaturefm));
			            if(file_exists($uploadPathfm.$filenamefm)){
			            	$qry5=mysqli_query($dbcon,"UPDATE vehicle_log_protocol SET sign_fleet_manager='$filenamefm',signed_by='$pid' WHERE id='$vlid'");
			            }else{
			            	$error++;
			            }	
			      	}
			      	if(isset($_POST["signature_vh_mngr"]) && !empty($_POST["signature_vh_mngr"])){
			      		$signature=$_POST["signature_vh_mngr"];
			      	   $filename="signvh_mngr".date("Ymdhis").".png";
			            $uploadPath="../img/signature_uploads/";
			            file_put_contents($uploadPath.$filename,file_get_contents($signature));
			            if(file_exists($uploadPath.$filename)){
			            	$qry6=mysqli_query($dbcon,"UPDATE vehicle_log_protocol SET sign_vehicle_manager='$filename' WHERE id='$vlid'");
			            }else{
			            	$error++;
			            }
			      	}
			      	if(isset($_POST["subtxtLabel"])){
			      		$subtextlabel=json_decode($_POST["subtxtLabel"]);
			      		$subtxtvalue=json_decode($_POST["subtxtvalue"]);
			      		for($i=0;$i < count($subtextlabel);$i++){
			      			$subtextlabel_s=safeInput($dbcon,$subtextlabel[$i]);
			      			$subtextvalue_s=safeInput($dbcon,$subtxtvalue[$i]);
			      			if(!empty($subtextlabel_s)){
			      				$qry7=mysqli_query($dbcon,"INSERT INTO vehicle_log_subtext(subtext_label,subtext_value,vlid,stand,cid) VALUES('$subtextlabel_s','$subtextvalue_s','$vlid','$stand','$cid')");
			      				if($qry7){
			      					$success++;
			      				}else{
			      					$error++;
			      				}
			      			}
			      		}
			      	}
			      	if(isset($_FILES["carimg"])){
			      		$uploadDir='../img/vehicle_uploads/';
						   $uploadedFile='';
						   $uploadOK=0;
			        	   for($i=0;$i<count($_FILES["carimg"]["name"]);$i++){
			        	   $uploaded_on=date("Y-m-d H:i:s");
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
				            	$qry=mysqli_query($dbcon,"INSERT INTO vehicle_log_image(uploaded_image,vlid,cid,uploaded_by,uploaded_on) VALUES('$uploadedFile','$vlid','$cid','$pid','$uploaded_on')");
				            	if($qry){
		                            $success++;
				            	}else{
		                            $error++;
				            	}
				            }
		        	      }
		            }
			      	if(isset($_POST["comLabel"])){
			      		$comLabel=json_decode($_POST["comLabel"]);
			      		$comText=json_decode($_POST["comText"]);
			      		for($i=0;$i < count($comLabel);$i++){
			      			$comLabel_s=safeInput($dbcon,$comLabel[$i]);
			      			$comText_s=safeInput($dbcon,$comText[$i]);
			      			if(!empty($comLabel_s)){
			      				$qry8=mysqli_query($dbcon,"INSERT INTO vehicle_log_comment(com_label,com_value,cid,vlid,pid,stand,created_date) VALUES('$comLabel_s','$comText_s','$cid','$vlid','$pid','$stand','$created_date')");
			      				if($qry8){
			      					$success++;
			      				}else{
			      					$error++;
			      				}
			      			}
			      		}
			      	}
			      }
			   }

	  		if($stand == '2'){
	  				 $qry_sign_st2=mysqli_query($dbcon,"SELECT data,sign_fleet_manager,sign_vehicle_manager FROM vehicle_log_protocol WHERE created_id='$created_id' AND stand='$stand'");
	  				 $qry_sign_st1=mysqli_query($dbcon,"SELECT data,sign_fleet_manager,sign_vehicle_manager FROM vehicle_log_protocol WHERE created_id='$created_id' AND stand='1'");
	  				 $arr_sign_st2=mysqli_fetch_assoc($qry_sign_st2);
	  				 $arr_sign_st1=mysqli_fetch_assoc($qry_sign_st1);
	  			if(empty($arr_sign_st1["sign_fleet_manager"]) || empty($arr_sign_st1["sign_vehicle_manager"]) || $arr_sign_st1["data"]=='0000-00-00 00:00:00' || empty($arr_sign_st2["sign_fleet_manager"]) || empty($arr_sign_st2["sign_vehicle_manager"]) || $arr_sign_st2["data"]=='0000-00-00 00:00:00'){
                     //checking sign and date of stand 1 and 2
                }else{
                	$qry_comment=mysqli_query($dbcon,"SELECT vlp.hallmark,vlc.com_label,vlc.com_value,vlc.created_date FROM vehicle_log_protocol vlp INNER JOIN vehicle_log_comment vlc ON vlp.id=vlc.vlid WHERE vlc.stand='2' AND vlc.vlid='$vlid'");
                	if(mysqli_num_rows($qry_comment) > 0){
                		while($arr_comment=mysqli_fetch_assoc($qry_comment)){
                			$vid=$arr_comment["hallmark"];
                			$label=$arr_comment["com_label"];
                			$value=$arr_comment["com_value"];
                			$created_date=$arr_comment["created_date"];

                			$qry_insert_comment=mysqli_query($dbcon,"INSERT INTO vehicle_list_comment(com_label,com_text,vid,cid,pid,created_date) VALUES('$label','$value','$vid','$cid','$pid','$created_date')");
                			if($qry_insert_comment){
                				$success++;
                			}else{
                				$error++;
                			}
                		}
                	}
                  
                	$qry_image=mysqli_query($dbcon,"SELECT vlp.hallmark,vli.* FROM vehicle_log_protocol vlp INNER JOIN vehicle_log_image vli ON vlp.id=vli.vlid WHERE vli.vlid='$vlid'");
                	if(mysqli_num_rows($qry_image) > 0){
                		while($arr_image=mysqli_fetch_assoc($qry_image)){
                			$vid=$arr_image["hallmark"];
                			$uploaded_image=$arr_image["uploaded_image"];
                			$qry_insert_image=mysqli_query($dbcon,"INSERT INTO vehicle_image(img,vid,cid) VALUES('$uploaded_image','$vid','$cid')");
                			if($qry_insert_image){
                				$success++;
                			}else{
                				$error++;
                			}
                		}
                	}    
                	$updateVhcleList=mysqli_query($dbcon, "UPDATE vehicle_list SET avaibility='$isAvailable',mileage_employee='$kms' WHERE id='$hallmark'");

                } 
	      	}else{
      			 $qry_sign_st2=mysqli_query($dbcon,"SELECT data,sign_fleet_manager,sign_vehicle_manager FROM vehicle_log_protocol WHERE created_id='$created_id' AND stand='$stand'");
  				 $qry_sign_st1=mysqli_query($dbcon,"SELECT data,sign_fleet_manager,sign_vehicle_manager,kms FROM vehicle_log_protocol WHERE created_id='$created_id' AND stand='2'");
  				 $arr_sign_st2=mysqli_fetch_assoc($qry_sign_st2);
  				 $arr_sign_st1=mysqli_fetch_assoc($qry_sign_st1);
  				 if(empty($arr_sign_st1["sign_fleet_manager"]) || empty($arr_sign_st1["sign_vehicle_manager"]) || $arr_sign_st1["data"] =='0000-00-00 00:00:00' || empty($arr_sign_st2["sign_fleet_manager"]) || empty($arr_sign_st2["sign_vehicle_manager"]) || $arr_sign_st2["data"] == '0000-00-00 00:00:00'){
  				 	$updateVhcleList=mysqli_query($dbcon,"UPDATE vehicle_list SET avaibility='$isAvailable',emp_vh_id='$employee_id_vl' WHERE id='$hallmark'");
  				 }else{
  				 	$mileage=$arr_sign_st1["kms"];
  				 	$updateVhcleList=mysqli_query($dbcon,"UPDATE vehicle_list SET avaibility='1',mileage_employee='$mileage',emp_vh_id='0' WHERE id='$hallmark'");
  				 }
	      		
	      	}
         }else{
			   	$response["message"]="Please fill all important fields";
			}
   }else{
   	$response["message"]="Please Select hallmark..!!!";
   }
   if($success >=1){
   	$response["status"]=1;
   	$response["hm"]=$hallmark;
   	$response["vlid"]=$vlid;
   	$response["message"]="Successfully Inserted Data. No of errors is ".$error;
   } 
	echo json_encode($response);
}
if(isset($_POST['deleteComment'])){
	$response=[
      "status"=>0,
      "message"=>"Oops Something Went Wrong"
	];
	$qry='';
	$com_id=$_POST["commentId"];
	if(isset($_POST["com_view"])){
	  $com_view=$_POST["com_view"];	
	  $qry=mysqli_query($dbcon,"DELETE FROM driver_license_comment WHERE id='$com_id' AND comment_view='$com_view'");
	}else{
	  $qry=mysqli_query($dbcon,"DELETE FROM vehicle_log_comment WHERE id='$com_id'");
	}
	if($qry){
		$response["status"]=1;
		$response["message"]="Successfully Deleted...";
	}else{
		$response["message"]="Oops Something Went Wrong";
	}
	echo json_encode($response);
}
if(isset($_POST['deleteCheckbox2'])){
	$response=[
      "status"=>0,
      "message"=>"Oops Something Went Wrong"
	];
	$delID=$_POST["delID"];
	$qry=mysqli_query($dbcon,"DELETE FROM vehicle_log_checkbox2_extra WHERE id='$delID'");
	if($qry){
		$response["status"]=1;
		$response["message"]="Successfully Deleted...";
	}else{
		$response["message"]="Oops Something Went Wrong";
	}
	echo json_encode($response);
}
if(isset($_POST['deletesubtext'])){
	$response=[
      "status"=>0,
      "message"=>"Oops Something Went Wrong"
	];
	$delID=$_POST["delID"];
	$qry=mysqli_query($dbcon,"DELETE FROM vehicle_log_subtext WHERE id='$delID'");
	if($qry){
		$response["status"]=1;
		$response["message"]="Successfully Deleted...";
	}else{
		$response["message"]="Oops Something Went Wrong";
	}
	echo json_encode($response);
}

if(isset($_POST["fetch_vehicle_list"])){
	$response=[];
	$vid=$_POST["vid"];
	$qry=mysqli_query($dbcon,"SELECT * FROM vehicle_list WHERE id='$vid'");
	if($qry){
		$arr=mysqli_fetch_assoc($qry);
			$output=[
	         "vehicle_type"=>$arr['vehicle_type'],
	         "mileage_original"=>($arr['mileage_employee'] == '' || $arr['mileage_employee']== 0) ? $arr['mileage_original'] : $arr['mileage_employee'],
	         "number_of_keys"=>$arr['number_of_keys'],
	         "car_name"=>$arr['car_name'],
	         "vehicle_checkbox"=>[],
	         "subtext"=>[],
	         "comments"=>[],
	         "vehicle_images"=>[]
		   ];
		   $qryCheckbox=mysqli_query($dbcon,"SELECT * FROM vehicle_list_checkbox WHERE vid='$vid'");
         if($qryCheckbox){
		   	while($arr_checkbox=mysqli_fetch_assoc($qryCheckbox)){
		   		$checkbox=[
                   "label_name"=>$arr_checkbox['label_name'],
                   "label_value"=>$arr_checkbox['label_value']
		   		];
		   		$output["vehicle_checkbox"][]=$checkbox;
		   	}
		   }
		   // $qryComment=mysqli_query($dbcon,"SELECT * FROM vehicle_list_comment WHERE vid='$vid'");
           // if($qryComment){
		   // 	while($arr_comment=mysqli_fetch_assoc($qryComment)){
		   // 		$comment=[
           //         "com_label"=>$arr_comment['com_label'],
           //         "com_text"=>$arr_comment['com_text']
		   // 		];
		   // 		$output["comments"][]=$comment;
		   // 	}
		   // }
		   $qrysubtext=mysqli_query($dbcon,"SELECT * FROM vehicle_list_subtext WHERE vid='$vid'");
		   if($qrysubtext){
		   	while($arr_subtext=mysqli_fetch_assoc($qrysubtext)){
		   		$subtextOutput=[
		   			 "textlabel"=>$arr_subtext['textlabel'],
                   "textvalue"=>$arr_subtext['textvalue']
		   		];
		   		$output["subtext"][]=$subtextOutput;
		   	}
		   }
		   $vehicle_image=mysqli_query($dbcon,"SELECT * FROM vehicle_image WHERE vid='$vid'");
		   if($vehicle_image){
		   	while($arr_vehicle_image=mysqli_fetch_assoc($vehicle_image)){
		   		$imgOutput=[
                   "vehicle_image"=>$arr_vehicle_image['img']
		   		];
		   		$output["vehicle_images"][]=$imgOutput;
		   	}
		   }
		   $response[]=$output;
 
		echo json_encode($response);
	}
}
if(isset($_POST["deletevehiclelogImage"])){
	$response=[
     "status"=>0,
     "message"=>"OOps Something went Wrong.."
	];
	$id=$_POST["imgId"];
	$qrySelect=mysqli_query($dbcon,"SELECT uploaded_image FROM vehicle_log_image WHERE id='$id'");
	if(mysqli_num_rows($qrySelect) > 0){
		$arr=mysqli_fetch_assoc($qrySelect);
		$image=$arr["uploaded_image"];
		if(unlink("../img/vehicle_uploads/".$image)){
			$qry=mysqli_query($dbcon,"DELETE FROM vehicle_log_image WHERE id='$id'");
			if($qry){
				$response["status"]=1;
				$response["message"]="successfully Deleted Images";
			}else{
				$response["message"]="OOps Something Went Wrong..!!!";
		   }
		}
	}
	echo json_encode($response);
}
if(isset($_POST["getPrintHandover"])){
	$table=$_POST["getPrintHandover"];
	$response=[];
	$id=$_POST["tcHandovedId"];
	$cid=base64_decode($_POST["cid"])/99999;
	$qry=mysqli_query($dbcon,"SELECT tch.*,vl.hallmark AS hallname,c.company_name,c.street,c.house_no AS chouseno,c.city AS company_city,c.zip_code,e.firstname,e.surname,e.house_no,e.street AS employee_street,e.city,e.zip,e.date_of_birth,e.phone_number,p.firstname AS admin_fname,p.surname AS admin_sname FROM ".$table." tch LEFT JOIN vehicle_list vl ON tch.hallmark=vl.id INNER JOIN company c ON tch.cid=c.id INNER JOIN employee e ON tch.eid=e.id LEFT JOIN personal_admin p ON tch.signed_by = p.id WHERE tch.id='$id' AND tch.cid='$cid'");
	if($qry){
       if(mysqli_num_rows($qry) > 0){
			$arr=mysqli_fetch_assoc($qry);
			$response=[
	          "id"=>$arr["id"],
	          "data_handover"=>date("d.m.Y",strtotime($arr["data_handover"])),
	          "signature_staff"=>$arr["signature_staff"],
	          "signed_by"=>($arr["signed_by"] == 0) ? "" : $arr["admin_fname"]." ".$arr["admin_sname"],
	          "signature_employee"=>$arr["signature_employee"],        
	          "hallname"=>(empty($arr["hallname"]) || $arr["hallname"] == "Keine") ? "": $arr["hallname"],
	          "text1"=>$arr["text1"],
	          "text2"=>$arr["text2"],
	          "print_heading"=>$arr["print_heading"],
	          "print_comment"=>$arr["print_comment"],
	          "company_name"=>$arr["company_name"],
	          "company_street"=>$arr["street"],
	          "company_house_no"=>$arr["chouseno"],
	          "company_city"=>$arr["company_city"],
	          "company_zip_code"=>$arr["zip_code"],
	          "firstname"=>$arr["firstname"],
	          "surname"=>$arr["surname"],
	          "empl_house_no"=>$arr["house_no"],
	          "empl_street"=>$arr["employee_street"],
	          "empl_city"=>$arr["city"],
	          "empl_zip"=>$arr["zip"],
	          "date_of_birth"=>date("d.m.Y",strtotime($arr["date_of_birth"])),
	          "phone_number"=>$arr["phone_number"]
			];
       }
	}
echo json_encode($response);
}
// if(isset($_POST["getTCPrintReturn"])){
if(isset($_POST["getPrintReturn"])){
$response=[];
   $table=$_POST["getPrintReturn"];
	$id=$_POST["tcReturnId"];
	$cid=base64_decode($_POST["cid"])/99999;
	$qry=mysqli_query($dbcon,"SELECT tcr.*,vl.hallmark AS hallname,c.company_name,c.street,c.house_no AS chouseno,c.city AS company_city,c.zip_code,e.firstname,e.surname,e.house_no,e.street AS employee_street,e.city,e.zip,e.date_of_birth,e.phone_number,p.firstname AS admin_fname,p.surname AS admin_sname FROM ".$table." tcr LEFT JOIN vehicle_list vl ON tcr.hallmark=vl.id INNER JOIN company c ON tcr.cid=c.id INNER JOIN employee e ON tcr.eid=e.id LEFT JOIN personal_admin p ON tcr.signed_by = p.id  WHERE tcr.id='$id' AND tcr.cid='$cid'");
	if($qry){
		$arr=mysqli_fetch_assoc($qry);
		$response=[
          "id"=>$arr["id"],
          "data_return"=>date("d.m.Y",strtotime($arr["data_return"])),
          "signature_staff"=>$arr["signature_staff"],
          "signed_by"=>($arr["signed_by"] == 0) ? "" : $arr["admin_fname"]." ".$arr["admin_sname"],
          "signature_employee"=>$arr["signature_employee"],        
          "hallname"=>$arr["hallname"],
          "text1"=>$arr["text1"],
          "text2"=>$arr["text2"],
          "print_heading"=>$arr["print_heading"],
          "print_comment"=>$arr["print_comment"],
          "company_name"=>$arr["company_name"],
          "company_street"=>$arr["street"],
          "company_house_no"=>$arr["chouseno"],
          "company_city"=>$arr["company_city"],
          "company_zip_code"=>$arr["zip_code"],
          "firstname"=>$arr["firstname"],
          "surname"=>$arr["surname"],
          "empl_house_no"=>$arr["house_no"],
          "empl_street"=>$arr["employee_street"],
          "empl_city"=>$arr["city"],
          "empl_zip"=>$arr["zip"],
          "date_of_birth"=>date("d.m.Y",strtotime($arr["date_of_birth"])),
          "phone_number"=>$arr["phone_number"]
		];
	}
  echo json_encode($response);	
}

if(isset($_POST["savePrint"])){
	$response=[
    "status"=>0,
    "message"=>"Oops Something went wrong"
	];
	$table=$_POST["savePrint"];
	$id=$_POST["id"];
   $print_heading=safeInput($dbcon,$_POST["print_heading"]);
   $print_comment=safeInput($dbcon,$_POST["print_comment"]);
	$qry=mysqli_query($dbcon,"UPDATE ".$table." SET print_heading='$print_heading',print_comment='$print_comment' WHERE id='$id'");
	if($qry){
     $response["message"]="Successfully Saved..";
     $response["status"]=1;
	}else{
		$response["message"]="something Went wrong";
	}
  echo json_encode($response);
}
if(isset($_POST["getPrintEquipment"])){
	$response=[];
	$eid=base64_decode($_POST["eid"])/77777;
	$cid=base64_decode($_POST["cid"])/99999;
	$qry1=mysqli_query($dbcon,"SELECT wei.*,c.company_name,c.street,c.zip_code,c.city,c.house_no,e.surname,e.firstname,e.street AS emp_street,e.city AS emp_city,e.house_no AS emp_house_no,e.zip AS emp_zip_code FROM work_equipment_issue wei LEFT JOIN company c ON wei.cid=c.id LEFT JOIN employee e ON wei.eid=e.id WHERE wei.cid='$cid' AND wei.eid='$eid'"); 
	if($qry1){
		if(mysqli_num_rows($qry1) > 0){
         $arr1=mysqli_fetch_assoc($qry1);
			$output1=[
	         "company_name"=>$arr1["company_name"],
	         "company_street"=>$arr1["street"],
	         "company_zip_code"=>$arr1["zip_code"],
	         "company_city"=>$arr1["city"],
	         "company_house_no"=>$arr1["house_no"],
	         "emp_surname"=>$arr1["surname"],
	         "emp_firstname"=>$arr1["firstname"],
	         "emp_street"=>$arr1["emp_street"],
	         "emp_city"=>$arr1["emp_city"],
	         "emp_house_no"=>$arr1["emp_house_no"],
	         "emp_zip_code"=>$arr1["emp_zip_code"],
	         "print_heading"=>$arr1["print_heading"],
	         "print_comment"=>$arr1["print_comment"],
	         "equipment_issue"=>[],
	         "equipment_return"=>[]
			];
	      $qry2=mysqli_query($dbcon,"SELECT wei.*,i.article AS article_name,p.firstname,p.surname FROM work_equipment_issue wei LEFT JOIN inventory i ON wei.article=i.id LEFT JOIN personal_admin p ON wei.pid=p.id WHERE wei.cid='$cid' AND wei.eid='$eid'");
	      if($qry2){
	      	while($arr2=mysqli_fetch_assoc($qry2)){
	      		$output2=[
	               "id"=>$arr2["id"],
	               "article_name"=>$arr2["article_name"],
	               "selected_crowd"=>$arr2["selected_crowd"],
	               "data_issue"=>date("d.m.Y | H:i",strtotime($arr2["data"])),
	               "staff_signature"=>$arr2["staff_signature"],
	               "signed_by"=>$arr2["firstname"]." ".$arr2["surname"],
	               "employee_signature"=>$arr2["employee_signature"],
	      		];

	      		$output1["equipment_issue"][]=$output2;
	      	}
	      }
	      $qry3=mysqli_query($dbcon,"SELECT wer.*,i.article AS article_name,p.firstname,p.surname FROM work_equipment_return wer LEFT JOIN inventory i ON wer.article=i.id LEFT JOIN personal_admin p ON wer.pid=p.id  WHERE wer.cid='$cid' AND wer.eid='$eid'");
	      if($qry3){
	      	while($arr3=mysqli_fetch_assoc($qry3)){
	      		$output3=[
	               "id"=>$arr3["id"],
	               "article_name"=>$arr3["article_name"],
	               "article_crowd"=>$arr3["article_crowd"],
	               "data_return"=>date("d.m.Y | H:i",strtotime($arr3["data_return"])),
	               "lost"=>$arr3["lost"],
	               "staff_signature"=>$arr3["staff_signature"],
	               "signed_by"=>$arr3["firstname"]." ".$arr3["surname"],
	               "employee_signature"=>$arr3["employee_signature"],
	      		];
	      		$output1["equipment_return"][]=$output3;
	      	}
	      }
	      $response[]=$output1;
		}
	}
	echo json_encode($response);
}
if(isset($_POST["saveWorkEquipmentPrint"])){
	$response=[
		"status"=>0,
		"message"=>"Oops Something Went Wrong.."
	];
	$text=safeInput($dbcon,$_POST["text"]);
	$comment=safeInput($dbcon,$_POST["comment"]);
	$cid=base64_decode($_POST["cid"])/99999;
	$eid=base64_decode($_POST["eid"])/77777;
	$qry=mysqli_query($dbcon,"UPDATE work_equipment_issue SET print_heading='$text',print_comment='$comment' WHERE cid='$cid' AND eid='$eid'");
	if($qry){
		$response["status"]=1;
		$response["message"]="Successully Update Print text";
	}else{
		$response["message"]="Oops Something Went Wrong..!!";
	}
	echo json_encode($response);
}
if(isset($_POST["driver_license"])){
$response=[
   "status"=>0,
   "message"=>"Oops Something Went Wrong..!!",
   "eid"=>$_POST["eid"],
   "dlid"=>''
];
	$pid=base64_decode($_POST["pid"])/88888;
	$cid=base64_decode($_POST["cid"])/99999;
	$eid=base64_decode($_POST["eid"])/77777;
    $success=$error=0;
    $created_date=date("Y-m-d H:i:s");
    $dlid=0;
    $qryMain='';
    $label1=$_POST["label1"];
    $nameField=$_POST["nameField"];
    $dateField=$_POST["dateField"];
    $label2=$_POST["label2"];
    $checkbox2=$_POST["checkbox2"];
    $success=$error=0;
    if(isset($_POST["dlid"])){
    	$dlid=base64_decode($_POST['dlid']);
        $qryMain=mysqli_query($dbcon,"UPDATE driver_license SET main_label='$label1',main_text='$nameField',main_data='$dateField',main_checkbox='$label2',checkbox_value='$checkbox2',update_by='$pid',updated_at='$created_date' WHERE id='$dlid'");
    }else{
        $qryMain=mysqli_query($dbcon,"INSERT INTO driver_license(main_label,main_text,main_data,main_checkbox,checkbox_value,print_head,print_para,print_desc,pid,cid,eid,update_by,created_at,updated_at) VALUES('$label1','$nameField','$dateField','$label2','$checkbox2','Führerscheinkontrolle','2.) Angaben zum Führerschein / zur Fahrerlaubnis','contain various details like the vehicle identification number (VIN), date, time, etc. There is various information that can be mentioned in the handover check form like engine condition, lights, tires, windows, airbags, gear, brakes, etc. Just add your logo, change the color scheme, or add a background image to match your brand.','$pid','$cid','$eid','$pid','$created_date','$created_date')");
        if($qryMain){
        	$dlid=mysqli_insert_id($dbcon);
        }else{
        	$response["message"]="Sorry Something went wrong in data insertion";
        }
    }
    if(isset($_POST["lab"])){
    	$label=json_decode($_POST["lab"]);
    	$icon=json_decode($_POST["ic"]);
    	$view=json_decode($_POST["vi"]);
    	$checboxIcon=json_decode($_POST["cbi"]);
    	$sort_by=json_decode($_POST["sort_by"]);
    	$qry=mysqli_query($dbcon,"DELETE FROM driver_license_icon_checkbox WHERE dlid='$dlid' AND $eid='$eid' AND cid='$cid'");
    	$maxSelect=mysqli_query($dbcon,"SELECT MAX(sort_by) AS sortng FROM driver_license_icon_checkbox");
    	$maxSort=0;
    	if(mysqli_num_rows($maxSelect) > 0){
    		$maxArr=mysqli_fetch_assoc($maxSelect);
            $maxSort=$maxArr["sortng"];
    	}
        for($i=0;$i<count($label);$i++){
        	$sort=($maxSort == 0) ? $i + 1 : $maxSort + $i;
        	$label_s=safeInput($dbcon,$label[$i]);
        	$icon_s=$icon[$i];
        	$view_s=$view[$i];
        	$sort_by_s=$sort_by[$i];
        	$checboxIcon_s=$checboxIcon[$i];
        	if($sort_by_s == 0){
				$qry=mysqli_query($dbcon,"INSERT INTO driver_license_icon_checkbox(dlid,label,view,chbx,icon,eid,cid,sort_by,created_at) VALUES('$dlid','$label_s','$view_s','$checboxIcon_s','$icon_s','$eid','$cid','$sort','$created_date')");
        	}else{
               $qry=mysqli_query($dbcon,"INSERT INTO driver_license_icon_checkbox(dlid,label,view,chbx,icon,eid,cid,sort_by,created_at) VALUES('$dlid','$label_s','$view_s','$checboxIcon_s','$icon_s','$eid','$cid','$sort_by_s','$created_date')");
        	}  	
        	if($qry){
        		$success++;
        	}else{
        		$error++;
        	}
        }	
    }
	if(isset($_POST["signature_fleet_mngr"]) && !empty($_POST["signature_fleet_mngr"])){
		$signaturefm=$_POST["signature_fleet_mngr"];
		$filenamefm="signfleet_mngr".date("Ymdhis").".png";
	    $uploadPathfm="../img/signature_uploads/";
	    file_put_contents($uploadPathfm.$filenamefm,file_get_contents($signaturefm));
	    if(file_exists($uploadPathfm.$filenamefm)){
	    	$signd_date=date("d.m.Y | H:i");
	    	$qryImg1=mysqli_query($dbcon,"UPDATE driver_license SET signature_fleet_manager='$filenamefm',signed_by='$pid',signed_date='$signd_date' WHERE id='$dlid'");
	    }else{
	    	$error++;
	    }	
	 }
	 if(isset($_POST["signature_vh_user"]) && !empty($_POST["signature_vh_user"])){
		$signature=$_POST["signature_vh_user"];
		$filename="signvh_mngr".date("Ymdhis").".png";
	    $uploadPath="../img/signature_uploads/";
	    file_put_contents($uploadPath.$filename,file_get_contents($signature));
	    if(file_exists($uploadPath.$filename)){
	    	$signd_date=date("d.m.Y | H:i");
	    	$qryImg2=mysqli_query($dbcon,"UPDATE driver_license SET signature_vehicle_user='$filename',signed_date='$signd_date' WHERE id='$dlid'");
	    }else{
	    	$error++;
	    }
	}
	if(isset($_POST["cb_label"])){
		$label=json_decode($_POST["cb_label"]);
		$textname=json_decode($_POST["cb_lvalue"]);
	    $chbx_yes=json_decode($_POST["cb_chbx_yes"]);
	    $chbx_no=json_decode($_POST["cb_chbx_no"]);
	    $showAll=json_decode($_POST["cb_show"]);
        $qryDeletePrevious=mysqli_query($dbcon,"DELETE FROM driver_license_checkbox WHERE dlid='$dlid'");
		for($i=0;$i < count($label);$i++){
			$label_s=safeInput($dbcon,$label[$i]);
			$textname_s=safeInput($dbcon,$textname[$i]);
			$chbx__yes_s=$chbx_yes[$i];
		    $chbx_no_s=$chbx_no[$i];
		    $showAll_s=$showAll[$i];
	    	$qry=mysqli_query($dbcon,"INSERT INTO driver_license_checkbox(dlid,textname,labelname,chbx_yes,chbx_no,view,eid,cid,created_date) VALUES('$dlid','$textname_s','$label_s','$chbx__yes_s','$chbx_no_s','$showAll_s','$eid','$cid','$created_date')");
	    	if($qry){
	    		$success++;
	    	}
		}
	}
	if(isset($_POST["cl1"])){
		$cl1=json_decode($_POST["cl1"]);
		$ct1=json_decode($_POST["ct1"]);
		$qryDelete=mysqli_query($dbcon,"DELETE FROM driver_license_comment WHERE dlid='$dlid' AND comment_view='top'");
		if($qryDelete){
			for($i=0;$i < count($cl1);$i++){
				$cl1_s=$cl1[$i];
				$ct1_s=$ct1[$i];
                $qry=mysqli_query($dbcon,"INSERT INTO driver_license_comment(dlid,comment_view,comLabel1,comm_value,eid,cid,created_date) VALUES('$dlid','top','$cl1_s','$ct1_s','$eid','$cid','$created_date')");
                if($qry){
                	$success++;
                }
			}
		}
	}
	if(isset($_POST["cl2"])){
		$cl2=json_decode($_POST["cl2"]);
		$ct2=json_decode($_POST["ct2"]);
		$qryDelete=mysqli_query($dbcon,"DELETE FROM driver_license_comment WHERE dlid='$dlid' AND comment_view='bottom'");
		if($qryDelete){
			for($i=0;$i < count($cl2);$i++){
				$cl2_s=$cl2[$i];
				$ct2_s=$ct2[$i];
                $qry=mysqli_query($dbcon,"INSERT INTO driver_license_comment(dlid,comment_view,comLabel1,comm_value,eid,cid,created_date) VALUES('$dlid','bottom','$cl2_s','$ct2_s','$eid','$cid','$created_date')");
                if($qry){
                	$success++;
                }
			}
		}
	}
	if(isset($_FILES["DLimg"])){
  		$uploadDir='../img/DL_uploads/';
		$uploadedFile='';
		$uploadOK=0;
    	for($i=0;$i<count($_FILES["DLimg"]["name"]);$i++){
    	   $uploaded_on=date("Y-m-d H:i:s");
    		$fileName = basename($_FILES["DLimg"]["name"][$i]);
            $fileName=rand(1000000000,9999999999)."_".$fileName;  
            $targetFilePath = $uploadDir . $fileName; 
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
            $allowTypes = array('jpg', 'png', 'jpeg','webp'); 
            if(in_array($fileType, $allowTypes)){ 
                 if($_FILES["DLimg"]["size"][$i] > 5000000 || $_FILES["DLimg"]["size"][$i] < 1000){
                    $response['message'] = 'Max upload size 600kb min upload Size 1kb'; 
                 }else{
                    if(move_uploaded_file($_FILES["DLimg"]["tmp_name"][$i], $targetFilePath)){ 
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
            	$qry=mysqli_query($dbcon,"INSERT INTO driver_license_image(dlid,license_image,cid,eid,uploaded_by,uploaded_on) VALUES('$dlid','$uploadedFile','$cid','$eid','$pid','$created_date')");
            	if($qry){
                    $success++;
            	}else{
                    $error++;
            	}
            }
	    }
    }
    if($success > 0){
    	$response["status"]=1;
    	$response["dlid"]=base64_encode($dlid);
    	$response["message"]="Successfully Saved Data..Error Found=".$error;
    }else{
    	$response["message"]="Oops Something Went wrong..";
    }
    echo json_encode($response);
}
if(isset($_POST["deleteIcon"])){
    $response=[
      "status"=>0,
      "message"=>"Oops Something went wrong"
    ];
	$id=$_POST["id"];
	$qry=mysqli_query($dbcon,"UPDATE driver_license_icon_checkbox SET icon='' WHERE id='$id'");
	if($qry){
		$response["message"]="Successfully Deleted icon";
		$response["status"]=1;
	}else{
		$response["message"]="Oops Something went wrong";
	}
	echo json_encode($response);
}
if(isset($_POST["deleteDLImage"])){
	$response=[
     "status"=>0,
     "message"=>"Oops Something went Wrong.."
	];
	$id=$_POST["imgId"];
	$qrySelect=mysqli_query($dbcon,"SELECT license_image FROM driver_license_image WHERE id='$id'");
	if(mysqli_num_rows($qrySelect) > 0){
		$arr=mysqli_fetch_assoc($qrySelect);
		$image=$arr["license_image"];
		if(unlink("../img/DL_uploads/".$image)){
			$qry=mysqli_query($dbcon,"DELETE FROM driver_license_image WHERE id='$id'");
			if($qry){
				$response["status"]=1;
				$response["message"]="successfully Deleted Images";
			}else{
				$response["message"]="OOps Something Went Wrong..!!!";
		   }
		}
	}
	echo json_encode($response);
}

if(isset($_POST['saveDLprint'])){
	$response=[
     "status"=>0,
     "message"=>"Something went wrong..!!!"
	];
    $head=safeInput($dbcon,$_POST['head']);
    $para=safeInput($dbcon,$_POST['para']);
    $desc=safeInput($dbcon,$_POST['desc']);
    $dlid=base64_decode($_POST['dlid']);
	$qry=mysqli_query($dbcon,"UPDATE driver_license SET print_head='$head',print_para='$para',print_desc='$desc' WHERE id='$dlid'");
	if($qry){
		$response['status']=1;
		$response['message']="Successfully Saved...";
	}else{
		$response['message']="Sorry there is a problem while updating print text..";
	}
	echo json_encode($response);
}
if(isset($_POST["transportCompany"])){
	$response=[
       "status"=>0,
       "message"=>"Oops Something went wrong..!!!!"
	];
	$cid=base64_decode($_POST['company'])/99999;
	$eid=base64_decode($_POST['eid'])/77777;
	if(getEquipment($dbcon,$eid) == 'Ja' || gettankChip($dbcon,$eid) == 'Ja' || getUTAfuelcard($dbcon,$eid) == 'Ja' || getvehicleLog($dbcon,$eid) == 'Ja'){
        $response["message"]="You cannot transport employee data...";
	}else{
		$qry1=mysqli_query($dbcon,"UPDATE employee SET cid='$cid' WHERE id='$eid'");
		if($qry1){

			$qry2=mysqli_query($dbcon,"UPDATE driver_license SET cid='$cid' WHERE eid='$eid'");
		   
			$qry3=mysqli_query($dbcon,"UPDATE driver_license_icon_checkbox SET cid='$cid' WHERE eid='$eid'");
			$qry4=mysqli_query($dbcon,"UPDATE driver_license_checkbox SET cid='$cid' WHERE eid='$eid'");
			$qry5=mysqli_query($dbcon,"UPDATE driver_license_comment SET cid='$cid' WHERE eid='$eid'");
			$qry6=mysqli_query($dbcon,"UPDATE driver_license_image SET cid='$cid' WHERE eid='$eid'");
    		$response["status"]=1;
    		$response["message"]="successfully Transported...";
		}
	} 
	echo json_encode($response);
}
if(isset($_POST["getCompany"])){
	$response=[];
	$table=$_POST["getCompany"];
	$cid=base64_decode($_POST["cid"])/99999;
	$eid=base64_decode($_POST["eid"])/77777;

	$qry=mysqli_query($dbcon,"SELECT t.cid,c.id,c.company_name FROM ".$table." t INNER JOIN company c ON t.cid=c.id WHERE t.cid <> '$cid' AND t.eid='$eid' GROUP BY t.cid");
	if($qry){
		$rows=mysqli_num_rows($qry);
		if($rows > 0){
			while($arr=mysqli_fetch_assoc($qry)){
               $output=[
               	  "company_id"=>base64_encode($arr["id"]*99999),
                  "company_name"=>$arr["company_name"]
               ];
               $response[]=$output;
			}
		}else{
          $response[]='empty';
		}
	}
	echo json_encode($response);
}
if(isset($_POST["getCompanyVL"])){
	$response=[];
	$table=$_POST["getCompanyVL"];
	$cid=base64_decode($_POST["cid"])/99999;
	$eid=base64_decode($_POST["eid"])/77777;

	$qry=mysqli_query($dbcon,"SELECT t.cid,c.id,c.company_name FROM ".$table." t INNER JOIN company c ON t.cid=c.id WHERE t.cid <> '$cid' AND t.eid='$eid' GROUP BY t.cid");
	if($qry){
		$rows=mysqli_num_rows($qry);
		if($rows > 0){
			while($arr=mysqli_fetch_assoc($qry)){
               $output=[
               	  "company_id"=>base64_encode($arr["id"]*99999),
                  "company_name"=>$arr["company_name"]
               ];
               $response[]=$output;
			}
		}else{
          $response[]='empty';
		}
	}
	echo json_encode($response);
}
if(isset($_POST["getVLOGCompany"])){
	$response=[];
	$cid=base64_decode($_POST["cid"])/99999;
	$eid=base64_decode($_POST["eid"])/77777;

	$qry=mysqli_query($dbcon,"SELECT vlp.cid,c.id,c.company_name FROM vehicle_log_protocol vlp INNER JOIN company c ON vlp.cid=c.id WHERE vlp.cid <> '$cid' AND vlp.eid='$eid' GROUP BY vlp.cid");
	if($qry){
		$rows=mysqli_num_rows($qry);
		if($rows > 0){
			while($arr=mysqli_fetch_assoc($qry)){
               $output=[
               	  "company_id"=>base64_encode($arr["id"]*99999),
                  "company_name"=>$arr["company_name"]
               ];
               $response[]=$output;
			}
		}else{
          $response[]='empty';
		}
	}
	echo json_encode($response);
}

// if(isset($_POST["getTCCompany"])){
// 	$response=[]; 
// 	$cid=base64_decode($_POST["cid"])/99999;
// 	$eid=base64_decode($_POST["eid"])/77777;

// 	$qry=mysqli_query($dbcon,"SELECT w.cid,c.id,c.company_name FROM tank_chip_handover tch INNER JOIN company c ON w.cid=c.id WHERE cid <> '$cid' AND eid='$eid' GROUP BY cid");
// 	if($qry){
// 		$rows=mysqli_num_rows($qry);
// 		if($rows > 0){
// 			while($arr=mysqli_fetch_assoc($qry)){
//                $output=[
//                	  "company_id"=>base64_encode($arr["id"]*99999),
//                   "company_name"=>$arr["company_name"]
//                ];
//                $response[]=$output;
// 			}
// 		}else{
//           $response[]='empty';
// 		}
// 	}
// 	echo json_encode($response);
// }

