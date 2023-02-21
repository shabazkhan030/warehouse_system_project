<?php 
// include "../dbcon/dbcon.php";
function safeInput($dbcon,$var){
  $str=mysqli_escape_string($dbcon,$var);
  $trim = trim($str);
  $ss = stripslashes($trim);
  $data = htmlspecialchars($ss);
   return filter_var($data,FILTER_SANITIZE_STRING);
}
function DateFormat($date){
  $d=explode(".",$date);
  return $d[2]."-".$d[1]."-".$d[0];
}

function PasswordHash($password){
    // $password = hash('sha512',$password);
    return password_hash($password, PASSWORD_DEFAULT);
}
function PasswordVerify($password){
  // $verify = hash('sha512',$password);
  return password_verify($password,$verify);  
 }
// $hashed_password = hash('sha512', $password);
// ========================================================
// ======================MYSQL FUNCTION====================
// ========================================================

function getLastInsertedID($dbcon,$tablename,$data){
  $sql='SELECT '.$data.' FROM '.$tablename.' ORDER BY id DESC';
  $qry=mysqli_query($dbcon,$sql);
  if($qry){
    if(mysqli_num_rows($qry) > 0){
        $arr=mysqli_fetch_assoc($qry);
        return $arr['id'];
    }else{
      return 0;
    }
  }
}
function updatedRowEmployee($dbcon,$eid,$cid,$pid,$prevROW,$updateROW){
  $UpdatedRows=array_diff_assoc($updateROW,$prevROW);
  $upArr=$UpdatedRows;
  $PreviousRows=array_diff_assoc($prevROW,$updateROW);
  $prevArr=$PreviousRows;
  $created_on = array_pop($upArr);
  array_pop($prevArr);
  $count=0;
      foreach($upArr as $key => $val){
           $qry=mysqli_query($dbcon,"INSERT INTO history_employee(position,befora,afte,pid,cid,eid,updated_on) VALUES('$key','$prevArr[$key]','$val','$pid','$cid','$eid','$created_on')");
           if($qry){
               $count++;
           }
      }
    return $count;
}
function updatedRowCompany($dbcon,$cid,$pid,$prevROW,$updateROW){
  $UpdatedRows=array_diff_assoc($updateROW,$prevROW);
  $PreviousRows=array_diff_assoc($prevROW,$updateROW);
  $created_on = date("Y-m-d");
  $count=0;
      foreach($UpdatedRows as $key => $val){
           $qry=mysqli_query($dbcon,"INSERT INTO history_company(position,befora,afte,pid,cid,updated_on) VALUES('$key','$PreviousRows[$key]','$val','$pid','$cid','$created_on')");
           if($qry){
               $count++;
           }
      }
   return $count;
}

function updatedRowVehicleList($dbcon,$cid,$pid,$vid,$prevROW,$updateROW){
  $UpdatedRows=array_diff_assoc($updateROW,$prevROW);
  $upArr=$UpdatedRows;
  $PreviousRows=array_diff_assoc($prevROW,$updateROW);
  $prevArr=$PreviousRows;
  $created_on = date("Y-m-d H:i:s");
  array_pop($prevArr);
  array_pop($upArr);
  $count=0;
      foreach($upArr as $key => $val){
           $qry=mysqli_query($dbcon,"INSERT INTO history_vehicle(position,befora,afte,pid,cid,vid,update_on) VALUES('$key','$prevArr[$key]','$val','$pid','$cid','$vid','$created_on')");
           if($qry){
               $count++;
           }
      }
   return $count;
}

function fillpersonalAdmin($dbcon,$cid){
    $output='<option value="0">Select Employee</option>';
  $qry=mysqli_query($dbcon,"SELECT id,firstname,surname FROM personal_admin");
  if($qry){
    $rows=mysqli_num_rows($qry);
    if($rows > 0){
      while($arr=mysqli_fetch_assoc($qry)){
             $output.='<option value='.$arr["id"].'>'.$arr["firstname"].' '.$arr["surname"].'</option>';
          } 
    }else{

    }
  }
  return $output;
}
function updatedRowPersonalAdmin($dbcon,$aid,$pid,$prevROW,$updateROW){
  $UpdatedRows=array_diff_assoc($updateROW,$prevROW);
  $PreviousRows=array_diff_assoc($prevROW,$updateROW);
  $created_on = date("Y-m-d");
  $count=0;
      foreach($UpdatedRows as $key => $val){
           $qry=mysqli_query($dbcon,"INSERT INTO history_personal_admin(aid,position,befora,afte,updated_by,updated_on) VALUES('$aid','$key','$PreviousRows[$key]','$val','$pid','$created_on')");
           if($qry){
               $count++;
           }
      }
   return $count;
}
function updatedRowinventory($dbcon,$iid,$cid,$pid,$prevROW,$updateROW){
  if(count(array_diff_assoc($updateROW,$prevROW)) > 0){
     $created_on =date("Y-m-d");
     $qry=mysqli_query($dbcon,"SELECT * FROM inventory WHERE id='$iid'");
     if($qry){
        $arr=mysqli_fetch_assoc($qry);
        $crowd=$arr["crowd"];
        $remaining=$arr["remaining_item"];
        $article=$arr["article"];

        $qryInsert=mysqli_query($dbcon,"INSERT INTO history_inventory(article_name,crowd,remaining,data,pid,cid,iid) VALUES('$article','$crowd','$remaining','$created_on','$pid','$cid','$iid')");
         if($qryInsert){
             return true;
         }else{
             return false;
         }
     }
  }

}
function getEmplName($dbcon,$eid){
  $employee_name='';
  $qry=mysqli_query($dbcon,"SELECT surname,firstname FROM employee WHERE id='$eid'");
  if($qry){
    $row=mysqli_num_rows($qry);
    if($row > 0){
      $arr=mysqli_fetch_assoc($qry);
      $employee_name=$arr["firstname"]." ".$arr["surname"];
    }else{
     $employee_name="Unknown Employee";
    }
  }
  return $employee_name;
}
function getPrintEmplName($dbcon,$pid){
  $employee_name='';
  $qry=mysqli_query($dbcon,"SELECT surname,firstname FROM personal_admin WHERE id='$pid'");
  if($qry){
    $row=mysqli_num_rows($qry);
    if($row > 0){
      $arr=mysqli_fetch_assoc($qry);
      $employee_name=$arr["firstname"]." ".$arr["surname"];
    }else{
     $employee_name="Unknown Employee";
    }
  }
  return $employee_name;
}

function getLastChangeEmplName($dbcon,$id){
  $employee_name='';
  $qry=mysqli_query($dbcon,"SELECT surname,firstname FROM personal_admin WHERE id='$id'");
  if($qry){
    $row=mysqli_num_rows($qry);
    if($row > 0){
      $arr=mysqli_fetch_assoc($qry);
      $employee_name=$arr["firstname"]." ".$arr["surname"];
    }else{
     $employee_name="Unknown Employee";
    }
  }
  return $employee_name;
}

function getLastChangeDate($dbcon,$id){
  $date='';
  $qry=mysqli_query($dbcon,"SELECT last_change FROM office_order WHERE id='$id'");
  if($qry){
    $row=mysqli_num_rows($qry);
    if($row > 0){
      $arr=mysqli_fetch_assoc($qry);
      $date=date("d.m.Y | H:i",strtotime($arr["last_change"]));
    }else{
     $date="Unknown";
    }
  }
  return $date;
}

function lastchangedate($dbcon,$table,$cid,$eid){
  $date='';
  $handover='';
  $return='';
  if($table== "uta_fuel_card"){
    $handover="uta_fuel_card_handover";
    $return="uta_fuel_card_return";
  }else if($table== "work_equipment"){
    $handover="work_equipment_issue";
    $return="work_equipment_return"; 
  }else{
    $handover="tank_chip_handover";
    $return="tank_chip_return";
  }
  $qry1=mysqli_query($dbcon,"SELECT created_datetime FROM ".$handover." WHERE cid='$cid' AND eid='$eid' ORDER BY id DESC");
  $qry2=mysqli_query($dbcon,"SELECT created_datetime FROM ".$return." WHERE cid='$cid' AND eid='$eid' ORDER BY id DESC");
  if($qry1 || $qry2){
    $row1=mysqli_num_rows($qry1);
    $row2=mysqli_num_rows($qry2);
    if($row1 > 0 || $row2 > 0){
      $date1=0;
      $date2=0;
      if($row1 > 0){
        $arr1=mysqli_fetch_assoc($qry1);
        $date1=(date("dmYHis",strtotime($arr1["created_datetime"])) != null) ? date("dmYHis",strtotime($arr1["created_datetime"])) : 0;
      }
      if($row2 > 0){
        $arr2=mysqli_fetch_assoc($qry2);
        $date2=(date("dmYHis",strtotime($arr2["created_datetime"])) != null) ? date("dmYHis",strtotime($arr2["created_datetime"])) : 0;
      }
      $date=($date1 > $date2) ? date("d.m.Y | H:i",strtotime($arr1["created_datetime"])) : date("d.m.Y | H:i",strtotime($arr2["created_datetime"]));
    }else{
     $date="Unknown";
    }
  }
  return $date;
}
function lastchangeby($dbcon,$tablename,$cid,$eid)
{
  $name='';
  $handover='';
  $return='';
  if($tablename== "uta_fuel_card_handover"){
    $handover="uta_fuel_card_handover";
    $return="uta_fuel_card_return";
  }else if($tablename== "work_equipment"){
    $handover="work_equipment_issue";
    $return="work_equipment_return"; 
  }else{
    $handover="tank_chip_handover";
    $return="tank_chip_return";
  }
   $qry1=mysqli_query($dbcon,"SELECT a.*,p.firstname,p.surname FROM $handover a INNER JOIN personal_admin p ON a.pid=p.id WHERE a.cid='$cid' AND a.eid='$eid' ORDER BY created_datetime DESC");
   $qry2=mysqli_query($dbcon,"SELECT a.*,p.firstname,p.surname FROM $return a INNER JOIN personal_admin p ON a.pid=p.id WHERE a.cid='$cid' AND a.eid='$eid' ORDER BY created_datetime DESC");

    if($qry1 || $qry2){
    $row1=mysqli_num_rows($qry1);
    $row2=mysqli_num_rows($qry2);
    if($row1 > 0 || $row2 > 0){
      $date1=0;
      $date2=0;
      if($row1 > 0){
        $arr1=mysqli_fetch_assoc($qry1);
        $date1=(date("dmYHis",strtotime($arr1["created_datetime"])) != null) ? date("dmYHis",strtotime($arr1["created_datetime"])) : 0;
      }
      if($row2 > 0){
        $arr2=mysqli_fetch_assoc($qry2);
        $date2=(date("dmYHis",strtotime($arr2["created_datetime"])) != null) ? date("dmYHis",strtotime($arr2["created_datetime"])) : 0;
      }
      $name=($date1 > $date2) ? $arr1["firstname"]." ".$arr1["surname"] : $arr2["firstname"]." ".$arr2["surname"] ;
    }else{
     $name="Unknown";
    }
    return $name;
  }
}
function fillSelectOptionCompany($dbcon){
  $output='<option value="0">Firma wählen</option>';
  $qry=mysqli_query($dbcon,"SELECT id,company_name FROM company");
  if($qry){
    $rows=mysqli_num_rows($qry);
    if($rows > 0){
      while($arr=mysqli_fetch_assoc($qry)){
             $output.='<option value='.$arr["id"].'>'.$arr["company_name"].'</option>';
          } 
    }else{

    }
  }
  return $output;
}
function fillcompanywithnotid($dbcon,$cid){
    $output='<option value="0">Firma wählen</option>';
  $qry=mysqli_query($dbcon,"SELECT id,company_name FROM company WHERE id != $cid");
  if($qry){
    $rows=mysqli_num_rows($qry);
    if($rows > 0){
      while($arr=mysqli_fetch_assoc($qry)){
             $output.='<option value='.$arr["id"].'>'.$arr["company_name"].'</option>';
          } 
    }else{

    }
  }
  return $output;
}
function fillInventoryItems($dbcon,$cid){
  $output='<option value="0">7Select Article</option>';
  $qry=mysqli_query($dbcon,"SELECT id,article FROM inventory WHERE cid='$cid'");
  if($qry){
    $rows=mysqli_num_rows($qry);
    if($rows > 0){
      while($arr=mysqli_fetch_assoc($qry)){
             $output.='<option value='.$arr["id"].'>'.$arr["article"].'</option>';
          } 
    }else{
        $output='<option value="0">8Select Article</option>';
    }
  }
  return $output;
}
function fillEmployee($dbcon,$cid){
  $output='<option value="0">Wählen</option><option value="NO">Nein</option>';
  $qry=mysqli_query($dbcon,"SELECT id,firstname,surname FROM employee WHERE cid='$cid'");
  if($qry){
    $rows=mysqli_num_rows($qry);
    if($rows > 0){
      while($arr=mysqli_fetch_assoc($qry)){
             $output.='<option value='.$arr["id"].'>'.$arr["firstname"].' '.$arr["surname"].'</option>';
          } 
    }else{
        $output='<option value="0">Select Article</option>';
    }
  }
  return $output;
}
function fillInventorysize($dbcon,$cid){
  $output='';
    $qry=mysqli_query($dbcon,"SELECT id,size FROM inventory WHERE cid='$cid' GROUP BY size");
    if($qry){
      $rows=mysqli_num_rows($qry);

      if($rows > 0){
        $size='';
        while($arr=mysqli_fetch_assoc($qry)){

               ($arr["size"]== '' || empty($arr['size'])) ? $size='0' : $size=$arr["size"];
               $output.='<option value="'.$size.'">'.$arr["size"].'</option>';
            } 
      }else{
          $output='<option value=""></option>';
        }
    }
    return $output;
  }
function getInvetoryDetails($dbcon,$issueItem,$scr){
  $qry=mysqli_query($dbcon,"SELECT crowd,defect,lost,output_item FROM inventory WHERE id='$issueItem'");
  if($qry){
    $rows=mysqli_num_rows($qry);
    if($rows > 0){
      $arr=mysqli_fetch_assoc($qry);
      $cr=$arr["crowd"]; //30
      $preOi=$arr["output_item"]; // 10
      $def=$arr["defect"]; //5 
      $lost=$arr["lost"]; //5
      $trem=$cr-$preOi-$def-$lost-$scr;  //30-10-5-5-5=15
      $oi=$cr-$trem-$def-$lost;
      //$ti=$cr - $def - $lost; // 30-5-5=//20
      // $oi=intval(($preOi + $scr)-($def + $lost));  //10+7 -5-5=7 ==check this line
      // $rem=$ti-$oi; //20 - 7 =13 
      $qry2=mysqli_query($dbcon,"UPDATE inventory SET output_item='$oi',remaining_item='$trem' WHERE id='$issueItem'");  // 30 10 5 5=10 -5 = 5
      if($qry2){
        return true;
      }else{
        return false;
      }
    }
  }
}
function InvetoryReturn($dbcon,$article_id,$weid,$slectedCrowd,$lost,$eid){
    $qry=mysqli_query($dbcon,"SELECT i.id,i.crowd,i.defect,i.lost,i.output_item,i.remaining_item,wei.article_crowd FROM inventory i INNER JOIN work_equipment_issue wei ON i.id=wei.article WHERE wei.eid='$eid' AND i.id='$article_id' AND wei.id='$weid'");
    $rows=mysqli_num_rows($qry);
    if($rows > 0){
      $total_remaining=$defect=$output=$articleCrd=$lost_value=0;
      $arr=mysqli_fetch_assoc($qry);
      $cr=$arr["crowd"]; //30
      $def=$arr["defect"]; //
      $los=$arr["lost"]; //
      $preOi=$arr["output_item"]; //
      // $remItem=$arr["remaining_item"]; 
      $artclIssue=$arr["article_crowd"]; //10

      if($lost == "YES"){
        $total_remaining= $cr - ($def + $los + $preOi);   //30 -5 -5 - 5 =10
        // $defect=$def; // 5
        $lost_value=$los+$slectedCrowd; // 5 + 5 =10
        $output=$preOi-$slectedCrowd; // 10 - 5 = 5
        $articleCrd=$artclIssue-$slectedCrowd; // 5 - 5 =0
      }else{
        $total_remaining= ($cr +$slectedCrowd) - ($def +$los + $preOi) ; //(30+5) -(5+0+15)= 15
        $lost_value=$los;
        $output=$preOi-$slectedCrowd; 
        $articleCrd=$artclIssue-$slectedCrowd;
      }
      $qry2=mysqli_query($dbcon,"UPDATE inventory SET output_item='$output',remaining_item='$total_remaining',lost='$lost_value' WHERE id='$article_id'");     
      if($qry2){
        $qry3=mysqli_query($dbcon,"UPDATE work_equipment_issue SET article_crowd='$articleCrd' WHERE article='$article_id' AND id='$weid' AND eid='$eid'");
        if($qry3){
          return true;
        }else{
          return false;
        }
      }else{
        return false;
      }
    }
}
function fillSelectOption($dbcon,$cid){
  $output='<option value="0">Arbeitsmittel wählen</option>';
  $qry=mysqli_query($dbcon,"SELECT id,article,size FROM inventory WHERE cid='$cid'");
  if($qry){
    $rows=mysqli_num_rows($qry);
    if($rows >0){
      while($arr=mysqli_fetch_assoc($qry)){
             $output.='<option value='.$arr["id"].'>'.$arr["article"].' '.$arr["size"].'</option>';
          } 
    }else{

    }
  }
  return $output;
}
function fillSelectOptionreturn($dbcon,$eid){
  $output='<option value="0#0">Arbeitsmittel wählen</option>';
  $qry=mysqli_query($dbcon,"SELECT i.id,i.article AS article_name,i.size,wei.id AS weid,wei.article,wei.data FROM inventory i INNER JOIN work_equipment_issue wei ON i.id=wei.article WHERE wei.eid='$eid' GROUP BY wei.id");
  if($qry){
    $rows=mysqli_num_rows($qry);
    if($rows >0){
      while($arr=mysqli_fetch_assoc($qry)){
             $output.='<option value='.$arr["id"].'#'.$arr['weid'].'>'.$arr["article_name"].' '.$arr["size"].'  ['.date("d.m.Y | H:i",strtotime($arr["data"])).']</option>';
          } 
    }else{

    }
  }
  return $output;
}
function fillSelectOptionHallmark($dbcon,$cid,$eid){
 $output='<option value="0">Wählen</option><option value="Keine">Keine</option>';
   $qry=mysqli_query($dbcon,"SELECT vl.id,vlp.id AS vlpid,vl.hallmark FROM vehicle_list vl INNER JOIN vehicle_log_protocol vlp ON vl.id=vlp.hallmark WHERE vl.cid='$cid' AND vl.status='1' AND vl.avaibility='0' AND vl.emp_vh_id='$eid' AND vlp.stand=1 GROUP BY vl.id ORDER BY vlp.id DESC");
  // $qry=mysqli_query($dbcon,"SELECT id,hallmark FROM vehicle_list WHERE cid='$cid' AND status='1' AND eid='$eid'"); GROUP BY vl.id
  if($qry){
    $rows=mysqli_num_rows($qry);
    if($rows > 0){
      while($arr=mysqli_fetch_assoc($qry)){
             $output.='<option value='.$arr["id"].'#'.$arr["vlpid"].'>'.$arr["hallmark"].'</option>';
          } 
    }else{

    }
  }
  return $output;
}
function fillSelectReturnOptionHallmark($dbcon,$cid,$eid,$tablename){
  $output='<option value="0">Wählen</option>';
  $qry=mysqli_query($dbcon,"SELECT vl.id,vl.hallmark,t.hallmark AS thall,vl.cid AS vhcl_comp,t.vlpid,t.id AS handoverID,t.data_handover FROM vehicle_list vl RIGHT JOIN ".$tablename." t ON vl.id=t.hallmark WHERE t.eid='$eid' AND t.cid='$cid' AND t.issue='1'");
  $qryHandoverNO=mysqli_query($dbcon,"SELECT COUNT(hallmark) FROM ".$tablename." WHERE hallmark='Keine' AND eid='$eid'");
  $hndover=mysqli_fetch_assoc($qryHandoverNO);
  $returnTable=($tablename == 'tank_chip_handover') ? 'tank_chip_return' : 'uta_fuel_card_return';
  $qryreturnNO=mysqli_query($dbcon,"SELECT COUNT(hallmark) FROM ".$returnTable." WHERE hallmark='Keine' AND eid='$eid'");
  $retur=mysqli_fetch_assoc($qryreturnNO);
  $data=($hndover > $retur) ? 1 : 0;
  if($qry){
    $rows=mysqli_num_rows($qry);
    if($rows > 0){
      while($arr=mysqli_fetch_assoc($qry)){
            if($arr["thall"] == "Keine"){
              if($data == 1){
                $output.='<option value="Keine#'.$arr["handoverID"].'">Keine ['.date("d.m.Y | H:i",strtotime($arr["data_handover"])).']</option>';
              }  
            }else{
              if($arr["vhcl_comp"] == $cid){
                 $output.='<option value='.$arr["id"].'#'.$arr["handoverID"].'>'.$arr["hallmark"].' ['.date("d.m.Y | H:i",strtotime($arr["data_handover"])).']</option>';
              }
            }
          } 
    }else{

    }
  }
  return $output;
}
function getCompanyName($dbcon,$cid){
  $qry=mysqli_query($dbcon,"SELECT company_name FROM company WHERE id='$cid'");
  $arr=mysqli_fetch_assoc($qry);
  return $arr["company_name"];
}

function getEquipment($dbcon,$id){
  // $qry=mysqli_query($dbcon,"SELECT signed_by FROM work_equipment_return WHERE eid='$id'");
  $hasEmptySign=true;
  $hasWorkEquipment='Nein';
  $qry=mysqli_query($dbcon,"SELECT staff_signature,employee_signature,article_crowd FROM work_equipment_issue WHERE eid='$id'");
  $qryWEReturn=mysqli_query($dbcon,"SELECT staff_signature,employee_signature FROM work_equipment_return WHERE eid='$id'");
  if(mysqli_num_rows($qryWEReturn) > 0){
     while($arrR=mysqli_fetch_assoc($qryWEReturn)){
       if(!empty($arrR["staff_signature"]) && !empty($arrR["employee_signature"])){
          $hasEmptySign=false; 
       }
     }
  }else{
    $hasEmptySign=false; 
  }
  $rows=mysqli_num_rows($qry);
  if($rows > 0){
    while($arr=mysqli_fetch_assoc($qry)){
      if(empty($arr['staff_signature']) || empty($arr['employee_signature']) || $arr["article_crowd"] != 0 || $hasEmptySign == true){
        $hasWorkEquipment='Ja';
      }
    }
  }
  return $hasWorkEquipment;
}
function gettankChip($dbcon,$id){
  // 
  $qry1=mysqli_query($dbcon,"SELECT signature_staff,signature_employee FROM tank_chip_handover WHERE eid='$id' AND hallmark <> 'Keine' AND issue='1'");
  $qry2=mysqli_query($dbcon,"SELECT hallmark FROM tank_chip_handover WHERE eid='$id'");
  $qry3=mysqli_query($dbcon,"SELECT hallmark FROM tank_chip_return WHERE eid='$id'");
  $hasTankChip='Nein';
  if(mysqli_num_rows($qry2) > mysqli_num_rows($qry3)){
    $hasTankChip='Ja';
  }
  // $qry=mysqli_query($dbcon,"SELECT signed_by,signature_staff,signature_employee FROM tank_chip_return WHERE eid='$id' AND hallmark <> 'NO'");
  // $qry2=mysqli_query($dbcon,"SELECT signature_staff, FROM tank_chip_handover WHERE eid='$id' AND hallmark <> 'NO'");
  $rows=mysqli_num_rows($qry1);
  if($rows > 0){
    while($arr=mysqli_fetch_assoc($qry1)){
      // if($arr['signed_by'] == 0 || !empty($arr['signature_employee'])){
        $hasTankChip='Ja';
      // }
    }
  }
  return $hasTankChip;
}
function getUTAfuelcard($dbcon,$id){
  // $qry=mysqli_query($dbcon,"SELECT signed_by FROM uta_fuel_card_return WHERE eid='$id' AND hallmark <> 'NO'");
  $qry1=mysqli_query($dbcon,"SELECT signed_by,signature_staff,signature_employee FROM uta_fuel_card_handover WHERE eid='$id' AND hallmark <> 'Keine' AND issue='1'");
  $qry2=mysqli_query($dbcon,"SELECT hallmark FROM uta_fuel_card_handover WHERE eid='$id'");
  $qry3=mysqli_query($dbcon,"SELECT hallmark FROM uta_fuel_card_return WHERE eid='$id'");
  $hasfuelCard='Nein';
  if(mysqli_num_rows($qry2) > mysqli_num_rows($qry3)){
    $hasfuelCard='Ja';
  }
  $rows=mysqli_num_rows($qry1);
  if($rows > 0){
    while($arr=mysqli_fetch_assoc($qry1)){
      // if($arr['signed_by'] == 0 || empty($arr['signature_employee'])){
        $hasfuelCard='Ja';
      // }
    }
  }
  return $hasfuelCard;
}
function getvehicleLog($dbcon,$id){
  $qry=mysqli_query($dbcon,"SELECT sign_fleet_manager,sign_vehicle_manager,data FROM vehicle_log_protocol WHERE eid='$id'");
  $rows=mysqli_num_rows($qry);
  $hasVehicle='Nein';
  if($rows > 0 ){
    while($arr=mysqli_fetch_assoc($qry)){
      if($arr['sign_fleet_manager'] == '' || $arr['sign_vehicle_manager'] == '' || $arr["data"] == '0000-00-00 00:00:00'){
        $hasVehicle='Ja';
      }
    }
  }
  return $hasVehicle;
}

function getDrivingLicense($dbcon,$id){
  $qry=mysqli_query($dbcon,"SELECT id,signature_vehicle_user,signature_fleet_manager,signed_by,main_data FROM driver_license WHERE eid='$id' ORDER BY id DESC");
  $rows=mysqli_num_rows($qry);
  $hasDL='<p class="ml-10 font-weight-bold">Nein</p>';
  if($rows > 0){
    $arr=mysqli_fetch_assoc($qry);
      $last_test=($arr["main_data"] == '0000-00-00 00:00:00') ? '00.00.0000' : date("d.m.Y",strtotime($arr["main_data"]));
      $next_test=($arr["main_data"] == '0000-00-00 00:00:00') ? '00.00.0000' : date("d.m.Y",strtotime("+6 months", strtotime($arr["main_data"])));
      if(!empty($arr["signature_vehicle_user"]) && !empty($arr["signature_fleet_manager"]) && $last_test != '00.00.0000 | 00:00'){
          $hasDL='<p style="font-weight:bold">'.$last_test.'|<span style="color:green;padding:5px;">'.$next_test.'</span></p>';
      }else{
          $hasDL='<p class="d-inline-block bg-red p-5 ml-10 text-light">Offen</p>';
      }
  }
  return $hasDL;
}
?>