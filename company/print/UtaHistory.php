<?php 
if(isset($_SESSION["isLogedIn"])){
	header("location:../../index.php");
}
include "../../dbcon/dbcon.php";
$cid=base64_decode($_GET["cid"])/99999;
$eid=base64_decode($_GET["eid"])/77777;

// echo $cid;
?>
<div style="margin-top:30px;"></div>
<a href="javascript:void(0)" style="padding:10px 20px;background:red;color:#ffffff;text-decoration:none;margin:50px 20px 20px 100px;" onclick="printDiv()">Print PDF</a>
<a href="../employee/employee.php?eid=<?= $_GET["eid"] ?>" style="padding:10px 20px;background:red;color:#ffffff;text-decoration:none;margin:50px;margin-left:20px;">Back</a>
 <div style="margin-left:100px;margin-right:100px;margin-top:10px;border:2px solid black;padding:20px 10px" id="print">
<?php
$qryCInfo=mysqli_query($dbcon,"SELECT * FROM company WHERE id='$cid'");
if($qryCInfo){
	$arrC=mysqli_fetch_assoc($qryCInfo);
?>
<h3 style="text-align:center">UTA Fuel Card Details</h3>
<div style="display:flex;justify-content:space-between">
<div style="margin-left:25px;margin-top:20px;">
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
<div style="margin-left:25px;">
	<p style="margin-top:20px">Employee</p>
    <p style="font-weight:bold;font-size:18px;"><?= $arrE["firstname"]." ".$arrE["surname"] ?></p>
    <p style="font-weight:bold;font-size:18px;"><?= $arrE["house_no"] ?></p>
	<p style="font-weight:bold;font-size:18px;"><?= $arrE["street"] ?></p>
	<p style="font-weight:bold;font-size:18px;"><?= $arrE["city"] ?></p>
	<p style="font-weight:bold;font-size:18px;"><?= $arrE["zip"] ?></p>
</div>
</div>
<h4 style="margin-left:25px;margin-top:20px;">Tank Chip Handover</h4>
<div style="margin-left:25px;">
<?php
}
$qry=mysqli_query($dbcon,"SELECT uh.*,vl.hallmark AS vlmark FROM uta_fuel_card_handover uh LEFT JOIN vehicle_list vl ON uh.hallmark=vl.id WHERE uh.cid='$cid' AND uh.eid='$eid'");
if($qry){
	while($arr=mysqli_fetch_assoc($qry)){
?>
    <div style="display:flex;margin-left:10px;">
      <div style="margin-top:10px">
        <label style="color:red;font-size:14px;" class="font-weight-bold">Arbeitsmittel Übergabe</label><br>
        <input type="text" style="width:160px;padding:2px;" value="<?= $arr["vlmark"] ?>"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label style="font-size:14px;" class="font-weight-bold">text1</label><br>
        <input type="text" style="width:80px;padding:2px;" value="<?= $arr["text1"] ?>" readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label style="font-size:14px;" class="font-weight-bold">text2</label><br>
        <input type="text" style="width:80px;padding:2px;" value="<?= $arr["text2"] ?>" readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Datum</label><br>
        <input type="text" style="width:160px;padding:2px;" value="<?= date("d.m.Y",strtotime($arr["data_handover"])) ?>"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Unterschrift Sachbearbeiter</label><br>
            <?php if($arr["signature_staff"] == ''){ ?> 
          <div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          </div><?php }else{ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/<?= $arr["signature_staff"] ?>"  style="width:100%;">
          
        </div>
    <?php } ?>
        <!-- <p>${data[0].equipment_issue[i].signed_by}</p>`} -->
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Unterschrift Mitarbeiter</label><br>
           <?php if($arr["signature_employee"] == ''){ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign"></div> <?php }else{ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/<?= $arr["signature_employee"] ?>"  style="width:100%;">
          <!-- ${data[0].equipment_issue[i].signature_employee} -->
        </div>
    <?php } ?>
      </div>
    </div>

<?php
	}
}
?>
</div>
<h4 style="margin-left:25px;margin-top:20px;">Tank Chip Return</h4>
<div style="margin-left:25px;">
<?php
$qry1=mysqli_query($dbcon,"SELECT ur.*,vl.hallmark AS vlmark FROM uta_fuel_card_return ur LEFT JOIN vehicle_list vl ON ur.hallmark=vl.id WHERE ur.cid='$cid' AND ur.eid='$eid'");
if($qry1){
	while($arr1=mysqli_fetch_assoc($qry1)){
?>
    <div style="display:flex;margin-left:10px;">
      <div style="margin-top:10px">
        <label style="color:red;font-size:14px;" class="font-weight-bold">Arbeitsmittel Übergabe</label><br>
        <input type="text" style="width:160px;padding:2px;" value="<?= $arr1["vlmark"] ?>" readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label style="font-size:14px;" class="font-weight-bold">text1</label><br>
        <input type="text" style="width:80px;padding:2px;" value="<?= $arr1["text1"] ?>" readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label style="font-size:14px;" class="font-weight-bold">text2</label><br>
        <input type="text" style="width:80px;padding:2px;" value="<?= $arr1["text2"] ?>" readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Datum</label><br>
        <input type="text" style="width:160px;padding:2px;" value="<?= date('d.m.Y',strtotime($arr1["data_return"])) ?>"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Unterschrift Sachbearbeiter</label><br>
            <?php if($arr1["signature_staff"] == ''){ ?> 
          <div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          </div><?php }else{ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/<?= $arr1["signature_staff"] ?>"  style="width:100%;"> 
        </div>
    <?php } ?>
        <!-- <p>${data[0].equipment_issue[i].signed_by}</p>`} -->
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Unterschrift Mitarbeiter</label><br>
           <?php if($arr1["signature_employee"] == ''){ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign"></div> <?php }else{ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/<?= $arr1["signature_employee"] ?>"  style="width:100%;">
          <!-- ${data[0].equipment_issue[i].signature_employee} -->
        </div>
    <?php } ?>
      </div>
    </div>
<?php
	}
}
?>
</div>
</div>
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