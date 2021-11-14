<?php include('header.php')?>
<?php include('sidebar.php')?>
<?php 

 	//To send Firebase notification to android device==================================
   function sendPushNotification($to = '', $data1=array(), $data2=array(),$data3=array(),$data4=array()) {
		
    $apiKey = "AAAAenpRW_k:APA91bHGKFZ3hiZPeYAm1hU76okOAlYEqv7oAg-PtJxlYAZUH396FBjUHp7IZxrj0EO2_eK7qpLT5JDq8W8u4IDwfwd1zZBiqas-iizJtDcStJu4Pow7EGEDYYpKg5JFEaoUPh6VfDTk"; //Place Server Legacy Key insted of api key
    
    $fields = array( 'to' => $to, 'notification' => $data1, 'data' => $data2,'priority'=>'high','android'=>$data3,'webpush'=>$data4);
// 		print_r(json_encode($fields));exit;
    $headers = array ( 'Authorization: key=' . $apiKey, 'Content-Type: application/json' );
    
    $url = 'https://fcm.googleapis.com/fcm/send';
  
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($fields) );
  
    $result = curl_exec ( $ch );
        // echo $result;exit;
    curl_close ( $ch );
    return json_decode($result, true);
  }	


if(isset($_REQUEST['action']) && $_REQUEST['action']=='StatusUpdate'){
  $status = $_POST['cstatus'];
   $order_id = $_POST['order_id'];
  $dbf->updateTable("orders","status='$status'","order_id='$order_id'");
  if($status=='-1'){
   
    $Ordder_pay=$dbf->fetchSingle("orders",'*',"order_id='$order_id'");
    $User_Wallet=$dbf->fetchSingle("user",'wallet',"id='$Ordder_pay[user_id]'");
    if(strtolower($Ordder_pay['payment_mode'])==strtolower('cod& Wallet Paid')){
        $amount= $Ordder_pay['wallet']+$User_Wallet['wallet'];
      
        $dbf->updateTable("user","wallet='$amount'","id='$Ordder_pay[user_id]'");
        $string = "amount='$Ordder_pay[wallet]',remark='Cancel Order',user_id='$Ordder_pay[user_id]',date=NOW()";

        $ins_id=$dbf->insertSet("wallet_histru",$string);
    }
    if(strtolower($Ordder_pay['payment_mode'])==strtolower('online& Wallet Paid')){
      $total_amnt=0;
      foreach($dbf->fetchOrder("orders","order_id='$order_id'","","price,qty","") as $CancelAmnt){
        $total_amnt+=$CancelAmnt['qty']*$CancelAmnt['price'];
      }
  
       $amount= ($total_amnt+$Ordder_pay['shipping_charge'])-$Ordder_pay['coupon_amnt'];
        $hist_amnt= $amount+$User_Wallet['wallet'];
        $dbf->updateTable("user","wallet='$hist_amnt'","id='$Ordder_pay[user_id]'");
        $string = "amount='$amount',remark='Cancel Order',user_id='$Ordder_pay[user_id]',date=NOW()";
        $ins_id=$dbf->insertSet("wallet_histru",$string);
    }if(strtolower($Ordder_pay['payment_mode'])==strtolower('online')){
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

  }
if($status=='1'){
  $Ordder_pay=$dbf->fetchSingle("orders",'user_id',"order_id='$order_id'");
  $User=$dbf->fetchSingle("user",'full_name,contact_no',"id='$Ordder_pay[user_id]'");
  $Shop=$dbf->fetchSingle("user",'shop_name,fcm_id',"id='$profileuserid'");

  $apikey = "VsllEZjMRkKztBpUIKVmEA";
  $apisender = "DToDor";
  $msg ="Dear $User[full_name], Your order $order_id is confirmed by  $Shop[shop_name].Our executive will call once the packing is ready. Kindly use your order number $order_id for  reference.";
  $num = $User['contact_no']; 
  $ms = rawurlencode($msg); 
  $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=1&number='.$num.'&text='.$ms.'&route=1';
  $ch=curl_init($url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch,CURLOPT_POST,1);
  curl_setopt($ch,CURLOPT_POSTFIELDS,"");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
  $data = curl_exec($ch);
  // print_r($data);exit;
    $to=$Shop['fcm_id'];
  	$data1=array(
      'title'=>"OwnMyStore",
      'body'=>"Order is been processed,#$order_id"
    );
    $data2=array(''=>'');
    sendPushNotification($to,$data1,$data2); 

}



  if($status=='2'){
    $Vendor=$dbf->fetchSingle("orders",'vendor_id',"order_id='$order_id'");
    $Shop=$dbf->fetchSingle("user",'lat,lng,address1,shop_name',"id='$Vendor[vendor_id]'");

    $lat = $Shop['lat'];
    $lng = $Shop['lng'];

    //Get All Assign Status Active
$Active_Delivery_Boy=$dbf->fetchOrder("orders","status IN(2,3)","","d_boy_id","");
if(!empty($Active_Delivery_Boy)){
  $Array_of_dboy=array();
  foreach($Active_Delivery_Boy as $Act_boy){
    if($Act_boy['d_boy_id']!=''){
    array_push($Array_of_dboy,$Act_boy['d_boy_id']);
    }
  }
  if(!empty($Array_of_dboy)){
    $dboy_id = implode(',',$Array_of_dboy);
    $Condi=" AND id NOT IN($dboy_id) ";
  }else{
    $Condi="";
  }

}else{
  $Condi="";
}


//Get All Sending Requests
$ActiveRequest_Delivery_Boy=$dbf->fetchOrder("order_sending","","","dboy_id","");
if(!empty($ActiveRequest_Delivery_Boy)){
$Arry_of_reqactive=array();
foreach($ActiveRequest_Delivery_Boy as $ReqAct_boy){
  array_push($Arry_of_reqactive,$ReqAct_boy['dboy_id']);
}
if(!empty($Array_of_dboy)){
  $reqdboy_id = implode(',',$Arry_of_reqactive);
  $condi1=" AND id NOT IN($reqdboy_id) ";
}else{
  $condi1="";
}
}else{
  $condi1="";
}

$Dboy=$dbf->fetchOrder("user","status='1' AND user_type='5' AND online='1' $Condi $condi1","distance LIMIT 0,5","id,fcm_id,(6371  * acos(cos(radians($lat)) * cos(radians(lat)) * cos(radians(lng) - radians($lng)) + sin(radians($lat)) * sin( radians(lat)))) as distance","");
   
    $Curtime = date('Y-m-d H:i:s');

    // print_r($Dboy);exit;
    //Check Delivery Boy Availabel Or Not
    if(!empty($Dboy)){
        $Arr_deboy_id=array();
      foreach($Dboy as $SingleDboy){
        array_push($Arr_deboy_id,$SingleDboy['id']);
      }
      $Dboyids=implode(',',$Arr_deboy_id);
      $Arr_deboy_fcm_id=array();
      foreach($Dboy as $SingleDboy){
        array_push($Arr_deboy_fcm_id,$SingleDboy['fcm_id']);
      }

      //Send Push Notification
       
          //To send Firebase notification to android device==================================
          
        
            $to=implode(',',$Arr_deboy_fcm_id);
          $data1=array(''=>"");
          $data2=array("notification_type"=>"new_order","pay_load"=>'{ "total_amnt": "300","lat":"'.$lat.'", "lng": "'.$lng.'","order_id": "'.$order_id.'","sho_adress": "'.$Shop['address1'].'","shop_name":"'.$Shop['shop_name'].'","time":"'.$Cur_date.'"}');
          $data3=array("ttl"=>"5s");
          $data4= array("headers"=>array("TTL"=>"5"));
          // array('"'.'order_id'.'"'=>'"'.$order_id.'"','"'.'lat'.'"'=>'"'.$lat.'"','"'.'lng'.'"'=>'"'.$lng.'"','"'.'sho_adress'.'"'=>'"'.$Shop[address1].'"','"'.'total_amnt'.'"'=>'"'.'330'.'"','"'.'shop_name'.'"'=>'"'.$Shop[shop_name].'"');
          
          sendPushNotification($to,$data1,$data2,$data3,$data4);
              //Send Push Notification


      //Insert Sending Information
      $CntOrder_id=$dbf->countRows("order_sending","order_id='$order_id'","");
      if($CntOrder_id==0){
      $dbf->insertSet("order_sending","order_id='$order_id',dboy_id='$Dboyids',cur_time='$Curtime',order_time='$Curtime'");
      }
      //Insert Sending Information

    }else{
      $dbf->updateTable("orders","status='10'","order_id='$order_id'");
    }
  }

header("Location:orders.php");

}?>

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
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th scope="col">Sl No</th>
                    <th scope="col">Order Id </th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Delivery By</th>
                     <th scope="col">Payment </th>
                     <th>Delivery Time Slot</th>
                    <th scope="col">Status </th>
                    <th scope="col">View Details </th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                  $k=1;
                  foreach($dbf->fetchOrder("orders","vendor_id='$profileuserid'","created_date DESC","","order_id") as $Order){
                     $addres=$dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");
                     $Dboy=$dbf->fetchSingle("user",'full_name',"id='$Order[d_boy_id]'");
                    ?>
                  <tr>
                  <td><?= $k++?></td>
                  <td><?= $Order['order_id']?></td>
                  <td><?= date('d.m.Y',strtotime($Order['created_date']))?></td>
                  <td><?= ($Dboy['full_name'])?'<a href="javascript:void(0)" onclick="getDboyDetails('."'".$Order['order_id']."'".','.$Order['d_boy_id'].')">'.$Dboy['full_name'].'</a>':""?></td>
                  <td><?= $Order['payment_mode']?></td>
                  <td><?= date('d.m.Y',strtotime($Order['delivery_date']))?>(
                    <?php 
                    switch($Order['time_slot']){
                      case '7-9':
                        echo '7AM-9AM';
                      break;
                      case '9-11':
                        echo '9AM-11AM';
                      break;
                      case '11-13':
                        echo '11AM-1PM';
                      break;
                      case '13-15':
                        echo '1PM-3PM';
                      break;
                      case '15-17':
                        echo '3PM-5PM';
                      break;
                      case '17-18':
                        echo '5PM-6PM';
                      break;
                      default:
                      echo "Change Is Occur";
                    }
                    
                    ?>)
                  </td>
                  <td>
                  <?php  $status_chk=$dbf->fetchSingle("status",'*',"status_id='$Order[status]'");
                    if($status_chk['status_id']=='1' ||  $status_chk['status_id']=='2' || $status_chk['status_id']=='0'){
                  ?>
                    <button class="btn btn-warning" data-toggle="modal" data-target="#myModals<?= $Order['orders_id']?>"  type="button">
                      <?php  $status=$dbf->fetchSingle("status",'*',"status_id='$Order[status]'");

                        if(empty($status)){ echo "New Orders";}else{ echo $status['name'];}
                      ?>

                    </button>
                    <?php }else{?>
                      <button class="btn btn-warning" data-toggle="modal"  type="button">
                      <?php  $status=$dbf->fetchSingle("status",'*',"status_id='$Order[status]'");

                        if(empty($status)){ echo "New Orders";}else{ echo $status['name'];}
                      ?>
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
  $i=1;

   foreach($dbf->fetchOrder("orders","order_id='$Order[order_id]'  AND status IN(5,6,7)","orders_id DESC","","") as $singleorders){
  $totalqty= $dbf->fetchSingle("orders",'SUM(qty) as total_qty',"order_id='$order[order_id]'");
  $prototal= $dbf->fetchSingle("orders",'SUM(qty*price) as pro_total',"order_id='$order[order_id]'");
  
  ?>
    

    <tr>
    <td><?php echo $i;?></td>
    <td><?= $singleorders['ordername']?></td>
    <td><?= $singleorders['price']?></td>
    <td><?= $singleorders['qty']?></td>
    <td><?php echo $singleorders['price']* $singleorders['qty']; ?>.00</td>
    <td>  <select class="form-control" name="cstatus">
          <?php  foreach($dbf->fetchOrder("status","","","","") as $stauts){?>
          <option value="<?= $stauts['status_id']?>" <?php if($stauts['status_id']==$singleorders['status']){ echo 'selected';}?> <?php if($stauts['status_id']<$singleorders['status'] ){ echo " disabled ";} if($stauts['status_id']=='8'){ echo " disabled ";}?><?php if($stauts['status_id']=='6' || $stauts['status_id']=='5' || $stauts['status_id']=='7'){ echo " disabled ";}?>> <?= $stauts['name']?></option>
        <?php }?>
        </select>
      </td>

    </tr>

  
    <?php $i++; } ?>
    
    
     
    
    
    </table>
   

        </div>

        
    </div>
</div>
<?php }?>
                  </td>
                  <td>
                  <button class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-example<?= $Order['order_id']?>" > View Details</button>
                  
    
                  <div id="modal-example<?= $Order['order_id']?>" uk-modal>
          <div class="uk-modal-dialog uk-modal-body">
                 <div id="print_area<?= $Order['order_id']?>">
          <div class="uk-grid">
             <div style="text-align: center;"  class="uk-width-expand">
             <img src="../admin/images/<?php echo $page['logo'];?>" width="100" >
          </div>
            <div class="uk-width-auto">Order Id : <?= $Order['order_id']?></div>
            <div class="uk-width-expand"></div>
            <div class="uk-width-auto" style="text-align: right;">Date: <?= date('d.m.Y',strtotime($Order['created_date']))?></div>
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
        $CardDetails = $dbf->fetchSingle("orders",'gift_name,gift_price,gift_img',"order_id='$Order[order_id]'");
        $return_Amnt=0;
         foreach($dbf->fetchOrder("orders","order_id='$Order[order_id]' ","orders_id DESC","","") as $singleorder){
        $totalqty= $dbf->fetchSingle("orders",'SUM(qty) as total_qty',"order_id='$Order[order_id]'");
        $prototal= $dbf->fetchSingle("orders",'SUM(qty*price) as pro_total',"order_id='$Order[order_id]'");
        $ReturnAmnt= $dbf->fetchSingle("orders",'qty,price',"orders_id='$singleorder[orders_id]' AND status='5'");
       $return_Amnt=$return_Amnt+$ReturnAmnt['qty']*$ReturnAmnt['price'];
      
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
          <?php if(!empty($CardDetails['gift_name'])){?>
          <tr>
          <td><?=$i?></td>
          <td><?=$CardDetails['gift_name']?>(Gift Card)</td>
          <td><?=number_format($CardDetails['gift_price'],2)?></td>
          <td><?=$CardDetails['gift_price']?></td>
          <td><?=number_format($CardDetails['gift_price'],2)?></td>
          </tr>
       <?php }?>
          <tr>
          <td></td>
          <td></td>
          <td>TOTAL</td>
          <td><b><?php if($CardDetails['gift_name']!=''){ echo $totalqty['total_qty'] +1;}else{ echo $totalqty['total_qty'] ;}?></b></td>
          <td><b><?= $prototal['pro_total']-$return_Amnt?></b></td>
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
          <td><b><?= $singleorder['shipping_charge']?></b></td>
          </tr>
          
          <tr>
          <td></td>
          <td></td>
          <td><span class="uk-text-danger">SUB TOTAL</span></td>
          <td><b></b></td>
          <td><b><?php echo number_format($prototal['pro_total']+$CardDetails['gift_price']+$singleorder['shipping_charge']-$singleorder['wallet']-$return_Amnt-$singleorder['coupon_amnt'],2); ?></b></td>
          </tr>
          </table>
              </div>
              <?php $address = $dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");   ?>
              <?php $city = $dbf->fetchSingle("city",'*',"city_id='$address[city_id]'"); 
                $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$address[pincode]'");
              ?>
              <h5>Address</h5>
             
        <p> 
        Name: <?php echo $singleorder['fname'];?> <?php echo $singleorder['lname'];?><br />
        Contact No: <?php echo $singleorder['num'];?> <br />
        Email : <?php echo $singleorder['email'];?> <br />
        Address: <?php echo $singleorder['adress'];?>, <br />
        Address1: <?php echo $singleorder['adress1'];?>, <br />
        <?php echo $singleorder['city'];?>,  Pin: <?php echo $singleorder['pin'];?>  <br />
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
           

                    <div class="modal fade" id="myModals<?= $Order['orders_id']?>" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Order Status</h4>
        </div>
        <form action="" method="post">
          <input type="hidden" name="action" value="StatusUpdate">
           <input type="hidden" name="order_id" value="<?= $Order['order_id']?>">
        <div class="modal-body">
        <select class="form-control" name="cstatus">
          <?php  foreach($dbf->fetchOrder("status","status_id IN (1,-1,2)","","","") as $stauts){?>
          <option value="<?= $stauts['status_id']?>" <?php if($stauts['status_id']==$Order['status']){ echo 'selected';}?>> <?= $stauts['name']?></option>
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
  </div>     <?php }?>
                </tbody>
                <tfoot>
               <tr>
                  <th scope="col">Sl No</th>
                    <th scope="col">Order Id </th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Delivery By</th>
                     <th scope="col">Payment </th>
                     <th>Delivery Time Slot</th>
                    <th scope="col">Status </th>
                    <th scope="col">View Details </th>
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
  </script>
   <?php include('footer.php')?>

<script>
function getDboyDetails(arg,dboy){
 
 $("#DeBoyMOdalHead").text("ORDER ID: "+arg);
 var url="getAjax.php";
 $.post(url,{"choice":"GetDboy","dboy":dboy},function(res){
//  $('#Shop').html(res);
  //  alert(res);
   res = res.split('!next!');
   $('#DboyName').text(res[0]);
   $('#DboyMail').text(res[1]);
   $('#DboyNum').text(res[2]);
   $('#DboyProfile').html(res[3]);
$('#DboyDetails').modal('show'); 

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
