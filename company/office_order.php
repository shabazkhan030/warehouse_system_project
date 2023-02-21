<?php
   session_start();
   if(!isset($_SESSION["isLogedIn"])){
     header("location:../index.php");
   }
   if(!isset($_SESSION["company"]["cid"])){
     header("location:company.php");
   }
   $cid=intval(base64_decode($_SESSION["company"]["cid"])/99999);
   include "../dbcon/dbcon.php"; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Bestellung</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
 <?php include "common/headtop.php"; ?>
	<!--<div class="my-100 mt-20">
		<div class="d-flex">
			 <div class="d-block w-60">
			 	 <img src="../img/company_uploads/<?php echo $_SESSION["company"]["clogo"] ?>" style="width:120px;height:auto">
			 	 <h3 class="ml-10 mt-10"><?php echo $_SESSION["company"]["cname"] ?></h3>
			 </div>
			 <div class="mt-50 w-40 text-right">
			 	<a href="company_logout.php" class="btn bg-blue">Zurück</a>
			 </div>
		</div>
	</div>-->

    <header class="my-100 mt-50">
		 <ul class="d-flex bg-light list-style-none p-20">
			 	<li><a href="employee/employee.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Mitarbeiter</a></li>
			 	<li><a href="inventory.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark ">Warenbestand</a></li>
			 	<li><a href="office_order.php" class="active-company-profile text-decoration p-20 font-weight-bold border-right-1 text-dark ">Bestellung</a></li>
			 	<li><a href="vehicle_list.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Fahrzeugliste</a></li>
			 	<li><a href="edit_company.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Firmenprofil</a></li>
			 	<!--<li><a href="#" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Statistics</a></li>-->
		 </ul>
	</header>

	<div class="my-100 mt-20 mb-20">
		<div class="d-flex justify-content-between">
			<p class="bg-skyblue text-decoration text-light p-10">Bestellung</p>
			<input type="hidden" id="cid" value="<?= $cid; ?>">
			<a href="create_office_order.php"class="bg-orange text-decoration text-light cur-pointer p-10">Neue Bestellung +</a>
		</div>
	</div>
	<div class="my-100 mt-20 mb-50">
		<div class="my-100 mt-20 d-flex justify-content-between">
			<div>
				<label>Show</label>
				<select style="width:50px;padding:2px;" id="showTotal">
			    	<option value="10">10</option>
			    	<option value="25">25</option>
			    	<option value="50">50</option>
			    	<option value="100">100</option>
			    </select>
			</div>	
			<input type="text" id="searchbox" placeholder="Bestellung suchen">		
		</div>
		<table class="table-lightblue w-100" id="table-office-order">
			<thead>
				<tr>
					<td>ID</td>
					<td>Erstellt am</td>
					<td>Ge&auml;ndert am</td>
					<td>Abgeschlossen am</td>
					<td>Gew&uuml;nschte Menge</td>
					<td>Freigegeben Menge</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody id="fetch_office_order">
			</tbody>
		</table>
		<div class="text-center mt-20">
			<input type="hidden" id="pageinfo" value="1">
			<ul class="d-flex" style="display:flex;justify-content:center;" id="pagination-wrapper"></ul>
	  </div>
	</div>
	 <?php include "common/footer.php"; ?>
	 <script src="../js/functions.js"></script>
	 <script>
	 	let changeLimit=getID("showTotal");
	 	fetchOfficeOrder(1,changeLimit.value);
	 	 function fetchOfficeOrder(pageno,limit){
	 	 	let cid=getID("cid").value;
	 	 	formdata=new FormData();
	 	 	formdata.append("cid",cid);
	 	 	formdata.append("fetchOfficeOrder",1);
	 	 	getID("pageinfo").value=pageno;
	    formdata.append("pageno",pageno);
	    formdata.append("limit",limit);
	 	 	 fetch("../ajax/ajax_office_order.php",{
	 	 	 	 method:"POST",
	 	 	 	 body:formdata
	 	 	 }).then(res=>res.json())
	 	 	 .then((data)=>{
	 	 	 	let html='';
	 	 	 	if(data[0] == "empty"){
            html=`<tr>
                     <td colspan="7">Noch kein eintrag...</td>
                  </tr>`;
	 	 	 	}else{
	 	 	 		for(let i in data){
	           html+=`<tr>
                      <td>${data[i].id}</td>
                      <td>${data[i].submitted_date}</td>
                      <td>${data[i].signPur}</td>
                      <td>${data[i].signDir}</td>
                      <td>${data[i].crowd}</td>
                      <td>${data[i].appr}</td>
                      <td>
                          <a href="edit_office_order.php?oid='${data[i].oid}'" class="btn-sm bg-lightblue">bearbeiten</a>
                      </td>
                    </tr>`;
		 	 	 	}	
	 	 	 	}
           getID("fetch_office_order").innerHTML=html;
           pagination(limit);
	 	 	 })
	 	 }
	 	 function pagination(limit){
        let cid=getID("cid").value;
        let formdata=new FormData();
        formdata.append("page",1);
        formdata.append("cid",cid);
        formdata.append("limit",limit);
        fetch("../ajax/ajax_office_order.php",{
          method:"POST",
          body:formdata
        }).then(res=>res.json())
        .then((data)=>{
          var html='';
          if(data.length == 0){

          }else{
            for(var i in data){
              if(getID("pageinfo").value == data[i]){
                html+=`<li class="page list-style-none p-10 bg-lightblue cur-pointer" style="margin:0px 3px;margin-bottom:50px;color:white" page-no=${data[i]}>${data[i]}</li>`;
              }else{
                html+=`<li class="page list-style-none p-10 cur-pointer" style="margin:0px 3px;margin-bottom:50px;color:black;background:#edf7fe" page-no=${data[i]}>${data[i]}</li>`;
              }
            }
          }
          getID("pagination-wrapper").innerHTML=html;
          pageClick();
        })
    }
    changeLimit.addEventListener("change",function(){
		  let pageno=1;
		  fetchOfficeOrder(pageno,changeLimit.value);
		   pagination(changeLimit.value);
	  })
    function pageClick(){
   		let page=queryAll(".page")
	   	page.forEach(function(index){
	   		index.addEventListener("click",function(){
	   			var pageNo=getAttr(index,"page-no");
	   			getID("pageinfo").value=pageNo;
	   			let limit=getID("showTotal").value;
	   			fetchOfficeOrder(pageNo,limit);
	   			pagination(limit);
	   		})
	   	})
   	}
   	// search box
   	let searchBox=getID("searchbox")
		searchBox.addEventListener("keyup",function(){
	    var keyword = this.value;
	    keyword = keyword.toUpperCase();
	    var table = getID("table-office-order");
	    var all_tr = table.getElementsByTagName("tr");
	    for(var i=1; i<all_tr.length; i++){
	            var id_col = all_tr[i].getElementsByTagName("td")[0];
	            var sd_col = all_tr[i].getElementsByTagName("td")[1];
	            var signP_col = all_tr[i].getElementsByTagName("td")[2];
	            var signD_col = all_tr[i].getElementsByTagName("td")[3];
	            var crd_col = all_tr[i].getElementsByTagName("td")[4];
	            var appr_col = all_tr[i].getElementsByTagName("td")[5];
	            if(id_col && sd_col && signP_col && signD_col && crd_col && appr_col){
	                var id_val = id_col.textContent || id_col.innerText;
	                var sd_val = sd_col.textContent || sd_col.innerText;
	                var signp_val = signP_col.textContent || signP_col.innerText;
	                var signd_val = signD_col.textContent || signD_col.innerText;
	                var crd_val = crd_col.textContent || crd_col.innerText;
	                var appr_val = appr_col.textContent || appr_col.innerText;
	                id_val = id_val.toUpperCase();
	                sd_val = sd_val.toUpperCase();
	                signp_val = signp_val.toUpperCase();
	                signd_val = signd_val.toUpperCase();
	                crd_val = crd_val.toUpperCase();
	                appr_val = appr_val.toUpperCase();
	                if((id_val.indexOf(keyword) > -1)  || (sd_val.indexOf(keyword) > -1)  || (signp_val.indexOf(keyword) > -1) || (signd_val.indexOf(keyword) > -1) || (crd_val.indexOf(keyword) > -1) || (appr_val.indexOf(keyword) > -1)){
	                    all_tr[i].style.display = "";
	                    getID("pagination-wrapper").style.display='flex';
	                }else{
	                    all_tr[i].style.display = "none";
	                    getID("pagination-wrapper").style.display='none';
	                }
	            }
	        }
		})
	 </script>
</body>
</html>