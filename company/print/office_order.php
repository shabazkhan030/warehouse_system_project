<div class="my-100 mt-50">
	    <div class="w-100 p-20">
    		<div class="d-flex">
	    		<a href="#" class="btn-sm bg-orange" style="width:250px">Bestellung</a>
	    		<!--<a href="javascript:void(0)" onclick="editPrint();" class="btn-sm bg-lightblue">Bearbeiten</a>-->
	    		<a href="javascript:void(0)" class="btn-sm bg-grey" onclick="printDivcreate()">Drucken</a>
	    	</div>
	    	<div id="printtankchipcreate" class="border-2 w-100">
	    		<div class="d-flex my-40">
	    			<h3 class="text-left mt-20">Bestellung</h3>
	    			
	    		</div>
	    		<div class="d-flex justify-content-between my-40 mt-50">
	    			<p><?php echo $arr["firstname"]." ".$arr["surname"]; ?></p>
	    			<div>Erstellt am <input type="text" style="width:150px;margin-top:-20px;" value="<?php echo date('d.m.Y',strtotime($arr['submitted'])); ?>" readonly></div>
	    			<div>
	    			    <img src="../img/company_uploads/<?= $_SESSION["company"]["clogo"]?>" style="width:80px;height:80px;">	
	    			    <h2><?php echo getCompanyName($dbcon,$cid); ?></h2>
	    			</div>
	    		</div>
	    		<div class="my-40">
		    		<table class="w-100 table-light">
						<thead>
							<tr>
							
							
								<td>Akzeptiert</td>
								<td>Wunschmenge</td>
								<td>Material / Produkt </td>
								<td>Gr&ouml;&szlig;e</td>
								<td>Sachbearbeiter</td>
								<td>Anmerkung</td>
								<td>Gesamt</td>
							</tr>
						</thead>
						<tbody>
					<?php
					$qry=mysqli_query($dbcon,"SELECT ood.*,e.id AS emp_id,e.firstname,e.surname FROM office_order_details ood LEFT JOIN employee e ON ood.eid=e.id WHERE ood.order_id='$oid' AND ood.chbx='1'");
					if($qry){
						if(mysqli_num_rows($qry) > 0){
                         while($arr1=mysqli_fetch_assoc($qry)){
                       	$emp_name=(empty($arr1["emp_id"])) ? "NO" : $arr1["firstname"].' '.$arr1["surname"];
          ?>  
            <tr>
                <td>							    
					 <label class="tasks-list-item">
				           <input type="checkbox" name="task_1" class="checkboxofficeorder tasks-list-cb removeAttributes" <?php if($arr1["chbx"] == 1){ echo "checked"; } ?> readonly>
				           <span class="tasks-list-mark"></span>
				    </label>
				 </td>
                <td><input type="text" class="qtyofficeorder removeAttributes" value="<?php echo $arr1["amt"]; ?>" style="width:60px;padding:3px;" readonly>
                </td>
                <td><input type="text" value="<?php echo $arr1["inventory_article"]; ?>" style="width:180px;" class="removeAttributes" readonly> </td>
              	<td><input type="text" value="<?php echo $arr1["size"]; ?>" style="width:60px;padding:3px;" readonly></td>
              	<td><input type="text" value="<?php echo $emp_name; ?>" style="width:180px;" readonly></td>
              	<td><input type="text" style="width:200px;" value="<?php echo $arr1["remarks"]?>" readonly></td>
              	<td><input type="text" value="<?php echo $arr1["amt"]*$arr1["piece_price"]?>" style="width:100px;" readonly></td>
            </tr>
            <?php
               }
						}else{
							echo '<tr class="text-center">
                       <td colspan="8">Keine Bestellung gefunden...</td>
							      </tr>';
						}
					}
					?>
						</tbody>
					</table>
	    		</div>
	    		<div class="my-40 mt-50">
	    			<div>
	    				<label class="font-weight-bold mr-20">Unterschrieben am</label>
	    				<input type="text" style="width:150px;margin-top:-20px;" value="<?php echo date('d.m.Y',strtotime($arr['signature_closed'])); ?>" readonly>
	    			</div>
	    			<div style="display:flex;justify-content:left;" class="d-flex mb-50 mt-50">
	    				<div class="d-block mr-20">
	    					<label style="margin-bottom:10px;">Unterschrift Sachbearbeiter</label><br><br>
				    		<?php 
			              if($arr["sign_purchaser"] == ''){
								   ?>
								   <div style="border:solid 2px black;width:200px;height:90px;padding:3px;">
								
									</div>
								<?php }else{?>
									<div id="signaturePurchaserWrapper" style="border:solid 2px black;width:200px;height:90px;padding:3px;">
										  <img src="../img/signature_uploads/<?php echo $arr["sign_purchaser"];?>" style="width:100%;" id="signaturepurchaserimage">
									</div>
									<p><?= $arr["firstname"]." ".$arr["surname"]; ?></p>
								<?php } ?>
	    				</div>
	    				<div class="d-block">
	    					<label>Unterschrift Gesch&auml;ftsf&uuml;hrer</label>
	    					<br><br>
				    			<?php
				    				if($arr["sign_director"] == ''){
								   ?>
								    <div style="border:solid 2px black;width:200px;height:90px;padding:3px;">
									</div>
									<?php }else{?>
									<div id="signatureDirectorWrapper" style="border:solid 2px black;width:200px;height:90px;padding:3px;">
										  <img src="../img/signature_uploads/<?php echo $arr["sign_director"];?>" style="width:100%;" id="signaturedirectorimage">
									</div>
									<p><?php echo getLastChangeEmplName($dbcon,$arr["signed_by_dir"]) ?></p>
								<?php } ?>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
    	</div>			
	</div>