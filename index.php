<?php include("header.php"); ?>
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
header("Location:index.php?msg=success");
}?>


<div class="uk-container uk-margin-bottom">
    <div class="uk-grid-small" uk-grid>
        <div class="uk-width-expand">
               
        
       <div class="uk-position-relative uk-visible-toggle uk-text-center" tabindex="-1" uk-slider>

    <ul class="uk-slider-items uk-child-width-1-3 uk-child-width-1-3@m uk-grid-small"  uk-grid>
            
               
                <?php
            $i=1;
             foreach($dbf->fetchOrder("type","","type_id","","") as $type){
            ?>
            <li >
            
    <div>
     <a href="product-category.php?type=<?php echo base64_encode($type['type_id']);?>" class="category" >
        <div class="uk-card uk-card-default uk-border-rounded border" style="overflow:hidden font-size:14px;">
            <img src="admin/images/brands/<?= $type['type_image']?>"  width="32">
            
             <?= $type['type_name']?>   
            
            
        </div>
        
        </a>
    </div>
      </li>
   		<?php } ?>
            
          
             
            
            
        </ul>

       

    </div>

   </div>
   </div>



            </div>
            
            
            
            
     
     <div class="uk-container">
          <div class="uk-grid-small" uk-grid>
        <div class="uk-width-expand">
            <div class="uk-position-relative uk-visible-toggle  " tabindex="-1" uk-slideshow="animation: pull">

            <ul class="uk-slideshow-items " style="border-radius: 10px;">
                <?php
         $i=1;
         foreach($dbf->fetchOrder("banner","","banner_id DESC","","") as $resBanner){
         ?>
      
        <a href="<?php echo $resBanner['url'];?>"> <img src="admin/images/banner/<?php echo $resBanner['banner_image'];?>" alt="<?php echo $resBanner['banner_title'];?>" uk-cover></a>
      
      <?php $i++;}?>
                
            </ul>

          <ul class="uk-slideshow-nav uk-dotnav uk-flex-center uk-margin"></ul>  

        </div>
        </div>
        </div>
        </div>
        <section class="uk-section uk-background-default  uk-padding-remove-left uk-padding-remove-right   " style="padding:5px 5px 25px 5px;">
            <div class="uk-container">
               <h5 style="padding:25px 5px 5px 5px; margin:0;">Shop By  Group</h5>
        
        <div class="uk-position-relative uk-visible-toggle " tabindex="-1" uk-slider="center: false">
    <div class="uk-position-relative uk-visible-toggle " tabindex="-1">
        <ul class="uk-slider-items  uk-grid-small uk-text-center" uk-grid>
            
               
            <?php
             $i=1;
             foreach($dbf->fetchOrder("groups","","groups_id","","") as $group){
            ?>
            <li class="uk-width-1-3">
            
    <div>
     <a href="product-category.php?group=<?php echo base64_encode($group['groups_id']);?>" class="category" >
        <div class="uk-card uk-card-default uk-border-circle border" style="overflow:hidden ">
            <img src="admin/images/brands/<?= $group['groups_image']?>" class="">
            
            
        </div>
        
               <p style="font-size:16px;" class="uk-margin-remove uk-text-secondary"> <?= $group['groups_name']?></p>
            
        </a>
    </div>
      </li>
   		<?php } ?>
            
          
             
            
            
        </ul>

       

    </div>

   

</div>

            </div>
            </section>
        <!--end shop by group-->
        
        <!--start by category  -->
        <section class="uk-section uk-background-default  uk-padding-remove-left uk-padding-remove-right   " style="padding:5px;">
           <div class="uk-container">
               <div class="uk-grid-small" uk-grid>
                   <div class="uk-width-expand"><h5 style="padding:25px 5px 5px 5px; margin:0;"> Shop by Category
               </h5></div>
                   <div class="uk-width-auto"><a href="all_category.php" class="uk-button uk-button-primary uk-button-small uk-border-rounded" style="margin-top:25px;">View All</a></div>
               </div>
               
               
        <div class="uk-grid-small uk-child-width-1-3 uk-text-center" uk-grid>
        <?php
        $productCountOfCategory=array();
        foreach($dbf->fetchOrder("product_catagory_1","","","","") as $product_catagory_1){
            
            $productCountOfCategory[$product_catagory_1['product_catagory_1_id']]=$dbf->countRows("pro_rel_cat1","catagory1_id='$product_catagory_1[product_catagory_1_id]'","");
            
        }
        arsort($productCountOfCategory);
            $catNo=1;
             foreach($productCountOfCategory as $key => $val){
                 if($catNo >= 13){
                     break;
                 }
                 $product_catagory_1=$dbf->fetchSingle("product_catagory_1",'*',"product_catagory_1_id='$key'");
            ?>
            
    <div>
     <a href="subcategory.php?cate=<?php echo base64_encode($product_catagory_1['product_catagory_1_id']);?>" class="category" >
        <div class="uk-card uk-card-default uk-border-rounded border" style="overflow:hidden">
            <img src="admin/images/category/<?= $product_catagory_1['img']?>" class="">
            <div class="uk-overlay uk-position-cover uk-padding-small rdd">
                <p class="uk-margin-remove uk-padding-remove damar" ><?= $product_catagory_1['product_catagory_1_name']?></p>
            </div>
            
        </div>
        </a>
    </div>
   		<?php $catNo++; } ?>
</div>
            </div>
      </section>
        <!--end shop by category-->
        
        <!--start shop by brand-->
        
            <section class="uk-section uk-background-default  uk-padding-remove-left uk-padding-remove-right   " style="padding:5px; padding-top:20px; padding-bottom:20px; background:#F0F6FB;">
            <div class="uk-container">
                <div class="uk-grid-small" uk-grid>
                   <div class="uk-width-expand"><h5 style="padding:25px 5px 5px 5px; margin:0;">Shop By Brands</h5></div>
                   <div class="uk-width-auto"><a href="brand_page.php" class="uk-button uk-button-primary uk-button-small uk-border-rounded" style="margin-top:15px;">View All</a></div>
               </div>
               
        
        
                 <ul class=" uk-grid-small  uk-text-center uk-child-width-1-3  uk-grid-collapse" uk-grid>
                <?php
            $i=1;
             foreach($dbf->fetchOrder("brands","display_in_home='1'","brands_id","","") as $brand){
            ?>
            <li style="border:solid 1px #F0F6FB;">
            
    <div>
     <a href="product-category.php?brand=<?php echo base64_encode($brand['brands_id']);?>" class="category" >
        <div  style="overflow:hidden;">
            <img src="admin/images/brands/<?= $brand['images']?>" class="">
            
            
        </div>
        <!--<?= $brand['brands_name']?>-->
        </a>
    </div>
      </li>
   		<?php } ?>
            
          
             
            
            
        </ul>

       
        
   

            </div>
            </section>   
  
      
      <section class="uk-section uk-section-small uk-padding-small uk-padding-remove-left uk-padding-remove-right">
           <div class="uk-container">
               <h5>Deal Of the Day</h5>
        
        <div class="uk-position-relative uk-visible-toggle " tabindex="-1" uk-slider="center: false">

    <div class="uk-position-relative uk-visible-toggle " tabindex="-1">

        <ul class="uk-slider-items  uk-grid-small uk-text-center uk-child-width-1-2 uk-grid-match" uk-grid>
          <?php
               $i=1;
                                 $date_time=date('Y-m-d H:i');
        
               foreach($dbf->fetchOrder("product","status='1' AND today_dealing_date_time>='$date_time'","product_id ASC","","") as $product){
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

       

    </div>

   

</div>

            </div>
      </section>
      
     
    <style>
       

.border{ border:solid 1px #ccc;}
.padding5 { padding:5px !important;}
    </style> 
   
      
      <?php
      $footerIcon='Home';
      include("footer.php"); ?>