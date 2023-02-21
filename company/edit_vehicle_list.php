<?php
   session_start();
   if(!isset($_SESSION["isLogedIn"])){
     header("location:../index.php");
   }
   if(!isset($_SESSION["company"]["cid"])){
   	header("location:company.php");
   }
   $cid=base64_decode($_SESSION["company"]["cid"])/99999;
   if(!isset($_GET["vid"])){
   	header("location:company.php");
   }
   $vid=base64_decode($_GET["vid"])/55555;
    // echo $vid;
	if(!intval($vid)){
		header("location:company.php");
	}
   include "../dbcon/dbcon.php"; 
   include "../func/functions.php"; 
   $qry=mysqli_query($dbcon,"SELECT vl.*,p.firstname,p.surname FROM vehicle_list vl INNER JOIN personal_admin p ON vl.created_by=p.id WHERE vl.id='$vid'");
   $arr=mysqli_fetch_assoc($qry);
   $vid=$arr["id"];
   $hallmark=$arr["hallmark"];
   $created_by=$arr["created_by"];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fahrzeug bearbeiten</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
	<div id="toast"></div>
  <?php include 'common/headtop.php'; ?>
	<header class="my-100 mt-50">
		 <ul class="d-flex bg-light list-style-none p-20">
			 	<li><a href="employee/employee.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Mitarbeiter</a></li>
			 	<li><a href="inventory.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark">Warenbestand</a></li>
			 	<li><a href="office_order.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark ">Bestellung</a></li>
			 	<li><a href="vehicle_list.php" class="active-company-profile  text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Fahrzeugliste</a></li>
			 	<li><a href="edit_company.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Firmenprofil</a></li>
			 	<!--<li><a href="#" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Statistics</a></li>-->
		 </ul>
	</header>
	<div class="my-100 mt-20" style="display:flex;justify-content:between;">
		<div>
			<p class="btn bg-lightblue" style="width:200px">Fahrzeug bearbeiten</p>
		</div>
		
		<div style="position: absolute;right:100px;">

			  <p style="margin-bottom:5px;">Erstellt von : <?php echo $arr["firstname"].' '.$arr["surname"]; ?></p>
			  <b>Erstellt am : <?php echo date("d.m.Y | H:i",strtotime($arr["last_updated_on"])) ?></b>
		</div>
	</div>
	<form id="EditvehicleListMainForm">
	<div class="my-100 mt-20">
		<div class="d-flex justify-content-between">
		<input type="hidden" name="pid" id="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
		<input type="hidden" name="cid" id="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
		<p class="btn bg-lightblue" style="margin-bottom:20px;padding:5px">ID 0<?php echo $arr["id"];?></p>
		<div>
			<select style="width:160px;padding:3px;">
				<?php 
         $select_company=mysqli_query($dbcon,"SELECT id,company_name FROM company WHERE id  <> '$cid'");
         while($arr_company=mysqli_fetch_assoc($select_company)){
         	  ?>
               <option value="<?php echo $arr_company['id']; ?>"><?php echo $arr_company['company_name']; ?></option>
        <?php
          }
				?>
			</select>
			<a href="javascript:void(0)" class="btn bg-red" onclick="confirmModalTransportVL(this)">Transportien</a>
		</div>
		<label>Status
         <select style="width:100px;padding:3px;" name="selectStatus">
					<option value="1" <?php echo ($arr["status"] == 1) ? 'selected' : ''; ?> >aktiv</option>
					<option value="0" <?php echo ($arr["status"] == 0) ? 'selected' : ''; ?>>deaktiviert</option>
         </select>
		</label>
		</div>
	</div>
	<div class="my-100 mt-50">
		 <div class="d-flex">
			 	<div class="d-block ml-20 mt-20">
			 		<input type="hidden" name="vid" id="vid" value="<?php echo $_GET['vid']; ?>">
			 	    <label class="text-red font-weight-bold">Kennzeichen</label><br>
			 	    <input type="text" class="w-160px" name="hallmark" value="<?php echo $arr['hallmark']; ?> ">
			 	</div>
	         <div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold">Fahrzeug Art</label><br>
			 	    <select class="w-160px p-0" name="vehicleType">
					 	   <option value="PKW" <?php echo ($arr["vehicle_type"] == 'PKW') ? 'selected' : ''; ?> >PKW</option>
							<option value="LKW" <?php echo ($arr["vehicle_type"] == 'LKW') ? 'selected' : ''; ?>>LKW</option>
			 	    </select>
			 	</div>
			 	<div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold">Fahrzeugname</label><br>
			 	    <input type="text" class="w-160px" value="<?php echo $arr['car_name'] ?>" name="carname">
			 	</div>
			 	<div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold">Baujahr</label><br>
			 	    <input type="date" class="w-160px" value="<?php echo $arr['construction_year'] ?>" name="conYear">
			 	</div>
			 	<div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold">Schl&uuml;sselnummer</label>
			 	    <br>
			 	    <input type="text" class="w-160px" name="keyNum" value="<?php echo $arr['key_number'] ?>">
			 	</div>
			 	<div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold">Km Stand</label><br>
			 	    <input type="text" class="w-160px" name="milOrg" value="<?php echo $arr['mileage_original'] ?>">
			 	</div>
			 	<div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold">Mitarbeiter gefahrene Km Stand</label><br>
			 	    <input type="text" class="w-160px" name="milEmp" value="<?php echo $arr['mileage_employee'] ?>">
			 	</div>
			 	<div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold text-green">letzte Inspektion</label><br>
			 	    <input type="date" class="w-160px" name="serLast" value="<?php echo $arr['service_last'] ?>">
			 	</div>
			 	<div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold text-red">Nächste Inspektion</label><br>
			 	    <input type="date" class="w-160px" name="serNext" value="<?php echo $arr['service_next'] ?>">
			 	</div>
			 	<div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold text-green">Letzter TÜV Last</label><br>
			 	    <input type="date" class="w-160px" name="tuvLast" value="<?php echo $arr['tuv_last'] ?>">
			 	</div>
			 	<div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold text-red">Nächster Tüv</label><br>
			 	    <input type="date" class="w-160px" name="tuvNext" value="<?php echo $arr['tuv_next'] ?>">
			 	</div>
			 	<div class="d-block ml-20 mt-20">
			 	    <label class="font-weight-bold">Anzahl Schlüssel</label><br>
			 	    <input type="text" class="w-160px" name="numKeys" value="<?php echo $arr['number_of_keys'] ?>">
			 	</div>
			 	<div id="text-fields" class="d-flex mt-20">
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
    $qrycheckbox=mysqli_query($dbcon,"SELECT * FROM vehicle_list_checkbox WHERE vid='$vid' AND cid='$cid'");
    if($qrycheckbox){
    	while($arrcheckbox=mysqli_fetch_assoc($qrycheckbox)){
      ?>
								<tr>
									<td style="padding:0px"><p><?= $arrcheckbox["label_name"] ?></p>
										<input type="hidden" name="labelname[]" value="<?= $arrcheckbox["label_name"] ?>">
									</td>
									<td style="padding:0px">							
										<label class="tasks-list-item">
										   <input type="hidden" name="checkbox_id[]" value="<?= $arrcheckbox["id"]; ?>">
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
									 	      <a href="javascript:void(0)" class="d-inline-block bg-green text-light p-5 text-decoration" data-edit="<?= $arrcheckbox["id"] ?>" onclick="editCheckbox(this)">bearbeiten</a>
									 	      <a href="javascript:void(0)" class="d-inline-block bg-green text-light bg-blue p-5 text-decoration ml-10" data-save="<?= $arrcheckbox["id"] ?>" onclick="saveCheckbox(this)">Speichern</a>
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
              	<label>Für alle kommende Fahrzeuege</label>
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
						   <div id="commentsSection">
<?php
   $qryComment=mysqli_query($dbcon,"SELECT * FROM vehicle_list_comment WHERE vid='$vid'");
   if($qryComment){
   	$comRows=mysqli_num_rows($qryComment);
   	if($comRows > 0){
   		;
   		while($arrCom=mysqli_fetch_assoc($qryComment)){
   			?><div class="d-flex w-100">
   				  <div class="d-block w-80">
   				  	   <label class="font-weight-bold">
   				  	   	  <div class="d-flex">
   				  	   	   <p><?= $arrCom["com_label"] ?></p>
   				  	   	   <input type="hidden" value="<?= $arrCom["com_label"] ?>" class="commentLabel" >
   				  	   	   <a href="javascript:void(0)" class="d-inline-block bg-blue p-5 cur-pointer text-light text-decoration ml-10" onclick="editComment(this)">bearbeiten</a>
   				  	   	   <a href="javascript:void(0)" class="bg-green btn-sm ml-10" data-id="<?= $arrCom["id"]; ?>" onclick="saveComment(this)" style="display:none;">Speichern</a>
   				  	   	</div>
   				  	   </label>
			           <textarea rows="6" class="w-100 commentText"><?= $arrCom["com_text"]; ?></textarea>
			           
   				  </div>
   				  <div class="d-block"><br><br>
   				  	   <a href="javascript:void(0)" data-remove="<?= $arrCom["id"]; ?>" class="cancel-btn text-decoration mt-50 ml-20" aria-label="Close"  onclick="removeComment(this)"></a>
   				  </div>
	
   			  </div>

		    <?php
   		}
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
				<div class="mt-50 ml-20 p-20">
	                <div id="subtext">
	             <?php
	                	$qrySubtxt=mysqli_query($dbcon,"SELECT * FROM vehicle_list_subtext WHERE vid='$vid'");
	                	if($qrySubtxt){
	                		$rows_subxt=mysqli_num_rows($qrySubtxt);
	                		if($rows_subxt > 0){
	                			while($arrSubtxt=mysqli_fetch_assoc($qrySubtxt)){
                                   ?>
						                <div class="d-block mt-20">
						                	 <div class="d-flex">
						                	 	<div class="d-block">
						                	 		<label><?= $arrSubtxt["textlabel"] ?></label><br>
									                <input type="text" class="text1" value="<?= $arrSubtxt["textvalue"] ?>">
									                <input type="hidden" class="subtextlabel" value="<?= $arrSubtxt["textlabel"] ?>">
						                	 	</div>
						                	 	<div class="d-block">
						                	 		<br><br>
		                                            <a href="javascript:void(0)" data-delete="<?= $arrSubtxt["id"] ?>" class="cancel-btn text-decoration mt-50 ml-20" aria-label="Close" onclick="removeSubtext(this)"></a>
						                	 	</div>
						                	 </div>

									    </div>
                                   <?php
	                			}
	                		}
	                	}  ?>
	                </div>
			        <div class="mt-20">
			            	    <label class="font-weight-bold">Extra Feld Name</label><br>
			            	    <input type="text" id="subtextvalue">     	
		          </div><br>
		            <a href="javascript:void(0)" class="btn bg-green" id="btnCreateSubtext">Extra Feld +</a>
				</div>
				<div class="mt-20 ml-20 p-20">
						<?php
	                      $qryImg=mysqli_query($dbcon,"SELECT * FROM vehicle_image WHERE vid='$vid' AND cid='$cid'");
	                      if($qryImg){
	                      	$imgRows=mysqli_num_rows($qryImg);
	                      	if($imgRows > 0){
	                      		while($arrImg=mysqli_fetch_assoc($qryImg)){
							?>
	                           <div style="display:block;width:300px;border:1px;">
			      	               <div style="display:flex">
			                          <img src="../img/vehicle_uploads/<?php echo $arrImg['img'] ?>" style="width:350px;">

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
			</div>
			<div class="text-center mt-50 w-100">
			  <input type="submit" class="btn-sm bg-grey cur-pointer" id="savebtn" value="Alles Speichern" onClick="saveVehicleEdit();">
</div>
	   </div>
	   <div class="mt-50">
			<h3>Verlauf</h3>
			<table class="mt-20 history-tableEc" style="width:100%;border-collapse: collapse;">
				<thead>
					<tr>
						<td>Was</td>
						<td>Vorher</td>
						<td>Danach</td>
						<td>ge&auml;ndert am</td>
						<td>Sachbearbeiter</td>
					</tr>
				</thead>
				<tbody id="vehicleHistoryTable">
				</tbody>
			</table>
	  </div>
	  <h3 class="mt-100 mb-20">Welcher Mitarbeiter hatte das Fahrzeug</h3>
      <table class="w-80 PrintpdfTable mb-50">
			<thead>
				<tr>
					<td>Kennzeichen</td>
					<td>Übergabe am</td>
					<td>Rückgabe am</td>
					<td>ID</td>
					<td>Mitarbeiter</td>
				</tr>
			</thead>
			<tbody>
				<?php 
          $qryhistory=mysqli_query($dbcon,"SELECT vlp.id,vlp.created_id,vl.hallmark AS hallname,e.firstname,e.surname FROM vehicle_log_protocol vlp  INNER JOIN vehicle_list vl ON vlp.hallmark=vl.id INNER JOIN employee e ON vlp.eid = e.id  WHERE vlp.hallmark='$vid' AND vlp.cid='$cid' GROUP BY created_id");
          $num_rows=mysqli_num_rows($qryhistory);
          if($num_rows > 0){
          	  while($arrhistory=mysqli_fetch_assoc($qryhistory)){

          	  echo '<tr>
          	        <td>'.$arrhistory["hallname"].'</td>';

          	  	$crid=$arrhistory["created_id"];
          	  	$qryhistory2=mysqli_query($dbcon,"SELECT stand,data FROM vehicle_log_protocol WHERE created_id='$crid'");
                 $data1=0;
                 $data2=0;
                while($arrhistory2=mysqli_fetch_assoc($qryhistory2)){
                   if($arrhistory2["stand"] == 1){
                   	 $data1=($arrhistory2["data"] == "0000-00-00 00:00:00") ? "date is not given" : date("d.m.Y | H:i",strtotime($arrhistory2["data"]));
                   }else{
                       if($arrhistory2["stand"] == 2){
		                   	  $data2=($arrhistory2["data"] == "0000-00-00 00:00:00") ? "Not returned By employee" : date("d.m.Y | H:i",strtotime($arrhistory2["data"]));
		                   }
                   }

                }

                echo '<td>'.$data1.'</td><td>'.$data2.'</td>';
             
             ?>
					<td><?= '00'.$arrhistory["id"];  ?></td>
					<td><?php echo $arrhistory["firstname"]." ".$arrhistory["surname"] ?></td>
				</tr> 
				<?php   	
              }
          }else{
          	?>
               <tr>
               	  <td colspan="4" class="text-center p-5"><b>Wird von niemandem verwendet....</b></td>
               </tr>
          	<?php
          }
				?>

			</tbody>
		</table>
	</div>
</form>
<div id="confirmModalTransportVL" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <!-- <span class="closeModal">&times;</span> -->
    <div class="text-center pt-50">
    	<p>Are you sure you want to Transport Vehicle to other Company ?</p>
    	<input type="hidden" id="transportID" value="">
    	<div class="d-flex" style="justify-content:center;padding-top:30px;padding-bottom:20px;">
    		 <a href="javascript:void(0)" class="btn bg-green" onclick="confirmTransportVL()">Yes</a>
    		 <a href="javascript:void(0)" class="closeModal btn bg-red mr-20">Close</a>
    	</div>
    </div>
  </div>
</div>
<div class="ml-20">
  <a href="print/vehicleListHistory.php?vid=<?= $_GET["vid"]; ?>">View Previous Company Vehicle History</a>
</div>
	<script src="../js/functions.js"></script>
	<script src="../js/vehicle_list.js"></script>
	<script>
		checkForMainTextfield();
		loadVehicleHistoryTable()

		function confirmTransportVL(){
             // let cid=event.parentElement.children[0].value;
             let pid=getID("pid").value;
             let vid=getID("vid").value;
             let cid=getID("transportID").value;
             formdata=new FormData();
             formdata.append("transport_vehicle",1);
             formdata.append("cid",cid);
             formdata.append("vid",vid);
             formdata.append("pid",pid);
             fetch("../ajax/ajax_vehicle_list.php",{
             	  method:"POST",
             	  body:formdata
             }).then((res)=>res.json())
             .then((data)=>{
                 if(data.status == 1){
                     myToast(data.status,data.message);
                     getID("confirmModalTransportVL").style.display="none";
                     setTimeout(function(){ window.location.href='vehicle_list.php'; }, 3000);
                 }else{
                     myToast(data.status,data.message);
                 }
             })
		}

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
	      deleteCarImage();
    
	</script>
	<?php include "common/footer.php"; ?>
</body>
</html>
