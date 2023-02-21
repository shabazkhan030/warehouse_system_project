<?php
   session_start();
   if(!isset($_SESSION["isLogedIn"])){
   	header("location:../index.php");
   }
   include "../dbcon/dbcon.php";
   include "../func/functions.php";

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sachbearbeiter anlegen</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
   <div id="toast"></div>
   <div class="neu-top-2">
	<section class="form-section">
		<form id="insertpersonaladmin">
		<div class="my-100 d-flex">
		   <div class="mt-50 w-40">
		   	   <h2 class="bg-green text-light p-10">Sachbearbeiter anlegen +</h2>
		   </div>
		   <div class="mt-50 w-60 text-right">
		   	   <a href="personal_admin.php" class="btn bg-red ml-20">&nbsp;schliesen und zurück &nbsp;</a>
		   </div>	
		</div>
		<div class="mt-100 my-100 d-flex">
			<div class="d-block mr-20 ml-20 mt-20">

				<label class="font-weight-bold">ID</label><br>
				<input type="text" value="<?php echo intval(getLastInsertedID($dbcon,"personal_admin","id")+1); ?>" disabled>
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Erstellt am</label><br>
				<input type="text" value="<?php echo date("d.m.Y"); ?>" readonly>
			</div>
<!-- 			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Closed Data</label><br>
				<input type="text" name="close_data">
			</div> -->
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Status</label><br>
				<select name="statusSelect">
					<option value="0">Disabled</option>
					<option value="1">Aktiv</option>
				</select>
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Firma wählen</label><br>
				<select name="selectcompany" class="p-0" id="selectcompany">
		   	</select>
         </div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Nachname</label><br>
				<input type="text" name="surname">
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Vorname</label><br>
				<input type="text" name="firstname">
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">E-Mail</label><br>
				<input type="email" name="email">
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Welche Rolle</label><br>
				<select name="role">
					 <option value="personal_admin">Personal admin</option>
					 <option value="super_admin">Super admin</option>
				</select>
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Passwort</label>
				<br>
				<input type="password" name="password">
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Passwort widerholen</label><br>
				<input type="password" name="rpassword">
			</div>
			<div id="text-fields" class="d-flex"></div>
		</div>
		<div class="my-100 mt-100">
			<div class="d-flex">
              <div class="d-block mr-20 ml-20 mt-20">
			         <label class="font-weight-bold">Neues Feld</label><br>
			         <input type="text" id="fieldname" placeholder="Bitte Boxnaname eingeben bei bedarf">
			     </div>
				  <div class="d-block mr-20 ml-20 mt-50">
				      <label class="tasks-list-item">
				      <input type="checkbox" id="fieldCheckbox" class="tasks-list-cb">
				      <span class="tasks-list-mark"></span>
				      </label>'
				   </div>
			</div>
		</div>
		<div class="my-100 mt-20 d-flex">
			<a href="javascript:void(0)" class="btn bg-green btncreate" style="width:160px;">Neues Feld +</a>
		</div>
		<div class="my-100 mt-50">
			<input type="submit" name="submit" value="Speichern" id="save" class="d-inline-block text-light p-10 border-grey bg-red cur-pointer" onclick="createPersonalAdmin()" style="border:1px solid grey">
		</div>
		</form>
	</section>
   <script src="../js/functions.js"></script>
	<script src="../js/personal_admin.js"></script>
	<script>
      btnCreate=queryOne('.btncreate');
      btnCreate.addEventListener("click",function(){
      	var fieldname=getID('fieldname').value;
         var fieldCheckbox=getID('fieldCheckbox').checked;
         console.log(fieldCheckbox);
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
      fetchCompany();

   </script>  
   </div>
</body>
</html>



