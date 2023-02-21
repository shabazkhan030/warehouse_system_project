function createPersonalAdmin(){
  const createPersonalAdmin = getID('insertpersonaladmin');
  createPersonalAdmin.addEventListener('submit', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation()
      var fieldname=[];
      var fieldvalue=[];
      var checkboxvalue=[];

      var textfield=queryAll(".textfield");
      textfield.forEach(function(field,index){
       fieldname.push(textfield[index].value);
      });
      var fieldnamevalue=queryAll(".fieldnamevalue");
        fieldnamevalue.forEach(function(fieldval,index){
       fieldvalue.push(fieldnamevalue[index].value);
      });
      var checkboxfeild=queryAll(".checkboxfeild");
       checkboxfeild.forEach(function(fieldval,index){
       checkboxvalue.push(checkboxfeild[index].value);
      });
    const formData = new FormData(this);
    formData.append('fieldname',JSON.stringify(fieldname));
    formData.append('fieldvalue',JSON.stringify(fieldvalue));
    formData.append('checkboxvalue',JSON.stringify(checkboxvalue));
    fetch('../ajax/ajax_personal_admin.php', {
      method: 'POST',
      body: formData,
    })
    .then(res => res.json())
    .then((data) => {
      if(data.status == 1){
         myToast(data.status,data.message);
         getID("insertpersonaladmin").reset();
         setTimeout(function(){ location.reload(); }, 3000);
      }else{
        myToast(data.status,data.message);
      }
    })
  }); 
}
function EditPersonalAdmin(){
  const editpersonaladmin = getID('editpersonaladmin');
  editpersonaladmin.addEventListener('submit', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
      var fieldname=[];
      var fieldvalue=[];
      var checkboxvalue=[];

      var textfield=queryAll(".textfield");
      textfield.forEach(function(field,index){
       fieldname.push(textfield[index].value);
      });
      var fieldnamevalue=queryAll(".fieldnamevalue");
        fieldnamevalue.forEach(function(fieldval,index){
       fieldvalue.push(fieldnamevalue[index].value);
      });
      var checkboxfeild=queryAll(".checkboxfeild");
       checkboxfeild.forEach(function(fieldval,index){
       checkboxvalue.push(checkboxfeild[index].value);
      });
    const formData = new FormData(this);
    formData.append('fieldname',JSON.stringify(fieldname));
    formData.append('fieldvalue',JSON.stringify(fieldvalue));
    formData.append('checkboxvalue',JSON.stringify(checkboxvalue));
    fetch('../ajax/ajax_personal_admin.php', {
      method: 'POST',
      body: formData,
    })
    .then(res => res.json())
    .then((data) => {
      if(data.status == 1){
         myToast(data.status,data.message);
         getID("editpersonaladmin").reset();
         setTimeout(function(){ location.reload(); }, 3000);
      }else{
        myToast(data.status,data.message);
      }
    })
  }); 
}

