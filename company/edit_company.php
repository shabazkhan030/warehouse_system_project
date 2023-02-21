<?php
   session_start();
   if(!isset($_SESSION["isLogedIn"])){
     header("location:../index.php");
   }
   if(!isset($_SESSION["company"]["cid"])){
     header("location:company.php");
   }
   $cid=intval(base64_decode($_SESSION["company"]["cid"])/99999);
   include "../dbcon/dbcon.php"; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Company Profile</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
	<div id="toast"></div>
	 <?php include 'common/headtop.php'; ?>
	<!--<div class="my-100 mt-20">
		<div class="d-flex">
			 <div class="d-block w-60">
			 	 <img src="../img/company_uploads/<?php echo $_SESSION["company"]["clogo"] ?>" style="width:120px;height:auto">
			 	 <h3 class="ml-10 mt-10"><?php echo $_SESSION["company"]["cname"] ?></h3>
			 </div>
			 <div class="mt-50 w-40 text-right">
			 	<a href="company_logout.php" class="btn bg-blue">Zurück</a>
			 </div>
		</div>
	</div>-->
    	<header class="my-100 mt-50">
		 <ul class="d-flex bg-light list-style-none p-20">
			 	<li class=" "><a href="employee/employee.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Mitarbeiter</a></li>
			 	<li class=""><a href="inventory.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark ">Warenbestand</a></li>
			 	<li class=""><a href="office_order.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark ">Bestellung</a></li>
			 	<li class=""><a href="vehicle_list.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Fahrzeugliste</a></li>
			 	<li class=""><a href="edit_company.php" class="active-company-profile text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Firmenprofil</a></li>
			 	<!--<li class=""><a href="#" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Statistics</a></li>-->
		 </ul>
	</header>
	<section class="form-section">
<?php 
  $qry=mysqli_query($dbcon,"SELECT * FROM company WHERE id='$cid'");
  $arr=mysqli_fetch_assoc($qry);
 ?>
		<form id="editCompanyProfile">
			<div class="mt-100 my-100 d-flex">
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">ID</label><br>
					<input type="hidden" name="pid" id="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>">
					<input type="hidden" name="cid" id="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
					<input type="text" value="<?php echo $arr["id"] ?>" disabled>
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Firmen Logo hochladen</label>
					<br>
					<?php 
					if($arr["logo"] !== ''){
				    ?>
				    <div id="companyLogo" style="width:270px;display:flex;align-items: center;">
				    	<a onclick="changeInputFile();" class="bg-red text-light mt-10 p-10 text-decoration cur-pointer ml-10">Neues logo laden</a>
				    	<img src="../img/company_uploads/<?php echo $arr["logo"]; ?>" style="width:100px;height:100px;" class="ml-10">
					      
				    </div>
                <div id="logoInsert" style="display:none">
                	  <input type="file" name="logofile" accept="image/*">
                </div>
				    <?php
				     }else{
					 ?>    
                   <input type="file" name="logofile" accept="image/*">
					<?php
			        } 
			        ?>
					
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Firmenname</label>
					<br>
					<input type="text" name="cname" value="<?php echo $arr["company_name"] ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">HRB Nr.</label>
					<br>
					<input type="text" name="chrb" value="<?php echo $arr["hrb"] ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">E-Mail Adresse</label>
					<br>
					<input type="email" name="cemail" value="<?php echo $arr["email"] ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Tel.</label>
					<br>
					<input type="text" name="cphone" value="<?php echo $arr["phone"] ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Umsatzsteuer Nr.</label>
					<br>
					<input type="text" name="ctaxno" value="<?php echo $arr["tax_number"] ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Strasse</label><br>
					<input type="text" name="cstreet" value="<?php echo $arr["street"] ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Haus Nr.</label><br>
					<input type="text" name="chno" value="<?php echo $arr["house_no"]; ?>">
				</div>
				<div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">Stadt</label><br>
					<input type="text" name="ccity" value="<?php echo $arr["city"] ?>">
				</div>
            <div class="d-block mr-20 ml-20 mt-20">
					<label class="font-weight-bold">PLZ</label><br>
					<input type="text" name="czip" value="<?php echo $arr["zip_code"] ?>">
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
			<div class="my-100 mt-50">
				<input type="submit" name="submit" value="Speichern" class="btn-sm bg-grey cur-pointer" onclick="EditCompany();" style="border:1px solid grey">
			</div>
		</form>
	</section>
	<section class="history-section my-100 mt-50 mb-50">
			<h3>Verlauf</h3>
			<table class="mt-20 history-tableEc" style="width:100%;border-collapse: collapse;">
				<thead>
					<tr>
						<td>Was</td>
						<td>Vorher</td>
						<td>Danach</td>
						<td>Geändert am</td>
						<td>Sachbearbeiter</td>
					</tr>
				</thead>
				<tbody id="companyHistoryTable">
<!-- 					<tr>
						<td>Position</td>
						<td>Before</td>
						<td>Later</td>
						<td>Date</td>
						<td>Staff Employee</td>
					</tr> -->
				</tbody>
			</table>
	</section>
	<script src="../js/functions.js"></script>
	<script src="../js/company.js"></script>
	<script>
		checkForTextfield();
		loadCompanyHistoryTable();

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
    
    function changeInputFile(){
    	var companylogo=getID("companyLogo");
    	var getLogo=getID("logoInsert");

    	getLogo.style.display="block";
    	companylogo.style.display="none";

    }


	</script>
	<?php include "common/footer.php"; ?>
</body>
</html>