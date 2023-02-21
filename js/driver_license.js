function createIconCheckbox(){
           getID("create-icon-checkbox").style.display="flex";
}
function removeIconCheckboxCreate(){
	getID("create-icon-checkbox").style.display="none";
}
let icon=getID("iconFilename");
icon.addEventListener("change",(e)=>{
  const file=icon.files[0];
  const reader=new FileReader();

  reader.addEventListener("load",()=>{
  	getID("iconimage").value=reader.result;
  })
  reader.readAsDataURL(file);
})
function addIconCheckbox(){
  let labelname= getID("iconlabelName").value;
  let fileName=getID("iconimage").value;
  let viewAll=document.querySelector('#viewAll');
  let view=(viewAll.checked == true) ? 1 : 0;
  console.log(view);
  if(labelname != ''){
	html=`<div class="d-flex" style="padding:20px 40px">
		  <label class="tasks-list-item" style="display:flex;">
        <input type="checkbox" class="tasks-list-cb iconCheckbox">
        <span class="tasks-list-mark"></span>
        <span class="tasks-list-desc" style="margin-right:10px;margin-top:7px;">
        <p style="display:block">${labelname}</p>
        <input type="hidden" value="${labelname}" class="label" style="width:50px;height:25px;margin-top:-10px;padding:1px;">
        <input type="hidden" value="0" class="sort_by">
        </span>
        </label>
        <span class="d-flex">
           <div></div>
           <img src="${fileName}" style="width:25px;height:25px;">
           <select style="display: none; width: 70px; padding: 2px; height: 25px; margin-left: 8px; margin-top: 3px;" class="view">
			      <option value="0" ${(view == 0) ? "selected" : "" }>deaktiviert</option>
			      <option value="1" ${(view == 1) ? "selected" : "" }>aktiv</option>
			  </select>
           <a class="cancel-btn cur-ponter iconCancel" onclick="removeIconcheckbox(this)" style="display:none; margin-left: 10px; width: 0px; height: 15px;"></a>
        </span>
        <input type="hidden" value="${fileName}" class="icon">    
     </div>`;
     getID("checkboxIcon").innerHTML+=html;
 }else{
      myToast(0,"Name field cannot be empty");
 }
  getID("iconlabelName").value=''; 
  getID("iconFilename").value='';
}
function removeIconcheckbox(el){
	console.log(el.parentElement.parentElement.remove())
}
function deletecomment(el){
	console.log(el);
}
function addMoreComments(){
let commentLabel=getID("comLabel").value.trim();
console.log(commentLabel);
let commentsSection=getID("commentsSection");
let data='';
	if(commentLabel != ''){
	    let data=`<div class="d-flex w-100">
	                <div class="d-block w-80">
	                <label class="font-weight-bold">${commentLabel}</label>
		              <textarea rows="6" class="w-100 commentText1"></textarea>
		              <input type="hidden" value="${commentLabel}" class="commentLabel1" >
		              </div>
		              <div class="d-block"><br><br>
		                  <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-20" aria-label="Close"  onclick="removecomment(this)"></a>
		              </div>
		            </div>`;

		 commentsSection.innerHTML+=data;       
	}else{
		myToast(0,"please Insert comment Label name..");
	}
	getID("comLabel").value='';
}
function addMoreComments2(){
let commentLabel=getID("comLabel2").value.trim();
console.log(commentLabel);
let commentsSection=getID("commentsSection2");
let data='';
	if(commentLabel != ''){
	    let data=`<div class="d-flex w-100">
	                <div class="d-block w-80">
	                <label class="font-weight-bold">${commentLabel}</label>
		              <textarea rows="6" class="w-100 commentText2"></textarea>
		              <input type="hidden" value="${commentLabel}" class="commentLabel2" >
		              </div>
		              <div class="d-block"><br><br>
		                  <a href="javascript:void(0)" class="cancel-btn text-decoration mt-50 ml-20" aria-label="Close"  onclick="removecomment(this)"></a>
		              </div>
		            </div>`;
		 commentsSection.innerHTML+=data;       
	}else{
		myToast(0,"please Insert comment Label name..");
	}
	getID("comLabel2").value='';
}
function removecomment(el){
	el.parentElement.parentElement.innerHTML='';
}
function editComment(el){
	el.style.display="none";
	el.nextElementSibling.style.display="block";
	el.parentElement.children[0].style.display="none";
	el.parentElement.children[1].setAttribute("type","text");

}
function removeComment(el){
	id=getAttr(el,"data-remove");
	let formdata=new FormData();
	formdata.append("removeComment",1);
	formdata.append("id",id);
	fetch("../ajax/ajax_vehicle_list.php",{
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
function saveComment(el){
	id=getAttr(el,"data-id");
	label=el.parentElement.children[1].value;
		let formdata=new FormData();
	formdata.append("saveComment",1)
	formdata.append("id",id);
	formdata.append("label",label);
	fetch("../ajax/ajax_vehicle_list.php",{
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
function addMoreRow(){
	let tbodyvehicleList=getID("vehicleListTableCheckbox");
	let view=(getID("viewtoall").checked) ? 1 : 0;
	// const regex = /[^A-Za-z0-9_]/g;.replace(regex, "").trim()  .replace(regex, "").
	let labelName=getID("labelname").value;
	let textName=getID("textName").value.trim();
	let data='';
		if(labelName != ''){
		data+=`<tr>
		         <td style="padding:0px;">${labelName}<input type="hidden" class="cb_labelname" value="${labelName}"></td>
		         <td style="padding:0px;"><input type="text" class="cb_textname" value="${textName}"></td>
					<td style="padding:0px;">							
						<label class="tasks-list-item">
				           <input type="checkbox" name="${labelName}" value="1" class="tasks-list-cb cb_checkbox cb_yes">
				           <span class="tasks-list-mark"></span>
				    </label> 	
				  </td>

					<td style="padding:0px;">
							<label class="tasks-list-item">
					           <input type="checkbox" name="${labelName}" value="0" checked class="tasks-list-cb cb_checkbox cb_no">
					           <span class="tasks-list-mark"></span>
					    </label>
				  </td>
				  <td style="padding:0px;">
				 	 <div class="d-flex">	
				 	      <a href="javascript:void(0)" class="d-inline-block bg-green text-light p-5 text-decoration">Edit</a>
				 	      <a href="javascript:void(0)" class="d-inline-block bg-green text-light bg-blue p-5 text-decoration ml-10" style="display:none">Speichern</a>
				 	 </div>
				 </td>
				 <td style="padding:0px;">
				 	  <select class="cb_showall" style="width:80px;padding:3px;">
				 	      ${(view == 1) ? '<option value="1" selected>Active</option><option value="0">Disabled</option>' : '<option value="1">Active</option><option value="0" selected>Disabled</option>'}
				 	 </select>
				 </td>	
			</tr>`;
			tbodyvehicleList.insertRow().innerHTML=	data;
			getID("labelname").value='';
			getID("textName").value='';
		}else{
		   myToast(0,"please Enter label Name..");
		}   
}
function fleetManager(){
   getID("note_fleet_mngr").innerHTML="";
}
function vehicleUser(){
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
function confirmModalDLImage(el){
  var modal =getID("confirmModalDLImage");
  modal.style.display = "block";
  var span = document.getElementsByClassName("closeModal")[2];
  var id=getAttr(el,"data-delete");
  getID("dataDelete").value=id;

  span.onclick = function() {
    modal.style.display = "none";
  }
  window.onclick = function(event) {
    if(event.target == modal){
      modal.style.display = "none";
    }
  }
}
function confirmDeleteDLImage(){
  let id=getID("dataDelete").value;
  var modal =getID("confirmModalDLImage");
   modal.style.display = "none";
   deleteDLImage(id);
}
function deleteDLImage(el){
  let imgId=el;
  let formdata=new FormData();
  formdata.append("deleteDLImage",1)
  formdata.append("imgId",imgId);
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
function mainTextLabelEdit(el){
	el.parentElement.children[0].style.display="none";
	el.parentElement.children[1].setAttribute("type","text");
	el.parentElement.children[2].style.display="none";
    el.parentElement.children[3].style.display="block";
}
function mainTextLabelSave(el){
	el.parentElement.children[0].innerText=el.parentElement.children[1].value;
	el.parentElement.children[0].style.display="block";
	el.parentElement.children[1].setAttribute("type","hidden");
	el.parentElement.children[3].style.display="none";
    el.parentElement.children[2].style.display="block";
}
function EditIconCheckbox(el){
  el.style.display="none";
  el.nextElementSibling.style.display="flex";
  queryAll(".label").forEach((label)=>{
  	 label.parentElement.children[0].style.display="none";
  	 label.setAttribute("type","text");
  });
  queryAll(".iconCancel").forEach((ic)=>{
  	ic.parentElement.children[2].style.display="flex";
  	ic.style.display="flex";
  	ic.parentElement.children[3].style.display="flex";
  })
}
function SaveIconCheckbox(el){
  el.style.display="none";
  el.previousElementSibling.style.display="flex";
  queryAll(".label").forEach((label)=>{
  	 label.parentElement.children[0].style.display="block";
  	 label.setAttribute("type","hidden");
  });
  queryAll(".iconCancel").forEach((ic)=>{
  		// console.log(ic.parentElement)
  	ic.style.display="none";
  	ic.parentElement.children[2].style.display="none";
  	ic.parentElement.children[3].style.display="none";
  })
}
function confirmModalCom(el){
  console.log("hello comment");
  var modal =getID("confirmModalCom");
  modal.style.display = "block";
  var span = document.getElementsByClassName("closeModal")[1];
  let id=getAttr(el,"data-delete");
  let pos=getAttr(el,"data-pos");
  getID("dataDelete").value=id;
  getID("pos").value=pos;
  span.onclick = function() {
    modal.style.display = "none";
  }
  window.onclick = function(event){
    if(event.target == modal){
      modal.style.display = "none";
    }
  }
}
function confirmDeleteCom(){
  let id=getID("dataDelete").value;
  let pos=getID("pos").value;
  var modal =getID("confirmModalCom");
   modal.style.display = "none";
  deletecomDL(id,pos);
}
function deletecomDL(el,pos){
	let comId=el;
	var formdata=new FormData();
	formdata.append("deleteComment",1);
	formdata.append("commentId",comId);
	formdata.append("com_view",pos);
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
function deleteIconModal(el){
  var id=getAttr(el,"data-delete");
  if(id == 0){
  	el.parentElement.children[1].style.display="none";
  	el.parentElement.children[1].setAttribute("src","");
  	el.parentElement.children[0].style.display="none";
  	el.parentElement.parentElement.children[2].value=''
    el.parentElement.parentElement.children[0].style.display='flex';
    el.parentElement.children[0].style.display='flex';
    el.parentElement.children[2].style.display="none";
    console.log(el.parentElement);
  }else{
  	  var modal =getID("confirmModalIcon");
      modal.style.display = "block";
      var span = document.getElementsByClassName("closeModal")[0];
	  getID("dataDelete").value=id;
	  span.onclick = function() {
	    modal.style.display = "none";
	  }
	  window.onclick = function(event) {
	    if (event.target == modal) {
	      modal.style.display = "none";
	    }
	  }
  }
}

function EditCheckbox2(el){
	el.style.display="none";
	el.parentElement.parentElement.parentElement.children[0].children[0].style.display="none";
	el.parentElement.parentElement.parentElement.children[0].children[1].setAttribute("type","text");
	// console.log();
	el.nextElementSibling.style.display="inline-block";
	// console.log()
}
function saveCheckbox2(el){
	el.style.display="none";
	el.previousElementSibling.style.display="inline-block";
	el.parentElement.parentElement.parentElement.children[0].children[0].style.display="inline-block";
	el.parentElement.parentElement.parentElement.children[0].children[1].setAttribute("type","hidden");
	//.replace(/ /g,'')
 let label=el.parentElement.parentElement.parentElement.children[0].children[1].value;
	el.parentElement.parentElement.parentElement.children[0].children[0].innerText=label;
	el.parentElement.parentElement.parentElement.children[2].children[0].children[0].setAttribute("name",label);
  el.parentElement.parentElement.parentElement.children[3].children[0].children[0].setAttribute("name",label);
}
function newUploadIcon(el){
	const file=el.files[0];
  const reader=new FileReader();
  
  reader.addEventListener("load",()=>{
  	el.parentElement.parentElement.children[0].style.display="none";
    el.parentElement.parentElement.children[1].setAttribute("src",reader.result);
    el.parentElement.parentElement.children[1].style.display="";
  	el.parentElement.parentElement.nextElementSibling.value=reader.result
  	el.setAttribute("type","hidden");
  })
  reader.readAsDataURL(file);
}
function confirmDeleteIcon(){
  let id=getID("dataDelete").value;
  var modal =getID("confirmModalIcon");
   modal.style.display = "none";
  deleteIcon(id);
}
function deleteIcon(id){
	let formdata=new FormData();
	formdata.append("deleteIcon",1);
	formdata.append("id",id);
	fetch("../../ajax/ajax_employee.php",{
		method:"POST",
		body:formdata
	}).then(res=>res.json())
	.then((data)=>{
		if(data.status == 1){
			myToast(data.status,data.message);
			setTimeout(function(){
				location.reload();
			},200);
		}else{
			myToast(data.status,data.message);
		}
	})
}

queryAll(".cb_checkbox").forEach((index)=>{
	index.addEventListener("click",function(){
		if(index.parentElement.parentElement.nextElementSibling.children[0].classList.contains("tasks-list-item")){
         index.parentElement.parentElement.nextElementSibling.children[0].children[0].checked=false;
		}else{
         index.parentElement.parentElement.previousElementSibling.children[0].children[0].checked=false;
		}
	})
})

function submitDriverLicense(){
	let form=getID("driverLicenseCreateForm");
	form.addEventListener("submit",function(e){
	  e.preventDefault();
	  e.stopImmediatePropagation();
	let submitBtn=getID("submitBtn");
	submitBtn.disabled = true;
	  	let vi=[],lab=[],srtby=[],ic=[],cbi=[],ct1=[],cl1=[],ct2=[],cl2=[],cb_label=[],cb_lvalue=[],cb_chbx_yes=[],cb_chbx_no=[],cb_show=[];
	queryAll(".cb_labelname").forEach((cblab)=>{
		 cb_label.push(cblab.value);
	})
	queryAll(".cb_textname").forEach((cblval)=>{
		 cb_lvalue.push(cblval.value);
	})	
	queryAll(".cb_yes").forEach((cby)=>{
		let chb_yes=(cby.checked) ? 1 : 0;
		 cb_chbx_yes.push(chb_yes);
	})	
	queryAll(".cb_no").forEach((cbn)=>{
		let chb_no=(cbn.checked) ? 1 : 0;
		 cb_chbx_no.push(chb_no);
	})
	queryAll(".cb_showall").forEach((view)=>{
		 cb_show.push(view.value);
	})
	queryAll(".view").forEach((view)=>{
		 vi.push(view.value);
	})
	queryAll(".label").forEach((label)=>{
		 lab.push(label.value);
	})
	queryAll(".sort_by").forEach((sortby)=>{
		 srtby.push(sortby.value);
	})
	queryAll(".icon").forEach((icon)=>{
		 ic.push(icon.value);
	})
	queryAll(".commentText1").forEach((commentText1)=>{
		 ct1.push(commentText1.value);
	})
	queryAll(".commentLabel1").forEach((commentLabel1)=>{
		 cl1.push(commentLabel1.value);
	})
	queryAll(".commentText2").forEach((commentText2)=>{
		 ct2.push(commentText2.value);
	})
	queryAll(".commentLabel2").forEach((commentLabel2)=>{
		 cl2.push(commentLabel2.value);
	})
	queryAll(".iconCheckbox").forEach((iconCheckbox)=>{
		let checkbox=(iconCheckbox.checked) ? 1 : 0;
		 cbi.push(checkbox);
	})
		let formData=new FormData(this);
		formData.append("driver_license",1);
		formData.append("vi",JSON.stringify(vi));
		formData.append("lab",JSON.stringify(lab));
		formData.append("sort_by",JSON.stringify(srtby));
		formData.append("ic",JSON.stringify(ic));
		formData.append("cbi",JSON.stringify(cbi));
		formData.append("ct1",JSON.stringify(ct1));
		formData.append("cl1",JSON.stringify(cl1));
		formData.append("ct2",JSON.stringify(ct2));
		formData.append("cl2",JSON.stringify(cl2));
		formData.append("cb_label",JSON.stringify(cb_label));
		formData.append("cb_lvalue",JSON.stringify(cb_lvalue));
		formData.append("cb_chbx_yes",JSON.stringify(cb_chbx_yes));
		formData.append("cb_chbx_no",JSON.stringify(cb_chbx_no));
		formData.append("cb_show",JSON.stringify(cb_show));
		fetch("../../ajax/ajax_employee.php",{
			method:"POST",
			body:formData
		}).then(res=>res.json())
		.then((data)=>{
			if(data.status == 1){
				myToast(data.status,data.message);
				setTimeout(function(){ window.location.href='edit_driver_license.php?dlid='+data.dlid+'&eid='+data.eid },2000);
			}else{
				submitBtn.disabled = false;
				myToast(data.status,data.message);
			}
		})
	})
}
function submitEditDriverLicense(){
	let form=getID("driverLicenseEditForm");
	form.addEventListener("submit",function(e){
	  e.preventDefault();
	  e.stopImmediatePropagation();
	  	let vi=[],lab=[],srtby=[],ic=[],cbi=[],ct1=[],cl1=[],ct2=[],cl2=[],cb_label=[],cb_lvalue=[],cb_chbx_yes=[],cb_chbx_no=[],cb_show=[];;
	queryAll(".cb_labelname").forEach((cblab)=>{
		 cb_label.push(cblab.value);
	})
	queryAll(".cb_textname").forEach((cblval)=>{
		 cb_lvalue.push(cblval.value);
	})	
	queryAll(".cb_yes").forEach((cby)=>{
		let chb_yes=(cby.checked) ? 1 : 0;
		 cb_chbx_yes.push(chb_yes);
	})	
	queryAll(".cb_no").forEach((cbn)=>{
		let chb_no=(cbn.checked) ? 1 : 0;
		 cb_chbx_no.push(chb_no);
	})
	queryAll(".cb_showall").forEach((view)=>{
		 cb_show.push(view.value);
	})
	queryAll(".view").forEach((view)=>{
		 vi.push(view.value);
	})
	queryAll(".label").forEach((label)=>{
		 lab.push(label.value);
	})
	queryAll(".sort_by").forEach((sortby)=>{
		 srtby.push(sortby.value);
	})
	queryAll(".icon").forEach((icon)=>{
		 ic.push(icon.value);
	})
	queryAll(".commentText1").forEach((commentText1)=>{
		 ct1.push(commentText1.value);
	})
	queryAll(".commentLabel1").forEach((commentLabel1)=>{
		 cl1.push(commentLabel1.value);
	})
	queryAll(".commentText2").forEach((commentText2)=>{
		 ct2.push(commentText2.value);
	})
	queryAll(".commentLabel2").forEach((commentLabel2)=>{
		 cl2.push(commentLabel2.value);
	})
	queryAll(".iconCheckbox").forEach((iconCheckbox)=>{
		let checkbox=(iconCheckbox.checked) ? 1 : 0;
		 cbi.push(checkbox);
	})
	let formData=new FormData(this);
		formData.append("driver_license",1);
		formData.append("vi",JSON.stringify(vi));
		formData.append("lab",JSON.stringify(lab));
		formData.append("sort_by",JSON.stringify(srtby));
		formData.append("ic",JSON.stringify(ic));
		formData.append("cbi",JSON.stringify(cbi));
		formData.append("ct1",JSON.stringify(ct1));
		formData.append("cl1",JSON.stringify(cl1));
		formData.append("ct2",JSON.stringify(ct2));
		formData.append("cl2",JSON.stringify(cl2));
		formData.append("cb_label",JSON.stringify(cb_label));
		formData.append("cb_lvalue",JSON.stringify(cb_lvalue));
		formData.append("cb_chbx_yes",JSON.stringify(cb_chbx_yes));
		formData.append("cb_chbx_no",JSON.stringify(cb_chbx_no));
		formData.append("cb_show",JSON.stringify(cb_show));
		fetch("../../ajax/ajax_employee.php",{
			method:"POST",
			body:formData
		}).then(res=>res.json())
		.then((data)=>{
			if(data.status == 1){
				myToast(data.status,data.message);
				setTimeout(function(){ location.reload()},2000);
			}else{
				myToast(data.status,data.message);
			}
		})
	})
}
function signatureVehicleUser(){
	let wrapper = getID("signature-pad-vh_user");
	let clearButton = getID("clear_btn_vh_user");
	let btnClick=getID("save_btn_vh_user");
	let canvas = wrapper.querySelector("#the_canvas_vh_user");
	let el_note = getID("note_vh_mngr");
	let signaturePad;
	signaturePad = new SignaturePad(canvas);
	clearButton.addEventListener("click", function (event) {
	   getID("note_vh_mngr").innerHTML="Die Unterschrift sollte innerhalb der Box sein";
	   signaturePad.clear();
	});
	btnClick.addEventListener("click", function (event){
		event.preventDefault();
	   if(signaturePad.isEmpty()){
	     myToast(0,"Please provide signature first.");
	   }else{
	     let canvas  = getID("the_canvas_vh_user");
	     let dataUrl = canvas.toDataURL();
	     getID("signature_vh_user").value = dataUrl;
	     myToast(1,"Successfully Uploaded Signature..");
	   }
	});
}
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

function editPrint(){
	let editBtn=getID("editPrint");
	let saveBtn=getID("savePrint");
	let text=getID("dl_text");
	let para=getID("dl_para");
	let desc=getID("dl_desc");
	let etext=getID("dl_edit_text");
	let epara=getID("dl_edit_para");
	let edesc=getID("dl_edit_desc");

  // btn
	editBtn.style.display='none';
	saveBtn.style.display='block';

	//text
	text.style.display="none";
	etext.value=text.innerText;
	etext.setAttribute("type","text");
   
   //para
   para.style.display="none";
	epara.value=para.innerText;
	epara.setAttribute("type","text");

	//desc
	desc.style.display="none";
	edesc.value=desc.innerText
	edesc.style.display="block";
}
function savePrint(){
	let editBtn=getID("editPrint");
	let saveBtn=getID("savePrint");
	let text=getID("dl_text");
	let para=getID("dl_para");
	let desc=getID("dl_desc");
	let etext=getID("dl_edit_text");
	let epara=getID("dl_edit_para");
	let edesc=getID("dl_edit_desc");

  // btn
	editBtn.style.display='block';
	saveBtn.style.display='none';

	//text
	etext.setAttribute("type","hidden");
	text.innerText=etext.value;
	text.style.display="block";

   //para
   epara.setAttribute("type","hidden");
   para.innerText=epara.value;
   para.style.display="block";

	//desc
	edesc.style.display="none";
	desc.innerText=edesc.value;
	desc.style.display="block";
   let formdata=new FormData();
   let dlid=getID('dlid').value;
   formdata.append('head',etext.value);
   formdata.append('para',epara.value);
   formdata.append('desc',edesc.value);
   formdata.append('dlid',dlid);
   formdata.append('saveDLprint',1)
	fetch('../../ajax/ajax_employee.php',{
		method:"POST",
		body:formdata,
	}).then(res=>res.json())
	.then((data)=>{
		if(data.status == 1){
				myToast(data.status,data.message);
			}else{
            myToast(data.status,data.message);
			}
	})

}