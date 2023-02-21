<?php
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../../index.php");
}
if(!isset($_SESSION["company"]["cid"])){
	header("location:../company.php");
}
   include "../../dbcon/dbcon.php"; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Neuen Mitarbeiter anlegen</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
	<link rel="stylesheet" type="text/css" href="../../css/company.css">
</head>
<body>
	<div id="toast"></div>
    <?php include "common/headtop.php"; ?>
    <?php include "common/navbar.php" ?>   
	<div class="my-100 mt-20 mb-20">
		<div class="d-flex">
			<a href="javascript:void(0)" class="bg-skyblue d-inline-block text-light p-10 text-decoration">Neuen Mitarbeiter anlegen</a>
		</div>
	</div>
	<div class="my-100" style="display:flex">
		<div class="ml-20 w-100">
		<form id="createEmployeeForm">
			<div class="mt-20 d-flex">
                <div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Firma</label><br>
                    <input type="text" value="<?php echo $_SESSION["company"]["cname"]; ?>"readonly>
                    <input type="hidden" id="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>"readonly>
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Status</label><br>
					<select class="p-0" name="selectStatus">
						<option value="1">Aktiv</option>
						<option value="0">Deaktiv</option>
					</select>
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Vormane</label>
					<br>
					<input type="text" name="esname">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Nachname</label>
					<br>
					<input type="text" name="efname">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">E-Mail</label>
					<br>
					<input type="email" name="eemail">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">ID</label><br>
					<input type="hidden" name="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
					<input type="hidden" name="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
					<input type="text" id="eid" readonly>
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Geburtstag</label><br>
					<input type="date" name="edob" placeholder="dd-mm-yyyy">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Tel.</label><br>
					<input type="text" name="ephone">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Strasse</label><br>
					<input type="text" name="estreet">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Haus Nr.</label><br>
					<input type="text" name="ehno">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Stadt</label><br>
					<input type="text" name="ecity">
				</div>
        <div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">PLZ</label>
					<br>
					<input type="text" name="ezip">
				</div>
				<div class="d-flex">
				<?php
            $qryExtra=mysqli_query($dbcon,"SELECT * FROM employee_extra_field WHERE view='1' GROUP BY label_name");
            if($qryExtra){
               while($rowsExtraField=mysqli_fetch_assoc($qryExtra)){
               	?>
			        <div class="d-block mr-20 ml-20 mt-20">
								<label class="font-weight-bold"><?= $rowsExtraField['label_name']; ?></label><br>
								<input type="text" class="textfield">
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
					<label class="font-weight-bold">Kommentar</label>
					<br>
					<textarea style="width:270px;" name="e_comment"></textarea>
				</div>

			</div>
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
			<div class="ml-20 mt-50">
				<input type="submit" name="submit" onclick="createEmployee()" value="Speichern" class="btn-sm bg-grey cur-pointer" style="border:1px solid grey">
			</div>
		</form>
		</div>
	</div>
	<script type="text/javascript" src="../../js/functions.js"></script>
	<script>
		const btnCreateEmployee = getID('createEmployeeForm');
		getLastID("employee");
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