function SubmithandoverUTA(){
   const handoverUTAForm=getID("handoverUTAForm");
     handoverUTAForm.addEventListener('submit', function(e) {
     e.preventDefault();
     e.stopImmediatePropagation();
     let submitBtn=getID("submitUTBtn");
     submitBtn.disabled=true;
     const formData = new FormData(this);
     formData.append("handoverUTASubmit",1);
       fetch('../../ajax/ajax_employee.php', {
       method: 'POST',
       body: formData,
     })
     .then(res => res.json())
     .then((data) => {
       // console.log(data);
       if(data.status == 1){
         getID('handoverUTAForm').reset();
         myToast(data.status,data.message);
         setTimeout(function(){ location.reload(); }, 3000);
       }else{
         getID("submitUTBtn").disabled=false;
         myToast(data.status,data.message);
       }
     });
   })
}
function SubmitreturnUTA(){
   const returnUTAForm=getID("returnUTAForm");
     returnUTAForm.addEventListener('submit', function(e) {
     e.preventDefault();
     e.stopImmediatePropagation()
     getID("SubmitUTBtn").disabled=true;
     const formData = new FormData(this);
     formData.append("returnUTASubmit",1);
       fetch('../../ajax/ajax_employee.php', {
       method: 'POST',
       body: formData,
     })
     .then(res => res.json())
     .then((data) => {
       if(data.status == 1){
         getID('returnUTAForm').reset();
         myToast(data.status,data.message);
         setTimeout(function(){ location.reload(); }, 3000);
       }else{
        getID("SubmitUTBtn").disabled=false;
         myToast(data.status,data.message);
       }
     });
   })
}

queryAll(".printUTAHandover").forEach((row,index)=>{
  row.addEventListener("click",()=>{
    let tcHandovedId=getAttr(row,"data-print");
    let cid=getID("cid").value;
    formdata=new FormData();
    formdata.append("getPrintHandover","uta_fuel_card_handover");
    formdata.append("tcHandovedId",tcHandovedId);
    formdata.append("cid",cid);
    fetch("../../ajax/ajax_employee.php",{
       method:"POST",
       body:formdata
    }).then(res=>res.json())
    .then((data)=>{
 getID("phid").value=tcHandovedId;
 getID("empl_name").innerText=data.firstname+' '+data.surname;
 getID("empl_street").innerText=data.empl_street+' '+data.empl_house_no;
 getID("empl_zip").innerText=data.empl_zip;
 getID("empl_city").innerText=data.empl_city;
 getID("empl_phone").innerText=data.phone_number;
 getID("empl_dob").innerText=data.date_of_birth ;
 getID("com_name").innerText=data.company_name;
 getID("com_city").innerText=data.company_city;
 getID("com_street").innerText=data.company_street+" "+data.company_house_no;
 getID("com_zip").innerText=data.company_zip_code;
 getID("headingText").innerHTML=data.print_heading;
 getID("paraHandover").innerHTML=data.print_comment;
 if(data.hallname != '' && data.hallname != null){
   getID("hallname").innerHTML=`<label style="font-size:18px;font-weight:bold;">Kennzeichen</label><br>
          <p id="hallname" style="width:200px;border:1px solid #000000;padding:5px;">${data.hallname}</p>`;
 }else{
  getID("hallname").innerHTML='';
 }
 getID("text1").innerText=data.text1;
 getID("text2").innerText=data.text2;
 
 getID("handoverData").innerText=data.data_handover;
 if(data.signature_staff != ''){
  getID("handoverSignStaff").innerHTML=`<img src="../../img/signature_uploads/${data.signature_staff}" style="width:95%">
                                        <p>${data.signed_by}</p>`;
 }
 if(data.signature_employee != ''){
  getID("handoverEmplSign").innerHTML=`<img src="../../img/signature_uploads/${data.signature_employee}" style="width:100%">`;
 }
   myToast(1,"Loaded Tankchip Handover Data For Print..");
    });
  })
});

queryAll(".printUTAReturn").forEach((row,index)=>{
  row.addEventListener("click",()=>{
    let tcReturnId=getAttr(row,"data-print");
    let cid=getID("cid").value;
    formdata=new FormData();
    formdata.append("getPrintReturn","uta_fuel_card_return");
    formdata.append("tcReturnId",tcReturnId);
    formdata.append("cid",cid);
    fetch("../../ajax/ajax_employee.php",{
       method:"POST",
       body:formdata
    }).then(res=>res.json())
    .then((data)=>{
 getID("prid").value=tcReturnId;
 getID("rempl_name").innerText=data.firstname+' '+data.surname;
 getID("rempl_street").innerText=data.empl_street+' '+data.empl_house_no;
 getID("rempl_zip").innerText=data.empl_zip;
 getID("rempl_city").innerText=data.empl_city;
 getID("rempl_phone").innerText=data.phone_number;
 getID("rempl_dob").innerText=data.date_of_birth ;
 getID("rcom_name").innerText=data.company_name;
 getID("rcom_city").innerText=data.company_city;
 getID("rcom_street").innerText=data.company_street+" "+data.company_house_no;
 getID("rcom_zip").innerText=data.company_zip_code;
  getID("headingTextReturn").innerText=data.print_heading;
 getID("paraReturn").innerText=data.print_comment;
 if(data.hallname != '' && data.hallname != null){
   getID("rhallname").innerHTML=`<label style="font-size:18px;font-weight:bold;">Kennzeichen</label><br>
          <p id="hallname" style="width:200px;border:1px solid #000000;padding:5px;">${data.hallname}</p>`;
 }else{
  getID("rhallname").innerHTML='';
 }
 getID("rtext1").innerText=data.text1;
 getID("rtext2").innerText=data.text2;
 getID("returnData").innerText=data.data_return;
 if(data.signature_staff != ''){
  getID("returnSignStaff").innerHTML=`<img src="../../img/signature_uploads/${data.signature_staff}" style="width:100%"><p>${data.signed_by}</p>`;
 }
 if(data.signature_employee != ''){
  getID("returnEmplSign").innerHTML=`<img src="../../img/signature_uploads/${data.signature_employee}" style="width:100%">`;
 }
   myToast(1,"Loaded Tankchip Return Data For Print..");
    });
  })
});


let headingText=getID("headingText");
const editHandoverPrint=getID("editHandoverPrint");
const saveHandoverPrint=getID("saveHandoverPrint");
let texthandover= getID("texthandover");
let textareaHandover=getID("textareaHandover");
let paraHandover=getID("paraHandover");
editHandoverPrint.addEventListener("click",()=>{
  // BTN
  saveHandoverPrint.style.display="block";
  editHandoverPrint.style.display="none";
  // HEADING
  texthandover.setAttribute("type","text");
  headingText.style.display="none";
  texthandover.value=headingText.innerText;

  // PARAGRAPH
  paraHandover.style.display="none";
  textareaHandover.style.display="block";
  textareaHandover.value=paraHandover.innerText;
});

saveHandoverPrint.addEventListener("click",()=>{
 if(texthandover.value.trim() != '' && textareaHandover.value.trim() != ''){
   // BTN
   saveHandoverPrint.style.display="none";
   editHandoverPrint.style.display="block";
   // HEADING
   headingText.innerText=texthandover.value;
   texthandover.setAttribute("type","hidden");
   headingText.style.display="block";

   // PARAGRAPH
  paraHandover.innerText=textareaHandover.value;
  paraHandover.style.display="block";
  textareaHandover.style.display="none";
  let phid=getID("phid").value;
  let formdata=new FormData();
  formdata.append("savePrint","uta_fuel_card_handover");
  formdata.append("id",phid);
  formdata.append("print_heading",texthandover.value);
  formdata.append("print_comment",textareaHandover.value);
  fetch("../../ajax/ajax_employee.php",{
       method:"POST",
       body:formdata
    }).then(res=>res.json())
    .then((data)=>{
      if(data.status == 1){
        myToast(data.status,data.message);
      }else{
        myToast(data.status,data.message);
      }
    })

 }else{
    myToast(0,"Please Enter Header Text value");
 }
});

// RETURN PROTOCOL===
let headingTextReturn=getID("headingTextReturn");
const editReturnPrint=getID("editReturnPrint");
const saveReturnPrint=getID("saveReturnPrint");
let textReturn= getID("textReturn");
let textareaReturn=getID("textareaReturn");
let paraReturn=getID("paraReturn");
editReturnPrint.addEventListener("click",()=>{
  // BTN
  saveReturnPrint.style.display="block";
  editReturnPrint.style.display="none";
  // HEADING
  textReturn.setAttribute("type","text");
  headingTextReturn.style.display="none";
  textReturn.value=headingTextReturn.innerText;

  // PARAGRAPH
  paraReturn.style.display="none";
  textareaReturn.style.display="block";
  textareaReturn.value=paraReturn.innerText;
});

saveReturnPrint.addEventListener("click",()=>{
 if(textReturn.value.trim() != '' && textareaReturn.value.trim() != ''){
   // BTN
   saveReturnPrint.style.display="none";
   editReturnPrint.style.display="block";
   // HEADING
   headingTextReturn.innerText=textReturn.value;
   textReturn.setAttribute("type","hidden");
   headingTextReturn.style.display="block";

   // PARAGRAPH
  paraReturn.innerText=textareaReturn.value;
  paraReturn.style.display="block";
  textareaReturn.style.display="none";
  let prid=getID("prid").value;
  let formdata=new FormData();
  formdata.append("savePrint","uta_fuel_card_return");
  formdata.append("id",prid);
  formdata.append("print_heading",textReturn.value);
  formdata.append("print_comment",textareaReturn.value);
  fetch("../../ajax/ajax_employee.php",{
       method:"POST",
       body:formdata
    }).then(res=>res.json())
    .then((data)=>{
      if(data.status == 1){
        myToast(data.status,data.message);
      }else{
        myToast(data.status,data.message);
      }
    })

 }else{
    myToast(0,"Please Enter Header Text value");
 }
});

