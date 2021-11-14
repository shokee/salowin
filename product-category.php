<?php include("header.php"); 
$type=$dbf->checkSqlInjection($_REQUEST['type']);
$group=$dbf->checkSqlInjection($_REQUEST['group']);
$brand=$dbf->checkSqlInjection($_REQUEST['brand']);
$subcat=$dbf->checkSqlInjection($_REQUEST['subcat']);
if($type != ''){
    $searchId='?type='.$type;
    $type_id=base64_decode($type);
    $typen=$dbf->fetchSingle("type",'*',"type_id='$type_id'");
    $condnt="AND  type_id='$typen[type_id]'";
}elseif($group != ''){
    $searchId='?group='.$group;
    $group_id=base64_decode($group);
    $groupDtls=$dbf->fetchSingle("groups",'*',"groups_id='$group_id'");
    $condnt=" AND  groups_id='$groupDtls[groups_id]'";

}elseif($brand != ''){
    $searchId='?brand='.$brand;
    $brand_id=base64_decode($brand);
    $brandDtls=$dbf->fetchSingle("brands",'*',"brands_id='$brand_id'");
    $condnt=" AND  brands_id='$brandDtls[brands_id]'";

}elseif($subcat != ''){
    $searchId='?subcat='.$subcat;
    $subcat_id=base64_decode($subcat);
    $subcatDtls=$dbf->fetchSingle("product_catagory_2",'*',"product_catagory_2_id='$subcat_id'");
    $list_prod_array=array();
    foreach($dbf->fetchOrder("pro_rel_cat2","catagory2_id='$subcatDtls[product_catagory_2_id]'","","","") as $product_id){
    array_push($list_prod_array,$product_id['product_id']);
    }
    $prod_id = implode(',',$list_prod_array);
    $condnt=" AND  product_id IN($prod_id)";
}else{
    header("Location:index.php");
}
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
    <div class="uk-offcanvas-bar uk-background-default uk-width-5-6">
<form action="filter_page.php" method="post" >
    
    
    
    <div class="uk-grid-small" uk-grid>
    <div class="uk-width-1-2">

        <ul class="uk-nav uk-nav-default" uk-switcher="connect: #component-nav; animation: uk-animation-fade">
            <li><a href="#" style="color:#000; border-bottom:solid 1px #ccc; "> Type</a></li>
            <li><a href="#" style="color:#000; border-bottom:solid 1px #ccc;" > Group</a></li>
            <li><a href="#" style="color:#000; border-bottom:solid 1px #ccc;" > Category</a></li>
            <li><a href="#" style="color:#000; border-bottom:solid 1px #ccc;" > Brand</a></li>
        </ul>

    </div>
            <div class="uk-width-expand">
                <ul id="component-nav" class="uk-switcher">
                    <li style="height:420px; overflow: scroll; "><ul class="uk-list uk-text-muted">
              <?php 
             foreach($dbf->fetchOrder("type","","type_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px;"> <input type="checkbox" name="type[]" value="<?=$group['type_id']?>">  <?= $group['type_name']?></li>
              
             <?php }?>
        </ul></li>
                    <li style="height:420px; overflow: scroll; "><ul class="uk-list uk-text-muted">
            <?php 
             foreach($dbf->fetchOrder("groups","","groups_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px; "> <input type="checkbox" name="group[]" value="<?=$group['groups_id']?>">  <?= $group['groups_name']?></li>
              
             <?php }?>
           
        </ul></li>
                    <li style="height:420px; overflow: scroll; "><ul class="uk-list uk-text-muted">
              <?php 
             foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px;"> <input type="checkbox" name="category[]" value="<?=$group['product_catagory_1_id']?>">  <?= $group['product_catagory_1_name']?></li>
              
             <?php }?>
        </ul></li>
                    <li style="height:420px; overflow: scroll; ">
            <ul class="uk-list uk-text-muted">
                 <?php 
             foreach($dbf->fetchOrder("brands","","brands_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px;"> <input type="checkbox" name="brands[]" value="<?=$group['brands_id']?>">  <?= $group['brands_name']?></li>
              
             <?php }?>
        </ul>
        </li>
                </ul>
            </div>
        </div>
    
    
    
    
    
    
    
    
    
    
    
 
        

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
    <select class="uk-input" onchange="SortItemAccordingly(this.value)" id="sortItemFltr"  style="margin-top: -10px;" >
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
					 foreach($dbf->fetchOrder("product"," status='1' AND ( vendor_id IS NOT NULL) ".$condnt,"product_id DESC LIMIT 15","","") as $product){
                        // if($product['vendor_id'] == '' || $product['vendor_id'] == 0){

                        //     continue;

                        // }
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
