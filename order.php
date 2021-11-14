<?php include("header.php"); ?>

<?php
//Check whether user is logged in or not

 if(isset($_SESSION['userid'])=="")
{
    header("location:login.php");
    exit;
}
?>
<?php 


 if(isset($_REQUEST['opeartion']) && $_REQUEST['opeartion']=='ReturnProducts'){
$order_id = $_POST['prod_ret_id'];
$reason = $_POST['reason'];
$dbf->updateTable("orders","status='6',reason='$reason'","orders_id='$order_id'");
header("Location:order.php");
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

    header("Location:order.php?msg=calcel_order");
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
  

<div class=" uk-height-viewport ">
<div class="uk-container ">

                <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='calcel_order'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Order Cancel Successfully,If You Pay Through Online Or Wallet Then ,amount refund to your wallet.</p>
</div>

<?php }?>
 <table class="uk-table uk-table-divider uk-background-default uk-text-left uk-table-small uk-table-middle  uk-table-responsive">

  
 <?php
    $j=1;
    $Order_status_array=array();
     foreach($dbf->fetchOrder("orders","user_id='$user_ip'"," created_date DESC ","","order_id") as $order){
        unset($Order_status_array);
        foreach($dbf->fetchOrder("orders","order_id='$order[order_id]'","","status","") as $OrderStatus){
        $Order_status_array[]=$OrderStatus['status'];
       
        }
         $Order_staues=implode(',',$Order_status_array);
    ?>
    
  <tr>
    <!--<td>SL No : <?php echo $j++;?></td>-->
    <td>
        
    <div id="offcanvas-overlay1<?= $order['order_id']?>"  uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar uk-width-5-6 uk-background-default ">
    
            <h4 class="uk-text-center uk-margin-remove-top" style="color:#000;">Order Summary
            <!--<?=$Order_staues?>-->
            </h4>
    <div class="uk-grid">
        <div class="uk-width-auto " style="color:#000;">Order Id : <?= $order['order_id']?></div>
        
        <div class="uk-width-auto" style="color:#000;" >Date: <?= date('d.m.Y',strtotime($order['created_date']))?></div>
    </div>
    

        <div  style="border:solid 1px #ccc; overflow: auto;">
        <table class="uk-table uk-table-small uk-table-divider  uk-table-responsive" style="color:#000;">
        <!--<tr >-->
        <!--    <th>Sl No</th>-->
        <!--    <th>Product Details</th>-->
        <!--    <th> Price</th>-->
        <!--    <th> Qty</th>-->
        <!--    <th> Total</th>-->
        <!--</tr>-->
        
         <?php
    $i=1;
     foreach($dbf->fetchOrder("orders","order_id='$order[order_id]' ","orders_id DESC","","") as $singleorder){
    $totalqty= $dbf->fetchSingle("orders",'SUM(qty) as total_qty',"order_id='$order[order_id]'");
    $prototal= $dbf->fetchSingle("orders",'SUM(qty*price) as pro_total',"order_id='$order[order_id]'");
       $ReturnAmnt= $dbf->fetchSingle("orders",'SUM(price) as ret_amnt',"order_id='$order[order_id]' AND status='5'");
    ?>
    
    <tr style="border-bottom: solid 1px #ccc;">
    <!--<td><?php echo $i;?></td>-->
    <td><?= $singleorder['ordername']?><?php if($singleorder['status']=='5'){?><span style="color:red;font-size: 20px;">(Returned)</span><?php }?></td>
    <td>Price: <?= $singleorder['price']?></td>
    <td> Qty : <?= $singleorder['qty']?></td>
  <td><?php if($singleorder['status']=='5'){?><span style="color:red;font-size: 20px;font-weight: bold;">-</span><?php }?>
    Price: <?php echo $singleorder['price']* $singleorder['qty']; ?>.00</td>
    <!--<td><?php if($singleorder['status'] == 4){?><button class="btn btn-danger">Return</button><?php }?></td>-->
    </tr>
    <?php $i++; } ?>
    <tr style="border-bottom: solid 1px #ccc;">
    <td></td>
    <td></td>
    <td>TOTAL  
    <!--<b><?= $totalqty['total_qty']?></b>   -->
    <b class="uk-float-right">Rs. <?= $totalGrnadAMT=$prototal['pro_total']-$ReturnAmnt['ret_amnt']?></b></td>
    <td></td>
    <td></td>
    </tr>
    
<?php if($singleorder['coupon_amnt']!=0){?>
      <tr style="border-bottom: solid 1px #ccc;">
    <td></td>
    <td></td>
    <td>Coupon Amount(<?= $singleorder['coupon_code']?>): <b class="uk-float-right">-<?= number_format($singleorder['coupon_amnt'],2)?></b></td>
    <td></td>
    <td><b></b></td>
    </tr>
  <?php }?>
     <?php if($singleorder['wallet']!=0){?>
     <tr style="border-bottom: solid 1px #ccc;">
    <td></td>
    <td></td>
    <td>WALLET: <b class="uk-float-right">-<?= number_format($singleorder['wallet'],2)?></b></td>
    <td></td>
    <td><b></b></td>
    </tr>
  <?php }?>
     <tr style="border-bottom: solid 1px #ccc;">
    <td></td>
    <td></td>
    <td>SHIPPING CHARGE <b class="uk-float-right">Rs. <?= $order['shipping_charge']?></b></td>
    <td><b></b></td>
    <td></td>
    </tr>
    
          
                  <?php if($singleorder['payment_mode'] == 'cod'){?>
           <tr>
          <td></td>
          <td></td>
          <td>COD Charge: <b class="uk-float-right">&#8377; <?php
          $totalAMTT=$prototal['pro_total']+ $singleorder['shipping_charge']-$singleorder['wallet']-$ReturnAmnt['ret_amnt']-$singleorder['coupon_amnt'];
          $codfee=$totalAMTT * 5 / 100;
          echo number_format($codfee,2);?></b></td>
          <td></td>
          <td><b></b></td>
          </tr>
        <?php }?>
        
       <tr >
    <td></td>
    <td></td>
    <td>SUB TOTAL
    <b class="uk-float-right">
        <?php  
        
        
            if($singleorder['payment_mode'] == 'cod'){
              echo number_format($codfee+$prototal['pro_total']+ $singleorder['shipping_charge']-$singleorder['wallet']-$ReturnAmnt['ret_amnt']-$singleorder['coupon_amnt'],2);
          }else{
              echo number_format($prototal['pro_total']+ $singleorder['shipping_charge']-$singleorder['wallet']-$ReturnAmnt['ret_amnt']-$singleorder['coupon_amnt'],2);
          }
        
        ?>
        </b>
    </td>
    <td><b></b></td>
      <td></td>
    </tr>
    </table>
        </div>
        <?php $address = $dbf->fetchSingle("address",'*',"address_id='$order[address_id]'");   ?>
        <?php $city = $dbf->fetchSingle("city",'*',"city_id='$address[city_id]'");
        $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$address[pincode]'");
         ?>
        <h5 style="color:#000; padding:0px ; margin:0; margin-top:25px; padding-left:5px; ">Address</h5>
       
  <p style="color:#000; padding:5px ;margin:0;"> 
  Name: <?php echo $address['first_name'];?> <?php echo $address['last_name'];?><br />
  Contact No: <?php echo $address['number'];?> <br />
  Email : <?php echo $address['email'];?> <br />
  Address: <?php echo $address['address'];?>, <br />
  <?php echo $city['city_name'];?>,  Pin:  <?php echo $pincode['pincode'];?> <br />
  
  
  <br><br></p>
        <p class="uk-text-right">
           
            <?php if($order['status']<3){?>
            <!--<button class="uk-button uk-button-danger" type="button" onclick="CancelOrder('<?= $order[order_id]?>')">Cancel Order</button>-->
        <?php }?>
        </p>
    
<p>&nbsp;<br></p>
</div></div>
    
    </td>
    
    <td>Order Id : <?= $order['order_id']?></td>
    <td> Date : <?= date('d.m.Y h:i:s a',strtotime($order['created_date']))?></td>
    <td> Payment Mode : <?= $order['payment_mode']?></td>
    <td> Order Amount : 
    <?php echo number_format($prototal['pro_total']+ $singleorder['shipping_charge']-$singleorder['wallet']-$ReturnAmnt['ret_amnt']-$singleorder['coupon_amnt'],2); ?>
    </td>
    <td> Status : 
    <?php 
    if($order['status']=='0'){?>
        <label  class=""  >In Process</label>
    <?php }else if($order['status']=='1'){?>
        <label  class=""  >Order Received</label>
    <?php } else if($order['status']=='2'){?>
        <label  class=""  >Processing</label>
    <?php }else if($order['status']=='3'){?>
        <label  class=""  >Shiped</label>
    <?php }else if($order['status']=='4'){?>
        <label  class=""  >Delivered </label>
    <?php } else if($order['status']=='5'){?>
         <label  class="" >Returned</label>
     <?php } else if($order['status']=='6'){?>
        <label  class="" >Returned Process</label>
     <?php } else if($order['status']=='7'){?>
        <span  style="color:#000" >Return Cancelled</span>
    <?php }else if($order['status']=='8'){?>
        <label  class="" >Canceled</label>
    <?php }else if($order['status']=='9'){?>
        <label  class="" >Transaction Failed</label>
    <?php }else if($order['status']=='10'){?>
        <label  class="" >Shiped</label>
    <?php } else{?>
        <label  class="" >Processing</label>
         <?php }?>
    
    </td>
    <td>
        <button class="uk-button uk-width-1-1 uk-button-primary" uk-toggle="target: #offcanvas-overlay1<?= $order['order_id']?>" style="text-transform: none;">Order Details</button>
    
    
    
    </td>
    
     <?php  

                
     if(in_array('4', explode(',',$Order_staues)) || in_array('5', explode(',',$Order_staues)) || in_array('6', explode(',',$Order_staues)) || in_array('7', explode(',',$Order_staues))){?>
  <td><button class="uk-button uk-button-small uk-button-danger"       uk-toggle="target: #offcanvas-overlay2<?= $order['order_id']?>"    > Return</button>
    
    
    
    
        
        
        
        
        <div id="offcanvas-overlay2<?= $order['order_id']?>" uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar uk-width-5-6 uk-background-default ">
    <div class="uk-grid">
      <div class="uk-width-auto" style="color:#000;">Order Id : <?= $order['order_id']?></div>
      <div class="uk-width-expand"></div>
      <div class="uk-width-auto" style="color:#000;">Date: <?= date('d.m.Y',strtotime($orders['created_date']))?></div>
    </div>
    <hr />
      <h4 class="uk-text-center uk-margin-remove-top" style="color:#000;">Order Summary</h4>
        <div  >
        <table class="uk-table uk-table-small uk-table-divider uk-table-responsive">
        <!--<tr class="uk-background-muted uk-text-secondary">-->
        <!--  <th style="color:#000;">Sl No</th>-->
        <!--    <th style="color:#000;">Product Details</th>-->
        <!--    <th style="color:#000;"> Price</th>-->
        <!--    <th style="color:#000;"> Qty</th>-->
        <!--    <th style="color:#000;"> Total</th>-->
        <!--    <th style="color:#000;" > Return</th>-->
        <!--</tr>-->
        
         <?php
  $i=1;
   foreach($dbf->fetchOrder("orders","order_id='$order[order_id]' ","orders_id DESC","","") as $singleorder){
  $totalqty= $dbf->fetchSingle("orders",'SUM(qty) as total_qty',"order_id='$order[order_id]'");
  $prototal= $dbf->fetchSingle("orders",'SUM(qty*price) as pro_total',"order_id='$order[order_id]'");
  
  ?>
    
    <tr style="border-bottom: solid 1px #ccc;">
    <!--<td><?php echo $i;?></td>-->
    <td>Product Name: <?= $singleorder['ordername']?></td>
    <td> Price Per Unit: <?= $singleorder['price']?></td>
    <td> Qty<?= $singleorder['qty']?></td>
    <td>Price: <?php echo $singleorder['price']* $singleorder['qty']; ?>.00
    
    
    
    </td>
    <td><b>
        <?php
        $today=date('Y-m-d');
        $deliverdate=date('Y-m-d', strtotime($today. ' - 7 days'));
        
        if($singleorder['status']=='4' &&  $deliverdate <= $singleorder['delivery_date']){?>
        <button class="uk-button uk-button-danger" type="button" uk-toggle="target: #modal-return<?=$singleorder[orders_id]?>">Return</button>
    <?php }else if($singleorder['status']=='6'){?>
        <button class="uk-button uk-button-danger " type="button">Return Process</button>
    <?php } else if($singleorder['status']=='7'){?>
           <button class="uk-button uk-button-danger " type="button">Return Canceled</button>
    <?php }else if($singleorder['status']=='5'){?>
           <button class="uk-button uk-button-danger " type="button">Return Complete</button>
    <?php }else{?>
          <button class="uk-button uk-button-danger " type="button">Expire</button>
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

                
<?php 
$footerIcon='Order';
include("footer.php"); ?>

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