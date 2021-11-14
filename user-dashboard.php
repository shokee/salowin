<?php include("header.php"); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCm-V7-i7-hH5pI0nMxjb6l064Ma30xt-Q"></script>
<!-- Bootstrap 3.3.7 -->
<style>   .ui-autocomplete { z-index:2147483647; }</style>
<?php
//Check whether user is logged in or not

 if(isset($_SESSION['userid'])=="")
{
    header("location:login.php");
    exit;
}
?>
<?php 
/* if(isset($_REQUEST['action']) && $_REQUEST['action']=='UpdateAddres'){
    $addres_id = $_POST['addres_id'];
        $fnane = $_POST['fnane'];
        $lanme = $_POST['lanme'];
        $cname = $_POST['cname'];
        $zcode = $_POST['zcode'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $string="user_id='$user_ip',first_name='$fnane',last_name='$lanme',city_id='$cname',pincode='$zcode',email='$email',number='$phone',address='$address',created_date=NOW()";
        $dbf->updateTable("address",$string,"address_id='$addres_id'");
        header("Location:user-dashboard.php");exit;
}*/

 if(isset($_REQUEST['opeartion']) && $_REQUEST['opeartion']=='ReturnProducts'){
$order_id = $_POST['prod_ret_id'];
$reason = $_POST['reason'];
$dbf->updateTable("orders","status='6',reason='$reason'","orders_id='$order_id'");
header("Location:user-dashboard.php");
 }


//Cancel Order
if(isset($_REQUEST['action']) && $_REQUEST['action']=='CancelOrder'){
    $cancel_order_id =$_POST['cancel_order_id'];
    $dbf->updateTable("orders","status='8'","order_id='$cancel_order_id'");
    $Ordder_pay=$dbf->fetchSingle("orders",'*',"order_id='$cancel_order_id'");
    $User_Wallet=$dbf->fetchSingle("user",'wallet',"id='$Ordder_pay[user_id]'");
    if(strtolower($Ordder_pay['payment_mode'])==strtolower('cod& Wallet Paid')){
        $amount= $Ordder_pay['wallet']+$User_Wallet['wallet'];
        $dbf->updateTable("user","wallet='$amount'","id='$Ordder_pay[user_id]'");
        $string = "amount='$Ordder_pay[wallet]',remark='Cancel Order',user_id='$_SESSION[userid]',date=NOW()";
        $dbf->insertSet("wallet_histru",$string);
    }
   if(strtolower($Ordder_pay['payment_mode'])==strtolower('online& Wallet Paid')){
        
        $total_amnt=0;
        foreach($dbf->fetchOrder("orders","order_id='$cancel_order_id'","","price,qty","") as $CancelAmnt){
        $total_amnt+=$CancelAmnt['qty']*$CancelAmnt['price'];
        }

        $amount= ($total_amnt+$Ordder_pay['shipping_charge'])-$Ordder_pay['coupon_amnt'];
        $hist_amnt= $amount+$User_Wallet['wallet'];
        $dbf->updateTable("user","wallet='$hist_amnt'","id='$Ordder_pay[user_id]'");
        $string = "amount='$amount',remark='Cancel Order',user_id='$_SESSION[userid]',date=NOW()";
        $ins_id=$dbf->insertSet("wallet_histru",$string);

    }if(strtolower($Ordder_pay['payment_mode'])==strtolower('online')){
         
        $total_amnt=0;
        foreach($dbf->fetchOrder("orders","order_id='$cancel_order_id'","","price,qty","") as $CancelAmnt){
        $total_amnt+=$CancelAmnt['qty']*$CancelAmnt['price'];
        }

        $amount= ($total_amnt+$Ordder_pay['shipping_charge'])-$Ordder_pay['coupon_amnt'];
        $hist_amnt= $amount+$User_Wallet['wallet'];
        $dbf->updateTable("user","wallet='$hist_amnt'","id='$Ordder_pay[user_id]'");
        $string = "amount='$amount',remark='Cancel Order',user_id='$_SESSION[userid]',date=NOW()";
        $ins_id=$dbf->insertSet("wallet_histru",$string);
    }

    header("Location:user-dashboard.php?msg=calcel_order");
}



if(strtolower($Ordder_pay['payment_mode'])=='online& Wallet Paid'){
  $total_amnt=0;
  foreach($dbf->fetchOrder("orders","order_id='$order_id'","","price,qty","") as $CancelAmnt){
    $total_amnt+=$CancelAmnt['qty']*$CancelAmnt['price'];
  }

   $amount= ($total_amnt+$Ordder_pay['shipping_charge'])-$Ordder_pay['coupon_amnt'];
    $hist_amnt= $amount+$User_Wallet['wallet'];
    $dbf->updateTable("user","wallet='$hist_amnt'","id='$Ordder_pay[user_id]'");
    $string = "amount='$amount',remark='Cancel Order',user_id='$Ordder_pay[user_id]',date=NOW()";
    $ins_id=$dbf->insertSet("wallet_histru",$string);
}if(strtolower($Ordder_pay['payment_mode'])=='online'){
  $total_amnt=0;
  foreach($dbf->fetchOrder("orders","order_id='$order_id'","","price,qty","") as $CancelAmnt){
    $total_amnt+=$CancelAmnt['qty']*$CancelAmnt['price'];
  }

     $amount= ($total_amnt+$Ordder_pay['shipping_charge'])-$Ordder_pay['coupon_amnt'];

     $hist_amnt= $amount+$User_Wallet['wallet'];
    $dbf->updateTable("user","wallet='$hist_amnt'","id='$Ordder_pay[user_id]'");
    $string = "amount='$amount',remark='Cancel Order',user_id='$Ordder_pay[user_id]',date=NOW()";
    $ins_id=$dbf->insertSet("wallet_histru",$string);
}


//Cancel Order
?>
  

<div class="uk-background-muted uk-height-viewport">
<div class="uk-container ">
<div class="uk-margin-top uk-margin-bottom">
<div class="uk-card uk-card-default uk-card-body uk-padding-small uk-margin-remove-top uk-margin-small-bottom">
<ul class="uk-breadcrumb">
    <li><a href="#"><span uk-icon="home"></span></a></li>
    <li><span>User Dashboard</span></li>
</ul>
</div>

<div class="uk-grid-small" uk-grid>
            <div class="uk-width-1-4@m " >
           
                <ul class="uk-tab-left uk-list-divider uk-background-default" uk-tab="connect: #component-tab-left; animation: uk-animation-fade ">
                    <li><a href="#"> My Order</a></li>
                    <li><a href="#"> Profile</a></li>
                    <li><a href="#"> Change Password </a></li>
                     <li><a href="#"> Address </a></li>
                     <li><a href="#"> Wallet </a></li>
                    <li><a href="#"> Logout </a></li>
                </ul>
            </div>
            <div class="uk-width-expand@m">
                <ul id="component-tab-left" class="uk-switcher ">
                   <li>
                    
                    <div class="uk-card uk-card-default uk-card-body uk-padding-remove">
                    <div class="uk-panel uk-background-muted">
                    <div class="uk-grid uk-grid-collapse uk-child-width-expand@m">
                    
                    </div>
                </div>
                <div class="uk-overflow-auto">
                <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='calcel_order'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Order Cancel Successfully,If You Pay Through Online Or Wallet Then ,amount refund to your wallet.</p>
</div>

<?php }?>
 <table class="uk-table uk-table-divider uk-background-default uk-text-left uk-table-small uk-table-middle uk-card-default">
  <tr class="uk-background-muted">
    <th scope="col">Sl No</th>
    <th scope="col">Order Id </th>
    <th scope="col">Order Date</th>
     <th scope="col">Payment </th>
    <th scope="col">Status </th>
    <th scope="col">View Details </th>
    <th scope="col">Return</th>
  </tr>
  
 <?php
    $j=1;
    $Order_status_array=array();
     foreach($dbf->fetchOrder("orders","user_id='$user_ip'"," created_date DESC ","","order_id") as $order){
        unset($Order_status_array);
        foreach($dbf->fetchOrder("orders","order_id='$order[order_id]' ","","status","") as $OrderStatus){
        $Order_status_array=array($Order_status_array,$OrderStatus['status']);
        $Order_staues=implode(',',$Order_status_array);
        }
    ?>
    
  <tr>
    <td><?php echo $j++;?></td>
    <td><?= $order['order_id']?></td>
    <td><?= date('d.m.Y h:i:s a',strtotime($order['created_date']))?></td>
    <td><?= $order['payment_mode']?></td>
    <td>
    <?php 
    if($order['status']=='0'){?>
        <label  class="uk-label uk-label-warning uk-width-1-1 uk-text-center"  >New Order</label>
    <?php }else if($order['status']=='1'){?>
        <label  class="uk-label uk-label-primary uk-width-1-1 uk-text-center"  >Order Received</label>
    <?php } else if($order['status']=='2'){?>
        <label  class="uk-label uk-label-primary uk-width-1-1 uk-text-center"  >Processing</label>
    <?php }else if($order['status']=='3'){?>
        <label  class="uk-label uk-label-primary uk-width-1-1 uk-text-center"  >Shiped</label>
    <?php }else if($order['status']=='4'){?>
        <label  class="uk-label uk-label-success uk-width-1-1 uk-text-center"  >Completed </label>
    <?php } else if($order['status']=='5'){?>
         <label  class="uk-label uk-label-danger uk-width-1-1 uk-text-center" >Returned</label>
     <?php } else if($order['status']=='6'){?>
        <label  class="uk-label uk-label-danger uk-width-1-1 uk-text-center" >Returned Process</label>
     <?php } else if($order['status']=='7'){?>
        <button  class="uk-button uk-button-small uk-button-danger" >Returned Cancel</button>
    <?php }else if($order['status']=='8'){?>
        <label  class="uk-label uk-label-danger uk-width-1-1 uk-text-center" >Canceled</label>
    <?php }else if($order['status']=='9'){?>
        <label  class="uk-label uk-label-danger uk-width-1-1 uk-text-center" >Transaction Failed</label>
    <?php }else if($order['status']=='10'){?>
        <label  class="uk-label uk-label-danger uk-width-1-1 uk-text-center" >Shiped</label>
    <?php } else{?>
        <label  class="uk-label uk-label-danger uk-width-1-1 uk-text-center" >Processing</label>
         <?php }?>
    
    </td>
    <td><button class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-example<?= $order['order_id']?>" > View Details</button>
    
    
    
    <div id="modal-example<?= $order['order_id']?>" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
    <div class="uk-grid">
        <div class="uk-width-auto">Order Id : <?= $order['order_id']?></div>
        <div class="uk-width-expand"></div>
        <div class="uk-width-auto">Date: <?= date('d.m.Y',strtotime($order['created_date']))?></div>
    </div>
    <hr />
        <h4 class="uk-text-center uk-margin-remove-top">Order Summery</h4>
        <div  style="border:solid 1px #ccc;">
        <table class="uk-table uk-table-small uk-table-divider">
        <tr class="uk-background-muted">
            <th>Sl No</th>
            <th>Product Details</th>
            <th> Price</th>
            <th> Qty</th>
            <th> Total</th>
        </tr>
        
         <?php
    $i=1;
     foreach($dbf->fetchOrder("orders","order_id='$order[order_id]' ","orders_id DESC","","") as $singleorder){
    $totalqty= $dbf->fetchSingle("orders",'SUM(qty) as total_qty',"order_id='$order[order_id]'");
    $prototal= $dbf->fetchSingle("orders",'SUM(qty*price) as pro_total',"order_id='$order[order_id]'");
       $ReturnAmnt= $dbf->fetchSingle("orders",'SUM(price) as ret_amnt',"order_id='$order[order_id]' AND status='5'");
    ?>
    
    <tr>
    <td><?php echo $i;?></td>
    <td><?= $singleorder['ordername']?><?php if($singleorder['status']=='5'){?><span style="color:red;font-size: 20px;">(Returned)</span><?php }?></td>
    <td><?= $singleorder['price']?></td>
    <td><?= $singleorder['qty']?></td>
  <td><?php if($singleorder['status']=='5'){?><span style="color:red;font-size: 20px;font-weight: bold;">-</span><?php }?>
    <?php echo $singleorder['price']* $singleorder['qty']; ?>.00</td>
    </tr>
    <?php $i++; } ?>
    <tr>
    <td></td>
    <td></td>
    <td>TOTAL</td>
    <td><b><?= $totalqty['total_qty']?></b></td>
    <td><b><?= $prototal['pro_total']-$ReturnAmnt['ret_amnt']?></b></td>
    </tr>
    
<?php if($singleorder['coupon_amnt']!=0){?>
      <tr>
    <td></td>
    <td></td>
    <td>Coupon Amount(<?= $singleorder['coupon_code']?>):</td>
    <td></td>
    <td><b>-<?= number_format($singleorder['coupon_amnt'],2)?></b></td>
    </tr>
  <?php }?>
     <?php if($singleorder['wallet']!=0){?>
     <tr>
    <td></td>
    <td></td>
    <td>WALLET:</td>
    <td></td>
    <td><b>-<?= number_format($singleorder['wallet'],2)?></b></td>
    </tr>
  <?php }?>
     <tr>
    <td></td>
    <td></td>
    <td>SHIPPING CHARGE</td>
    <td><b></b></td>
    <td><b><?= $order['shipping_charge']?></b></td>
    </tr>
       <tr>
    <td></td>
    <td></td>
    <td><span class="uk-text-danger">SUB TOTAL</span></td>
    <td><b></b></td>
      <td><b><?php echo number_format($prototal['pro_total']+ $singleorder['shipping_charge']-$singleorder['wallet']-$ReturnAmnt['ret_amnt']-$singleorder['coupon_amnt'],2); ?></b></td>
    </tr>
    </table>
        </div>
        <?php $address = $dbf->fetchSingle("address",'*',"address_id='$order[address_id]'");   ?>
        <?php $city = $dbf->fetchSingle("city",'*',"city_id='$address[city_id]'");
        $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$address[pincode]'");
         ?>
        <h5>Address</h5>
       
  <p> 
  Name: <?php echo $address['first_name'];?> <?php echo $address['last_name'];?><br />
  Contact No: <?php echo $address['number'];?> <br />
  Email : <?php echo $address['email'];?> <br />
  Address: <?php echo $address['address'];?>, <br />
  <?php echo $city['city_name'];?>,  Pin:  <?php echo $pincode['pincode'];?> <br />
  </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
            <?php if($order['status']<3){?>
            <button class="uk-button uk-button-danger" type="button" onclick="CancelOrder('<?= $order[order_id]?>')">Cancel Order</button>
        <?php }?>
        </p>
    </div>
</div>
    
    </td>
     <?php  

                
     if(in_array('4', explode(',',$Order_staues))){?>
  <td><button class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-example2<?= $order['order_id']?>" > Return</button>
    
    
    
    <div id="modal-example2<?= $order['order_id']?>" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
    <div class="uk-grid">
      <div class="uk-width-auto">Order Id : <?= $order['order_id']?></div>
      <div class="uk-width-expand"></div>
      <div class="uk-width-auto">Date: <?= date('d.m.Y',strtotime($orders['created_date']))?></div>
    </div>
    <hr />
      <h4 class="uk-text-center uk-margin-remove-top">Order Summery</h4>
        <div  style="border:solid 1px #ccc;">
        <table class="uk-table uk-table-small uk-table-divider">
        <tr class="uk-background-muted">
          <th>Sl No</th>
            <th>Product Details</th>
            <th> Price</th>
            <th> Qty</th>
            <th> Total</th>
            <th> Return</th>
        </tr>
        
         <?php
  $i=1;
   foreach($dbf->fetchOrder("orders","order_id='$order[order_id]' ","orders_id DESC","","") as $singleorder){
  $totalqty= $dbf->fetchSingle("orders",'SUM(qty) as total_qty',"order_id='$order[order_id]'");
  $prototal= $dbf->fetchSingle("orders",'SUM(qty*price) as pro_total',"order_id='$order[order_id]'");
  
  ?>
    
    <tr>
    <td><?php echo $i;?></td>
    <td><?= $singleorder['ordername']?></td>
    <td><?= $singleorder['price']?></td>
    <td><?= $singleorder['qty']?></td>
    <td><?php echo $singleorder['price']* $singleorder['qty']; ?>.00</td>
    <td><b>
        <?php if($singleorder['status']=='4'){?>
        <button class="uk-button uk-button-default uk-button-small" type="button" uk-toggle="target: #modal-return<?=$singleorder[orders_id]?>">Return</button>
    <?php }else if($singleorder['status']=='6'){?>
        <button class="uk-button uk-button-default uk-button-small" type="button">Return Process</button>
    <?php } else if($singleorder['status']=='7'){?>
           <button class="uk-button uk-button-default uk-button-small" type="button">Return Canceled</button>
    <?php }else{?>
          <button class="uk-button uk-button-default uk-button-small" type="button">Return Approved</button>
    <?php }?>
    </b>
<div id="modal-return<?= $singleorder[orders_id]?>" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h3 >Reason For Return <?= $singleorder['ordername']?></h3>
        <form action="" method="post">
            <input type="hidden" name="prod_ret_id" value="<?= $singleorder['orders_id'] ?>">
            <textarea placeholder="Enter Your Reason For  Return" class="uk-textarea" name="reason" required=""></textarea>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" name="opeartion" value="ReturnProducts">Save</button>
        </p>
        </form>
    </div>
</div>
    </td>
    </tr>
    <?php $i++; }?>
    
    
     
    
    
    </table>
        </div>

        
    </div>
</div>
    
    </td>
<?php }else{?>
    <td></td>
<?php }?>

  </tr>
  <?php $i++; } ?>
  
  </table>
  
  
            </div>
   <hr />  
   
   
</div>
</li>
                    <li>
                        <div class="uk-card uk-card-default uk-card-body">
                        
                     
<?php 
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='updateuser'){
    
    if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
    
        $fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
        $source_path1="admin/images/".$fname1;
        
        $destination_path1="admin/images/thumb/".$fname1;   
        $imgsize1 = getimagesize($source_path1);        
        $new_height1 = 400;
        $new_width1 = 400;      
        $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");                       
        move_uploaded_file($_FILES['img']['tmp_name'],"admin/images/".$fname1);
        
        if($_FILES['img']['type'] == "image/JPG" || $_FILES['img']['type'] == "image/JPEG" || $_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type']=='image/jpeg' ){
            //for small                
            $srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
            ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
            ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
        }else if($_FILES['img']['type'] == "image/gif" || $_FILES['img']['type'] == "image/GIF"){  
            //for small          
            $srcimg1 = imagecreatefromgif($source_path1);
            ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
            ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
        }else if($_FILES['img']['type'] == "image/png" || $_FILES['img']['type'] == "image/PNG"){ 
             //for small          
            $srcimg1 = imagecreatefrompng($source_path1);
            ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
            ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
        }
    }
    
    //@unlink("../banner-img/".$fname1);
    $fname=$dbf->checkXssSqlInjection($_REQUEST['fname']);
    $email=$dbf->checkXssSqlInjection($_REQUEST['email']);
    $contact=$dbf->checkXssSqlInjection($_REQUEST['contact']);
   
    
   
    $city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
    $pin=$dbf->checkXssSqlInjection($_REQUEST['pin']);
    $pagesId=$dbf->checkXssSqlInjection($_REQUEST['pagesId']);
    
    
    if($fname1!=''){
    $string="full_name='$fname', email='$email', contact_no='$contact',profile_image='$fname1', created_date=NOW()";
    }else{
        $string="full_name='$fname', email='$email', contact_no='$contact',created_date=NOW()";
        }
    $dbf->updateTable("user",$string,"id='$pagesId'");
    
    header('Location:user-dashboard.php?msg=success');
}

?>


          <?php            
$profile=$dbf->fetchSingle("user",'*',"id='$_SESSION[userid]'");
//print_r($profile);exit;
?>         <form action="" method="post" enctype="multipart/form-data"  >      
         <div class="uk-grid uk-grid-small">
    <div class=" uk-width-1-1@m"><h5 class="uk-heading-line"><span>Personal Information</span></h5></div>
    <input class="uk-input" type="hidden" name="pagesId" id="fname" value="<?php echo $profile['id'];?>" >
    <div class=" uk-width-1-2@m uk-margin-small-bottom">
        <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon" uk-icon="icon: user"></span>
            <input class="uk-input" type="text" name="fname" id="fname" value="<?php echo $profile['full_name'];?>" >
        </div>
    </div>
    <div class=" uk-width-1-2@m uk-margin-small-bottom">
        <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon" uk-icon="icon: mail"></span>
            <input class="uk-input" type="text" name="email" id="email" value="<?php echo $profile['email'];?>">
        </div>
    </div>
    <div class=" uk-width-1-2@m uk-margin-small-bottom">
        <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon" uk-icon="icon: receiver"></span>
            <input class="uk-input" type="text" name="contact" id="contact" value="<?php echo $profile['contact_no'];?>">
        </div>
    </div>
    <div class=" uk-width-1-1@m"><h5 class="uk-heading-line"><span>Other Information</span></h5></div>
   
    <div class="uk-width-1-2">
    <div class="uk-inline uk-width-1-1">
     <label class="uk-margin-small-bottom"><br />Upload Profile Image</label>
        <div class="js-upload uk-placeholder uk-text-center">
    <span uk-icon="icon: cloud-upload"></span>
    <div uk-form-custom>
        <input type="file" name="img" accept="image/*">
        <span class="uk-link">selecting one</span>
    </div>
</div>
    </div>
    </div>
    <div class="uk-width-1-2">
         <?php if($profile['profile_image']<>''){?>
                 <img src="admin/images/thumb/<?php echo $profile['profile_image'];?> "   width="150" height="150" >
                 <?php }else{?>
                 <img src="admin/images/default.jpg?> "  width="150" class="rounded-circle" alt="User Image"  >
                 <?php }?>
    </div>
  

    </div>
            
    
   <button class="uk-button uk-button-small uk-button-danger" type="submit" name="submit" value="updateuser"> Update</button>
   
   </form>
                        </div>
                    </li>
                    
                    
                    <li>
                        <div class="uk-card uk-card-default uk-card-body" >
                                 
    <h4>Change  Password </h4>
    <span id="customerSucces" class="uk-text-success"></span>
    <span id="customerError" class="uk-text-danger"></span>

    <hr />
    <form accept="" method="post" id="ChanPasswordFrm"  action="">
    <input type="hidden" name="user_id" value="<?= $user_ip?>">
    <input type="hidden" value="UpdatePassword" name="opeartions">
    <div class="uk-grid uk-grid-small ">
    
            <div class=" uk-width-1-2@m uk-margin-small-bottom">
        <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon" uk-icon="icon: lock"></span>
            <input class="uk-input" type="password" placeholder="Enter Password" name="password" required>
        </div>
    </div>
    
             <div class=" uk-width-1-2@m uk-margin-small-bottom">
        <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon" uk-icon="icon: lock"></span>
            <input class="uk-input" type="password" placeholder=" New Password" name="newpassword" required>
        </div>
    </div>

     <div class=" uk-width-1-2@m uk-margin-small-bottom">
        <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon" uk-icon="icon: lock"></span>
            <input class="uk-input" type="password" placeholder=" Conform Password" name="confipassword" required>
        </div>
    </div>
    </div>
       <button class="uk-button uk-button-primary"> Update Password</button>
     </form>
 
                        </div>
                    </li>
                    <li>
                                    
    <div class="uk-card uk-card-default uk-card-body" >
                                 
    <h4>Address:</h4>
    <span id="delResp" class="uk-text-success"></span>
    <hr />
    
    <div class="uk-grid uk-grid-small ">
     
       <div class="addesss_id" id="UpdateAddres">
    <?php foreach($dbf->fetchOrder("address","user_id='$user_ip' AND is_delte='0'","address_id DESC","","")as $addres){ 
    $city=$dbf->fetchSingle("city",'*',"city_id='$addres[city_id]'");
    $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$addres[pincode]'");
    ?>
    <div class="uk-card uk-card-body uk-background-muted uk-card-small uk-margin-bottom " id="delAddres<?= $addres['address_id']?>">
        <?= $addres['first_name'].' '.$addres['last_name'].','.$addres['email'].',<br>'.$addres['number'].','.$city['city_name'].','.$addres['address'].','.$pincode['pincode']?>
        <!-- <button class="btn btn-primary"uk-toggle="target: #modal-address<?= $addres['address_id']?>">Edit</button> -->
         <button class="btn btn-danger" type="button" onclick="addresDelte(<?=$addres['address_id']?>)">Delete</button>
      </div>    
     <?php }?>
      </div>
    </div>
    
    <button class="uk-button uk-button-primary" uk-toggle="target: #modal-new-address">Add More Address</button>
 <div id="modal-new-address" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <div class="uk-grid">
       <h3>Address Add:</h3>
     
       <span id="addrcustomerSucces" class="uk-text-success"></span>
       <span id="addrcustomerError" class="uk-text-danger"></span>
        <form action="" method="post" id="AddressFrm">
            <input type="hidden" name="operations" value="AddAddres">
            <input type="hidden" name="user_ip" value="<?= $user_ip?>">
            <div class="uk-grid uk-child-width-1-2 uk-grid-small "id="AddNewAddres">
               <div>
                <input type="text" placeholder="First Name" class="uk-input uk-margin-bottom" required="" name="fnane" id="fname" />
               </div>
               <div>
                <input type="text" placeholder="Last Name" class="uk-input uk-margin-bottom" required="" name="lanme" id="lname"/>
               </div>
               <div>
               
                <select class="uk-select uk-margin-bottom" required=""  name="cname" id="cname" onchange="SelectOnPin(this.value)">
                    <option value="">--Select City--</option>
                    <?php foreach($dbf->fetchOrder("city","","","","")as $city){?>
                    <option value="<?= $city['city_id']?>"><?= $city['city_name']?></option>
                <?php }?>
                </select>
               </div>
               
               <div>
    
                <select class="uk-select" required="" name="zcode" id="zcode" required>
                     <option value="">--Select Zipcode--</option>
                   
                </select>
               </div>
               
                <div>
                <input type="email" placeholder="Email Id" class="uk-input uk-margin-bottom" required name="email"  id="email" required/>
               </div>
               
                <div>
                <input type="tel" placeholder="Phone" class="uk-input uk-margin-bottom" required name="phone" id="phone" required/>
               </div>
               
                <!-- <div class="uk-width-1-1">
                <textarea class="uk-textarea uk-margin-bottom" placeholder="Address" required name="address" id="address"></textarea>

               </div> -->
                
                <div class="uk-width-1-1">    
                <input type="text" id="search_location" class="uk-input search_addr" placeholder="Search Your Location" required>
                <div id="myMap" style="width: 100%; height:200px;"></div>
                    <input type="hidden" class="search_addr"  required name="address">
                    <input type="hidden" class="search_latitude"  required name="lat">
                    <input type="hidden" class="search_longitude" required name="lng">
               
                </div>
               
             
                  <div class="uk-width-1-1">
              <button class="btn btn-primary" name="action" value="AddAddres">Add</button>
          </div>
               </div>
        </form>
    </div>
    </div>
</div>



                        </div>
                       
                    </li>
                      <li>
                        <div class="uk-card uk-card-default uk-card-body" >
                                 
    <h4>Wallet: </h4>
    <hr />
    
    <div class="uk-grid uk-grid-small ">
    <button class="btn btn-primary" data-toggle="modal" data-target="#walletHistory<?= $user_ip?>">Amount:&#8377;<?= number_format($profile['wallet'],2)?></button>
  
  <div class="modal fade" id="walletHistory<?= $user_ip?>" tabindex="-1" role="dialog" aria-labelledby="walletHistory" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Wallet History</h5>
      </div>
      <div class="modal-body">
      <table class="table table-bordered table-striped" >
                <thead>
                <tr>
                 <th>Sl No.</th>
                  <th>Amount</th>
                  <th>Payment Type</th>
                  <th>Remark</th>
                  <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php    
                $j=1;
                foreach($dbf->fetchOrder("wallet_histru","user_id='$user_ip'","wallet_histru_id ","","") as $Payment_history){?>
                <tr>
                  <td><?= $j++?></td>
                  <td>Rs.<?=   $Payment_history['amount'] ?></td>
                  <td><?php if($Payment_history['pay_type']==1){ echo"Credit"; }else{ echo"Debit";} ?></td>
                  <td><?=   $Payment_history['remark'] ?></td>
                  <td><?=   date('d-m-Y h:m:s a' ,strtotime($Payment_history['date'])) ?></td>
                </tr>
                <?php }?>
                </tbody>
                <thead>
                <tr>
                 <th>Sl No.</th>
                  <th>Amount</th>
                  <th>Payment Type</th>
                  <th>Remark</th>
                  <th>Date</th>
                </tr>
                </thead>
        
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    </div>
    
                        </div>
                    </li>
                    <li>
                    <div class="uk-card uk-card-body uk-card-default">
                    Are you sure want to logout ?
                    <a href="logout.php" class="uk-button uk-button-danger">Logout</a>
                   
                    </div>
                    </li>
                </ul>
            </div>
        </div>
</div>
</div>

</div>

<script type="text/javascript">
function SelectOnPin(arg){
  var url="getAjax.php";
  $.post(url,{"choice":"changPin","value":arg},function(res){
 $('#zcode').html(res);
// alert(res)
});
}

function CancelOrder(arg){
         
    $("#cancel_order_id").val(arg);
    var conf=confirm("Are you sure want to Cancel This Order.");
    if(conf){
       $("#Calce_order_frm").submit();
    }

}
</script>
<form name="Calce_order_frm" id="Calce_order_frm" action="" method="post">
                    <input type="hidden" name="action"  value="CancelOrder">
                    <input type="hidden" name="cancel_order_id" id="cancel_order_id" value="">
                  </form>


<form name="frm_addres" id="frm_addres" action="" method="post">
                    <input type="hidden" name="action"  value="DleteAdres">
                    <input type="hidden" name="addres_id" id="addres_id" value="">
                  </form>

                
<?php include("footer.php"); ?>

<script>
var geocoder;
var map;
var marker;

/*
 * Google Map with marker
 */
$(document).ready(function () {
function initialize() {
    var initialLat = $('.search_latitude').val();
    var initialLong = $('.search_longitude').val();
    initialLat = initialLat?initialLat:20.5937;
    initialLong = initialLong?initialLong:78.9629;

    var latlng = new google.maps.LatLng(initialLat, initialLong);
    var options = {
        zoom: 18,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("myMap"), options);

    geocoder = new google.maps.Geocoder();

    marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: latlng
    });

    google.maps.event.addListener(marker, "dragend", function () {
        var point = marker.getPosition();
        map.panTo(point);
        geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                marker.setPosition(results[0].geometry.location);
                $('.search_addr').val(results[0].formatted_address);
                $('.search_latitude').val(marker.getPosition().lat());
                $('.search_longitude').val(marker.getPosition().lng());
            }
        });
    });

}


    //load google map
    initialize();
    
    /*
     * autocomplete location search
     */
 

    var PostCodeid = '#search_location';
    $(function () {
        $(PostCodeid).autocomplete({
            source: function (request, response) {
                geocoder.geocode({
                    'address': request.term
                }, function (results, status) {
                    response($.map(results, function (item) {
                        return {
                            label: item.formatted_address,
                            value: item.formatted_address,
                            lat: item.geometry.location.lat(),
                            lon: item.geometry.location.lng()
                        };
                    }));
                });
            },
            select: function (event, ui) {
                $('.search_addr').val(ui.item.value);
                $('.search_latitude').val(ui.item.lat);
                $('.search_longitude').val(ui.item.lon);
                var latlng = new google.maps.LatLng(ui.item.lat, ui.item.lon);
                marker.setPosition(latlng);
                initialize();
            }
        });
    });
    
    /*
     * Point location on google map
     */
    $('.get_map').click(function (e) {
        var address = $(PostCodeid).val();
        geocoder.geocode({'address': address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                marker.setPosition(results[0].geometry.location);
                $('.search_addr').val(results[0].formatted_address);
                $('.search_latitude').val(marker.getPosition().lat());
                $('.search_longitude').val(marker.getPosition().lng());
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
        e.preventDefault();
    });

    //Add listener to marker for reverse geocoding
    google.maps.event.addListener(marker, 'drag', function () {
        geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('.search_addr').val(results[0].formatted_address);
                    $('.search_latitude').val(marker.getPosition().lat());
                    $('.search_longitude').val(marker.getPosition().lng());
                }
            }
        });
    });

   // makes sure the whole site is loaded 
        $('#prestatus').fadeOut(); // will first fade out the loading animation 
        $('#divpreloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
        $('body').delay(350).css({'overflow':'visible'});
});
</script>

<!-- Change Password -->
<script type="text/javascript">
    $(document).ready(function (e) {
 $("#ChanPasswordFrm").on('submit',(function(e) {
    $("#divpreloader").css("display", "block");

  e.preventDefault();
  $.ajax({
   url: "getAjaxx.php",
   type: "POST",
   data:  new FormData(this),
   contentType: false,
         cache: false,
   processData:false,
   beforeSend : function()
   {
    //console.log(this);
   },
   success: function(res)
      {
          
        // makes sure the whole site is loaded 
        $('#prestatus').fadeOut(); // will first fade out the loading animation 
        $('#divpreloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
        $('body').delay(350).css({'overflow':'visible'});
       

        document.getElementById("customerSucces").innerHTML="";
          document.getElementById("customerError").innerHTML="";
        // alert(res);
       if(res=='succes'){
 document.getElementById("ChanPasswordFrm").reset();
document.getElementById("customerSucces").innerHTML="Password Reset Successfully.";
// location.reload();
    }else{
 if(res=='pwderr'){
  document.getElementById("customerError").innerHTML="Wrong Password Entered.";
}else{
document.getElementById("customerError").innerHTML="Confirmation Password Not Match.";
 }
}
      },
     error: function(e) 
      {
    //$("#err").html(e).fadeIn();
      }          
    });
 }));
});


</script> 
<!-- Change Password -->


<!-- Address Change -->
<!-- Change Password -->
<script type="text/javascript">
    $(document).ready(function (e) {
 $("#AddressFrm").on('submit',(function(e) {
    $("#divpreloader").css("display", "block");

  e.preventDefault();
  $.ajax({
   url: "getAjaxx.php",
   type: "POST",
   data:  new FormData(this),
   contentType: false,
         cache: false,
   processData:false,
   beforeSend : function()
   {
    //console.log(this);
   },
   success: function(res)
      {
          
        // makes sure the whole site is loaded 
        $('#prestatus').fadeOut(); // will first fade out the loading animation 
        $('#divpreloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
        $('body').delay(350).css({'overflow':'visible'});
       

        document.getElementById("addrcustomerSucces").innerHTML="";
          document.getElementById("addrcustomerError").innerHTML="";
           document.getElementById("AddressFrm").reset();
            document.getElementById("addrcustomerSucces").innerHTML="Address Added Successfully."
        $('#UpdateAddres').html(res);
      },
     error: function(e) 
      {
    //$("#err").html(e).fadeIn();
      }          
    });
 }));
});


</script> 
<!-- Address Change -->

<!-- Delete Address -->
<script>

function  addresDelte(argument) {
        
$("#addres_id").val(argument);
var conf=confirm("Are you sure want to delete this Address");
if(conf){
    var url="getAjaxx.php";
$.post(url,{"choice":"DleteAdres","addres_id":argument},function(res){
if(res=='succes'){
$('#delAddres'+argument).css('display','none');
$('#delResp'+argument).text('Deleted Successfully.');
}
});
}
      
 }
</script>
<!-- Delete Address -->