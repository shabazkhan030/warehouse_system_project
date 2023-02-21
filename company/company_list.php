<?php
   session_start();
   if(!isset($_SESSION['role']) || $_SESSION['role'] != 'super_admin'){
   	header("location:company.php");
   }
   include "../dbcon/dbcon.php";
   include "../func/functions.php"; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Firmenliste</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/company.css">
</head>
<body>
 <div class="neu-top-2">
		<div class="my-100 d-flex justify-content-between">
		   <div class="mt-50">
		   	   <h2 class="bg-lightblue text-light p-10">Firmenliste</h2>
		   </div>
		   <div class="mt-50">
		   	<a href="company.php" class="bg-red text-light p-10 text-decoration">zurück</a>
		   </div>

		</div>
	<section class="company-list my-100 mt-50 mb-50">
			<table class="mt-20 table-lightblue" style="width:100%;border-collapse: collapse;">
				<thead>
					<tr>
						<td>ID</td>
						<td>Firmenname</td>
						<td>HRB</td>
						<td>Steuer Nr.</td>
						<td>E-Mail</td>
						<td>Tel.</td>
						<td>Strasse</td>
						<td>Haus Nr.</td>
						<td>Stadt</td>
						<td>PLZ</td>
						<td>Bearbeiten</td>
					</tr>
				</thead>
				<tbody id="companyList">
				</tbody>
			</table>
	</section>
	<script src="../js/functions.js"></script>
	<script>
		LoadCompanyListData();
	</script>
	</div>
</body>
</html>