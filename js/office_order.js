function purchaser(){
   getID("note_purchaser").innerHTML="";
}
function director(){
   getID("note_director").innerHTML="";
}
function signaturePurchaser(){
	let wrapper = getID("signature-pad-purchaser");
	let clearButton = wrapper.querySelector("[data-action=clearpurchaserSign]");
	let btnClick=getID("save_btn_purchaser");
	let canvas = wrapper.querySelector("#the_canvas_purchaser");
	let el_note = getID("note_purchaser");
	let signaturePad;
	signaturePad = new SignaturePad(canvas);
	clearButton.addEventListener("click", function (event) {
	   getID("note_purchaser").innerHTML="Die Unterschrift sollte innerhalb der Box sein";
	   signaturePad.clear();
	});
	btnClick.addEventListener("click", function (event){
		event.preventDefault();
	   if (signaturePad.isEmpty()){
	     myToast(0,"Please provide signature first.");
	   }else{
	     let canvas  = getID("the_canvas_purchaser");
	     let dataUrl = canvas.toDataURL();
	     getID("signature_purchaser").value = dataUrl;
	     myToast(1,"Successfully Uploaded Signature");
	   }
	});
}
function signatureDirector(){
	let wrapper = getID("signature-pad-director");
	let clearButton = wrapper.querySelector("[data-action=cleardirectorSign]");
	let btnClick=getID("save_btn_managing_director");
	let canvas = wrapper.querySelector("#the_canvas_director");
	let el_note = getID("note_director");
	let signaturePad;
	signaturePad = new SignaturePad(canvas);
	clearButton.addEventListener("click", function (event) {
	   getID("note_director").innerHTML="Die Unterschrift sollte innerhalb der Box sein";
	   signaturePad.clear();
	});
	btnClick.addEventListener("click", function (event){
		 event.preventDefault();
	   if (signaturePad.isEmpty()){
	     myToast(0,"Please provide signature first.");
	   }else{
	     let canvas  = getID("the_canvas_director");
	     let dataUrl = canvas.toDataURL();
	     getID("signature_director").value = dataUrl;
	     myToast(1,"Successfully Uploaded Signature..");
	   }
	});
}
function saveOrderForm(){
	forminsert=getID("insertOfficeOrder");
	forminsert.addEventListener('submit', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
	  let cboo=[];
	  queryAll(".checkboxofficeorder").forEach((checkofficeOrder)=>{
       (checkofficeOrder.checked == true) ? cboo.push(1) : cboo.push(0);
     });
        let formdata=new FormData(this);
        formdata.append("chckbxofficeorder",JSON.stringify(cboo));
        formdata.append("submitOfficeOrder",1);
        fetch("../ajax/ajax_office_order.php",{
        	method:"POST",
        	body:formdata
        }).then((res)=>{
        	return res.json()
        }).then((data)=>{
        	console.log(data);
           if(data.status == 1){
            	myToast(data.status,data.message);
            	setTimeout(function(){ 
                 window.location.href="edit_office_order.php?oid="+data.oid;
            	},2000);
           }else{
           	  myToast(data.status,data.message);
           	  cboo=[];
           }
        })
	})
}

  function getArticleList(event){
     let input=event.value;
     let cid=getID("cid").value;
    if(input.value != ''){
    	 let formdata=new FormData();
	     formdata.append("fetchArticle",1);
	     formdata.append("input",input);
	     formdata.append("cid",cid);  
	     fetch("../ajax/ajax_office_order.php",{
	     	   method:"POST",
	     	   body:formdata
	     }).then((res)=>res.json())
	       .then((data)=>{
	       	let output='';
	       	console.log(data);
	       	// console.log(data[]);
	     	  if(data[0] != null){
	     	  	for(var i in data){
	            output+=`<li class="list-style-none p-5 selectedArticle cur-pointer" onclick="selectedArticle(this);" style="width:180px;padding-top:10px;position:relative;z-index:999">${data[i].article}  [${data[i].size}#${data[i].piece_price}]</li>`;
	     	  	}
	           
	     	  }else{
	          event.parentElement.children[1].innerHTML=''
	     	  }
	     	   event.parentElement.children[1].innerHTML=output;
	     })
    }else{
    	event.parentElement.children[1].innerHTML='';
    }

  }

  function selectedArticle(event){
     let selected=event.innerText;
     let sp1=selected.split(' [');
     let article=sp1[0];
     let si=sp1[1].split('#');
     let sp2= si[1].split("]");
     event.parentElement.previousSibling.value=article;
     event.parentElement.parentElement.nextSibling.children[0].value=si[0];
     event.parentElement.parentElement.parentElement.children[7].children[0].value=sp2[0];
     // piece_price
     event.parentElement.innerHTML='';
  }

    function deleteitemListRow(e){
  	if(!e.target.classList.contains("deleteitemList")){
  		return;
  	}else{
  		if(e.target.hasAttribute('data-delete')){
  			let odetails=e.target.getAttribute("data-delete");
  			confirmModal(odetails)
  		}else{
  			const btn=e.target;
  		    btn.closest("tr").remove();
  		}  
  	}
  }

function confirmModal(data){
  getID("dataDelete").value=data;
   var modal =getID("confirmModal");
	  modal.style.display = "block";
	  var span = document.getElementsByClassName("closeModal")[0];
	  span.onclick = function() {
	    modal.style.display = "none";
	  }
	  window.onclick = function(event) {
	    if(event.target == modal){
	      modal.style.display = "none";
	    }
	  }
}
function confirmDelete(){
	  let odetails=getID("dataDelete").value;
	  let formdata=new FormData;
		formdata.append("deleteRow",1);
		formdata.append("odetails",odetails);
		fetch("../ajax/ajax_office_order.php",{
			method:"POST",
			body:formdata
		}).then((res)=>{
			return res.json();
		}).then((data)=>{
      	if(data.status == 1){
      		myToast(data.status,data.message);
      		getID("confirmModal").style.display = "none";
      		setTimeout(function(){ location.reload()},3000);
      	}else{
      		myToast(data.status,data.message);
      	}
		})
}

  function calcTotal(el){
  	
  	let row=el.parentElement.parentElement;
    let price=row.children[7].children[0].value;
    let qty=row.children[6].children[0].value;
    row.children[8].children[0].value=parseFloat(qty*price).toFixed(2);
  }