    <div class="my-100 mt-100" style="margin-bottom:50px;">
    	<div class="d-flex">
    		<a href="javascript:void(0)" class="btn bg-orange" onclick="printEquipment()" style="width:160px">PDF Arbeitsmittel</a>
    		<a href="javascript:void(0)" id="btnEditEquipment"class="btn bg-lightblue" onclick="editEquipment()">Bearbeiten</a>
    		<a href="javascript:void(0)" id="btnSaveEquipment" class="btn bg-green" onclick="saveEquipment()" style="display:none">Speichern</a>
    		<a href="javascript:void(0)" class="btn bg-grey" onclick="printDiv()">Drucken</a>
    	</div>
    	<div id="printworkEquip" class="border-2 w-80">
    		<div style="text-align:center">
    			<h3 id="headingText">Vereinbarung über die Überlassung von Arbeitsmittel</h3>
    			<input type="hidden" id="textinput">
    		</div>
    		<div style="margin:25px">
    			<p style="font-weight:bold;font-size:18px;" id="com_name"></p>
    			<!--<p style="font-weight:bold;font-size:18px;" id="com_street"></p>-->
				<p style="font-size:16px;margin-bottom:0.5rem"><span id="com_street"></span> <span id="company_house_no"></span></p>
				<!--<p style="font-weight:bold;font-size:18px;" id="company_house_no"></p>-->
    			<!--<p style="font-weight:bold;font-size:18px;" id="com_city"></p>-->
    			<!--<p style="font-weight:bold;font-size:18px;" id="com_zip"></p>-->
				<p style="font-size:16px;margin-bottom:0.5rem"><span id="com_zip"></span> <span id="com_city"></span></p>
    			<!-- <p style="font-weight:bold;font-size:18px;">LOREM</p> -->

    		</div>
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