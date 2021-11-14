<?php include("header.php"); 
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;
$shop_id=$dbf->checkSqlInjection($_REQUEST['shop']);
$bannerId=base64_decode($bannerId);
$product=$dbf->fetchSingle("product",'*',"product_id='$bannerId'");

$unit = $dbf->fetchSingle("units",'*',"unit_id='$product[unit_id]'");
$all_shop=$product['vendor_id'];
 foreach($dbf->fetchOrder("variations_values","product_id='$bannerId' AND vendor_id IN($all_shop)","sale_price ASC","","") as $lowestVndr){
                                    $singlevdrID=$lowestVndr['vendor_id'];
                                    break;
                                }
                                 $All_Variotions=$dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id='$singlevdrID'","sale_price ASC","","");
?>
<div class="uk-container ">
<!--<div class="uk-card uk-card-small uk-card-body  uk-margin-bottom" style="border-bottom:solid 1px #ccc; margin-top:-10px; ">-->
<!--	<ul class="uk-breadcrumb">-->
<!--    <li><a href="index.php"> Home</a></li>-->
<!--    <li><a href="#"><?php echo $product['product_name'];?></a></li>-->
<!--</ul>-->
<!--</div>-->
<div class="uk-card-   uk-card-small">
<div class="uk-child-width-1-2@s uk-margin-bottom" uk-grid>
    
    <div>
        <h5><?php echo $product['product_name'];?></h5>
        
    </div>
    <div>
    
    
   
          
          
          <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slideshow="ratio: 7:8">
              
              
              <div style="position:absolute; z-index:1; padding5px; background:#1e87f0; color:#fff;padding:5px; text-align: center;">
                  <?php
            foreach($All_Variotions as $varition){
              $Vari_price_single=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$varition[price_variation_id]'");
              $Measure_Single=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price_single[measure_id]'");
            ?>
            <?php }?>

<?=  round(abs(($varition['sale_price']/$varition['mrp_price']*100)-100))?>% Off <br/>

You Save &#8377; <?= round(($varition['mrp_price'] - $varition['sale_price']))?>/- 
    </div>  
    
    

            <ul class="uk-slideshow-items">
                <li>
                    	<?php if($product['primary_image']<>''){?>
        <img src="admin/images/product/<?php echo $product['primary_image'];?>"  uk-cover  >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"  uk-cover  >
        <?php }?>
                </li>
                
                <?php
		$i=1;
		foreach($dbf->fetchOrder("gallery","product_id='$bannerId'","gallery_id ASC","","") as $gallery){
		    
		    
		?>
                
                <li>
                    <img src="admin/images/gallery/<?php echo $gallery['image'];?>" alt="" uk-cover>
                </li>
         <?php $i++; } ?>       
            </ul>

            <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
            <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next"></a>

 <ul class="uk-slideshow-nav uk-dotnav uk-flex-center uk-margin"></ul> 
        </div>
         
          
          
          
          
          



  
    <form > 
   <div class="uk-grid uk-child-width-1-2">
       <?php 
            if(!empty($All_Variotions)){?>
              <form  action="product-category.php<?=$searchId?>" method="POST" >
   <div>
   <h5 class="uk-margin-small">Variation: </h5>
             
         <select style="font-size: 16px;" class="uk-select variant" id="variation_idA" onchange="checkQty(this.value,<?=$product['product_id']?>)" name="variation_id">
            <?php
            foreach($All_Variotions as $varition){
              $Vari_price_single=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$varition[price_variation_id]'");
              $Measure_Single=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price_single[measure_id]'");
            ?>
           
          <option value="<?=$varition['variations_values_id']?>">
            Rs.<?=$varition['sale_price']?> &nbsp; &nbsp;  
            <?= $Vari_price_single['units'].$Measure_Single['unit_name']?> &nbsp; 
            
           </option>

            <?php }?>
          
          </select> 

    
                <?php }else{?>
                	<div class="uk-width-expand ">
                   <ins>&#8377; <?php echo $product['sales_price'];?> /-</ins> 
                    <del><small>&#8377; <?php echo $product['regular_price'];?> /-</small></del>  
                    </div>
                <?php }?>
   
   </div>
   <div>
   <h5 class="uk-margin-small">Quantity: </h5>
   <div class="quantity buttons_added">
	<input type="button" value="-" onclick="AddCartMinus1('<?=$product['product_id']?>')" class="minus" style="height:37px; border-right: solid 1px  #efefef; ">
    <input type="number" step="1" min="1"  id="quantityA" value="1" title="Qty" class="input-text qty text" style="height:37px; padding:0; margin:0px;" size="4" pattern=""  name="qty" id="qty">
    <input type="button" value="+" onclick="AddCartPlus1('<?=$product['product_id']?>')" class="plus" style="height:37px; ">
  </div>
   </div>


  </div>
 
  <div id="newPrice">
  
  </div>
<p></p>



 
 
    <input type="hidden" name="product_id" value="<?= $bannerId?>">
    <input type="hidden" name="shop_id" id="shop_idA" value="<?= $singlevdrID?>" />
    <input type="hidden" name="user_id" id="useridA" value="<?=  $_SESSION['userid'] ?>" />
    <?php  $count_cartA = $dbf->countRows("cart","user_id='$_SESSION[userid]' AND product_id='$product[product_id])'",""); 
           $count_wishA = $dbf->countRows("wishlist","user_id='$_SESSION[userid]' AND product_id='$product[product_id])'","");
    ?>
     <?php
          foreach($All_Variotions as $varition){
              $SingleVariation=$varition['variations_values_id'];
              break;
          }
          
          $checkqty=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$SingleVariation'"); ?>
    
    <?php if(isset($_SESSION['userid'])=="" || $_SESSION['usertype']!="4"){?>
    <div class=" uk-grid-small  uk-child-width-expand@m" uk-grid>
        <div>
        <a class="uk-button uk-button-primary uk-button-large  uk-width-expand uk-text-nowrap" href="login.php"  onclick="return alert('please login.!');">Add to  Cart</a>
        
        </div>
        <div>
			<a class="uk-button uk-button-secondary uk-button-large  uk-width-expand uk-text-nowrap" href="login.php" onclick="return alert('please login.!');">Add to Wishlist</a>
		
        </div>
    </div>
    <?php }else{?>
    <div class=" uk-grid-small  uk-child-width-expand@m" uk-grid>
        <div>
        <button class="uk-button uk-button-warning uk-button-large  uk-width-expand uk-text-nowrap" <?php if($checkqty['qty'] > 0 ){?>style="display:none;"<?php }?>  id="OutofStockButton" type="button" >Out of Stock</button>
        <button class="uk-button uk-button-primary uk-button-large  uk-width-expand uk-text-nowrap" <?php if($checkqty['qty'] <= 0 || $checkqty['qty'] == ''){?>style="display:none;"<?php }?> <?php if($count_cartA != 0){?>style="display:none;"<?php }?>  id="AddCartbtnA" type="button" onclick="Add22Cart1('<?=$product['product_id']?>')">Add to  Cart</button>
        <button class="uk-button uk-button-primary uk-button-large  uk-width-expand uk-text-nowrap " <?php if($checkqty['qty'] <= 0 || $checkqty['qty'] == ''){?>style="display:none;"<?php }?> <?php if($count_cartA == 0){?>style="display:none;"<?php }?>  id="RemoveCartbtnA" type="button" onclick="RemoveCart22('<?=$product['product_id']?>')">Remove from  Cart</button>
        </div>
        <div>
			<button class="uk-button uk-button-secondary uk-button-large  uk-width-expand uk-text-nowrap" <?php if($count_wishA != 0){?>style="display:none;"<?php }?> id="AddWishbtnA" type="button" onclick="Add2Wish1('<?=$product['product_id']?>')">Add to Wishlist</button>
			<button class="uk-button uk-button-secondary uk-button-large  uk-width-expand uk-text-nowrap " <?php if($count_wishA == 0){?>style="display:none;"<?php }?> id="RemoveWishbtnA" type="button" onclick="RemoveWish1('<?=$product['product_id']?>')">Remove from Wishlist</button>
        </div>
    </div>
    <?php }?>
	<p style="font-size:16px;"> <b>MRP:</b> &#8377;<?php echo $varition['mrp_price'] ?> </p>
<p style="font-size:16px;"><b>Delivery Time:</b> 24 to 48 hours  <br>



<b>Buying in Bulk:</b>

    8860630600

</p>

<?php echo $product['description'];?>
   
 <!--        <div></span><button class="uk-button uk-button-primary">Buy Now</button></div> -->
       

</form>     
    </div>
    </div>
    </div>
   
    <div >
    <!--<h3>Other Product  <a href="shop-single-page.php?editId=<?= $_REQUEST['shop']?>" class="uk-button uk-button-small uk-float-right uk-button-danger">View All</a></h3>-->
  
       
       <div class="uk-position-relative uk-visible-toggle " tabindex="-1" uk-slider="center: false">

    <!--<div class="uk-position-relative uk-visible-toggle " tabindex="-1" >-->

    <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-5@m uk-grid-small " uk-grid>
    <!--<ul class="uk-slider-items  uk-grid-small uk-text-center uk-child-width-1-2" uk-grid>-->
    <?php
     $i=1;
     
     $category_id=$dbf->fetchSingle("pro_rel_cat1",'*',"product_id='$product[product_id]'");
     $singleProductID=$product['product_id'];
     $ArryProdId=array();
foreach ($dbf->fetchOrder("pro_rel_cat1","catagory1_id='$category_id[catagory1_id]'","","product_id","") as $prod_id) {
    array_push($ArryProdId, $prod_id['product_id']);
}
$prod_id = implode(',',$ArryProdId);
	foreach($dbf->fetchOrder("product","status='1' AND product_id IN($prod_id)","product_id ASC LIMIT 10","","") as $product){
	    
	    if($product['product_id'] == $singleProductID){
	        continue;
	    }
	    
                if($product['vendor_id'] == '' || $product['vendor_id'] == 0){

                            continue;

                        }else{
                            $all_shop=$product['vendor_id'];
                            
                                foreach($dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id IN($all_shop)","sale_price ASC","","") as $lowestVndr){
                                    $singlevdrID=$lowestVndr['vendor_id'];
                                    break;
                                }
                                 $All_Variotions=$dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id='$singlevdrID'","sale_price ASC","",""); 
               ?>
               
				<?php include('productloop.php'); ?>
                <?php   } $i++; } ?>		
        </ul>
        
    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>

    </div>
    
    
    </div>
   </div>

<?php include("footer.php"); ?>





<script>
    
    function AddCartPlus1(arg){
      
      var url="getAjax.php";
      var shop_id = $('#shop_idA').val();
      var userid = $('#useridA').val();
      var variation_id = $('#variation_idA').val();
      var qty  = $('#quantityA').val();
      qty = parseInt(qty)+1;
  $.post(url,{"choice":"AddCart","prod_id":arg,"user_id":userid,
  "variation":variation_id,"attribute":shop_id,"qty":qty},function(res){
      $('#quantityA').val(qty);
      var count = res.trim()
        if(count=='failed'){
             $('#quantityA').val('0');
        $('#RemoveCartbtnA').css('display','none');
      $('#AddCartbtnA').css('display','');
         return alert('This Product is Out of Stock');
         
      }else{
      $('#CartCountShow').text(count);
      $('#RemoveCartbtnA').css('display','');
      $('#AddCartbtnA').css('display','none');
      }
});

    }

    function AddCartMinus1(arg){
      var url="getAjax.php";
     var shop_id = $('#shop_idA').val();
      var userid = $('#useridA').val();
      var variation_id = $('#variation_idA').val();
      var qty  = $('#quantityA').val();
      if(qty == 0){ return false; }
      qty = parseInt(qty)-1;
  $.post(url,{"choice":"AddCart","prod_id":arg,"user_id":userid,
  "variation":variation_id,"attribute":shop_id,"qty":qty},function(res){
      var count = res.trim()
        if(count=='failed'){
             $('#quantityA').val('0');
        $('#RemoveCartbtnA').css('display','none');
      $('#AddCartbtnA').css('display','');
         return alert('This Product is Out of Stock');
         
      }else{
      $('#CartCountShow').text(count);
    if(qty <= 0){
        $('#quantityA').val('0');
        $('#RemoveCartbtnA').css('display','none');
      $('#AddCartbtnA').css('display','');
    }else{
        $('#quantityA').val(qty);
         $('#RemoveCartbtnA').css('display','');
      $('#AddCartbtnA').css('display','none');
    }
      }

});
    }

    function Add22Cart1(arg){
      var url="getAjax.php";
      var shop_id = $('#shop_idA').val();
      var userid = $('#useridA').val();
      var variation_id = $('#variation_idA').val();
      var qty  = $('#quantityA').val();
  
  $.post(url,{"choice":"AddCart","prod_id":arg,"user_id":userid,
  "variation":variation_id,"attribute":shop_id,"qty":qty},function(res){
      
      $('#quantityA').val(qty);
      var count = res.trim()
      if(count=='failed'){
             $('#quantityA').val('0');
        $('#RemoveCartbtnA').css('display','none');
      $('#AddCartbtnA').css('display','');
         return alert('This Product is Out of Stock');
         
      }else{
      $('#CartCountShow').text(count);
         $('#RemoveCartbtnA').css('display','');
      $('#AddCartbtnA').css('display','none');
      }

});
    }
    
    function RemoveCart22(arg)
    {
       var url="getAjax.php";
      var shop_id = $('#shop_idA').val();
      var userid = $('#useridA').val();
      var variation_id = $('#variation_idA').val();
      var qty  = 0;
  
  $.post(url,{"choice":"AddCart","prod_id":arg,"user_id":userid,
  "variation":variation_id,"attribute":shop_id,"qty":qty},function(res){
      
      $('#quantityA').val(qty);
      var count = res.trim()
      $('#CartCountShow').text(count);
         $('#RemoveCartbtnA').css('display','none');
      $('#AddCartbtnA').css('display','');

});  
    }
    
   function Add2Wish1(arg){
      var url="getAjax.php";
      var variationID =document.getElementById("variation_idA").value;
      
  $.post(url,{"choice":"AddWish","prod_id":arg,"user_id":<?= (isset($_SESSION['userid']))?$_SESSION['userid']:0 ?>,"vari_id":variationID},function(res){
      
      $('#RemoveWishbtnA').css('display','');
      $('#AddWishbtnA').css('display','none');
});
    }

    function RemoveWish1(arg){
// alert(arg)
  var url="getAjax.php";
  var variationID =document.getElementById("variation_idA").value;
 
  $.post(url,{"choice":"RemoveWish","prod_id":arg,"user_id":<?= (isset($_SESSION['userid']))?$_SESSION['userid']:0 ?>,"vari_id":variationID},function(res){
   
     $('#RemoveWishbtnA').css('display','none');
      $('#AddWishbtnA').css('display','');
});
    }
    
    function checkQty(val,arg)
    {
        var userid ='<?= $_SESSION['userid'];?>';
        var url="getAjax.php";
       $.post(url,{"choice":"checkAvailability","prod_id":arg,"user_id":userid,
  "variation":val},function(res){ 
      var result = res.trim()
      if(result=='OutOfStock'){
          
           $('#OutofStockButton').css('display','');
       $('#AddCartbtnA').css('display','none');
       $('#RemoveCartbtnA').css('display','none');
          
      }else if(result=='InCart'){

            $('#OutofStockButton').css('display','none');
       $('#AddCartbtnA').css('display','none');
       $('#RemoveCartbtnA').css('display','');
      
      }else{
          
           $('#OutofStockButton').css('display','none');
       $('#AddCartbtnA').css('display','');
       $('#RemoveCartbtnA').css('display','none');
          
      }
      
  });
    }
    
</script>
