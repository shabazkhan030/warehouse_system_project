<?php
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../../index.php");
}
if(!isset($_SESSION["company"]["cid"])){
	header("location:../company.php");
}
$eid=intval(base64_decode($_GET["eid"])/77777);
if(!isset($_GET["eid"]) || $eid == 0){
   header("location:employee.php");
}

$cid=base64_decode($_SESSION["company"]["cid"])/99999;
include "../../dbcon/dbcon.php";
include "../../func/functions.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fahrzeugübergabeprotokoll erstellen</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
	<link rel="stylesheet" type="text/css" href="../../css/company.css">
</head>
<body>
	<div id="toast"></div>
   <?php include "common/headtop.php"; ?>
   <?php include "common/navbar.php"; ?>
   
   	<div class="my-100">
<ul class="d-flex  bg-sub-nav list-style-none p-0">
<li><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
<li><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
<li><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
<li><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
<li class="active-profile-0"><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
<li><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
</ul>
</div>
	
	<div class="my-100 mt-20 mb-20">
  <div class="d-flex">
    <div class="oben1"><?php include "common/employee_name.php"; ?></div>
    <div class="oben1"></div>
    <div class="oben1 obenr">
      <div><small class="d-inline-block text-light p-10 bg-lightblue">Fahrzeugprotokol</small>
				<small class="d-inline-block text-light p-10 bg-red">Übergabeprotokoll erstellen</small></div>

    </div>
  </div>
</div>
   
  <!-- <?php include "common/employee_name.php"; ?>-->
	<form id="formCreateVehicleHandover">
	<div class="my-100" style="display:flex">
		<!--<div class="w-20">
            <ul class="bg-light list-style-none d-block p-0">
            	<li class="p-10"><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
            	<li class="p-10"><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
            	<li class="p-10"><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
            	<li class="p-10"><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
            	<li class="active-profile p-10"><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
            	<li class="p-10"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
            </ul>
		</div>-->
		<div class="w-50">		
			<!--<div class="ml-50">
				<a class="d-inline-block text-light p-5 bg-lightblue">Fahrzeugprotokol</a>
				<a class="d-inline-block text-light p-5 bg-grey">Fahrzeugübergabeprotokoll erstellen</a>
			</div>-->
			<div class="ml-10 mt-20 d-flex">
              <div class="d-block mt-20 ml-10">
               	   <input type="hidden" name="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
               	   <input type="hidden" name="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
               	   <input type="hidden" name="eid" value="<?php echo $_GET["eid"]; ?>">
               	   <label>Kennzeichen</label><br>
                   <select name="hallmark" onChange="selectedHallmark(event);">
                     <?php 	
	                     $qry2=mysqli_query($dbcon,"SELECT id,hallmark FROM vehicle_list WHERE avaibility='1' AND cid='$cid' AND status='1'");
	                     if($qry2){
	                     	$rows2=mysqli_num_rows($qry2);
	                     	  echo '<option value="0">Kennzeichen wählen</option>';
	                     	if($rows2 > 0){
	                     		while($arr2=mysqli_fetch_assoc($qry2)){
	                    	      ?>
                     <option value="<?php echo $arr2["id"];?>"><?php echo $arr2["hallmark"]; ?></option>
                     <?php			
	                     		}
	                     	}
	                     }
               	   ?>
                   </select>
</div>
                <div class="d-block mt-20 ml-10">
               	<label>Fahrzeug Art</label><br>
					   <select name="vtype" id="vtype">
					   	   <option value="PKW">PKW</option>
					   	   <option value="LKW">LKW</option>
					   </select>
               </div>
               <div class="d-block mt-20 ml-10">
               	   <label>Fahrzeugname</label><br>
				         <input type="text" name="carname" id="carname">
               </div>
               <div class="d-block mt-20 ml-10">
               	   <label>Km Stand</label><br>
				         <input type="text" name="kms" id="kms">
               </div>
               <div class="d-block mt-20 ml-10">
               	   <label>Datum &Uuml;bergabe</label>
               	   <br>
				         <input type="datetime-local" name="returndate">
               </div>
               <div class="d-block mt-20 ml-10">
               	   <label>Anzahl Schlüssel</label><br>
				         <input type="text" name="keys" id="keys">
               </div>
               <div id="text-fields" class="d-flex">
               </div>
			</div>
	      <div class="d-block mr-20 ml-20 mb-20 mt-20">
		 	    <div class="d-flex">
			 	    	<div class="d-block">
			            <label class="font-weight-bold">Neues Feld</label><br>
			            <input type="text" id="fieldname" placeholder="Bitte Boxnaname eingeben bei bedarf">				 	    		
			 	    	</div>
		 	    </div>
		 	    <div class="mt-20 mr-20 ml-10">
		 	         <a href="javascript:void(0)" class="btn bg-green create-fields">Neues Feld +</a>
		       </div>
		   </div>
			<div>
				<table class="table-light">
					<thead>
						<tr>
							<td>&nbsp;</td>
							<td>Ja</td>
							<td>Nein</td>
							<td>Nicht benötigt</td>
						</tr>
					</thead>
					<tbody id="vehicleListTableCheckbox">
					</tbody>
				</table>
			</div>
			<div class="w-100 mt-50" id="comments">
		   </div>
			<div class="mt-50">
				<div id="commentsSection"></div>
			</div>
			<div class="mt-20 mr-20">
		  	   <label class="font-weight-bold">Kommentarnamen erstellen</label><br>
		  	   <input type="text" id="comLabel"><br><br>
				<a href="javascript:void(0)" class="btn bg-green" onclick="addMoreComments()">Komentarfeld erstellen +</a>
			</div>
		</div>
		<div class="w-50">
			<!--<div class="d-inline-block p-5 ml-20 bg-orange text-light">
				
			</div>-->
         <div class="d-flex mt-50">
				<div class="d-block mb-20  mr-20">
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
				<div class="d-block mb-20">
				  <label class="font-weight-bold">Unterschrift Fahrzeugnutzer</label><br>
				   <div id="signature-pad-vh_mngr">
				     <div style="border:solid 2px black; width:360px;height:110px;padding:3px;position:relative;">
				        <div id="note_vh_mngr" onmouseover="vehicleManager();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
				        <canvas id="the_canvas_vh_mngr" width="350px" height="100px"></canvas>
				     </div>
				     <div class="d-flex justify-content-between" style="margin:10px;">
				        <input type="hidden" id="signature_vh_mngr" name="signature_vh_mngr">
				        <button type="button" id="clear_btn_vh_mngr" class="p-5" data-action="clearvehicleMngrSign"> Löschen</button>
				        <button type="button" id="save_btn_vh_mngr" class="p-5" data-action="save-png">Speichern</button> 
				     </div>
				   </div>			
				</div>
			</div>
			<div class="mt-50 ml-20">
				<table class="table-light w-100">
					<thead>
						<tr>
							<td>&nbsp;</td>
							<td>Ja</td>
							<td>Nein</td>
						</tr>
					</thead>
					<tbody id="handoverCheckbox2">
						<tr>
							<td>Privatnutzung des Fahrzeugs</td>
								<td>							
									<label class="tasks-list-item">
							           <input type="radio" name="puov" value="1" class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
								<td>	
									<label class="tasks-list-item">
							           <input type="radio" name="puov" checked value="0" class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
						</tr>
						<tr>
							<td>Fahrtenbuchmethode</td>
								<td>							
									<label class="tasks-list-item">
							           <input type="radio" name="lbm" value="1" class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
								<td>	
									<label class="tasks-list-item">
							           <input type="radio" name="lbm" checked value="0" class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>

						</tr>
						<tr>
							<td>Home office</td>
								<td>							
									<label class="tasks-list-item">
							           <input type="radio" name="hoff" value="1" class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
								<td>	
									<label class="tasks-list-item">
							           <input type="radio" name="hoff" checked value="0" class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
						</tr>
					</tbody>
				</table>
				 <input type="text" id="checkbox2" placeholder="Bitte Namen eingeben">
	          <a href="javascript:void(0)" class="d-inline-block text-decoration text-light p-10 cur-pointer bg-green" onclick="createcheckbox2()">Neues Feld anlegen +</a>
			</div>
			<div class="mt-50 ml-20">			
            <div id="subtext"></div>
            <div class="mt-20">
            	    <label class="font-weight-bold">Extra Feld Name</label><br>
            	    <input type="text" id="subtextvalue">     	
            </div><br>
            <a href="javascript:void(0)" class="btn bg-green" id="btnCreateSubtext">Extra Feld anlegen +</a>
			</div>
			<div class="mt-50 ml-20">
             <div id="display_images"></div>
			</div>
		</div>

	</div>
	<div class="my-100" style="display:flex">
		<div class="w-20"></div>
		
		<div class="w-40">
			<div class="w-100">
			</div>

		</div>
	</div>
	<div class="mt-100 text-center mb-50">
		  <input type="submit" class="d-inline-block p-10 cur-pointer bg-grey border-grey text-light" value="Alles Speichern" id="submitHandoverVLP" onclick="submitFromvehicleHandover()">
	</div>
	</form>
	<script src="../../js/functions.js"></script>
	<script src="../../js/signature.js"></script>
	<script src="../../js/vehicle_log.js"></script>
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
       function submitFromvehicleHandover(){
       	var formID=getID("formCreateVehicleHandover");
       	let mainLabel=[],mainValue=[],checkboxLabel=[],subtxtLabel=[],subtxtvalue=[],comLabel=[],comText=[];
       	queryAll(".fieldnamevalue").forEach((row,index)=>{
         	mainLabel.push(row.value);
         });
         queryAll(".textfield").forEach((row,index)=>{
         	mainValue.push(row.value);
         });
         queryAll(".commentLabel").forEach((row,index)=>{
         	comLabel.push(row.value);
         });
         queryAll(".commentText").forEach((row,index)=>{
         	comText.push(row.value);
         });
         queryAll(".subtextlabel").forEach((row,index)=>{
         	subtxtLabel.push(row.value);
         });
         queryAll(".text1").forEach((row,index)=>{
         	subtxtvalue.push(row.value);
         });
         queryAll(".checkboxLabel").forEach((row,index)=>{
         	checkboxLabel.push(row.value);
         });
       	formID.addEventListener("submit",function(e){
       		e.preventDefault();
       		e.stopImmediatePropagation();
       		getID("submitHandoverVLP").disabled=true;
       		formdata=new FormData(this);
       		formdata.append("createvehicleHandover",1);
       		formdata.append("mainLabel",JSON.stringify(mainLabel));
       		formdata.append("mainValue",JSON.stringify(mainValue));
       		formdata.append("comLabel",JSON.stringify(comLabel));
       		formdata.append("comText",JSON.stringify(comText));
       		formdata.append("subtxtLabel",JSON.stringify(subtxtLabel));
       		formdata.append("subtxtvalue",JSON.stringify(subtxtvalue));
       		formdata.append("checkboxLabel",JSON.stringify(checkboxLabel));
       		fetch("../../ajax/ajax_employee.php",{
	         	method:"POST",
	         	body:formdata
	         }).then((res)=>{
	         	return res.json();
	         }).then((data)=>{
	         	if(data.status == 1){
					   myToast(data.status,data.message);
					   setTimeout(function(){ 
					   	window.location.href="edit_vehicle_handover_protocol.php?eid=<?php echo $_GET["eid"]; ?>&hm="+data.hm+"&vlid="+data.vlid; 
					   }, 2000);
					}else{
					   myToast(data.status,data.message);
					   getID("submitHandoverVLP").disabled=false;
					}
	         })
       	}) 
       }

       function selectedHallmark(event){
       	let vid=event.target.value;
       	let formdata=new FormData();
       	formdata.append("fetch_vehicle_list",1);
       	formdata.append("vid",vid);
       	fetch("../../ajax/ajax_employee.php",{
	         	method:"POST",
	         	body:formdata
	         }).then((res)=>{
	         	return res.json();
	         }).then((data)=>{
               getID("vtype").value=data[0].vehicle_type;
               getID("kms").value=data[0].mileage_original;
               getID("keys").value=data[0].number_of_keys;
               getID("carname").value=data[0].car_name;
               let vehicle_list_checbox='';
               for(var s in data[0].vehicle_checkbox){
               	value=data[0].vehicle_checkbox[s].label_value;
               	vehicle_list_checbox+=`<tr>
							 <td><p>${data[0].vehicle_checkbox[s].label_name}</p><input type="hidden" class="checkboxLabel" value="${data[0].vehicle_checkbox[s].label_name}"</td>
							 <td>							
								 <label class="tasks-list-item">
							           <input type="radio" name="${data[0].vehicle_checkbox[s].label_name}" value="1" ${(value== 1 ) ? "checked" : "" }  class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
							 <td>	
								 <label class="tasks-list-item">
							           <input type="radio" name="${data[0].vehicle_checkbox[s].label_name}" value="0" ${(value==0) ? "checked" : "" }  class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
							 <td>	
								 <label class="tasks-list-item">
							           <input type="radio" name="${data[0].vehicle_checkbox[s].label_name}" value="2" ${(value==2) ? "checked" : "" } class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
							 </tr>`;
               } 
               getID("vehicleListTableCheckbox").innerHTML=vehicle_list_checbox;
               // let vehicle_list_comment='';
               // for(var j in data[0].comments){
               // 	vehicle_list_comment+=`<div class="d-block mt-20 mr-20">
				       //          <label class="font-weight-bold">${data[0].comments[j].com_label}</label><br>
				       //            <input type="hidden"  value="${data[0].comments[j].com_label}">
				       //            <textarea rows="5" class="w-100" >${data[0].comments[j].com_text}</textarea>
			         //           </div>`;
               // } 
               // getID("comments" ).innerHTML=vehicle_list_comment;
              
               let vehicle_list_subtext='';
               for(var s in data[0].subtext){
               	vehicle_list_subtext+=`<div class="d-block mt-20">
					         <label>${data[0].subtext[s].textlabel}</label><br>
					         <input type="text" value="${data[0].subtext[s].textvalue}">
					         <input type="hidden" value="${data[0].subtext[s].textlabel}">
					    </div>`;
               } 
               getID("subtext").innerHTML=vehicle_list_subtext;
               let output_vehicle_image='';
               for(var i in data[0].vehicle_images){
               	console.log(data[0].vehicle_images[i].vehicle_image);
               	output_vehicle_image+=`<img src='../../img/vehicle_uploads/${data[0].vehicle_images[i].vehicle_image}' style="width:300px;">`;
               }
               getID("display_images").innerHTML=output_vehicle_image;
	         })
       }
// SIGNATURE======
signatureFleetManager();
signatureVehicleManager();
	</script>
 <?php include "common/footer.php" ?>
</body>
</html>