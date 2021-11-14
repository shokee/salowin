<?php include("header.php"); ?>
<section class="uk-section uk-background-default  uk-padding-remove-left uk-padding-remove-right   " style="padding:5px;">
           <div class="uk-container">
               <div class="uk-grid-small" uk-grid>
                   <div class="uk-width-expand"><h5 style="padding:25px 5px 5px 5px; margin:0;"> Shop by Category
               </h5></div>
                   <div class="uk-width-auto"></div>
               </div>
               
               
        <div class="uk-grid-small uk-child-width-1-3 uk-text-center" uk-grid>
        <?php
            $i=1;
             foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id","","") as $product_catagory_1){
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
   		<?php } ?>
</div>
            </div>
      </section>
      <?php include("footer.php"); ?>