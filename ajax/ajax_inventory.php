<?php 
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../index.php");
}
include "../dbcon/dbcon.php";
include "../func/functions.php";

if(isset($_POST["savenewInventory"])){
	$response=array(
      "status"=>0,
      "message"=>"Oops Something Went Wrong"
	);
	$iid=safeInput($dbcon,$_POST["iid"]);
	$article=safeInput($dbcon,$_POST["article"]);
	$size=safeInput($dbcon,$_POST["size"]);
	$crowd=($_POST["crowd"] != '') ? safeInput($dbcon,$_POST["crowd"]) : 0;
	$defect=($_POST["defect"] != '') ? safeInput($dbcon,$_POST["defect"]) : 0;
	$lost=($_POST["lost"] != '') ? safeInput($dbcon,$_POST["lost"]) : 0;
	$output=($_POST["output"] != '') ? safeInput($dbcon,$_POST["output"]) : 0;
	$remaining=$crowd -($defect+$output+$lost);  
	$piecePrice=safeInput($dbcon,$_POST["piecePrice"]);
	$cid=base64_decode($_POST["cid"])/99999;
	$pid=base64_decode($_POST["pid"])/88888;
	$created_on=date("Y-m-d");
	$error=(($crowd - ($defect+$lost)) < $output) ? 1 : 0;
    if($error == 0){
	    if($iid != 0){
	    	$prevQRY=mysqli_query($dbcon,"SELECT crowd,remaining_item FROM inventory WHERE id='$iid'");
	        $prevROW=mysqli_fetch_assoc($prevQRY);
	    	$qry=mysqli_query($dbcon,"UPDATE inventory SET article='$article',size='$size',crowd='$crowd',defect='$defect',lost='$lost',output_item='$output',remaining_item='$remaining',piece_price='$piecePrice',cid='$cid',pid='$pid' WHERE id='$iid'");
	    	if($qry){
	    	$updateQRY=mysqli_query($dbcon,"SELECT crowd,remaining_item FROM inventory WHERE id='$iid'");
	        $updateROW=mysqli_fetch_assoc($updateQRY);   
	        $total_changes=updatedRowinventory($dbcon,$iid,$cid,$pid,$prevROW,$updateROW);
	    		$response["status"]=1;
	    		$response["message"]="Updated Successfully Inventory";
	    	}else{
	    		$response["message"]="Oops Something Went Wrong..!!!";
	    	}
	    }else{
	    	$qry=mysqli_query($dbcon,"INSERT INTO inventory(article,size,crowd,defect,output_item,remaining_item,piece_price,cid,pid,created_on) VALUES('$article','$size','$crowd','$defect','$output','$remaining','$piecePrice','$cid','$pid','$created_on')");
	    	if($qry){
	    		$response["status"]=1;
	    		$response["message"]="Successfully inserted Into Inventory..";
	    	}else{
	    		$response["message"]="Oops Something Went Wrong..!!!";
	    	}
	    }
    }else{
    	$response["message"]="Output cannot be greater than crowd..";
    }
    echo json_encode($response);
}

if(isset($_POST["transportArticle"])){
	$response=array(
		"status"=>0,
		"message"=>"Oops Something Went wrong..!!!"
	);
	$selectComp=$_POST["selectComp"];
	$transport= (!empty($_POST["transport"])) ? $_POST["transport"] : 0;
	$iid=$_POST["iid"];
	$pid=base64_decode($_POST["pid"])/88888;
	$created_on=date("Y-m-d");
	$qry=mysqli_query($dbcon,"SELECT * FROM inventory WHERE id='$iid'");
	if($qry){
		$rows=mysqli_num_rows($qry);
		if($rows > 0){
            $arr=mysqli_fetch_assoc($qry);
            $remainingArticle=$arr["crowd"] -($arr["defect"] + $arr["output_item"]);
            if($remainingArticle >= $transport){
            	$article=$arr["article"];
            	$crowd=$arr["crowd"]; 
            	$output_item=$arr["output_item"]; 
            	$remaining_item=$arr["remaining_item"]; 
            	$size=$arr["size"];
            	$def=$arr["defect"]; 
            	$piecePrice=$arr["piece_price"]; 
            	$setCrowd=$crowd-$transport;
            	$setRemaining=$setCrowd-($output_item + $def);
                if($transport != 0){
	            	$update=mysqli_query($dbcon,"UPDATE inventory SET crowd='$setCrowd',output_item='$output_item',remaining_item='$setRemaining' WHERE id='$iid'");
	            	if($update){
	            		$qrySelect=mysqli_query($dbcon,"SELECT * FROM inventory WHERE cid='$selectComp' AND article LIKE '%".$article."%' AND size LIKE '%".$size."%'");
	            		if(mysqli_num_rows($qrySelect) > 0){
	            			$arrselect=mysqli_fetch_assoc($qrySelect);
	            			$id=$arrselect["id"];
	            			//before Transport
	            			$crowdbt=$arrselect["crowd"]+$transport;
	            			$remainingbt=$arrselect["remaining_item"]+$transport;
	            			$updateInventory=mysqli_query($dbcon,"UPDATE inventory SET crowd='$crowdbt',remaining_item='$remainingbt' WHERE id='$id'");
	            			if($updateInventory){
			                	$response["status"]=1;
		                        $response["message"]="Successfully Transported Data..!!!";
			                }else{
			                	$response["message"]="Something Went Wrong While Inserting inventory data";
			                }                               
	            		}else{
		            		$insert=mysqli_query($dbcon,"INSERT INTO inventory(article,size,crowd,defect,output_item,remaining_item,piece_price,cid,pid,created_on) VALUES('$article','$size','$transport','0','0','$transport','$piecePrice','$selectComp','$pid','$created_on')");
			                if($insert){
			                	$response["status"]=1;
		                        $response["message"]="Successfully Transported Data..!!!";
			                }else{
			                	$response["message"]="Something Went Wrong While Inserting inventory data";
			                }
	            		}
	            	}else{
	            		$response["message"]="Something Went Wrong While updating inventory data";
	            	}
                }else{
                	$response["message"]="Please Enter Transport Quantity..";
                }
            }else{
            	$response["message"]="remaining Item Must be greater than transport quantity.";
            }
		}else{
			$response["message"]="Sorry Article not found Please try Later..";
		}
	}
	echo json_encode($response);
}

 ?>