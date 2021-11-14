<div class="uk-card uk-card-body uk-card-small uk-card-default uk-position-bottom uk-position-fixed" style="border-top:solid 1px #ccc; padding:15px 10px;  z-index:100000000">
     <div class="uk-child-width-expand uk-grid-small    uk-text-center" uk-grid>
    <div>
       <a href="index.php" class="uk-text-secondary">	<img <?php if($footerIcon == 'Home'){ ?> src="images/explore.png"<?php }else{ ?>src="images/Vector.jpg"  <?php }?> width="25" height="25"><br>
       Home
       </a>
       
    </div>
    	<div>
	 <a href="wishlist.php" class="uk-text-secondary" ><img <?php if($footerIcon == 'Wishlist'){ ?>src="images/footericon/icon1.png"<?php }else{ ?>src="images/wishlist.png"<?php }?>  width="25" height="25"><br>
     	Wishlist
     </a>
     
</div>
    	<div>
	 <a href="wallet.php" class="uk-text-secondary" >	<img <?php if($footerIcon == 'Wallet'){ ?>src="images/footericon/icon3.png"<?php }else{ ?>src="images/wallet.png"<?php }?> width="25" height="25"><br>
     	Wallet
     </a>
     
</div>

    <div>
        <a href="order.php" class="uk-text-secondary">	<img <?php if($footerIcon == 'Order'){ ?>src="images/footericon/icon.png"<?php }else{ ?>src="images/order.png"<?php }?> width="25" height="25"><br>
        Orders</a>
    	
    </div>

<div>
	 <span id="CartChange" >
            <a href="cart.php" class="uk-text-secondary" >
			 <?php  $Carts=$dbf->fetchOrder("cart","user_id='$user_ip'","","","");?>
            <img  <?php if($footerIcon == 'Cart'){ ?>src="images/footericon/icon2.png"<?php }else{ ?>src="images/cart.png"<?php }?>  width="25" height="25" ><span class="uk-badge md" id="CartCountShow"><?= count($Carts)?></span> 
            <strong></strong>
             </span> <br>
             Cart
            </a>
    </span>
     
</div>


</div>
     </div>
     <div class="uk-card uk-card-body uk-margin-top "></div>
     
     
     

<?php include("sidebar.php"); ?>

      <!-- jQuery 3 -->


<!-- Bootstrap 3.3.7 -->
<script>
     SubmitLocation()
function SubmitLocation(){
var city = $('#loc_city_id').val();
var pin = $('#loc_selected_pin').val();
var SelectaRea = $('#SelectaRea').val();
var city_error = $('#loc_city_erro');
var pin_error = $('#loc_pin_erro');

var pin_text =$( "#loc_selected_pin option:selected" ).text();
// alert(pin);
city_error.html('');
pin_error.html('');
if(city==''){
  $("#SetSubmitLoc").removeClass("uk-modal-close");
city_error.html('Please Select The City.');
}else if(pin==''){
    $("#SetSubmitLoc").removeClass("uk-modal-close");
pin_error.html('Please Select The Pincode.')
}else{
  // alert('ll');
 $("#SetSubmitLoc").addClass("uk-modal-close");
  if(typeof All_in_onfun === 'function'){
    All_in_onfun(city,pin);
  }
  $('#pinChange').html(pin_text);
  var url="getAjax.php";
  $.post(url,{"choice":"SelctedCity","city_id":city,"pin":pin,"SelectaRea":SelectaRea},function(res){
    // console.log(res);
    res = res.split('!next!');
 $('#Loc_Vendor').html(res[0]);
 $('#ArearUpdate').html(res[1]);
 $('#Gobackid2').css('display','');
 $('#Gobackid1').css('display','none');
 Array_of_json = JSON.parse(res[2]);
 $("input#myInput").autocomplete({
   
            source: Array_of_json,
            focus: function (event, ui) {
                $(event.target).val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $(event.target).val(ui.item.label);
                window.location = ui.item.value;
                return false;
            }
            });
// alert(res);
});
}
}

    
</script>


<script type="text/javascript">


  function DeleCArtItem(arg){
    $("#operation").val('delete');
    $("#item_id").val(arg);
 
       $("#frm_item").submit();
    
  }

  

  function RecentlyViews(arg){
    var user_ip='<?= $user_ip?>';
    var url="getAjax.php";
 $.post(url,{"choice":"ClickRecentViews","user_ip":user_ip,"prod_id":arg},function(res){
});
  }
  
  
  
  
function Add2Wish(arg){
      var url="getAjax.php";
      var variationID =document.getElementById("variation_id"+arg).value;
  $.post(url,{"choice":"AddWish","prod_id":arg,"user_id":<?= (isset($_SESSION['userid']))?$_SESSION['userid']:0 ?>,"vari_id":variationID},function(res){
    //   location.reload();
    //   $('#newWishbutton').html(res);
    // $("#favoriteH").addClass("error");
    $('#removetowishlist'+arg).css('display','');
    $('#addtowishlist'+arg).css('display','none');
      
    
//alert("wishlisted.!!");
});
    }

    function RemoVeWsi(arg){
// alert(arg)
  var url="getAjax.php";
 var variationID =document.getElementById("variation_id"+arg).value;
 
  $.post(url,{"choice":"RemoveWish","prod_id":arg,"user_id":<?= (isset($_SESSION['userid']))?$_SESSION['userid']:0 ?>,"vari_id":variationID},function(res){
    //   location.reload();
    //   $('#newWish').html(res);
    
     $('#addtowishlist'+arg).css('display','');
      $('#removetowishlist'+arg).css('display','none');
//alert("Removed.!!");
});
    }
  
  
  
</script>
                <form name="frm_item" id="frm_item" action="" method="post">
                    <input type="hidden" name="operation" id="operation" value="">
                    <input type="hidden" name="item_id" id="item_id" value="">
                </form>
    
              
    
 

 <script>
         jQuery(document).ready(($) => {
         $('.quantity').on('click', '.plus', function(e) {
             let $input = $(this).prev('input.qty');
             let val = parseInt($input.val());
             $input.val( val+1 ).change();
         });
     
         $('.quantity').on('click', '.minus', 
             function(e) {
             let $input = $(this).next('input.qty');
             var val = parseInt($input.val());
             if (val > 1) {
                 $input.val( val-1 ).change();
             } 
         });
     });
     </script>

<script>
    
        function AddCartPlus(arg){
      
      var url="getAjax.php";
      var shop_id = $('#shop_id'+arg).val();
      var userid = $('#userid'+arg).val();
      var variation_id = $('#variation_id'+arg).val();
      var qty  = $('#quantity'+arg).val();
      qty = parseInt(qty)+1;
  $.post(url,{"choice":"AddCart","prod_id":arg,"user_id":userid,
  "variation":variation_id,"attribute":shop_id,"qty":qty},function(res){
       $('#quantity'+arg).val(qty);
      var count = res.trim()
        if(count=='failed'){
              $('#QtyBox'+arg).css('display','none');
      $('#AddCartBtn'+arg).css('display','');
         return alert('This Product is Out of Stock');
         
      }else{
      $('#CartCountShow').text(count);
      $('#QtyBox'+arg).css('display','');
      $('#AddCartBtn'+arg).css('display','none');
// console.log(res);
}

});

//   setInterval('location.reload()', 2000);

    }

    function AddCartMinus(arg){
      var url="getAjax.php";
      var shop_id = $('#shop_id'+arg).val();
      var userid = $('#userid'+arg).val();
      var variation_id = $('#variation_id'+arg).val();
      var qty  = $('#quantity'+arg).val();
      qty = parseInt(qty)-1;
  $.post(url,{"choice":"AddCart","prod_id":arg,"user_id":userid,
  "variation":variation_id,"attribute":shop_id,"qty":qty},function(res){
      var count = res.trim()
        if(count=='failed'){
              $('#QtyBox'+arg).css('display','none');
      $('#AddCartBtn'+arg).css('display','');
         return alert('This Product is Out of Stock');
      }else{
      $('#CartCountShow').text(count);
    if(qty == 0){
         $('#QtyBox'+arg).css('display','none');
      $('#AddCartBtn'+arg).css('display','');
    }else{
         $('#quantity'+arg).val(qty);
      $('#QtyBox'+arg).css('display','');
      $('#AddCartBtn'+arg).css('display','none');
    }
      }
// console.log(res);

});
//   setInterval('location.reload()', 2000);
    }

    function Add22Cart(arg){
      var url="getAjax.php";
      var shop_id = $('#shop_id'+arg).val();
      var userid = $('#userid'+arg).val();
      var variation_id = $('#variation_id'+arg).val();
        qty = 1;
  
  $.post(url,{"choice":"AddCart","prod_id":arg,"user_id":userid,
  "variation":variation_id,"attribute":shop_id,"qty":qty},function(res){
      
      $('#quantity'+arg).val(1);
      var count = res.trim()
      if(count=='failed'){
            $('#QtyBox'+arg).css('display','none');
      $('#AddCartBtn'+arg).css('display','');
         return alert('This Product is Out of Stock');
      }else{
          $('#CartCountShow').text(count); 
        $('#QtyBox'+arg).css('display','');
       $('#AddCartBtn'+arg).css('display','none');
      }
});
//   setInterval('location.reload()', 2000);
    }
    
    
    function checkAvailablity(val,arg)
    {
         var userid = $('#userid'+arg).val();
        var url="getAjax.php";
       $.post(url,{"choice":"checkAvailability","prod_id":arg,"user_id":userid,
  "variation":val},function(res){ 
      var result = res.trim()
      if(result=='OutOfStock'){
          
           $('#outofstock'+arg).css('display','');
       $('#AddCartBtn'+arg).css('display','none');
       $('#QtyBox'+arg).css('display','none');
          
      }else if(result=='InCart'){

           $('#outofstock'+arg).css('display','none');
       $('#AddCartBtn'+arg).css('display','none');
       $('#QtyBox'+arg).css('display','');
      }else{
          
          $('#outofstock'+arg).css('display','none');
       $('#AddCartBtn'+arg).css('display','');
       $('#QtyBox'+arg).css('display','none');  
          
          
      }
      
  });
        
    }
    
</script>

<style>
    body{font-size:12px;}
    .header_fixed{
        position:fixed;
        top:0;
        left:0;
        right:0;
        z-index:10;
    }
</style>

</body>
</html>