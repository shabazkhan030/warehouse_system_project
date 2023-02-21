<?php 
if(isset($_SESSION["isLogedIn"])){
	header("location:../../index.php");
}
include "../../dbcon/dbcon.php";
$cid=base64_decode($_GET["cid"])/99999;
$eid=base64_decode($_GET["eid"])/77777;
?>
<link rel="stylesheet" type="text/css" href="../../css/style.css">
<link rel="stylesheet" type="text/css" href="../../css/company.css">
<div style="margin-top:30px;"></div>
<a href="javascript:void(0)" style="padding:10px 20px;background:red;color:#ffffff;text-decoration:none;margin:50px 20px 20px 100px;" onclick="printDiv()">Print PDF</a>
<a href="../employee/vehicle_log.php?eid=<?= $_GET["eid"] ?>" style="padding:10px 20px;background:red;color:#ffffff;text-decoration:none;margin:50px;margin-left:20px;">Back</a>
 <div style="margin-left:100px;margin-right:100px;margin-top:10px;border:2px solid black;padding:20px 10px" id="print">
<?php
$qryCInfo=mysqli_query($dbcon,"SELECT * FROM company WHERE id='$cid'");
if($qryCInfo){
	$arrC=mysqli_fetch_assoc($qryCInfo);
?>
<h3 style="text-align:center">Vehicle Log Protocol</h3>
<div style="display:flex;justify-content:space-between;margin:20px 25px;">
	<div>
		<p style="margin-top:20px">Company</p>
		<img src="../../img/company_uploads/<?= $arrC["logo"] ?>">
	    <p style="font-weight:bold;font-size:18px;" id="com_name"><?= $arrC["company_name"] ?></p>
		<p style="font-weight:bold;font-size:18px;" id="company_house_no"><?= $arrC["house_no"] ?></p>
		<p style="font-weight:bold;font-size:18px;" id="com_city"><?= $arrC["city"] ?></p>
		<p style="font-weight:bold;font-size:18px;" id="com_zip"><?= $arrC["zip_code"] ?></p>	
	</div>
<?php
}
$qryEInfo=mysqli_query($dbcon,"SELECT * FROM employee WHERE id='$eid'");
if($qryEInfo){
	$arrE=mysqli_fetch_assoc($qryEInfo);
?>
	<div>
		<p style="margin-top:20px">Employee</p>
	    <p style="font-weight:bold;font-size:18px;"><?= $arrE["firstname"]." ".$arrE["surname"] ?></p>
	    <p style="font-weight:bold;font-size:18px;"><?= $arrE["house_no"] ?></p>
		<p style="font-weight:bold;font-size:18px;"><?= $arrE["street"] ?></p>
		<p style="font-weight:bold;font-size:18px;"><?= $arrE["city"] ?></p>
		<p style="font-weight:bold;font-size:18px;"><?= $arrE["zip"] ?></p>
	</div>
</div>
<?php
}
$query=mysqli_query($dbcon,"SELECT vlp.*,vl.hallmark AS vlhall FROM vehicle_log_protocol vlp LEFT JOIN vehicle_list vl ON vlp.hallmark=vl.id WHERE vlp.cid='$cid' AND vlp.eid='$eid'");
?>
<table class="table-light w-100">
	<thead style="font-weight:bold">
		<tr>
			<td>Hallmark</td>
			<td>Type</td>
			<td>Name</td>
			<td>KMS stand</td>
			<td>Date</td>
		</tr>
	</thead>
	<tbody>
<?php
while($arr=mysqli_fetch_assoc($query)){
    $start=1; 
	if($arr["stand"] == 1){
		if($start == 1){
			?>
          <tr>
          	<td colspan="5">Info</td>
          </tr>
			<?php
		}
?>     
       <tr style="padding:10px;">
       	  <td><?= $arr["vlhall"]; ?></td>
          <td><?= $arr["vehicle_type"]; ?></td>
          <td><?= $arr["car_name"]; ?></td>
          <td><?= $arr["kms"]; ?></td>
          <td><?= date("d.m.Y",strtotime($arr["data"])); ?></td>
       </tr>
<?php 
    $start=0; 
	}else{
?>
       <tr>
       	  <td><?= $arr["vlhall"]; ?></td>
          <td><?= $arr["vehicle_type"]; ?></td>
          <td><?= $arr["car_name"]; ?></td>
          <td><?= $arr["kms"]; ?></td>
          <td><?= date("d.m.Y",strtotime($arr["data"])); ?></td>
       </tr>

<?php
    $start=1; 
	}
	// echo "<pre>";
	// print_r($arr);
	// echo "<pre>";
}
?>
   </tbody>
</table>

    <script type="text/javascript">
    	function printDiv() {
var divContents = document.getElementById("print").innerHTML;
var a = window.open('', '', 'height=3508, width=2480');
a.document.write('<html>');
a.document.write('<link rel="stylesheet" type="text/css" href="../../css/style.css">');
a.document.write('<link rel="stylesheet" type="text/css" href="../../css/company.css">');
a.document.write('<body >');
a.document.write(divContents);
a.document.write('</body></html>');
a.focus();
setTimeout(function(){a.print();},1000);
a.document.close();

}
    </script>
