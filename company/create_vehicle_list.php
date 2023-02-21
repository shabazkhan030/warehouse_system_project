<?php
   session_start();
   if(!isset($_SESSION["isLogedIn"])){
     header("location:../index.php");
   }
   if(!isset($_SESSION["company"]["cid"])){
   	header("location:company.php");
   }
   include "../dbcon/dbcon.php"; 
   include "../func/functions.php";  

 $cid=base64_decode($_SESSION["company"]["cid"])/99999;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Vehicle List Create</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
	<div id="toast"></div>
  <?php include 'common/headtop.php'; ?>
	<header class="my-100 mt-50">
		 <ul class="d-flex bg-light list-style-none p-20">
			 	<li class=" "><a href="employee/employee.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Mitarbeiter</a></li>
			 	<li class=""><a href="inventory.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark ">Warenbestand</a></li>
			 	<li class=""><a href="office_order.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark ">Bestellung</a></li>
			 	<li class=""><a href="vehicle_list.php" class="active-company-profile text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Fahrzeugliste</a></li>
			 	<li class=""><a href="company_profile.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Firmenprofil</a></li>
			 	<li class=""><a href="#" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Statistics</a></li>
		 </ul>
	</header>
	<div class="my-100 mt-20">
		<p class="bg-lightblue d-inline-block text-decoration text-light p-10">Neues Fahrzeug erstellen</p>
	</div>
	<form id="vehicleListMainForm">
			<input type="hidden" name="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
			<input type="hidden" name="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
			<div class="my-100 mt-20">
				<div class="d-flex justify-content-between">
				<p class="btn bg-lightblue" style="margin-bottom:20px;padding:5px">ID <?php echo getLastInsertedID($dbcon,"vehicle_list","id")+1; ?></p>
				<div>
					<select style="width:160px;padding:3px;">
						<?php 
						  
                      $QRY=mysqli_query($dbcon,"SELECT company_name FROM company WHERE id='$cid'");
                      if($QRY){
                      	$arr=mysqli_fetch_assoc($QRY);
                      	echo '<option>'.$arr["company_name"].'</option>';
                      }
						 ?>
					</select>
					<a href="javascript:void(0)" class="btn bg-red">Transport</a>
				</div>
				<label>Status
		         <select style="width:100px;padding:3px;" name="selectStatus">
		         	<option value="0">Disabled</option>
		         	<option value="1">Active</option>
		         </select>
				</label>
				</div>
			</div>
	<div class="my-100 mt-50">
				 <div class="d-flex">
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="text-red font-weight-bold">Kennzeichen</label><br>
					 	    <input type="text" class="w-160px" name="hallmark">
					 	</div>
		           <div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold">Fahrzeug Art</label><br>
					 	    <select class="w-160px p-0" name="vehicleType">
						 	    	<option value="PKW">PKW</option>
						 	    	<option value="LKW">LKW</option>
					 	    </select>
					 	</div>
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold">Fahrzeugname</label><br>
					 	    <input type="text" class="w-160px" name="carname">
					 	</div>
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold">Baujahr</label><br>
					 	    <input type="date" class="w-160px" name="conYear">
					 	</div>
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold">Schlüsselnummer</label><br>
					 	    <input type="text" class="w-160px" name="keyNum">
					 	</div>
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold">Km Stand</label><br>
					 	    <input type="text" class="w-160px" name="milOrg">
					 	</div>
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold">Mitarbeiter gefahrene Km Stand</label><br>
					 	    <input type="text" class="w-160px" name="milEmp">
					 	</div>
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold text-green">letzte Inspektion</label><br>
					 	    <input type="date" class="w-160px" name="serLast">
					 	</div>
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold text-red">N&auml;chste Inspektion</label>
					 	    <br>
					 	    <input type="date" class="w-160px" name="serNext">
					 	</div>
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold text-green">Letzter TÜV</label><br>
					 	    <input type="date" class="w-160px" name="tuvLast">
					 	</div>
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold text-red">Nächster Tüv</label><br>
					 	    <input type="date" class="w-160px" name="tuvNext">
					 	</div>
					 	<div class="d-block ml-20 mt-20">
					 	    <label class="font-weight-bold">Anzahl Schl&uuml;ssel</label>
					 	    <br>
					 	    <input type="text" class="w-160px" name="numKeys">
					 	</div>
					 	<div id="text-fields" class="d-flex mr-20 ml-20 mt-20">
					 		<?php
$qryExtraField=mysqli_query($dbcon,"SELECT * FROM vehicle_list_extra_field WHERE visible='1' GROUP BY reference_id_field");
if($qryExtraField){
	$rowsExtraField=mysqli_num_rows($qryExtraField);
	if($rowsExtraField > 0){
	    while($arrExtraField=mysqli_fetch_assoc($qryExtraField)){
             ?>
             <div class="d-flex">
		      <div class="d-block mr-20">
			      <label class="font-weight-bold"><?php echo $arrExtraField["textname"] ?></label><br>
			      <input type="text" class="textfield w-160px">
			      <input type="hidden" class="fieldnamevalue" value="<?php echo $arrExtraField["textname"] ?>">
			      <input type="hidden" class="viewAllvalue" value="<?php echo $arrExtraField["visible"] ?>">
			      <input type="hidden" class="refrence_id_field" value="<?php echo $arrExtraField["reference_id_field"] ?>"> 
		      </div>
		      </div>
            <?php
	    }
	    	
	}
}
			?>
					 	</div>
				 </div>
				 <div class="d-block mr-20 ml-20 mt-20">
				 	    <div class="d-flex">
					 	    	<div class="d-block">
					            <label class="font-weight-bold">Neues Feld</label><br>
					            <input type="text" id="fieldname" placeholder="Bitte Boxnaname eingeben bei bedarf">				 	    		
					 	    	</div>
					 	    	<div class="d-block ml-20">
					            <label class="font-weight-bold">Für alle kommende Fahrzeuege</label><br>
					            <label class="tasks-list-item" style="padding-top:15px;">
										        <input type="checkbox" id="viewAll" value="1" class="tasks-list-cb">
										        <span class="tasks-list-mark"></span>
										  </label>			 	    		
					 	    	</div>
				 	    </div>
				 </div>
				 <div class="mt-20 mr-20 ml-20">
				 	    <a href="javascript:void(0)" class="btn bg-green create-fields">Neues Feld +</a>
				 </div>
			</div>
			<div class="my-100 mt-50">
				<div class="d-flex">
					<div class="w-50">
							<table class="table-light w-100">
								<thead>
									<tr>
										<td>&nbsp;</td>
										<td>JA</td>
										<td>Nein</td>
										<td>Action</td>
										<td>Status</td>
									</tr>
								</thead>
								<tbody id="vehicleListTableCheckbox">
<?php 
 $qrycheckbox=mysqli_query($dbcon,"SELECT * FROM vehicle_list_checkbox WHERE view='1' AND cid='$cid' GROUP BY reference_id");
 if($qrycheckbox){
    	while($arrcheckbox=mysqli_fetch_assoc($qrycheckbox)){
      ?>
								<tr>
									<td style="padding:0px"><p><?= $arrcheckbox["label_name"] ?></p>
										<input type="hidden" name="labelname[]" value="<?= $arrcheckbox["label_name"] ?>">
										<input type="hidden" name="reference_id[]" value="<?= $arrcheckbox['reference_id'] ?>">
									</td>
									<td style="padding:0px">							
										<label class="tasks-list-item">
								           <input type="radio" name="<?= $arrcheckbox["label_name"]; ?>" value="1" <?php echo ($arrcheckbox["label_value"] == '1') ? "checked" : "" ;?>  class="tasks-list-cb">
								           <span class="tasks-list-mark"></span>
								        </label>
								    </td>
									<td style="padding:0px">	
											<label class="tasks-list-item">
									           <input type="radio"  name="<?= $arrcheckbox["label_name"]; ?>" value="0" <?php echo ($arrcheckbox["label_value"] == '0') ? "checked" : "" ;?> class="tasks-list-cb">
									           <span class="tasks-list-mark"></span>
									    </label>
								   </td>
								   <td style="padding:0px">
									 	 <div class="d-flex">	
									 	      <a href="javascript:void(0)" class="d-inline-block bg-green text-light p-5 text-decoration" data-edit="<?= $arrcheckbox["id"] ?>" onclick="myToast(0,'sorry in creation of vehicle you are not able to edit')">bearbeiten</a>
									 	      <a href="javascript:void(0)" class="d-inline-block bg-green text-light bg-blue p-5 text-decoration ml-10" data-save="<?= $arrcheckbox["id"] ?>" onclick="myToast(1,'Saved Successfully')">Speichern</a>
									 	 </div>
									 </td>
									 <td style="padding:0px">
									 	  <select name="showAll[]" style="width:80px;padding:3px;">
									 	      <option value="1" <?php echo ($arrcheckbox["view"] == '1') ? "selected" : "" ;?> >Active</option>
									 	      <option value="0" <?php echo ($arrcheckbox["view"] == '0') ? "selected" : "" ;?>>Disabled</option>
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
								      <input type="text" id="labelname">
								</div>
				                <div class="d-block ml-20 text-center">
				                  	<label>Für alle kommende Fahrzeuege
</label>
								    <label class="tasks-list-item">
								          <input type="checkbox" id="viewtoall" value="1" class="tasks-list-cb">
								          <span class="tasks-list-mark"></span>
								    </label>                  	
				                </div>
							</div>
							<div class="mt-20 mr-20">
								<a href="javascript:void(0)" class="btn bg-green" onclick="addMoreRow()">Neues Feld +</a>
							</div>
							<div class="mt-50">
								   <div id="commentsSection"></div>
						  </div>
						  <div class="mt-20 mr-20">
						  	   <label class="font-weight-bold">Kommentarnamen erstellen</label><br>
						  	   <input type="text" id="comLabel"><br><br>
								   <a href="javascript:void(0)" class="btn bg-green" onclick="addMoreComments()">Neues Feld +</a>
						  </div>			 
					</div>
					<div class="w-50">
						<div class="ml-20 p-20">
		                    <div id="subtext"></div>
				            <div class="mt-20">
				            	    <label class="font-weight-bold">Extra Feld Name</label><br>
				            	    <input type="text" id="subtextvalue">     	
				            </div><br>
		                    <a href="javascript:void(0)" class="btn bg-green" id="btnCreateSubtext">Extra Feld +</a>
						</div>
						<div class="mt-20 ml-20 p-20">
			               <div style="display:block;width:300px;border:1px;">
		      	               <div style="display:flex">
		                          <img src="../img/icon/preview_image.jpg" style="width:350px;">
		                          <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-20" aria-label="Close"  onclick="removeVehicleSrc(this)"></a>	 
		                      </div>
						      <input type="file" name="carimg[]" onchange="showPreview(this,event);"><br><br>
						   </div>
						   <div id="carimage">
						   </div>
						   <div class="mt-20">
							   <a href="javascript:void(0)" class="btn bg-green" id="btnCreateImageInput">Neues Bild +</a>
						   </div>	
						</div>
		<!-- 			</div> -->
					<div class="w-50">
						<div class="text-right  mt-50">
							<input type="submit" class="btn-sm bg-grey cur-pointer" style="margin-bottom:50px;" value="Alles Speichern" onclick="submitVehicleListMain();">
						</div>
						
					</div>
					
			   </div>
			</div>
	</form>
			</div>

	<script src="../js/functions.js"></script>
	<script src="../js/vehicle_list.js"></script>
	<script>
			btnCreateMainField=queryOne('.create-fields');
	         btnCreateMainField.addEventListener("click",function(){
	      	var fieldname=getID('fieldname').value;
	      	var viewAll=(getID("viewAll").checked) ? 1 : 0;
	         if(fieldname == ''){
	             var msg="Please fill text name field";
	             myToast(0,msg);
	         }else{
	         	createTextFieldWithVisibility(fieldname,viewAll);
	         	getID('fieldname').value='';
	         }  
	      });
	         let btncreateSubtext=getID("btnCreateSubtext");
	         btncreateSubtext.addEventListener("click",function(){
	         	 let subtextvalue=getID("subtextvalue").value.trim();
		         if(subtextvalue != ''){
                    let html=`
		                <div class="d-block mt-20">
					         <label>${subtextvalue}</label><br>
					         <input type="text" class="text1">
					         <input type="hidden" class="subtextlabel" value="${subtextvalue}">
					    </div>`;
					let subtext=getID("subtext");	                
                    subtext.innerHTML+=html
		         }else{
		         	myToast(0,"Please provide subtext label..")
		         }
	
             getID("subtextvalue").value='';
	         });
	         btnCreateImageInput=getID("btnCreateImageInput");
	         btnCreateImageInput.addEventListener("click",function(){
	         	// console.log("hello");
             let html=`<div style="display:block;width:300px;border:1px;">
                           <div style="display:flex">
                                <img src="../img/icon/preview_image.jpg" style="width:350px;">
                                <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-20" aria-label="Close" onclick="removeVehicleSrc(this)"></a>	 
                            </div>
                            <input type="file" name="carimg[]" onchange="showPreview(this,event);">
                        </div><br><br>`;	
             let img= getID("carimage");
                 img.innerHTML+=html;
	         });




	</script>
	<?php include "common/footer.php"; ?>
	</body>
</html>