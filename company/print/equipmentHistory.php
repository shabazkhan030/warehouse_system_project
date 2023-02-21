<?php 
if(isset($_SESSION["isLogedIn"])){
	header("location:../../index.php");
}
include "../../dbcon/dbcon.php";
$cid=base64_decode($_GET["cid"])/99999;
$eid=base64_decode($_GET["eid"])/77777;
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
<h3 style="text-align:center">Work Equipment Details</h3>
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
<h4 style="margin-left:25px;margin-top:20px;">Work Equipment Issue</h4>
<div style="margin-left:25px;">
<?php
}
$qry=mysqli_query($dbcon,"SELECT wei.*,i.article AS invetory_art FROM work_equipment_issue wei LEFT JOIN inventory i ON wei.article=i.id WHERE wei.cid='$cid' AND wei.eid='$eid'");
if($qry){
	while($arr=mysqli_fetch_assoc($qry)){
?>
    <div style="display:flex;margin-left:10px;">
      <div style="margin-top:10px">
        <label style="color:red;font-size:14px;" class="font-weight-bold">Arbeitsmittel Übergabe</label><br>
        <input type="text" style="width:160px;padding:2px;" value="<?= $arr["invetory_art"] ?>"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label style="font-size:14px;" class="font-weight-bold">Menge</label><br>
        <input type="text" style="width:80px;padding:2px;" value="<?= $arr["selected_crowd"] ?>" readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Datum</label><br>
        <input type="text" style="width:160px;padding:2px;" value="<?= $arr["data"] ?>"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Unterschrift Sachbearbeiter</label><br>
            <?php if($arr["staff_signature"] == ''){ ?> 
          <div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          </div><?php }else{ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/<?= $arr["staff_signature"] ?>"  style="width:100%;">
          
        </div>
    <?php } ?>
        <!-- <p>${data[0].equipment_issue[i].signed_by}</p>`} -->
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Unterschrift Mitarbeiter</label><br>
           <?php if($arr["employee_signature"] == ''){ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign"></div> <?php }else{ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/<?= $arr["employee_signature"] ?>"  style="width:100%;">
          <!-- ${data[0].equipment_issue[i].employee_signature} -->
        </div>
    <?php } ?>
      </div>
    </div>

<?php
	}
}
?>
</div>
<h4 style="margin-left:25px;margin-top:20px;">Work Equipment return</h4>
<div style="margin-left:25px;">
<?php
$qry1=mysqli_query($dbcon,"SELECT wer.*,i.article AS invetory_art FROM work_equipment_return wer LEFT JOIN inventory i ON wer.article=i.id WHERE wer.cid='$cid' AND wer.eid='$eid'");
if($qry1){
	while($arr1=mysqli_fetch_assoc($qry1)){
?>
    <div style="display:flex;margin-left:10px;">
      <div style="margin-top:10px">
        <label style="color:red;font-size:14px;" class="font-weight-bold">Arbeitsmittel Übergabe</label><br>
        <input type="text" style="width:160px;padding:2px;" value="<?= $arr1["invetory_art"] ?>" readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label style="font-size:14px;" class="font-weight-bold">Menge</label><br>
        <input type="text" style="width:80px;padding:2px;" value="<?= $arr1["article_crowd"] ?>" readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Datum</label><br>
        <input type="text" style="width:160px;padding:2px;" value="<?= $arr1["data_return"] ?>"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Unterschrift Sachbearbeiter</label><br>
            <?php if($arr1["staff_signature"] == ''){ ?> 
          <div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          </div><?php }else{ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/<?= $arr1["staff_signature"] ?>"  style="width:100%;"> 
        </div>
    <?php } ?>
        <!-- <p>${data[0].equipment_issue[i].signed_by}</p>`} -->
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Unterschrift Mitarbeiter</label><br>
           <?php if($arr1["employee_signature"] == ''){ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign"></div> <?php }else{ ?><div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/<?= $arr1["employee_signature"] ?>"  style="width:100%;">
          <!-- ${data[0].equipment_issue[i].employee_signature} -->
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
    <!-- <div class="my-100 mt-100" style="margin-bottom:50px;"> -->
<!--     	<div class="d-flex">
    		<a href="javascript:void(0)" class="btn bg-orange" onclick="printEquipment()" style="width:160px">PDF Arbeitsmittel</a>
    		<a href="javascript:void(0)" id="btnEditEquipment"class="btn bg-lightblue" onclick="editEquipment()">Bearbeiten</a>
    		<a href="javascript:void(0)" id="btnSaveEquipment" class="btn bg-green" onclick="saveEquipment()" style="display:none">Speichern</a>
    		<a href="javascript:void(0)" class="btn bg-grey" onclick="printDiv()">Drucken</a>
    	</div> -->
    	<!-- <div id="printworkEquip" class="border-2 w-80"> -->
    		<!-- <div style="text-align:center">
    			<h3 id="headingText">Vereinbarung über die Überlassung von Arbeitsmittel</h3>
    			<input type="hidden" id="textinput">
    		</div>
    		<div style="margin:25px">
    			<p style="font-weight:bold;font-size:18px;" id="com_name"></p> -->
    			<!--<p style="font-weight:bold;font-size:18px;" id="com_street"></p>-->
				<!-- <p style="font-size:16px;margin-bottom:0.5rem"><span id="com_street"></span> <span id="company_house_no"></span></p> -->
				<!--<p style="font-weight:bold;font-size:18px;" id="company_house_no"></p>-->
    			<!--<p style="font-weight:bold;font-size:18px;" id="com_city"></p>-->
    			<!--<p style="font-weight:bold;font-size:18px;" id="com_zip"></p>-->
				<!-- <p style="font-size:16px;margin-bottom:0.5rem"><span id="com_zip"></span> <span id="com_city"></span></p> -->
    			<!-- <p style="font-weight:bold;font-size:18px;">LOREM</p> -->
    		<!-- </div>
    		<div style="margin:25px">
    			<p style="font-weight:bold;font-size:18px;" id="empl_name"></p>
    			<p style="font-weight:bold;font-size:18px;" id="empl_street"></p>
    			<p style="font-weight:bold;font-size:18px;" id="empl_city"></p>
    			<p style="font-weight:bold;font-size:18px;" id="empl_zip"></p>

    		</div>
    		<div style="margin:20px" id="workIssue">
    		</div>
    		<div style="margin:20px" id="workReturn">
    		</div>
    		<div style="margin:40px 25px">
    			<p id="paraHandover" style="font-size:18px;font-weight:normal;width:100%">§ 2 Die Arbeitsmittel sind vom Arbeitnehmer ausschließlich im Rahmen seiner Tätigkeit für die Firma zu benutzen. Eine Nutzung für private Zwecke ist nicht gestattet. Der Arbeitnehmer ist nicht berechtigt, Arbeitsmittel Dritten zu überlassen oder Zugang zu gewähren.
<br><br>
§ 3 Der Arbeitnehmer hat dafür Sorge zu tragen, dass Schäden, die an den Arbeitsmitteln auftreten, unverzüglich dem Unternehmer gemeldet werden.
<br><br>
§ 4 Schäden, die auf normalen Verschleiß zurückzuführen sind, werden auf Kosten der Firma beseitigt. Bei unsachgemäßem Gebrauch und bei Verlust der Arbeitsmittel trägt der Arbeitnehmer bei nachgewiesener grober Fahrlässigkeit 100% der Wiederbeschaffungskosten und 50 % der Wiederbeschaffungskosten, wenn eine grobe Fahrlässigkeit nach den bekannten Begleitumständen nicht ausgeschlossen werden kann. Die Firma ist berechtigt, die Kosten vom Lohn in Abzug zu bringen.
<br><br>
§ 5 Der Arbeitnehmer bestätigt mit seiner Unterschrift unter diese Vereinbarung, dass er die Arbeitsmittel von der Firma in funktionsfähigem und mangelfreiem Zustand erhalten hat.
<br><br>
§ 6 Endet das Arbeitsverhältnis, hat der Arbeitnehmer die Arbeitsmittel unaufgefordert zurückzugeben. Auch während des Bestands des Arbeitsverhältnisses hat der Arbeitnehmer einer Rückgabeaufforderung durch die Firma unverzüglich Folge zu leisten. Ein Zurückbehaltungsrecht</p>
    			<textarea id="textareaInput" style="display:none;width:100%"></textarea>
    		</div>
    	</div>
    </div>


    </div> -->
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