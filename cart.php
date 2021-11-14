<?php include("header.php");?>
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->



<div class="uk-container">
	<div class="uk-grid uk-grid-small">
    
    	<div class="uk-width-3-5@m">
<div class="  uk-border-rounded uk-card-small">
<?php 

if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
    $item_id=$dbf->checkXssSqlInjection($_REQUEST['item_id']);
    $dbf->deleteFromTable("cart","cart_id='$item_id'");
    header("Location:cart.php");
}

if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='Update'){
   $qty=$_POST['qty'];
   $cart_id=$_POST['cart_id'];
   for ($i=0; $i < count($cart_id); $i++) { 
     $dbf->updateTable("cart","qty='$qty[$i]'","cart_id='$cart_id[$i]'");
   }

 header("Location:cart.php");
}



$Carts=$dbf->fetchOrder("cart","user_id='$user_ip'","","","");

if(!empty($Carts)){
  $ArryProd_status=array();
  $CartArry=array();
  $ProdArry=array();
  $Subtotal=0;
  $out_of_stock=0;
foreach ($Carts as $cart) {
   $User_pin=$dbf->fetchSingle("user",'pin',"id='$cart[shop_id]'");
      $Match_pin=$dbf->fetchSingle("pincode",'pincode',"pincode_id='$User_pin[pin]'");

  $products=$dbf->fetchSingle("product",'*',"product_id='$cart[product_id]'");
$Price_Vari=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$cart[variation_id]'");
$Vari_price=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$Price_Vari[price_variation_id]'");
$Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price[measure_id]'");
  array_push($CartArry,$cart['product_id']);
    array_push($ProdArry,$products['product_id']);

?>
<form action="" method="post">
 <table class="uk-table uk-table-divider uk-table-small uk-table-middle  uk-table-responsive " style="border-bottom:solid 1px #ccc;">       	
 
  <input type="hidden" name="operation" value="Update">
  <input type="hidden" name="cart_id[]" value="<?= $cart['cart_id']?>">
  <tr>
    <td>
      
      <div class="uk-grid-small" uk-grid>
          <div class="uk-width-auto">
              <img src="admin/images/product/<?php echo $products['primary_image'];?>" width="60"   />
          </div>
           <div class="uk-width-expand">
               <button type="button" class="uk-icon-button uk-button-danger uk-float-right" onclick="DeleCArtItem(<?= $cart['cart_id']?>)" ><span uk-icon="icon: trash"></span></button> 
    
    <?= $products['product_name']?>-<?= $Vari_price['units'].$Measure['unit_name']?>
    
    
         
          <?php
          
          $remainingqty=$Price_Vari['qty'] - $cart['qty'];
          
          if(empty($Price_Vari) || $remainingqty < 0 || $Price_Vari['qty'] == ''){ 
          $out_of_stock=1;
          ?>
    <div>
      <h6 class="uk-text-danger"> This Product Out Of Stock,Please Select Another Product <?=$Price_Vari['qty']?></h6>
    </div>
    <?php }else{ ?>
    <div class="uk-margin-small" id="cartqtyup<?= $cart['cart_id']?>">
    <?php 
      $Subtotal=$Subtotal+$cart['qty']*$Price_Vari['sale_price'];
      echo 'Price:'.$cart['qty']*$Price_Vari['sale_price'];
      ?>/-  
       </div>
    <?php }?>
    
    <div class="quantity" style="margin-left:0; max-width:100px;">
         <input type="button" value="-" class="qtyminus minus trig" field="quantity">
         <input type="number" min="1" max="<?= $products['stocks']?>" value="<?= $cart['qty']?>" class="uk-input qty"   name="qty[]" onchange="UpdateCart(this.value,<?= $cart['cart_id']?>)" onkeyup="UpdateCart(this.value,<?= $cart['cart_id']?>)"/>
         <input type="button" value="+" class="qtyplus plus trig" field="quantity">
         </div>
         
        
  
          </div>
          
      </div>
    
         
    
         
    </td>
    
    
    
   
    
  </tr>
  <?php if($products['status']=='0' || empty($products)){?>
  <tr>
    <td colspan="6" style="text-align: center;color:red;">This Product Not In Stock,Please Remove And Procced.</td>
  </tr>
<?php }?>

<?php }}else{?>
  <h4 style="text-align:center; margin-top:20px; font-size:24px;">Your Cart is Empty </h4>
<?php }?>

<?php 
//if($Match_pin['pincode']!=''){
//if($Match_pin['pincode']!=$Selected_pin['pincode']){?>
<!-- <tr> <td colspan="6" style="text-align: center;color:red;">All This Product Not Delivery Your Selected Pincode.</td> </tr>
-->
<?php

//}}

//?>

</table>


</form>
</div>
        </div>

     
        <div class="uk-width-2-5@m"  <?php if (in_array("0", $ArryProd_status) || empty($Carts) || !empty($Differ)){?> style=" display: none;" <?php }?>>
       <!--  <div class="uk-background-muted uk-margin-top uk-padding-small">
        	<p>Enter Cupon</p>
            <input type="text" class="uk-input" placeholder="Enter Cupon Code" />
        </div> -->
        
        <div class=" ">
        	<h4 class="uk-grid "><span class="uk-width-expand">Subtotal</span> 	<span class="uk-width-auto" id="cartSubtotal">&#8377;<?= $Subtotal.'.00'?></span></h5>
            <hr />
            <h4>Shipping</h4>
            

<div id="delveryType">     
<?php 
  $deliver_price=0;
  
  $checkShippingCondtn=$dbf->fetchSingle("shipping",'*',"shipping_id='6'");
  
  if($Subtotal > $checkShippingCondtn['price']){
      
      $applicable_shipping=2;
      
  }else{
      $applicable_shipping=1;
  }
foreach ($dbf->fetchOrder("shipping","status='1'","shipping_id DESC","","") as $Shipping) {
    
    if($Shipping['shipping_id'] == $applicable_shipping){
        continue;
    }
    $deliver_price=$Shipping['price'];
  ?>
   <p class="uk-grid uk-grid-small" style="font-size:16px;">
       <span class="uk-width-expand"> 
   
    <input type="hidden" id="shiptype<?= $Shipping['shipping_id']?>" value="<?= $Shipping['name']?>">
    
    <input type="radio" name="delivery_type" onclick="DeliveryType(<?= $Shipping['price']?>,<?= $Subtotal?>)" checked class="procced" value="<?= $Shipping['shipping_id']?>"/> <?= $Shipping['name']?>:</span> <span class="uk-width-auto"> ₹<?= $Shipping['price']?> </span></p>
 
   <?php }?>
   </div>
   <h4>Wallet:</h4>
   <p style="font-size:16px;"> <?php  $Wallet=$dbf->fetchSingle("user","*","id='$user_ip'")?>
   <input type="checkbox" name="wamnt" id="wamnt" value="<?= $Wallet['wallet']?>" onclick="WalletAmount(this.value)"> &nbsp; ₹<?= $Wallet['wallet']?>
   </p>
   <hr>
   <h4>Enter Coupon Code:</h4>
 
        <input type="hidden" name="action" id="action" value="applyCoupon">
    <input type="hidden" name="cuponSubTotal" id="cuponSubTotal" value="<?=$Subtotal?>">
    <div class="uk-grid-small uk-grid-collapse" uk-grid>
    <div class="uk-width-3-4 ">
        <input class="uk-input" type="text" style="font-size:16px;" placeholder="Enter Your Coupon Code"   id="coupon_code">
        <input type="hidden" id="code_amnt">
        <span  style="color:green" id="error_code"></span>
        <!--<span id="succes_code"  style="color:green"></span>-->
    </div>
     <div class="uk-width-1-4">
        <input class="uk-input uk-button-primary uk-light" style="font-size:18px; " type="button" value="Apply" onclick="ApplyCoupon()">
    </div>
</div>
   <!--<h5>Shipping for <b>india</b></h5>-->
        <hr />
        <input type="hidden" id="grandtotalValue" name="grandtotalValue" value="">
         <h4 class="uk-grid uk-grid-small"><span class="uk-width-expand">Total:</span><span class="uk-width-auto" id="total"> 	</span></h4>
       <br />
       <?php if(empty(!$Price_Vari)){?>
       <div class="uk-text-center">
        <?php  if(isset($_SESSION['userid'])!=""){
         if($out_of_stock == 0){
        ?>
       <button class="uk-button  uk-button-Primary uk-width-1-1" style="font-size:18px; height:50px; text-transform: none; "  type="button" onclick="Proceed2Check()"
       >Proceed to Checkout</button>
         <?php }else{?>
          <a class="uk-button  uk-button-secondary uk-width-1-1" style="font-size:18px; height:50px; text-transform: none;" href="#" onclick="return alert('Please remove out of stock Product');" >Proceed to Checkout</a>
     <?php } }else{?>
      <a class="uk-button  uk-button-secondary uk-width-1-1" style="font-size:18px; height:50px; text-transform: none;" href="login.php">Proceed to Checkout</a>
     <?php }?>
        </div>
     <?php }?>
        </div>
    </div>
 
</div>
<p></p>
</div>
<script type="text/javascript">
  function Proceed2Check() {
  var deli_charge=$(".procced:checked").val();
  var wallet=$("#wamnt:checked").val();
  var coupon_code = $('#coupon_code').val();
   var code_amnt = $('#code_amnt').val();
      $("#shipping_id").val(deli_charge);
      $("#WalletAmnt").val(wallet);
      $("#coupon_code1").val(coupon_code);
      $("#code_amnt1").val(code_amnt);
  $("#frmProceed").submit();
  }
</script>


<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='CartNew'){?>
<script>
$(document).ready(function(){
  $('#NewCartModal').modal({
      backdrop: 'static'
    });
    $("#NewCartModal").modal('show');
   
  });
</script>
<?php }?>
<form action="check_out.php" method="post" id="frmProceed">
  <input type="hidden" name="shipping_id" id="shipping_id">
<input type="hidden" name="WallAmnt" id="WalletAmnt">
<input type="hidden" name="coupon_code" id="coupon_code1">
<input type="hidden" name="code_amnt" id="code_amnt1">
</form>


<!-- Button trigger modal -->


<div class="modal fade" id="NewCartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Replace Cart Items?</h5>
      </div>
      <div class="modal-body">
       Your cart contains products from other shop.Do you want to discard the selection and add this product?
      </div>
      <div class="modal-footer">
        <form action="cart_process.php" method="post">
          <input type="hidden" name="action" value="RemtoNEwCart">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button  class="btn btn-primary">Yes</button>
      </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function ApplyCoupon(){
      var coupon_code=$("#coupon_code").val();
      var price = $("#cuponSubTotal").val();
           var url="getAjaxx.php";
  $.post(url,{"choice":"ApplyCoupon","coupon_code":coupon_code,"price":price},function(res){
      
      sub_res=res.substring(0,13);
      sub_str1=res.substring(13,);

    if(res=="Invalid Coupon Code"){
    //   alert(res);
    $('#error_code').text(res);
       $('#code_amnt').val(0);
  }else if(res=="Expired Coupon Code"){
    // alert(res);
        $('#error_code').text(res);
           $('#code_amnt').val(0);
  }else if(sub_res=="CodeAplicable"){
        // alert(res);
    //   $('#error_code').text('Code Applicable Successfuly And Deducted Amount: ₹'+sub_str1);
    $('#error_code').text('Success!! ₹'+sub_str1+' reduced from total. Proceed to Checkout to see the updated Order Amount.');
       
       $('#code_amnt').val(sub_str1);

  }else{
    // alert(res);
    $('#error_code').text(res);
     $('#code_amnt').val(0);
  }

 // $('#cityres').html(res);

// text("Your text here");
});
  

  }

  function UpdateCart(qty,cartid){
     // alert(cartid);
    // $('#cartqtyup'+cartid).css('display','none');
    var url="getAjax.php";
 $.post(url,{"choice":"CartUpdate","qty":qty,"cartid":cartid,"user_ip":"<?= $user_ip?>"},function(res){

  res = res.split("!next!");
  // alert(res[1]);
 $('#cartqtyup'+cartid).html(res[0]);
 $('#cartSubtotal').html(res[1]);
  $('#cuponSubTotal').val(res[1]);
 $('#delveryType').html(res[2]);
 
   DeliveryType(res[3],res[1]);
  });
  }

function WalletAmount(arg)
{
     var price = $("#grandtotalValue").val();
      if ($('#wamnt').prop("checked")) {
    var total =parseFloat(price) -parseFloat(arg);
    if(total < 0){
        total = 0;
    }
    
$("#total").text('₹'+total+".00");
    }else{
        $("#total").text('₹'+price+".00");
    }
    
}



  DeliveryType(<?= $deliver_price?>,<?= $Subtotal?>)
  function DeliveryType(arg,arg1){
      var wallet=$("#wamnt:checked").val();
     
      if(wallet == undefined){
          wallet=0;
      }
   var total =parseFloat(arg)+parseFloat(arg1)-parseFloat(wallet);
   if(total < 0){
       total=0;
   }
$("#total").text('₹'+total+".00");
$("#grandtotalValue").val(total);
  }
</script>

<style>
    .uk-table-divider>:not(:first-child)>tr{border-top: 0px solid #e5e5e5 !important;}
</style>

<?php 
$footerIcon='Cart';
include("footer.php"); ?>





