<?php 
session_start();
if(!isset($_SESSION["isLogedIn"])){
  header("location:../index.php");
}
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Company</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
 <div class="neu-top-2">
     <div class="text-right w-100 mt-50">
         <input type="hidden" id="pid" value="<?= $_SESSION["isLogedIn"] ?>">
         <a href="company_list.php" class="btn bg-grey">Firmen liste</a>
         <a href="personal_admin.php" class="btn bg-green">Firmen Personal</a>
         <a href="create_new_company.php" class="btn bg-orange">Neue Firma anlegen +</a>
         <a href="../logout.php" class="btn bg-blue mr-20">abmelden</a>
     </div>
     <div class="m-100">
     	<h2 class="ml-20 mt-50">Firma wählen</h2>
     	<div id="fetchCompany" class="d-flex flex-nowrap">	
     	</div>
     </div>
     <script src="../js/functions.js"></script>
     <script>
     	getAllCompany();
     </script>
	 </div>
</body>
</html>