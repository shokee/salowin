<?php include("header.php");

$s= $_REQUEST['s'];
$ArryProdId=array();
foreach ($dbf->fetchOrder("product","product_name LIKE '%$s%'","","product_id","") as $prod_id) {
    array_push($ArryProdId, $prod_id['product_id']);
}
$prod_id='';
$prod_id = implode(',',$ArryProdId);
$BrandArry=array();

foreach ($dbf->fetchOrder("brands","brands_name LIKE '%$s%'","","brands_id","") as $brand_id) {
    array_push($BrandArry, $brand_id['brands_id']);
}
$bran_id='';
$bran_id = implode(',',$BrandArry);
if($prod_id != ''){
 $condnt=" AND  product_id IN($prod_id)";
}
 if($bran_id != ''){
     if($condnt == ''){
 $condnt=" AND  brands_id IN($bran_id)";
     }else{
       $condnt.=" OR  brands_id IN($bran_id)";   
     }
}

if($prod_id == '' && $bran_id ==''){
$categoryArray=array();
 foreach($dbf->fetchOrder("product_catagory_2","product_catagory_2_name LIKE '%$s%'","","product_catagory_2_id","") as $product_catagory_1){
array_push($categoryArray, $product_catagory_1['product_catagory_2_id']);
}
$cat_id='';
$cat_id = implode(',',$categoryArray);
$ArryProdId=array();
 foreach($dbf->fetchOrder("pro_rel_cat2","catagory2_id IN($cat_id)","","product_id","") as $product_catagory_1){
array_push($ArryProdId, $product_catagory_1['product_id']);
}
$prod_id = implode(',',$ArryProdId);
if($prod_id != ''){
 $condnt=" AND  product_id IN($prod_id)";
}
}
// echo $condnt;exit;
?>
<input type="hidden" value="<?=$condnt?>" id="showProductCondition">

<?php 
  ########################## insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addcart'){
    
$variation_id=trim($dbf->checkXssSqlInjection($_REQUEST['variation_id']));
$shop_id=trim($dbf->checkXssSqlInjection($_REQUEST['shop_id']));
$product_id=trim($dbf->checkXssSqlInjection($_REQUEST['product_id']));
$user_id=trim($dbf->checkXssSqlInjection($_REQUEST['user_id']));
$qty=trim($dbf->checkXssSqlInjection($_REQUEST['quantity']));

$checkCart=$dbf->fetchSingle("cart",'*',"user_id='$user_id' AND product_id='$product_id' AND variation_id='$variation_id' AND shop_id='$shop_id'");
if(!empty($checkCart)){
    $qty=$qty + $checkCart['qty'];
    $dbf->updateTable("cart","qty='$qty'","cart_id='$checkCart[cart_id]'");

}else{
	
$string="shop_id='$shop_id', product_id='$product_id', user_id='$user_id', qty='$qty', variation_id='$variation_id', created_date=NOW()";
$dbf->insertSet("cart",$string);
}
header("Location:product-category.php".$searchId);
}?>
<div class="uk-container">
<div class="uk-grid-small uk-child-width-expand uk-grid-item-match" uk-grid>
    <div>
	<a href="#" class="uk-text-secondary"  uk-toggle="target: #offcanvas-overlay"><span uk-icon="settings"></span> Filter</a>
<!--Filter Start-->
    <div id="offcanvas-overlay" uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar uk-background-default">
<form action="filter_page.php" method="post" >
       <h5 style="color:#000; margin:0; padding:0;"> Shop by Type</h5>
        <ul class="uk-list uk-text-muted">
              <?php 
             foreach($dbf->fetchOrder("type","","type_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px;"> <input type="checkbox" name="type[]" value="<?=$group['type_id']?>">  <?= $group['type_name']?></li>
              
             <?php }?>
        </ul>
        
         <h5 style="color:#000; margin:0; padding:0;"> Shop by Group</h5>
        <ul class="uk-list uk-text-muted">
            <?php 
             foreach($dbf->fetchOrder("groups","","groups_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px; "> <input type="checkbox" name="group[]" value="<?=$group['groups_id']?>">  <?= $group['groups_name']?></li>
              
             <?php }?>
           
        </ul>
        
         <h5 style="color:#000; margin:0; padding:0;"> Shop by Category</h5>
        <ul class="uk-list uk-text-muted">
              <?php 
             foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px;"> <input type="checkbox" name="category[]" value="<?=$group['product_catagory_1_id']?>">  <?= $group['product_catagory_1_name']?></li>
              
             <?php }?>
        </ul>
        
         <h5 style="color:#000; margin:0; padding:0;"> Shop by Brand</h5>
        <ul class="uk-list uk-text-muted">
                 <?php 
             foreach($dbf->fetchOrder("brands","","brands_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px;"> <input type="checkbox" name="brands[]" value="<?=$group['brands_id']?>">  <?= $group['brands_name']?></li>
              
             <?php }?>
        </ul>

        <button class="uk-button uk-button-primary uk-width-1-1 uk-margin-bottom " type="submit" name="filter" value="filterF" style="color:#fff; text-transform: none; "> Search </button>
        <button class="uk-button uk-button-primary uk-width-1-1 " type="reset" name="filter" value="filterF" style="color:#fff; text-transform: none; "> Reset </button>
        </form>
        <div class="uk-padding"></div>
       
    </div>
</div>
    <!--Filter End-->
	</div>
    <div></div>
    <div>
    <select class="uk-input" onchange="SortItemAccordingly(this.value)" id="itemLoadedNo"  style="margin-top: -10px;" >
        <option value="">Sort</option>
    	<option value="1">Low To High</option>
        <option value="2">High To Low </option>
    </select>
    </div>
</div>
	
 
<hr />

<div class="uk-grid-small uk-child-width-1-1" uk-grid id="showsortingItemId">
<?php
					$i=1;
					 foreach($dbf->fetchOrder("product"," status='1' AND ( vendor_id IS NOT NULL) ".$condnt,"product_id DESC","","") as $product){
                   
                            $all_shop=$product['vendor_id'];
                            
                                foreach($dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id IN($all_shop)","sale_price ASC","","") as $lowestVndr){
                                    $singlevdrID=$lowestVndr['vendor_id'];
                                    break;
                                }
                                 $All_Variotions=$dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id='$singlevdrID'","sale_price ASC","","");
                            
                             
                                // include("productloop.php");
                                
                                include("productlooplist.php");
                                

                               $i++; } ?>
                              
</div>
<input type="hidden" id="itemLoadedNo" value="15">
<br />
<div class="uk-text-center">
<button class="uk-button uk-button-primary uk-margin-auto" style="text-align:center;" onclick="LoadMoreItem()">Load More</button>
</div>
</div>

<?php include("footer.php"); ?>
<script>
    function SortItemAccordingly(arg)
    {
        
        var condition = $('#showProductCondition').val();
        var url="getAjax.php";
        var loadedNo=0;
        $.post(url,{"choice":"SortItemAccordingly","sort":arg,"condition":condition,"loadedNo":loadedNo},function(res){
            var result=res.trim();
        $('#showsortingItemId').html(result);
        loadedNo=parseInt(loadedNo) + 15 ;
        $('#itemLoadedNo').val(loadedNo);
// alert(result);
});
    }
    
        function LoadMoreItem()
    {
        
        var condition = $('#showProductCondition').val();
        var loadedNo=$('#itemLoadedNo').val();
        var url="getAjax.php";
        var arg=$('#sortItemFltr').val();
        $.post(url,{"choice":"SortItemAccordingly","sort":arg,"condition":condition,"loadedNo":loadedNo},function(res){
            var result=res.trim();
        $('#showsortingItemId').append(result);
        loadedNo=parseInt(loadedNo) + 15 ;
         $('#itemLoadedNo').val(loadedNo);
// alert(condition);
});
    }
    
</script>
