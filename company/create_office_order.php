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
   include "../func/functions.php"; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Neue Bestelung</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
	<div id="toast"></div>
	 <?php include "common/headtop.php"; ?>
	<!--<div class="my-100 mt-20">
		<div class="d-flex">
			 <div class="d-block w-60 mt-50">
			 	 <h2 class="ml-20"><?php echo $_SESSION["company"]["cname"] ?></h2>
			 </div>
			 <div class="mt-50 w-40 text-right">
			 	<a href="company_logout.php" class="btn bg-blue">Zurück</a>
			 </div>
		</div>
	</div>-->
	<header class="my-100 mt-50">
		 <ul class="d-flex bg-light list-style-none p-20">
			 	<li><a href="employee/employee.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Mitarbeiter</a></li>
			 	<li><a href="inventory.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark ">Warenbestand</a></li>
			 	<li><a href="office_order.php" class="active-company-profile text-decoration p-20 font-weight-bold border-right-1 text-dark ">Bestellung</a></li>
			 	<li><a href="vehicle_list.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Fahrzeugliste</a></li>
			 	<li><a href="edit_company.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Firmenprofil</a></li>
			 	<!--<li><a href="#" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Statistics</a></li>-->
		 </ul>
	</header>
	<div class="my-100">
		<form id="insertOfficeOrder">
		<div class="d-flex justify-content-between">
		<div class="mt-50">
			<div class="ml-20">
				<div class="d-flex">
					<div>
						<small href="javascript:void(0)" class="btn-sm bg-lightblue">Bestellformular Büro</small>
					   <small href="javascript:void(0)" class="btn-sm bg-grey">ID 0<?php echo getLastInsertedID($dbcon,"office_order","id")+1;?></small>
					   <input type="hidden" id="officeOrderInsert" value=<?php echo getLastInsertedID($dbcon,"office_order","id")+1; ?>>
					   <input type="hidden" id="cid" name="cid" value="<?php echo $_SESSION["company"]["cid"];?>">
					   <input type="hidden" id="pid" name="pid" value="<?php echo $_SESSION["isLogedIn"];?>">
					</div>
					<div class="ml-50">
						<label class="font-weight-bold mr-20">Datum</label>
						<input type="date" name="submittedBy" id="submittedBy"  style="margin-top:-10px;">
					</div>
				</div>
			</div>
			<div style="overflow-x:scroll;overflow-y:hidden;">
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

						</tbody>
					</table>
			</div>
			<div class="text-right mt-20">
				<a href="javascript:void(0)" class="bg-green btn-sm" onclick="createNewItemList()">Neue Bestellung +</a>
			</div>
			<div class="d-flex justify-content-between">
				<div class="d-flex">
					<div class="d-block">
					  <label>Unterschrift Sachbearbeiter</label><br>
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
							

					</div>
					<div class="d-block ml-50">
					  <label>Unterschrift Geschäftsführer</label><br>
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
					</div>
				</div>
				<div class="mt-50">
					<input type="submit" class="cur-pointer btn-sm bg-grey" onclick="saveOrderForm()" value="Alles Speichern">
				</div>
			</div>

	    </div>
    	<div class="mt-50 ml-20">
		</div>
		</div>
	</form>
	</div>
	<script src="../js/functions.js"></script>
	<script src="../js/signature.js"></script>
	<script src="../js/office_order.js"></script>
	<script>
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
		html+='<td><input type="text" name="qtyofficeorder[]" class="qtyofficeorder" value="" style="width:60px;padding:3px;" onkeypress="return onlyNumberKey(event)"></td>';
		html+='<td>';
		html+='<input type="text" name="articleOfficeOrder[]" class="articleOfficeOrder" onkeyup="getArticleList(this);"  value="" style="width:180px;;padding:3px;">';
		html+='<div class="articleFetch" style="background:#fafafa;width:180px;position:absolute;z-index:999"></div>'
		html+='</td>';
		html+='<td>';
		html+='<input type="text" name="sizeofficeOrder[]" class="sizeofficeOrder" value="" style="width:60px;padding:3px;">';
		html+='</td>';
		html+='<td><select name="employeeOfficeorder[]" class="employeeOfficeorder" style="width:180px;padding:1px;">'
		html+='<?php echo fillEmployee($dbcon,$cid); ?>';
		html+='<select>';
		html+='</td>';
		html+='<td><input type="text" name="remarkofficeOrder[]" class="remarkofficeOrder" style="width:200px;padding:3px;"></td>';
		html+='<td><input type="text" name="amtofficeOrder[]" class="amtofficeOrder" style="width:60px;padding:1px;" onkeypress="return onlyNumberKey(event)" onkeyup="calcTotal(this)"></td>';
		html+='<td><input type="text" style="width:80px;" name="piecePrice[]" onkeypress="return allowedNumberDecimal(this,event)" class="piecePrice" onkeyup="calcTotal(this)" style="width:100px;padding:1px;"></td>';
		html+='<td><input type="text" style="width:80px;" class="totalPrice" style="width:100px;padding:3px;"></td>';
		html+='<td><a href="javascript:void(0)" class="cancel-btn text-decoration deleteitemList" aria-label="Close"></a></td>';
		html+='</tr>';
		tbodyitems.insertRow().innerHTML=html;
}
  tableID.addEventListener("click",deleteitemListRow);

  function deleteitemListRow(e){
  	if(!e.target.classList.contains("deleteitemList")){
  		return;
  	}else{
  		const btn=e.target;
  		btn.closest("tr").remove();
  	}
  }
  signaturePurchaser();
<?php if($_SESSION['role'] == 'super_admin') : ?>
  signatureDirector();
<?php endif; ?>

	</script>
	<?php include "common/footer.php"; ?>
</body>
</html>


