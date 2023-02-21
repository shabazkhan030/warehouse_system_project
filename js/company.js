function createCompany(){
  const createcompany = getID('insertCompany');
createcompany.addEventListener('submit', function(e) {
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
  formData.append('fieldname',JSON.stringify(fieldname));
  formData.append('fieldvalue',JSON.stringify(fieldvalue));
  formData.append('checkboxvalue',JSON.stringify(checkboxvalue));
  fetch('../ajax/ajax_company.php', {
    method: 'POST',
    body: formData,
  })
  .then(res => res.json())
  .then((data) => {
    if(data.status == 1){
       myToast(data.status,data.message);
       getID("insertCompany").reset();
       setTimeout(function(){ location.reload(); }, 3000);
       
    }else{
      myToast(data.status,data.message);
    }
  })
});
}
function EditCompany(){
  const editCompany = getID('editCompanyProfile');
  editCompany.addEventListener('submit', function(e) {
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
  formData.append('fieldname',JSON.stringify(fieldname));
  formData.append('fieldvalue',JSON.stringify(fieldvalue));
  formData.append('checkboxvalue',JSON.stringify(checkboxvalue));
  fetch('../ajax/ajax_company.php', {
    method: 'POST',
    body: formData,
  })
  .then(res => res.json())
  .then((data) => {
    console.log(data);
    if(data.status == 1){
       myToast(data.status,data.message);
       getID("editCompanyProfile").reset();
       setTimeout(function(){ location.reload(); }, 3000);
       checkForTextfield();
    }else{
      myToast(data.status,data.message);
    }
  })
});
}
function checkForTextfield(){
  const cid=getID("cid").value;
  const textfields=getID("text-fields");
  
  const getText = new FormData();
  getText.append("getTextField","1");
  getText.append("cid",cid);
  fetch('../ajax/ajax_company.php', {
    method: 'POST',
    body: getText,
  })
  .then(res => res.json())
  .then((data) => {
    console.log(data);
    var row='';
    if(data[0] != "empty"){
          for(var i in data){
      row+=`<div class="d-flex">
              <div class="d-block mr-20 ml-20 mt-20">
                <label class="font-weight-bold">${data[i].textname}</label><br>
                <input type="text" class="textfield" value="${data[i].ctextval}">
                <input type="hidden" class="fieldnamevalue" value="${data[i].textname}">
                <input type="hidden" class="checkboxfeild" value="${data[i].visible}">
              </div>
            </div>`;

      textfields.innerHTML=row;
      }
    }else{
      textfields.innerHTML='';
    }

  }) 
}



