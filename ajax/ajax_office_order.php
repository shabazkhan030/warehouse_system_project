<?php 
session_start();
if(!isset($_SESSION["isLogedIn"])){
	header("location:../index.php");
}
include "../dbcon/dbcon.php";
include "../func/functions.php";
if(isset($_POST["submitOfficeOrder"])){
	$response=array(
       "status"=>0,
       "message"=>"Oops Something Went Wrong",
       "oid"=>0
	);
    $pid=base64_decode($_POST["pid"])/88888;
    $cid=base64_decode($_POST["cid"])/99999;
    $success=0;
    $error=0;
    $signed_by=0;
    $oid=(isset($_POST["oid"])) ? $_POST["oid"] : 0;
    $sd=(isset($_POST["submittedBy"])) ? $_POST["submittedBy"] : "";
    if(!empty($sd)){
        $approve=0;
        $crowd=0;
        $last_change=date("Y-m-d H:i:s");
        $qry1='';
        if(isset($_POST["oid"])){
            $oid=$_POST["oid"];
           $qry1=mysqli_query($dbcon,"UPDATE office_order SET pid='$pid',last_change='$last_change' WHERE id='$oid'");
        }else{
            $qry1=mysqli_query($dbcon,"INSERT INTO office_order(submitted,cid,pid,last_change) VALUES('$sd','$cid','$pid','$last_change')");
            $oid=mysqli_insert_id($dbcon);    
        }
        if(isset($_POST["signature_puchaser"])){
            if(!empty($_POST["signature_puchaser"])){
                $signPurchaser=$_POST["signature_puchaser"];
                $filePurc="signaturepur_".date("Ymdhis").".png";
                $uploadPath="../img/signature_uploads/";

                file_put_contents($uploadPath.$filePurc,file_get_contents($signPurchaser));
                $sign_on_pur=date("Y-m-d H:i:s");
                $signed_by=$pid;
                $qry2=mysqli_query($dbcon,"UPDATE office_order SET signature_data='$sign_on_pur',sign_purchaser='$filePurc',signed_by='$signed_by' WHERE id='$oid'");
                if($qry2){
                  $success++;
               }else{
                $error++;
               }             
            }
        }
        if(isset($_POST["signature_director"])){
           if(!empty($_POST["signature_director"])){
                $signdir=$_POST["signature_director"];
                $fileDirector="signatureDir_".date("Ymdhis").".png";
                $uploadPath="../img/signature_uploads/";
               file_put_contents($uploadPath.$fileDirector,file_get_contents($signdir));
               $sign_on_dir=date("Y-m-d H:i:s");
               $signed_by=$pid;
            //     
            $qry3=mysqli_query($dbcon,"UPDATE office_order SET signature_closed='$sign_on_dir',sign_director='$fileDirector',signed_by_dir='$signed_by' WHERE id='$oid'");
            //   exit;
              if($qry3){
                  $success++;
                }else{
                  $error++;
                }
           }
        }
        if(isset($_POST["qtyofficeorder"])){
            $qty=$_POST["qtyofficeorder"];
            $size=$_POST["sizeofficeOrder"];
            $eid=$_POST["employeeOfficeorder"];
            $article=$_POST["articleOfficeOrder"];
            $remark=$_POST["remarkofficeOrder"];
            $amount=$_POST["amtofficeOrder"];
            $piecePrice=$_POST["piecePrice"];
            $checbox=json_decode($_POST["chckbxofficeorder"]);

            if(isset($_POST["oid"])){
                $qry=mysqli_query($dbcon,"DELETE FROM office_order_details WHERE order_id='$oid'");
            }
            $qryGetSign=mysqli_query($dbcon,"SELECT sign_purchaser,sign_director FROM office_order WHERE id='$oid'");
            $arr_signature=mysqli_fetch_assoc($qryGetSign);
            for($i=0;$i< count($_POST["qtyofficeorder"]);$i++){
              $article_s=safeInput($dbcon,$article[$i]);
              $qty_s=safeInput($dbcon,$qty[$i]);
              $size_s=safeInput($dbcon,$size[$i]);
              $eid_s=safeInput($dbcon,$eid[$i]);
              $remark_s=safeInput($dbcon,$remark[$i]);
              $amount_s=safeInput($dbcon,$amount[$i]);
              $piecePrice_s=(empty($piecePrice[$i])) ? 0 : safeInput($dbcon,$piecePrice[$i]);
              $chbx_s=safeInput($dbcon,$checbox[$i]);
              if(!empty($article_s) && !empty($qty_s) && $eid_s != ''){ 
                  $qry4=mysqli_query($dbcon,"INSERT INTO office_order_details(order_id,chbx,desired_amount,inventory_article,size,eid,remarks,amt,piece_price,cid) VALUES('$oid','$chbx_s','$qty_s','$article_s','$size_s','$eid_s','$remark_s','$amount_s','$piecePrice_s','$cid')");
                  if($qry4){
                         $crowd=$crowd+$qty_s;
                    //previous   $crowd=$crowd+$qty_s;
                        if($chbx_s == 1){
                            // $approve=$approve+$qty_s;
                            $approve=$approve+intval($amount_s);
                        }
                    $success++;
                  }else{
                    $error++;
                  }
                if(empty($arr_signature["sign_purchaser"]) || empty($arr_signature["sign_director"])){
                    
                }else{  
                   $qrySearchInventory=mysqli_query($dbcon,"SELECT * FROM inventory WHERE article LIKE '%".$article_s."%' AND size LIKE '%".$size_s."%' AND cid='$cid'"); 
                    $num_rows=mysqli_num_rows($qrySearchInventory);
                    if($num_rows > 0){
                      $arr_article=mysqli_fetch_assoc($qrySearchInventory);
                      $inventory_id=$arr_article["id"];
                      
                      $cr=$amount_s+ $arr_article["crowd"];
                      $rr=$amount_s + $arr_article["remaining_item"]; 
                      $updateInventory=mysqli_query($dbcon,"UPDATE inventory SET crowd='$cr',remaining_item='$rr',piece_price='$piecePrice_s' WHERE id='$inventory_id'");
                    }else{
                        $created_on=date("Y-m-d");
                        $insertInventory=mysqli_query($dbcon,"INSERT INTO inventory(article,size,crowd,defect,output_item,remaining_item,piece_price,cid,pid,created_on) VALUES('$article_s','$size_s','$amount_s',0,0,'$amount_s','$piecePrice_s','$cid','$pid','$created_on')");
                    }
                  }               
              }else{
                $error++;
              }
            }
            $qryUpd=mysqli_query($dbcon,"UPDATE office_order SET crowd='$crowd',approved='$approve' WHERE id='$oid'");
        }
        if($error > 0){
           $response["message"]="Oops Created Order Failed.. Error Found ".$error;        
        }else{
           $response["status"]=1;
           $response["oid"]=base64_encode($oid);
           $response["message"]="You have Successfully Created Order.. Error Found ".$error;  
        }
    }else{
       $response["message"]="Please Enter Submitted Date";
    }
  echo json_encode($response);
}

if(isset($_POST["signature"])){
   $response=array(
      "sign_p"=>"",
      "sign_d"=>""
   );
   $oid=$_POST["oid"];
   $qry=mysqli_query($dbcon,"SELECT sign_purchaser,sign_director FROM office_order WHERE id='$oid'");
   if($qry){
      $arr=mysqli_fetch_assoc($qry);

      $response["sign_p"]=$arr["sign_purchaser"];
      $response["sign_d"]=$arr["sign_director"];
   }

   echo json_encode($response);
}
if(isset($_POST["deleteRow"])){
    $response=array(
      "status"=>0,
      "message"=>"Oops Something Went Wrong..!!"
    );
    $odetails=$_POST["odetails"];
    //
    $slct=mysqli_query($dbcon,"SELECT * FROM office_order_details WHERE id='$odetails'");
    $slct_arr=mysqli_fetch_assoc($slct);
    $oid=$slct_arr["order_id"];
    //
    $slct_main=mysqli_query($dbcon,"SELECT crowd,approved FROM office_order WHERE id='$oid'");
    $slct_main_arr=mysqli_fetch_assoc($slct_main);
    //
    $approved=$slct_main_arr["approved"];
    $crowd=$slct_main_arr["crowd"];

    if($slct_arr["chbx"] == 1){
        $crd_main=$crowd-$slct_arr["desired_amount"];
        $appr_main=$approved-$slct_arr["desired_amount"];
        $insrt_main=mysqli_query($dbcon,"UPDATE office_order SET approved='$appr_main',crowd='$crd_main' WHERE id='$oid'");
        if($insrt_main){
           $qry=mysqli_query($dbcon,"DELETE FROM office_order_details WHERE id='$odetails'"); 
            if($qry){
                $response["status"]=1;
                $response["message"]="Successfully removed data";
            }else{
                $response["message"]="Something Went Wrong";
            }
        }
    }else{
        $crd_main=$crowd-intval($slct_arr["desired_amount"]);
        $insrt_main=mysqli_query($dbcon,"UPDATE office_order SET crowd='$crd_main' WHERE id='$oid'");
        if($insrt_main){
           $qry=mysqli_query($dbcon,"DELETE FROM office_order_details WHERE id='$odetails'"); 
            if($qry){
                $response["status"]=1;
                $response["message"]="Successfully removed data";
            }else{
                $response["message"]="Something Went Wrong";
            }
        }
    }
    echo json_encode($response);
}

if(isset($_POST['fetchArticle'])){
    $response=[];
   $input=safeInput($dbcon,$_POST['input']);
   if(!empty($input)){
       $cid=base64_decode($_POST['cid'])/99999;
       $qry=mysqli_query($dbcon,"SELECT * FROM inventory WHERE cid='$cid' AND article LIKE '%".$input."%'");
       if($qry){
        if(mysqli_num_rows($qry) > 0){
            while($arr=mysqli_fetch_assoc($qry)){
                $output=["id"=>$arr['id'],
                "article"=>$arr["article"],
                "size"=>$arr["size"],
                "piece_price"=>$arr["piece_price"]
                ];

                $response[]=$output;
            }
        }else{
            $response[]=null;
        }
       }
   }
   echo json_encode($response);
} 

if(isset($_POST["fetchOfficeOrder"])){
    $response=[];
   $cid=$_POST["cid"];
   $pageno=(isset($_POST["pageno"])) ? $_POST["pageno"] : 1;
   $limit=(isset($_POST["limit"])) ? $_POST["limit"] : 10;
   $start=($pageno * $limit)-$limit;
   $qry=mysqli_query($dbcon,"SELECT * FROM office_order WHERE cid='$cid' LIMIT ".$start.",".$limit);
   if($qry){
        $row=mysqli_num_rows($qry);
        if($row > 0){
            while($arr=mysqli_fetch_assoc($qry)){
                $signPur=$arr["signature_data"];
                $signDir=$arr["signature_closed"];
                $appr=$arr["approved"];
                ($arr["signature_data"] == '' || $arr["signature_data"] == "0000-00-00") ?  $signPur="Offen" : $signPur=date("d.m.Y | H:i",strtotime($arr["signature_data"]));
                ($arr["signature_closed"] == '' || $arr["signature_closed"] == "0000-00-00") ?  $signDir="Offen": $signDir=date("d.m.Y | H:i",strtotime($arr["signature_closed"]));
                if($signDir == 'Offen'){
                    ($arr["approved"] == '' || $arr["approved"] == 0 ) ?  $appr="<span class='bg-red p-5 ml-10 text-light'>Offen</span>" : $appr=$arr["approved"];
                }

                $output=[
                   "id"=>"0".$arr["id"],
                   "submitted_date"=>date("d.m.Y",strtotime($arr["submitted"])),
                   "signPur"=>$signPur,
                   "signDir"=>$signDir,
                   "crowd"=>$arr["crowd"],
                   "appr"=>$appr,
                   "oid"=>base64_encode($arr["id"])
                ];

                $response[]=$output; 
            }
        }else{
            $response[]="empty";
        }
    }
    echo json_encode($response);
}  
if(isset($_POST["page"])){
  $response=[];
  $cid=$_POST["cid"];
  $qry=mysqli_query($dbcon,"SELECT * FROM office_order WHERE cid='$cid'");
  $count=mysqli_num_rows($qry);
  $limit=(isset($_POST["limit"])) ? $_POST["limit"] : 10; 
  $pageno =ceil($count/$limit);
  for($i =1;$i <= $pageno;$i++){
    $output=$i;
    $response[]=$output;
  } 
  echo json_encode($response);
}
 ?>
