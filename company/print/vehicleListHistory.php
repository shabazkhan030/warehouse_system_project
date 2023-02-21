<?php 
session_start();
if(!isset($_SESSION["isLogedIn"])){
 header("location:../../index.php");
}
if(!isset($_SESSION["company"]["cid"])){
	header("location:../company.php");
}
$cid=base64_decode($_SESSION["company"]["cid"])/99999;
if(!isset($_GET["vid"])){
	header("location:../company.php");
}
$vid=base64_decode($_GET["vid"])/55555;
if(!intval($vid)){
	header("location:../company.php");
}
include "../../dbcon/dbcon.php"; 
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fahrzeug bearbeiten</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
	<link rel="stylesheet" type="text/css" href="../../css/company.css">
</head>
<body>
	  <h3 class="mt-100 mb-20" style="text-align:center">Welcher Mitarbeiter hatte das Fahrzeug</h3>
	  <div style="margin-left:70px;">
      <table class="w-80 PrintpdfTable mb-50">
			<thead>
				<tr>
					<td>Kennzeichen</td>
					<td>Übergabe am</td>
					<td>Rückgabe am</td>
					<td>ID</td>
					<td>Mitarbeiter</td>
					<td>Company Name</td>
				</tr>
			</thead>
			<tbody>
<?php

          $qryhistory=mysqli_query($dbcon,"SELECT vlp.id,vlp.created_id,vl.hallmark AS hallname,e.firstname,e.surname,c.company_name FROM vehicle_log_protocol vlp  INNER JOIN vehicle_list vl ON vlp.hallmark=vl.id INNER JOIN employee e ON vlp.eid = e.id INNER JOIN company c ON vlp.cid=c.id  WHERE vlp.hallmark='$vid' AND vlp.cid <> '$cid' GROUP BY created_id");
          $num_rows=mysqli_num_rows($qryhistory);
          if($num_rows > 0){
          	  while($arrhistory=mysqli_fetch_assoc($qryhistory)){

          	  echo '<tr>
          	        <td>'.$arrhistory["hallname"].'</td>';

          	  	$crid=$arrhistory["created_id"];
          	  	$qryhistory2=mysqli_query($dbcon,"SELECT stand,data FROM vehicle_log_protocol WHERE created_id='$crid'");
                 $data1=0;
                 $data2=0;
                while($arrhistory2=mysqli_fetch_assoc($qryhistory2)){
                   if($arrhistory2["stand"] == 1){
                   	 $data1=($arrhistory2["data"] == "0000-00-00 00:00:00") ? "date is not given" : date("d.m.Y | H:i",strtotime($arrhistory2["data"]));
                   }else{
                       if($arrhistory2["stand"] == 2){
		                   	  $data2=($arrhistory2["data"] == "0000-00-00 00:00:00") ? "Not returned By employee" : date("d.m.Y | H:i",strtotime($arrhistory2["data"]));
		                   }
                   }

                }

                echo '<td>'.$data1.'</td><td>'.$data2.'</td>';
             
             ?>
					<td><?= '00'.$arrhistory["id"];  ?></td>
					<td><?php echo $arrhistory["firstname"]." ".$arrhistory["surname"] ?></td>
					<td><?= $arrhistory["company_name"] ?></td>
				</tr> 
				<?php   	
              }
          }else{
          	?>
               <tr>
               	  <td colspan="6" class="text-center p-5"><b>Wird von niemandem verwendet....</b></td>
               </tr>
          	<?php
          }
?>
			</tbody>
		</table>
		</div>
	</body>