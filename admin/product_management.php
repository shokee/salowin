<?php include('header.php')?>
<?php include('sidebar.php')?>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<?php
########################## UPDATE STATUS  agent #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='update'){
   $id=$dbf->checkXssSqlInjection($_REQUEST['id']);
	$ststus=$dbf->checkXssSqlInjection($_REQUEST['ststus']);
	 $dbf->updateTable("product","status='$ststus'", "product_id='$id'");
	header("Location:product_management.php");
}
?>

 
<script type="text/javascript">
function upDateStatus(id,id1){
	//alert(id)
	if(id1=='1'){
		var msg ="Are you sure want to active this Record";
		}else{
			var msg ="Are you sure want to block this Record";
			}
	
	$("#status").val(id1);
	$("#id").val(id);
	var conf=confirm(msg);
	if(conf){
	   $("#frm_update").submit();
	}
}
</script>


<form name="frm_deleteBanner" id="frm_update" action="" method="post">
  <input type="hidden" name="operation" id="operation" value="update">
  <input type="hidden" name="id" id="id" value="">
  <input type="hidden" name="ststus" id="status" value="">
  </form>
  
  
  
  
  
  <?php
########################## DELETE  #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
	$banner_id=$dbf->checkXssSqlInjection($_REQUEST['banner_id']);

	$resBanner=$dbf->fetchSingle("product","*","product_id='$banner_id'");
	$gallery=$dbf->fetchSingle("gallery","*","product_id='$banner_id'");
	
	@unlink("images/product/".$resBanner['primary_image']);
	@unlink("images/product/thumb/".$resBanner['primary_image']);
	
	@unlink("images/gallery/".$gallery['image']);
  @unlink("images/gallery/thumb/".$gallery['image']);
  $Array_of_price_vari_id = array();
  foreach($dbf->fetchOrder("price_varition","product_id='$banner_id'","","price_varition_id","")as $varition_value){
    array_push($Array_of_price_vari_id,$varition_value['price_varition_id']);
  }
  if(!empty($Array_of_price_vari_id)){
  $price_vari_id = implode(',',$Array_of_price_vari_id);
  }else{
    $price_vari_id ="";
  }
  

	$dbf->deleteFromTable("product","product_id='$banner_id'");
	$dbf->deleteFromTable("gallery","product_id='$banner_id'");
  $dbf->deleteFromTable("price_varition","product_id='$banner_id'");
  if($price_vari_id!=''){
	$dbf->deleteFromTable("variations_values","price_variation_id IN($price_vari_id)");
  }
	
	header("Location:product_management.php");
}


//Upload Excel Product
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='UploadExcel'){
  $excel=$_FILES["excel_upload_prod"]["name"];
  $extension = pathinfo($excel, PATHINFO_EXTENSION);
   // For getting Extension of selected file
   $output="";
$allowed_extension = array("xls", "xlsx", "csv"); //allowed extension

if(in_array($extension, $allowed_extension)) //check selected file extension is present in allowed extension array
{
$file = $_FILES["excel_upload_prod"]["tmp_name"]; // getting temporary source of excel file

include("php/PHPExcel/IOFactory.php"); // Add PHPExcel Library in this code

$objPHPExcel = PHPExcel_IOFactory::load($file); // create object of PHPExcel library by using load() method and in load method define path of selected file
$objWorksheet = $objPHPExcel->getActiveSheet();

foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
{
$highestRow = $worksheet->getHighestRow();


for($i=2; $i<=$highestRow; $i++)
{



// $img=$objWorksheet->getDrawingCollection();

$product = trim($worksheet->getCellByColumnAndRow(6, $i)->getValue());

$Chk_prodcts=$dbf->countRows("product","product_name='$product'","");
if($Chk_prodcts==0){
    
// if(isset($img[$i-2]) && $img[$i-2]->getCoordinates()!=''){
  
//   list($startColumn, $startRow) = PHPExcel_Cell::coordinateFromString($img[$i-2]->getCoordinates()); //Get the row and column of the picture
// $imageFileName = $img[$i-2]->getCoordinates() . mt_rand(10000, 99999);

// switch($img[$i-2]->getExtension()) {
// case 'jpeg':
//     $imageFileName .= '.jpeg';
//     $source = imagecreatefromjpeg($img[$i-2]->getPath());
//     imagejpeg($source, "images/product/".$imageFileName);
//     imagejpeg($source, "images/product/thumb/".$imageFileName);
//     break;
// case 'gif':
//     $imageFileName .= '.gif';
//     $source = imagecreatefromgif($img[$i-2]->getPath());
//     imagejpeg($source, "images/product/".$imageFileName);
//     imagejpeg($source, "images/product/thumb/".$imageFileName);
//     break;
// case 'png':
//     $imageFileName .= '.png';
//     $source = imagecreatefrompng($img[$i-2]->getPath());
//     imagejpeg($source, "images/product/".$imageFileName);
//     imagejpeg($source, "images/product/thumb/".$imageFileName);
//     break;
//  default:
//  $imageFileName .= 'no-img.png';
//  }
// }else{
//   $imageFileName = 'no-img.png';
// }


$product_img = trim($worksheet->getCellByColumnAndRow(10, $i)->getValue());
$status =trim($worksheet->getCellByColumnAndRow(1, $i)->getValue());
$brand = trim($worksheet->getCellByColumnAndRow(5, $i)->getValue());
$group = trim($worksheet->getCellByColumnAndRow(11, $i)->getValue());
$type = trim($worksheet->getCellByColumnAndRow(12, $i)->getValue());

$decrip = trim($worksheet->getCellByColumnAndRow(7, $i)->getValue());
$decrip = str_replace("'", "\'", $decrip); 
$weight = trim($worksheet->getCellByColumnAndRow(8, $i)->getValue());
$units = trim($worksheet->getCellByColumnAndRow(9, $i)->getValue());
$category =trim($worksheet->getCellByColumnAndRow(2, $i)->getValue());
$category =  str_replace("&amp;","&","$category");
$category1 = explode('|',$category);
$List_Of_Cate_name="'".implode("','",$category1)."'";
$List_of_Cate_id=array();

foreach($dbf->fetchOrder("product_catagory_1","product_catagory_1_name IN($List_Of_Cate_name)","","product_catagory_1_id","") as $Cates){
array_push($List_of_Cate_id,$Cates['product_catagory_1_id']);
}


$sub_category =trim($dbf->checkXssSqlInjection($worksheet->getCellByColumnAndRow(3, $i)->getValue()));
$sub_category =  str_replace("&amp;","&","$sub_category");
$category2 = explode('|',$sub_category);
$List_Of_SubCate_name="'".implode("','",$category2)."'";
$List_of_SubCate_id=array();
foreach($dbf->fetchOrder("product_catagory_2","product_catagory_2_name IN($List_Of_SubCate_name)","","product_catagory_2_id","") as $Subcate){
array_push($List_of_SubCate_id,$Subcate['product_catagory_2_id']);
}


$sub_category2 =trim($worksheet->getCellByColumnAndRow(4, $i)->getValue());
$sub_category2 =  str_replace("&amp;","&","$sub_category2");
$category3 = explode('|',$sub_category2);
$List_Of_SubCate2_name="'".implode("','",$category3)."'";
$List_of_SubCate2_id=array();
foreach($dbf->fetchOrder("product_catagory_3","product_catagory_3_name IN($List_Of_SubCate2_name)","","product_catagory_3_id","") as $Subcate2){
array_push($List_of_SubCate2_id,$Subcate2['product_catagory_3_id']);
}



$brands_id=$dbf->fetchSingle("brands",'brands_id',"brands_name='$brand'");
$groups_id=$dbf->fetchSingle("groups",'groups_id',"groups_name='$group'");
$type_id=$dbf->fetchSingle("type",'type_id',"type_name='$type'");
 $string="product_name='$product', description='$decrip', brands_id='$brands_id[brands_id]',groups_id='$groups_id[groups_id]',type_id='$type_id[type_id]', primary_image='$product_img',status='$status',created_date=NOW()";
$inst_id=$dbf->insertSet("product",$string);

//Variation Add
$units = explode('|',$units);
$weight = explode('|',$weight);
for($k=0;$k<count($units);$k++){
$unit=$dbf->fetchSingle("units",'unit_id',"unit_name='$units[$k]'");
$cntPrice=$dbf->countRows("price_varition","units='$weight[$k]' AND product_id='$inst_id' AND measure_id='$unit[unit_id]'");
if($cntPrice==0){
  $string1="units='$weight[$k]',product_id='$inst_id',measure_id='$unit[unit_id]'";
  $dbf->insertSet("price_varition",$string1);
}
}
//Variation Add




foreach($List_of_Cate_id as $cate1){
  $dbf->insertSet("pro_rel_cat1","product_id='$inst_id',catagory1_id='$cate1'");
  }
  foreach($List_of_SubCate_id as $cate2){
  $dbf->insertSet("pro_rel_cat2","product_id='$inst_id',catagory2_id='$cate2'");
  }
  foreach($List_of_SubCate2_id as $cate3){
  $dbf->insertSet("pro_rel_cat3","product_id='$inst_id',catagory3_id='$cate3'");
  }
}
}
} 
header("Location:product_management.php");exit;
}
else
{
  $error="Invalid File Format ,Please Check It!!!";

}

}
//Upload Excel Product

$category=$subcategory=$subcategory2='';
if(isset($_REQUEST['operations']) && $_REQUEST['operations']=='Fillter'){
$category=$_POST['category'];
$subcategory=$_POST['Subcategory'];
// $subcategory2=$_POST['Subcategory2'];
$condi="";
if($category!=''){
$Array_cate=array();
foreach($dbf->fetchOrder("pro_rel_cat1","catagory1_id='$category'","","product_id","") as $categories){
array_push($Array_cate,$categories['product_id']);
}
// if(!empty($Array_cate)){
  $Prod_cate = implode(',',$Array_cate);
  $condi.="  AND product_id IN($Prod_cate)";

// }else{
//   $Prod_cate="";
// }
}
if($subcategory!=''){
$Array_Subcate=array();
foreach($dbf->fetchOrder("pro_rel_cat2","catagory2_id='$subcategory'","","product_id","") as $Subcategoie){
array_push($Array_Subcate,$Subcategoie['product_id']);
}
// if(!empty($Array_Subcate)){
  $Prod_Subcate = implode(',',$Array_Subcate);
  $condi.="  AND product_id IN($Prod_Subcate)";
// }else{
//   $Prod_Subcate='';
// }
}
// if($subcategory2!=''){
//   $Array_Subcate=array();
//   foreach($dbf->fetchOrder("pro_rel_cat3","catagory3_id='$subcategory2'","","product_id","") as $Subcategoie2){
//   array_push($Array_Subcate,$Subcategoie2['product_id']);
//   }
//   if(!empty($Array_Subcate)){
//     $Prod_Subcate2 = implode(',',$Array_Subcate);
//     $condi.="  AND product_id IN($Prod_Subcate2)";
//   }else{
//     $Prod_Subcate2='';
//   }
//   }

}else{
$condi="";
}
?>

<script type="text/javascript">
function deleteRecord(banner_id){
		$("#operation1").val('delete');
	$("#banner_id").val(banner_id);
	var conf=confirm("Are you sure want to delete this Record");
	if(conf){
	   $("#frm_deleteBanner").submit();
	}
}
</script>

 <form name="frm_deleteBanner" id="frm_deleteBanner" action="" method="post">
 <input type="hidden" name="operation" id="operation1" value="">
 <input type="hidden" name="banner_id" id="banner_id" value="">
 </form>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Product
        <small>Manage All Product Here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Product</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      <div class="box">
             <div class="box-header">
             <?php if(isset($error)){?>
             <h3 class="text-danger"><?= $error?></h3>
             <?php }?>
             <form action="CategoryExcel.php" method="post">
             <input type="hidden" value="<?= $condi?>" name="condi">
              <h3 class="box-title">
              <?php if(in_array('6.1',$Job_assign)){?>
              <a href="product.php" class="btn btn-primary">Add Product</a>
              <?php }?>
              <?php if(in_array('6.2',$Job_assign)){?>
              <button class="btn btn-primary" data-toggle="modal" data-target="#Upload_Excel_prod" type="button">
              <i class="fa fa-upload" aria-hidden="true">
              <i class="fa fa-file-excel-o" aria-hidden="true">
              </i>
              </i>
              </button>
              <?php }?>
              <a class="btn btn-primary" href="dist/upload_excel/product_upload.xlsx">
              <i class="fa fa-file-excel-o" aria-hidden="true">
              </i>
              </a>
              <?php if(in_array('6.3',$Job_assign)){?>
              <button class="btn btn-primary" name="operation" value="ProductsExports">  
              <i class="fa fa-download" aria-hidden="true"></i>
              <i class="fa fa-file-excel-o" aria-hidden="true"></i>
              </button>
              <?php }?>
              </h3>
              </form>
              
  <!-- Modal -->
<div class="modal fade" id="Upload_Excel_prod" tabindex="-1" role="dialog" aria-labelledby="Upload_Excel_prod" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    <form action=""  method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Upload Excel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
      <label for="excel_upload_prod">Upload Product</label>
      <input type="file" name="excel_upload_prod" class="form-control" required id="excel_upload_prod"
      accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button class="btn btn-primary" name="choice" value="UploadExcel">Upload</button>
      </div>
    </form>
    </div>
  </div>
</div>
            </div> 
            <!-- /.box-header -->
            <div class="box-body">
            <form action="" method="post">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                <select name="category" id="category" class="form-control select2" onchange="GetSubCategory()" style="width:100%;">
                <option value="">~~Select Category~~</option>
    			<?php  foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","product_catagory_1_name,product_catagory_1_id","") as $DirName){?>
    			<option value="<?=$DirName['product_catagory_1_id']?>" <?= ($category==$DirName['product_catagory_1_id'])?"selected":""?>><?=$DirName['product_catagory_1_name']?></option>
   			    <?php }?>
                </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
              
                <select name="Subcategory" class="form-control select2" id="Subcateid"  style="width:100%;">
                <option value="">~~Select SubCateGory~~</option>
                </select>
                </div>
              </div>
              <!-- <div class="col-md-3">
                <div class="form-group">
                <select name="Subcategory2" id="Subcateid2" class="form-control select2">
                <option value="">~~Select SubCateGory2~~</option>
                </select>
                </div>
              </div> -->
              <div class="col-md-3">
                <div class="form-group">
                <button class="btn btn-primary" name="operations" value="Fillter">Fillter</button>
                <a href="" class="btn btn-default">Refresh</a>
                </div>
              </div>
            </div>
            </form>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sl. No </th>
                  <th>Image</th>
                  <th>Name</th>
                  <?php if(in_array('6.4',$Job_assign)){?>
                  <th>Gallery</th>
                  <?php }?>
                  <?php if(in_array('6.5',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('6.6',$Job_assign)){?>
                  <th>Variation</th>
                  <?php }?>
                  <?php if(in_array('6.7',$Job_assign)){?>
                  <th>Action</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
                 <?php
					$i=1;
					 foreach($dbf->fetchOrder("product","product_id<>0 ".$condi,"product_id DESC","","") as $product){
					 $unit = $dbf->fetchSingle("units",'*',"unit_id='$product[unit_id]'");
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td> 
				  <?php if($product['primary_image']<>''){?>
                  <img src="images/product/thumb/<?php echo $product['primary_image'];?> " width="50px" height="50px;" >
                  <?php }else{?>
                  <img src="images/default.png?> " width="50px" height="50px;">
                  <?php }?>
        		</td>
                  <td style="width:120px;"><?php echo $product['product_name'];?></td>
                  <?php if(in_array('6.4',$Job_assign)){?>
                  <td>
                  <a class="btn btn-primary" href="product_gallery.php?id=<?php echo $product['product_id'];?>"><i class="fa fa-image"></i></a></td>
                  <?php }?>
                  <?php if(in_array('6.5',$Job_assign)){?>
                 <td>
                  <?php if($product['status']=='1'){?><button type="button" class="btn btn-success" onClick="upDateStatus(<?=$product['product_id']?>,0)">Active</button><?php }else{?><button type="button" class="btn btn-danger" onClick="upDateStatus(<?=$product['product_id']?>,1)">Block</button> <?php }?>
                  </td>
                  <?php }?>
                  <?php if(in_array('6.6',$Job_assign)){?>
                  <td>
                  <!-- <a class="btn btn-warning"  href="product_variations.php?id=<?php echo $product['product_id'];?>"> Variation</a> -->
                  <button  class="btn btn-warning" onclick="GetVarion(<?= $product['product_id']?>)">Variation</button>
                  </td>
                  <?php }?>
            <?php if(in_array('6.7',$Job_assign)){?>
				<td> 
 <a class="btn btn-social-icon btn-primary" href="productedit.php?id=<?php echo $product['product_id'];?>" ><i class="fa fa-edit"></i></a>
 <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $product['product_id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a>
                  </td>
            <?php }?>
                </tr>
                
                <?php $i++; } ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Sl. No </th>
                  <th>Image</th>
                  <th>Name</th>
                  <?php if(in_array('6.4',$Job_assign)){?>
                  <th>Gallery</th>
                  <?php }?>
                  <?php if(in_array('6.5',$Job_assign)){?>
                  <th>Variation</th>
                  <?php }?>
                  <?php if(in_array('6.6',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('6.7',$Job_assign)){?>
                  <th>Action</th>
                  <?php }?>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
    
   <!-- gallery --> 
<!-- Modal -->
<div class="modal fade" id="VariationModal" tabindex="-1" role="dialog" aria-labelledby="VariationModallabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="VariationModallabel"></h5>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
                  <label for="variunit">Enter Weight</label>
                  <input type="text" id="variunit" class="form-control" 
                  placeholder="Enter Weight" required autocomplete="off">
          </div>
        </div>
        <input type="hidden"  id="varproduct_id">
        <div class="col-md-6">
        <div class="form-group">
                <label for="measure">Select Measurement</label>
                <select  id="varimeasure" class="form-control select2" required style="width:100%;">
                    <option value="">~~Select Measurement~~</option>
                    <?php foreach( $dbf->fetchOrder("units","","unit_name","","")as $Measures){?>
                    <option value="<?= $Measures['unit_id']?>"><?= $Measures['unit_name']?></option>
                    <?php }?>
                </select>
          </div>
        </div>
      </div>
                    
<div class="row">
<div class="col-md-12">
<table class="table">
      <thead>
      <tr>
      <th>Weight</th>
      <th>Measure Ment</th>
      <th>Delete</th>
      </tr>
      </thead>

  <tbody id="NewPrice">
  </tbody>
   </table>
   </div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button class="btn btn-primary" name="operation" value="AddVariotion" type="button" onclick="AddPrice()">Submit</button>
      </div>
    </div>
  </div>
</div>
  <!-- gallery --> 
 
   
  </div>
  <!-- /.content-wrapper -->
  <style>
  	.gg{ border:solid 1px #f9f9f9; position:relative; border-radius:5px;}
	.btt{ position:absolute; left:0; top:0;}
  </style>
 
<!-- /.variation price -->
   <?php include('footer.php')?>
   <script>
  function  GetSubCategory() {
       // alert(val);
       var val =$('#category').val();
    
//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getSubcate","value":val,"subcategory":"<?= $subcategory?>"},function(res){
 $('#Subcateid').html(res);
// alert(res)
});
  
  } 
  GetSubCategory();



  function GetVarion(varid){
    $('#VariationModal').modal('show'); 

    var url="getAjax.php";
    $.post(url,{"choice":"variations","product_id":varid},function(res){
     
      res = res.split('!next!');
      //  console.log(res)
      $('#VariationModallabel').html(res[0]);
      $('#varproduct_id').val(res[1]);
      $('#NewPrice').html(res[2]);
});
  }


  function AddPrice(){
      // alert(arg);
      var product = $('#varproduct_id').val();
      var unit = $('#variunit').val();
      var measure = $('#varimeasure').val();      
	   var url="getAjax.php";
 $.post(url,{"operation":"AddVariotion","unit":unit,"measure":measure,"product_id":product},function(res){
 
 if(res!='error'){
      var unit = $('#variunit').val("");
      var measure = $('#varimeasure').val("");   
  $('#NewPrice').html(res);
//  alert(res);
 }else{
  alert('Duplicate Date Error!!!');
 }
});
    }

    
    function PriceVar(arg,prod_id){
	 var conf=confirm("Are you sure want to delete this Record?");
    if(conf){
       document.getElementById('PriceVari'+arg).style.display = "none"; 
	   
	   var url="getAjax.php";
 $.post(url,{"choice":"PriceVariDel","price_vari":arg,"product_id":prod_id},function(res){
//    alert('ss');
//  alert(res);
});
    }
	
	  }
</script>