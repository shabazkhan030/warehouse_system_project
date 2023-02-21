<div class="w-50 p-20">
	<div class="d-flex mb-20">
		<a href="#" class="d-inline-block p-5 cur-pointer text-light text-decoration ml-10 bg-orange">&Uuml;bergabeprotokoll UTA Tankkarte </a>
		<a href="javascript:void(0)" class="d-inline-block p-5 cur-pointer text-light text-decoration ml-10 bg-lightblue" id=editHandoverPrint>Bearbeiten</a>
		<a href="javascript:void(0)" class="d-inline-block p-5 cur-pointer text-light text-decoration ml-10 bg-green" style="display:none;" id=saveHandoverPrint>Speichern</a>
		<a href="javascript:void(0)" class="d-inline-block p-5 cur-pointer text-light text-decoration ml-10 bg-grey" onclick="printDivcreate()">Drucken</a>
	</div>
	<div id="printtankchipcreate" class="border-2 w-100">
		<div style="text-align:center;padding-top:30px;">
			<h2 id="headingText">&Uuml;bergabeprotokoll UTA Tankkarte</h2>
		    <input type="hidden" id="texthandover">	
		    <input type="hidden" id="phid">
		</div>
	    <div style="display:flex;justify-content: space-between;margin-top:20px;">
			<div style="margin:25px;width:50%">
				<p style="font-size:16px;margin-bottom:0.5rem"><b>Mitarbeiter</b></p>
				<p style="font-size:16px;margin-bottom:0.5rem"> <span id="empl_name"> </span></p>
				<p style="font-size:16px;margin-bottom:0.5rem"><b>Adresse</b></p>
				<p style="font-size:16px;margin-bottom:0.5rem;line-height:1.2;"> <span id="empl_street" style="width:120px;word-wrap: break-all;white-space:normal;"></span></p>
				<p style="font-size:16px;margin-bottom:0.5rem"> <span id="empl_zip"></span> <span id="empl_city"></span></p>
				<p style="font-size:16px;margin-bottom:0.5rem"> </p>
				 
				<br>
				<br>
				<p style="margin-bottom:0.5rem">Tel.:<span id="empl_phone"></span></p>
				<p style="margin-bottom:0.5rem">Geburtstag:<span id="empl_dob"></span></p>

			</div>
			<div style="margin:25px;width:50%">
				<p style="font-size:16px;"><b>Firma</b></p>
				<p style="font-size:16px;"> <span id="com_name"></span></p>
				<p style="font-size:16px;line-height:1.2;"> <span id="com_street" style="width:120px;word-wrap: break-all;white-space:normal;"></span></p>
				<p style="font-size:16px;"> <span id="com_zip"></span> <span id="com_city"></span></p>
				<p style="font-size:16px;"></p>
			</div>
	    </div>
	    <div style="margin:25px">
			<div style="display:flex;">
				<div style="margin-right:10px;" id="hallname">	         
				</div>
				<div style="margin-right:10px;">
					<label style="font-size:18px;font-weight:bold;">Info 1</label><br>
	                <p id="text1" style="width:200px;height:20px;border:1px solid #000000;padding:5px;"></p>
				</div>
				<div>
					<label style="font-size:18px;font-weight:bold;">Info 2</label><br>
	                <p id="text2" style="width:200px;height:20px;border:1px solid #000000;padding:5px;"></p>
				</div>
			</div>
		</div>
		<div style="margin:30px 25px">
			<p id="paraHandover"style="font-size:16px;font-weight:normal">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			<textarea id="textareaHandover" style="display:none;width:100%" rows="7"></textarea>
		</div>
		<div class="ml-20 mt-10 mr-10" style="margin-left:25px;">
			<label style="font-size:18px;font-weight:bold;">Datum</label>
			<br>
			<p id="handoverData" style="width:150px;border:1px solid #000000;padding:5px"></p>
		</div>
		<div style="display:flex;margin-left:25px;margin-top:20px;margin-bottom:30px;">
			<div style="margin-right:20px;">
				<label class="font-weight-bold">Unterschrift Sachbearbeiter
</label><br>
				<div style="width:160px;height:70px;padding-top:10px;border:1px solid #000000" id="handoverSignStaff">
	                   
	            </div>
			</div>
			<div>
				<label class="font-weight-bold">Unterschrift Mitarbeiter
</label><br>
				<div style="width:160px;height:70px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
					<img src=""  style="width:100%;">
				</div>
			</div>
		</div>
	</div>
</div>
<!-- TANKCHIP RETRUN -->
<div class="w-50 p-20">
	<div class="d-flex mb-20">
		<a href="javascript:void(0)" class="d-inline-block p-5 cur-pointer text-light text-decoration ml-10 bg-orange">R&uuml;ckgabe UTA Tankkarte</a>
		<a href="javascript:void(0)" class="d-inline-block p-5 cur-pointer text-light text-decoration ml-10 bg-lightblue" id=editReturnPrint>Bearbeiten</a>
		<a href="javascript:void(0)" class="d-inline-block p-5 cur-pointer text-light text-decoration ml-10 bg-green" style="display:none;" id=saveReturnPrint>Speichern</a>
		<a href="javascript:void(0)" class="d-inline-block p-5 cur-pointer text-light text-decoration ml-10 bg-grey" onclick="printDivreturn()">Drucken</a>
	</div>
	<div id="printtankchipreturn" class="border-2 w-100">
		<div style="text-align:center;padding-top:30px;">
			<h2 id="headingTextReturn">R&uuml;ckgabe UTA Tankkarte</h2>
		    <input type="hidden" id="textReturn">	
		    <input type="hidden" id="prid">	
		</div>
          <div style="display:flex;justify-content: space-between;margin-top:20px;">
			<div style="margin:25px;width:50%">
    			<p style="font-size:16px;margin-bottom:0.5rem"><b>Mitarbeiter</b></p>
    			<p style="font-size:16px;margin-bottom:0.5rem"><span id="rempl_name"></span></p>
    			<p style="font-size:16px;margin-bottom:0.5rem"><b></b></p>
    			<p style="font-size:16px;margin-bottom:0.5rem;line-height:1.2;"><span id="rempl_street" style="width:120px;word-wrap: break-all;white-space:normal;"></span></p>
    			<p style="font-size:16px;margin-bottom:0.5rem"><span id="rempl_zip"></span> <span id="rempl_city"></span></p>
    			<p style="font-size:16px;margin-bottom:0.5rem"></p>
    			 
    			<br>
    			<br>
    			<p style="margin-bottom:0.5rem">Tel.:<span id="rempl_phone"></span></p>
    			<p style="margin-bottom:0.5rem">Geburtstag:<span id="rempl_dob"></span></p>

    		</div>
    		<div style="margin:25px;width:50%">
    			<p style="font-size:16px;"><b>Firma</b></p>
    			<p style="font-size:16px;"> <span id="rcom_name"></span></p>
    			<p style="font-size:16px;line-height:1.2;"> <span id="rcom_street" style="width:120px;word-wrap: break-all;white-space:normal;"></span></p>
    			<p style="font-size:16px;"><span id="rcom_zip"></span> <span id="rcom_city"></span></p>
    			<p style="font-size:16px;"></p>
    		</div>
          </div>
        <div style="margin:25px">
			<div style="display:flex;">
				<div style="margin-right:10px;" id="rhallname">
<!-- 					<label style="font-size:18px;font-weight:bold;">Hallmark</label><br>
					<p id="rhallname" style="width:200px;border:1px solid #000000;padding:5px;"></p> -->
             
				</div>
				<div style="margin-right:10px;">
					<label style="font-size:18px;font-weight:bold;">Info 1</label><br>
                    <p id="rtext1" style="width:200px;height:20px;border:1px solid #000000;padding:5px;"></p>
				</div>
				<div>
					<label style="font-size:18px;font-weight:bold;">Info 2</label><br>
                    <p id="rtext2" style="width:200px;height:20px;border:1px solid #000000;padding:5px;"></p>
				</div>
			</div>
		</div>
		<div style="margin:30px 25px">
			<p id="paraReturn"style="font-size:16px;font-weight:normal">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			<textarea id="textareaReturn" style="display:none;width:100%" rows="7"></textarea>
		</div>
		<div class="ml-20 mt-10 mr-10" style="margin-left:25px;">
			<label style="font-size:18px;font-weight:bold;">Datum</label>
			<br>
			<p id="returnData" style="width:150px;border:1px solid #000000;padding:5px"></p>
		</div>
		<div style="display:flex;margin-left:25px;margin-top:20px;margin-bottom:30px;">
			<div style="margin-right:20px;">
				<label class="font-weight-bold">Unterschrift Sachbearbeiter
</label><br>
				<div style="width:160px;height:70px;padding-top:10px;border:1px solid #000000" id="returnSignStaff">
                    <!-- <img src=""  style="width:100%"> -->
                </div>
			</div>
			<div>
				<label class="font-weight-bold">Unterschrift Mitarbeiter
</label><br>
				<div style="width:160px;height:70px;padding-top:10px;border:1px solid #000000"  id="returnEmplSign">
					<!-- <img src="" style="width:100%;"> -->
				</div>
			</div>
		</div>
    </div>
</div>