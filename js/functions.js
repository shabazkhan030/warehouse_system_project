 //=========MESSAGE TOAST========== 
function myToast(status,message){
  var x = document.getElementById("toast");
    if(status == 1){
       x.style.backgroundColor='green';
       x.innerHTML=message;
    }else{
      x.style.backgroundColor='red';
      x.innerHTML=message;
    }
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
}
function getID(element){
  return document.getElementById(element);
}
function queryAll(element){
  return document.querySelectorAll(element);
}
function queryOne(element){
  return document.querySelector(element);
}
function getAttr(element,attrName){
  return element.getAttribute(attrName);
}
function allowedNumberDecimal(el,evnt){
    var charC = (evnt.which) ? evnt.which : evnt.keyCode;
    if (charC == 46) {
        if (el.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (charC > 31 && (charC < 48 || charC > 57))
            return false;
    }
    return true;
}
function onlyNumberKey(evt) {
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
}
function confirmModalTransportVL(el){
  var modal =getID("confirmModalTransportVL");
  modal.style.display = "block";
  let transport_id=el.parentElement.children[0].value;
  var span = document.getElementsByClassName("closeModal")[0];
  getID("transportID").value=transport_id;

  span.onclick = function() {
    modal.style.display = "none";
  }
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
}
function confirmModalSubtext(el){
  var modal =getID("confirmModalSubtext");
  modal.style.display = "block";
  var span = document.getElementsByClassName("closeModal")[0];
  var id=getAttr(el,"data-delete");
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
function confirmDeleteSubtext(){
  let id=getID("dataDelete").value;
  var modal =getID("confirmModalSubtext");
   modal.style.display = "none";
  deletesubtext(id);
}
function confirmModalComment(el){
  var modal =getID("confirmModalComment");
  modal.style.display = "block";
  var span = document.getElementsByClassName("closeModal")[1];
  var id=getAttr(el,"data-delete");
  getID("dataDelete").value=id;

  span.onclick = function() {
    modal.style.display = "none";
  }
  window.onclick = function(event){
    if(event.target == modal){
      modal.style.display = "none";
    }
  }
}
function confirmDeleteComment(){
  let id=getID("dataDelete").value;
  var modal =getID("confirmModalComment");
   modal.style.display = "none";
  deletecomment(id);
}
function confirmModalCheckbox2(el){
  var modal =getID("confirmModalCheckbox2");
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
function confirmDeleteCheckbox2(){
  let id=getID("dataDelete").value;
  var modal =getID("confirmModalCheckbox2");
   modal.style.display = "none";
   deleteCheckbox2(id);
}
function confirmModalImage(el){
  var modal =getID("confirmModalImage");
  modal.style.display = "block";
  var span = document.getElementsByClassName("closeModal")[3];
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
function confirmDeleteImage(){
  let id=getID("dataDelete").value;
  var modal =getID("confirmModalImage");
   modal.style.display = "none";
   deleteVehicleImage(id);
}
function createTextFields(fieldname,fieldCheckbox){
  var textFields=getID('text-fields');
  var duplicateInputField=0;
  queryAll(".fieldnamevalue").forEach(function(element){
    if(fieldname == element.value){
        duplicateInputField=1;
    }
  });
  if(duplicateInputField == 0){
      data='<div class="d-flex">';
      data+='<div class="d-block mr-20 ml-20 mt-20">';
      data+='<label class="font-weight-bold">'+fieldname+'</label><br>';
      data+='<input type="text" class="textfield">';
      data+='<input type="hidden" class="fieldnamevalue" value="'+fieldname+'">';
      data+='<input type="hidden" class="checkboxfeild" value="'+fieldCheckbox+'">';
      data+='</div>';
      data+='</div>';
      textFields.innerHTML+=data;
  }else{
    status=0;
    message="Please don't try to insert duplicate rows..!!"
    myToast(status,message);
  }
}
function createTextFieldOnly(fieldname){
  var textFields=getID('text-fields');
  var duplicateInputField=0;
  queryAll(".fieldnamevalue").forEach(function(element){
    if(fieldname == element.value){
        duplicateInputField=1;
    }
  });
  if(duplicateInputField == 0){
      data='<div class="d-flex">';
      data+='<div class="d-block mr-20 ml-20 mt-20">';
      data+='<label class="font-weight-bold">'+fieldname+'</label><br>';
      data+='<input type="text" class="textfield w-160px">';
      data+='<input type="hidden" class="fieldnamevalue" value="'+fieldname+'">';
      data+='</div>';
      data+='</div>';
      textFields.innerHTML+=data;
  }else{
    status=0;
    message="Please don't try to insert duplicate rows..!!"
    myToast(status,message);
  }
}
function createTextmainOnly(fieldname){
  var textFields=getID('text-fields');
  var duplicateInputField=0;
  queryAll(".fieldnamevalue").forEach(function(element){
    if(fieldname == element.value){
        duplicateInputField=1;
    }
  });
  if(duplicateInputField == 0){
      data='<div class="d-flex">';
      data+='<div class="d-block ml-10 mt-20">';
      data+='<label>'+fieldname+'</label><br>';
      data+='<input type="text" class="textfield w-160px">';
      data+='<input type="hidden" class="fieldnamevalue" value="'+fieldname+'">';
      data+='</div>';
      data+='</div>';
      textFields.innerHTML+=data;
  }else{
    status=0;
    message="Please don't try to insert duplicate rows..!!"
    myToast(status,message);
  }
}
function createTextFieldWithVisibility(fieldname,viewAll){
  var textFields=getID('text-fields');
  var duplicateInputField=0;
  queryAll(".fieldnamevalue").forEach(function(element){
    if(fieldname == element.value){
        duplicateInputField=1;
    }
  });
  if(duplicateInputField == 0){
      data='<div class="d-flex mr-20">';
      data+='<div class="d-block">';
      data+='<label class="font-weight-bold">'+fieldname+'</label><br>';
      data+='<input type="text" class="textfield w-160px">';
      data+='<input type="hidden" class="fieldnamevalue" value="'+fieldname+'">';
      data+='<input type="hidden" class="viewAllvalue" value="'+viewAll+'">';
      data+='<input type="hidden" class="refrence_id_field" value="">';
      data+='</div>';
      data+='</div>';
      textFields.innerHTML+=data;
  }else{
    status=0;
    message="Please don't try to insert duplicate rows..!!"
    myToast(status,message);
  }
}
function fetchCompany(){
  var company=getID("selectcompany");
  const getCompanyName = new FormData();
  getCompanyName.append('getCompanyName',1);
  fetch('../ajax/ajax_action.php', {
    method: "POST",
    body:getCompanyName
  })
  .then((response)=>{
      return response.json(); 
  }) 
  .then((data)=>{
    var options='';
    if(data["empty"] == 'empty'){
       options=`<option value="0">Select Options</option>`;
       company.innerHTML=options;
    }else{
      for(var i in data){
        options+=`<option value="${data[i].id}">${data[i].cname}</option>`;
        company.innerHTML=options;
      }
    }
  }).catch(err => console.log(err))
}  
function LoadCompanyListData(){
  var companyList=getID("companyList");
  const getcompanyList = new FormData();
  getcompanyList.append('getcompanyList',1);
  fetch('../ajax/ajax_company.php', {
    method: "POST",
    body:getcompanyList
  })
  .then((response)=>{
      return response.json(); 
  }) 
  .then((data)=>{
    var tr='';
    if(data["empty"] == 'empty'){
       tr=`<tr><td colspan="12" class="text-center">Keine Eintr&auml;ge gefunden....</td></tr>`;
       companyList.innerHTML=tr;
    }else{
    for(var i in data){
       tr+=`<tr>
            <td>${data[i].id}</td>
            <td>${data[i].cname}</td>
            <td>${data[i].hrb}</td>
            <td>${data[i].tax_number}</td>
            <td>${data[i].email}</td>
            <td>${data[i].phone}</td>
            <td>${data[i].street}</td>
            <td>${data[i].house_no}</td>
            <td>${data[i].city}</td>
            <td>${data[i].zip_code}</td>
            <td>
               <a href="javascript:void(0)" cid=${data[i].cid} onclick="CompanyLogin(this)" class="btn-sm bg-lightblue">bearbeiten</a>
            </td>
      </tr>`;
      // href="edit_company.php?cid=${data[i].cid}"
      companyList.innerHTML=tr;
    };
  }
  })
  .catch(err => console.log(err));
}

function getAllCompany(){
  var company=getID("fetchCompany");
  const fetchCompany = new FormData();
  fetchCompany.append('fetchCompany',1);
  fetch('../ajax/ajax_company.php', {
    method: "POST",
    body:fetchCompany
  })
  .then((response)=>{
      return response.json(); 
  }) 
  .then((data)=>{
    var html='';
    if(data["empty"] == 'empty'){
       html=`<h2 class="text-center mt-50">No Company Found...</h2>`;
       company.innerHTML=html;
    }else{
    for(var i in data){

        html+=`<div class="card text-center">
               <img src="../img/company_uploads/${data[i].logo}" style="margin-top:20px;width:200px;height:200px;">
                  <div class="card-body">
                      <h4 class="mb-20"><b>${data[i].cname}</b></h4> 
                      <a href="javascript:void(0)" cid=${data[i].cid} onclick="CompanyLogin(this)" class="btn bg-red">Login</a> 
                  </div>
               </div>`;
      company.innerHTML=html;
    };
  }
  })
  .catch(err => console.log(err));
}
function CompanyLogin(element){
  let cid=getAttr(element,'cid');
  const companyLogin = new FormData();
  companyLogin.append('companyLogin',1);
  companyLogin.append('cid',cid);
  fetch('../ajax/ajax_company.php', {
    method: "POST",
    body:companyLogin
  }).then((response)=>{
      return response.json(); 
  }).then((data)=>{
    if(data.status == 1){
      location.href="employee/employee.php";
    }else{
      myToast(data.status,data.message);
    }
  }) 
}

function getLastID(element){
  const getLastId = new FormData();
  getLastId.append('getLastID',1);
  getLastId.append('tablename',element);
  fetch('../../ajax/ajax_action.php', {
    method: "POST",
    body:getLastId
  }).then((response)=>{
      return response.json(); 
  }).then((data)=>{
      getID("eid").value=data.lastInsertedID;
  }) 
}

function LoadEmployeeListData(pageno,limit){
  var empList=getID("employeeList");
  var cid=getID("cid").value;
  getID("pageinfo").value=pageno;
  // var limit=getID("showTotal").value;
  const getEmployeeList = new FormData();
  getEmployeeList.append('getEmployeeList',1);
  getEmployeeList.append('cid',cid);
  getEmployeeList.append('pageno',pageno);
  getEmployeeList.append('limit',limit);
  fetch('../../ajax/ajax_employee.php', {
    method: "POST",
    body:getEmployeeList
  })
  .then((response)=>{
      return response.json(); 
  }) 
  .then((data)=>{
    var tr='';
    empList.innerHTML='';
    if(data["empty"] == 'empty'){
       tr=`<tr><td colspan="12" class="text-center">Keine Eintr&auml;ge gefunden....</td></tr>`;
       empList.innerHTML=tr;
    }else{
      let emptyBox=[];
        let table=getID("table-employee");
            for(var i=1 in data){
           tr=`<tr>
                <td>${data[i].id}</td>
                <td>${data[i].fname}</td>
                <td>${data[i].sname}</td>
                <td>${data[i].eqp}</td>
                <td>${data[i].tc}</td>
                <td>${data[i].ufc}</td>
                <td>${data[i].vl}</td>
                <td>${data[i].dl}</td>
                <td class="text-min-10">${data[i].created_updated_by}</td>
                <td>${data[i].status}</td>
                <td>
                   <a href="edit_employee_data.php?eid=${data[i].eid}" class="btn-sm bg-lightblue">bearbeiten</a>
                </td>
          </tr>`;   
          empList.innerHTML+=tr;
        }  
        // pagination();
    }
  })
  .catch(err => console.log(err));
}

function loadEmpHistoryData(){
  const empTable=getID("employeeHistoryTable");
  const eid=getID("eid").value;
  const empTableList = new FormData();
  empTableList.append('empTableList',1);
  empTableList.append('eid',eid);
  fetch('../../ajax/ajax_employee.php', {
    method: "POST",
    body:empTableList
  })
  .then((response)=>{
      return response.json(); 
  }) 
  .then((data)=>{
    var tr='';
    if(data["empty"] == 'empty'){
       tr=`<tr><td colspan="12" class="text-center">Keine Eintr&auml;ge gefunden....</td></tr>`;
       empTable.innerHTML=tr;
    }else{
    for(var i in data){
       tr+=`<tr>
            <td class="font-weight-bold">${data[i].position}</td>
            <td class="font-weight-bold text-red">${data[i].before}</td>
            <td class="font-weight-bold text-green">${data[i].after}</td>
            <td>${data[i].update_on}</td>
            <td class="font-weight-bold">${data[i].firstname} ${data[i].surname}</td>
      </tr>`;
      empTable.innerHTML=tr;
    };
  }
  })
  .catch(err => console.log(err));
}

function loadCompanyHistoryTable(){
  const companyHistoryTable=getID("companyHistoryTable");
  const cid=getID("cid").value;
  const companyHistory = new FormData();
  companyHistory.append('companyHistoryTable',1);
  companyHistory.append('cid',cid);
  fetch('../ajax/ajax_company.php', {
    method: "POST",
    body:companyHistory
  })
  .then((response)=>{
      return response.json(); 
  }) 
  .then((data)=>{
    var tr='';
    if(data["empty"] == 'empty'){
       tr=`<tr><td colspan="12" class="text-center">Keine Eintr&auml;ge gefunden....</td></tr>`;
       companyHistoryTable.innerHTML=tr;
    }else{
    for(var i in data){
       tr+=`<tr>
            <td class="font-weight-bold">${data[i].position}</td>
            <td class="font-weight-bold text-red">${data[i].before}</td>
            <td class="font-weight-bold text-green">${data[i].after}</td>
            <td>${data[i].update_on}</td>
            <td class="font-weight-bold">${data[i].firstname} ${data[i].surname}</td>
      </tr>`;
      companyHistoryTable.innerHTML=tr;
    };
  }
  })
  .catch(err => console.log(err));
}
function loadPAHistoryTable(){
  const paHistoryTable=getID("PAHistoryTable");
  const aid=getID("aid").value;
  const adminHistory = new FormData();
  adminHistory.append('paHistoryTable',1);
  adminHistory.append('aid',aid);
  fetch('../ajax/ajax_personal_admin.php', {
    method: "POST",
    body:adminHistory
  })
  .then((response)=>{
      return response.json(); 
  }) 
  .then((data)=>{
    var tr='';
    if(data["empty"] == 'empty'){
       tr=`<tr><td colspan="12" class="text-center">Keine Eintr&auml;ge gefunden....</td></tr>`;
       paHistoryTable.innerHTML=tr;
    }else{
    for(var i in data){
       tr+=`<tr>
            <td class="font-weight-bold">${data[i].position}</td>
            <td class="font-weight-bold text-red">${data[i].before}</td>
            <td class="font-weight-bold text-green">${data[i].after}</td>
            <td>${data[i].update_on}</td>
            <td class="font-weight-bold">${data[i].firstname} ${data[i].surname}</td>
      </tr>`;
      paHistoryTable.innerHTML=tr;
    };
  }
  })
  .catch(err => console.log(err));
}
// UTA FUEL CARD && TANK CHIP SIGNATURE FUNCTION
function employeeSign(){
   queryAll(".note_employee_handover").forEach((row,index)=>{
    document.getElementsByClassName("note_employee_handover")[index].innerHTML="";
   })
}
function staffSign(){
   queryAll(".note_staff_handover").forEach((row,index)=>{
   document.getElementsByClassName("note_staff_handover")[index].innerHTML="";
   })
 }

 function signaturestaff(){
  let signaturePad;
  let canvas=queryAll(".the_canvas_staff_handover").forEach((row,index)=>{
    canvasrow=document.getElementsByClassName("the_canvas_staff_handover")[index]
    signaturePad = new SignaturePad(canvasrow);
  })
  let SaveBtn=queryAll(".save_btn_staff_handover").forEach((row,index)=>{
  row.addEventListener("click",function(event){
      let rowIndex=row.closest("tr").rowIndex - 1;
    let saveRow = document.getElementsByClassName("the_canvas_staff_handover")[index];
      let dataUrl = saveRow.toDataURL();
      document.getElementsByClassName("signature_staff_handover")[rowIndex].value=dataUrl;
      myToast(1,"Sussuessfully inserted Staff Signature"); 
  })
  })
  let clearButton=queryAll(".clear_btn_staff_handover").forEach((row,index)=>{
    row.addEventListener("click",function(event){
    event.preventDefault();
     document.getElementsByClassName("note_staff_handover")[index].innerHTML="Die Unterschrift sollte innerhalb der Box sein";
     // signaturePad.clear();
     // );
     signatureClear(row,canvasrow);
     document.getElementsByClassName("signature_staff_handover")[index].value='';
  })
  })

}
function signatureEmployee(){
let signaturePad;
  let canvas=queryAll(".the_canvas_employee_handover").forEach((row,index)=>{
    canvasrow=document.getElementsByClassName("the_canvas_employee_handover")[index]
    signaturePad = new SignaturePad(canvasrow);
  })
  let SaveBtn=queryAll(".save_btn_employee_handover").forEach((row,index)=>{
  row.addEventListener("click",function(event){
  let rowIndex=row.closest("tr").rowIndex - 1;
  saveRow = document.getElementsByClassName("the_canvas_employee_handover")[index];
  let dataUrl = saveRow.toDataURL();
  document.getElementsByClassName("signature_employee_handover")[rowIndex].value=dataUrl;
  myToast(1,"Sussuessfully inserted Employee Signature");
  })
  })
  let clearButton=queryAll(".clear_btn_employee_handover").forEach((row,index)=>{
    row.addEventListener("click",function(event){
    event.preventDefault();
     document.getElementsByClassName("note_employee_handover")[index].innerHTML="Die Unterschrift sollte innerhalb der Box sein";
     signatureClear(row,canvasrow)
     document.getElementsByClassName("signature_employee_handover")[index].value='';
     
  })
   })
}

function employeeSignReturn(){
   queryAll(".note_employee_return").forEach((row,index)=>{
    document.getElementsByClassName("note_employee_return")[index].innerHTML="";
   })
}
function staffSignReturn(){
   queryAll(".note_staff_return").forEach((row,index)=>{
   document.getElementsByClassName("note_staff_return")[index].innerHTML="";
   })
 }
function signatureEmployeeReturn(){
let signaturePad;
  let canvas=queryAll(".the_canvas_employee_return").forEach((row,index)=>{
    canvasrow=document.getElementsByClassName("the_canvas_employee_return")[index]
    signaturePad = new SignaturePad(canvasrow);
  })
  let SaveBtn=queryAll(".save_btn_employee_return").forEach((row,index)=>{
  row.addEventListener("click",function(event){
  let rowIndex=row.closest("tr").rowIndex - 1;
  saveRow = document.getElementsByClassName("the_canvas_employee_return")[index];
  let dataUrl = saveRow.toDataURL();
  document.getElementsByClassName("signature_employee_return")[rowIndex].value=dataUrl;
  myToast(1,"Sussuessfully inserted Employee Signature");
  })
  })
  let clearButton=queryAll(".clear_btn_employee_return").forEach((row,index)=>{
    row.addEventListener("click",function(event){
    event.preventDefault();
     document.getElementsByClassName("note_employee_return")[index].innerHTML="Die Unterschrift sollte innerhalb der Box sein";
     signatureClear(row,canvasrow)
     document.getElementsByClassName("signature_employee_return")[index].value='';
     
  })
   })
}
function signaturestaffReturn(){
  let signaturePad;
  let canvas=queryAll(".the_canvas_staff_return").forEach((row,index)=>{
    canvasrow=document.getElementsByClassName("the_canvas_staff_return")[index]
    signaturePad = new SignaturePad(canvasrow);
  })
  let SaveBtn=queryAll(".save_btn_staff_return").forEach((row,index)=>{
  row.addEventListener("click",function(event){
      let rowIndex=row.closest("tr").rowIndex - 1;
    let saveRow = document.getElementsByClassName("the_canvas_staff_return")[index];
      let dataUrl = saveRow.toDataURL();
      document.getElementsByClassName("signature_staff_return")[rowIndex].value=dataUrl;
      myToast(1,"Sussuessfully inserted Staff Signature");
     
  })
  })
  let clearButton=queryAll(".clear_btn_staff_return").forEach((row,index)=>{
    row.addEventListener("click",function(event){
    event.preventDefault();
     document.getElementsByClassName("note_staff_return")[index].innerHTML="Die Unterschrift sollte innerhalb der Box sein";
     signatureClear(row,canvasrow);
     document.getElementsByClassName("signature_staff_return")[index].value='';
  })
  })

}

function signatureClear(row,canvasrow){
     let ctx=row.parentElement.previousElementSibling.children[1].getContext('2d');
     ctx.fillStyle ="#ffffff"
     ctx.clearRect(0, 0, canvasrow.width, canvasrow.height);
     ctx.fillRect(0, 0, canvasrow.width, canvasrow.height);
}



