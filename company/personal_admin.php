<?php 
session_start();
if(!isset($_SESSION['isLogedIn'])){
	header("location:../index.php");
}
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Personaleinstellung</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
<div class="neu-top-2">
   <div class="m-100 d-flex">
   	  	<div class="w-60">
	   	  	<h2>Personaleinstellung</h2>
	   	</div>
	   	<div class="w-40 text-right">
	   	  	<div class="d-block">
	   	  		<a href="../logout.php" class="btn bg-blue">&nbsp; Abmelden &nbsp;</a>
	   	  		
	   	  		<a href="company.php" class="btn bg-green">&nbsp; &nbsp;Zurück &nbsp; &nbsp;</a>
	   	  	</div>
   	  	</div>
   </div>
   <?php if($_SESSION['role']== 'super_admin'): ?>
   <div class="mt-20 mr-20 text-right ">
   	 <a href="insert_personal_admin.php" class="btn bg-orange">Sachbearbeiter anlegen +</a>
   </div>
   <?php endif; ?>

   <div class="my-100 d-flex mb-10">
   	  <div class="w-40">
   	  	<h3 class="bg-lightblue p-10 w-100 text-light">Sachbearbeiter</h3>
   	  </div>
   </div>
   <div class="my-100">
   	  <div class="w-100 text-right">
   	  	 <input type="text" id="searchbox" placeholder="Sachbearbeiter suchen " style="width:200px;">
   	  </div>
   </div>
   <div class="my-100">
   	  <table border="1" class="gsstable" id="table-admin" style="width:100%;border-collapse: collapse;">
   	  	 <thead>
   	  	 	<tr>
   	  	 		<td>ID</td>
   	  	 		<td>Nachname</td>
   	  	 		<td>Vorname</td>
   	  	 		<td>E-Mail Adresse</td>
   	  	 		<td>Erstellt am</td>
   	  	 		<td>Geschlossen am</td>
   	  	 		<td>Firma</td>
   	  	 		<td>Status</td>
   	  	 		<?php if($_SESSION['role']== 'super_admin'): ?>
   	  	 		<td>&nbsp;</td>
   	  	 		<?php endif; ?>
   	  	 	</tr>
   	  	 </thead>
   	  	 <tbody id="adminList">
   	  	 </tbody>
   	  </table>
   </div>
   <script src="../js/functions.js"></script>
   <script>
   	function LoadPersonalAdminData(){
  var adminList=getID("adminList");
  const getRequest = new FormData();
  getRequest.append('getRequest',1);
  fetch('../ajax/ajax_personal_admin.php', {
    method: "POST",
    body:getRequest
  })
  .then((response)=>{
      return response.json(); 
  }) 
  .then((data)=>{
    var tr='';
    if(data["empty"] == 'empty'){
       tr=`<tr><td colspan="9" class="text-center">Keine Eintr&auml;ge gefunden....</td></tr>`;
       adminList.innerHTML=tr;
    }else{
        for(var i in data){
        	let bg=(data[i].status == 'disabled') ? "bg-red" : "bg-green";
        	let id="<?= $_SESSION['isLogedIn'] ?>";
        	if(data[i].role != 'super_admin' || id == data[i].aid){
        	  tr+=`<tr>
              <td>${data[i].id}</td>
              <td>${data[i].sname}</td>
              <td>${data[i].fname}</td>
              <td>${data[i].email}</td>
              <td>${data[i].crdate}</td>
              <td>${data[i].cldate}</td>
              <td>${data[i].cname}</td>
              <td><p class="d-inline-block ${bg} text-light p-5" style="width:70px;text-align:center">${data[i].status}</p></td>
              <?php if($_SESSION['role']== 'super_admin'): ?>
              <td>
                 <a href="edit_personal_admin.php?aid=${data[i].aid}" class="btn-sm bg-lightblue">bearbeiten</a>
              </td>
              <?php endif; ?>
           </tr>`;	
        	}
        adminList.innerHTML=tr;
      }
    }

  })
  .catch(err => console.log(err));
}
   	LoadPersonalAdminData();
   	let searchBox=getID("searchbox")
		searchBox.addEventListener("keyup",function(){
	    var keyword = this.value;
	    keyword = keyword.toUpperCase();
	    var table = getID("table-admin");
	    var all_tr = table.getElementsByTagName("tr");
	    for(var i=1; i<all_tr.length; i++){
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
	                }else{
	                    all_tr[i].style.display = "none";

	                }
	            }
	        }
		})

   </script>
  </div>
</body>
</html>