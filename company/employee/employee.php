<?php
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../../index.php");
}
if(!isset($_SESSION["company"]["cid"])){
	header("location:../company.php");
}
include "../../dbcon/dbcon.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mitarbeiterübersicht</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
	<link rel="stylesheet" type="text/css" href="../../css/company.css">
</head>
<body>
    <?php include "common/headtop.php"; ?>
    <?php include "common/navbar.php" ?>

	<div class="my-100 mt-20">
		<a class="btn bg-lightblue">Mitarbeiterübersicht</a>
	</div>
	<div class="my-100">
		<div class="my-100 mt-20 text-right">
			<a href="insert_employee_data.php" class="btn bg-orange">Mitarbeiter anlegen +</a>
		</div>
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
			<input type="text" id="searchbox" placeholder="Mitarbeiter suchen">		
		</div>
		<input type="hidden" id="cid" value="<?php echo $_SESSION["company"]["cid"]; ?>">
		
		<div>
		<table class="mt-20 table-lightblue" style="width:100%;border-collapse: collapse;" id="table-employee">
			<thead>
				<tr class="drehen">
					<th>ID</th>
					<th>Nachname</th>
					<th>Vorname</th>
					<th>Arbeitsmittel</th>
					<th>Tankchip</th>
					<th>UTA Tankkarte</th>
					<th>Fahrzeugpr.</th>
					<th>Führerschein</th>
					<th>Letzte/Ma.</th>
					<th>Status</th>
					<th>Bearbeiten</th>
				</tr>
			</thead>
			<tbody id="employeeList">
			</tbody>
		</table>
		
		</div>
		<div class="text-center mt-20">
			<input type="hidden" id="pageinfo" value="1">
			<ul class="d-flex" style="display:flex;justify-content:center;" id="pagination-wrapper"></ul>
		</div>
	</div>
	<script src="../../js/functions.js"></script>
	<script>
		let changeLimit=getID("showTotal");
		LoadEmployeeListData(1,changeLimit.value);
        let searchBox=getID("searchbox")
		searchBox.addEventListener("keyup",function(){
	    var keyword = this.value;
	    keyword = keyword.toUpperCase();
	    var table = getID("table-employee");
	    var all_tr = table.getElementsByTagName("tr");
	    for(var i=0; i<all_tr.length; i++){
	            var id_col = all_tr[i].getElementsByTagName("td")[0];
	            var sname_col = all_tr[i].getElementsByTagName("td")[1];
	            var fname_col = all_tr[i].getElementsByTagName("td")[2];
	            if(id_col && sname_col && fname_col){
	                var id_val = id_col.textContent || id_col.innerText;
	                var sname_val = sname_col.textContent || sname_col.innerText;
	                var fname_val = fname_col.textContent || fname_col.innerText;
	                id_val = id_val.toUpperCase();
	                sname_val = sname_val.toUpperCase();
	                fname_val = fname_val.toUpperCase();
	                if((id_val.indexOf(keyword) > -1) || (sname_val.indexOf(keyword) > -1) || (fname_val.indexOf(keyword) > -1)){
	                    all_tr[i].style.display = "";
	                    getID("pagination-wrapper").style.display='flex';
	                }else{
	                    all_tr[i].style.display = "none";
	                    getID("pagination-wrapper").style.display='none';
	                }
	            }
	        }
		})
	
	changeLimit.addEventListener("change",function(){
		// console.log(changeLimit.value);
		let pageno=1;
		LoadEmployeeListData(pageno,changeLimit.value)
		pagination(changeLimit.value);
	})
	pagination(changeLimit.value);
	function pagination(limit){
        let cid=getID("cid").value;
        let formdata=new FormData();
        formdata.append("page",1);
        formdata.append("cid",cid);
        formdata.append("limit",limit);
        fetch("../../ajax/ajax_employee.php",{
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
   	function pageClick(){
   		let page=queryAll(".page")
	   	page.forEach(function(index){
	   		index.addEventListener("click",function(){
	   			var pageNo=getAttr(index,"page-no");
	   			getID("pageinfo").value=pageNo;
	   			let limit=getID("showTotal").value;
	   			LoadEmployeeListData(pageNo,limit);
	   			pagination(limit);
	   		})
	   	})
   	}

	</script>
	 <?php include "common/footer.php" ?>
</body>
</html>