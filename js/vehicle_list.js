function addMoreRow(){
	let tbodyvehicleList=getID("vehicleListTableCheckbox");
	let view=(getID("viewtoall").checked) ? 1 : 0;
	const regex = /[^A-Za-z0-9_]/g;
	let labelName=getID("labelname").value.replace(regex, "").trim();
	let data='';
		if(labelName != ''){
		data+=`<tr>
					<td style="padding:0px;">${labelName}</td>
					<input type="hidden" name="labelname[]" value="${labelName}">
					<td style="padding:0px;">							
						<label class="tasks-list-item">
				           <input type="radio" name="${labelName}" value="1" class="tasks-list-cb">
				           <span class="tasks-list-mark"></span>
				    </label>
				    	
				  </td>
					<td style="padding:0px;">
							<label class="tasks-list-item">
					           <input type="radio" name="${labelName}" value="0" checked class="tasks-list-cb">
					           <span class="tasks-list-mark"></span>
					    </label>
				  </td>
				  <td style="padding:0px;">
				 	 <div class="d-flex">	
				 	      <a href="javascript:void(0)" class="d-inline-block bg-green text-light p-5 text-decoration">Bearbeiten</a>
				 	      <a href="javascript:void(0)" class="d-inline-block bg-green text-light bg-blue p-5 text-decoration ml-10">Speichern</a>
				 	 </div>
				 </td>
				 <td style="padding:0px;">
				 	  <select name="showAll[]" style="width:80px;padding:3px;">
				 	      ${(view == 1) ? '<option value="1" selected>Active</option><option value="0">Disabled</option>' : '<option value="1">Active</option><option value="0" selected>Disabled</option>'}
				 	 </select>
				 </td>	
			</tr>`;
			tbodyvehicleList.insertRow().innerHTML=	data;
			getID("labelname").value='';
		}else{
		   myToast(0,"please Enter label Name..");
		}   
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

		 commentsSection.innerHTML+=data;       
	}else{
		myToast(0,"please Insert comment Label name..");
	}
	getID("comLabel").value='';
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
	formdata.append("removeComment",1)
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
function removeSubtext(el){
	let id=getAttr(el,"data-delete");
	let formdata=new FormData();
	formdata.append("removeSubtext",1)
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
 function submitVehicleListMain(){

 const mainFormVL=getID("vehicleListMainForm");
 	mainFormVL.addEventListener('submit', function(e) {
	  e.preventDefault();
	  e.stopImmediatePropagation();
	   let subtext=[],subtextLabel=[],mainTextvalue=[],mainTextLabel=[],mainTextCheckbox=[],refrencefield=[],commentText=[],commentLabel=[];
	   var stext=queryAll(".text1");
	   var stextLabel=queryAll(".subtextlabel");
	    stext.forEach(function(field,index){
	     subtext.push(stext[index].value);
	     console.log(stext[index].value);
	    });
	    stextLabel.forEach(function(field,index){
	     subtextLabel.push(stextLabel[index].value);
	     console.log(stextLabel[index].value);
	    });
	   var textfieldvalue=queryAll(".textfield");
	    textfieldvalue.forEach(function(field,index){
	     mainTextvalue.push(textfieldvalue[index].value);
	    }); 
	   var textfieldlabel=queryAll(".fieldnamevalue");
	    textfieldlabel.forEach(function(field,index){
	     mainTextLabel.push(textfieldlabel[index].value);
	    });
	    var refrenceIdField=queryAll(".refrence_id_field");
	    refrenceIdField.forEach(function(field,index){
	     refrencefield.push(refrenceIdField[index].value);
	    });
	    var checkboxMain=queryAll(".viewAllvalue");
	    checkboxMain.forEach(function(field,index){
	    mainTextCheckbox.push(checkboxMain[index].value);
	    });
	    var comText=queryAll('.commentText');
	    comText.forEach(function(field,index){
	     commentText.push(comText[index].value);
	    });
	    var comLabel=queryAll('.commentLabel');
	    comLabel.forEach(function(field,index){
	     commentLabel.push(comLabel[index].value);
	    });
	   const formData = new FormData(this);
	   formData.append("subtext",JSON.stringify(subtext));
	   formData.append("stextLabel",JSON.stringify(subtextLabel));
	   formData.append("mainTextvalue",JSON.stringify(mainTextvalue));
	   formData.append("mainTextLabel",JSON.stringify(mainTextLabel));
	   formData.append("mainTextCheckbox",JSON.stringify(mainTextCheckbox));
	   formData.append("refrencefield",JSON.stringify(refrencefield));
	   formData.append("commentText",JSON.stringify(commentText));
	   formData.append("commentLabel",JSON.stringify(commentLabel));
	   formData.append('VLMainForm',1);
	  fetch('../ajax/ajax_vehicle_list.php', {
	    method: 'POST',
	    body: formData,
	  })
	  .then(res => res.json())
	  .then((data) => {
	    if(data.status == 1){
	       myToast(data.status,data.message);
	       getID('vehicleListMainForm').reset();
	       setTimeout(function(){ window.location.href="edit_vehicle_list.php?vid="+data.vid; }, 3000);
	    }else{
	      myToast(data.status,data.message);
	    }
	  });
    });
 }

 function checkForMainTextfield(){
 	let textfield=getID("text-fields");
 	let vid=getID("vid").value;
 	let formData = new FormData();
 	formData.append("getMaintextBox",1);
 	formData.append("vid",vid);
 	fetch('../ajax/ajax_vehicle_list.php', {
	    method: 'POST',
	    body: formData,
	  })
	  .then(res => res.json())
	  .then((data) => {
	  	if(data["empty"] == 'empty'){
	    }else{
	    	  	for(var i in data){
		      html='<div class="d-flex">';
		      html+='<div class="d-block ml-20">';
		      html+='<label class="font-weight-bold">'+data[i].textname+'</label><br>';
		      html+='<input type="text" class="textfield w-160px" value="'+data[i].textval+'">';
		      html+='<input type="hidden" class="fieldnamevalue" value="'+data[i].textname+'">';
		      html+='<input type="hidden" class="viewAllvalue" value="'+data[i].visible+'">';
		      html+='<input type="hidden" class="refrence_id_field" value="'+data[i].reference_id_field+'">'; 
		      html+='</div>';
		      html+='</div>';

		      textfield.innerHTML+=html;
		  	}	
	    }
	  });
 }
function getCarImage(){
	let carimage=getID("carimage");
	let vid=getID("vid").value;
	let formData = new FormData();
	formData.append("getVehicleImage",1);
 	formData.append("vid",vid);
 	 	fetch('../ajax/ajax_vehicle_list.php', {
	    method: 'POST',
	    body: formData,
	  })
	  .then(res => res.json())
	  .then((data) => {
	  	if(data["empty"] == 'empty'){
	    }else{
	  		for(var i in data){
	            let html=`<div class="d-flex">
	                         <img src='../img/vehicle_uploads/${data[i].img}' class="ml-20" style="width:350px">
	                         <a href="javascript:void(0)" data-delete='${data[i].id}' class="cancel-btn text-decoration deletevehicleImage mt-50 ml-20" aria-label="Close"></a>
	                      <div>`;
		      carimage.innerHTML+=html;
		  	}	
	  	}
	  	deleteCarImage();

	  });
}
function editCheckbox(chbx){
	chbx.parentElement.parentElement.parentElement.children[0].children[0].style.display="none";
	chbx.parentElement.parentElement.parentElement.children[0].children[1].setAttribute("type","text");
}

function saveCheckbox(chbx){
	let id=chbx.getAttribute("data-save");
	let chbxValue=chbx.parentElement.parentElement.parentElement.children[0].children[1].value;
	let formdata=new FormData();
	formdata.append("saveChecbox",1);
	formdata.append("id",id);
	formdata.append("chbxValue",chbxValue); 

	fetch("../ajax/ajax_vehicle_list.php",{
		method:"POST",
		body:formdata
	}).then(res=>res.json())
	.then((data)=>{
		if(data.status == 1){
			myToast(data.status,data.message);
			setTimeout(function(){ location.reload(); }, 3000);
		}else{
			myToast(data.status,data.message);
		}
	})
}


function saveVehicleEdit(){
 const mainFormEditVL=getID("EditvehicleListMainForm");
 console.log("hello");
 	mainFormEditVL.addEventListener('submit',function(e) {
	   e.preventDefault();
	   let savebtn=getID("savebtn");
	   savebtn.setAttribute("disabled",true);
	   let subtext=[],subtextLabel=[],mainTextvalue=[],mainTextLabel=[],mainTextCheckbox=[],refrencefield=[],commentText=[],commentLabel=[];
	   var stext=queryAll(".text1");
	   var stextLabel=queryAll(".subtextlabel");
	    stext.forEach(function(field,index){
	     subtext.push(stext[index].value);
	     console.log(stext[index].value);
	    });
	    stextLabel.forEach(function(field,index){
	     subtextLabel.push(stextLabel[index].value);
	     console.log(stextLabel[index].value);
	    });
	   var textfieldvalue=queryAll(".textfield");
	    textfieldvalue.forEach(function(field,index){
	     mainTextvalue.push(textfieldvalue[index].value);
	    }); 
	   var textfieldlabel=queryAll(".fieldnamevalue");
	    textfieldlabel.forEach(function(field,index){
	     mainTextLabel.push(textfieldlabel[index].value);
	    });
	    var refrenceIdField=queryAll(".refrence_id_field");
	    refrenceIdField.forEach(function(field,index){
	     refrencefield.push(refrenceIdField[index].value);
	    });
	    var checkboxMain=queryAll(".viewAllvalue");
	    checkboxMain.forEach(function(field,index){
	    mainTextCheckbox.push(checkboxMain[index].value);
	    });
	    var comText=queryAll('.commentText');
	    comText.forEach(function(field,index){
	     commentText.push(comText[index].value);
	    });
	    var comLabel=queryAll('.commentLabel');
	    comLabel.forEach(function(field,index){
	     commentLabel.push(comLabel[index].value);
	    });
	   const formData = new FormData(this);
	   formData.append("subtext",JSON.stringify(subtext));
	   formData.append("stextLabel",JSON.stringify(subtextLabel));
	   formData.append("mainTextvalue",JSON.stringify(mainTextvalue));
	   formData.append("mainTextLabel",JSON.stringify(mainTextLabel));
	   formData.append("mainTextCheckbox",JSON.stringify(mainTextCheckbox));
	   formData.append("refrencefield",JSON.stringify(refrencefield));
	   formData.append("commentText",JSON.stringify(commentText));
	   formData.append("commentLabel",JSON.stringify(commentLabel));
	   formData.append('VLMainForm',1);
	  fetch('../ajax/ajax_vehicle_list.php', {
	    method: 'POST',
	    body: formData,
	  })
	  .then(res => res.json())
	  .then((data) => {
	  	console.log(data);
	    if(data.status == 1){
	       myToast(data.status,data.message);
	       getID('EditvehicleListMainForm').reset();
	       setTimeout(function(){ location.reload(); }, 3000);
	       savebtn.setAttribute("disabled",false);
	       loadVehicleHistoryTable()
	    }else{
	      myToast(data.status,data.message);
	    }
	  });
    });

}
function loadVehicleHistoryTable(){
  const vehicleHistoryTable=getID("vehicleHistoryTable");
  const cid=getID("cid").value;
  const vid=getID("vid").value;
  const vehicleHistory = new FormData();
  vehicleHistory.append('vehicleHistoryTable',1);
  vehicleHistory.append('cid',cid);
  vehicleHistory.append('vid',vid);
  fetch('../ajax/ajax_vehicle_list.php', {
    method: "POST",
    body:vehicleHistory
  })
  .then((response)=>{
      return response.json(); 
  }) 
  .then((data)=>{
    var tr='';
    if(data["empty"] == 'empty'){
       tr=`<tr><td colspan="12" class="text-center">Keine Eintr&auml;ge gefunden....</td></tr>`;
       vehicleHistoryTable.innerHTML=tr;
    }else{
    for(var i in data){
       tr+=`<tr>
            <td class="font-weight-bold">${data[i].position}</td>
            <td class="font-weight-bold text-red">${data[i].before}</td>
            <td class="font-weight-bold text-green">${data[i].after}</td>
            <td>${data[i].update_on}</td>
            <td class="font-weight-bold">${data[i].firstname} ${data[i].surname}</td>
      </tr>`;
      vehicleHistoryTable.innerHTML=tr;
    };
  }
  })
  .catch(err => console.log(err));
}