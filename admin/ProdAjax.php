<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
$dbf = new User();
 $profileuserid=$_POST['profile_id'];
 $num_row = $_POST['num_row'];
 $pagenum = $_POST['pagenum'];
 $num_val = $_POST['num_val'];
 $Category = $_POST['Category'];
 $subCategory = $_POST['subCategory'];
 if($num_val!=''){
  $pagenum=$num_val;
 }
 if($pagenum==0){
     $start=1;
     $pagenum=1;
     $end = 0;
 }else{
    $start=($num_row*($pagenum-1))+1;
    $end = $num_row*($pagenum-1);
 }

$list_cate_array=array();
foreach($dbf->fetchOrder("vendor_catagory","vendor_id='$profileuserid'","","","") as $cate_id){
array_push($list_cate_array,$cate_id['catagory_id']);
}
$list_cate=implode(',',$list_cate_array);
$list_prod_array=array();
foreach($dbf->fetchOrder("pro_rel_cat1","catagory1_id IN($list_cate)","","","") as $product_id){
array_push($list_prod_array,$product_id['product_id']);
}
$prod_id = implode(',',$list_prod_array);

//Category By Search
if($Category!=''){
$Array_of_Category=array();
foreach($dbf->fetchOrder("product_catagory_1","product_catagory_1_id ='$Category'","","product_catagory_1_id","") as $Category_id){
array_push($Array_of_Category,$Category_id['product_catagory_1_id']);
}
if(!empty($Array_of_Category)){
$list_category=implode(',',$Array_of_Category);
$Array_of_rel_cate = array();
foreach($dbf->fetchOrder("pro_rel_cat1","catagory1_id IN($list_category)","","product_id","") as $relcate_prod){
  array_push($Array_of_rel_cate,$relcate_prod['product_id']);
  }
if(!empty($Array_of_rel_cate)){
  $Cate_prod_rel = implode(',',$Array_of_rel_cate);
  $Cate_condi = " AND product_id IN($Cate_prod_rel)";
}else{
  $Cate_condi = " AND product_id='-1'";
}
}else{
  $Cate_condi = " AND product_id='-1'";
}
}else{
  $Cate_condi = "";
}
//Category By Search

//SubCategory By Search 
if($subCategory!=''){
  $Array_of_Category2=array();
  foreach($dbf->fetchOrder("product_catagory_2","product_catagory_2_id = '$subCategory'","","product_catagory_2_id","") as $Category_id2){
  array_push($Array_of_Category2,$Category_id2['product_catagory_2_id']);
  }
  if(!empty($Array_of_Category2)){
  $list_category2=implode(',',$Array_of_Category2);
  $Array_of_rel_cate2 = array();
  foreach($dbf->fetchOrder("pro_rel_cat2","catagory2_id IN($list_category2)","","product_id","") as $relcate_prod2){
    array_push($Array_of_rel_cate2,$relcate_prod2['product_id']);
    }
  if(!empty($Array_of_rel_cate2)){
    $Cate_prod_rel2 = implode(',',$Array_of_rel_cate2);
    $SubCate_condi = " AND product_id IN($Cate_prod_rel2)";
  }else{
    $SubCate_condi = " AND product_id='-1'";
  }
  }else{
    $SubCate_condi = " AND product_id='-1'";
  }
  }else{
    $SubCate_condi = "";
  }
  //SubCategory By Search

//SubCategory2 By Search
// if($Subcateid2!=''){

//   $Array_of_Category3=array();
//   foreach($dbf->fetchOrder("product_catagory_3","product_catagory_3_id = '$Subcateid2'","","product_catagory_3_id","") as $Category_id3){
//   array_push($Array_of_Category3,$Category_id3['product_catagory_3_id']);
//   }
//   if(!empty($Array_of_Category3)){
//   $list_category3=implode(',',$Array_of_Category3);
//   $Array_of_rel_cate3 = array();
//   foreach($dbf->fetchOrder("pro_rel_cat3","catagory3_id IN($list_category3)","","product_id","") as $relcate_prod3){
//     array_push($Array_of_rel_cate3,$relcate_prod3['product_id']);
//     }
//   if(!empty($Array_of_rel_cate3)){
//     $Cate_prod_rel3 = implode(',',$Array_of_rel_cate3);
//     $SubCate_condi2 = " AND product_id IN($Cate_prod_rel3)";
//   }else{
//     $SubCate_condi2 = " AND product_id='-1'";
//   }
//   }else{
//     $SubCate_condi2 = " AND product_id='-1'";
//   }
//   }else{
//     $SubCate_condi2 = "";
//   }
  //SubCategory2 By Search

$i=$start;

foreach($dbf->fetchOrder("product","product_id IN($prod_id) AND status='1'".$Cate_condi.$SubCate_condi,"product_id ASC LIMIT $num_row OFFSET  $end","primary_image,product_name,product_id,vendor_id","") as $product){

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

$Al_rel_cate=$dbf->fetchSingle("pro_rel_cat1",'catagory1_id',"product_id='$product[product_id]'");
$All_Category=$dbf->fetchSingle("product_catagory_1",'product_catagory_1_name',"product_catagory_1_id='$Al_rel_cate[catagory1_id]'");
echo $All_Category['product_catagory_1_name'].$Al_rel_cate['catagory1_id'];
// Cate Lisst Get
?>
</td>
<td>
<?php 
$Al_rel_cate=$dbf->fetchSingle("pro_rel_cat2",'catagory2_id',"product_id='$product[product_id]'");
$All_Category=$dbf->fetchSingle("product_catagory_2",'product_catagory_2_name',"product_catagory_2_id='$Al_rel_cate[catagory2_id]'");
echo $All_Category['product_catagory_2_name'].$Al_rel_cate['catagory2_id'];
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
<?php $i++; }echo"!next!"; ?>       
<?php 
 $CntRows=$dbf->countRows("product","product_id IN($prod_id) AND status='1'".$Cate_condi.$SubCate_condi.$SubCate_condi2,"");
$cntRow = $CntRows/$num_row;
$cntRow = ceil($cntRow);
?>
<a href="javascript:void(0)" onclick="Back()" class="<?= ($pagenum==1)?'disabled':''?>">&laquo;</a>
<?php 
 $counter = 1;
 if($pagenum<5){
 $pagenums = $pagenum+4;
 }else{
  $pagenums = $pagenum+2;
 }
 $pagenums1 = $pagenum-1;
for($i=1;$i<=$cntRow;$i++){
  if($counter == $pagenums){
  ?>
  <a href="javascript:void(0)"  class="disabled">...</a>
  <?php }else if($counter < $pagenums || $counter > ($cntRow - 1)){
     if($counter < $pagenums1){
      if($counter=='1'){?>
       <a href="javascript:void(0)" <?php if($i==$pagenum) echo'class="actives"';?> data-page="<?= $i?>" onclick="PageNUm(<?= $i?>)" ><?=$i?></a>
        <a href="javascript:void(0)"  class="disabled">...</a>
     <?php }

     }else{
    ?>
    <a href="javascript:void(0)" <?php if($i==$pagenum) echo'class="actives"';?> data-page="<?= $i?>" onclick="PageNUm(<?= $i?>)" ><?=$i?></a>
<?php }}
 $counter++;
}?>
  <a href="javascript:void(0)" onclick="Forward()" class="<?= ($pagenum==$cntRow)?'disabled':''?>">&raquo;</a>
<?php 
echo "!next!";
$las_num = $num_row+$start-1;
echo "Showing ".$start." to ".$las_num." of ".$CntRows." entries";
?>