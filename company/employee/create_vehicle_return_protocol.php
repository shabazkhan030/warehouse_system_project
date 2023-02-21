<?php
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../../index.php");
}
if(!isset($_SESSION["company"]["cid"])){
	header("location:../company.php");
}
$cid=intval(base64_decode($_SESSION["company"]["cid"])/99999);
$eid=intval(base64_decode($_GET["eid"])/77777);
if(!isset($_GET["eid"]) || $eid == 0){
   header("location:employee.php");
}
include "../../dbcon/dbcon.php";
include "../../func/functions.php";
if(!isset($_GET["hm"])){
   header("location:employee.php");
}
$hm=$_GET["hm"];
$vlid=$_GET["vlid"];
$created_id=$_GET["crid"];
$qry=mysqli_query($dbcon,"SELECT * FROM vehicle_log_protocol WHERE hallmark='$hm' AND id='$vlid' AND created_id='$created_id'");
$arr=mysqli_fetch_assoc($qry);
$stand=$arr["stand"];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fahrzeugübergabeprotokoll</title>
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
				<small class="d-inline-block text-light p-10 bg-red">Rückgabeprotokoll erstellen</small></div>

    </div>
  </div>
</div>
	
	
	
    <!--<?php include "common/employee_name.php"; ?>-->
   <form id="saveVehicleLogReturnForm">
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
				<small class="d-inline-block text-light p-5 bg-lightblue">Fahrzeugprotokol</small>
				<small class="d-inline-block text-light p-5 bg-grey">Fahrzeugrückgabeprotokoll</small>

			</div>-->
			<div class="ml-10 mt-20 d-flex">
               <div class="d-block mt-20 ml-10">
               	   <input type="hidden" name="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
               	   <input type="hidden" name="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
               	   <input type="hidden" name="eid" value="<?php echo $_GET["eid"]; ?>">
               	   <input type="hidden" name="crid" value="<?php echo $_GET["crid"]; ?>">
               	   <label>Kennzeichen</label><br>

               	   <select name="hallmark">
                         <?php 	
	                    
                         $qry2=mysqli_query($dbcon,"SELECT vl.hallmark AS hallname,vl.id,vlp.hallmark FROM vehicle_log_protocol vlp INNER JOIN vehicle_list vl ON vlp.hallmark=vl.id WHERE vl.cid='$cid' AND vlp.created_id='$created_id' AND vlp.stand='$stand'");

	                     if($qry2){
	                     	$rows2=mysqli_num_rows($qry2);
	                     	if($rows2 > 0){
	                     		while($arr2=mysqli_fetch_assoc($qry2)){
	                    	      ?>
	                    	        <option value="<?php echo $arr2["id"];?>"><?php echo $arr2["hallname"]; ?></option>
	                     		<?php			
	                     		}
	                     	}
	                     }else{
	                     	?>
                              <option value="0">select option</option> 
	                     	<?php
	                     }
               	   ?>
               	   </select>
               </div>
                <div class="d-block mt-20 ml-10">
               	<label>Fahrzeug Art</label><br>
					   <select name="vtype">
					   	   <option value="PKW" <?php if($arr["vehicle_type"]== "PKW"){ ?> selected <?php } ?>>PKW</option>
					   	   <option value="LKW" <?php if($arr["vehicle_type"]== "LKW"){ ?> selected <?php } ?>>LKW</option>
					   </select>
               </div>
               <div class="d-block mt-20 ml-10">
               	   <label>Fahrzeugname</label><br>
				         <input type="text" name="carname" value="<?= $arr["car_name"] ?>">
               </div>
               <div class="d-block mt-20 ml-10">
               	   <label>Km Stand</label><br>
				         <input type="text" name="kms" value="<?= $arr["kms"] ?>">
               </div>
               <div class="d-block mt-20 ml-10">
               	   <label>Datum Übergabe</label><br>
				          <input type="datetime-local" name="returndate">
               </div>
               <div class="d-block mt-20 ml-10">
               	   <label>Anzahl Schlüssel</label><br>
				         <input type="text" name="keys" value="<?= $arr["no_of_key"] ?>" >
               </div>
               <div id="text-fields" class="d-flex">
              <?php 
								$qryMainField=mysqli_query($dbcon,"SELECT * FROM vehicle_log_extra_field WHERE vlid='$vlid'");
								$main_rows=mysqli_num_rows($qryMainField);
								if($main_rows > 0){
									while($arrMainField=mysqli_fetch_assoc($qryMainField)){
									?>
									<div class="d-flex">
							          <div class="d-block ml-10 mt-20">
							              <label><?= $arrMainField["label_name"]; ?></label><br>
							              <input type="text" class="textfield w-160px" value="<?= $arrMainField["label_value"]; ?>">
							              <input type="hidden" class="fieldnamevalue" value="<?= $arrMainField["label_name"]; ?>">
							          </div>
							      </div>
			          <?php
			      	          }
											}
			          ?>
               </div><br>
			</div>
	      <div class="d-block mr-20 ml-20 mb-20 mt-20">
		 	    <div class="d-flex">
			 	    	<div class="d-block">
			            <label class="font-weight-bold">Neues Feld</label><br>
			            <input type="text" id="fieldname" placeholder="Bitte Boxnaname eingeben bei bedarf">				 	    		
			 	    	</div>
		 	    </div>
		 	    <div class="mt-20 mr-20 ">
		 	         <a href="javascript:void(0)" class="btn bg-green create-fields">Neues Feld +</a>
		      </div>
         </div>
			<div>
				<table class="table-light">
					<thead>
						<tr>
							<td>&nbsp;</td>
							<td>JA</td>
							<td>Nein</td>
							<td>Nicht benötigt</td>
						</tr>
					</thead>
					<tbody id="vehicleListTableCheckbox">
<?php
$qryCheckbox=mysqli_query($dbcon,"SELECT * FROM vehicle_log_checkbox_handover WHERE vlid='$vlid'");

while($arrCheckbox=mysqli_fetch_assoc($qryCheckbox)){
?><tr>
							 <td><p><?= $arrCheckbox["label_name"]; ?></p><input type="hidden" class="checkboxLabel" value="<?= $arrCheckbox["label_name"]; ?>"></td>
							 <td>							
								 <label class="tasks-list-item">
							           <input type="radio" name="<?= $arrCheckbox["label_name"]; ?>" value="1" <?php echo ($arrCheckbox["label_value"] == 1) ? "checked" : ""; ?>  class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
							 <td>	
								 <label class="tasks-list-item">
							           <input type="radio" name="<?= $arrCheckbox["label_name"]; ?>" value="0" <?php echo ($arrCheckbox["label_value"] == 0) ? "checked" : ""; ?> class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
							 <td>	
								 <label class="tasks-list-item">
							           <input type="radio" name="<?= $arrCheckbox["label_name"]; ?>" value="2" <?php echo ($arrCheckbox["label_value"] == 2) ? "checked" : ""; ?> class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
							 </tr>
       <?php
}
       ?>

					</tbody>
				</table>
			</div>

			<div class="mt-50">
				<div id="commentsSection" class="d-flex w-100">
<?php
  $qryComment=mysqli_query($dbcon,"SELECT * FROM vehicle_log_comment WHERE vlid='$vlid'");
  if($qryComment){
  	while($arrComment=mysqli_fetch_assoc($qryComment)){
  		?>
  		   <div class="d-block mt-20 mr-20 w-80">
				      <label class="font-weight-bold"><?= $arrComment["com_label"] ?></label><br>
				      <input type="hidden" class="commentLabel" value="<?= $arrComment["com_label"] ?>">
				      <textarea rows="5" class="w-100 commentText"><?= $arrComment["com_value"] ?></textarea>
			   </div>
			   <div class="d-block"><br><br>
		          <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50" data-delete="<?= $arrComment['id'] ?>"aria-label="Close" onclick="deletecomment(this);"></a>
		     </div>
<?php
  	}	
  }
 ?>
				</div>
			</div>
			<div class="mt-20 mr-20">
		  	   <label class="font-weight-bold">Kommentarnamen erstellen</label><br>
		  	   <input type="text" id="comLabel"><br><br>
				<a href="javascript:void(0)" class="btn bg-green" onclick="addMoreComments()">Neues Feld +</a>
			</div>
		</div>
		<div class="w-50">
			<!--<div class="d-inline-block p-5 ml-20 bg-orange text-light">
				text vehicle Return Protocol
			</div>-->
         <div class="d-flex mt-50 ml-20">

				<div class="d-block mb-20  mr-20">
				  <label class="font-weight-bold">Unterschrift Fuhrparkleiter</label><br>
				   <div id="signature-pad-fleet-mngr">
				     <div style="border:solid 2px black; width:400px;height:150px;padding:3px;position:relative;"> 
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
				     <div style="border:solid 2px black; width:400px;height:150px;padding:3px;position:relative;">
				        <div id="note_vh_mngr" onmouseover="vehicleManager();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
				        <canvas id="the_canvas_vh_mngr" width="350px" height="100px"></canvas>
				     </div>
				     <div class="d-flex justify-content-between" style="margin:10px;">
				        <input type="hidden" id="signature_vh_mngr" name="signature_vh_mngr">
				        <button type="button" id="clear_btn_vh_mngr" class="p-5" data-action="cleardirectorSign"> Löschen</button>
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
							<td>JA</td>
							<td>Nein</td>
						</tr>
					</thead>
					<tbody id="handoverCheckbox2">
<?php 
$qry3=mysqli_query($dbcon,"SELECT * FROM vehicle_log_checkbox2 WHERE  vlid='$vlid' AND stand='$stand'");
$arr3=mysqli_fetch_assoc($qry3);
?>
						<tr>
							<td>Privatnutzung des Fahrzeugs</td>
							<td>							
								<label class="tasks-list-item">
					           <input type="radio" name="puov" value="1" <?php if($arr3["private_use"]== "1"){ ?> checked <?php } ?> class="tasks-list-cb">
					           <span class="tasks-list-mark"></span>
						    </label>
						   </td>
							<td>	
								<label class="tasks-list-item">
						           <input type="radio" name="puov" value="0" <?php if($arr3["private_use"]== "0"){ ?> checked <?php } ?> class="tasks-list-cb">
						           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
							 <td></td>
						</tr>
						<tr>
							<td>Fahrtenbuchmethode</td>
								<td>							
									<label class="tasks-list-item">
							           <input type="radio" name="lbm" value="1" <?php if($arr3["log_book"]== "1"){ ?> checked <?php } ?> class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
								<td>	
									<label class="tasks-list-item">
							           <input type="radio" name="lbm" value="0" <?php if($arr3["log_book"]== "0"){ ?> checked <?php } ?> class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
							 <td></td>

						</tr>
						<tr>
							<td>Home office</td>
								<td>							
									<label class="tasks-list-item">
							           <input type="radio" name="hoff" value="1" <?php if($arr3["home_office"]== "1"){ ?> checked <?php } ?> class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							      </label>
							   </td>
								<td>	
									<label class="tasks-list-item">
							           <input type="radio" name="hoff" value="0" <?php if($arr3["home_office"]== "0"){ ?> checked <?php } ?> class="tasks-list-cb">
							           <span class="tasks-list-mark"></span>
							      </label>
							   </td>
							   <td></td>
						</tr>
<?php 
$qry5=mysqli_query($dbcon,"SELECT * FROM vehicle_log_checkbox2_extra WHERE vlid='$vlid' AND stand='$stand'");
$row5=mysqli_num_rows($qry5);
if($row5 > 0){
	while($arr5=mysqli_fetch_assoc($qry5)){
		?>
			 <tr>
			      <td>
			       <label><p><?= $arr5["checkbox_label"]; ?></p></label>
			       <input type="hidden" value="0" id="sel_chbx_2<?= $arr5["checkbox_label"]; ?>" name="chckbox2_slct_val[]">
			       <input type="hidden" value="<?= $arr5["checkbox_label"]; ?>" class="checkbox2" name="checkbox2[]">
			       </td>
			       <td>
			       <label class="tasks-list-item">
					 <input type="radio" name="<?= $arr5["checkbox_label"]; ?>" value="1" <?php if($arr5["checkbox_value"]== "1"){ ?> checked <?php } ?> class="tasks-list-cb sel_class_<?= $arr5["checkbox_label"]; ?>">
					 <span class="tasks-list-mark"></span>
					 </label>
			       </td>
			       <td>
			       <label class="tasks-list-item">
					 <input type="radio" name="<?= $arr5["checkbox_label"]; ?>" value="0" <?php if($arr5["checkbox_value"]== "0"){ ?> checked <?php } ?> class="tasks-list-cb sel_class_<?= $arr5["checkbox_label"]; ?>">
					 <span class="tasks-list-mark"></span>
					 </label>
			      </td>
			      <td>
			      		<div class="d-block">
		              <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50" data-delete="<?= $arr5['id'] ?>"aria-label="Close" onclick="deleteCheckbox2(this);"></a>
		            </div>
			      </td>
			 </tr>
        
		<?php
	}
}
 ?>
					</tbody>
				</table>
				<input type="text" id="checkbox2">
				<a href="javascript:void(0)" class="d-inline-block text-decoration text-light p-10 cur-pointer bg-green" onclick="createcheckbox2()">Neues Feld +</a>
			</div>
			<div class="mt-50 ml-20">
				  <div id="subtext">
<?php 
$qry4=mysqli_query($dbcon,"SELECT * FROM vehicle_list_subtext WHERE vid='$hm'");
$rows4=mysqli_num_rows($qry4);
if($rows4 > 0){
   while($arr4=mysqli_fetch_assoc($qry4)){
   ?>
          <div class="d-block mt-20">
					<label><?php echo $arr4["textlabel"] ?></label><br>
					<input type="text" value="<?php echo $arr4["textvalue"]; ?>">
			 </div> 
   <?php
   }
}
$qryvls=mysqli_query($dbcon,"SELECT * FROM vehicle_log_subtext WHERE vlid='$vlid' AND stand='$stand'");
$rowsvls=mysqli_num_rows($qryvls);
if($rowsvls > 0){
  while($arrvls=mysqli_fetch_assoc($qryvls)){	
 ?>
               <div class="d-block mt-20">
					    <label><?php echo $arrvls["subtext_label"] ?></label><br>
					    <input type="text" class="text1" value="<?php echo $arrvls["subtext_value"] ?>">
					    <input type="hidden" class="subtextlabel" value="<?php echo $arrvls["subtext_label"] ?>">
					    <a href="javascript:void(0)" class="cancel-btn text-decoration ml-10" data-delete="<?= $arrvls['id'] ?>"aria-label="Close" onclick="deletesubtext(this);"></a>
					</div>

 <?php
  }
}
 ?>
            </div>
            <div class="mt-20">
            	    <label class="font-weight-bold">Extra Feld Name</label><br>
            	    <input type="text" id="subtextvalue">     	
            </div>
            <br>
            <a href="javascript:void(0)" class="btn bg-green" id="btnCreateSubtext">Extra Feld +</a>
			</div>
			<div class="display_image mt-50 ml-20">
<?php 
$qry6=mysqli_query($dbcon,"SELECT * FROM vehicle_image WHERE vid='$hm'");
$row6=mysqli_num_rows($qry6);
if($row6 > 0){
	while($arr6=mysqli_fetch_assoc($qry6)){
		?>
         <img src="../../img/vehicle_uploads/<?php echo $arr6["img"] ?>" style="width:300px;" >
		<?php
	}
}
?>
			</div>
			<div class="mt-20 ml-20">
				<?php
                   $qryImg=mysqli_query($dbcon,"SELECT * FROM vehicle_log_image WHERE vlid='$vlid' AND cid='$cid'");
                   if($qryImg){
                   	$imgRows=mysqli_num_rows($qryImg);
                   	if($imgRows > 0){
                   		while($arrImg=mysqli_fetch_assoc($qryImg)){
					?>
                        <div style="display:block;width:300px;border:1px;">
	      	               <div style="display:flex">
	                          <img src="../../img/vehicle_uploads/<?php echo $arrImg['uploaded_image'] ?>" style="width:300px;">

	                          <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-20 deletevehicleImage" aria-label="Close" data-delete="<?php echo $arrImg["id"] ?>"></a>	 
	                        </div>
					         </div>
					   <br>
					<?php
                   	  }
                   	}
                   }
                 ?>
              <div style="display:block;width:300px;border:1px;">
	               <div style="display:flex">
                    <img src="../../img/icon/preview_image.jpg" style="width:300px;">
                    <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-10" aria-label="Close"  onclick="removeVehicleSrc(this)"></a>	 
                 </div>
			   <input type="file" name="carimg[]" onchange="showPreview(this,event);"><br><br>
		    </div>
			<div id="carimage">
			</div>
		    <div class="mt-20">
				<a href="javascript:void(0)" class="btn bg-green" id="btnCreateImageInput">Neues Bild +</a>
		    </div>		
		</div>
		</div>
	</div>
	<div class="mt-100 text-center mb-50">
		  <input type="submit" class="btn-sm bg-grey cur-pointer" value="Alles Speichern" onclick="vehicleLogReturnSave();">
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
       function populateCheckBoxVal(chbx){
        	   let allCheckBox = queryAll('.sel_class_'+chbx);
				allCheckBox.forEach((checkbox) => { 
				checkbox.addEventListener('change',(event) => {
					if(event.target.checked){
						let chbxAllClass=event.target.className;	
					   chbxClass=chbxAllClass.split("sel_class_")
						getID("sel_chbx_2"+chbxClass[1]).value=event.target.value;
				    }
				  })
				})
        }
    	   btnCreateImageInput=getID("btnCreateImageInput");
         btnCreateImageInput.addEventListener("click",function(){
          let html=`<div style="display:block;width:300px;border:1px;">
                        <div style="display:flex">
                             <img src="../../img/icon/preview_image.jpg" style="width:300px;">
                             <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-20" aria-label="Close" onclick="removeVehicleSrc(this)"></a>	 
                         </div>
                         <input type="file" name="carimg[]" onchange="showPreview(this,event);">
                     </div><br><br>`;	
          let img= getID("carimage");
              img.innerHTML+=html;
         });

       function createCheckbox3(){
       	let tbody=getID("insertcheckbox3");
       	let checkbox3= getID("checkbox3");
       	if(checkbox3.value == ''){
            myToast(0,"field cannot be empty");
        	}else{
       		var tr='<tr>';
			       tr+='<td>';
			       tr+='<label><p>'+checkbox3.value+'</p></label>';
			       tr+='</td>';
			       tr+='<td>';
			       tr+='<label class="tasks-list-item">';
					 tr+='<input type="radio" name="'+checkbox3.value+'" value="1" checked class="tasks-list-cb">';
					 tr+='<span class="tasks-list-mark"></span>';
					 tr+='</label>';
			       tr+='</td>';
			       tr+='<td>';
			       tr+='<label class="tasks-list-item">';
					 tr+='<input type="radio" name="'+checkbox3.value+'" value="0" class="tasks-list-cb">';
					 tr+='<span class="tasks-list-mark"></span>';
					 tr+='</label>';
			       tr+='</td>';
			       tr+='</tr>';
			   tbody.insertRow().innerHTML=tr;
			}
			getID("checkbox3").value='';
       }
     function vehicleLogReturnSave(){
       	var formID=getID("saveVehicleLogReturnForm");
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
       		formdata=new FormData(this);
       		formdata.append("createvehiclereturn",1);
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
	         	console.log(data);
	         	if(data.status == 1){
					   myToast(data.status,data.message);
					   setTimeout(function(){ 
                    window.location.href="edit_vehicle_return_protocol.php?eid=<?php echo $_GET["eid"]; ?>&hm="+data.hm+"&vlid="+data.vlid+"&crid=<?php echo $_GET["crid"]; ?>";
					    }, 3000);
					}else{
					   myToast(data.status,data.message);
					}
	         })
       	}) 
       }

    	function deleteCarImage(){
	      const imageDeleteId=queryAll(".deletevehicleImage");
	      imageDeleteId.forEach((imgRemove)=>{
            imgRemove.addEventListener("click",()=>{
            	 vehicleImageId=getAttr(imgRemove,"data-delete");
            	 var formdata=new FormData();
            	 formdata.append("delete_vehicle_image",1);
            	 formdata.append("img_id",vehicleImageId);
            	 fetch("../ajax/ajax_vehicle_list.php",{
            	 	  method:"POST",
            	 	  body:formdata
            	 }).then(res=>res.json())
            	 .then((data)=>{
            	 	 if(data.status == 1){
            	 	 	 myToast(data.status,data.message);
            	 	 	 setTimeout(function(){ location.reload()},2000);
            	 	 }else{
            	 	 	myToast(data.status,data.message);
            	 	 }
            	 })
            });
	      })
      }

// SIGNATURE======
signatureFleetManager();
signatureVehicleManager();
	</script>
 <?php include "common/footer.php" ?>
</body>
</html>