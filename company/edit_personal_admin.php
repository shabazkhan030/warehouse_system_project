<?php
   session_start();
   if(!isset($_SESSION["isLogedIn"])){
   	header("location:../index.php");
   }
   if($_SESSION['role'] != 'super_admin'){
   	 header("location:company.php");
   }
   include "../dbcon/dbcon.php";
   $pid=base64_decode($_SESSION["isLogedIn"])/88888;
   $aid=base64_decode($_GET["aid"])/88888;
   $qry=mysqli_query($dbcon,"SELECT * FROM personal_admin WHERE id='$aid'");
   $arr=mysqli_fetch_assoc($qry);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sachbearbeiter bearbeiten</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
	<div id="toast"></div>
	 <div class="neu-top-2">
	<section class="form-section">
		<form id="editpersonaladmin">
		<div class="my-100 d-flex">
		   <div class="mt-50 w-40">
		  <!-- EDITED BY WHOM -->
		   	  <!--    -->
		   	   <input type="hidden" name="pid" id="pid" value="<?php echo $_SESSION["isLogedIn"];?>">
		   	   <!-- WHICH ADMIN IS EDITED -->
		   	   <input type="hidden" name="aid" id="aid" value="<?php echo $aid;?>">
		   	   <h2 class="bg-lightblue text-light p-10">Sachbearbeiter bearbeiten</h2>
		   </div>
		   <div class="mt-50 w-60 text-right">
		   	   <a href="company.php" class="btn bg-red ml-20 mt-20">&nbsp;schliesen &nbsp;</a>
		   </div>	
		</div>
		<div class="mt-100 my-100 d-flex">
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">ID</label><br>
				<input type="text" value="<?php echo $pid; ?>" readonly>
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Erstellt am </label><br>
				<input type="text" name="created-data" value="<?php echo date("d.m.Y",strtotime($arr["created_date"]))?>" readonly>
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Geschlossen am</label><br>
				<input type="text" name="close-data" value="<?php  echo ($arr["closed_date"] == "0000-00-00") ? '' : date("d.m.Y",strtotime($arr["closed_date"])) ;?>" readonly>
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Status</label><br>
				<select name="statusSelect">
						<option value="1" <?php echo ($arr["status"] == 1) ? 'selected' : ''; ?> >aktiv</option>
						<option value="0" <?php echo ($arr["status"] == 0) ? 'selected' : ''; ?>>deaktiviert</option>
				</select>
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Nachname</label><br>
				<input type="text" name="surname" value="<?php echo $arr["surname"]?>">
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Vorname</label><br>
				<input type="text" name="firstname" value="<?php echo $arr["firstname"]?>">
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Firma wählen</label><br>
					 <select name="selectcompany" class="p-0">
					 	<?php
              $qry1=mysqli_query($dbcon,"SELECT * FROM company");
              while($arr1=mysqli_fetch_assoc($qry1)){
              	if($arr1["id"] == $arr["cid"]){
              		echo "<option value='".$arr1["id"]."' selected>".$arr1["company_name"]."</option>";
              	}else{
              		echo "<option value='".$arr1["id"]."'>".$arr1["company_name"]."</option>";
              	}
              }
					 	 ?>
		   	    </select>
      </div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">E-Mail Adresse</label><br>
				<input type="email" name="email" value="<?php echo $arr["email"]?>" readonly>
			</div>
			<?php if($_SESSION['role']== 'super_admin'): ?>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Welche Rolle</label><br>
				<select name="role">
					<?php if($arr['role'] == 'personal_admin'){ ?>
					<option value="personal_admin" selected>Sachbearbeiter</option>
					<option value="super_admin">Administartor</option>
					<?php }else{ ?>
                    <option value="super_admin">Super Admin</option>
					<?php } ?>
				</select>
			</div>
			<?php endif; ?>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Passwort</label><br>
				<input type="password" name="password" value="">
			</div>
			<div class="d-block mr-20 ml-20 mt-20">
				<label class="font-weight-bold">Passwort widerholen</label><br>
				<input type="password" name="rpassword" value="">
			</div>
		</div>
		<div id="text-fields" class="my-100 d-flex">
			<?php
         $qry2=mysqli_query($dbcon,"SELECT * FROM personal_admin_fields WHERE pid='$aid'");
         $num_rows=mysqli_num_rows($qry2);
         if($num_rows > 0){
            while($arr2=mysqli_fetch_assoc($qry2)){
      ?>
			     <div class="d-flex">
             <div class="d-block mr-20 ml-20 mt-20">
	             <label class="font-weight-bold"><?php echo $arr2["textval"]; ?></label><br>
	             <input type="text" class="textfield" value="<?php echo $arr2['textname'] ?>">
	             <input type="hidden" class="fieldnamevalue" value="<?php echo $arr2["textval"]; ?>">
	             <input type="hidden" class="checkboxfeild" value="<?php echo $arr2["visible"] ?>">
             </div>
           </div>
      <?php
            }
         }
      ?>
		</div>
		<!-- text-fields -->
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
			<input type="submit" value="Speichern" class="btn bg-grey cur-pointer" onclick="EditPersonalAdmin()" style="border:1px solid grey">
		</div>
		</form>
	</section>
	<section class="history-section my-100 mt-50 mb-50">
			<h3>Verlauf</h3>
			<table class="mt-20 history-tableEgss" style="width:100%;border-collapse: collapse;">
				<thead>
					<tr>
						<td>Was</td>
						<td>Vorher</td>
						<td>Danach</td>
						<td>geändert am</td>
						<td>Sachbearbeiter</td>
					</tr>
				</thead>
				<tbody id="PAHistoryTable">
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
	<script src="../js/personal_admin.js"></script>
	<script>
		loadPAHistoryTable();
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
	</div>
</body>
</html>


