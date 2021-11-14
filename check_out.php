<?php include("header.php"); 
$ship_id= $_POST['shipping_id'];
$shipping=$dbf->fetchSingle("shipping",'*',"shipping_id='$ship_id'");
?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCm-V7-i7-hH5pI0nMxjb6l064Ma30xt-Q"></script>
<div class="checkout">
    <div class="uk-container ">
    
     <form action="orderProcess.php" method="post" onsubmit="return CheckAdress_id();">
        <input type="hidden" name="shipChar" value="<?= $shipping['price']?>">
          <input type="hidden" name="shiptype" value="<?= $shipping['name']?>">
        <input type="hidden" name="action" value="newOrders">
        
        
            <?php 
          $cart_shop_id=$dbf->fetchSingle("cart",'*',"user_id='$user_ip'");
        $cart_city_id=$dbf->fetchSingle("user",'*',"id='$cart_shop_id[shop_id]'");
		$All_addres=$dbf->fetchOrder("address","user_id='$user_ip' ","","","");
    // AND pincode='$cart_city_id[pin]' AND is_delte='0'
            ?>
            
               <div class="uk-grid-small" uk-grid>
                   <div class="uk-width-1-1">
                    <table class="uk-table uk-table-divider uk-table-small">
                    <thead>
                    <!--<tr>-->
                    <!--    <th >Product Image</th>-->
                    <!--    <th >Product</th>-->
                    <!--    <th >Total</th>-->
                    <!--</tr>-->
                        </thead>
                        
                        <tbody>
                            <?php $Carts=$dbf->fetchOrder("cart","user_id='$user_ip'","","","");
                            if(!empty($Carts)){
                                  $ArryProd_status=array();
                                  $CartArry=array();
                                  $ProdArry=array();
                                  $Subtotal=0;
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
                        <tr >
                <td><img src="admin/images/product/<?php echo $products['primary_image'];?>" style="width:100px;" /></td>      
            <td ><?= $products['product_name']?>,<?= $Vari_price['units'].$Measure['unit_name']?>   <strong>× <?= $cart['qty']?></strong>  </td>                                                                                   
            <td > <?php if(empty($Price_Vari)){?>
              <h3 class="uk-text-danger"> This Product Out Of Stock,Please Select Another Product</h3>
  <?php   }else{ 
      $Subtotal=$Subtotal+$cart['qty']*$Price_Vari['sale_price'];
      echo '&#8377;'. $cart['qty']*$Price_Vari['sale_price'].".00";}?></td>
                    </tr>
                <?php }}?>

                        </tbody>
                        </table>
                        
                    <table class="uk-table uk-table-small uk-table-divider">
  <tr>
   
    <td><h5>Subtotal</h5></td>
    <td style="text-align:right;">&#8377; <ins><?= number_format($Subtotal,2)?></ins></td>
  </tr>
    <!-- Coupon Code -->
    <?php if($_POST['code_amnt']!='0' && $_POST['code_amnt']!=''){
    $Subtotal = $Subtotal-$_POST['code_amnt'];
    ?>
  
   <tr>
    
    <td><h5>Coupon Amount(<?= $_POST['coupon_code'] ?>)</h5></td>
    <td style="text-align:right;"><ins><?= '-'.number_format($_POST['code_amnt'],2)?></ins></td>
      <input type="hidden" name="coupon_code" value="<?= $_POST['coupon_code']?>">
      <input type="hidden" name="code_amnt" value="<?= $_POST['code_amnt']?>">
  </tr>
<?php }else{?>
 <input type="hidden" name="coupon_code" value="">
 <input type="hidden" name="code_amnt" value="0">
<?php }?>
  <!-- Coupon Code -->
    <?php if($_POST['WallAmnt']!=''){?>
   <tr>
    
    <td><h5>Wallet Amount</h5></td>
    <?php  if($_POST['WallAmnt']<$Subtotal+$shipping['price']){?>
    <td style="text-align:right;">&#8377; 
      <ins><?= '-'.number_format($_POST['WallAmnt'],2)?></ins>
      <input type="hidden" name="wamntdeduc" value="<?= $_POST['WallAmnt']?>">
    </td>
  <?php }else{?>
    <td style="text-align:right;">&#8377; <ins><?= '-'.number_format($Subtotal+$shipping['price'],2)?></ins></td>
      <input type="hidden" name="wamntdeduc" value="<?= $Subtotal+$shipping['price']?>">
  <?php }?>
  </tr>
<?php }else{?>
     <input type="hidden" name="wamntdeduc" value="0">
   <?php }?>
  

  <tr>
    
    <td><h5>Shipping Charge</h5></td>
    <td style="text-align:right;">&#8377; <ins><?= number_format($shipping['price'],2)?></ins></td>
  </tr>
  <hr />
    <tr id="extracodfee" style="display:none;">
        
           <?php
   $Total_amn= $Subtotal+$shipping['price']-$_POST['WallAmnt'];
    if($Total_amn<0){
      $Total_amn=0;
    }else{
      $Total_amn= $Subtotal+$shipping['price']-$_POST['WallAmnt'];
    }
     ?>
    
    <td><h5>COD Charge</h5></td>
    <td style="text-align:right;">&#8377; <ins><?php
   $codfee= $Total_amn * 5 /100;
    
    echo number_format($codfee,2); ?></ins></td>
  </tr>
  <tr id="defaultGrnadTotal">
    
    <td><h5>Total</h5></td>
 
   <td style="text-align:right;">&#8377; <ins><?= number_format($Total_amn,2)?></ins></td>
  </tr>
  
    <tr id="CodfeeGrnadTotal" style="display:none;" >
    
    <td><h5>Total</h5></td>

 
   <td  style="text-align:right">&#8377; <ins><?= number_format($Total_amn + $codfee,2)?></ins></td>
  </tr>
  
  
</table>
</div>
                </div>
                    
               
        
                <div>
                
                <div class="uk-grid uk-child-width-1-2 uk-grid-small "id="AddNewAddres">
               <div>
                <input type="text" placeholder="First Name" class="uk-input uk-margin-bottom" required="" name="fnane" id="fname" />
                <span id="fnameError" class="uk-text-danger"></span>
               </div>
               <div>
                <input type="text" placeholder="Last Name" class="uk-input uk-margin-bottom" required="" name="lanme" id="lname" />
                <span id="lnameError" class="uk-text-danger"></span>
               </div>
               <div>
               
                <select class="uk-select uk-margin-bottom" required=""  name="cname" id="cname"  onchange="GetPincode(this.value)">
                    <?php foreach($dbf->fetchOrder("city","","","","")as $city){?>
                    <option value="<?= $city['city_id']?>"><?= $city['city_name']?></option>
                <?php }?>
                </select>
                <span id="cnameError" class="uk-text-danger"></span>
               </div>
               
               <div>
    
                <select class="uk-select" required="" name="zcode" id="zcode" >
                    <?php foreach($dbf->fetchOrder("pincode","status = '1'","","","")as $Pincode){?>
                    <option value="<?= $Pincode['pincode_id']?>"><?= $Pincode['pincode']?></option>
                <?php }?>
                </select>
                <span id="zcodeError" class="uk-text-danger"></span>
               </div>
               
                <div>
                <input type="email" placeholder="Email Id" class="uk-input uk-margin-bottom" required name="email"  id="email" />
                <span id="emailError" class="uk-text-danger"></span>
               </div>
               
                <div>
                <input type="tel" placeholder="Phone" class="uk-input uk-margin-bottom" required name="phone" id="phone" />
                <span id="phoneError" class="uk-text-danger"></span>
               </div>
               
                <div class="uk-width-1-1">
                <!-- <textarea class="uk-textarea uk-margin-bottom" placeholder="Address" required name="address" id="address"></textarea> -->
               
                <textarea id="search_location" name="address" class="uk-textarea search_addr" placeholder="Enter your Address" required  > </textarea>
                <span id="search_locationError" class="uk-text-danger"></span>
                <!-- <div id="myMap" style="width: 100%; height:200px;"></div> -->
                    <input type="hidden" class="search_addr"   name="address">
                    <input type="hidden" class="search_latitude" id="lat"  name="lat">
                    <input type="hidden" class="search_longitude" id="lng"  name="lng">
               </div>
              
               </div>
               
                     <div class="uk-grid uk-child-width-1-2 uk-grid-small" style="font-size:16px;" >
                    <?php if($Total_amn!=0){?>
                    <div>
                <input type="radio" name="payment_type"  value="online"  onclick="Deliverytype(1)" <?php if ($Total_amn != 0) {
                   echo "checked";
                   }?> required/> Pay Online
               </div>
             <?php }?>
               <div>
                   <input type="radio" name="payment_type" value="cod" onclick="Deliverytype(0)" / required <?php if ($Total_amn==0) {
                   echo "checked";
                   }?>> <i class="fas fa-money-check-alt" ></i>  Cash On Delivery 
               </div>
               </div>
               <h4>Billing Address</h4>
               <div class="uk-grid uk-child-width-1-1 uk-grid-small" >
                <?php if(count($All_addres)!='0'){?>
              <div>
              <hr />

                  <button class="uk-button uk-button-primary uk-width-1-1" type="button" onclick="AddnewAddre()" id="AddNewAdd">Add New  Address</button>
                   <button class="uk-button uk-button-danger uk-width-1-1" type="button" onclick="CancelNeAdds()" id="CancelNewAds" style="display: none;">Cancel</button>
                   <hr />
              </div> 
          <?php }?>
            <?php if(count($All_addres)!='0'){
              ?>
                <div class="addesss_id" style="font-size:16px;" >
                  <?php foreach($dbf->fetchOrder("address","user_id='$user_ip' AND is_delte='0'","","","")as $addres){ 
                    $city=$dbf->fetchSingle("city",'*',"city_id='$addres[city_id]'");
                    $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$addres[pincode]'");
                     $checkPincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$addres[pincode]' AND status = '1'");
                    ?>
                <div class="uk-card uk-card-body uk-background-muted uk-card-small uk-margin-bottom ">

                  <input type="radio" name="adresss_id"  value="<?= $addres['address_id']?>" required class="adresss_id" <?php  if(empty($checkPincode)){ echo "disabled";}?> > 
        <?= $addres['first_name'].' '.$addres['last_name'].','.$addres['email'].',<br>'.$addres['number'].','.$city['city_name'].','.$addres['address'].','.$pincode['pincode']?>
        <?php 
        if(empty($checkPincode)){ ?>
        <br><span colspan="6" style="text-align: center;color:red;">Unable to deliver on this address.</span><?php }?>


      </div>
               <?php }?>
                </div>
                <span class="uk-text-danger" id="AdressIds"></span>
            <?php }?>
                  </div>
                </div>
        
       
    
    <input type="hidden" name="grand_total" id="grand_total" value="<?php echo $sub_price;?>">
    <div class="uk-container">
    <div class=" uk-margin-bottom uk-margin-top uk-padding-remove uk-panel">
   
    <?php  if(isset($_SESSION['userid'])!=""){?>

 <button         class="uk-button uk-button-Primary uk-button-large uk-align-right uk-width-1-1" type="submit" id="Codchk" style="display:none"> Proceed  to Checkout <span uk-icon="icon: arrow-right"></span></button>
    <button      class="uk-button uk-button-Primary uk-button-large uk-align-right uk-width-1-1" type="button" id="Onlinechk"  onclick="PayOnLine()"> Proceed  to Checkout <span uk-icon="icon: arrow-right"></span></button> 
    <!--<button  class="uk-button uk-button-secondary uk-button-large uk-align-right uk-width-1-1" type="submit"> proceed  to Checkout <span uk-icon="icon: arrow-right"></span></button>-->
   <?php }else{?>
    <a  class="btn btn-success uk-align-right" href="login.php"> Login to Checkout <span uk-icon="icon: arrow-right"></span></a>

   <?php }?>
</div>
  </div>
</form>
</div>
<?php include("footer.php"); ?>

<script type="text/javascript">
  <?php if(count($All_addres)!='0'){?>
 CancelNeAdds();
 <?php }else{?>
    AddnewAddre();
      <?php }?>
function AddnewAddre() {
     $('.addesss_id').css('display','none');
     $(".adresss_id").attr("required",false);
  document.getElementById('AddNewAddres').style.display="";
document.getElementById('AddNewAdd').style.display="none";
document.getElementById('CancelNewAds').style.display="";
document.getElementById("fname").setAttribute("required", "");
document.getElementById("cname").setAttribute("required", "");
document.getElementById("lname").setAttribute("required", "");
document.getElementById("zcode").setAttribute("required", "");
document.getElementById("email").setAttribute("required", "");
document.getElementById("phone").setAttribute("required", "");
document.getElementById("search_location").setAttribute("required", "");



}
function CancelNeAdds(){
     $('.addesss_id').css('display',"");
     $(".adresss_id").attr("required",true);
  document.getElementById('AddNewAddres').style.display="none";
document.getElementById('AddNewAdd').style.display="";
document.getElementById('CancelNewAds').style.display="none";

document.getElementById("fname").removeAttribute("required");
document.getElementById("lname").removeAttribute("required");
document.getElementById("zcode").removeAttribute("required");
document.getElementById("email").removeAttribute("required");
document.getElementById("phone").removeAttribute("required");
document.getElementById("cname").removeAttribute("required");
document.getElementById("search_location").removeAttribute("required");



}

function CheckAdress_id(){
     var fnameChk = $('#fname').attr('required');
    var AdressIds = $('#AdressIds');
    AdressIds.text('');
    var address_id = $("input[name='adresss_id']:checked").val();
    
     if(fnameChk ==undefined && typeof address_id === "undefined"){
         
      AdressIds.text('Please Select Address*.');
      return false;
    }
    
}

function PayOnLine(){
    
 var fnameChk = $('#fname').attr('required');
  var fnameError=$('#fnameError');
  var lnameError=$('#lnameError');
  var cnameError=$('#cnameError');
  var zcodeError=$('#zcodeError');
  var emailError=$('#emailError');
  var phoneError=$('#phoneError');
  var search_locationError=$('#search_locationError');
  var AdressIds = $('#AdressIds');

  fnameError.text('');
  lnameError.text('');
  cnameError.text('');
  zcodeError.text('');
  emailError.text('');
  phoneError.text('');
  search_locationError.text('');
  AdressIds.text('');
  // alert(fnameChk);
  var numPattern   = /^([6,7,8,9])+([0-9]{9})+$/;

    var  fname=$('#fname').val();
    var  lname=$('#lname').val();
    var  cname=$('#cname').val();
    var  zcode=$('#zcode').val();
    var  email=$('#email').val();
    var  phone=$('#phone').val();
    var address_id = $("input[name='adresss_id']:checked").val();
    var  search_location=$('#search_location').val();
    
        var lat               =$('#lat').val();
    var lng               =$('#lng').val();

  if(fnameChk!=undefined){
    if(!fname){
      fnameError.text('First Name Fields Is Required *.');
    }else if(!lname){
      lnameError.text('Secondary Phone no Fields Is Required *.');
    }else if(!cname){
      cnameError.text('City Name Fields Is Required *.');
    }else if(!zcode){
      zcodeError.text('Pincode Fields Is Required *.');
    }else if(!email){
      emailError.text('Email Fields Is Required *.');
    }else if(!phone){
      phoneError.text('Phone Fields Is Required *.');
    }
    else if(!search_location){
      search_locationError.text('Address Fields Is Required *.');
    }
    else{
      if(!numPattern.test(phone)){
        phoneError.text('Invalid Phone Number.');
      }else{
      var payuForm = $('#payuForm');
       payuForm.submit();
      }
    }
  }else{
    if (typeof address_id === "undefined") {
      AdressIds.text('Please Select Address*.');
      
    }else{
      var payuForm = $('#payuForm');
        payuForm.submit();
    }
  }
  $('#onfname').val(fname);
  $('#onlname').val(lname);
  $('#oncity').val(cname);
  $('#onpin').val(zcode);
  $('#onemail').val(email);
  $('#onnum').val(phone);
  $('#onlocad').val(search_location);
  $('#onaddress').val(address_id);
  $('#onloc').val(lat);
  $('#onloc1').val(lng);

}


</script>


<form action="orderProcess.php" method="POST" id="payuForm">
    
      <input type="hidden" name="shipChar" value="<?= $shipping['price']?>">
          <input type="hidden" name="shiptype" value="<?= $shipping['name']?>">
        <input type="hidden" name="action" value="newOrders">
              <input type="hidden" name="coupon_code" value="<?= $_POST['coupon_code']?>">
      <input type="hidden" name="code_amnt" value="<?= $_POST['code_amnt']?>">
      <input type="hidden" name="payment_type" value="online">
      
      <input type="hidden" name="adresss_id" id="onaddress">
      
<input type="hidden" name="fnane" id="onfname">
<input type="hidden" name="lanme" id="onlname">
<input type="hidden" name="cname" id="oncity">
<input type="hidden" name="zcode" id="onpin">
<input type="hidden" name="email" id="onemail">
<input type="hidden" name="phone" id="onnum">
<input type="hidden" name="lat" id="onloc">
<input type="hidden" name="lng" id="onloc1">
<input type="hidden" name="address" id="onlocad">

  <!-- Coupon Code -->
    <?php if($_POST['WallAmnt']!=''){
 if($_POST['WallAmnt']<$Subtotal+$shipping['price']){?>
      <input type="hidden" name="wamntdeduc" value="<?= $_POST['WallAmnt']?>">
  <?php }else{?>
      <input type="hidden" name="wamntdeduc" value="<?= $Subtotal+$shipping['price']?>">
  <?php }
  }else{?>
     <input type="hidden" name="wamntdeduc" value="0">
   <?php }?>
    
<?php 
  $User=$dbf->fetchSingle("user",'full_name,contact_no,email',"id='$user_ip'");
?>
<script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="rzp_live_hSlqtD8QBnfyUq" 
    data-amount="<?=$Total_amn*100;?>" 
    data-currency="INR"
    data-buttontext="Pay with Razorpay"
    data-name="Salowin"
    data-description="Purchasing Products"
    data-image="admin/images/<?php echo $page['logo'];?>"
    data-prefill.name="<?= $User['full_name']?>"
    data-prefill.email="<?= $User['email']?>"
    data-prefill.contact="<?= $User['contact_no']?>"
    data-theme.color="#E92D2C"
>

</script>
</form>
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



function GetPincode(arg){

var url="getAjax.php";
  $.post(url,{"choice":"changPin","value":arg},function(res){
 $('#zcode').html(res);
 // alert(res)
});
}

function Deliverytype(arg){
  
  if(arg=='1'){
   $('#Codchk').css('display','none');
  $('#Onlinechk').css('display','');
  
  $('#extracodfee').css('display','none');
   $('#defaultGrnadTotal').css('display','');
  $('#CodfeeGrnadTotal').css('display','none');
  
  }else{
    $('#Codchk').css('display','');
  $('#Onlinechk').css('display','none');
  
    $('#extracodfee').css('display','');
   $('#defaultGrnadTotal').css('display','none');
  $('#CodfeeGrnadTotal').css('display','');
  
  }
  
}
</script>

<style>
    .razorpay-container{z-index:1000000000000000000000000000000000;}
</style>