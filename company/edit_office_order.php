<?php
   session_start();
   if(!isset($_SESSION["isLogedIn"])){
     header("location:../index.php");
   }
   if(!isset($_SESSION["company"]["cid"])){
     header("location:company.php");
   }
   // if(!isset($_GET['oid'])){
   //   header("location:office_order.php");
   // }
   include "../dbcon/dbcon.php"; 
   include "../func/functions.php";
   $oid=intval(base64_decode($_GET['oid']));
   $qry=mysqli_query($dbcon,"SELECT oo.*,p.firstname,p.surname FROM office_order oo LEFT JOIN personal_admin p ON oo.signed_by=p.id WHERE oo.id='$oid'");
 	if(mysqli_num_rows($qry) <= 0 ){
    header("location:office_order.php");
 	}
  $arr=mysqli_fetch_assoc($qry); 		
   $cid=intval(base64_decode($_SESSION["company"]["cid"])/99999);
   $pid=base64_decode($_SESSION["isLogedIn"])/88888;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Bestellung Bearbeiten</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
	<div id="toast"></div>
  <?php include "common/headtop.php"; ?>
	<header class="my-100 mt-50">
		 <ul class="d-flex bg-light list-style-none p-20">
			 	<li><a href="employee/employee.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Mitarbeiter</a></li>
			 	<li><a href="inventory.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark">Warenbestand</a></li>
			 	<li><a href="office_order.php" class="active-company-profile text-decoration p-20 font-weight-bold border-right-1 text-dark ">Bestellung</a></li>
			 	<li><a href="vehicle_list.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Fahrzeugliste</a></li>
			 	<li><a href="edit_company.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Firmenprofil</a></li>
			 	<!--<li><a href="#" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Statistics</a></li>-->
		 </ul>
	</header>
	<form id="insertOfficeOrder">
	<div class="my-100">
		<div class="d-flex justify-content-between">
		<div class="w-80 mt-50">
			<div class="ml-20">
				<div class="d-flex">
					<div>
						<small href="javascript:void(0)" class="btn-sm bg-lightblue">Bestellung Bearbeiten</small>
					   <small href="javascript:void(0)" class="btn-sm bg-grey">ID 0<?php echo $arr["id"];?></small>
					   <input type="hidden" id="oid" name="oid" value="<?php echo $oid; ?>">
					   <input type="hidden" id="cid" name="cid" value="<?php echo $_SESSION["company"]["cid"];?>">
					   <input type="hidden" id="pid" name="pid" value="<?php echo $_SESSION["isLogedIn"];?>">
					</div>
					<div class="ml-50">
						<label class="font-weight-bold mr-20">Date</label>
						<input type="text" name="submittedBy" value="<?php echo date("d.m.Y",strtotime($arr['submitted'])); ?>" style="margin-top:-10px;" readonly>
					</div>
				</div>
			</div>
			<table class="w-100 table-light" id="itemListTable">
				<thead>
					<tr>
						<td>Akzeptiert</td>
								<td>Wunschmenge</td>
								<td>Material / Produkt </td>
								<td>Gr&ouml;&szlig;e</td>
								<td>Sachbearbeiter</td>
								<td>Anmerkung</td>
								<td>Freigegebene Menge</td>
							  <td>St&uuml;ckpreis</td>
							  <td>Gesamt</td>
								<td>L&ouml;schen</td>
					</tr>
				</thead>
				<tbody id="tbodyitems">
					<?php
					$qry=mysqli_query($dbcon,"SELECT ood.*,e.id AS emp_id,e.firstname,e.surname FROM office_order_details ood LEFT JOIN employee e ON ood.eid=e.id WHERE ood.order_id='$oid'");
					if($qry){
						if(mysqli_num_rows($qry) > 0){
              while($arr1=mysqli_fetch_assoc($qry)){
              	$emp_id=(empty($arr1["emp_id"])) ? "NO" : $arr1["emp_id"];
              	$emp_name=(empty($arr1["emp_id"])) ? "NO" : $arr1["firstname"].' '.$arr1["surname"];
          ?>  
            </tr>
                <td>							    
				 <label class="tasks-list-item">
					<input type="checkbox" name="task_1" class="checkboxofficeorder tasks-list-cb" <?php if($arr1["chbx"] == 1){ echo "checked"; } ?> readonly>
					<span class="tasks-list-mark"></span>
				 </label>
			    </td>
                <td><input type="text" class="qtyofficeorder" name="qtyofficeorder[]" value="<?php echo $arr1["desired_amount"]; ?>" style="width:60px;padding:3px;" onkeypress="return onlyNumberKey(event)"></td>
                <td>
                    <input type="hidden" name="articleOfficeOrder[]" value="<?php echo $arr1["inventory_article"]; ?>"><?php echo $arr1["inventory_article"]; ?> 
								</td>
              	<td><input type="hidden" name="sizeofficeOrder[]" value="<?php echo $arr1["size"]; ?>"><?php echo $arr1["size"]; ?></td>
              	<td><input type="hidden" name="employeeOfficeorder[]" value="<?php echo $emp_id ?>"><?php echo $emp_name; ?></td>
              	<td><input type="text" name="remarkofficeOrder[]" style="width:200px;" value="<?php echo $arr1["remarks"]?>"></td>
              	<td><input type="text" name="amtofficeOrder[]" value="<?php echo $arr1["amt"]?>" style="width:80px;" onkeypress="return onlyNumberKey(event)" onkeyup="calcTotal(this)"></td>
              	<td><input type="text" class="piecePrice" name="piecePrice[]" onkeyup="calcTotal(this)" value="<?php echo $arr1["piece_price"]; ?>" style="width:80px;" onkeypress="return allowedNumberDecimal(this,event)"></td>
              	<td><input type="text" value="<?php echo $arr1["piece_price"]*$arr1["amt"]?>" style="width:80px;" onkeypress="return onlyNumberKey(event)"></td>
              	<td>
            <?php if(empty($arr["sign_director"]) || empty($arr["sign_director"])){?>
              		<a href="javascript:void(0)" data-delete='<?php echo $arr1["id"]; ?>' class="cancel-btn text-decoration deleteitemList" aria-label="Close"></a>
            <?php }else{ ?>
                  <a href="javascript:void(0)" class="cancel-btn text-decoration" aria-label="Close"></a>
              <?php	  }
            ?>

              	</td>
            </tr>
            <?php
               }
						}else{
							// echo '<tr class="text-center">
              //          <td colspan="8">Keine Bestellung gefunden...</td>
							//       </tr>';
						}
					}
					?>
				</tbody>
			</table>
			<div class="text-right mt-20">
				<a href="javascript:void(0)" class="bg-green btn-sm" onclick="createNewItemList()">Neues Feld +</a>
			</div>
			<div class="d-flex justify-content-between">
				<div class="d-flex">
					<div class="d-block">
					  <label>Unterschrift Sachbearbeiter</label><br>
					  <?php 
              if($arr["sign_purchaser"] == ''){
					   ?>

						   <div id="signature-pad-purchaser">
						     <div style="border:solid 2px black; width:360px;height:110px;padding:3px;position:relative;">
						        <div id="note_purchaser" onmouseover="purchaser();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
						        <canvas id="the_canvas_purchaser" width="350px" height="100px"></canvas>
						     </div>
						     <div class="d-flex justify-content-between" style="margin:10px;">
						        <input type="hidden" id="signature_purchaser" name="signature_puchaser">
						        <button type="button" id="clear_btn_purchaser" class="p-5" data-action="clearpurchaserSign"> Löschen</button>
						        <input type="button" id="save_btn_purchaser" class="p-5" data-action="save-png" value="Speichern">
						     </div>
						   </div>
					<?php }else{?>
						<div id="signaturePurchaserWrapper" style="border:solid 2px black;width:360px;height:110px;padding:3px;">
							  <img src="../img/signature_uploads/<?php echo $arr["sign_purchaser"];?>" style="width:100%;" id="signaturepurchaserimage">
						</div>
						<p><?php echo $arr["firstname"]." ".$arr["surname"]; ?></p>
					<?php } ?>
					</div>
					<div class="d-block ml-50">
					  <label>Unterschrift Geschäftsführer</label><br>
					  <?php 
              if($arr["sign_director"] == ''){
					   ?>
							   <div id="signature-pad-director">
							     <div style="border:solid 2px black; width:360px;height:110px;padding:3px;position:relative;">
							        <div id="note_director" onmouseover="director();" style="color:grey;text-align:center;">Die Unterschrift sollte innerhalb der Box sein</div>
							        <canvas id="the_canvas_director" width="350px" height="100px"></canvas>
							     </div>
							     <div class="d-flex justify-content-between" style="margin:10px;">
							        <input type="hidden" id="signature_director" name="signature_director">
							        <button type="button" id="clear_btn_managing_director" class="p-5" data-action="cleardirectorSign"> Löschen</button>
							        <input type="button" id="save_btn_managing_director" class="p-5" data-action="save-png" value="Speichern"> 
							     </div>
							   </div>
						<?php }else{?>
						<div id="signatureDirectorWrapper" style="border:solid 2px black;width:360px;height:110px;padding:3px;"> 
							  <img src="../img/signature_uploads/<?php echo $arr["sign_director"];?>" style="width:100%;" id="signaturedirectorimage">  
						</div>
						<p style="margin-bottom:0px;"><?php echo getLastChangeEmplName($dbcon,$arr["signed_by_dir"]) ?></p>
					<?php } ?>
					</div>
				</div>
				<div class="mt-50">
					<?php if(empty($arr["sign_director"]) || empty($arr["sign_director"])){
					?>
					<input type="submit" class="btn-sm bg-grey cur-pointer" onclick="saveOrderForm()" value="Alles Speichern">
					<?php }else{ ?>
					<a href="office_order.php" class="btn bg-red cur-pointer">Abgeschlossen</a>
				    <?php } ?>
				</div>
			</div>

	    </div>
    	<div class="w-15">
			<p>Letzte &Auml;nderung:</p>
			<p class="font-weight-bold"><?php echo getLastChangeDate($dbcon,$oid) ?></p>
			<p><?php echo getLastChangeEmplName($dbcon,$arr["pid"]) ?></p>
		</div>
		</div>
	</div>
</form>
<div id="confirmModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <!-- <span class="closeModal">&times;</span> -->
    <div class="text-center pt-50">
    	<p>Are you sure you want to delete ?</p>
    	<input type="hidden" id="dataDelete" value="">
    	<div class="d-flex" style="justify-content:center;padding-top:30px;padding-bottom:20px;">
    		 <a href="javascript:void(0)" class="btn bg-green" onclick="confirmDelete()">Yes</a>
    		 <a href="javascript:void(0)" class="closeModal btn bg-red mr-20">Close</a>
    	</div>
    </div>
  </div>
</div>
<!-- PRINT FUNCTION -->
<?php include 'print/office_order.php'; ?>

	<script src="../js/functions.js"></script>
	<script src="../js/signature.js"></script>
	<script src="../js/office_order.js"></script>
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

var tableID=getID("itemListTable");
function createNewItemList(){
var tbodyitems=getID("tbodyitems");
 html='<tr>';
		html+='<td>';							    
		html+='<label class="tasks-list-item">';
		html+='<input type="checkbox" name="task_1" value="1" class="tasks-list-cb checkboxofficeorder">';
		html+='<span class="tasks-list-mark"></span>';
		html+='</label>';
		html+='</td>';
		html+='<td><input type="text" name="qtyofficeorder[]" class="qtyofficeorder" value="" style="width:60px;padding:3px;"></td>';
		html+='<td>';
		html+='<input type="text" name="articleOfficeOrder[]" class="articleOfficeOrder" onkeyup="getArticleList(this);"  value="" style="width:180px;;padding:3px;">';
		html+='<div class="articleFetch" style="background:#fafafa;width:180px;position:absolute;z-index:999"></div>'
		html+='</td>';
		html+='<td>';
		html+='<input type="text" name="sizeofficeOrder[]" class="sizeofficeOrder" value="" style="width:60px;padding:3px;">';
		html+='</td>';
		html+='<td><select name="employeeOfficeorder[]" class="employeeOfficeorder" style="width:180px;padding:3px;">'
		html+='<?php echo fillEmployee($dbcon,$cid); ?>';
		html+='<select>';
		html+='</td>';
		html+='<td><input type="text" name="remarkofficeOrder[]" class="remarkofficeOrder"  style="width:200px;padding:3px;"></td>';
		html+='<td><input type="text" style="width:80px;" name="amtofficeOrder[]" class="amtofficeOrder" style="width:100px;padding:3px;" onkeypress="return onlyNumberKey(event)" onkeyup="calcTotal(this)"></td>';
		html+='<td><input type="text" style="width:80px;" name="piecePrice[]" onkeypress="return allowedNumberDecimal(this,event)" class="piecePrice" onkeyup="calcTotal(this)" style="width:100px;padding:3px;"></td>';
		html+='<td><input type="text" style="width:80px;" class="totalPrice" style="width:100px;padding:3px;"></td>';
		html+='<td><a href="javascript:void(0)" class="cancel-btn text-decoration deleteitemList" aria-label="Close"></a></td>';
		html+='</tr>';
		tbodyitems.insertRow().innerHTML=html;
}
  tableID.addEventListener("click",deleteitemListRow);

 
	</script>
	<?php 
   if(empty($arr["sign_purchaser"])){
	?>
   <script>
   	signaturePurchaser();
   </script>
  <?php
   }
  ?>
  <?php 
   if(empty($arr["sign_director"])){
	?>
 <script>
 	<?php if($_SESSION['role'] == 'super_admin') : ?>
   signatureDirector();

  <?php endif; ?>
 </script>
  <?php
   }
  ?>
  <?php include "common/footer.php"; ?>
</body>
</html>

