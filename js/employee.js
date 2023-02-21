function createEmployee(){
btnCreateEmployee.addEventListener('submit', function(e) {
e.preventDefault();
e.stopImmediatePropagation();
    var fieldname=[];
    var fieldvalue=[];
    var checkboxvalue=[];

    var textfieldval=queryAll(".textfield");
    textfieldval.forEach(function(field,index){
     fieldvalue.push(textfieldval[index].value);
    });
    var fieldnamevalue=queryAll(".fieldnamevalue");
      fieldnamevalue.forEach(function(fieldval,index){
     fieldname.push(fieldnamevalue[index].value);
    });
    var checkboxfeild=queryAll(".checkboxfeild");
     checkboxfeild.forEach(function(fieldval,index){
     checkboxvalue.push(checkboxfeild[index].value);
    });
const formData = new FormData(this);
var cid=getID("cid").value;
  formData.append('fieldname',JSON.stringify(fieldname));
  formData.append('fieldvalue',JSON.stringify(fieldvalue));
  formData.append('checkboxvalue',JSON.stringify(checkboxvalue));
  formData.append('cid',cid);
formData.append('createEmployeeData',1);
fetch('../../ajax/ajax_employee.php', {
method: 'POST',
body: formData,
})
.then(res => res.json())
.then((data) => {
if(data.status == 1){
myToast(data.status,data.message);
getID('createEmployeeForm').reset();
setTimeout(function(){ location.reload(); }, 3000);
}else{
myToast(data.status,data.message);
}
});
});
}
function EditEmployee(){
const btnEditEmployee = getID('editEmployeeData');
btnEditEmployee.addEventListener('submit', function(e) {
e.preventDefault();
e.stopImmediatePropagation();
    var fieldname=[];
    var fieldvalue=[];
    var checkboxvalue=[];

    var textfieldval=queryAll(".textfield");
    textfieldval.forEach(function(field,index){
     fieldvalue.push(textfieldval[index].value);
    });
    var fieldnamevalue=queryAll(".fieldnamevalue");
      fieldnamevalue.forEach(function(fieldval,index){
     fieldname.push(fieldnamevalue[index].value);
    });
    var checkboxfeild=queryAll(".checkboxfeild");
     checkboxfeild.forEach(function(fieldval,index){
     checkboxvalue.push(checkboxfeild[index].value);
    });
var eid=getID("eid").value;
var cid=getID("cid").value;
// const formData = new FormData(this);
  const formData = new FormData(this);
  formData.append('fieldname',JSON.stringify(fieldname));
  formData.append('fieldvalue',JSON.stringify(fieldvalue));
  formData.append('checkboxvalue',JSON.stringify(checkboxvalue));
formData.append('editEmployeeData',1);
formData.append('eid',eid);
formData.append('cid',cid);
fetch('../../ajax/ajax_employee.php', {
method: 'POST',
body: formData,
})
.then(res => res.json())
.then((data) => {
if(data.status == 1){
getID('editEmployeeData').reset();
loadEmpHistoryData();
myToast(data.status,data.message);

setTimeout(function(){ location.reload(); }, 3000);
}else{
myToast(data.status,data.message);
}
});
});
}

function SubmitWorkEquipmentissue(){
const forworkEquIssueForm=getID("workEquIssueForm");
forworkEquIssueForm.addEventListener('submit', function(e) {
e.preventDefault();
e.stopImmediatePropagation();
const formData = new FormData(this);
formData.append("workIssueSubmit",1);
fetch('../../ajax/ajax_employee.php', {
method: 'POST',
body: formData,
})
.then(res => res.json())
.then((data) => {
// console.log(data);
if(data.status == 1){
getID('workEquIssueForm').reset();
myToast(data.status,data.message);
setTimeout(function(){ location.reload(); }, 3000);
}else{
myToast(data.status,data.message);
}
});
})
}
function SubmitWorkEquipmentReturn(){
const forworkEquIssueForm=getID("workEqureturnForm");
forworkEquIssueForm.addEventListener('submit', function(e) {
e.preventDefault();
e.stopImmediatePropagation();
const formData = new FormData(this);
formData.append("workreturnSubmit",1);
fetch('../../ajax/ajax_employee.php', {
method: 'POST',
body: formData,
})
.then(res => res.json())
.then((data) => {
// console.log(data.message);
if(data.status == 1){
getID('workEqureturnForm').reset();
myToast(data.status,data.message);
setTimeout(function(){ location.reload(); }, 3000);
}else{
myToast(data.status,data.message);
}
});
})
}
// WORK EQUIPMENT ISSUE FUNCTION START
function populateCrowd(){
let issueItemChange=queryAll(".populatearticleQty").forEach((row,index)=>{
row.addEventListener("change",function(){
crowdOption(this.value,index);
})
})
}
function crowdOption(val,index){
let option='';
data=new FormData;
data.append("getPopulatedOption",1);
data.append("selectedArticle",val);
fetch("../../ajax/ajax_employee.php",{
method:"POST",
body:data
}).then(res=>res.json()).then((data)=>{
for(var i in data){
option+=data[i];
}
document.getElementsByClassName("selectArticleCrowd")[index].innerHTML=option; 
})
}

function populateCrowdReturn(){
let issueItemChange=queryAll(".populatearticleReturnQty").forEach((row,index)=>{
row.addEventListener("change",function(){
crowdOptionReturn(this.value,index);
})
})
}
function crowdOptionReturn(val,index){
// console.log(val);
let eid=getID("eid").value;
let option='';
data=new FormData;
data.append("getPopulatedReturnOption",1);
data.append("selectedreturnArticle",val);
data.append("eid",eid)
fetch("../../ajax/ajax_employee.php",{
method:"POST",
body:data
}).then(res=>res.json()).then((data)=>{
for(var i in data){
option+=data[i];
}
document.getElementsByClassName("selectArticleCrowdreturn")[index].innerHTML=option; 
})
}


function printEquipment(){
  cid=getID("cid").value;
  eid=getID("eid").value;
    formdata=new FormData();
    formdata.append("getPrintEquipment",1);
    formdata.append("eid",eid);
    formdata.append("cid",cid);
  fetch("../../ajax/ajax_employee.php",{
    method:"POST",
    body:formdata,
  }).then(res=>res.json())
  .then((data)=>{
   getID("empl_name").innerText=data[0].emp_firstname+' '+data[0].emp_surname;
   getID("empl_street").innerText=data[0].emp_street+' '+data[0].emp_house_no;
   getID("empl_zip").innerText=data[0].emp_zip_code;
   getID("empl_city").innerText=data[0].emp_city;
  
  
   getID("com_name").innerText=data[0].company_name;
   getID("com_city").innerText=data[0].company_city;
   getID("com_street").innerText=data[0].company_street;
   getID("company_house_no").innerText=data[0].company_house_no;
   getID("com_zip").innerText=data[0].company_zip_code;
   getID("headingText").innerHTML=data[0].print_heading;
   getID("paraHandover").innerHTML=data[0].print_comment;
     let workIssue='',workReturn='';
   for(var i in data[0].equipment_issue){
    workIssue+=`<div style="display:flex;margin-left:10px;">
      <div style="margin-top:10px">
        <label style="color:red;font-size:14px;" class="font-weight-bold">Arbeitsmittel Übergabe</label><br>
        <input type="text" style="width:160px;padding:2px;" value="${data[0].equipment_issue[i].article_name}"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label style="font-size:14px;" class="font-weight-bold">Menge</label><br>
        <input type="text" style="width:80px;padding:2px;" value="${data[0].equipment_issue[i].selected_crowd}"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Datum</label><br>
        <input type="text" style="width:160px;padding:2px;" value="${data[0].equipment_issue[i].data_issue}"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Unterschrift Sachbearbeiter</label><br>
            ${(data[0].equipment_issue[0].staff_signature == '') ? `<div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          
        </div>` : `<div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/${data[0].equipment_issue[i].staff_signature}"  style="width:100%;">
          
        </div><p>${data[0].equipment_issue[i].signed_by}</p>`}
        </div>
      <div  style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold" style="font-size:14px;">Unterschrift Mitarbeiter</label><br>
            ${(data[0].equipment_issue[i].employee_signature == '') ? `<div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign"></div>` : `<div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/${data[0].equipment_issue[i].employee_signature}"  style="width:100%;">
        </div>`}
        </div>
    </div>`;
   }
   getID("workIssue").innerHTML=workIssue;
   for(var i in data[0].equipment_return){
      workReturn+=`<div style="display:flex;margin-left:10px;margin-top:10px;font-size:12px;">
      <div style="margin-top:10px;">
        <label style="color:green;font-size:14px;" class="font-weight-bold">Arbeitsmittel Rückgabe</label><br>
                <input type="text" style="width:160px;padding:2px;" value="${data[0].equipment_return[i].article_name}"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold">Menge</label><br>
                <input type="text" style="width:80px;padding:2px;" value="${data[0].equipment_return[i].article_crowd}"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label style="color:green;padding:2px;" class="font-weight-bold">Verloren</label><br>
        <input type="text" style="width:80px;padding:2px;" value="${data[0].equipment_return[i].lost}"readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold">Datum</label><br>
                <input type="text" style="width:160px;padding:2px;" value="${data[0].equipment_return[i].data_return}" readonly>
      </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold">Unterschrift Sachbearbeiter</label><br>
            ${(data[0].equipment_return[i].staff_signature == '') ? `<div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign"></div>` : `<div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000" id="handoverEmplSign">
          <img src="../../img/signature_uploads/${data[0].equipment_return[i].staff_signature}"  style="width:100%;">
        </div><p>${data[0].equipment_return[i].signed_by}</p>`}
        </div>
      <div style="margin-top:10px;margin-left:10px;">
        <label class="font-weight-bold">Unterschrift Mitarbeiter</label><br>
            ${(data[0].equipment_return[i].employee_signature == '') ? `<div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000">
        </div>` : `<div style="width:120px;height:60px;padding-top:10px;border:1px solid #000000">
          <img src="../../img/signature_uploads/${data[0].equipment_return[i].employee_signature}" style="width:100%;"></div>`}
            </div>
    </div>`;
   }
  getID("workReturn").innerHTML=workReturn;
  })
}
function editEquipment(){
  let headingText=getID("headingText");
  const btnEdit=getID("btnEditEquipment");
  const btnSave=getID("btnSaveEquipment");
  let textinput= getID("textinput");
  let textareaInput=getID("textareaInput");
  let paraHandover=getID("paraHandover");
  
   btnEdit.style.display="none";
   btnSave.style.display="block";

   textinput.setAttribute("type","text");
   headingText.style.display="none"; 
   textinput.value=headingText.innerText;
   
   paraHandover.style.display="none";
   textareaInput.style.display="block";
   textareaInput.value=paraHandover.innerText;
   myToast(1,"Loaded print");
   
}

function saveEquipment(){
  let headingText=getID("headingText");
  const btnEdit=getID("btnEditEquipment");
  const btnSave=getID("btnSaveEquipment");
  let textinput= getID("textinput");
  let textareaInput=getID("textareaInput");
  let paraHandover=getID("paraHandover");
  
   btnEdit.style.display="block";
   btnSave.style.display="none";

   textinput.setAttribute("type","hidden");
   headingText.style.display="block"; 
   headingText.innerText=textinput.value;
   
   paraHandover.style.display="block";
   textareaInput.style.display="none";
   paraHandover.innerText=textareaInput.value; 

   let text=textinput.value.trim(); 
   let comment=textareaInput.value.trim();
   let cid=getID("cid").value;
   let eid=getID("eid").value;
   formdata=new FormData();
   formdata.append("saveWorkEquipmentPrint",1)
   formdata.append("text",text);
   formdata.append("comment",comment);
   formdata.append("cid",cid);
   formdata.append("eid",eid);
   fetch("../../ajax/ajax_employee.php",{
     method:"POST",
     body:formdata
   }).then(res=>res.json())
   .then((data)=>{
        myToast(data.status,data.message);
   }); 
}










