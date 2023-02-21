<?php
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../../index.php");
}
if(!isset($_SESSION["company"]["cid"])){
	header("location:../company.php");
}
$eid=intval(base64_decode($_GET["eid"])/77777);
$cid=intval(base64_decode($_SESSION["company"]["cid"])/99999);
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
	<title>Fahrzeugprotokol</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
	<link rel="stylesheet" type="text/css" href="../../css/company.css">
</head>
<body>
    <?php include "common/headtop.php"; ?>
    <?php include "common/navbar.php" ?>
	<div class="my-100">
<ul class="d-flex  bg-sub-nav list-style-none p-0">
<li class="p-10"><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
            	<li class="p-10"><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
            	<li class="p-10"><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
            	<li><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
            	<li class="active-profile-0"><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
            	<li class="p-10"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
</ul>
</div>
		<div class="my-100 mt-20 mb-20">
  <div class="d-flex">
    <div class="oben1"><?php include "common/employee_name.php"; ?></div>
    <div class="oben1 obenm">Fahrzeugprotokol</div>
    <input type="hidden" id="cid" value=<?= $_SESSION["company"]["cid"]; ?>>
    <input type="hidden" id="eid" value=<?= $_GET["eid"]; ?>>
    <div class="oben1 obenr">
	<?php
			$qryChange=mysqli_query($dbcon,"SELECT vlp.pid,vlp.last_change,p.firstname,p.surname FROM `vehicle_log_protocol` vlp INNER JOIN personal_admin p ON vlp.pid=p.id WHERE vlp.cid='$cid' ORDER BY vlp.last_change DESC");
			$lastChange=mysqli_fetch_assoc($qryChange);
			  $clerkName=(isset($lastChange)) ? $lastChange["firstname"].' '.$lastChange["surname"] : "Not Available" ;
			  $time=(isset($lastChange))?date("d.m.Y | H:i",strtotime($lastChange["last_change"])) : "0000-00-00 | 00:00";
			?>
      <div>Letzte Änderung:<?php echo $time; ?> <br>
    <?php echo $clerkName ?></div>
	 
	<div class="mt-20">
				<a href="create_vehicle_handover_protocol.php?eid=<?php echo $_GET["eid"]; ?>" class="d-inline-block cur-pointer p-5 bg-green text-decoration text-light">Fahrzeug anlegen +</a>
			</div>

    </div>
  </div>
</div>
	
  <!--  <?php include "common/employee_name.php"; ?>-->
	<div class="my-100" style="display:flex">
		<!--<div class="w-20">
            <ul class="bg-light list-style-none d-block p-0">
            	<li class="p-10"><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
            	<li class="p-10"><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
            	<li class="p-10"><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
            	<li class="p-10"><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
            	<li class="active-profile p-10"><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
            	<li class="p-10"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
            </ul>
		</div>-->
		<div class="w-100">		
			<!--<div class="ml-50">
				<small href="javascript:void(0)" class="d-inline-block text-decoration text-light p-5 bg-lightblue">Fahrzeugprotokol</small>
			</div>-->
			<div>
				<table class="table-lightblue mt-20 " style="width:100%">
					<thead>
						<tr>
							<td>Kennzeichen</td>
							<td>Übergabe Protokol</td>
							<td>Rückgabe Protokol</td>
						</tr>
					</thead>
					<tbody>
					<?php 
$qry=mysqli_query($dbcon,"SELECT vlp.id,vlp.hallmark,vlp.data,vlp.stand,vlp.created_id,vl.hallmark AS hlmrk FROM vehicle_log_protocol vlp INNER JOIN vehicle_list vl ON vlp.hallmark=vl.id WHERE vlp.eid='$eid' AND vlp.cid='$cid' AND vlp.stand ='1'");
if($qry){
	if(mysqli_num_rows($qry) > 0 ){
		while($arr=mysqli_fetch_assoc($qry)){
			$hllmrk=$arr["hallmark"];
			$vlid=$arr["id"];
			$created_id=$arr["created_id"];

?>		
						<tr>
							<td><?= $arr["hlmrk"] ?></td>
							<td><?php echo $data=($arr["data"] != '0000-00-00 00:00:00') ? date("d.m.Y",strtotime($arr["data"])) : '00.00.0000' ?>
							<a href="edit_vehicle_handover_protocol.php?eid=<?php echo $_GET["eid"]; ?>&hm=<?php echo $hllmrk; ?>&vlid=<?php echo $vlid; ?>" class="btn-sm bg-orange">Bearbeiten</a></td>
							<td>
<?php
$qry2=mysqli_query($dbcon,"SELECT vlp.id,vlp.hallmark,vlp.data,vlp.stand FROM vehicle_log_protocol vlp INNER JOIN vehicle_list vl ON vlp.hallmark=vl.id WHERE vlp.eid='$eid' AND vlp.stand ='2' AND vlp.cid='$cid' AND vlp.hallmark='$hllmrk' AND vlp.created_id='$created_id'");
if($qry2){
		if(mysqli_num_rows($qry2) > 0 ){
		   $arr2=mysqli_fetch_assoc($qry2);
		      $vlrp=$arr2["id"];
	      if($arr2["hallmark"] !== 0){
	   	?>
     			   <?php echo $data=($arr2["data"] != '0000-00-00 00:00:00') ? date("d.m.Y",strtotime($arr2["data"])) : '00.00.0000' ?>
					<a href="edit_vehicle_return_protocol.php?eid=<?php echo $_GET["eid"]; ?>&hm=<?php echo $hllmrk; ?>&vlid=<?php echo $vlrp; ?>&crid=<?php echo $created_id; ?>" class="btn-sm bg-orange">Bearbeiten</a>			
<?php
        }
      }else{
      	$qry3=mysqli_query($dbcon,"SELECT id,created_id FROM vehicle_log_protocol WHERE created_id='$created_id'");

      	?>
      	Nein <a href="create_vehicle_return_protocol.php?eid=<?php echo $_GET["eid"]; ?>&hm=<?php echo $hllmrk; ?>&vlid=<?php echo $vlid; ?>&crid=<?php echo $created_id; ?>" class="btn-sm bg-orange">Bearbeiten</a>

<?php }
}
?>
						</td>
						</tr>
<?php	
		}
	}else{
    ?><tr><td colspan="3">noch kein Fahrzeug übergeben...</td></tr>
    	<?php
	}
}

					?>
					</tbody>
				</table>
			</div>			
		</div>
		<!--<div class="w-15" style="margin-top:-50px;">
			<?php
			$qryChange=mysqli_query($dbcon,"SELECT vlp.pid,vlp.last_change,p.firstname,p.surname FROM `vehicle_log_protocol` vlp INNER JOIN personal_admin p ON vlp.pid=p.id WHERE vlp.cid='$cid' ORDER BY vlp.last_change DESC");
			$lastChange=mysqli_fetch_assoc($qryChange);
			  $clerkName=(isset($lastChange)) ? $lastChange["firstname"].' '.$lastChange["surname"] : "Not Available" ;
			  $time=(isset($lastChange))?date("d.m.Y | H:i",strtotime($lastChange["last_change"])) : "0000-00-00 | 00:00";
			?>
			<p>Letzte Änderung:</p>
			<p class="font-weight-bold"><?php echo $time; ?></p>
			<p>Sachbearbeiter : <b><?php echo $clerkName ?></b></p>-->

			<!--<div class="mt-20">
				<a href="create_vehicle_handover_protocol.php?eid=<?php echo $_GET["eid"]; ?>" class="d-inline-block cur-pointer p-5 bg-green text-decoration text-light">Fahrzeug anlegen +</a>
			</div>
		</div>-->
	</div>
	<div class="mt-50" style="margin-left:40px;">
		<a href="javascript:void(0)" onclick="loadHistory()" style="padding:10px 10px;font-weight:bold;text-decoration:none;">View Previous vehicle Log</a>
	</div>
<!-- 	<div style="margin-left:30px;">
          <a href="javascript:void(0)" onclick="loadHistory()" style="padding:10px 10px;font-weight:bold;text-decoration:none;">View Previous Company History</a>
      </div> -->
<div id="historyVLModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
      <p>workequipment History in other company</p>
      <div id="showCompany" style="margin-top:10px;margin-bottom:10px;text-align:left;">
        
      </div> 
      <button class="closeModal btn bg-red mr-20">Schliesen</button> 
  </div>
</div>
	 <?php include "common/footer.php" ?>
	 <script src="../../js/functions.js"></script>
	 <script>
function loadHistory(){
  console.log("history");
  var modal =getID("historyVLModal");
  modal.style.display = "block";
  let cid=getID("cid").value;
  let eid=getID("eid").value;
  var span = document.getElementsByClassName("closeModal")[0];
  formdata=new FormData();
  formdata.append("getVLOGCompany",1);
  formdata.append("cid",cid);
  formdata.append("eid",eid);
  fetch("../../ajax/ajax_employee.php",{
    method:"POST",
    body:formdata
  }).then(res=>res.json())
  .then((data)=>{
    let company='';
    if(data[0] == 'empty'){
      console.log("empty");
    }else{
      for(var i in data){
         company+= `<a href="../print/VlHistory.php?cid='${data[i].company_id}&eid=${eid}">${data[i].company_name}</p>`;
      }
      getID("showCompany").innerHTML=company;
    }
  })
  span.onclick = function() {
    modal.style.display = "none";
  }
  window.onclick = function(event){
    if(event.target == modal){
      modal.style.display = "none";
    }
  }
}
	 </script>
</body>
</html>