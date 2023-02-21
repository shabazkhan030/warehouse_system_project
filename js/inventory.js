var table=getID("inventoryTable");
function editRow(){
		var tableEdit=queryAll(".editInventory");
		    tableEdit.forEach((tr, index) => {
	            tr.addEventListener('click', (arrow) => {
	               for(var i = 0;i< 8;i++){
	               	if(i != 5){
	               	table.rows[index+1].cells[i].children[0].removeAttribute('readonly');
	               	table.rows[index+1].cells[i].children[0].style.border="2px solid black";		               		
	               	}
	               }
	            	tableRowTotalPrice();
	       });
	   });
}  
function tableRowTotalPrice(){
 	var totalAll=0;
 	var tableEdit=queryAll(".editInventory");

 	tableEdit.forEach((tr,index)=>{
 		// total price of row
 		var totalprice=Math.round(table.rows[index+1].cells[2].children[0].value*table.rows[index+1].cells[7].children[0].value * 100) / 100;
 		var floatPrice=parseFloat(totalprice).toFixed(2);
      table.rows[index+1].cells[8].innerHTML=floatPrice;
      totalAll=totalAll+totalprice;
 	})
 	//total price of all
     getID("totalAll").innerHTML=parseFloat(totalAll).toFixed(2);
 }

function rowSave(){
		var tableSave=queryAll(".save");
    tableSave.forEach((tr, index) => { 
      tr.addEventListener('click', arrow => {
      
      var article=table.rows[index+1].cells[0].children[0].value;
      var size=table.rows[index+1].cells[1].children[0].value;
      var crowd=table.rows[index+1].cells[2].children[0].value;
      var defect=table.rows[index+1].cells[3].children[0].value;
      var lost=table.rows[index+1].cells[4].children[0].value;
      var output=table.rows[index+1].cells[5].children[0].value;
      var piecePrice=table.rows[index+1].cells[7].children[0].value;
      table.rows[index+1].cells[9].children[0].children[1].disabled =true;
      var pid=getID("pid").value;
      var cid=getID("cid").value;
      var iid=(tr.getAttribute("iid") == '' || tr.getAttribute("iid") == null) ? 0 : tr.getAttribute("iid");
      var formdata=new FormData();
      formdata.append("savenewInventory",1);
      formdata.append("article",article);
      formdata.append("size",size);
      formdata.append("crowd",crowd);
      formdata.append("defect",defect);
      formdata.append("lost",lost);
      formdata.append("output",output);
      formdata.append("piecePrice",piecePrice);
      formdata.append("iid",iid);
      formdata.append("cid",cid);
      formdata.append("pid",pid);
      
	   fetch('../ajax/ajax_inventory.php',{
				    method: 'POST',
				    body: formdata,
				  })
				  .then(res => res.json())
				  .then((data) => {
				    if(data.status == 1){
				      myToast(data.status,data.message);
				       tableRowTotalPrice();
				       setTimeout(function(){ location.reload(); }, 3000);
				    }else{
				      myToast(data.status,data.message);
				    }
				  });
	   });
	});
}