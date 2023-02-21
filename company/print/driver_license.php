
<div class="mt-50" style="width:1000px">
    		<div class="d-flex">
	    		<a href="#" class="btn-sm bg-orange" style="width:250px">F&uuml;hrerscheinkontrolle </a>
	    		<a href="javascript:void(0)" class="btn-sm bg-lightblue" id="editPrint" onclick="editPrint()">Bearbeiten</a>
	    		<a href="javascript:void(0)" class="btn-sm bg-green" id="savePrint" onclick="savePrint()" style="display:none">Speichern</a>
	    		<a href="javascript:void(0)" class="btn-sm bg-grey" onclick="printDivcreate()">Drucken</a>
	    	</div>
	    	<div id="printtankchipcreate" class="border-2 w-100">
	    		<div style="display:flex;justify-content:space-between;padding:10px 20px;">
	    			<div>

	    				<h2 style="text-align:left" id="dl_text"><?php echo ($arr["print_head"] != '') ? $arr["print_head"] : 'Führerscheinkontrolle'; ?></h2>
	    				<input type="hidden" id="dl_edit_text" value="">
		    			<p style="font-size:18px;font-weight:bold;margin-top:30px;">Mitarbeiterdaten</p>
		    			<p style="font-size:18px;margin-top:10px;"><?= $arr["firstname"]." ".$arr["surname"] ?></p>
		    			<p style="font-size:18px;margin-top:10px;"><?= $arr["street"]." ".$arr["house_no"]?></p>
		    			<p style="font-size:18px;margin-top:10px;"><?= $arr["zip"]?> <?= $arr["city"] ?></p>
		    			<!--<p style="font-size:18px;margin-top:10px;"><//?= $arr["zip"]?></p>-->
		    			<p style="font-size:18px;margin-top:30px;"><span style="font-weight:bold">Tel:</span><?= $arr["phone_number"] ?></p>
		    			<p style="font-size:18px;margin-top:10px;"><span style="font-weight:bold">E-Mail:</span><?= $arr["email"] ?></p>
		    			<br>
		    			<br>
	    			</div>
	    			<div style="text-align:center;">
	    				<img src="../../img/company_uploads/<?= $_SESSION["company"]["clogo"]?>" style="width:80px;height:80px;">
	    				<h1 style="text-align:right;margin-top:10px;"><?php echo getCompanyName($dbcon,$cid); ?></h1>
	    			</div>
	    		</div>
	    		
	            <div style="padding:20px 20px;">
					<p style="margin-top:10px;" id="dl_para"> 2.) Angaben zum F&uuml;hrerschein / zur Fahrerlaubnis</p>
					<input type="hidden" id="dl_edit_para">
					<div style="width:100%;">
						<div style="display:flex;flex-wrap: wrap;">
<?php
$qryIcon=mysqli_query($dbcon,"SELECT * FROM driver_license_icon_checkbox WHERE eid='$eid' AND cid='$cid' AND dlid='$dlid' AND view='1'");
while($arrIcon=mysqli_fetch_assoc($qryIcon)){
?>
							<div style="display:flex;padding:15px 50px 10px 0px;">
			       		       <label class="tasks-list-item" style="display:flex">
					           <input type="checkbox" class="tasks-list-cb" <?php if($arrIcon["chbx"] != "0"){ ?> checked <?php } ?> readonly>
					           <span class="tasks-list-mark"></span>
					           <span class="tasks-list-desc" style="margin-right:10px;margin-top:7px;">
					           	    <p><?= $arrIcon["label"] ?></p>
					           </span>
					           </label>
					           <span style="display:flex;margin-top:15px;">
		                           <?php if(empty($arrIcon["icon"])){
		                           ?>
		                           <?php
		                           }else{
		                           ?>
		                           <img src="<?= $arrIcon["icon"] ?>" style="width:25px;height:25px;">
		                           <?php
		                           } 
		                           ?>
					           </span>
					        </div>
<?php
}
?>
				        </div>
						<div style="margin-top:30px;">
<?php 
$qryCom1=mysqli_query($dbcon,"SELECT * FROM driver_license_comment WHERE dlid='$dlid' AND eid='$eid' AND cid='$cid' AND comment_view='top'");
if($qryCom1){
	while($arrCom1=mysqli_fetch_assoc($qryCom1)){
?>
	    <div style="width:80%;margin-bottom:20px;">
	       <label class="font-weight-bold"><?= $arrCom1["comLabel1"]?></label>
	       	<?php if($arrCom1["comm_value"] != ''){  ?>
	       <textarea rows="5" style="width:100%" readonly><?= $arrCom1["comm_value"]?></textarea>
	       <?php } ?>
	    </div>
<?php
	}
}
?> 
						</div>
						<div style="margin-top:30px;">
<?php
$qryct=mysqli_query($dbcon,"SELECT * FROM driver_license_checkbox WHERE dlid='$dlid' AND eid='$eid' AND cid='$cid' AND view='1'");
if($qryct){
	if(mysqli_num_rows($qryct) > 0){
?>
		                         <table class="PrintpdfTable" style="width:100%">
		                         	<thead style="background-color:#f8f9fa;">
		                         		<tr>
										<td style="padding:10px;">&nbsp;</td>
										<td style="padding:10px;">&nbsp;</td>
										<td style="padding:10px;">Ja</td>
										<td style="padding:10px;">Nein</td>
		                         		</tr>
		                         	</thead>
		                         	<tbody>
<?php
	while($arrct=mysqli_fetch_assoc($qryct)){
?>
            <tr>
		         <td style="padding:10px;"><p><?= $arrct["labelname"]; ?></p><input type="hidden" value="<?= $arrct["labelname"]; ?>"></td>
		         <!-- <input type="hidden" name="dlcid[]" value="<?= $arrct["id"] ?>"> -->
		         <td style="padding:10px;"><input type="text" value="<?= $arrct["textname"]; ?>" readonly></td>
				 <td style="padding:10px;">							
						<label class="tasks-list-item">
				           <input type="radio" value="1" <?php if($arrct["chbx_yes"] == 1){?> checked="checked" <?php } ?> class="tasks-list-cb" readonly>
				           <span class="tasks-list-mark"></span>
				    </label> 	
			    </td>
				  <td style="padding:10px;">
						<label class="tasks-list-item">
					        <input type="radio" value="0" <?php if($arrct["chbx_no"] == 1){?> checked="checked" <?php } ?> class="tasks-list-cb" readonly>
					        <span class="tasks-list-mark"></span>
					    </label>
				  </td>	
			</tr>
<?php
	}
  }
}
?>
		                         	</tbody>
		                         </table>
						</div>
						<div style="margin-top:30px;">
							<div style="display:flex;">
								 <p  style="font-weight:bold"><?= $arr["main_label"]?></p><input type="hidden" value="<?= $arr["main_label"]; ?>" class="m-0"> 
							</div>
						   <div style="display:flex;">
						   	<div style="width:50%;padding:0px 20px 10px 20px;">
								   	<label>Herr / Frau / Name</label><br>
								   	<input type="text" value="<?= $arr["main_text"] ?>" style="width:100%" readonly>
						     </div>
							 <div>am</div>
							   <div style="width:50%;padding:0px 20px 10px 20px;">
								   	<label>Datum</label>
								   	<br>
								   	<input type="datetime-local" value="<?= $arr["main_data"]?>" style="width:100%" readonly>
							   </div>
						   </div>
						</div>
						<div style="margin-top:30px;">
						   <p style="font-weight:bold"><?= $arr["main_checkbox"]?></p>
						   <div style="display:flex;padding-top:10px;">
						   	<div style="width:100px;">
							   	<label class="tasks-list-item">
						            <input type="radio" name="checkbox2" value="1" <?php if($arr["checkbox_value"]== 1){ ?> checked <?php } ?> class="tasks-list-cb" disabled>
						            <span class="tasks-list-mark"></span>
						            <span class="tasks-list-desc">JA</span>
						        </label>
						     </div>
							   <div style="width:100px;">
							   	<label class="tasks-list-item">
						            <input type="radio" name="checkbox2" value="0" <?php if($arr["checkbox_value"]== 0){ ?> checked <?php } ?> class="tasks-list-cb" disabled>
						            <span class="tasks-list-mark"></span>
						            <span class="tasks-list-desc">Nein</span>
						        </label>
							   </div>
						   </div>
						</div>
						<div style="margin-top:30px;">
<?php 
$qryCom2=mysqli_query($dbcon,"SELECT * FROM driver_license_comment WHERE dlid='$dlid' AND eid='$eid' AND cid='$cid' AND comment_view='bottom'");
if($qryCom2){
	while($arrCom2=mysqli_fetch_assoc($qryCom2)){
?>
	    <div style="width:80%;margin-bottom:20px;">
	        <label style="font-weight:bold;"><?= $arrCom2["comLabel1"]?></label>
	       <?php if($arrCom2["comm_value"] != ''){ ?>
	        <textarea rows="4" style="width:100%" readonly><?= $arrCom2["comm_value"] ?></textarea>
	       <?php } ?>
	    </div>
<?php
	}
}
?>
						</div>
						<div class="">
					
					  
					   <div class="mt-20 mr-20">
					  	   <label class="font-weight-bold">4.) Der Fahrer wird im F&uuml;hrerschein eingetragene Auflagen oder Beschr&auml;nkungen beachten. Er best&auml;tigt mit seiner Unterschrift, &uuml;ber die Bestimmungen aus StVG, StVO und StVZO belehrt worden zu sein.</label><br>
					  	</div>   
					 
			   </div>
						<div style="margin-top:20px;">
						  	<label>Datum</label>
						  	<br>
							<input type="text" value="<?= $arr["signed_date"]?>" readonly>
						</div>
						<div style="margin-top:30px;">
							<div style="display:flex">
								<div style="display:block;margin-right:20px;">
			    					<label style="margin-bottom:5px;font-weight:bold;">Unterschrift Fuhrparkleiter</label><br><br>
						    		<?php 
					              if($arr["signature_fleet_manager"] == ''){
										   ?>
										   <div style="border:solid 2px black;width:200px;height:90px;padding:3px;">
										
								  </div>
										<?php }else{?>
											<div id="signaturePurchaserWrapper" style="border:solid 2px black;width:200px;height:90px;padding:3px;">
												  <img src="../../img/signature_uploads/<?php echo $arr["signature_fleet_manager"];?>" style="width:100%;">
											</div>
											<p></p>
											<?= $arr["pfname"]." ".$arr["psname"]; ?>
										<?php } ?>
			    				</div>
			    				<div style="display:block">
			    					<label style="font-weight:bold;margin-bottom:5px">Unterschrift Mitarbeiter</label><br><br>
						    			<?php
						    				if($arr["signature_vehicle_user"] == ''){
										   ?>
										    <div style="border:solid 2px black;width:200px;height:90px;padding:3px;">
											</div>
											<?php }else{?>
											<div id="signatureDirectorWrapper" style="border:solid 2px black;width:200px;height:90px;padding:3px;">
												  <img src="../../img/signature_uploads/<?php echo $arr["signature_vehicle_user"];?>" style="width:100%;">
											</div>
										<?php } ?>
			    				</div>
							</div>
						</div>
					</div>
					<div style="padding:30px 0px">
					  <p id="dl_desc">
							<strong class="text-red">Hinweise zur Halterhaftung und zur F&uuml;hrerscheinkontrolle</strong><br>
Auf Grund der nachstehenden gesetzlichen Regelungen liegt die Haftung beim Fahren ohne F&uuml;hrerschein in der Verantwortung des Fahrzeughalters. Die in diesem Rahmen notwendige Kontrolle des F&uuml;hrerscheins unterliegt daher nicht dem Datenschutz, auch wenn pers&ouml;nliche Daten (wie z.B. das Geburtsdatum des Fahrers etc.) dem Fuhrparkleiter bzw. dem einzelnen Fuhrparkmanager bekannt werden.<br>
<br>
Insbesondere bei Neueinstellungen muss durch den Halter bzw. das Fuhrparkmanagement sichergestellt werden, dass der neue Mitarbeiter bzw. die neue Mitarbeiterin im Besitz einer g&uuml;ltigen Fahrerlaubnis ist. Dabei sollte die Fahrerlaubnis kontrolliert, kopiert und in die Akten des Fuhrparkmanagements bzw. und/oder in die Personalakte gelegt werden. Die Verantwortung f&uuml;r das F&uuml;hren der Kontroll-Liste, deren Vollst&auml;ndigkeit sowie die Aufbewahrung dieser Dokumente liegt beim Fuhrparkmanagement.<br>
<br>
Grunds&auml;tzlich kann die F&uuml;hrerschein-Kontrolle sowohl innerhalb des eigenen Unternehmens als auch extern an andere Unternehmen delegiert werden. Wichtig ist dabei: auch in den F&auml;llen der Delegation der Halterverantwortung ist der Fahrzeughalter gefordert, die Personen, auf die er seine Pflichten delegiert, hinsichtlich einer ordnungsgem&auml;&szlig;en Durchf&uuml;hrung der F&uuml;hrerscheinkontrollen in zeitlichen Abst&auml;nden selbst zu &uuml;berpr&uuml;fen. Die &Uuml;bertragung der Kontrollpflichten an Dritte sch&uuml;tzt den Fahrzeughalter nicht vor einer m&ouml;glichen Eigenhaftung.

						</p>
						<textarea id="dl_edit_desc" style="width:100%;display:none" rows="5"></textarea>
					</div>
	            </div>
	    	</div>
    	</div>	
	</div>