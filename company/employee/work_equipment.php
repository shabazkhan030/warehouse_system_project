<?php
session_start();
if(!isset($_SESSION["isLogedIn"])){
header("location:../../index.php");
}
if(!isset($_SESSION["company"]["cid"])){
header("location:../company.php");
}
$eid=intval(base64_decode($_GET["eid"])/77777);
$cid=intval(base64_decode($_SESSION["company"]["cid"])/99999);
if(!isset($_GET["eid"]) || $eid == 0){
header("location:employee.php");
}
include "../../dbcon/dbcon.php";
include "../../func/functions.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Arbeitsmittel</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">
<link rel="stylesheet" type="text/css" href="../../css/company.css">
</head>
<body>
<div id="toast"></div>
<?php include "common/headtop.php"; ?>
<?php include "common/navbar.php" ?>

<div class="my-100">
<ul class="d-flex  bg-sub-nav list-style-none p-0">
<li><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
<li class="active-profile-0"><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
<li><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
<li><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
<li><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
<li><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
</ul>
</div>

<style>
</style>
<div class="my-100 mt-20 mb-20">
  <div class="d-flex">
    <div class="oben1"><?php include "common/employee_name.php"; ?></div>
    <div class="oben1 obenm">Arbeitsmittel</div>
    <div class="oben1 obenr">
      <div>Letzte Änderung: <?php echo lastchangeby($dbcon,'work_equipment',$cid,$eid); ?> <br>
        <?php echo lastchangedate($dbcon,'work_equipment',$cid,$eid) ?></div>
      <div><a href="javascript:void(0)" class="d-inline-block bg-orange text-light p-5 cur-pointer text-decoration ml-20" onclick="printEquipment()">PDF Arbeitsmittel</a></div>
    </div>
  </div>
</div>
<div class="my-100" style="display:flex">
  
  <!--<?//php //include "common/sidebar.php"; ?>-->
 <!-- <div class="w-20">
<ul class="bg-light list-style-none d-block p-0">
<li class="p-10"><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
<li class="active-profile p-10"><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
<li class="p-10"><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
<li class="p-10"><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
<li class="p-10"><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
<li class="p-10"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
</ul>
</div>-->
  
  <div class="w-100" style="display:flex;flex-wrap:no-wrap;"> <section class="equipment-issue">
    <!--<div><a href="javascript:void(0)" class="bg-lightblue p-10 text-light text-decoration">Arbeitsmittel </a></div>-->
    <div>
      <!--style="overflow-x:auto;overflow-y: hidden;"-->
      <form id="workEquIssueForm" class="work100">
        <input type="hidden" name="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
        <input type="hidden" name="cid" id="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
        <input type="hidden" name="eid" id="eid" value="<?php echo $_GET["eid"]; ?>">
        <table class="w-100 mt-501 wd-68" id="workEquipmentTable" style="overflow-x:scroll;height:100%">
          <thead class="font-weight-bold text-center wd-69">
            <tr>
              <td class="text-red">Arbeitsmittel Übergabe</td>
              <td class="text-green">Menge</td>
              <td>Ausgeliefert </td>
              <td>Datum</td>
              <td>Unterschrift Sachbearbeiter</td>
              <td>Unterschrift Mitarbeiter</td>
            </tr>
          </thead>
          <tbody class="text-center" id="createnewWorkEquipment">
            <?php 
$eeid=base64_decode($_GET["eid"])/77777;
$qry=mysqli_query($dbcon,"SELECT wei.*,p.firstname,p.surname FROM work_equipment_issue wei LEFT JOIN personal_admin p ON wei.signed_by=p.id WHERE wei.eid='$eeid' AND wei.cid='$cid'");
if($qry){
$rows=mysqli_num_rows($qry);
if($rows > 0){

while($arr=mysqli_fetch_assoc($qry)){
?>
            <tr class="mt-501">
              <td><input type="hidden" name="weid[]" value=<?php echo $arr["id"]?>>
                <select name="issueItem[]" class="wd1">
                  <?php
$options=mysqli_query($dbcon,"SELECT id,article,size FROM inventory WHERE cid='$cid'");
if($options){
while($arr2=mysqli_fetch_assoc($options)){
if($arr2["id"] == $arr["article"]){
echo '<option value='.$arr2["id"].' selected>'.$arr2["article"].' '.$arr2["size"].'</option>';
}else{
echo '<option value='.$arr2["id"].'>'.$arr2["article"].' '.$arr2["size"].'</option>';
}		
}
}
?>
                </select>
              </td>
              <td><input type="text" name="slectedCrowd[]" value="<?php echo $arr["article_crowd"] ?>" readonly class="wd3">
              </td>
              <td><input type="text" value="<?php echo $arr["selected_crowd"] ?>" readonly class="wd2">
              </td>
              <td><input type="text" name="issueDate[]" class="wd4" readonly value='<?php echo date("d.m.Y | H:i",strtotime($arr["data"])) ?>'>
              </td>
              <td><?php if($arr["staff_signature"] != '' || !empty($arr["staff_signature"])){ ?>
                <div class="signaturestaffWrapper" class="wd5"> <img src="<?php echo "../../img/signature_uploads/".$arr['staff_signature']?>" style="width:100%;" id="signaturestaffimage">
                  <input type="hidden" name="signature_staff_handover[]" class="signature_staff_handover">
                  <p><?php echo $arr["firstname"]." ".$arr["surname"]; ?></p>
                  <!-- 													  <input type="hidden" class="save_btn_staff_issue">
<input type="hidden" class="clear_btn_staff_issue">
-->
                </div>
                <?php
}else{ ?>
                <div class="signature-pad-staff">
                  <div class="wd6">
                    <div class="note_staff_handover" onmouseover="staffSign();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
                    <canvas class="the_canvas_staff_handover" width="290px" height="95px"></canvas> </div>
                  <div class="d-flex justify-content-between" style="margin:10px;">
                    <input type="hidden" class="signature_staff_handover" name="signature_staff_handover[]">
                    <button type="button" class="clear_btn_staff_handover" class="p-5" data-action="clearstaffSign">
                   löschen
                    </button>
                    <button type="button" class="save_btn_staff_handover" class="p-5" data-action="save-png">
                   speichern
                    </button>
                  </div>
                </div>
                <?php } ?>
              </td>
              <td><?php if($arr["employee_signature"]== '' || empty($arr["employee_signature"])){ ?>
                <div class="signature-pad-employee">
                  <div class="wd7">
                    <div class="note_employee_handover" onmouseover="employeeSign();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
                    <canvas class="the_canvas_employee_handover" width="290px" height="95px"></canvas> </div>
                  <div class="d-flex justify-content-between" style="margin:10px;">
                    <input type="hidden" class="signature_employee_handover" name="signature_employee_handover[]">
                    <button type="button" class="clear_btn_employee_handover" class="p-5" data-action="clearemployeeSign">
                   löschen
                    </button>
                    <button type="button" class="save_btn_employee_handover" class="p-5" data-action="save-png">
                    speichern
                    </button>
                  </div>
                </div>
                <?php
}else{ ?>
                <div class="signatureemployeeWrapper" class="wd8"> <img src="<?php echo "../../img/signature_uploads/".$arr['employee_signature']?>" style="width:100%;" id="signatureemployeeimage">
                  <input type="hidden" name="signature_employee_handover[]" class="signature_employee_handover">
                </div>
                <?php } ?>
              </td>
            </tr>
            <?php
}
}else{
}

}  
?>
          </tbody>
        </table>
        <div class="w-100 text-right mt-20"> <a href="javascript:void(0)" onclick="AddWorkEquipment()" class="btn-sm bg-green mr-20">Neues Arbeitsmittel anlegen +</a> </div>
        <div class="w-100 text-right mt-20 mb-50">
          <input type="submit" onclick="SubmitWorkEquipmentissue()" class="btn-sm bg-grey mr-20 border-grey cur-pointer" value="Änderung speichern">
        </div>
      </form>
    </div>
    </section>
    <!-- <section class="ml-20" style="margin-top:150px;">
	
</section> -->
  </div>
  <!-- <div class="ml-50 d-flex justify-content-between"> -->
  <!--
<div class="w-15 p-20">
<p>Letzte Änderung: <?php echo lastchangeby($dbcon,'work_equipment',$cid,$eid); ?></p>  
<p class="font-weight-bold"><?php echo lastchangedate($dbcon,'work_equipment',$cid,$eid) ?></p>

<a href="javascript:void(0)" class="d-inline-block bg-orange text-light p-5 cur-pointer text-decoration ml-20" style="margin-top:80px;" onclick="printEquipment()">PDF Arbeitsmittel</a>

</div>	-->
  <!-- </div> -->
</div>

<div class="my-100" style="display:flex">
  <!--<div class="w-20"></div>-->
  <div class="w-100" style="display:flex;flex-wrap:no-wrap;"> <section class="equipment-issue mt-501">
    <div>
	<!--<div style="overflow-x:auto;overflow-y: hidden;">-->
      <form id="workEqureturnForm">
        <input type="hidden" name="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
        <input type="hidden" name="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
        <input type="hidden" name="eid" id="eid" value="<?php echo $_GET["eid"]; ?>">
        <table class="w-100 mt-501" id="workEquipmentreturnTable" style="overflow-x:scroll;height:100%">
          <thead class="font-weight-bold text-center wd-69">
            <tr>
              <td class="text-red">Arbeitsmittel Rückgabe</td>
              <td>Menge</td>
              <td class="text-red">Verloren</td>
              <td>Datum</td>
              <td>Unterschrift Sachbearbeiter</td>
              <td>Unterschrift Mitarbeiter</td>
            </tr>
          </thead>
          <tbody class="text-center" id="WorkEquipmentreturn">
            <?php 
$eeid=base64_decode($_GET["eid"])/77777;
$qry=mysqli_query($dbcon,"SELECT wer.*,p.firstname,p.surname FROM work_equipment_return wer LEFT JOIN personal_admin p ON wer.signed_by=p.id WHERE wer.eid='$eeid' AND wer.cid='$cid'");
if($qry){
$rows=mysqli_num_rows($qry);
if($rows > 0){

while($arr=mysqli_fetch_assoc($qry)){
?>
            <tr class="mt-501">
              <td><input type="hidden" name="werid[]" value=<?php echo $arr["id"]?>>
                <select  name="returnItem[]" style="padding:2px;width:200px;margin-bottom: 115px;">
                  <?php
$options=mysqli_query($dbcon,"SELECT id,article,size FROM inventory WHERE cid='$cid'");
if($options){
while($arr2=mysqli_fetch_assoc($options)){
if($arr2["id"] == $arr["article"]){
echo '<option value='.$arr2["id"].' selected>'.$arr2["article"].' '.$arr2["size"].'</option>';
}else{
echo '<option value='.$arr2["id"].'>'.$arr2["article"].' '.$arr2["size"].'</option>';
}		
}
}
?>
                </select>
              </td>
              <td><input type="text" name="returnCrowd[]" value="<?php echo $arr['article_crowd']; ?>" style="width:60px;margin-bottom:115px;" readonly >
              </td>
              <td>
                <select name="lost[]" style="padding:2px;width:60px;margin-bottom: 115px;">
                  <option value="NO" <?php  if($arr["lost"] == "NO"){ ?> selected <?php } ?> >Nein</option>
                  <option value="YES" <?php if($arr["lost"] == "YES"){ ?> selected <?php } ?> >Ja</option>
                </select>
              </td>
              <td><input type="text" name="returnDate[]" style="width:200px;margin-bottom: 115px;" readonly value='<?php echo date("d.m.Y | H:i",strtotime($arr["data_return"])); ?>'>
              </td>
              <td style="padding-top:20px"><?php if($arr["staff_signature"] == '' || empty($arr["staff_signature"])){ ?>
                <div class="signature-pad-staff">
                  <div style="border:solid 2px black; width:300px;height:120px;padding:3px;position:relative;">
                    <div class="note_staff_return" onmouseover="staffSignReturn();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
                    <canvas class="the_canvas_staff_return" width="290px" height="100px"></canvas> </div>
                  <div class="d-flex justify-content-between" style="margin:10px;">
                    <input type="hidden" class="signature_staff_return" name="signature_staff_return[]">
                    <button type="button" class="clear_btn_staff_return" class="p-5" data-action="clearstaffSign">
                    Clear
                    </button>
                    <button type="button" class="save_btn_staff_return" class="p-5" data-action="save-png">
                    Speichern
                    </button>
                  </div>
                </div>
                <?php }else{ ?>
                <div class="signaturestaffWrapper" style="border:solid 2px black;width:300px;height:120px;padding:3px;margin-top:-40px"> <img src="<?php echo "../../img/signature_uploads/".$arr['staff_signature']?>"  style="width:100%;" id="signaturestaffimage">
                  <input type="hidden" name="signature_staff_return[]" class="signature_staff_return">
                  <p><?php echo $arr["firstname"]." ".$arr["surname"]; ?></p>
                </div>
                <?php } ?>
              </td>
              <td style="padding-top:20px"><?php if($arr["employee_signature"] == '' || empty($arr["employee_signature"])){ ?>
                <div id="signature-pad-employee">
                  <div style="border:solid 2px black; width:300px;height:120px;padding:3px;position:relative;">
                    <div class="note_employee_return" onmouseover="employeeSignReturn();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
                    <canvas class="the_canvas_employee_return" width="290px" height="95px"></canvas> </div>
                  <div class="d-flex justify-content-between" style="margin:10px;">
                    <input type="hidden" class="signature_employee_return" name="signature_employee_return[]">
                    <button type="button" class="clear_btn_employee_return" class="p-5" data-action="clearemployeeSign">
                    Clear
                    </button>
                    <button type="button" class="save_btn_employee_return" class="p-5" data-action="save-png">
                   Speichern
                    </button>
                  </div>
                </div>
                <?php }else{ ?>
                <div class="signatureemployeeWrapper" style="border:solid 2px black;width:300px;height:120px;padding:3px;margin-top:-40px"> <img src="<?php echo "../../img/signature_uploads/".$arr['employee_signature']?>"  style="width:100%;" id="signatureemployeeimage">
                  <input type="hidden" name="signature_employee_return[]" class="signature_employee_return">
                </div>
                <?php } ?>
              </td>
            </tr>
            <?php
}
}else{
}

}  
?>
          </tbody>
        </table>
        <div class="w-100 text-right mt-501"> <a href="javascript:void(0)" onclick="AddWorkEquipmentReturn()" class="btn-sm bg-green mr-20">Rückgabe anlegen +</a> </div>
        <div class="w-100 text-right mt-20 mb-50">
          <input type="submit" onclick="SubmitWorkEquipmentReturn()" class="btn-sm bg-grey mr-20 border-grey cur-pointer" value="Änderung speichern">
        </div>
      </form>
    </div>
    </section></div>
  <!--<div class="w-15"> </div>-->
</div>
<div style="margin-left:30px;">
  <a href="javascript:void(0)" onclick="loadHistory()" style="padding:10px 10px;font-weight:bold;text-decoration:none;">View Previous Company History</a>
</div>
<div id="historyModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
      <p>workequipment History in other company</p>
      <div id="showCompany" style="margin-top:10px;margin-bottom:10px;text-align:left;">
        
      </div> 
      <button class="closeModal btn bg-red mr-20">Schliesen</button> 
  </div>

</div>
<!-- PRINT START-->
<?php include "../print/work_equipment.php"; ?>
<!-- PRINT END -->
<script src="../../js/signature.js"></script>
<script src="../../js/functions.js"></script>
<script src="../../js/employee.js"></script>
<script>
function printDiv() {
var divContents = document.getElementById("printworkEquip").innerHTML;
var a = window.open('', '', 'height=3508, width=2480');
a.document.write('<html>');
a.document.write('<link rel="stylesheet" type="text/css" href="../css/style.css">');
a.document.write('<link rel="stylesheet" type="text/css" href="../css/company.css">');
a.document.write('<body >');
a.document.write(divContents);
a.document.write('</body></html>');
a.focus();
setTimeout(function(){a.print();},1000);
a.document.close();

}

function AddWorkEquipment(){
var tbody=getID('createnewWorkEquipment');
var tr='<tr class="mt-501">';
tr+='<td>';
tr+='<input type="hidden" name="weid[]" value="">';
tr+='<select name="issueItem[]" class="populatearticleQty" style="width:200px;margin-bottom: 135px;padding:3px;">';
tr+='<?php echo fillSelectOption($dbcon,$cid); ?>';        
tr+='</select>';        
tr+='</td>';
tr+='<td>';
tr+='<select name="slectedCrowd[]" class="selectArticleCrowd" style="width:60px;margin-bottom:135px;padding:2px;">';
tr+='</select>';
tr+='</td>';
tr+='<td>';
tr+='<input type="text" readonly style="width:60px;margin-bottom:127px;margin-top:0px;">'; 
tr+='</td>';
tr+='<td>';
tr+='<input type="datetime-local" name="issueDate[]" style="width:200px;margin-bottom:135px;margin-left:1px;">';
tr+='</td>';
tr+='<td>';
tr+='<div id="signature-pad-staff">';
tr+='<div style="border:solid 2px black; width:300px;height:110px;padding:3px;position:relative;">';
tr+='<div class="note_staff_handover" onmouseover="staffSign()" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>';
tr+='<canvas class="the_canvas_staff_handover" width="290px" height="95px"></canvas>';
tr+='</div>';
tr+='<div class="d-flex justify-content-between" style="margin:10px;">';
tr+='<input type="hidden" name="signature_staff_handover[]" class="signature_staff_handover">';
tr+='<button type="button" class="clear_btn_staff_handover" class="p-5" title="Unterschrift löschen">Löschen</button>';
tr+='<button type="button" class="save_btn_staff_handover" class="p-5" title="Unterschrift speichern">Speichern</button>'; 
tr+='</div>';
tr+='</div>';
tr+='</td>';
tr+='<td>';
tr+='<div class="signature-pad-employee">';
tr+='<div style="border:solid 2px black; width:300px;height:110px;padding:3px;position:relative;">';
tr+='<div class="note_employee_handover" onmouseover="employeeSign();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>';
tr+='<canvas class="the_canvas_employee_handover" width="290px" height="95px"></canvas>';
tr+='</div>';
tr+='<div class="d-flex justify-content-between" style="margin:10px;">';
tr+='<input type="hidden" name="signature_employee_handover[]" class="signature_employee_handover">';
tr+='<button type="button" class="clear_btn_employee_handover" class="p-5" data-action="clearemployeeSign" title="Unterschrift löschen">Löschen</button>';
tr+='<button type="button" class="save_btn_employee_handover" class="p-5" data-action="save-png" title="Unterschrift speichern">Speichern</button>'; 
tr+='</div>';
tr+='</div>';
tr+='<div class="signatureemployeeWrapper" style="display:none;border:solid 2px black;width:360px;height:110px;padding:3px;">';
tr+='<img src="" style="width:100%;" class="signatureemployeeimage">';
tr+='</div>';
tr+='</td>';
tr+='<td>';
//tr+='<a href="javascript:void(0)" class="d-inline-block bg-orange text-light p-5 cur-pointer text-decoration ml-20">Handover TEXT Equipment</a>';
tr+='</td>';
tr+='</tr>';
tbody.insertRow().innerHTML=tr; 
signaturestaff();
signatureEmployee(); 
populateCrowd();         
}

function AddWorkEquipmentReturn(){
var tbody=getID('WorkEquipmentreturn');
var tr='<tr class="mt-501">';
tr+='<td>';
tr+='<input type="hidden" name="werid[]" value="">';
tr+='<select name="returnItem[]" style="width:200px;margin-bottom: 135px;padding:2px;" class="populatearticleReturnQty">';
tr+='<?php echo fillSelectOptionreturn($dbcon,$eeid); ?>';        
tr+='</select>';          
tr+='</td>';
tr+='<td>';
tr+='<select name="returnCrowd[]" style="width:50px;padding:2px;width:60px;margin-bottom:135px;" class="selectArticleCrowdreturn">';  
tr+='</select>';
tr+='</td>';
tr+='<td>';
tr+='<select name="lost[]" style="padding:2px;width:50px;margin-bottom:135px;">';
tr+='<option value="NO">Nein</option>'
tr+='<option value="YES">Ja</option>'        
tr+='</select>';          
tr+='</td>';
tr+='<td>';
tr+='<input type="datetime-local" name="returnDate[]" style="width:200px;margin-bottom: 135px;">';
tr+='</td>';
tr+='<td>';
tr+='<div id="signature-pad-staff">';
tr+='<div style="border:solid 2px black; width:300px;height:110px;padding:3px;position:relative;">';
tr+='<div class="note_staff_return" onmouseover="staffSignReturn()" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>';
tr+='<canvas class="the_canvas_staff_return" width="290px" height="95px"></canvas>';
tr+='</div>';
tr+='<div class="d-flex justify-content-between" style="margin:10px;">';
tr+='<input type="hidden" name="signature_staff_return[]" class="signature_staff_return">';
tr+='<button type="button" class="clear_btn_staff_return" class="p-5"> Löschen</button>';
tr+='<button type="button" class="save_btn_staff_return" class="p-5">Speichern</button>'; 
tr+='</div>';
tr+='</div>';
tr+='</td>';
tr+='<td>';
tr+='<div class="signature-pad-employee">';
tr+='<div style="border:solid 2px black; width:300px;height:110px;padding:3px;position:relative;">';
tr+='<div class="note_employee_return" onmouseover="employeeSignReturn();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>';
tr+='<canvas class="the_canvas_employee_return" width="290px" height="95px"></canvas>';
tr+='</div>';
tr+='<div class="d-flex justify-content-between" style="margin:10px;">';
tr+='<input type="hidden" name="signature_employee_return[]" class="signature_employee_return">';
tr+='<button type="button" class="clear_btn_employee_return" class="p-5" data-action="clearemployeeSign"> Löschen</button>';
tr+='<button type="button" class="save_btn_employee_return" class="p-5" data-action="save-png">Speichern</button>'; 
tr+='</div>';
tr+='</div>';
tr+='<div class="signatureemployeeWrapper" style="display:none;border:solid 2px black;width:360px;height:110px;padding:3px;">';
tr+='<img src="" style="width:100%;" class="signatureemployeeimage">';
tr+='</div>';
tr+='</td>';
tr+='</tr>';
tbody.insertRow().innerHTML=tr;
populateCrowdReturn();
signatureEmployeeReturn();
signaturestaffReturn();               
} 

function loadHistory(){
  console.log("history");
  var modal =getID("historyModal");
  modal.style.display = "block";
  let cid=getID("cid").value;
  let eid=getID("eid").value;
  var span = document.getElementsByClassName("closeModal")[0];
  formdata=new FormData();
  formdata.append("getCompany","work_equipment_issue");
  formdata.append("cid",cid);
  formdata.append("eid",eid);
  fetch("../../ajax/ajax_employee.php",{
    method:"POST",
    body:formdata
  }).then(res=>res.json())
  .then((data)=>{
    let company='';
    console.log(data[0]);
    if(data[0] == 'empty'){
      console.log("empty");
    }else{
      for(var i in data){
         company+= `<a href="../print/equipmentHistory.php?cid='${data[i].company_id}&eid=${eid}">${data[i].company_name}</p>`;
      }
      getID("showCompany").innerHTML=company;
    }
  })
  span.onclick = function() {
    modal.style.display = "none";
  }
  window.onclick = function(event){
    if(event.target == modal){
      modal.style.display = "none";
    }
  }
}
populateCrowd();
populateCrowdReturn();
signaturestaff();
signatureEmployee(); 
signatureEmployeeReturn();
signaturestaffReturn(); 

</script>
 <?php include "common/footer.php" ?>
</body>
</html>
