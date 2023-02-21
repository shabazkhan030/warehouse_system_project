function addMoreComments(){
let commentLabel=getID("comLabel").value.trim();
console.log(commentLabel);
let commentsSection=getID("commentsSection");
let data='';
	if(commentLabel != ''){
	    let data=`<div class="d-flex w-100">
	                <div class="d-block w-80">
	                <label class="font-weight-bold">${commentLabel}</label>
		              <textarea rows="6" class="w-100 commentText"></textarea>
		              <input type="hidden" value="${commentLabel}" class="commentLabel" >
		              </div>
		              <div class="d-block"><br><br>
		                  <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-20" aria-label="Close"  onclick="removecomment(this)"></a>
		              </div>
		            </div>`;
         console.log(data);
		 commentsSection.innerHTML+=data;       
	}else{
		myToast(0,"please Insert comment Label name..");
	}
	getID("comLabel").value='';
}
function deletecomment(el){
	let comId=el;
	var formdata=new FormData();
	formdata.append("deleteComment",1);
	formdata.append("commentId",comId);
	fetch("../../ajax/ajax_employee.php",
	{
		method:"POST",
		body:formdata
	}).then(res=>res.json())
	.then((data)=>{
		if(data.status == 1){
			myToast(data.status,data.message);
			setTimeout(function(){ location.reload()},3000);
		}else{
			myToast(data.status,data.message);
		}
	})
}
function deleteCheckbox2(el){
	let delID=el;
	var formdata=new FormData();
	formdata.append("deleteCheckbox2",1);
	formdata.append("delID",delID);
	fetch("../../ajax/ajax_employee.php",{
		method:"POST",
		body:formdata
	}).then(res=>res.json())
	.then((data)=>{
		if(data.status == 1){
			myToast(data.status,data.message);
			setTimeout(function(){ location.reload()},3000);
		}else{
			myToast(data.status,data.message);
		}
	})
}
function deletesubtext(el){
	let delID=el;
	console.log(delID);
	var formdata=new FormData();
	formdata.append("deletesubtext",1);
	formdata.append("delID",delID);
	fetch("../../ajax/ajax_employee.php",{
		method:"POST",
		body:formdata
	}).then(res=>res.json())
	.then((data)=>{
		if(data.status == 1){
			myToast(data.status,data.message);
			setTimeout(function(){ location.reload()},3000);
		}else{
			myToast(data.status,data.message);
		}
	})
}
function removecomment(el){
	el.parentElement.parentElement.innerHTML='';
}

function removeCheckbox2(el){
	el.parentElement.parentElement.parentElement.remove();
}
function removeSubtext(el){
	el.parentElement.remove();
}
function fleetManager(){
   getID("note_fleet_mngr").innerHTML="";
}
function vehicleManager(){
   getID("note_vh_mngr").innerHTML="";
}
function signatureFleetManager(){
	let wrapper = getID("signature-pad-fleet-mngr");
	let clearButton = wrapper.querySelector("[data-action=clearFleetMngrSign]");
	let btnClick=getID("save_btn_fleet_mngr");
	let canvas = wrapper.querySelector("#the_canvas_fleet_mngr");
	let el_note = getID("note_fleet_mngr");
	let signaturePad;
	signaturePad = new SignaturePad(canvas);
	clearButton.addEventListener("click", function (event) {
	   getID("note_fleet_mngr").innerHTML="Die Unterschrift sollte innerhalb der Box sein";
	   signaturePad.clear();
	});
	btnClick.addEventListener("click", function (event){
		event.preventDefault();
	   if (signaturePad.isEmpty()){
	     myToast(0,"Please provide signature first.");
	   }else{
	     let canvas  = getID("the_canvas_fleet_mngr");
	     let dataUrl = canvas.toDataURL();
	     getID("signature_fleet_mngr").value = dataUrl;
	     myToast(1,"Successfully Uploaded Signature");
	   }
	});
}
function signatureVehicleManager(){
	let wrapper = getID("signature-pad-vh_mngr");
	let clearButton = getID("clear_btn_vh_mngr");
	let btnClick=getID("save_btn_vh_mngr");
	let canvas = wrapper.querySelector("#the_canvas_vh_mngr");
	let el_note = getID("note_vh_mngr");
	let signaturePad;
	signaturePad = new SignaturePad(canvas);
	clearButton.addEventListener("click", function (event) {
	   getID("note_vh_mngr").innerHTML="Die Unterschrift sollte innerhalb der Box sein";
	   signaturePad.clear();
	});
	btnClick.addEventListener("click", function (event){
		 event.preventDefault();
	   if (signaturePad.isEmpty()){
	     myToast(0,"Please provide signature first.");
	   }else{
	     let canvas  = getID("the_canvas_vh_mngr");
	     let dataUrl = canvas.toDataURL();
	     getID("signature_vh_mngr").value = dataUrl;
	     myToast(1,"Successfully Uploaded Signature..");
	   }
	});
}
function createcheckbox2(){
	let tbody=getID("handoverCheckbox2");
	let checkbox2= getID("checkbox2");
	checkboxvalue=checkbox2.value.replace(/ /g,'');
	if(checkboxvalue == ''){
   myToast(0,"field cannot be empty");
	}else{
   var tr='<tr>';
       tr+='<td>';
       tr+='<label><p>'+checkboxvalue+'</p></label>';
       tr+='<input type="hidden" value="0" id="sel_chbx_2'+checkboxvalue+'" name="chckbox2_slct_val[]">';
       tr+='<input type="hidden" value="'+checkboxvalue+'" class="checkbox2" name="checkbox2[]">';
       tr+='</td>';
       tr+='<td>';
       tr+='<label class="tasks-list-item">';
		 tr+='<input type="radio" name="'+checkboxvalue+'" value="1" class="tasks-list-cb sel_class_'+checkboxvalue+'">';
		 tr+='<span class="tasks-list-mark"></span>';
		 tr+='</label>';
       tr+='</td>';
       tr+='<td>';
       tr+='<label class="tasks-list-item">';
		 tr+='<input type="radio" name="'+checkboxvalue+'" value="0" checked  class="tasks-list-cb sel_class_'+checkboxvalue+'">';
		 tr+='<span class="tasks-list-mark"></span>';
		 tr+='</label>';
       tr+='</td>';
       tr+='<td>';
       tr+='<div class="d-block">';
		 tr+='<a href="javascript:void(0)" class="cancel-btn text-decoration mt-50" aria-label="Close" onclick="removeCheckbox2(this);"></a>';
		 tr+='</div>';
       tr+='</td>';
       // tr+='</td>';
       tr+='</tr>';
     tbody.insertRow().innerHTML=tr;
     let allCheckBox = queryAll('.sel_class_'+checkboxvalue);

			allCheckBox.forEach((checkbox) => { 
			checkbox.addEventListener('change',(event) => {
				if (event.target.checked){
					let chbxAllClass=event.target.className;	
				   chbxClass=chbxAllClass.split("sel_class_")
					getID("sel_chbx_2"+chbxClass[1]).value=event.target.value;
			    }
			  })
			})

	}
  getID("checkbox2").value = '';
}

btnCreateMainField=queryOne('.create-fields');
btnCreateMainField.addEventListener("click",function(){
var fieldname=getID('fieldname').value;
	if(fieldname == ''){
	    var msg="Please fill text name field";
	    myToast(0,msg);
	}else{
		createTextmainOnly(fieldname);
		getID('fieldname').value='';
	}
})
let btncreateSubtext=getID("btnCreateSubtext");
   btncreateSubtext.addEventListener("click",function(){
   	 let subtextvalue=getID("subtextvalue").value.trim();
      if(subtextvalue != ''){
           let html=`
             <div class="d-block mt-20">
		         <label>${subtextvalue}</label><br>
		         <input type="text" class="text1">
		         <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-10" aria-label="Close" onclick="removeSubtext(this);"></a>
		         <input type="hidden" class="subtextlabel" value="${subtextvalue}">
		    </div>`;
		let subtext=getID("subtext");	                
           subtext.innerHTML+=html
      }else{
      	myToast(0,"Please provide subtext label..")
      }

    getID("subtextvalue").value='';
   });
function showPreview(_this,event){
  if(event.target.files.length > 0){
    var src = URL.createObjectURL(event.target.files[0]);
    var preview = _this.previousElementSibling.children[0];
    preview.style.display = "flex";
    preview.src = src; 
  }
}
function removeVehicleSrc(imgsrc){
	let imgSrc=imgsrc.previousElementSibling.src;
	console.log(imgSrc);
	if(imgSrc != "http://localhost/warehouse_system/img/icon/preview_image.jpg"){
		imgsrc.parentElement.nextElementSibling.value='';
		imgsrc.previousElementSibling.setAttribute("src","http://localhost/warehouse_system/img/icon/preview_image.jpg");
	}
}
function editBtn(stand){
	let editbtn=getID("editPrint");
	let savebtn=getID("savePrint");
	let data=getID("header-text");
	let headerText=data.children[0];
	let headerTextEdit=getID("headerTextedit");
	let ptext=getID("inputtext").children[0];
	let inputtext=getID("inputtext").children[1];
   // seeting button
   editbtn.style.display="none";
   savebtn.style.display="block";
	//setting header text
	headerTextEdit.setAttribute("type","text");
	headerText.style.display="none";
	headerTextEdit.value=headerText.innerHTML;
	// setting text value
   ptext.style.display="none";
   inputtext.setAttribute("type","text")
   inputtext.value=ptext.innerHTML;
}
function saveBtn(stand){
	let editbtn=getID("editPrint");
	let savebtn=getID("savePrint");
	let data=getID("header-text");
	let headerText=data.children[0];
	let headerTextEdit=getID("headerTextedit");
	let ptext=getID("inputtext").children[0];
	let inputtext=getID("inputtext").children[1];
   // seeting button
   editbtn.style.display="block";
   savebtn.style.display="none";
	//setting header text
	headerTextEdit.setAttribute("type","hidden");
	headerText.style.display="block";
	headerText.innerHTML=headerTextEdit.value;
	// setting text value
   ptext.style.display="block";
   inputtext.setAttribute("type","hidden")
   ptext.innerHTML=inputtext.value;

   myToast(1,"Successfully Edited print text");
}
// function checkSignaturePurchaser(){
// 	let oid=getID("oid").value;
// 	let formdata=new FormData();
// 	formdata.append("signature",1);
// 	formdata.append("oid",oid);
// 	fetch("../ajax/ajax_office_order.php",{
//           method:"POST",
//           body: formdata
// 	     }).then((res)=>res.json())
// 	     .then((data)=>{
// 	     	if(data.sing_p != ''){
// 	     		let signatureWrapper=getID("signaturePurchaserWrapper");
// 	     		let signform=getID("signPurchaser");
// 	     		let img=getID("signaturepurchaserimage");
// 	     		img.setAttribute("src","../img/signature_uploads/"+data.sign_p);
// 	     		signform.style.display="none";
// 	     		signatureWrapper.style.display="block";
// 	     	}
// 	     	if(data.sign_d != ''){
// 	     		let signatureWrapper=getID("signatureDirectorWrapper");
// 	     		let signform=getID("signManagingDirector");
// 	     		let img=getID("signaturedirectorimage");
// 	     		img.setAttribute("src","../img/signature_uploads/"+data.sign_d);
// 	     		signform.style.display="none";
// 	     		signatureWrapper.style.display="block";
// 	     	}
// 	     })
// }