<?php
   session_start();
   if(!isset($_SESSION["isLogedIn"])){
     header("location:../index.php");
   }
   if(!isset($_SESSION["company"]["cid"])){
   	header("location:company.php");
   }
   $cid=base64_decode($_SESSION["company"]["cid"])/99999;

   include "../dbcon/dbcon.php"; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fahrzeugliste</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
  <?php include 'common/headtop.php'; ?>
	<header class="my-100 mt-50">
		 <ul class="d-flex bg-light list-style-none p-20">
			 	<li><a href="employee/employee.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Mitarbeiter</a></li>
			 	<li><a href="inventory.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark ">Warenbestand</a></li>
			 	<li><a href="office_order.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark ">Bestellung</a></li>
			 	<li><a href="vehicle_list.php" class="active-company-profile text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Fahrzeugliste</a></li>
			 	<li><a href="edit_company.php" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Firmenprofil</a></li>
			 	<!--<li><a href="#" class="text-decoration p-20 font-weight-bold border-right-1 text-dark text-decoration">Statistics</a></li>-->
		 </ul>
	</header>

	<div class="my-100 mt-20">
		<a class="btn bg-lightblue">Fahrzeugliste</a>
		<input type="hidden" id="cid" value="<?= $cid ?>">
	</div>
	<div class="my-100 mt-100">
		<div class="my-100 mt-20 text-right">
			<a href="create_vehicle_list.php" class="btn bg-orange">Neues Fahrzeug anlegen +</a>
		</div>
		<!-- added this -->
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
			<input type="text" id="searchbox" placeholder="Fahrzeugliste suchen">		
		</div>
		<table class="mt-20 table-light w-100" id="table-vehicle" style="width:100%;border-collapse: collapse;">
				<thead>
					<tr>
						<td>ID</td>
						<td>Kennzeichen</td>
						<td>Fahrzeug Art</td>
						<td>Fahrzeugname</td>
						<td class="text-green">letzte Inspektion</td>
						<td class="text-red">Nächste Inspektion</td>
						<td class="text-green">Letzte Tüv</td>
						<td class="text-red">Nächte TüV</td>
						<td>genutz von</td>
						<td>Status</td>
						<td>&nbsp; </td>
					</tr>
				</thead>
				<tbody id="fetch_vehicle_list">

				</tbody>
			</table>
	</div>
	<div class="text-center mt-20">
			<input type="hidden" id="pageinfo" value="1">
			<ul class="d-flex" style="display:flex;justify-content:center;" id="pagination-wrapper"></ul>
	</div>
	 <?php include "common/footer.php"; ?>
	 <script src="../js/functions.js"></script>
	 <script>
	 	let changeLimit=getID("showTotal");
	 	fetchVehicleList(1,changeLimit.value);
	    function fetchVehicleList(pageno,limit){
	    	 let cid=getID("cid").value;
	    	 formdata=new FormData();
	    	 formdata.append("fetchVehicleInfo",1)
	    	 formdata.append("cid",cid);
	    	 getID("pageinfo").value=pageno;
	    	 formdata.append("pageno",pageno);
	    	 formdata.append("limit",limit);
       fetch("../ajax/ajax_vehicle_list.php",{
       	   method:"POST",
       	   body:formdata
       }).then(res=>res.json())
       .then((data)=>{
       	 // console.log(data);
       	 let html='';
       	 if(data[0] == 'empty'){
             html=`<tr><td colspan="8">Noch kein eintrag...</td></tr>`;
       	 }else{
       	 	  for (let i in data){
         	 	   html+=`<tr>
												<td>${data[i].id}</td>
												<td>${data[i].hallmark}</td>
												<td>${data[i].vehicle_type}</td>
												<td>${data[i].car_name}</td>
												<td>${data[i].service_last}</td>
												<td>${data[i].service_next}</td>
												<td>${data[i].tuv_last}</td>
												<td>${data[i].tuv_next}</td>
												<td>${data[i].employee_name}</td>
												<td>${data[i].status}</td>
												<td>
							   	  	 		  <a href="edit_vehicle_list.php?vid=${data[i].vid}" class="btn-sm bg-lightblue">bearbeiten</a>
							   	  	 	</td>
									    </tr>`;
         	 } 
       	 }
        getID("fetch_vehicle_list").innerHTML=html;
        pagination(limit);
       })
	    }
	 	let searchBox=getID("searchbox")
		searchBox.addEventListener("keyup",function(){
	    var keyword = this.value;
	    keyword = keyword.toUpperCase();
	    var table = getID("table-vehicle");
	    var all_tr = table.getElementsByTagName("tr");
	    for(var i=1; i<all_tr.length; i++){
	            var id_col = all_tr[i].getElementsByTagName("td")[0];
	            var hall_col = all_tr[i].getElementsByTagName("td")[1];
	            var veh_type_col = all_tr[i].getElementsByTagName("td")[2];
	            var car_name_col = all_tr[i].getElementsByTagName("td")[3];
	            var sl_col = all_tr[i].getElementsByTagName("td")[4];
	            var sn_col = all_tr[i].getElementsByTagName("td")[5];
	            var tl_col = all_tr[i].getElementsByTagName("td")[6];
	            var tn_col = all_tr[i].getElementsByTagName("td")[7];
	            var ename_col = all_tr[i].getElementsByTagName("td")[8];
	            var st_col = all_tr[i].getElementsByTagName("td")[9];
	            if(id_col && hall_col && veh_type_col && car_name_col && sl_col && sn_col && tl_col && tn_col && ename_col && st_col){
	                var id_val = id_col.textContent || id_col.innerText;
	                var hall_val = hall_col.textContent || hall_col.innerText;
	                var vtype_val = veh_type_col.textContent || veh_type_col.innerText;
	                var cname_val = car_name_col.textContent || car_name_col.innerText;
	                var sl_val = sl_col.textContent || sl_col.innerText;
	                var sn_val = sn_col.textContent || sn_col.innerText;
	                var tl_val = tl_col.textContent || tl_col.innerText;
	                var tn_val = tn_col.textContent || tn_col.innerText;
	                var ename_val = ename_col.textContent || ename_col.innerText;
	                var st_val = st_col.textContent || st_col.innerText;
	                id_val = id_val.toUpperCase();
	                hall_val = hall_val.toUpperCase();
	                vtype_val = vtype_val.toUpperCase();
	                cname_val = cname_val.toUpperCase();
	                sl_val = sl_val.toUpperCase();
	                sn_val = sn_val.toUpperCase();
	                tl_val = tl_val.toUpperCase();
	                tn_val = tn_val.toUpperCase();
	                ename_val = ename_val.toUpperCase();
	                st_val = st_val.toUpperCase();
	                if((id_val.indexOf(keyword) > -1)  || (hall_val.indexOf(keyword) > -1)  || (vtype_val.indexOf(keyword) > -1) || (cname_val.indexOf(keyword) > -1) || (sl_val.indexOf(keyword) > -1) || (sn_val.indexOf(keyword) > -1) || (tl_val.indexOf(keyword) > -1) || (tn_val.indexOf(keyword) > -1) || (ename_val.indexOf(keyword) > -1) || (st_val.indexOf(keyword) > -1)){
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
		  let pageno=1;
		  fetchVehicleList(pageno,changeLimit.value);
		   pagination(changeLimit.value);
	  })

// PAGINATION

  function pagination(limit){
        let cid=getID("cid").value;
        let formdata=new FormData();
        formdata.append("page",1);
        formdata.append("cid",cid);
        formdata.append("limit",limit);
        fetch("../ajax/ajax_vehicle_list.php",{
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
	   			fetchVehicleList(pageNo,limit);
	   			pagination(limit);
	   		})
	   	})
   	}
	 </script>
</body>
</html>