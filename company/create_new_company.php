<?php
   session_start();
   if(!isset($_SESSION["isLogedIn"])){
   	header("location:../index.php");
   }
   if($_SESSION['role'] != 'super_admin'){
   	header("location:company.php");
   }
  $pid=$_SESSION["isLogedIn"];
   include "../dbcon/dbcon.php";
   include "../func/functions.php";  
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Neue Firma Anlegen</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
	<div id="toast"></div>
	 <div class="neu-top-2">
	<section class="form-section">
		<form id="insertCompany">
			<div class="my-100 d-flex">
			   <div class="mt-50 w-40">
			   	<input type="hidden" name="pid" value="<?php echo $pid; ?>">
			   	   <h2 class="bg-green text-light p-10 in-bl">Neue Firma Anlegen</h2>
			   </div>
			   <div class="mt-50 w-60 text-right">
			   	   <a href="company.php" class="btn bg-red ml-20">&nbsp;schliesen & Zurück &nbsp;</a>
			   </div>	
			</div>
			<div class="mt-100 my-100 d-flex c-bg">
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">ID</label><br>
					<input type="text" value="<?php echo intval(getLastInsertedID($dbcon,"company","id")+1); ?>" readonly>
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Firmen Logo hochladen</label><br>
					<input type="file" name="logofile">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Name</label><br>
					<input type="text" name="cname">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">HRB Nummer</label><br>
					<input type="text" name="chrb">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">E-Mail</label><br>
					<input type="email" name="cemail">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Telefonnummer</label><br>
					<input type="text" name="cphone">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Umsatzsteuer Nr.</label><br>
					<input type="text" name="ctaxno">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Strasse</label><br>
					<input type="text" name="cstreet">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Haus Nr.</label><br>
					<input type="text" name="chno">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Stadt</label><br>
					<input type="text" name="ccity">
				</div>
            <div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">PLZ</label><br>
					<input type="text" name="czip">
				</div>
				<div id="text-fields" class="d-flex"></div>
			</div>
			<div class="my-100 mt-100 d-flex c-bg">
				<div class="d-flex">
	              <div class="d-block mr-20 ml-20 mt-20">
				         <label class="font-weight-bold">Bitte Feld-Name eingeben</label><br>
				         <input type="text" id="fieldname" placeholder="(Beispiel: Fax Nr.)">
				     </div>
					  <div class="d-block mr-20 ml-20 mt-50">
					      <label class="tasks-list-item">
					      <input type="checkbox" id="fieldCheckbox" class="tasks-list-cb">
					      <span class="tasks-list-mark"></span>
					      </label>
					   </div>
				</div>
			</div>
			<div class="my-100 d-flex c-bg">
			     <a href="javascript:void(0)" class="btn bg-green btncreate w-40">Neues Feld hinzufügen +</a>
		   </div>
			<div class="my-100 mt-50">
				<input type="submit" name="submit" value="Speichern" class="btn bg-blue cur-pointer w-80 border-none fs-18" onclick="createCompany();">
			</div>
		</form>
	</section>
	<script src="../js/functions.js"></script>
	<script src="../js/company.js"></script>
	<script>
			btnCreate=queryOne('.btncreate');
	      btnCreate.addEventListener("click",function(){
	      	var fieldname=getID('fieldname').value;
	         var fieldCheckbox=getID('fieldCheckbox').checked;
	         console.log(fieldCheckbox);
	         if(fieldname == ''){
	             var msg="Bitte Feld-Name eingeben";
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
	</div>
</body>
</html>