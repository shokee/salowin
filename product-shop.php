<script>
 function All_in_onfun(arg1,arg2){
Loc_Wise_Prod(arg1,arg2)
}
  

    function Loc_Wise_Prod(arg,arg1){
var Produt_id  = $('#prodids').val();
if(Produt_id){
var url="getAjax.php";
  $.post(url,{"choice":"Set_Prod_wise","city_id":arg,"pin":arg1,"product_id":Produt_id},function(res){
 $('#set_prod_loc').html(res);
// alert(res);
});
}
} 
    
</script>

<input type="hidden" name="" id="prodids" value="<?= $prodid=base64_decode($_REQUEST['prod'])?>"> 
<?php include("header.php"); 

 $ty=$dbf->checkSqlInjection($_REQUEST['ty']);

$product=$dbf->fetchSingle("product",'*',"product_id='$prodid'");

 //For Bredcum
switch ($ty) {
	case 'lat':
		$bre_cum=" Latest Product";
		break;
	case 'trend':
		$bre_cum="Trending Product";
		break;
	case 'deal':
		$bre_cum="Deals of the day";
		break;
	case 'rece':
		$bre_cum="Recent Views";
		break;
	default:
		$bre_cum="Search result of ".$product['product_name'];
		break;
}
 //For Bredcum
?>

<div class="uk-background-muted">
<div class="uk-container " >
<div class="uk-margin-top uk-margin-bottom" >
<div class="uk-card uk-card-default uk-card-body uk-padding-small uk-margin-remove-top uk-margin-small-bottom">
<ul class="uk-breadcrumb">
    <li><a href="index.php"><span uk-icon="home"></span></a></li>
    <li><span><?= $bre_cum?></span></li>
</ul>
</div>

<div class="uk-child-width-1-2 uk-child-width-1-4@m uk-grid-small  uk-grid" style="transform: translateX(0px);" id="set_prod_loc">
          
    </div>

</div>

</div>
</div>



<?php include("footer.php"); ?>
