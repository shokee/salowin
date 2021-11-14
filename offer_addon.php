
<!--Deals of the day -->
        <div class="uk-container uk-padding-small" id="displayofDay">
        <div class="uk-card uk-card-body uk-card-default uk-card-small uk-margin-small-top uk-margin-small-bottom"><h4>Deals of the day <img src="https://img1a.flixcart.com/www/linchpin/fk-cp-zion/img/timer_931251.svg"></h4>
        <hr>
        
        <div uk-slider>

    <div class="uk-position-relative uk-visible-toggle " tabindex="-1">
 
        <ul class="uk-slider-items uk-child-width-1-5@s uk-grid uk-grid-small ">
          <?php
        $i=1;
         $date_time=date('Y-m-d H:i');
        foreach($dbf->fetchOrder("product","status='1' AND today_dealing_date_time>='$date_time'","product_id ASC","","") as $product){
        // $unit = $dbf->fetchSingle("units",'*',"unit_id='$product[unit_id]'");
        ?>

            <a href="" class="uk-text-center">
                <div >
                    <div class="uk-card-media-top uk-text-enter">
                        <img src="admin/images/product/<?php echo $product['primary_image'];?>" alt="">
                    </div>
                    <div class="uk-card-body uk-text-enter">
                        <h5 class="uk-margin-remove"><?php echo $product['product_name'];?></h5>
                        <p><spann class="uk-color-sucess">starting Rs-<?php echo $product['sales_price'];?></spann><br>
                            <span id="demo<?= $product['product_id']?>"></span>
                       <!--  <small>sss</small> --></p>
                    </div>
                </div>
            </a>
            <script>
    var today = "<?= date('M d, Y H:i:s',strtotime($product['today_dealing_date_time']))?>";
   

// Set the date we're counting down to
var countDownDate<?= $product['product_id']?> = new Date(today).getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
 //alert(countDownDate)
  // Find the distance between now and the count down date
  var distance = countDownDate<?= $product['product_id']?> - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="demo"
  document.getElementById("demo<?= $product['product_id']?>").innerHTML = days +"d "+hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // If the count down is over, write some text 
  if (distance <= 0) {
    clearInterval(x);
    //document.getElementById("displayofDay<?= $product['product_id']?>").style.display = "none";
    //document.getElementById("demo<?= $product['product_id']?>").innerHTML = "EXPIRED";
  }
}, 1000);
</script>
          <?php }?>     
        </ul>
     

        <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
        <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>

    </div>

   

</div>
        
        </div>
        
        </div>

<!--Deals of he day -->

<div class="uk-container uk-margin-top uk-margin-bottom">
    <div class="uk-grid uk-grid-small uk-child-width-expand@s">
                     <?php
                    $i=1;
                     foreach($dbf->fetchOrder("addd","","add_id DESC LIMIT 3","","") as $resBanner){
                    ?>
                     <div class="uk-position-relative uk-margin-bottom" >
                     <a href="<?php echo $resBanner['add_link'];?>" target="_blank">
                      <img src="admin/images/add/<?php echo $resBanner['add_image'];?>" >
                     </a>
                    </div>
                    <?php $i++; } ?>
    </div>
</div>
<!--Recently  Viewed-->
        <div class="uk-container uk-padding-small">
        <div class="uk-card uk-card-body uk-card-default uk-card-small uk-margin-small-top uk-margin-small-bottom"><h4>Recently  Viewed</h4>
        <hr>
        
        <div uk-slider>

    <div class="uk-position-relative uk-visible-toggle " tabindex="-1">

        <ul class="uk-slider-items uk-child-width-1-5@s uk-grid uk-grid-small ">
           
        </ul>


        <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
        <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>

    </div>

   

</div>
        
        </div>
        
        </div>

<!--Recently  Viewed -->
  <div class="uk-container uk-margin-top uk-margin-bottom">
    <div class="uk-grid uk-grid-small uk-child-width-expand@s">
                     <?php
                    $i=1;
                     foreach($dbf->fetchOrder("addd","","add_id ASC  LIMIT 3,3","","") as $resBanner){
                    ?>
                     <div class="uk-position-relative uk-margin-bottom" >
                     <a href="<?php echo $resBanner['add_link'];?>" target="_blank">
                      <img src="admin/images/add/<?php echo $resBanner['add_image'];?>" >
                     </a>
                    </div>
                    <?php $i++; } ?>
    </div>
</div>


<!--Today Trending-->
        <div class="uk-container uk-padding-small">
        <div class="uk-card uk-card-body uk-card-default uk-card-small uk-margin-small-top uk-margin-small-bottom"><h4>Today Trending</h4>
        <hr>
        
        <div uk-slider>

    <div class="uk-position-relative uk-visible-toggle " tabindex="-1">

        <ul class="uk-slider-items uk-child-width-1-5@s uk-grid uk-grid-small ">
              <?php
        $i=1;
        foreach($dbf->fetchOrder("product","status='1' AND trendingg='1'","product_id ASC","","") as $product){
        //$unit = $dbf->fetchSingle("units",'*',"unit_id='$product[unit_id]'");
        ?>
            <a href="" class="uk-text-center">
                <div >
                    <div class="uk-card-media-top uk-text-enter">
                        <img src="admin/images/product/<?php echo $product['primary_image'];?>" alt="">
                    </div>
                    <div class="uk-card-body uk-text-enter">
                        <h5 class="uk-margin-remove"><?php echo $product['product_name'];?></h5>
                        <p><spann class="uk-color-sucess">starting Rs-<?php echo $product['sales_price'];?></spann><br>
                       <!--  <small><?= $trending->descrip?></small> --></p>
                    </div>
                </div>
            </a>
           
             <?php }?>
        </ul>
       

        <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
        <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>

    </div>

   

</div>
        
        </div>
        
        </div>

<!--Today Trending -->
