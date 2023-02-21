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
	<title>Führerschein Prüfung anlegen</title>
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
            	<li><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
            	<li><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
            	<li class="active-profile-0"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
</ul>
</div>
	
	<div class="my-100 mt-20 mb-20">
  <div class="d-flex">
    <div class="oben1"><?php include "common/employee_name.php"; ?></div>
    <div class="oben1 obenm">Führerschein Prüfung anlegen</div>
    <div class="oben1 obenr">
      <div>
	
			
</div>
    </div>
  </div>
</div>
	
	
  <!--  <?php include "common/employee_name.php"; ?>-->
<form id="driverLicenseCreateForm">
	<div class="my-100" style="display:flex">
		<!--<div class="w-20">
            <ul class="bg-light list-style-none d-block p-0">
            	<li class="p-10"><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
            	<li class="p-10"><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
            	<li class="p-10"><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
            	<li class="p-10"><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
            	<li class="p-10"><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
            	<li class="active-profile p-10"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
            </ul>
		</div>-->
		<div class="w-100">		
			<div class="ml-50">
				<input type="hidden" name="eid" value="<?= $_GET["eid"]?>">
				<input type="hidden" name="cid" value="<?= $_SESSION["company"]["cid"]?>">
				<input type="hidden" name="pid" value="<?= $_SESSION["isLogedIn"]?>">
				<!--<small href="javascript:void(0)" class="btn-sm bg-lightblue">Führerschein Prüfung anlegen</small>-->
			</div>
			
			<div class="w-100 ml-20 mr-20 mt-20">
			<div>2.)	Angaben zum Führerschein / zur Fahrerlaubnis</div>
				<div class="d-flex ml-20 mr-20" id="checkboxIcon">
<?php
$qryIcon=mysqli_query($dbcon,"SELECT * FROM driver_license_icon_checkbox WHERE view='1' AND cid='$cid' GROUP BY label ORDER BY sort_by ASC");
while($arrIcon=mysqli_fetch_assoc($qryIcon)){
?>
					<div style="display:flex;padding:20px 40px">
	       		     <label class="tasks-list-item" style="display:flex">
			           <input type="checkbox" class="tasks-list-cb iconCheckbox">
			           <span class="tasks-list-mark"></span>
			           <span class="tasks-list-desc" style="margin-right:10px;margin-top:7px;">
			           	    <p><?= $arrIcon["label"] ?></p>
			           	    <input type="hidden" value="<?= $arrIcon["label"] ?>" class="label" style="width:50px;height:25px;margin-top:-10px;padding:1px">
			           	    <input type="hidden" value="<?= $arrIcon["sort_by"] ?>" class="sort_by">
			           </span>
			           </label>
			           <span class="d-flex">
                           <?php if(empty($arrIcon["icon"])){
                           ?>
                            <div class="upload-btn-wrapper">
							  <button class="customBtn">&#43;</button>
							  <input type="file" onchange="newUploadIcon(this)"/>
							</div>
						   <img src="" style="display:none;width:25px;height:25px;">
						   <a class="cancel-btn cur-ponter iconCancel" data-delete='0' onclick="return myToast(0,'Sorry you cannot delete this while creating..')" style="display:none;margin-left:10px;width:0px;height:15px;"></a>
                           <?php
                           }else{
                           ?>
                           <div></div>
                           <img src="<?= $arrIcon["icon"] ?>" style="width:25px;height:25px;">
                           <a class="cancel-btn cur-ponter iconCancel" data-delete='<?= $arrIcon["id"] ?>' onclick="return myToast(0,'Sorry you cannot delete this while creating..')" style="display:none;margin-left:10px;width:0px;height:15px;"></a>
                           <?php
                           } 
                           ?>
			           	   <select style="display:none;width:70px;padding:2px;height:25px;margin-left:8px;margin-top:3px;" class="view">
			           	   	   <option value="0" <?php if($arrIcon["view"] == 0){ ?> selected <?php } ?>>deaktiviert</option>
			           	   	   <option value="1" <?php if($arrIcon["view"] == 1){ ?> selected <?php } ?>>aktiv</option>
			           	   </select>
			           </span>
			           <input type="hidden" value="<?= $arrIcon["icon"] ?>" class="icon">
			        </div>

<?php
}
?>					
				</div>
				<div class="d-flex border-2 p-5 mt-20" id="create-icon-checkbox" style="display:none;width:80%" >
					<div class="ml-20">
						<label>Name</label><br>
					    <input type="text" id="iconlabelName" style="width:100px">
					</div>
					<div class="ml-20">
						<label>icon</label><br>
					    <input type="file" id="iconFilename" style="width:180px;">
					    <input type="hidden" id="iconimage">
					</div>
					<div class="ml-20">
						<label style="margin-left:25px;">Für alle kommende Führerscheinprüfung</label><br>
						<label class="tasks-list-item" style="display:flex;padding:20px 40px">
			             <input type="checkbox" class="tasks-list-cb" id="viewAll">
			             <span class="tasks-list-mark"></span>
			         </label>
					</div>
					<div>
						<br><br>
						<a href="javascript:void(0)" class="btn-sm bg-green mt-20" onclick="addIconCheckbox()">hinzufügen</a>
					</div>
					<div>
						<br><br>
						<a href="javascript:void(0)" class="btn-sm bg-red mt-20" onclick="removeIconCheckboxCreate()">zurück</a>
					</div>
				</div>
				<div class="mt-20 mb-20 d-flex">
					<a href="javascript:void(0)" class="btn-sm bg-lightblue" onclick="EditIconCheckbox(this)">Bearbeiten</a>
					<a href="javascript:void(0)" class="btn-sm bg-green" onclick="SaveIconCheckbox(this)" style="display:none">Speichern</a>
					<a href="javascript:void(0)" class="btn-sm bg-green" onclick="createIconCheckbox()">Neues Feld +</a>
				</div>
				<div>
					<div class="mt-50">
						   <div id="commentsSection"></div>
				  </div>
				  <div class="mt-20 mr-20">
				  	   <label class="font-weight-bold">Kommentarnamen erstellen</label><br>
				  	   <input type="text" id="comLabel"><br><br>
						   <a href="javascript:void(0)" class="btn bg-green" onclick="addMoreComments()">Neues Feld +</a>
				  </div>
				</div>
				<div class="d-flex mt-20">
					<div class="w-100">
							<table class="table-light w-100">
								<thead>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>JA</td>
										<td>Nein</td>
										<td>Action</td>
										<td>Status</td>
									</tr>
								</thead>
								<tbody id="vehicleListTableCheckbox">
<?php
$qryct=mysqli_query($dbcon,"SELECT * FROM driver_license_checkbox WHERE cid='$cid' AND view='1' GROUP BY labelname");
if($qryct){
	while($arrct=mysqli_fetch_assoc($qryct)){
?>
        <tr>
		      <td style="padding:0px;">
		         	  <p><?= $arrct["labelname"]; ?></p>
		         	  <!-- name="labelname[]" -->
		         	  <input type="hidden" value="<?= $arrct["labelname"]; ?>" class="cb_labelname"></td>
		         <!-- name="textname[]" -->
		      <td style="padding:0px;"><input type="text" class="cb_textname" value=""></td>
				  <td style="padding:0px;">							
						<label class="tasks-list-item">
				           <input type="checkbox" class="tasks-list-cb cb_checkbox cb_yes">
				           <span class="tasks-list-mark"></span>
				           <!-- <input type="checkbox" class="tasks-list-cb iconCheckbox"> -->
				    </label> 	
				  </td>
				  <td style="padding:0px;">
						<label class="tasks-list-item">
					        <input type="checkbox" class="tasks-list-cb cb_checkbox cb_no" checked="true">
					        <span class="tasks-list-mark"></span>
					  </label>
				  </td>
				  <td style="padding:0px;">
				 	 <div class="d-flex">	
				 	      <a href="javascript:void(0)" class="d-inline-block bg-green text-light p-5 text-decoration" data-id="<?= $arrct["id"]; ?>" onclick="EditCheckbox2(this)">Bearbeiten</a>
				 	      <a href="javascript:void(0)" class="d-inline-block bg-green text-light bg-blue p-5 text-decoration ml-10" style="display:none" onclick="saveCheckbox2(this)">Speichern</a>
				 	 </div>
				  </td>
				  <td style="padding:0px;">
				  	<!-- name="showAll[]"  -->
				 	  <select class="cb_showall" style="width:80px;padding:3px;">
				 	     <?php  if($arrct["view"] == 1){
				 	     	echo '<option value="1" selected>Aktiv</option>
				 	     	      <option value="0">Disabled</option>';
				 	     }else{
				 	     	echo '<option value="1">Aktiv</option>
				 	     	      <option value="0" selected>Disabled</option>';
				 	     }
				 	     ?>
				 	  </select>
				   </td>	
			</tr>
<?php
	}
}
?>
								</tbody>
							</table>
							<div class="d-flex mt-20">
								 <div class="d-block">
								 	    <p>Feldname:</p>
								      <input type="text" id="textName">
								 </div>
								 <div class="d-block ml-10">
								 	   <p>Inhalt:</p>
								      <input type="text" id="labelname">
								 </div>
			                <div class="d-block ml-20 text-center">
			                <label>Für alle kommende Führerscheinprüfung</label><br><br>
							    <label class="tasks-list-item" style="margin-top:-10px;">
							          <input type="checkbox" id="viewtoall" value="1" class="tasks-list-cb">
							          <span class="tasks-list-mark"></span>
							    </label>                  	
			                </div>
							</div>
							<div class="mt-20 mr-20">
								<a href="javascript:void(0)" class="btn bg-green" onclick="addMoreRow()">Neues Feld +</a>
							</div>
					</div>
			   </div>
			   <div class="d-flex">
			   <div class="w-60">
			   	<div class="mt-50">
				   	<div class="mt-50">
						<div class="d-flex">
							 <p class="font-weight-bold mt-10">Der vorstehend näher bezeichnete Führerschein wurde vom Fuhrparkleiter / Beauftragten des Unternehmens,:</p><input type="hidden" value="Der vorstehend näher bezeichnete Führerschein wurde vom Fuhrparkleiter / Beauftragten des Unternehmens,:" class="m-0" name="label1"> 
					         <a href="javascript:void(0)" class="bg-lightblue p-10 text-light text-decoration ml-10" onclick="mainTextLabelEdit(this)">Bearbeiten</a>
					         <a href="javascript:void(0)" class="bg-green p-10 text-light text-decoration ml-10" onclick="mainTextLabelSave(this)" style="display:none">Speichern</a>
						</div>
					   <div style="display:flex;">
					   	<div class="w-50" style="padding:10px;">
							   	<label>Herr / Frau / Name </label><br>
							   	<input type="text" name="nameField" style="width:100%">
						   </div>
						   <div class="w-50" style="padding:10px;">
							   	<label>Datum</label>
							   	<br>
							   	<input type="datetime-local" name="dateField" style="width:100%">
						   </div>
					   </div>
					</div>
					</div>
					<div class="mt-50">
						<div class="d-flex">
								<p class="font-weight-bold mt-10">3.)	Dokumentation des Fuhrparkmanagements: Bei der erstmaligen Kontrolle des Führerscheins soll eine Fotokopie der Fahrerlaubnis (des Original-Führerscheins) angefertigt werden. Bei der folgenden Kontrolle wird der Führerschein mit dieser Kopie verglichen.
Eine Fotokopie des Führerscheins wurde zu den Akten des Fuhrparkmanagements genommen: </p><input type="hidden" name="label2" class="m-0" value="3.)	Dokumentation des Fuhrparkmanagements: Bei der erstmaligen Kontrolle des Führerscheins soll eine Fotokopie der Fahrerlaubnis (des Original-Führerscheins) angefertigt werden. Bei der folgenden Kontrolle wird der Führerschein mit dieser Kopie verglichen.
Eine Fotokopie des Führerscheins wurde zu den Akten des Fuhrparkmanagements genommen: ">
					         <a href="javascript:void(0)" class="bg-lightblue p-10 text-light text-decoration ml-10" onclick="mainTextLabelEdit(this)">Bearbeiten</a>
					         <a href="javascript:void(0)" class="bg-green p-10 text-light text-decoration ml-10" onclick="mainTextLabelSave(this)" style="display:none">Speichern</a>
						</div>
					   <div class="d-flex mt-20">
					   	<div class="w-50">
						   	<label class="tasks-list-item">
					            <input type="radio" name="checkbox2" value="1" class="tasks-list-cb">
					            <span class="tasks-list-mark"></span>
					            <span class="tasks-list-desc">JA</span>
					        </label>
						   </div>
						   <div class="w-50">
						   	<label class="tasks-list-item">
					            <input type="radio" name="checkbox2" value="0" class="tasks-list-cb" checked>
					            <span class="tasks-list-mark"></span>
					            <span class="tasks-list-desc">NEIN</span>
					        </label>
						   </div>
					   </div>
					</div>
					
					
					<div class="">
					
					  
					   <div class="mt-20 mr-20">
					  	   <label class="font-weight-bold">4.)	Der Fahrer wird im Führerschein eingetragene Auflagen oder Beschränkungen beachten. Er bestätigt mit seiner Unterschrift, über die Bestimmungen aus StVG, StVO und StVZO belehrt worden zu sein.</label><br>
					  	</div>   
					 
			   </div>
					
					
					
					<div class="">
					   <div class="mt-50">
							<div id="commentsSection2"></div>
					   </div>
					   <div class="mt-20 mr-20">
					  	   <label class="font-weight-bold">Kommentarnamen erstellen</label><br>
					  	   <input type="text" id="comLabel2"><br><br>
							<a href="javascript:void(0)" class="btn bg-green" onclick="addMoreComments2()">Neues Feld +</a>
					   </div>
					</div>
			   </div>
			   <div class="w-40">
		   		<div class="mt-20 p-20" style="margin-left:50px;">
		               <div style="display:block;width:300px;border:1px;">
	      	               <div style="display:flex">
	                          <img src="../../img/icon/preview_image.jpg" style="width:250px;">
	                          <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-20" aria-label="Close"  onclick="removeVehicleSrc(this)"></a>	 
	                      </div>
					      <input type="file" name="DLimg[]" onchange="showPreview(this,event);"><br><br>
					   </div>
					   <div id="carimage">
					   </div>
					   <div class="mt-20">
						   <a href="javascript:void(0)" class="btn bg-green" id="btnCreateImageInput">Neues Bild +</a>
					   </div>	
					</div>
			   </div>
			</div>
			</div>
		</div>
		<!--<div class="w-15" style="margin-top:-50px;">-->
		</div>
	
   <div class="my-100" style="display:flex">	
	   <div class="w-20"></div>
		<div class="w-80">
			<div class="mt-50">
				<div class="d-flex">
					<div class="w-80 d-flex">
			         <div class="d-flex mt-50">
							<div class="d-block">
							  <label class="font-weight-bold">Unterschrift Fuhrparkleiter</label><br>
							   <div id="signature-pad-fleet-mngr">
							     <div style="border:solid 2px black; width:360px;height:110px;padding:3px;position:relative;"> 
							        <div id="note_fleet_mngr" onmouseover="fleetManager();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
							        <canvas id="the_canvas_fleet_mngr" width="350px" height="100px"></canvas>
							     </div>
							     <div class="d-flex justify-content-between" style="margin:10px;">
							        <input type="hidden" id="signature_fleet_mngr" name="signature_fleet_mngr">
							        <button type="button" id="clear_btn_fleet_mngr" class="p-5" data-action="clearFleetMngrSign"> Löschen</button>
							        <button type="button" href="javascript:void(0)" id="save_btn_fleet_mngr" class="p-5" data-action="save-png">Speichern</button> 
							     </div>
							   </div>				
							</div>
							<div class="d-block ml-10">
							  <label class="font-weight-bold">Unterschrift Mitarbeiter</label><br>
							   <div id="signature-pad-vh_user">
							     <div style="border:solid 2px black; width:360px;height:110px;padding:3px;position:relative;">
							        <div id="note_vh_mngr" onmouseover="vehicleUser();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
							        <canvas id="the_canvas_vh_user" width="350px" height="100px"></canvas>
							     </div>
							     <div class="d-flex justify-content-between" style="margin:10px;">
							        <input type="hidden" id="signature_vh_user" name="signature_vh_user">
							        <button type="button" id="clear_btn_vh_user" class="p-5" data-action="clearvehicleMngrSign"> Löschen</button>
							        <button type="button" id="save_btn_vh_user" class="p-5" data-action="save-png">Speichern</button> 
							     </div>
							   </div>			
							</div>
						</div>
					</div>
					<div class="w-20">
						  <input type="submit" class="btn-sm bg-grey cur-pointer" id="submitBtn" onclick="submitDriverLicense()" value="Alles Speichern">
					</div>
				</div>
			</div>
		</div>
   </div>
   
</form>
	    <script src="../../js/functions.js"></script>
	    <script src="../../js/signature.js"></script>
	    <script src="../../js/driver_license.js"></script>
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
       
btnCreateImageInput=getID("btnCreateImageInput");
btnCreateImageInput.addEventListener("click",function(){
	// console.log("hello");
 let html=`<div style="display:block;width:300px;border:1px;">
               <div style="display:flex">
                    <img src="../../img/icon/preview_image.jpg" style="width:250px;">
                    <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-20" aria-label="Close" onclick="removeVehicleSrc(this)"></a>	 
                </div>
                <input type="file" name="DLimg[]" onchange="showPreview(this,event);">
            </div><br><br>`;	
 let img= getID("carimage");
     img.innerHTML+=html;
});

// SIGNATURE======
signatureFleetManager();
signatureVehicleUser();
	</script>
	 <?php include "common/footer.php" ?>
</body>
</html>