 <?php include("header.php"); ?>
 <section class="uk-section uk-background-default  uk-padding-remove-left uk-padding-remove-right   " style="padding:5px; padding-top:20px; padding-bottom:20px; background:#F0F6FB;">
            <div class="uk-container">
               <h5 style="padding:25px 5px 5px 5px; margin:0;">Shop By Brands</h5>
        
        
                 <ul class=" uk-grid-small  uk-text-center uk-child-width-1-3  uk-grid-collapse" uk-grid>
                <?php
            $i=1;
             foreach($dbf->fetchOrder("brands","","brands_id","","") as $brand){
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
   <?php include("footer.php"); ?>