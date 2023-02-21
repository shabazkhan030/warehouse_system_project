<?php 
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../index.php");
}
include "../dbcon/dbcon.php";
include "../func/functions.php";
if(isset($_POST["getCompanyName"])){
  $response=array();
  $qry=mysqli_query($dbcon,"SELECT id,company_name FROM company");
   if($qry){
	   	$rows=mysqli_num_rows($qry);
	   	if($rows > 0){
	   	    while($arr=mysqli_fetch_assoc($qry)){
	             $output=["id"=>$arr["id"],"cname"=>$arr["company_name"]];
	             $response[]=$output;
	        }
	   	}else{
	   		$response['empty']=['empty'];
	   	}
   }  
   echo json_encode($response);
}
if(isset($_POST["getLastID"])){
$response=array(
   "lastInsertedID"=>0
);
	$tablename=$_POST["tablename"];
	$qry=mysqli_query($dbcon,"SELECT id FROM ".$tablename." ORDER BY id DESC");
	if($qry){
		if(mysqli_num_rows($qry) > 0){
			 $arr=mysqli_fetch_assoc($qry);
       $response["lastInsertedID"]=intval($arr["id"]+1); 
		}else{
			$response["lastInsertedID"]=1;
		}
	}
  echo json_encode($response);
}
if(isset($_POST["workEquipmentcreate"])){
	$qry=mysqli_query($dbcon,"SELECT id,article FROM inventory");
	if($qry){
		$row=mysqli_num_rows($qry);
		if($row > 0){
			 $arr=mysqli_fetch_assoc($qry);
		   $output='<tr>
	            <td>
					<select name="issueItem" style="padding:2px;width:200px;"></select>					
	            </td>
	           	<td>
	                <input type="date" name="issueDate" style="width:200px">
	           	</td>
	        		<td>
	              	<img src="" style="width:150px;height:60px">
	        		</td>
	          	<td>
	              	<img src="" style="width:150px;height:60px">
	          	</td>
	        </tr>';
		}
	
	}

			

}
?>