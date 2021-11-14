<span>
             <a href="#" uk-toggle="target: #offcanvas-cart">
			 <?php  $Carts=$dbf->fetchOrder("cart","user_id='$user_ip'","","","");?>
            <button uk-icon="icon: cart;"> </button><span class="uk-badge md"><?= count($Carts)?></span> 
            <strong></strong>
             </span> 
            </a>
             
    
    
<div id="offcanvas-cart" uk-offcanvas=" overlay: true; flip: true ">
    <div class="uk-offcanvas-bar uk-background-primary">
    
    <table class="uk-table uk-table-divider  uk-text-center uk-table-small uk-table-middle uk-table-striped ">
  <?php  
   $Subtotal=0;
if(!empty($Carts)){
     
foreach ($Carts as $cart) {
     $products=$dbf->fetchSingle("product",'*',"product_id='$cart[product_id]'");

$Price_Vari=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$cart[variation_id]'");
$Vari_price=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$Price_Vari[price_variation_id]'");
$Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price[measure_id]'");

  ?>
  <tr>
    <td width="100"><img src="admin/images/product/<?php echo $products['primary_image'];?>" width="60" height="60" /></td>
    <td><a href="#" style="color:black;"><?= $products['product_name']?>-<?= $Vari_price['units'].$Measure['unit_name']?></a></td>
    <td><?= $cart['qty']?></td>
    <td>Price: <ins><?php 
      $Subtotal=$Subtotal+$cart['qty']*$Price_Vari['sale_price'];
      echo $cart['qty']*$Price_Vari['sale_price'].'.00';?></ins>
</td>
    
    <!-- <td>
   <button class="uk-icon-button uk-button-danger" type="button" onclick="DeleCArtItem(<?//= $cart['cart_id']?>)"><span uk-icon="icon: trash" class="uk-icon"></span></button>
    
    </td>-->
  </tr>
<?php }}else{?>
  <tr><td colspan="6" style="text-align: center;color:red;">Cart Is Empty</td></tr>
<?php }?>
    <tr>
        	<td></td>
        	<td colspan="2"><b>Sub-Total</b></td>
            <td><?= $Subtotal.".00";?></td>
            <td></td>
        </tr>
      
         <tr>
        	
            <td colspan="5"> <a href="cart.php" class="btn btn-primary"><span uk-icon="cart" class="uk-icon"></span> View Cart</a>
        	</td>
            
        </tr>
</table>
    </div>
    </div>