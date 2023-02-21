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
	<title>Führerschein</title>
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
            	<li><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
            	<li class="active-profile-0"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
</ul>
</div>

<div class="my-100 mt-20 mb-20">
  <div class="d-flex">
    <div class="oben1"><?php include "common/employee_name.php"; ?></div>
    <div class="oben1 obenm">Führerschein</div>
    <div class="oben1 obenr">
      <div>
	    <?php
			 $qryChange=mysqli_query($dbcon,"SELECT * FROM driver_license WHERE cid='$cid' ORDER BY updated_at DESC");
			 $pid=0;
			 $changeAt='00.00.0000 | 00:00';
			 if(mysqli_num_rows($qryChange) > 0){
			 	 $lastChange=mysqli_fetch_assoc($qryChange);
			    $pid=$lastChange["update_by"];
			    $changeAt=($lastChange["updated_at"] == '0000-00-00 00:00:00') ? '00,00.0000 | 00:00' : date("d.m.Y | H:i ",strtotime($lastChange["updated_at"])); 
			 }
  
			?>
			<p>Letzte Änderung:</p>
			<p class="font-weight-bold"><?php echo $changeAt; ?></p>
			<p>Sachbearbeiter : <b><?php echo getLastChangeEmplName($dbcon,$pid) ?></b></p>

			<div class="mt-20">
				<a href="create_driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="d-inline-block cur-pointer p-5 bg-green text-decoration text-light">F&uuml;hrerschein Pr&uuml;fung erstellen +</a>
			</div>
</div>
    </div>
  </div>
</div>
  




	
   <!-- <?php include "common/employee_name.php"; ?>-->
	<div class="my-100" style="display:flex">
		<!--<div class="w-20">
            <ul class="bg-light list-style-none d-block p-0">
            	<li class="p-10"><a href="edit_employee_data.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10 w-100">Mitarbeiterdaten</a></li>
            	<li class="p-10"><a href="work_equipment.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Arbeitsmittel</a></li>
            	<li class="p-10"><a href="tank_chip.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Tankchip</a></li>
            	<li class="p-10"><a href="uta_fuel_card.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">UTA Tankkarte</a></li>
            	<li class="p-10"><a href="vehicle_log.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Fahrzeugprotokol</a></li>
            	<li class="active-profile p-10"><a href="driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="text-decoration font-weight-bold text-dark p-10">Führerschein</a></li>
            </ul>
		</div>-->
		<div class="w-100">		
		<!--<div class="ml-50">
				<small class="btn-sm bg-lightblue">Führerschein</small>
			</div>-->
			<div>
				<table class="table-lightblue mt-20 ml-10" style="width:100%">
					<thead>
						<tr>
							<td>ID</td>
							<td>Erster Pr&uuml;fung</td>
							<td>N&auml;chste Pr&uuml;fung</td>
							<td>Aktion</td>
						</tr>
					</thead>
					<tbody>
	<?php 
   $qry=mysqli_query($dbcon,"SELECT * FROM driver_license WHERE cid='$cid' AND eid='$eid'");
   $sno=1;
   if(mysqli_num_rows($qry) > 0){
   while($arr=mysqli_fetch_assoc($qry)){
   	$last_test=($arr["main_data"] == '0000-00-00 00:00:00') ? '00.00.0000 | 00:00' : date("d.m.Y | H:i ",strtotime($arr["main_data"]));
   	$next_test=($arr["main_data"] == '0000-00-00 00:00:00') ? '00.00.0000 | 00:00' : date("d.m.Y | H:i ",strtotime("+6 months", strtotime($arr["main_data"])));
   	$p='';
   	if(!empty($arr["signature_vehicle_user"]) && !empty($arr["signature_fleet_manager"]) && $last_test != '00.00.0000 | 00:00'){
        $p='<p class="d-inline-block bg-green p-5 ml-10 text-light">Done</p>';
   	}else{
   		$p='<p class="d-inline-block bg-red p-5 ml-10 text-light">Offen</p>';
   	}
	?>
						<tr>
							<td><?= $sno ?></td>
							<td><?= $last_test ?></td>
							<td><?= $next_test ?></td>
							<td><a href="edit_driver_license.php?dlid=<?= base64_encode($arr["id"])?>&eid=<?= $_GET["eid"];?>" class="btn-sm bg-orange">Bearbeiten</a><?= $p ?></td>
						</tr>	
	<?php
	$sno++;
   }
}else{
	?>
     <tr>
     	   <td colspan='4'>noch kein F&uuml;hrerschein geprüft..</td>
     </tr>
<?php }  ?>
					</tbody>
				</table>
			</div>			
		</div>
		<!-- LAST CHANGE -->
		<!--<div class="w-15" style="margin-top:-50px;">
		   <?php
			 $qryChange=mysqli_query($dbcon,"SELECT * FROM driver_license WHERE cid='$cid' ORDER BY updated_at DESC");
			 $pid=0;
			 $changeAt='00.00.0000 | 00:00';
			 if(mysqli_num_rows($qryChange) > 0){
			 	 $lastChange=mysqli_fetch_assoc($qryChange);
			    $pid=$lastChange["update_by"];
			    $changeAt=($lastChange["updated_at"] == '0000-00-00 00:00:00') ? '00,00.0000 | 00:00' : date("d.m.Y | H:i ",strtotime($lastChange["updated_at"])); 
			 }
  
			?>
			<p>Letzte Änderung:</p>
			<p class="font-weight-bold"><?php echo $changeAt; ?></p>
			<p>Sachbearbeiter : <b><?php echo getLastChangeEmplName($dbcon,$pid) ?></b></p>

			<div class="mt-20">
				<a href="create_driver_license.php?eid=<?php echo $_GET["eid"]; ?>" class="d-inline-block cur-pointer p-5 bg-green text-decoration text-light">F&uuml;hrerschein Pr&uuml;fung erstellen +</a>
			</div>
		</div>-->
	</div>
	 <?php include "common/footer.php" ?>
</body>
</html>

