<li class=" trade-mark uk-text-center">
            <div class="uk-card prdct  ">
            
            <div class="uk-card uk-grid-collapse uk-child-width-expand " uk-grid>
           
            <div class="uk-card-media-left uk-cover-container uk-width-1-3" >
                                    <!--<div class="tag">-->
                                    <!--    <?php $discountPrice=$product['regular_price'] - $product['sales_price']; $discountPercentage=($discountPrice / $product['regular_price'])*100; echo round($discountPercentage); ?>% OFF-->
                                    <!--    </div>-->
                                     <?php   if(isset($_SESSION['userid'])==""){?>
                                      <a  href="login.php" class="wish uk-position-top-right"><span uk-icon="heart"></span></a>
                                     <?php }else{?>
                                    <?php $ChkWist=$dbf->countRows("wishlist","user_id='$_SESSION[userid]' AND product_id='$product[product_id]'");
                                       
                                                  ?>
                                                  
                                                     <a  onclick="Add2Wish(<?= $product['product_id']?>)" <?php if($ChkWist !=0){ echo 'style="display:none;"';} ?>  id="addtowishlist<?= $product['product_id']?>"  class="wish "><span uk-icon="heart"></span></a>
                                   
                                     <a  onclick="RemoVeWsi(<?= $product['product_id']?>)" <?php if($ChkWist ==0){ echo 'style="display:none;"';} ?>  id="removetowishlist<?= $product['product_id']?>" class="wish wish123" ><span uk-icon="heart"></span></a>

                                   <?php  } ?>
            <a href="single-product-page.php?editId=<?php echo base64_encode($product['product_id']);?>" onclick="RecentlyViews(<?= $product['product_id']?>)" class="uk-text-secondary">
             <?php if($product['primary_image']<>''){?>
        <img src="admin/images/product/<?php echo $product['primary_image'];?>" alt="<?php echo $product['product_name'];?>"  >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"   >
        <?php }?> 
        </a>
        
                                </div> 
             

        
            <div class="uk-card-body" style="padding:5px 5px 5px 25px; background:#fff;">
            <a href="single-product-page.php?editId=<?php echo base64_encode($product['product_id']);?>" onclick="RecentlyViews(<?= $product['product_id']?>)" class="uk-text-secondary">
                <h5 class="uk-margin-remove  uk-text-left" style="width:80%" ><?php echo $product['product_name'];?></h5>
                </a>
               
                <div >
                    
                
                <?php 
                    $total_rev = $dbf->countRows("rating_review","product_id='$product[product_id]' AND status='1'","");
                    $TotalRate=$dbf->fetchSingle("rating_review",'SUM(rating) as rate ',"product_id='$product[product_id]' AND status='1'");
                    $Rate = $TotalRate['rate']/$total_rev;
				    $ratepercent= $Rate/5*100
                ?>
              <?php $cartcount=$dbf->fetchSingle("cart",'qty',"user_id='$user_ip' AND  product_id='$product[product_id]' AND attribute_id='$Atribite_id' ORDER BY prior DESC"); ?>
                  <!--  <div class="ratting">
                   <img src="images/star.png" class="star" />
                   <div class="color" style="width:<?=  $ratepercent;?>%;"></div>
    				 </div> -->

             <?php 
            if(!empty($All_Variotions)){?>
              <form  action="" method="POST" style="text-align: left;">
         <select class="uk-select variant uk-margin-small-top uk-margin-small-bottom " style="width:80%; font-size:16px;" id="variation_id<?=$product['product_id']?>" onchange="checkAvailablity(this.value,<?=$product['product_id']?>)" name="variation_id" >
            <?php
            foreach($All_Variotions as $varition){
              $Vari_price_single=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$varition[price_variation_id]'");
              $Measure_Single=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price_single[measure_id]'");
            ?>
           
          <option value="<?=$varition['variations_values_id']?>">
            Rs.<?=$varition['sale_price']?> &nbsp; &nbsp;  
            <?= $Vari_price_single['units'].$Measure_Single['unit_name']?> &nbsp; 
            <!--(<?=  round(abs(($varition['sale_price']/$varition['mrp_price']*100)-100))?>%OFF) &nbsp; &nbsp;-->
           </option>

            <?php }?>
          
          </select> 

    
                <?php }else{?>
                	<div class="uk-width-expand ">
                   <ins>&#8377; <?php echo $product['sales_price'];?> /-</ins> 
                    <del><small>&#8377; <?php echo $product['regular_price'];?> /-</small></del>  
                    </div>
                <?php }?>

                <input type="hidden" name="shop_id" id="shop_id<?=$product['product_id']?>" value="<?= $singlevdrID?>" />
         <!--<input type="hidden" name="product_id"  value="<?= $product['product_id'] ?>" />-->
         <input type="hidden" name="user_id" id="userid<?=$product['product_id']?>" value="<?=  $_SESSION['userid'] ?>" />

             <div class="uk-grid-small uk-child-width-expand" uk-grid>

             <div class="uk-width-1-1 ">
 <?php
          foreach($All_Variotions as $varition){
              $SingleVariation=$varition['variations_values_id'];
              break;
          }
          
          $checkqty=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$SingleVariation'"); ?>
                 
                <?php
      if(isset($_SESSION['userid'])=="" || $_SESSION['usertype']!="4"){
     ?>      
     
     <a href="login.php" class="uk-button uk-button-primary   uk-border-rounded " style="white-space: nowrap; text-transform: none; "  >Add to Cart</a>
      <?php }else{
      $count_cart = $dbf->countRows("cart","user_id='$_SESSION[userid]' AND product_id='$product[product_id]'","");
      ?>
      
                 <div style="width:80%;"> <div class="quantity" id="QtyBox<?=$product['product_id']?>" <?php if($count_cart == 0){?>style="display:none;"<?php }?> <?php if($checkqty['qty'] <= 0 || $checkqty['qty'] == ''){?>style="display:none;"<?php }?>>
          <input type="button" value="-" class="qtyminus minus trig" onclick="AddCartMinus('<?=$product['product_id']?>')" field="quantity">
          <input type="text" name="quantity" id="quantity<?=$product['product_id']?>" value="1" class="qty" min="1">
          <input type="button" value="+" onclick="AddCartPlus('<?=$product['product_id']?>')" class="qtyplus plus trig" field="quantity">
          </div>
          </div>
          
         
          
      <button type="button" id="outofstock<?=$product['product_id']?>" <?php if($checkqty['qty'] > 0 ){?>style="display:none;"<?php }?> class="uk-button uk-button-warning  uk-border-rounded " style="white-space: nowrap;" >Out of Stock</button>
       <button type="button" id="AddCartBtn<?=$product['product_id']?>" <?php if($checkqty['qty'] <= 0 || $checkqty['qty'] == ''){?>style="display:none;"<?php }?> <?php if($count_cart != 0){?>style="display:none;"<?php }?> class="uk-button uk-button-primary  uk-border-rounded " onclick="Add22Cart('<?=$product['product_id']?>')" style="white-space: nowrap;" value="addcart" name="submit" >Add to Cart</button>
       <?php }?>
                 
                 
                 
             </div>
          </div>
                </form>   
                   
                   <!-- <div class="uk-width-1-1">

                    <div class="qtybtt" id="QtyBox<?=$Atribite_id?>" <?php if(empty($cartcount)){?>style="display:none;"<?php }?>>
                    <input type="button" value="-" class="qty-minus"  onclick="AddCartMinus(<?= $product['product_id']?>,<?=$Atribite_id?>)">
                    <input type="number" min="0" max="" value="<?=($cartcount['qty']!="")?$cartcount['qty']:1?>" class="uk-input qtt qty " name="qty" id="proQty<?=$Atribite_id?>" onkeyup="Add2Cart(<?= $product['product_id']?>,<?=$Atribite_id?>)">
                    
                    <input type="button" value="+" class="qty-plus" onclick="AddCartPlus(<?= $product['product_id']?>,<?=$Atribite_id?>)">
                    </div>

                </div> -->
                
                
            </div>
            
        </div>
        </div>
        </div>
        </li>