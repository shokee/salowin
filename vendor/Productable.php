<?php 
include_once '../admin/includes/class.Main.php';
$dbf = new User();

if(isset($_REQUEST['opearions']) && $_REQUEST['opearions']=='GetProdTable'){
   $profileuserid = $_POST['profileuserid'];?>

   <table id="example1" class="table table-bordered table-striped">
   <thead>
   <tr>
    <th>Sl. No </th>
     <th>Image</th>
     <th>Name</th>
     <th>Gallery</th>
     <th>Variation</th>
     <th>Category</th>
     <th>Sub Category</th>
     <th>Sub Category2</th>
     <th>Status</th>
     
   </tr>
   </thead>
   <tbody>
  <?php  $list_cate_array=array();
foreach($dbf->fetchOrder("vendor_catagory","vendor_id='$profileuserid'","","","") as $cate_id){
array_push($list_cate_array,$cate_id['catagory_id']);
}
$list_cate=implode(',',$list_cate_array);
$list_prod_array=array();
foreach($dbf->fetchOrder("pro_rel_cat1","catagory1_id IN($list_cate)","","","") as $product_id){
array_push($list_prod_array,$product_id['product_id']);
}
$prod_id = implode(',',$list_prod_array);

$i=1;
foreach($dbf->fetchOrder("product","product_id IN($prod_id) AND status='1'","product_id ASC LIMIT 0,10","primary_image,product_name,product_id,vendor_id","") as $product){

$vendor_id_array=explode(',',$product['vendor_id']);
// $del_arry = array_delete('$profileuserid', $vendor_id_array)

?>
<tr class="<?= ($i%2==0)?"even":"odd"?>">
<td><?php echo $i;?></td>
<td> 
<?php if($product['primary_image']<>''){?>
<img src="../admin/images/product/thumb/<?php echo $product['primary_image'];?> " width="40px" height="40px;" >
<?php }else{?>
<img src="images/default.png?> " width="50px" height="50px;">
<?php }?>
</td>
<td><?php echo $product['product_name'];?></td>

<td><a class="btn btn-primary" href="product_gallery.php?id=<?php echo $product['product_id'];?>"><i class="fa fa-image"></i></a></td>
<td>

<button  class="btn btn-warning" onclick="GetVarion(<?= $product['product_id']?>)">Variation</button>
</td>
<td>
<?php 
// Cate Lisst Get
$Al_rel_cate=$dbf->fetchOrder("pro_rel_cat1","product_id='$product[product_id]'","","catagory1_id","");
$Arrya_rel_cate=array();
foreach($Al_rel_cate as $rel_cate){
array_push($Arrya_rel_cate,$rel_cate['catagory1_id']);
}
if(!empty($Arrya_rel_cate)){
$cate_id=implode(',',$Arrya_rel_cate);
$All_Category=$dbf->fetchOrder("product_catagory_1","product_catagory_1_id IN($cate_id)","","product_catagory_1_name","");
$Array_of_cate=array();
foreach($All_Category as $category){
array_push($Array_of_cate,$category['product_catagory_1_name']);
}
if(empty($Array_of_cate)){
$cate_list="";
}else{
$cate_list=implode('|',$Array_of_cate);
}

}else{
$cate_list="";
}

echo $cate_list;
// Cate Lisst Get
?>
</td>
<td>
<?php 
// Sub-Cate List Get
$Al_rel_Subcate=$dbf->fetchOrder("pro_rel_cat2","product_id='$product[product_id]'","","catagory2_id","");
$Arrya_rel_Subcate=array();
foreach($Al_rel_Subcate as $rel_subcate){
array_push($Arrya_rel_Subcate,$rel_subcate['catagory2_id']);
}
if(!empty($Arrya_rel_Subcate)){
$Subcate_id=implode(',',$Arrya_rel_Subcate);
$All_SubCategory=$dbf->fetchOrder("product_catagory_2","product_catagory_2_id IN($Subcate_id)","","product_catagory_2_name","");
$Array_of_subcate=array();
foreach($All_SubCategory as $Subcategory){
array_push($Array_of_subcate,$Subcategory['product_catagory_2_name']);
}
if(empty($Array_of_subcate)){
$Subcate_list="";
}else{
$Subcate_list=implode('|',$Array_of_subcate);
}

}else{
$Subcate_list="";
}
// Sub-Cate2 Lisst Get
echo $Subcate_list;
?>
</td>
<td>
<?php 

// Sub-Cate List Get
$Al_rel_Subcate2=$dbf->fetchOrder("pro_rel_cat3","product_id='$product[product_id]'","","catagory3_id","");
$Arrya_rel_Subcate2=array();
foreach($Al_rel_Subcate2 as $rel_subcate2){
array_push($Arrya_rel_Subcate2,$rel_subcate2['catagory3_id']);
}
if(!empty($Arrya_rel_Subcate2)){
$Subcate_id2=implode(',',$Arrya_rel_Subcate2);
$All_SubCategory2=$dbf->fetchOrder("product_catagory_3","product_catagory_3_id IN($Subcate_id2)","","product_catagory_3_name","");
$Array_of_subcate2=array();
foreach($All_SubCategory2 as $Subcategory2){
array_push($Array_of_subcate2,$Subcategory2['product_catagory_3_name']);
}
if(empty($Array_of_subcate2)){
$Subcate_list2="";
}else{
$Subcate_list2=implode('|',$Array_of_subcate2);
}

}else{
$Subcate_list2="";
}
// Sub-Cate2 Lisst Get
echo $Subcate_list2;
?>
</td>
<td>
<?php 
$CheckVarition=$dbf->fetchOrder("variations_values","vendor_id='$profileuserid' AND product_id='$product[product_id]'","variations_values_id DESC ","","");

if(in_array($profileuserid,$vendor_id_array)){?>
<button type="button" class="btn btn-success" onClick="upDateStatus(<?=$product['product_id']?>,0,'<?= $product['vendor_id']?>')">Active</button>
<?php }else{
if(!empty($CheckVarition)){
?>
<button type="button" class="btn btn-danger" onClick="upDateStatus(<?=$product['product_id']?>,1,'<?= $product['vendor_id']?>')">Block</button> 
<?php }else{?>

<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#CheckProd_vari<?= $product['product_id']?>">Block</button> 
<?php }}?>

<div id="CheckProd_vari<?= $product['product_id']?>" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">

<div class="modal-body">
<p class="text-danger">Varition Not Create Yet,Please Create Variation Then Activate.</p>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>

</div>
</div>
</td>

</tr>
<?php $i++; } ?>
   </tbody>
   <tfoot>
   <tr>
    <th>Sl. No </th>
     <th>Image</th>
     <th>Name</th>
     <th>Gallery</th>
     <th>Variation</th>
     <th>Category</th>
     <th>Sub Category</th>
     <th>Sub Category2</th>
     <th>Status</th>
   </tr>
   </tfoot>
 </table>
 
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
<?php }?>
