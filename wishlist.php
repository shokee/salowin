<?php include("header.php"); ?>

<div class="uk-container" >


    
    <table class="uk-table uk-table-divider uk-background-default uk-text-left uk-table-small uk-table-middle uk-table-responsive">
 
  <?php 
  
  $AllWishList=$dbf->fetchOrder("wishlist","user_id='$_SESSION[userid]' ","wishlist_id DESC","","");
  
if(!empty($AllWishList)){
   foreach($AllWishList as $Wishlist){

        $Product= $dbf->fetchSingle("product",'*',"product_id='$Wishlist[product_id]'");
        $all_shop=$Product['vendor_id'];
                            
                                foreach($dbf->fetchOrder("variations_values","product_id='$Product[product_id]' AND vendor_id IN($all_shop)","sale_price ASC","","") as $lowestVndr){
                                    $singlevdrID=$lowestVndr['vendor_id'];
                                    break;
                                }
                                
                                 $All_Variotions=$dbf->fetchOrder("variations_values","product_id='$Product[product_id]' AND vendor_id='$singlevdrID'","sale_price ASC","",""); 
                                 
  ?>
  <tr id="WishRem<?= $Wishlist['product_id']?>" >
    <td >
        <button class="uk-icon-button uk-button-danger uk-float-right" type="button" onclick="RemoVeWsi(<?= $Wishlist['product_id'] ?>)"><span uk-icon="icon: trash"></span></button>
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-auto"><img src="admin/images/product/<?php echo $Product['primary_image'];?>" width="60" /></div>
            <div class="uk-width-expand" ><?= $Product['product_name']?>
            
                  <!--<p></p>-->
                  <br ><br >
                <?php
            foreach($All_Variotions as $varition){
              $Vari_price_single=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$varition[price_variation_id]'");
              $Measure_Single=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price_single[measure_id]'");
              
            ?>
            
            Rs.<?=$varition['sale_price']?> &nbsp; &nbsp;  
            <?= $Vari_price_single['units'].$Measure_Single['unit_name']?> &nbsp; 
            <!--(<?=  round(abs(($varition['sale_price']/$varition['mrp_price']*100)-100))?>%OFF) &nbsp; &nbsp;-->
            <?php }?>
            
            </div> 
      
            
               <select class="uk-select variant uk-margin-small-top uk-margin-small-bottom " hidden  id="variation_id<?=$Product['product_id']?>" onchange="checkAvailablity(this.value,<?=$Product['product_id']?>)" name="variation_id" style="font-size:16px;" >
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
        </div>
        
        
    
    </td>
   
    <!--<td><p>Price: <ins>₹<?= $Product['sales_price'] ?></ins></p></td>-->
    <td> <a class="uk-button-primary uk-width-1-1 uk-button " href="single-product-page.php?editId=<?php echo base64_encode($Product['product_id']);?>" style="text-transform: none;">View Product</a></td>
     
  </tr>
<?php }  
}else{
    ?>
    <tr id="emptymsg">
      <h4 style="text-align:center; margin-top:20px;font-size:24px; "><b>Your  Wishlist is Empty </b> </h4>   
    </tr>
 
    
<?php 
}
     
?>
</table>

 



</div>


<?php 
$footerIcon='Wishlist';
include("footer.php"); ?>
<script type="text/javascript">
     function RemoVeWsi(arg){
// alert(arg)
  var url="getAjax.php";
 

      $("#WishRem"+arg).remove();

  $.post(url,{"choice":"RemoveWish","prod_id":arg,"user_id":<?= $_SESSION['userid'] ?>},function(res){
      location.reload();
});

    }
</script>
