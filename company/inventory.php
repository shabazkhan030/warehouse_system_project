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
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Warenbestand</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
	<div id="toast"></div>
	
	  <?php include "common/headtop.php"; ?>

	
	
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
			 	<li class=""><a href="employee/employee.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Mitarbeiter</a></li>
			 	<li class=""><a href="inventory.php" class="active-company-profile text-decoration p-20 font-weight-bold border-right-1 text-dark ">Warenbestand</a></li>
			 	<li class=""><a href="office_order.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark ">Bestellung</a></li>
			 	<li class=""><a href="vehicle_list.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Fahrzeugliste</a></li>
			 	<li class=""><a href="edit_company.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Firmenprofil</a></li>
			 	<!--<li class=""><a href="#" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Statistics</a></li>-->
		 </ul>
	</header>

	<div class="my-100 mt-20 mb-20">
		<div class="d-flex">
			<p class="bg-lightblue text-decoration text-light" style="padding: 10px;">Warenbestand</p>
		</div>
	</div>
	<div class="my-100 mt-50">
		<div class="text-right">
			<a href="javascript:void(0)" class="btn-sm bg-orange" onclick="createArticle();">Produkt anlegen +</a>
		</div>
		<!-- <form id="inventoryForm"> -->
			<input type="hidden" id="pid" value="<?php echo $_SESSION["isLogedIn"]; ?>" >
			<input type="hidden" id="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>" >
			<table class="PrintpdfTable w-100 mt-20 w-100" id="inventoryTable">
				<thead>
					<tr>
						<td>Produkte</td>
						<td>Gr&ouml;&szlig;e</td>
						<td>Menge</td>
						<td>Defekt</td>
						<td>Verloren</td>
						<td>Ausgabe</td>
						<td>Restmenge</td>
						<td>St&uuml;ckpreis</td>
						<td>Gesamtpreis</td>
						<td>&nbsp;</td>
						<td>Firmentransport /Menge</td>
					</tr>
				</thead>
				<tbody id="inventoryBody">
					<?php 
				   $cid=base64_decode($_SESSION["company"]["cid"])/99999;
                     $qry=mysqli_query($dbcon,"SELECT * FROM inventory WHERE cid='$cid'");
                     if($qry){
                       	$rows=mysqli_num_rows($qry);
                       	if($rows > 0){
                   
                           while($arr=mysqli_fetch_assoc($qry)){
                           	echo '<tr class="mt-20">';
                           	?>
			  <td><input type="text" class="article" value="<?php echo $arr["article"] ?>" style="width:150px;padding:3px;" readonly></td>
							<td><input type="text" class="size" value="<?php echo $arr["size"] ?>" style="width:60px;padding:3px;" readonly></td>
							<td><input type="text" class="crowd" value="<?php echo $arr["crowd"] ?>" style="width:60px;padding:3px;" onkeypress="return onlyNumberKey(event)"  readonly></td>
							<td><input type="text" class="defect" value="<?php echo $arr["defect"] ?>" style="width:60px;padding:3px;" onkeypress="return onlyNumberKey(event)"  readonly></td>
							<td><input type="text" class="lost" value="<?php echo $arr["lost"] ?>" style="width:60px;padding:3px;" onkeypress="return onlyNumberKey(event)"  readonly></td>
							<td><input type="text" class="output_item" value="<?php echo $arr["output_item"] ?>" onkeypress="return onlyNumberKey(event)"  style="width:60px;padding:3px;" readonly></td>
							<td><input type="text" class="remaining_item" value="<?php echo $arr["remaining_item"] ?>" style="width:60px;padding:3px;" readonly></td>
							<td><input type="text" class="piece_price" value="<?php echo $arr["piece_price"] ?>" onkeypress="return allowedNumberDecimal(this,event)" style="width:100px;padding:3px;" readonly></td>
							<td></td>
							<td>
								<div class="d-flex">
								    <a href="javascript:void(0)" iid="<?php echo $arr["id"]; ?>" class="bg-skyblue editInventory text-decoration text-light cur-pointer p-10">Bearbeiten</a>
								    <input type="button" iid="<?php echo $arr["id"]; ?>" class="bg-green text-decoration text-light cur-pointer p-10 ml-10 save" value="Speichern" style="border:1px solid #ffffff">
								</div>
							</td>
							<td>
								<select style="width:100px;padding:2px;">
	                         <?php echo fillcompanywithnotid($dbcon,$cid); ?>
								</select>
								<input type="text" style="width:60px;padding:4px;">
								<a href="javascript:void(0)" class="btn bg-red transport">Transport</a>
								<input type="hidden" value="<?php echo $arr["id"]; ?>">
							</td>
						<?php
						echo "</tr>";
                           }
                        }               
                     }  
					?>
				</tbody>
				<tfoot>
					<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
						<td colspan="5" class="text-right font-weight-bold">Total</td>
						
						<td colspan="2" class="font-weight-bold" id="totalAll">0000</td>
					</tr>
				</tfoot>
			</table>
		<!-- </form> -->

        <div class="mt-100 ml-20">
        	<h3 class="ml-20">History/Veränderung</h3>
        	<table class="PrintpdfTable w-100 mt-20 mb-50">
				<thead>
					<tr>
						<td>Produkt</td>
						<td>Menge Vorher / Nacher </td>
						<td>Datum</td>
						<td>Sachbearbeiter</td>
					</tr>
				</thead>
				<tbody>
             <?php 
              $qry=mysqli_query($dbcon,"SELECT i.*,pa.firstname,pa.surname FROM history_inventory i INNER JOIN personal_admin pa ON i.pid=pa.id WHERE i.cid='$cid' ORDER BY i.id DESC");
               if($qry){
               	if(mysqli_num_rows($qry) > 0){
               		while($arr=mysqli_fetch_assoc($qry)){
            ?>
	                  <tr>
	                        <td><?php echo $arr["article_name"] ?></td>
	                        <td><?php echo $arr["remaining"] ?>/<span class="text-green"><?php echo $arr["crowd"] ?><span></td>
	                        <td><?php echo date("d.m.Y",strtotime($arr["data"])) ?></td>
	                        <td><?php echo $arr["firstname"].' '.$arr["surname"]; ?></td>
	               	</tr>
	         <?php
	               	}
               	}else{
            ?>
               		<tr><td>Keine Daten verfügbar..</td>
               		</tr>
            <?php
               	}
               }
            ?>
				</tbody>
			</table>
      </div>
	</div>
	<script src="../js/functions.js"></script>
	<script src="../js/inventory.js"></script>
	<script>
	function createArticle(){
	var tbody=getID('inventoryBody');
	var html='<tr>';
		html+='<td><input type="text" class="article" style="width:150px;padding:3px;"></td>';
		html+='<td><input type="text" class="size" style="width:60px;padding:3px;"></td>';
		html+='<td><input type="text" class="crowd" style="width:60px;padding:3px;" onkeypress="return onlyNumberKey(event)"></td>';
		html+='<td><input type="text" class="defect" style="width:60px;padding:3px;" onkeypress="return onlyNumberKey(event)"></td>';
		html+='<td><input type="text" class="lost" style="width:60px;padding:3px;" onkeypress="return onlyNumberKey(event)"></td>';
		html+='<td><input type="text" class="output_item" onkeypress="return onlyNumberKey(event)"  style="width:60px;padding:3px;"></td>';
		html+='<td><input type="text" class="remaining_item" style="width:60px;padding:3px;" readonly></td>';
		html+='<td><input type="text" class="piece_price" onkeypress="return allowedNumberDecimal(this,event)" style="width:100px;padding:3px;"></td>';
		html+='<td></td>';
		html+='<td>';
	   html+='<div class="d-flex">';
	   html+='<a href="javascript:void(0)" iid="" class="bg-skyblue editInventory text-decoration text-light cur-pointer p-10">Bearbeiten</a>';
	   html+='<input type="button" iid="" class="bg-green text-decoration text-light cur-pointer p-10 ml-10 save" value="Speichern" style="border:1px solid #ffffff">';
	   html+='</div>';
		html+='</td>';
		html+='<td>';
		html+='<select style="width:100px;padding:2px;">';
		html+=' <?php echo fillcompanywithnotid($dbcon,$cid); ?>';
		html+='</select>';
		html+='<input type="text" name="" style="width:60px;padding:4px;margin-left:4px;">';
		html+='<a href="javascript:void(0)" class="btn bg-red transport">Transport</a>';
		html+='</td>';
		html+='</tr>';
			tbody.insertRow().innerHTML=html;
			rowSave();	
			editRow();						
   }
   editRow();
   tableRowTotalPrice();
   rowSave();
   // const table=getID("inventoryTable");
   btntransport=queryAll(".transport");
   btntransport.forEach((row,index)=>{
   	row.addEventListener("click",function(){
   	 let pid=getID("pid").value;
       let errorStatus=1;
       let errorMessage='';
   	 let selectComp=table.rows[index+1].cells[10].children[0].value;
   	 let transport=table.rows[index+1].cells[10].children[1].value;
   	 if(selectComp == 0){
   	 	errorStatus=0;
   	 	errorMessage="Please select company";
   	 	myToast(errorStatus,errorMessage);
   	 }
   	 let iid=table.rows[index+1].cells[10].children[3].value;
   	 let formdata=new FormData;
   	 formdata.append("transportArticle",1);
   	 formdata.append("selectComp",selectComp);
   	 formdata.append("transport",transport);
   	 formdata.append("iid",iid);
   	 formdata.append("pid",pid);
   	 if(errorStatus == 1){
         fetch('../ajax/ajax_inventory.php', {
			    method: 'POST',
			    body:formdata,
			  })
			  .then(res => res.json())
			  .then((data) => {
			    if(data.status == 1){
			       myToast(data.status,data.message);
			       // getID('createEmployeeForm').reset();
			       setTimeout(function(){ location.reload(); }, 3000);
			    }else{
			      myToast(data.status,data.message);
			    }
			  });

			}
   	})
   })
	</script>
	  <?php include "common/footer.php"; ?>
</body>
</html>