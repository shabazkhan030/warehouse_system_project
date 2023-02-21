<?php 
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../../index.php");
}
if(!isset($_SESSION["company"]["cid"])){
	header("location:../company.php");
}
$cid=base64_decode($_SESSION["company"]["cid"])/99999;
$eid=intval(base64_decode($_GET["eid"])/77777);
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
	<title>UTA Tankkarte</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
	<link rel="stylesheet" type="text/css" href="../../css/company.css">
</head>
<body>
	<div id="toast"></div>
    <?php include "common/headtop.php"; ?>
    <?php include "common/navbar.php" ?>
	
		<div class="my-100">
<ul class="d-flex  bg-sub-nav list-style-none p-0">
<li class="p-10"><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
            	<li class="p-10"><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
            	<li class="p-10"><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
            	<li class="active-profile-0"><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
            	<li class="p-10"><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
            	<li class="p-10"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
</ul>
</div>
	
	<div class="my-100 mt-20 mb-20">
  <div class="d-flex">
    <div class="oben1"><?php include "common/employee_name.php"; ?></div>
    <div class="oben1 obenm">UTA Tankkarte</div>
    <div class="oben1 obenr">
      <div>Letzte Änderung: <?php echo lastchangeby($dbcon,'uta_fuel_card_handover',$cid,$eid); ?> <br>
     <?php echo lastchangedate($dbcon,'uta_fuel_card',$cid,$eid) ?></div>

    </div>
  </div>
</div>
	
   <!-- <?php include "common/employee_name.php"; ?>
	<div class="my-100 mt-20 mb-20">
		
		<div style="position:absolute;right:50px;">
			<p>Letzte &Auml;nderung:<?php echo lastchangeby($dbcon,'uta_fuel_card_handover',$cid,$eid); ?></p>

			<p class="font-weight-bold"><?php echo lastchangedate($dbcon,'uta_fuel_card',$cid,$eid) ?></p>
			<div class="mt-50">		
			</div>	
		</div>
	</div>-->
	<div class="my-100" style="display:flex">
		<!--<div class="w-20">
            <ul class="bg-light list-style-none d-block p-0">
            	<li class="p-10"><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
            	<li class="p-10"><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
            	<li class="p-10"><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
            	<li class="active-profile p-10"><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
            	<li class="p-10"><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
            	<li class="p-10"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
            </ul>
		</div>-->
		<div class="w-100">
			<section class="equipment-issue">
				<!--<div class="ml-50">
					<a href="#" class="d-inline-block bg-lightblue text-light text-decoration p-5">UTA Tankkarte</a>
				</div>-->
				<div class="ml-20 mt-20">
					<p class="text-red">Übergabeprotokoll UTA Tankkarte</p>
				</div>
				<div>
				<!--<div style="overflow-x:auto;overflow-y: hidden;">-->
					<form id="handoverUTAForm">
						<input type="hidden" name="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
			            <input type="hidden" name="cid" id="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
				        <input type="hidden" name="eid" id="eid" value="<?php echo $_GET["eid"]; ?>">
						<table class="w-100 ml-10 mt-50" id="handoverUTATable" style="overflow-x:scroll;height:100%">
							<thead class="font-weight-bold text-center wd-69">
								<tr>
									<td class="text-red">Kennzeichen </td>
									<td class="text-green">Info 1</td>
									<td class="text-green">Info 2</td>
									<td>Datum</td>
              <td>Unterschrift Sachbearbeiter</td>
              <td>Unterschrift Mitarbeiter</td>
								</tr>
							</thead>
							<tbody class="text-center" id="createnewHandoverUTA">
								<?php 
								   $eeid=base64_decode($_GET["eid"])/77777;
                                   $qry=mysqli_query($dbcon,"SELECT uh.*,p.firstname,p.surname FROM uta_fuel_card_handover uh LEFT JOIN personal_admin p ON uh.signed_by=p.id WHERE uh.eid='$eeid' AND uh.cid='$cid'");
                                   if($qry){
                                   	$rows=mysqli_num_rows($qry);
                                   	if($rows > 0){
                                   		
                                       while($arr=mysqli_fetch_assoc($qry)){
                                       	?>
                                       	<tr class="mt-50">
											<td>
											<input type="hidden" name="hutaid[]" value=<?php echo $arr["id"]?>>
											<select name="hallmarkHandover[]" style="padding:2px;width:200px;margin-bottom:77px;">
									   <?php
									   if($arr["hallmark"] == "Keine"){
                                            echo '<option value='.$arr["id"].' selected>'.$arr["hallmark"].'</option>';
									   }else{
											$options=mysqli_query($dbcon,"SELECT id,hallmark FROM vehicle_list WHERE cid='$cid' AND status='1'");
											if($options){
												while($arr2=mysqli_fetch_assoc($options)){
													// if($arr["hallmark"] == "NO"){
		                                                   
		                                            //       break;
													// }else{
		                                               	if($arr2["id"] == $arr["hallmark"]){
															echo '<option value='.$arr2["id"].' selected>'.$arr2["hallmark"].'</option>';
														}else{
															echo '<option value='.$arr2["id"].'>'.$arr2["hallmark"].'</option>';
														}
													// }		
												}
											}
								        }
									    ?>
															
									        </select>
											<a href="javascript:void(0)" class="d-inline-block bg-orange text-light p-5 cur-pointer text-decoration ml-20 printUTAHandover" data-print=<?php echo $arr["id"]; ?>>Übergabeprotokoll</a>
											</td>
											<td>
												<input type="text" name="htext[]" value="<?php echo $arr["text1"] ?>" readonly style="width:180px;margin-bottom:115px">	
											</td>
											<td>
												<input type="text" name="htext2[]" value="<?php echo $arr["text2"] ?>" readonly style="width:180px;margin-bottom:115px">	
											</td>
											<td>
											<input type="text" name="hDate[]" style="width:200px;margin-bottom: 115px;" readonly value='<?php echo date("d.m.Y | H:i",strtotime($arr["data_handover"])) ?>'>
											</td>
											<td>
												<?php if($arr["signature_staff"] != '' || !empty($arr["signature_staff"])){ ?>
												<div class="signaturestaffWrapper" style="border:solid 2px black;width:300px;height:120px;padding:3px;margin-top:-40px">
													  <img src="<?php echo "../../img/signature_uploads/".$arr['signature_staff']?>" style="width:100%;" id="signaturestaffimage">
													  <input type="hidden" name="signature_staff_handover[]" class="signature_staff_handover">
													  <p><?= $arr["firstname"]." ".$arr["surname"];?></p>
												</div>
                                                <?php
												}else{ ?>
													<div class="signature-pad-staff">
												     <div style="border:solid 2px black; width:300px;height:120px;padding:3px;position:relative;">
												        <div class="note_staff_handover" onmouseover="staffSign(this);" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
												        <canvas class="the_canvas_staff_handover" width="290px" height="95px"></canvas>
												     </div>
												     <div class="d-flex justify-content-between" style="margin:10px;">
												        <input type="hidden" class="signature_staff_handover" name="signature_staff_handover[]">
												        <button type="button" class="clear_btn_staff_handover" class="p-5" data-action="clearstaffSign"> Löschen</button>
												        <button type="button" class="save_btn_staff_handover" class="p-5" data-action="save-png">Speichern</button>
												     </div>
												   </div>

											<?php } ?>
											</td>
											<td>
												<?php if($arr["signature_employee"]== '' || empty($arr["signature_employee"])){ ?>
												<div class="signature-pad-employee">
											     <div style="border:solid 2px black; width:300px;height:120px;padding:3px;position:relative;">
											        <div class="note_employee_handover" onmouseover="employeeSign();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
											        <canvas class="the_canvas_employee_handover" width="290px" height="95px"></canvas>
											     </div>
											     <div class="d-flex justify-content-between" style="margin:10px;">
											        <input type="hidden" class="signature_employee_handover" name="signature_employee_handover[]">
											        <button type="button" class="clear_btn_employee_handover" class="p-5" data-action="clearemployeeSign"> Löschen</button>
											        <button type="button" class="save_btn_employee_handover" class="p-5" data-action="save-png">Speichern</button>
											     </div>
											   </div>
											    <?php
												}else{ ?>
												<div class="signatureemployeeWrapper" style="border:solid 2px black;width:300px;height:120px;padding:3px;margin-top:-40px">
													  <img src="<?php echo "../../img/signature_uploads/".$arr['signature_employee']?>" style="width:100%;" id="signatureemployeeimage">
													  <input type="hidden" name="signature_employee_handover[]" class="signature_employee_handover">
												</div>
											  <?php } ?>
											</td>
											<!--<td>
												<a href="javascript:void(0)" class="d-inline-block bg-orange text-light p-5 cur-pointer text-decoration ml-20 printUTAHandover" data-print=<?php echo $arr["id"]; ?>>Übergabeprotokoll</a>
											</td>-->
										</tr>
										<?php
                                       }
                                   	}else{
                                   	}
                                   	
                                   }  
								 ?>
							</tbody>
						</table>
						<div class="w-100 text-right mt-20">
					         <a href="javascript:void(0)" onclick="AddhandoverUTA()" class="btn-sm bg-green mr-20">Neu anlegen +</a>
				       </div>
				       <div class="w-100 text-right mt-20 mb-50">
					        <input type="submit" onclick="SubmithandoverUTA()" id="submitUTBtn" class="btn-sm bg-grey mr-20 border-grey cur-pointer" value="Änderung speichern">
				      </div>
					</form>
				</div>	
			</section>			
		</div>
<!-- 	<div class="w-15">
			<p>Last Change:</p>
			<p class="font-weight-bold">2022-01-08 16:45</p>
			<div class="mt-50">
				<p class="btn-sm bg-orange" style="margin:0px;width:100%">Text Übergabeprotokoll UTA Tankkarte</p>
			</div>
		</div> -->

	</div>
	<div class="my-100" style="display:flex">
		<!--<div class="w-20"></div>-->
		<div class="w-100">
			<section class="equipment-issue mt-50">
				<div class="ml-20 mt-20">
					<p class="text-red">R&uuml;ckgabe UTA Tankkarte</p>
				</div>				
				<div>
				<!--<div style="overflow-x:auto;overflow-y: hidden;">-->
					<form id="returnUTAForm">
						<input type="hidden" name="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
			            <input type="hidden" name="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
				        <input type="hidden" name="eid" id="eid" value="<?php echo $_GET["eid"]; ?>">
						<table class="w-100 ml-10 mt-50" id="handoverTCTable" style="overflow-x:scroll;height:100%">
							<thead class="font-weight-bold text-center wd-69">
								<tr>
									<td>Kennzeichen</td>
									<td class="text-green">Info 1 </td>
									<td class="text-green">Info 2</td>
									<td>Datum</td>
									<td><span style="color: rgb(0, 0, 0); font-family: sans-serif, arial; font-size: medium; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 700; letter-spacing: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(245, 245, 245); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">Unterschrift Sachbearbeiter</span></td>
									<td>Unterschrift Mitarbeiter</td>
								</tr>
							</thead>
							<tbody class="text-center" id="createnewReturnUTA">
								<?php 
								   $eeid=base64_decode($_GET["eid"])/77777;
                                   $qry1=mysqli_query($dbcon,"SELECT ur.*,p.firstname,p.surname FROM uta_fuel_card_return ur LEFT JOIN personal_admin p ON ur.signed_by=p.id WHERE ur.eid='$eeid' AND ur.cid='$cid'");
                                   if($qry1){
                                   	$rows1=mysqli_num_rows($qry1);
                                   	if($rows1 > 0){
                                   		
                                       while($arr1=mysqli_fetch_assoc($qry1)){
                                       	?>
                                       	<tr class="mt-50">
											<td>
											<input type="hidden" name="rutaid[]" value=<?php echo $arr1["id"]?>>
											<select name="hallmarkreturn[]" style="padding:2px;width:200px;margin-bottom:77px;">
									   <?php
									   if($arr1["hallmark"] == "Keine"){
                                           echo '<option value='.$arr1["id"].' selected>'.$arr1["hallmark"].'</option>';
									   }else{
											$options=mysqli_query($dbcon,"SELECT vl.id,vl.hallmark FROM vehicle_list vl INNER JOIN uta_fuel_card_return ufr ON vl.id=ufr.hallmark WHERE ufr.eid='$eid' AND ufr.cid='$cid' GROUP BY vl.id");
											if($options){
												while($arr2=mysqli_fetch_assoc($options)){
													if($arr2["id"] == $arr1["hallmark"]){
														echo '<option value='.$arr2["id"].' selected>'.$arr2["hallmark"].'</option>';
													}else{
														echo '<option value='.$arr2["id"].'>'.$arr2["hallmark"].'</option>';
													}		
												}
											}
										}
									    ?>
															
									        </select>
											<a href="javascript:void(0)" class="d-inline-block text-light p-5 cur-pointer bg-orange text-decoration ml-20 printUTAReturn" data-print=<?php echo $arr1["id"]; ?>>Rückgabeprotokol</a>
											
											</td>
											<td>
												<input type="text" name="rtext[]" value="<?php echo $arr1["text1"] ?>" readonly style="width:180px;margin-bottom:115px">	
											</td>
											<td>
												<input type="text" name="rtext2[]" value="<?php echo $arr1["text2"] ?>" readonly style="width:180px;margin-bottom:115px">	
											</td>
											<td>
											<input type="text" name="rDate[]" style="width:200px;margin-bottom: 115px;" readonly value='<?php echo date("d.m.Y | H:i",strtotime($arr1["data_return"])) ?>'>
											</td>
											<td>
												<?php if($arr1["signature_staff"] != '' || !empty($arr1["signature_staff"])){ ?>
												<div class="signaturestaffWrapper" style="border:solid 2px black;width:300px;height:120px;padding:3px;margin-top:-40px">
													  <img src="<?php echo "../../img/signature_uploads/".$arr1['signature_staff']?>" style="width:100%;" id="signaturestaffimage">
													  <input type="hidden" name="signature_staff_return[]" class="signature_staff_return">
													  <p><?= $arr1["firstname"]." ".$arr1["surname"];?></p>
												</div>
                                                <?php
												}else{ ?>
													<div class="signature-pad-staff">
												     <div style="border:solid 2px black; width:300px;height:120px;padding:3px;position:relative;">
												        <div class="note_staff_return" onmouseover="staffSign();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
												        <canvas class="the_canvas_staff_return" width="290px" height="95px"></canvas>
												     </div>
												     <div class="d-flex justify-content-between" style="margin:10px;">
												        <input type="hidden" class="signature_staff_return" name="signature_staff_return[]">
												        <button type="button" class="clear_btn_staff_return" class="p-5" data-action="clearstaffSign"> Löschen</button>
												        <button type="button" class="save_btn_staff_return" class="p-5" data-action="save-png">Speichern</button>
												     </div>
												   </div>

											<?php } ?>
											</td>
											<td>
												<?php if($arr1["signature_employee"]== '' || empty($arr1["signature_employee"])){ ?>
												<div class="signature-pad-employee">
											     <div style="border:solid 2px black; width:300px;height:120px;padding:3px;position:relative;">
											        <div class="note_employee_return" onmouseover="employeeSign();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
											        <canvas class="the_canvas_employee_return" width="290px" height="95px"></canvas>
											     </div>
											     <div class="d-flex justify-content-between" style="margin:10px;">
											        <input type="hidden" class="signature_employee_return" name="signature_employee_return[]">
											        <button type="button" class="clear_btn_employee_return" class="p-5" data-action="clearemployeeSign"> Löschen</button>
											        <button type="button" class="save_btn_employee_return" class="p-5" data-action="save-png">Speichern</button>
											     </div>
											   </div>
											    <?php
												}else{ ?>
												<div class="signatureemployeeWrapper" style="border:solid 2px black;width:300px;height:120px;padding:3px;margin-top:-40px">
													  <img src="<?php echo "../../img/signature_uploads/".$arr1['signature_employee']?>" style="width:100%;" id="signatureemployeeimage">
													  <input type="hidden" name="signature_employee_return[]" class="signature_employee_return">
												</div>
											  <?php } ?>
											</td>
											<!--<td>
												<a href="javascript:void(0)" class="d-inline-block text-light p-5 cur-pointer bg-orange text-decoration ml-20 printUTAReturn" data-print=<?php echo $arr1["id"]; ?>>Rückgabeprotokol</a>
											</td>-->
										</tr>
										<?php
                                       }
                                   	}else{
                                   	}
                                   	
                                   }  
								 ?>
							</tbody>
						</table>
						<div class="w-100 text-right mt-20">
					         <a href="javascript:void(0)" onclick="AddreturnUTA()" class="btn-sm bg-green mr-20">R&uuml;ckgabe anlegen +</a>
				       </div>
				       <div class="w-100 text-right mt-20 mb-50">
					        <input type="submit" onclick="SubmitreturnUTA()" id="SubmitUTBtn" class="btn-sm bg-grey mr-20 border-grey cur-pointer" value="Änderung speichern">
				      </div>
					</form>
				</div>
			</section>			
		</div>
    </div>
    <div style="margin-left:30px;">
         <a href="javascript:void(0)" onclick="loadHistory()" style="padding:10px 10px;font-weight:bold;text-decoration:none;">View Previous Company History</a>
    </div>
    <div id="historyModal" class="modal">
  <!-- Modal content -->
			  <div class="modal-content">
			      <p>Tank Chip History in other company</p>
			      <div id="showCompany" style="margin-top:10px;margin-bottom:10px;text-align:left;">
			        
			      </div> 
			      <button class="closeModal btn bg-red mr-20">Schliesen</button> 
			  </div>
    </div>
    <div class="my-20 mt-100" style="display:flex"></div>
    <!-- PRINT -->
    <div style="display:flex;">
    <?php include "../print/uta_fuel_card.php"; ?>
    </div>
    <!-- PRINT -->
        <script src="../../js/functions.js"></script>
        <script src="../../js/signature.js"></script>
        <script src="../../js/uta_fuel_card.js"></script>
        <script>
        function printDivcreate() {
            var divContents = document.getElementById("printtankchipcreate").innerHTML;
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
        function printDivreturn() {
            var divContents = document.getElementById("printtankchipreturn").innerHTML;
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
  function AddhandoverUTA(){
    var tbody=getID('createnewHandoverUTA');
    var tr='<tr class="mt-50">';
      tr+='<td>';
      tr+='<input type="hidden" name="hutaid[]" value="">';
      tr+='<select name="hallmarkHandover[]" style="padding:2px;width:200px;margin-bottom:115px;">';
      tr+='<?php echo fillSelectOptionHallmark($dbcon,$cid,$eid); ?>';   
      tr+='</select>';          
      tr+='</td>';
      tr+='<td>';
      tr+='<input type="text" name="htext[]" style="width:180px;margin-bottom:115px;padding:2px">';
      tr+='</td>';
      tr+='<td>';
      tr+='<input type="text" name="htext2[]" style="width:180px;margin-bottom:115px;padding:2px">';
      tr+='</td>';
      tr+='<td>';
      tr+='<input type="datetime-local" name="hDate[]" style="width:200px;margin-bottom: 115px;">';
      tr+='</td>';
      tr+='<td>';
      tr+='<div id="signature-pad-staff">';
      tr+='<div style="border:solid 2px black; width:300px;height:110px;padding:3px;position:relative;">';
      tr+='<div class="note_staff_handover" onmouseover="staffSign()" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>';
      tr+='<canvas class="the_canvas_staff_handover" width="290px" height="95px"></canvas>';
      tr+='</div>';
      tr+='<div class="d-flex justify-content-between" style="margin:10px;">';
      tr+='<input type="hidden" name="signature_staff_handover[]" class="signature_staff_handover">';
      tr+='<button type="button" class="clear_btn_staff_handover" class="p-5"> Löschen</button>';
      tr+='<button type="button" class="save_btn_staff_handover" class="p-5">Speichern</button>'; 
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
      tr+='<button type="button" class="clear_btn_employee_handover" class="p-5" data-action="clearemployeeSign"> Löschen</button>';
      tr+='<button type="button" class="save_btn_employee_handover" class="p-5" data-action="save-png">Speichern</button>'; 
      tr+='</div>';
      tr+='</div>';
      tr+='<div class="signatureemployeeWrapper" style="display:none;border:solid 2px black;width:360px;height:110px;padding:3px;">';
      tr+='<img src="" style="width:100%;" class="signatureemployeeimage">';
      tr+='</div>';
      tr+='</td>';
      tr+='<td>';
	  tr+='<!--<a href="javascript:void(0)" class="bg-orange text-light p-5 d-inline-block cur-pointer ml-20 text-decoration">Handover protocol UTA fuel card</a>-->';
	  tr+='</a>';
	  tr+='</td>';
      tr+='</tr>';
      tbody.insertRow().innerHTML=tr;  
       signaturestaff();
       signatureEmployee();      
}
function AddreturnUTA(){
    var tbody=getID('createnewReturnUTA');
    var tr='<tr class="mt-50">';
      tr+='<td>';
      tr+='<input type="hidden" name="rutaid[]" value="">';
      tr+='<select name="hallmarkreturn[]" style="padding:2px;width:200px;margin-bottom:115px;">';
      tr+='<?php echo fillSelectReturnOptionHallmark($dbcon,$cid,$eid,'uta_fuel_card_handover'); ?>';   
      tr+='</select>';          
      tr+='</td>';
      tr+='<td>';
      tr+='<input type="text" name="rtext[]" style="width:180px;margin-bottom:115px;padding:3px;">';
      tr+='</td>';
      tr+='<td>';
      tr+='<input type="text" name="rtext2[]" style="width:180px;margin-bottom:115px;padding:3px;">';
      tr+='</td>';
      tr+='<td>';
      tr+='<input type="datetime-local" name="rDate[]" style="width:200px;margin-bottom: 115px;padding:3px;">';
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
      tr+='<td>';
	  tr+='<!--<a class="bg-orange text-light p-5 d-inline-block cur-pointer">Return protocol UTA Tankkarte</a>-->';
	  tr+='</a>';
	  tr+='</td>';
      tr+='</tr>';
      tbody.insertRow().innerHTML=tr; 
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
  formdata.append("getCompany","uta_fuel_card_handover");
  formdata.append("cid",cid);
  formdata.append("eid",eid);
  fetch("../../ajax/ajax_employee.php",{
    method:"POST",
    body:formdata
  }).then(res=>res.json())
  .then((data)=>{
    let company='';
    if(data[0] == 'empty'){
      console.log("empty");
    }else{
      for(var i in data){
         company+= `<a href="../print/UtaHistory.php?cid='${data[i].company_id}&eid=${eid}">${data[i].company_name}</p>`;
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
 signaturestaff();
 signatureEmployee(); 
 signatureEmployeeReturn();
 signaturestaffReturn();  
    </script>
	 <?php include "common/footer.php" ?>
</body>
</html>