<?php include('header.php')?>
  <?php include('sidebar.php')?>
  
<?php 
if(isset($_REQUEST['action']) && $_REQUEST['action']=='StatusUpdate'){
  $status = $_POST['status'];
  $order_id = $_POST['order_id'];

$dbf->updateTable("orders","status='$status'","orders_id='$order_id'");

header("Location:orders.php");
}
 
if(isset($_REQUEST['action']) && $_REQUEST['action']=='StatusUpdateS'){
  $status = $_POST['cstatus'];
  $order_id = $_POST['order_id'];
  if($status=='4'){
      $today=date('Y-m-d');
      $dbf->updateTable("orders","status='$status',delivery_date='$today'","order_id='$order_id'");
  }else{
$dbf->updateTable("orders","status='$status'","order_id='$order_id'");
}
        if($status=='4'){
        $Orders= $dbf->fetchSingle("orders",'user_id,address_id,order_id',"orders_id='$order_id'");
        $User= $dbf->fetchSingle("user",'*',"id='$Orders[user_id]'");
        $Address= $dbf->fetchSingle("address",'*',"address_id='$Orders[address_id]'");
          $from="syflextechnotest@gmail.com";
           $to="$User[email],$Address[email]";
                        //Send mail to customer--------------------------------------
            $subject = "Order Completed.";
   
            $message = ' 
                        <table  width="600" cellspacing="0" cellpadding="0" border="0" style="border:solid 1px #ccc;">
                          <tbody>
                            <tr>
                              <td valign="top" align="center"><table  width="600" cellspacing="0" cellpadding="0" border="0" style="background:#000; color:#fff; text-align:center">
                                <tbody>
                                  <tr>
                                    <td ><p></p><h1>Thanks for shopping with us</h1></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td valign="top" align="center"><table  width="600" style="border:solid 1px #ccc;">
                                <tbody>
                                  <tr>
                                    <td  valign="top"><table width="100%" cellspacing="0" cellpadding="20" border="0">
                                      <tbody>
                                        <tr>
                                          <td valign="top"><div id="m_2196456795022213401m_-6028743788893954303body_content_inner">
                                            <p>Hi '.$Address[first_name].',</p>
                                            <p>We have finished processing your order.</p>
                                            <p>Pay with '.$Orders[payment_mode].'.</p>
                                            <h2> [Order #'.$Orders[order_id].'] ('.date('F j, Y').')</h2>
                                            <div>
                                              <table cellspacing="0" cellpadding="6" border="1" width="100%">
                                                <thead>
                                                  <tr>
                                                    <th scope="col">Product</th>
                                                    <th scope="col">Quantity</th>
                                                    <th scope="col">Price</th>
                                                  </tr>
                                                </thead>
                                                <tbody>';
                                                  $sub_total=0;
                            foreach ($dbf->fetchOrder("orders","order_id='$Orders[order_id]' ","orders_id DESC","","") as $Morder) {
$AddresOfUser=$dbf->fetchSingle("address",'*',"address_id='$Morder[address_id]'");
    $sub_total +=$Morder[price]*$Morder[qty];

 $ordername=$products['product_name'].','.implode(',',$Arry_Vari);
                                                  $message .= '<tr>
                                                    <td> '.$Morder[ordername].' </td>
                                                    <td> '.$Morder[qty].' </td>
                                                    <td> &#8377;. '.number_format($Morder[price],'2').' </td>
                                                  </tr>';
                                              }
                                               $message .= '</tbody>
                                                <tfoot>
                                                  <tr>
                                                    <th scope="row" colspan="2">Subtotal:</th>
                                                    <td> &#8377;.'.number_format($sub_total,'2').'</td>
                                                  </tr>
                                                  <tr>
                                                    <th scope="row" colspan="2">Payment method:</th>
                                                    <td>'.$Morder[payment_mode].'</td>
                                                  </tr>
                                                  <tr>
                                                    <th scope="row" colspan="2">Total:</th>
                                                    <td>&#8377;. '.number_format($sub_total+$Morder[shipping_charge],'2').'</td>
                                                  </tr>
                                                </tfoot>
                                              </table>
                                            </div>
                                            <table id="m_2196456795022213401m_-6028743788893954303addresses" cellspacing="0" cellpadding="0" border="0">
                                              <tbody>
                                                <tr>
                                                  <td width="50%" valign="top"><h2>Billing address</h2>
                                                   <address>
                                              '.$AddresOfUser[address].'<br />
                                              '.$AddresOfUser[first_name].' '.$AddresOfUser[last_name].'<br />
                                              '.$City[city_name].'<br />
                                               '.$Pincode[pincode].'<br />
                                                '.$State[state_name].' <br />
                                              <a href="tel:'.$AddresOfUser[number].'" rel="noreferrer" target="_blank">'.$AddresOfUser[number].'</a> <br />
                                              <a href="mailto:'.$AddresOfUser[email].'" rel="noreferrer" target="_blank">'.$AddresOfUser[email].'</a>
                                            </address>
                                            </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                            <p>Thanks for shopping with us.</p>
                                          </div></td>
                                        </tr>
                                      </tbody>
                                    </table></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                          </tbody>
                        </table>
';
        //echo $message;exit;
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
           
            //More headers
            $headers .= 'From:'. $from . "\r\n";   
           
            mail($to,$subject,$message,$headers);

header("Location:orders.php");exit;
      
}else{

header("Location:orders.php");exit;
}



}
$pin=$citi=$shop='';
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='Fillter'){
  $citi=$_POST['city'];
  $pin=$_POST['pin'];
  $shop=$_POST['shop'];
  $condi = "";
  if($citi!=''){
    $Citys = $dbf->fetchSingle("city",'city_name',"city_id='$citi'");
    $condi .= " AND city = '$Citys[city_name]'";
  }
  if($pin!=''){
    $Pins = $dbf->fetchSingle("pincode",'pincode',"pincode_id='$pin'");
    $condi .= " AND pin='$Pins[pincode]'";
  }
  if($shop!=''){
    $condi .= " AND vendor_id='$shop'";
  }
}else{
  $condi = "";
}
?>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Orders
        <small>Manage All Orders Here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Orders</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      <div class="box">
         
            <!-- /.box-header -->
            <div class="box-body">
            <?php if($_SESSION['usertype']=='1'){
              $loc="orders_id<>0";

              ?>
            <form action="" method="post">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                <select name="city" id="" class="form-control select2" onchange="UpdateChangepin(this.value)" >
                  <option value="">--Select City--</option>
                  <?php  foreach($dbf->fetchOrder("city","","city_name ASC","","") as $stateName){?>
                  <option value="<?=$stateName['city_id']?>" <?= ($citi==$stateName['city_id'])?"selected":""?>><?=$stateName['city_name']?></option>
                  <?php }?>
                </select>
                </div>
              </div>
              <div class="col-md-3">
              <select name="pin" class="form-control select2" id="loc_selected_pin"  onchange="GetShop(this.value)">
                <option value="">--Select Pincode--</option>
               </select>
              </div>
              <div class="col-md-3">
              <select name="shop" class="form-control select2" id="Shop">
                <option value="">--Select Shop--</option>
               </select>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <button class="btn btn-primary" name="operation" value="Fillter">Fillter</button>
                  <a href="" class="btn btn-default">Refresh</a>
                </div>
              </div>
            </div>
            </form>
                  <?php }else{    
                        $Loc_Pin=$dbf->fetchSingle("city",'city_name',"city_id='$_SESSION[city]'");
                        $loc = " city='$Loc_Pin[city_name]'";
                    }?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                      <th scope="col">Sl No</th>
                      <th scope="col">Order Id </th>
                      <th scope="col">Order Date & Time in 24 hrs</th>
                      <th scope="col">Vendor Name</th>
                      <th scope="col">Order By</th>
                      <th scope="col">Delivery By</th>
                     <th scope="col">Payment Mode</th>
                     <th>Delivery Date & Time in 24 hrs</th>
                     <?php if(in_array('3.1',$Job_assign)){?>
                     <th scope="col">Order Status </th>
                     <?php }?>
                     <th scope="col">Order Details </th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                  $i=1;
                  foreach($dbf->fetchOrder("orders",$loc.$condi,"created_date DESC","","order_id") as $Order){
                     $addres=$dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");
           $vendor=$dbf->fetchSingle("user",'*',"id='$Order[vendor_id]'");
           $User=$dbf->fetchSingle("user",'full_name',"id='$Order[user_id]'");
           $Dboy=$dbf->fetchSingle("user",'full_name',"id='$Order[d_boy_id]'");
                    ?>
                  <tr>
                  <td><?= $i?></td>
                  <td><?= $Order['order_id']?></td>
                  <td><?= date('d.m.Y h:i:s',strtotime($Order['created_date']))?></td>
                  <td><?= $vendor['shop_name']?></td>
                  <td><?= $User['full_name']?></td>
                  <td><?= ($Dboy['full_name'])?'<a href="javascript:void(0)" onclick="getDboyDetails('."'".$Order['order_id']."'".','.$Order['d_boy_id'].')">'.$Dboy['full_name'].'</a>':""?></td>
                  <td><?= strtoupper($Order['payment_mode']);?></td>
                  <td><?= date('d.m.Y h:i:s',strtotime($Order['delivery_date']))?>
                  </td>
                  <?php if(in_array('3.1',$Job_assign)){?>
                  <td>
                       <?php 
    if($Order['status']=='0'){?>
        <button  class="uk-button uk-button-small uk-button-warning"  uk-toggle="target: #modal-example2<?= $Order['order_id']?>">New Order</button>
    <?php }else if($Order['status']=='1'){?>
        <button  class="uk-button uk-button-small uk-button-primary"  uk-toggle="target: #modal-example2<?= $Order['order_id']?>">Order Received</button>
    <?php } else if($Order['status']=='2'){?>
        <button  class="uk-button uk-button-small uk-button-primary"  uk-toggle="target: #modal-example2<?= $Order['order_id']?>">Processing</button>
    <?php }else if($Order['status']=='3'){?>
        <button  class="uk-button uk-button-small uk-button-primary"  uk-toggle="target: #modal-example2<?= $Order['order_id']?>">Shipped</button>
    <?php }else if($Order['status']=='4'){?>
        <button  class="uk-button uk-button-small uk-label-success"  uk-toggle="target: #modal-example2<?= $Order['order_id']?>">Completed </button>
    <?php } else if($Order['status']=='5'){?>
         <button  class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-example2<?= $Order['order_id']?>">Return Approved</button>
     <?php } else if($Order['status']=='6'){?>
        <button  class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-example2<?= $Order['order_id']?>">Returned Process</button>
      <?php } else if($Order['status']=='7'){?>
        <button  class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-example2<?= $Order['order_id']?>">Returned Cancel</button>
        <?php } else if($Order['status']=='8'){?>
        <button  class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-example2<?= $Order['order_id']?>">Cancel By Customer</button>
    <?php }else if($Order['status']=='9'){?>
        <button  class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-example2<?= $Order['order_id']?>">Payment Failed</button>
    <?php }else if($Order['status']=='10'){?>
        <button  class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-example2<?= $Order['order_id']?>">Delivery Boy Not Found!!!</button>
    <?php }else{?>
        <button  class="uk-button uk-button-small uk-button-danger" >Cancel By Vendor</button>
         <?php }?>
              

<?php if($Order['status']>3){?>
                    <div id="modal-example2<?= $Order['order_id']?>" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
    <div class="uk-grid">
      <div class="uk-width-auto">Order Id : <?= $Order['order_id']?></div>
      <div class="uk-width-expand"></div>
      <div class="uk-width-auto">Date: <?= date('d.m.Y',strtotime($Order['created_date']))?></div>
    </div>
    <hr />
      <h4 class="uk-text-center uk-margin-remove-top">Order Status Change</h4>
        <div  style="border:solid 1px #ccc;">
        <table class="uk-table uk-table-small uk-table-divider">
        <tr class="uk-background-muted">
          <th>Sl No</th>
            <th>Product Details</th>
            <th> Price</th>
            <th> Qty</th>
            <th> Total</th>
            <th> Status</th>
        </tr>
        
         <?php
  $j=1;
   foreach($dbf->fetchOrder("orders","order_id='$Order[order_id]' ","orders_id DESC","","") as $singleorder){
  $totalqty= $dbf->fetchSingle("orders",'SUM(qty) as total_qty',"order_id='$order[order_id]'");
  $prototal= $dbf->fetchSingle("orders",'SUM(qty*price) as pro_total',"order_id='$order[order_id]'");
  
  ?>
    

    <tr>
    <td><?php echo $j;?></td>
    <td><?= $singleorder['ordername']?></td>
    <td><?= $singleorder['price']?></td>
    <td><?= $singleorder['qty']?></td>
    <td><?php echo $singleorder['price']* $singleorder['qty']; ?>.00</td>
    <td>  <select class="form-control" name="cstatus" onchange="ChangeOrderstatus(this.value,<?= $singleorder['orders_id']?>)">
          <?php  foreach($dbf->fetchOrder("status","","","","") as $stauts){?>
          <option value="<?= $stauts['status_id']?>" <?php if($stauts['status_id']==$singleorder['status']){ echo 'selected';}?> <?php if($stauts['status_id']<$singleorder['status'] ){ echo " disabled ";}   if($stauts['status_id']=='8'){ echo " disabled ";}?><?php if($stauts['status_id']=='6' || $stauts['status_id']=='5' || $stauts['status_id']=='7'){ echo " disabled ";}?>> <?= $stauts['name']?></option>
        <?php }?>
        </select>
      </td>

    </tr>

  
    <?php $j++; } ?>
    
    
     
    
    
    </table>
   

        </div>

        
    </div>
</div>
<?php }else{?>

<div class="modal fade" id="modal-example2<?= $Order['order_id']?>"  uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Change Order Status</h2>
          <form action="" method="post">
          <input type="hidden" name="action" value="StatusUpdateS">
           <input type="hidden" name="order_id" value="<?= $Order['order_id']?>">
        <div class="modal-body">
        <select class="form-control" name="cstatus">
          <?php  foreach($dbf->fetchOrder("status","","","","") as $stauts){?>
          <option value="<?= $stauts['status_id']?>" <?php if($stauts['status_id']==$Order['status']){ echo 'selected';}?><?php if($stauts['status_id']<$Order['status'] ){ echo "disabled";}?><?php if($stauts['status_id']=='6' || $stauts['status_id']=='5' || $stauts['status_id']=='7'){ echo "disabled";}?>> <?= $stauts['name']?></option>
        <?php }?>
        </select>
        </div>
    
   
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" >Save</button>
        </p>
           </form>
    </div>
</div>
        
<?php }?>
<?php  $GetAll_status=$dbf->fetchOrder("orders","order_id='$Order[order_id]'","","status","");
      $arr_of_Order_status=array();
      if(!empty($GetAll_status)){
          foreach($GetAll_status as $order_status){
            array_push($arr_of_Order_status,$order_status['status']);
          }

      }
      if(in_array(5, $arr_of_Order_status) || in_array(6, $arr_of_Order_status) || in_array(7, $arr_of_Order_status)){
        // if(in_array(5, $arr_of_Order_status)){
        ?>
        <button  class="uk-button uk-button-small uk-label-success"  uk-toggle="target: #modal-return<?= $Order['order_id']?>">Return</button>
<!-- <button  class="uk-button uk-button-small uk-button-success"  uk-toggle="target: #modal-return<?= $Order['order_id']?>">Return Approved</button> -->
  <!-- <?php //} elseif(in_array(6, $arr_of_Order_status)){?> -->
    <!-- <button  class="uk-button uk-button-small uk-button-danger"  uk-toggle="target: #modal-return<?= $Order['order_id']?>">Return Process</button> -->
  <!-- <?php //}else{?> -->
    <!-- <button  class="uk-button uk-button-small uk-button-danger"  uk-toggle="target: #modal-return<?= $Order['order_id']?>">Return Canceled</button> -->
  <?php //}?>
  
  <div id="modal-return<?= $Order['order_id']?>" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
    <div class="uk-grid">
      <div class="uk-width-auto">Order Id : <?= $Order['order_id']?></div>
      <div class="uk-width-expand"></div>
      <div class="uk-width-auto">Date: <?= date('d.m.Y',strtotime($Order['created_date']))?></div>
    </div>
    <hr />
      <h4 class="uk-text-center uk-margin-remove-top">Order Return</h4>
        <div  style="border:solid 1px #ccc;">
          
        <table class="uk-table uk-table-small uk-table-divider">
        <tr class="uk-background-muted">
          <th>Sl No</th>
            <th>Product Details</th>
            <th> Price</th>
            <th> Qty</th>
            <th> Total</th>
            <th> Status</th>
        </tr>
        
         <?php
  $sl_no=1;
   foreach($dbf->fetchOrder("orders","order_id='$Order[order_id]'  AND status IN(5,6,7)","orders_id DESC","","") as $singleorders){
  $totalqty= $dbf->fetchSingle("orders",'SUM(qty) as total_qty',"order_id='$order[order_id]'");
  $prototal= $dbf->fetchSingle("orders",'SUM(qty*price) as pro_total',"order_id='$order[order_id]'");
  
  ?>
    

    <tr>
    <td><?php echo $sl_no;?></td>
    <td><?= $singleorders['ordername']?></td>
    <td><?= $singleorders['price']?></td>
    <td><?= $singleorders['qty']?></td>
    <td> <?php echo $singleorders['price']* $singleorders['qty']; ?>.00</td>
    <td>  <select class="form-control" name="cstatus">
          <?php  foreach($dbf->fetchOrder("status","","","","") as $stauts){?>
          <option value="<?= $stauts['status_id']?>" <?php if($stauts['status_id']==$singleorders['status']){ echo 'selected';}?> <?php if($stauts['status_id']<$singleorders['status'] ){ echo " disabled ";}?><?php if($stauts['status_id']=='6' || $stauts['status_id']=='5' || $stauts['status_id']=='7' || $stauts['status_id']=='8'){ echo " disabled ";}?>> <?= $stauts['name']?></option>
        <?php }?>
        </select>
      </td>

    </tr>

  
    <?php $sl_no ++; } ?>
    
    
     
    
    
    </table>
   

        </div>

        
    </div>
</div>
<?php }?>
             
                  </td>
   <?php }?>        
                  <td>
                  <a class="btn btn-social-icon btn-success" uk-toggle="target: #modal-example<?= $Order['order_id']?>" ><i class="fa fa-eye"></i></a>
                  
    
                  <div id="modal-example<?= $Order['order_id']?>" uk-modal>
          <div class="uk-modal-dialog uk-modal-body">
                 <div id="print_area<?= $Order['order_id']?>">
          <div class="uk-grid">
             <div style="text-align: center;"  class="uk-width-expand">
             <img src="images/<?php echo $page['logo'];?>" width="100" >
          </div>
            <div class="uk-width-auto">Order Id : <?= $Order['order_id']?></div>
            <div class="uk-width-expand"></div>
            <div class="uk-width-auto" style="text-align: right;">Date: <?= date('d.m.Y',strtotime($Order['created_date']))?></div>
          </div>
          <hr />
            <h4 class="uk-text-center uk-margin-remove-top">Order Summary</h4>
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
            $CardDetails = $dbf->fetchSingle("orders",'gift_name,gift_price,gift_img',"order_id='$Order[order_id]'");
        $k=1;
        $return_Amnt=0;
         foreach($dbf->fetchOrder("orders","order_id='$Order[order_id]' ","orders_id DESC","","") as $singleorder){
        $totalqty= $dbf->fetchSingle("orders",'SUM(qty) as total_qty',"order_id='$Order[order_id]'");
        $prototal= $dbf->fetchSingle("orders",'SUM(qty*price) as pro_total',"order_id='$Order[order_id]'");
        $ReturnAmnt= $dbf->fetchSingle("orders",'qty,price',"orders_id='$singleorder[orders_id]' AND status='5'");
       $return_Amnt=$return_Amnt+$ReturnAmnt['qty']*$ReturnAmnt['price'];
      
        ?>
          
          <tr>
          <td><?php echo $k;?></td>
          <td><?= $singleorder['ordername']?><?php if($singleorder['status']=='5'){?><span style="color:red;font-size: 20px;">(Returned)</span><?php }?></td>
          <td>&#8377; . <?= $singleorder['price']?></td>
          <td><?= $singleorder['qty']?></td>
          <td><?php if($singleorder['status']=='5'){?><span style="color:red;font-size: 20px;font-weight: bold;">-</span><?php }?>
          &#8377; . <?php echo $singleorder['price']* $singleorder['qty']; ?>.00</td>
          </tr>
          <?php $k++; } ?>
          <?php if(!empty($CardDetails['gift_name'])){?>
          <tr>
          <td><?=$k?></td>
          <td><?=$CardDetails['gift_name']?>(Gift Card)</td>
          <td>&#8377; . <?=number_format($CardDetails['gift_price'],2)?></td>
          <td>&#8377; . <?=$CardDetails['gift_price']?></td>
          <td>&#8377; . <?=number_format($CardDetails['gift_price'],2)?></td>
          </tr>
       <?php }?>
          <tr>
          <td></td>
          <td></td>
          <td>TOTAL</td>
          <td><b><?php if($CardDetails['gift_name']!=''){ echo $totalqty['total_qty'] +1;}else{ echo $totalqty['total_qty'] ;}?></b></td>
          <td><b>&#8377; . <?=$totalGrnadAMT= $prototal['pro_total']-$return_Amnt?></b></td>
          </tr>
          <?php if($singleorder['coupon_amnt']!=0){?>
      <tr style="border-bottom: solid 1px #ccc;">
    <td></td>
    <td></td>
    <td>Coupon Amount(<?= $singleorder['coupon_code']?>): </td>
    <td></td>
    <td><b >-<?= number_format($singleorder['coupon_amnt'],2)?></b></td>
    </tr>
  <?php }?>
           <?php if($singleorder['wallet']!=0){?>
           <tr>
          <td></td>
          <td></td>
          <td>WALLET:</td>
          <td></td>
          <td><b>&#8377; <?= number_format($singleorder['wallet'],2)?></b></td>
          </tr>
        <?php }?>
           <tr>
          <td></td>
          <td></td>
          <td>SHIPPING CHARGE</td>
          <td><b></b></td>
          <td><b>&#8377; . <?= $singleorder['shipping_charge']?></b></td>
          </tr>
          
                  <?php if($Order['payment_mode'] == 'cod'){?>
           <tr>
          <td></td>
          <td></td>
          <td>COD Charge:</td>
          <td></td>
          <td><b>&#8377; <?php
          $totalAMTT=$prototal['pro_total']+$CardDetails['gift_price']+ $singleorder['shipping_charge']-$singleorder['wallet']-$return_Amnt-$singleorder['coupon_amnt'];
          $codfee= $totalAMTT * 5 / 100;
          echo number_format($codfee,2);?></b></td>
          </tr>
        <?php }?>
          
          <tr>
          <td></td>
          <td></td>
          <td><span class="uk-text-danger">SUB TOTAL</span></td>
          <td><b></b></td>
          <td><b>&#8377;. <?php
          
          if($Order['payment_mode'] == 'cod'){
              echo number_format($codfee+$prototal['pro_total']+$CardDetails['gift_price']+ $singleorder['shipping_charge']-$singleorder['wallet']-$return_Amnt-$singleorder['coupon_amnt'],2);
          }else{
              echo number_format($prototal['pro_total']+$CardDetails['gift_price']+ $singleorder['shipping_charge']-$singleorder['wallet']-$return_Amnt-$singleorder['coupon_amnt'],2);
          }
           ?></b></td>
          </tr>
          </table>
              </div>
              <?php $address = $dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");   ?>
              <?php $city = $dbf->fetchSingle("city",'*',"city_id='$address[city_id]'"); 
                $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$address[pincode]'");
              ?>
              <h5>Address</h5>
             
        <p> 
        Name: <?php echo $address['first_name'];?> <?php echo $address['last_name'];?><br />
        Contact No: <?php echo $address['number'];?> <br />
        Email : <?php echo $address['email'];?> <br />
        Address: <?php echo $address['address'];?>, <br />
        Address1: <?php echo $address['adress1'];?>, <br />
        <?php echo $city['city_name'];?>,  Pin: <?php echo $pincode['pincode'];?>  <br />
        </p>
        </div>
              <p class="uk-text-right">
                  <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
                  <button class="uk-button uk-button-primary" type="button" onclick="printPageArea('<?='print_area'.$Order['order_id']?>')">Print</button>
              </p>
          
        </div>
      </div>
                  </td>
                
                  </tr>

                    <div class="modal fade" id="myModal<?= $Order['orders_id']?>" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Shipping Address</h4>
        </div>
        <div class="modal-body">
       <?= $addres['first_name'].' '.$addres['first_name'].','.$addres['email'].',<br>'.$addres['number'].','.$city['city_name'].','.$addres['address'].','.$addres['pincode']?><br>
       <h3>Shiping Type:<?= $Order['shipping_type']?></h3>
       <h3>Payment Mode:<?= $Order['payment_mode']?></h3>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
           
<!-- 
                    <div class="modal fade" id="myModals<?= $Order['orders_id']?>" role="dialog">
    <div class="modal-dialog">
    
  
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Order Status</h4>
        </div>
        <form action="" method="post">
          <input type="hidden" name="action" value="StatusUpdate">
           <input type="hidden" name="order_id" value="<?= $Order['orders_id']?>">
        <div class="modal-body">
        <select class="form-control" name="cstatus">
          <?php  foreach($dbf->fetchOrder("status","","","","") as $stauts){?>
          <option value="<?= $stauts['status_id']?>" <?php if($stauts['status_id']==$Order['status']){ echo 'selected';}?> <?php if($stauts['status_id']<$Order['status']){ echo "disabled";}?>> <?= $stauts['name']?></option>
        <?php }?>
        </select>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Update Status</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
      </div>
      
    </div>
  </div>   -->   <?php $i++;}?>
                </tbody>
                <tfoot>
                <tr>
                      <th scope="col">Sl No</th>
                      <th scope="col">Order Id </th>
                      <th scope="col">Order Date & Time in 24 hrs</th>
                      <th scope="col">Vendor Name</th>
                      <th scope="col">Order By</th>
                      <th scope="col">Delivery By</th>
                     <th scope="col">Payment Mode</th>
                     <th>Delivery Date & Time in 24 hrs</th>
                     <?php if(in_array('3.1',$Job_assign)){?>
                     <th scope="col">Order Status </th>
                     <?php }?>
                     <th scope="col">Order Details </th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
    
   <!-- gallery --> 
     <!-- Modal -->

  </div>

   <script type="text/javascript">
    function printPageArea(areaID){
    var printContent = document.getElementById(areaID);
    var WinPrint = window.open('', '', 'width=1000,height=700');
    WinPrint.document.write(printContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}

function ChangeOrderstatus(arg1,arg2){
// alert(arg2)
 $("#order_id").val(arg2);
 $("#status").val(arg1);
 $("#ChangeOrders").submit();
}

  </script>
<form id="ChangeOrders" action="" method="post">
   <input type="hidden" name="action" value="StatusUpdate">
   <input type="hidden" name="order_id" id="order_id">
   <input type="hidden" name="status" id="status">
</form>

   <?php include('footer.php')?>

   <script>
  function UpdateChangepin(arg){
  var url="getAjax.php";

  $.post(url,{"choice":"changPin","value":arg,"pin":"<?= $pin?>"},function(res){
 $('#loc_selected_pin').html(res);
// alert(res)
});
}
UpdateChangepin(<?= $citi?>);
function GetShop(arg){
  var url="getAjax.php";
  $.post(url,{"choice":"Shops","pin":arg,"shop":"<?= $shop?>"},function(res){
 $('#Shop').html(res);
// alert(res)
});
}
GetShop(<?= $pin?>);


function getDboyDetails(arg,dboy){
 
  $("#DeBoyMOdalHead").text("ORDER ID: "+arg);
  var url="getAjax.php";
  $.post(url,{"choice":"GetDboy","dboy":dboy},function(res){
//  $('#Shop').html(res);
    
    res = res.split('!next!');
    $('#DboyName').text(res[0]);
    $('#DboyMail').text(res[1]);
    $('#DboyNum').text(res[2]);
    $('#DboyProfile').html(res[3]);
 $('#DboyDetails').modal('show'); 
// alert(res)
});

}

</script>

<div id="DboyDetails" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="DeBoyMOdalHead"></h4>
      </div>
      <div class="modal-body">
          <div class="row">
          <div class="col-md-12">
          <div class="col-md-5">
          <span id="DboyProfile"></span>
          </div>
          <div class="col-md-7">
          <div class="row">
          <div class="col-md-3">
          Name:
          </div>
          <div class="col-md-9">
            <span id="DboyName"></span>
          </div>
          </div>
          <div class="row">
          <div class="col-md-3">
          Email:
          </div>
          <div class="col-md-9">
            <span id="DboyMail"></span>
          </div>
          </div>
          <div class="row">
          <div class="col-md-3">
          Number:
          </div>
          <div class="col-md-9">
            <span id="DboyNum"></span>
          </div>
          </div>
          </div>
          </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>