<script>
     function All_in_onfun(arg1,arg2){
Loc_SubCate_Change(arg1,arg2)
}
      function Loc_SubCate_Change(arg,arg1){
    Loc_SubCates_Change(arg,arg1);
var category_id =document.getElementById('Subcategory_id');
if(category_id){
  category_id = category_id.value;
var url="getAjax.php";
  $.post(url,{"choice":"SubCateLocation","city_id":arg,"pin":arg1,"category_id":category_id},function(res){
 $('#Set_SubCate_Loc').html(res);
// alert(res);
});
}
  }
    
</script>

<input type="hidden" name="" id="Subcategory_id" value="<?= base64_decode($_REQUEST['cate'])?>"> 
<?php include("header.php"); 
$cate=$dbf->checkSqlInjection($_REQUEST['cate']);
$category_id=base64_decode($cate);
$SubCateName=$dbf->fetchSingle("product_catagory_2",'*',"product_catagory_2_id='$category_id'");
$CateName=$dbf->fetchSingle("product_catagory_1",'*',"product_catagory_1_id='$SubCateName[product_catagory_1_id]'");
?>

<div class="uk-background-muted">
<div class="uk-container " >
<div class="uk-margin-top uk-margin-bottom" >
<div class="uk-card uk-card-default uk-card-body uk-padding-small uk-margin-remove-top uk-margin-small-bottom">
<ul class="uk-breadcrumb">
    <li><a href="index.php"><span uk-icon="icon: home"></span> Home</a></li>
    <li><a href="product-catagory.php?cate=<?= base64_encode($CateName['product_catagory_1_id']) ?>"><?= $CateName['product_catagory_1_name']?></a></li>
    <li><a href=""><?= $SubCateName['product_catagory_2_name']?></a></li>
</ul>
</div>

<div class="uk-child-width-1-2 uk-child-width-1-4@m uk-grid-small  uk-grid" style="transform: translateX(0px);" id="Set_SubCate_Loc">
          
    </div>

</div>

</div>
</div>



<?php include("footer.php"); ?>
