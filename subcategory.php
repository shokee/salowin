<?php include("header.php"); 

$cate=$dbf->checkSqlInjection($_REQUEST['cate']);
$cate_id=base64_decode($cate);
$categoryDtls=$dbf->fetchSingle("product_catagory_1",'*',"product_catagory_1_id='$cate_id'");

?>
     
     
      
           <div class="uk-container">
               <h4>Shop by Category</h4>
        <div class="uk-grid-small uk-child-width-1-3 uk-text-center" uk-grid>
        <?php
            $i=1;
             foreach($dbf->fetchOrder("product_catagory_2","product_catagory_1_id='$categoryDtls[product_catagory_1_id]'","product_catagory_2_id ASC","","") as $product_catagory_1){
            ?>
            
    <div>
     <a href="product-category.php?subcat=<?php echo base64_encode($product_catagory_1['product_catagory_2_id']);?>" class="category" >
        <div class="uk-card uk-card-default uk-border-rounded border" style="overflow:hidden">
            <img src="admin/images/subcategory/<?= $product_catagory_1['img']?>" class="">
            <div class="uk-overlay uk-position-cover uk-padding-small rdd">
                <p><?= $product_catagory_1['product_catagory_2_name']?></p>
            </div>
            
        </div>
        </a>
    </div>
   		<?php } ?>
</div>
            </div>
     
     
     <style>
         
 .rdd{    padding: 5px;
    background: rgb(0,0,0);
background: linear-gradient(0deg, rgba(0,0,0,0.7371323529411764) 0%, rgba(0,0,4,0.4374124649859944) 27%, rgba(0,0,0,0) 100%);
}
.rdd p{
   position: absolute;
    bottom: 5px !important;
    left: 0px;
    color: #fff !important;
    font-size: 16px;
    width: 100%;
    
}
     </style>
     
      
      <?php include("footer.php"); ?>