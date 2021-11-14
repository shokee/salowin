<?php include("header.php");

$cate=$dbf->checkSqlInjection($_REQUEST['cate']);
$category_id=base64_decode($cate);

$CateName=$dbf->fetchSingle("product_catagory_1",'*',"product_catagory_1_id='$category_id'");

$ArryProdId=array();
foreach ($dbf->fetchOrder("pro_rel_cat1","catagory1_id='$category_id'","","product_id","") as $prod_id) {
    array_push($ArryProdId, $prod_id['product_id']);
}
print_r($ArryProdId);exit;
$prod_id = implode(',',$ArryProdId);

$BrandArry=array();

foreach ($dbf->fetchOrder("product","product_id IN($prod_id)","","brands_id","") as $brand_id) {
    array_push($BrandArry, $brand_id['brands_id']);
}
$bran_id = implode(',',$BrandArry);

 ?>

<link rel="stylesheet" href="rangeslider/style.css">
<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" />
      <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
     $('#slider').slider({min:1,max:10000,range:true,values:[10,5000],change:function(event,ui){
       $('#hidden_minimum_price').val(ui.values[0]);
            $('#hidden_maximum_price').val(ui.values[1]);
             $('#slider-min').html('&#8377;'+ui.values[0]);
       $('#slider-max').html('&#8377;'+ui.values[1]);
          getDetails();
   }});
getDetails();


function getDetails(){
  //alert('aa');
  $('.filter_data').html('<div id="loading" style="" ></div>'); 
      var min = $('#hidden_minimum_price').val();
        var max = $('#hidden_maximum_price').val();
        var variotion = get_filter('variotion');
        var brand = get_filter('brand');
        var prod_id = '<?= $prod_id?>';
var url="getAjax.php";
 $.post(url,{"choice":"prodCateFillter","min":min,"max":max,"variotion":variotion,"brand":brand,"prod_id":prod_id},function(res){
if($.trim(res)==''){
    $('#cateoffillter').css('display','none');
    $('#Fillters').prepend('<img  src="images/untitled-1-_1_.png" style="width:100%"/>')
    }else{
 $('#Fillters').html(res);
}
});
  //alert(min);
    //alert(max);
  }
 
 function get_filter(class_name)
    {
        var filter = [];
        $('.'+class_name+':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }

    $('.common_selector').click(function(){

        getDetails();
    });

  

  });



  



</script>
<div class="body uk-padding-small uk-padding-remove-left uk-padding-remove-right">
<div class="uk-container">
<div class="uk-padding-small uk-background-default" >
<div class="uk-card uk-card-small uk-card-body uk-background-muted uk-margin-bottom">
	<ul class="uk-breadcrumb">
    <li><a href="index.php"><span uk-icon="icon: home"></span> Home</a></li>
    <li><a href=""><?= $CateName['product_catagory_1_name']?></a></li>
</ul>
</div>

<div class="uk-grid uk-grid-small">
	<div class="uk-width-1-5@m" >
    <div class="scard">
    
    <ul class="uk-list uk-list-divider">
     <?php
          $i=1;
           foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC ","","") as $product_catagory_1){
          ?>
          <li>
          <a class="uk-inline" href="product-catagory.php?cate=<?php echo base64_encode($product_catagory_1['product_catagory_1_id']);?>">
                                                    
                                                    <?= $product_catagory_1['product_catagory_1_name']?>  
                                                </a>
                                                </li>
           <?php }?>
           </ul>
    </div>
      
    </div>
    <div class="uk-width-expand@m">
     
    <ul class=" uk-child-width-1-4@s uk-grid">
    
                       
               <?php
               $i=1;
               foreach($dbf->fetchOrder("product","status='1'","product_id ASC","","") as $product){
               $cntattribute=$dbf->countRows("attribute","product_id='$product[product_id]'");
               ?>
               
				<?php include('productloop.php'); ?>
                <?php $i++; } ?>
            
                    </ul>
                    
    </div>
</div>
</div>
</div>
</div>
<?php include("footer.php"); ?>
