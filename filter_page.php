<?php include("header.php"); 
?>
<?php 
  ########################## insert  #############################
if(isset($_REQUEST['filter']) && $_REQUEST['filter']=='filterF'){
    $condnt=array();
    $category=$_REQUEST['category'];
    if($category != ''){
          
           $categoryids=implode(',',$category);
            $list_prod_array=array();
    foreach($dbf->fetchOrder("pro_rel_cat1","catagory1_id IN($categoryids)","","","") as $product_id){
    array_push($list_prod_array,$product_id['product_id']);
    }
    $prod_id = implode(',',$list_prod_array);
    $condnt[]=" product_id IN($prod_id) "; 
        
    }
    $brand=$_REQUEST['brands'];
    if($brand != ''){
        $brandids=implode(',',$brand);
        $condnt[]=" brands_id IN($brandids) "; 
    }
    $grouparr=$_REQUEST['group'];
        if($grouparr != ''){
        $groupids=implode(',',$grouparr);
        $condnt[]=" groups_id IN($groupids) "; 
    }
    $type=$_REQUEST['type'];
     if($type != ''){
        $typeids=implode(',',$type);
        $condnt[]=" type_id IN($typeids) "; 
    }
    
    if(!empty($condnt)){
        
        $condnt=' AND '.implode(' AND ',$condnt);
    }else{
       $condnt=''; 
    }
    // echo $condnt;exit;
}
?>

<div class="uk-container">
<div class="uk-grid-small uk-child-width-expand uk-grid-item-match" uk-grid>
    <div>
	<a href="#" class="uk-text-secondary"  uk-toggle="target: #offcanvas-overlay"><span uk-icon="settings"></span> Filter</a>
<!--Filter Start-->
    <div id="offcanvas-overlay" uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar uk-background-default uk-width-5-6">
<form action="" method="post" >
    
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
            <li>  
            <ul class="uk-list uk-text-muted">
              <?php 
             foreach($dbf->fetchOrder("type","","type_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px;"> <input type="checkbox" name="type[]" <?php if (in_array($group['type_id'], $type)) {echo 'checked';}?> value="<?=$group['type_id']?>">  <?= $group['type_name']?></li>
              
             <?php }?>
        </ul></li>
            <li>
                <ul class="uk-list uk-text-muted">
            <?php 
             foreach($dbf->fetchOrder("groups","","groups_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px;"> <input type="checkbox" name="group[]" <?php if (in_array($group['groups_id'], $grouparr)) {echo 'checked';}?> value="<?=$group['groups_id']?>">  <?= $group['groups_name']?></li>
             <?php }?>
          
           
        </ul>
            </li>
            <li><ul class="uk-list uk-text-muted">
              <?php 
             foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_name ASC","","") as $group){
             
             ?>
              <li style="color:#000;  font-size:16px"> <input type="checkbox" name="category[]" <?php if (in_array($group['product_catagory_1_id'], $category)) {echo 'checked';}?> value="<?=$group['product_catagory_1_id']?>">  <?= $group['product_catagory_1_name']?></li>
              
             <?php }?>
        </ul>
        <ul class="uk-list uk-text-muted">
              <?php 
             foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_name ASC","","") as $group){
             
             ?>
              <li style="color:#000;  font-size:16px"> <input type="checkbox" name="category[]" <?php if (in_array($group['product_catagory_1_id'], $category)) {echo 'checked';}?> value="<?=$group['product_catagory_1_id']?>">  <?= $group['product_catagory_1_name']?></li>
              
             <?php }?>
        </ul>
        </li>
            <li>
                <ul class="uk-list uk-text-muted">
                 <?php 
             foreach($dbf->fetchOrder("brands","","brands_name ASC","","") as $group){
             
             ?>
              <li style="color:#000; font-size:16px;"> <input type="checkbox" name="brands[]" <?php if (in_array($group['brands_id'], $brand)) {echo 'checked';}?> value="<?=$group['brands_id']?>">  <?= $group['brands_name']?></li>
              
             <?php }?>
        </ul>
            </li>
        </ul>

    </div>
</div>
    
    
      
        
        

        <button class="uk-button uk-button-primary uk-width-1-1 uk-margin-bottom " type="submit" name="filter" value="filterF" style="color:#fff;">search </button>
         <a href="<?php $_SERVER['PHP_SELF']; ?>" class="uk-button uk-button-primary uk-width-1-1 " type="reset" name="filter" value="filterF" style="color:#fff;">Reset </a>
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
<input type="hidden" value="<?=$condnt?>" id="showProductCondition">
<div class="uk-grid-small uk-child-width-1-1" uk-grid  id="showsortingItemId">
<?php
					$i=1;
					 foreach($dbf->fetchOrder("product"," status='1' AND ( vendor_id IS NOT NULL) ".$condnt,"product_id DESC LIMIT 15","","") as $product){
                    
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
// alert(loadedNo);
});
    } 
</script>

