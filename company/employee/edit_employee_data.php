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
include "../../dbcon/dbcon.php";
include "../../func/functions.php";
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mitarbeiterdaten</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
	<link rel="stylesheet" type="text/css" href="../../css/company.css">
</head>
<body>
	<div id="toast"></div>
  <?php include "common/headtop.php"; ?>
  <?php include "common/navbar.php"; ?>
  
  	<div class="my-100">
<ul class="d-flex  bg-sub-nav list-style-none p-0">
<li class="active-profile-0"><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
<li><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
<li><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
<li><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
<li><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
<li><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
</ul>
</div>

<div class="my-100 mt-20 mb-20">
  <div class="d-flex">
    <div class="oben1"><?php include "common/employee_name.php"; ?></div>
    <div class="oben1 obenm">Mitarbeiterdaten bearbeiten</div>
    <div class="oben1 obenr">
      <div>
	  
	  
	    </div>
    </div>
  </div>
</div>
  
  <!--
  <?php include "common/employee_name.php"; ?>
	<div class="my-100" style="display:flex">
		<div class="w-20">
            <ul class="bg-light list-style-none d-block p-0">
            	<li class="active-profile p-10"><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
            	<li class="p-10"><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
            	<li class="p-10"><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
            	<li class="p-10"><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
            	<li class="p-10"><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
            	<li class="p-10"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
            </ul>
		</div>-->
		<div class="dd-flex">
<?php 
  $qry=mysqli_query($dbcon,"SELECT * FROM employee WHERE id='$eid'");
  if($qry){
  	$arr=mysqli_fetch_assoc($qry);
  }
 ?>
		<form id="editEmployeeData">
			<div class="d-flex w-100">
				<!--<div class="w-20">
					<a href="javascript:void(0)" class="d-inline-block bg-grey p-10 text-light text-decoration">Datenübersicht</a>
				</div>-->
				
				<div class=" w-100 text-right" style="margin-top:-40px;">
					<p>Status</p>
					<select style="width:100px;height:40px;padding:0px;" name="selectStatus">
						<option value="1" <?php echo ($arr["status"] == 1) ? 'selected' : ''; ?> >Aktiv</option>
						<option value="0" <?php echo ($arr["status"] == 0) ? 'selected' : ''; ?>>Deaktiv</option>
					</select>
				</div>
			</div>
			<div class="mt-20 d-flex c-bg">
                <input type="hidden" name="eid" id="eid" value="<?php echo $_GET["eid"]; ?>">
                <input type="hidden" name="pid" id="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
                
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Vorname</label><br>
					<input type="text" name="esname" value="<?php echo $arr["surname"]; ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Nachname</label><br>
					<input type="text" name="efname" value="<?php echo $arr["firstname"]; ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">E-Mail</label><br>
					<input type="email" name="eemail" value="<?php echo $arr["email"]; ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">ID</label><br>
					<input type="text" value="<?php echo $arr["id"]; ?>" readonly>
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Geburtstag</label><br>
					<input type="date" name="edob" value="<?php echo $arr["date_of_birth"]; ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Tel.</label><br>
					<input type="text" name="ephone" value="<?php echo $arr["phone_number"]; ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Strasse</label><br>
					<input type="text" name="estreet" value="<?php echo $arr["street"]; ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Haus Nr.</label><br>
					<input type="text" name="ehno" value="<?php echo $arr["house_no"]; ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Stadt</label><br>
					<input type="text" name="ecity" value="<?php echo $arr["city"]; ?>">
				</div>
        <div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Plz</label><br>
					<input type="text" name="ezip" value="<?php echo $arr["zip"]; ?>">
				</div>
				<div class="d-flex">
				<?php
            $qryExtra=mysqli_query($dbcon,"SELECT  *
FROM (
    SELECT * FROM employee_extra_field WHERE eid='$eid'
UNION ALL
    SELECT * FROM employee_extra_field WHERE view=1
) tmp GROUP BY label_name");
            if($qryExtra){
               while($rowsExtraField=mysqli_fetch_assoc($qryExtra)){
               	?>
			        <div class="d-block mr-20 ml-20 mt-20">
								<label class="font-weight-bold"><?= $rowsExtraField['label_name']; ?></label><br>
								<input type="text" class="textfield" value="<?= $rowsExtraField['label_value']; ?>">
								<input type="hidden" class="fieldnamevalue" value="<?= $rowsExtraField['label_name']; ?>">
								<input type="hidden" class="checkboxfeild" value="<?= $rowsExtraField['view']; ?>">
							</div>
				<?php
               }
            }

				?>
					<!--  -->
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Comment</label>
					<br>
					<textarea style="width:270px;" name="e_comment"><?php echo $arr["ecomment"]; ?></textarea>
				</div>
			</div>
			<!-- TEXT FIELDS -->
			<div id="text-fields" class="my-100 d-flex"></div>
			<div class="my-100 mt-100 d-flex">
			   <div class="d-flex">
              <div class="d-block mr-20 ml-20 mt-20">
			         <label class="font-weight-bold">Neues Feld</label><br>
			         <input type="text" id="fieldname" placeholder="Bitte Boxnaname eingeben bei bedarf">
			     </div>
				  <div class="d-block mr-20 ml-20 mt-50">
				      <label class="tasks-list-item">
				      <input type="checkbox" id="fieldCheckbox" class="tasks-list-cb">
				      <span class="tasks-list-mark"></span>
				      </label>'für alle 
				   </div>
			   </div>	
			</div>
			<div class="my-100 mt-20 d-flex">
			    <a href="javascript:void(0)" class="btn bg-green btncreate" style="width:160px;">Neues Feld anlegen +</a>
		   </div>
		   <!-- TEXT FIELD END -->
			<div class="d-flex-w1">
			<div class="w-50 mt-50">
				<input type="submit" name="submit" onclick="EditEmployee()" value="Änderung speichern" class="speichern-1 bg-green border-none fs-18">
			</div>
			

				</div>
		</form>

		</div>
		<div class="w-50 mt-50 text-right">
			<div class="transport1">
			<label style="color: #000;font-size: 12px;">Mitarbeiter darf zum einen Unternehmen transportieren<br>(wenn alle unterlagen abgegeben sind)</label><br>
					<select style="height:40px;padding:0px;" name="cid" id="cid">
						<?php 
						$qry2=mysqli_query($dbcon,"SELECT id,company_name FROM company");
						while($rows=mysqli_fetch_assoc($qry2)){
							if($arr["cid"] == $rows["id"]){
							  echo '<option value="'.base64_encode($rows["id"]*99999).'" selected>'.$rows["company_name"].'</option>'; 
							}else{
								echo '<option value="'.base64_encode($rows["id"]*99999).'">'.$rows["company_name"].'</option>';
							}						
						}
						?>
					</select>
					<a href="javascript:void(0)" class="btn bg-red" id="btnTransport" style="height:20px;margin-top:8px">Transportieren</a>
					</div>
		</div>
		<div id="confirmModalTransportEmp" class="modal">
		  <!-- Modal content -->
		  <div class="modal-content">
		    <!-- <span class="closeModal">&times;</span> -->
		    <div class="text-center pt-50">
		    	<p>Are you sure you want to Transport Employee to other Company ?</p>
		    	
		    	<div class="d-flex" style="justify-content:center;padding-top:30px;padding-bottom:20px;">
		    		 <a href="javascript:void(0)" class="btn bg-green" onclick="confirmTransportEmp()">Yes</a>
		    		 <a href="javascript:void(0)" class="closeModal btn bg-red mr-20">Close</a>
		    	</div>
		    </div>
		  </div>
		</div>
	
	<div class="my-100 mb-50 extr-da">
		<h3>History</h3>
		<table class="mt-20 table-light" style="width:100%;border-collapse: collapse;">
			<thead>
				<tr>
					<td>Was</td>
					<td>Vorher</td>
					<td>Danach</td>
					<td>Datum</td>
					<td>Sachbearbeiter</td>
				</tr>
			</thead>
			<tbody id="employeeHistoryTable">
			</tbody>
		</table>
	</div>
	<script type="text/javascript" src="../../js/functions.js"></script>
	<script>
		loadEmpHistoryData();	
		btnTrans=getID("btnTransport");
		function confirmTransportEmp(){
			let cid=getID("cid").value;
			let eid=getID("eid").value;
			let formdata=new FormData();
			formdata.append('transportCompany',1)
			formdata.append('company',cid);
			formdata.append('eid',eid);
			fetch("../../ajax/ajax_employee.php",{
				method:"POST",
				body:formdata
			}).then(res=>res.json())
			.then((data)=>{
				if(data.status == 1){
					location.href="employee.php";
				}else{
					myToast(data.status,data.message);
				}
			})
		}
		btnTrans.addEventListener("click",function(){
			  var modal =getID("confirmModalTransportEmp");
			  modal.style.display = "block";
			  var span = document.getElementsByClassName("closeModal")[0];
			  span.onclick = function() {
			    modal.style.display = "none";
			  }
			  window.onclick = function(event) {
			    if (event.target == modal) {
			      modal.style.display = "none";
			    }
			  }
		})
		btnCreate=queryOne('.btncreate');
    btnCreate.addEventListener("click",function(){
      var fieldname=getID('fieldname').value;
       var fieldCheckbox=getID('fieldCheckbox').checked;
       if(fieldname == ''){
           var msg="Please fill text name field";
           myToast(0,msg);
       }else{
        if(fieldCheckbox){
          createTextFields(fieldname,1);
        }else{
          createTextFields(fieldname,0);
        }
        getID('fieldname').value='';
        getID('fieldCheckbox').checked=false;
       }  
    });
	</script>
	<script type="text/javascript" src="../../js/employee.js"></script>
		 <?php include "common/footer.php" ?>
</body>
</html>