<div class="my-100 mt-50">
    <div class="w-100 p-20">
		<div class="d-flex justify-content-center">
	    		<a href="javascript:void(0)" class="d-inline-block p-10 text-light bg-orange text-decoration mb-10">
	    			<?php echo ($stand == 2) ? "Fahrzeugr&uuml;ckgabeprotokoll" : "Fahrzeug&uuml;bergabeprotokoll"; ?> </a>
	    		<a href="javascript:void(0)" class="d-inline-block p-10 ml-10 text-light ml-10 mb-10 bg-lightblue text-decoration" id="editPrint" style="width:90px;" onclick="editBtn(<?= $stand ?>);">Bearbeiten</a>
	    		<a href="javascript:void(0)" id="savePrint" class="d-inline-block p-10 ml-10 text-light ml-10 mb-10 bg-green text-decoration" style="width:90px;display:none" onclick="saveBtn(<?= $stand ?>);">Speichern</a>
	    		<a href="javascript:void(0)" class="d-inline-block p-10 ml-10 mb-10 text-light bg-grey text-decoration" onclick="printDivcreate()" style="width:90px;">Drucken</a>				
    	</div>
	    	<br>
	<div id="printtankchipcreate" class="border-2 w-100">
	    		<h2 class="text-center mt-20" id="header-text"><b>Fahrzeugr&uuml;ckgabeprotokoll</b>
	    		  <input type="hidden" id="headerTextedit"></h2>
				  <div style="text-align:center;float: right;">
	    				<img src="../../img/company_uploads/<?= $_SESSION["company"]["clogo"]?>" style="width:80px;height:80px;">
	    				<h1 style="text-align:right;margin-top:10px;"><?php echo getCompanyName($dbcon,$cid); ?></h1>
	    			</div>
	    		<div class="d-flex mt-20">
	    			<div class="w-100 ml-20">
		    			<p class="fs-18 font-weight-normal mt-15">Mitarbeiter</p>
		    			<p class="fs-18 font-weight-normal mt-15">Vormane  <b><?php $name=getEmplName($dbcon,$eid); $fname=explode(" ",$name);
                 echo $fname[1];
		    		?></b></p>
		    			<p class="fs-18 font-weight-normal mt-15">Nachname  <b><?php echo $fname[0]; ?></b></p>
		    			<?php
                 $print1=mysqli_query($dbcon,"SELECT vlp.id,vlp.hallmark,vlp.vehicle_type,vlp.kms,vlp.no_of_key,vlp.data,vlp.stand,vl.hallmark AS hallname FROM vehicle_log_protocol vlp INNER JOIN vehicle_list vl ON vlp.hallmark=vl.id WHERE vlp.hallmark='$hm' AND stand='$stand' ORDER BY vlp.id DESC");
                   $arrPrint=mysqli_fetch_assoc($print1);
		    			?>
		    			<p class="fs-18 font-weight-normal mt-15">Kennzeichen <b><?php echo $arrPrint["hallname"]; ?></b></p>
		    			<p class="fs-18 font-weight-normal mt-15">Fahrzeug Art  <b><?php echo $arrPrint["vehicle_type"]; ?></b></p>
		    			<p class="fs-18 font-weight-normal mt-15">KM Stand  <b><?php echo $arrPrint["kms"]; ?></b></p>
		    			
		    			<p class="fs-18 font-weight-normal mt-15">Letzte &Auml;nderung am <b><?php echo ($arr["data"] == '0000-00-00 00:00:00') ? "00.00.0000 | 00:00" : date('d.m.Y | H:i', strtotime($arr["data"])); ?></b></p>
		    			<?php 
	               $qryFieldExtra=mysqli_query($dbcon,"SELECT * FROM vehicle_log_extra_field WHERE stand='$stand' AND vlid='$vlid'");
	               if(mysqli_num_rows($qryFieldExtra) > 0){
	               	while($extraField=mysqli_fetch_assoc($qryFieldExtra)){
	               	?>
	                  <p class="fs-18 font-weight-normal mt-15"><?= $extraField["label_name"] ?>  <b><?php echo $extraField["label_value"]; ?></b></p>

	               	<?php
	               	}
	               }
		    		   ?>
		    			<div class="fs-18 font-weight-normal mt-20" id="inputtext"><span>Der Fahrzeugnutzer hat folgende Unterlagen erhalten:</span><input type="hidden"></div>
		    			<br>
		    			<br>
		    		</div>
		    		<div class="w-100">
					<table class="table-light" style="width:100%">
					<thead>
						<tr>
							<td>&nbsp;</td>
							<td>JA</td>
							<td>Nein</td>
							<td>Nicht ben&ouml;tigt</td>
						</tr>
					</thead>
					<tbody id="vehicleListTableCheckbox">
<?php
$table_name=($stand == 2) ? "vehicle_log_checkbox_return" : "vehicle_log_checkbox_handover";
$qryCheckbox=mysqli_query($dbcon,"SELECT * FROM ".$table_name." WHERE vlid='$vlid'");
while($arrCheckbox=mysqli_fetch_assoc($qryCheckbox)){
?>                      <tr>
							 <td><p><?= $arrCheckbox["label_name"]; ?></p><input type="hidden" class="checkboxLabel" value="<?= $arrCheckbox["label_name"]; ?>"></td>
							 <td>							
								 <label class="tasks-list-item">
							           <input type="radio" name="<?= $arrCheckbox["label_name"]; ?>" value="1" <?php echo ($arrCheckbox["label_value"] == 1) ? "checked" : "";?>  class="tasks-list-cb" onclick="return false;">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
							 <td>	
								 <label class="tasks-list-item">
							           <input type="radio" name="<?= $arrCheckbox["label_name"]; ?>" value="0" <?php echo ($arrCheckbox["label_value"] == 0) ? "checked" : ""; ?> class="tasks-list-cb" onclick="return false;">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
							 <td>	
								 <label class="tasks-list-item">
							           <input type="radio" name="<?= $arrCheckbox["label_name"]; ?>" value="2" <?php echo ($arrCheckbox["label_value"] == 2) ? "checked" : ""; ?> class="tasks-list-cb" onclick="return false;">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
						</tr>
       <?php
}
       ?>

				   </tbody>
				</table>
						<p class="mt-20 ml-20">Anzahl der Schl&uuml;ssel  <?php echo $arrPrint["no_of_key"]; ?></p>
		    		</div>
      </div>
	              <div style="margin-top:100px;"></div>
	              <!-- <div style=""> -->
	           <table class="PrintpdfTable" style="width:100%;margin:20px;">
						<thead>
							<tr>
								<td>&nbsp;</td>
								<td>JA</td>
								<td>Nein</td>
							</tr>
						</thead>
					<tbody id="handoverCheckbox2">
<?php 
$qry3=mysqli_query($dbcon,"SELECT * FROM vehicle_log_checkbox2 WHERE  vlid='$vlid' AND stand='$stand'");
$arr3=mysqli_fetch_assoc($qry3);
?>
						<tr>
							<td>Privatnutzung des Fahrzeugs</td>
							<td>							
								<label class="tasks-list-item">
					           <input type="radio" name="puov" value="1" <?php if($arr3["private_use"]== "1"){ ?> checked <?php } ?> class="tasks-list-cb" onclick="return false;">
					           <span class="tasks-list-mark"></span>
						    </label>
						   </td>
							<td>	
								<label class="tasks-list-item">
						           <input type="radio" name="puov" value="0" <?php if($arr3["private_use"]== "0"){ ?> checked <?php } ?> class="tasks-list-cb" onclick="return false;">
						           <span class="tasks-list-mark"></span>
							    </label>
						  </td>
						</tr>
						<tr>
							<td>Fahrtenbuchmethode</td>
								<td>							
									<label class="tasks-list-item">
							           <input type="radio" name="lbm" value="1" <?php if($arr3["log_book"]== "1"){ ?> checked <?php } ?> class="tasks-list-cb" onclick="return false;">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>
								<td>	
									<label class="tasks-list-item">
							           <input type="radio" name="lbm" value="0" <?php if($arr3["log_book"]== "0"){ ?> checked <?php } ?> class="tasks-list-cb" onclick="return false;">
							           <span class="tasks-list-mark"></span>
							    </label>
							 </td>

						</tr>
						<tr>
							<td>Home office</td>
								<td>							
									<label class="tasks-list-item">
							           <input type="radio" name="hoff" value="1" <?php if($arr3["home_office"]== "1"){ ?> checked <?php } ?> class="tasks-list-cb" onclick="return false;">
							           <span class="tasks-list-mark"></span>
							      </label>
							   </td>
								<td>	
									<label class="tasks-list-item">
							           <input type="radio" name="hoff" value="0" <?php if($arr3["home_office"]== "0"){ ?> checked <?php } ?> class="tasks-list-cb" onclick="return false;">
							           <span class="tasks-list-mark"></span>
							      </label>
							   </td>
						</tr>
<?php 
$qry5=mysqli_query($dbcon,"SELECT * FROM vehicle_log_checkbox2_extra WHERE vlid='$vlid' AND stand='$stand'");
$row5=mysqli_num_rows($qry5);
if($row5 > 0){
	while($arr5=mysqli_fetch_assoc($qry5)){
		?>
			 <tr>
			      <td>
			       <label><p><?= $arr5["checkbox_label"]; ?></p></label>
		       </td>
			       <td>
			       <label class="tasks-list-item">
					 <input type="radio" name="<?= $arr5["checkbox_label"]; ?>" value="1" <?php if($arr5["checkbox_value"]== "1"){ ?> checked <?php } ?> class="tasks-list-cb sel_class_<?= $arr5["checkbox_label"]; ?>" onclick="return false;">
					 <span class="tasks-list-mark"></span>
					 </label>
			       </td>
			       <td>
			       <label class="tasks-list-item">
					 <input type="radio" name="<?= $arr5["checkbox_label"]; ?>" value="0" <?php if($arr5["checkbox_value"]== "0"){ ?> checked <?php } ?> class="tasks-list-cb sel_class_<?= $arr5["checkbox_label"]; ?>" onclick="return false;">
					 <span class="tasks-list-mark"></span>
					 </label>
			      </td>
			      <td>
			 </tr>
        
		<?php
	}
}
 ?>
					</tbody>
					</table>
					<div class="mt-20 ml-20 mr-20 d-flex">
<?php
$qryvls=mysqli_query($dbcon,"SELECT * FROM vehicle_log_subtext WHERE vlid='$vlid' AND stand='$stand'");
$rowsvls=mysqli_num_rows($qryvls);
if($rowsvls > 0){
  while($arrvls=mysqli_fetch_assoc($qryvls)){	
 ?>
          <div style="display:block;margin-left:20px;">
					    <label><?php echo $arrvls["subtext_label"] ?></label><br>
					    <input type="text" class="text1" value="<?php echo $arrvls["subtext_value"] ?>" readonly>
					    </a>
					</div>
 <?php
  }
}
 ?>
</div>
				    <div class="mt-20 ml-20 mr-20 d-flex">
				    	<div class="w-50">
				    	<?php 
						$qry6=mysqli_query($dbcon,"SELECT * FROM vehicle_image WHERE vid='$hm'");
						$row6=mysqli_num_rows($qry6);
						if($row6 > 0){
							while($arr6=mysqli_fetch_assoc($qry6)){
								?>
						         <img src="../../img/vehicle_uploads/<?php echo $arr6["img"] ?>" style="width:300px;margin-top:30px;margin-bottom:30px;" >
								<?php
							}
						}
                  ?>
				    	</div>

			<div id="commentsSection" class="d-flex w-100" style="width:80%">
<?php
  $qryComment=mysqli_query($dbcon,"SELECT * FROM vehicle_log_comment WHERE vlid='$vlid'");
  if($qryComment){
  	while($arrComment=mysqli_fetch_assoc($qryComment)){
  		?>
	  		   <div class="d-block mt-20 mr-20" style="width:80%">
					<label class="font-weight-bold"><?= $arrComment["com_label"] ?></label><br>
					<textarea rows="5" style="width:100%" readonly><?= $arrComment["com_value"] ?></textarea>
			   </div>
<?php
  	}	
  }
?>
			</div>
	        </div>
             <br>
	    	<div class="ml-20 mt-10 mr-10">
					<label style="font-size:18px;" class="font-weight-bold">Datum</label><br>
                  <input type="text" name="date" value="<?php echo date("d.m.Y | H:i",strtotime($arr["last_change"]));?>" readonly>
				</div>
				<br>
		    	<div style="display:flex;justify-content:left;" class="d-flex mb-50 mt-50">
	    		<div class="d-block mr-20">
						<label class="font-weight-bold">Unterschrift Fuhrparkleiter</label><br>
						
						   <?php if(!empty($arr["sign_fleet_manager"])){?>
						<div style="border:solid 2px black;width:200px;padding:3px;height: 100px">
	                        <img src="../../img/signature_uploads/<?php echo $arr["sign_fleet_manager"]; ?>" style="width:100%;">
	                    </div>
	                    <p><?= getLastChangeEmplName($dbcon,$arr["signed_by"]) ?></p>
	               <?php }else{ ?>
                      <div style="border:solid 2px black;width:200px;padding:3px;height:100px">
                      </div> 
	               <?php } ?>
					</div>
					<div class="d-block" style="margin-left:20px;">
						<label class="font-weight-bold">Unterschrift Fahrzeugnutzer</label><br>
	               <?php if(!empty($arr["sign_vehicle_manager"])){?>
	               	  <div style="border:solid 2px black;width:200px;padding:3px;height:100px;">
	               	  	<img src="../../img/signature_uploads/<?php echo $arr["sign_vehicle_manager"]; ?>" style="width:100%;">
	               	  </div>
	                  
	               <?php }else{ ?>
                      <div style="border:solid 2px black;width:200px;padding:3px;height:100px;">
                      </div> 
	               <?php } ?>
					</div>
				</div>
	    	</div>
	    </form>
   	  </div>	
	</div>
