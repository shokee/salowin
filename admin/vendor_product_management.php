<?php include('header.php')?>
<?php include('sidebar.php')?>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
 <script src="https://rawgit.com/padolsey/jQuery-Plugins/master/sortElements/jquery.sortElements.js"></script>
  <style>
.pagination {
  display: inline-block;
  float:right;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  border: 1px solid #ddd;
}

.pagination a.actives {
  background-color: #3c8dbc;
  color: white;
  border: 1px solid #3c8dbc;
}

.pagination a:hover:not(.actives) {background-color: #ddd;}

.pagination a:first-child {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
}

.pagination a:last-child {
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
}
a.disabled {
  cursor: no-drop;
  pointer-events: none;
  
}
th {
  cursor: pointer;
}

</style>
<?php

if(isset($_REQUEST['vndr_id'])){
  $profileuserid=$dbf->checkSqlInjection($_REQUEST['vndr_id']);  
}else{
  	header("Location:vendor.php");  
}
// Upload Excel

// if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='UploadExcel'){
//   $excel=$_FILES["excel_upload_prod"]["name"];
//   $extension = pathinfo($excel, PATHINFO_EXTENSION);
//   // For getting Extension of selected file
//   $output="";
// $allowed_extension = array("xls", "xlsx", "csv"); //allowed extension

// if(in_array($extension, $allowed_extension)) //check selected file extension is present in allowed extension array
// {
// $file = $_FILES["excel_upload_prod"]["tmp_name"]; // getting temporary source of excel file

// include("php/PHPExcel/IOFactory.php"); // Add PHPExcel Library in this code

// $objPHPExcel = PHPExcel_IOFactory::load($file); // create object of PHPExcel library by using load() method and in load method define path of selected file
// $objWorksheet = $objPHPExcel->getActiveSheet();

// foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
// {
// $highestRow = $worksheet->getHighestRow();
// for($i=2; $i<=$highestRow; $i++)
// {
//     $product_name= trim($worksheet->getCellByColumnAndRow(1, $i)->getValue());
//   $productDetails1=$dbf->fetchSingle("product",'product_id',"product_name='$product_name'");  
    
// $productid =$productDetails1['product_id'];
//  $variationDetails=$dbf->fetchSingle("price_varition",'price_varition_id',"product_id='$productid'"); 
// $variationid =$variationDetails['price_varition_id'];
// $Chk_prodcts=$dbf->countRows("variations_values","vendor_id='$profileuserid' AND product_id='$productid' AND price_variation_id='$variationid'","");
// if($Chk_prodcts==0){

// $mrp_price = trim($worksheet->getCellByColumnAndRow(2, $i)->getValue());
// $sale_price = trim($worksheet->getCellByColumnAndRow(3, $i)->getValue());
// $quantity = trim($worksheet->getCellByColumnAndRow(4, $i)->getValue());

// $string="vendor_id='$profileuserid',product_id='$productid',price_variation_id='$variationid',mrp_price='$mrp_price',sale_price='$sale_price',qty='$quantity'";
// $dbf->insertSet("variations_values",$string);

// $productDetails=$dbf->fetchSingle("product",'*',"product_id='$productid'");
// if($productDetails['vendor_id'] == ''){
// $vendoridss=$profileuserid;
// }else{
    
//     $vendoridss=$productDetails['vendor_id'];
//     $vendoridss.=','.$profileuserid;
// }
// $dbf->updateTable("product","vendor_id='$vendoridss'","product_id='$productid'");

// }




// }
// } 
// header("Location:vendor_product_management.php?vndr_id=".$profileuserid);exit;
// }
// else
// {
//   $error="Invalid File Format ,Please Check It!!!";

// }

// }
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
$productid = trim($worksheet->getCellByColumnAndRow(1, $i)->getValue());
$variationid =trim($worksheet->getCellByColumnAndRow(5, $i)->getValue());
$Chk_prodcts=$dbf->countRows("variations_values","vendor_id='$profileuserid' AND product_id='$productid' AND price_variation_id='$variationid'","");
if($Chk_prodcts==0){

$mrp_price = trim($worksheet->getCellByColumnAndRow(6, $i)->getValue());
$sale_price = trim($worksheet->getCellByColumnAndRow(7, $i)->getValue());
$quantity = trim($worksheet->getCellByColumnAndRow(8, $i)->getValue());
if($mrp_price >= $sale_price){
$string="vendor_id='$profileuserid',product_id='$productid',price_variation_id='$variationid',mrp_price='$mrp_price',sale_price='$sale_price',qty='$quantity'";
$dbf->insertSet("variations_values",$string);

$productDetails=$dbf->fetchSingle("product",'*',"product_id='$productid'");
if($productDetails['vendor_id'] == ''){
$vendoridss=$profileuserid;
}else{
    
    $vendoridss=$productDetails['vendor_id'];
    $vendoridss.=','.$profileuserid;
}
$dbf->updateTable("product","vendor_id='$vendoridss'","product_id='$productid'");
}
}else{
    
$mrp_price = trim($worksheet->getCellByColumnAndRow(6, $i)->getValue());
$sale_price = trim($worksheet->getCellByColumnAndRow(7, $i)->getValue());
$quantity = trim($worksheet->getCellByColumnAndRow(8, $i)->getValue());
if($mrp_price >= $sale_price){
$string="vendor_id='$profileuserid',product_id='$productid',price_variation_id='$variationid',mrp_price='$mrp_price',sale_price='$sale_price',qty='$quantity'";
$dbf->updateTable("variations_values",$string,"vendor_id='$profileuserid' AND product_id='$productid' AND price_variation_id='$variationid'");

$productDetails=$dbf->fetchSingle("product",'*',"product_id='$productid'");
if($productDetails['vendor_id'] == ''){
$vendoridss=$profileuserid;
$dbf->updateTable("product","vendor_id='$vendoridss'","product_id='$productid'");
}
}

}




}
} 
header("Location:vendor_product_management.php?vndr_id=".$profileuserid);exit;
}
else
{
  $error="Invalid File Format ,Please Check It!!!";

}

}
//Upload Excel Product





########################## UPDATE STATUS  agent #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='update'){

   $id=$dbf->checkXssSqlInjection($_REQUEST['id']);
	 $ststus=$dbf->checkXssSqlInjection($_REQUEST['ststus']);
   $array_name=$_POST['array_name'];
   if($array_name!=""){
$vendor_id_array=explode(',',$array_name);
}else{
  $vendor_id_array=array();
}
  if($ststus=='0'){

      function remove_element($array,$value) {
  return array_diff($array, (is_array($value) ? $value : array($value)));
}
 
$del_arry = remove_element($vendor_id_array,$profileuserid);
 $vendor_id=implode(',',$del_arry);
	 $dbf->updateTable("product","vendor_id='$vendor_id'", "product_id='$id'");
  }else{

    $add_array=array();
    array_push($vendor_id_array,$profileuserid);
    $vendor_id=implode(',',$vendor_id_array);
   $dbf->updateTable("product","vendor_id='$vendor_id'", "product_id='$id'");
  }
	header("Location:vendor_product_management.php?vndr_id=".$profileuserid);
}

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addcountry'){
	
  if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
    
      $fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
      $source_path1="../admin/images/brands/".$fname1;
      
      $destination_path1="../admin/images/brands/thumb/".$fname1;	
      $imgsize1 = getimagesize($source_path1);		
      $new_height1 = 400;
      $new_width1 = 400;		
      $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
      move_uploaded_file($_FILES['img']['tmp_name'],"../admin/images/brands/".$fname1);
      
      if($_FILES['img']['type'] == "image/JPG" || $_FILES['img']['type'] == "image/JPEG" || $_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type']=='image/jpeg' ){
        //for small                
        $srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
        ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
        ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
      }else if($_FILES['img']['type'] == "image/gif" || $_FILES['img']['type'] == "image/GIF"){  
        //for small          
        $srcimg1 = imagecreatefromgif($source_path1);
        ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
        ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
      }else if($_FILES['img']['type'] == "image/png" || $_FILES['img']['type'] == "image/PNG"){ 
         //for small          
        $srcimg1 = imagecreatefrompng($source_path1);
        ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
        ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
      }
    }
    //@unlink("../banner-img/".$fname1);
  $country=trim($dbf->checkXssSqlInjection($_REQUEST['country']));
    
  
  
  $cntcountry=$dbf->countRows("brands","brands_name='$country'");
  
  if($cntcountry==0){
  $string="brands_name='$country',images='$fname1',status='0',created_date=NOW()";
  $dbf->insertSet("brands",$string);
 	header("Location:vendor_product_management.php?vndr_id=".$profileuserid."&msg=success");
  }else{	
  header("Location:vendor_product_management.php?vndr_id=".$profileuserid."&msg=exit");
  }
  }

?>

 
<script type="text/javascript">
function upDateStatus(id,id1,id2){
	// alert(id2)

	if(id1=='1'){
		var msg ="Are you sure want to active this Product";
		}else{
			var msg ="Are you sure want to block this Product";
			}
	
	$("#status").val(id1);
	$("#id").val(id);
  $("#array_name").val(id2);
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
   <input type="hidden" name="array_name" id="array_name" value="">
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
      <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
              <div class="uk-alert-success" uk-alert>
              <a class="uk-alert-close" uk-close></a>
              <p>Your Brand Add Successfully , Please Wait For Admin Approval.</p>
              </div>
              <?php }?>
              <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
              <div class="uk-alert-danger" uk-alert>
              <a class="uk-alert-close" uk-close></a>
              <p>Your Brand Name Already Exist.</p>
              </div>
              <?php }?>
      <div class="box-header">
      <!--<h3>  -->
      <!--<button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add Your Brand</button>-->
      <!--</h3>-->
                <button class="btn btn-primary" data-toggle="modal" data-target="#Upload_Excel_prod" type="button">
              <i class="fa fa-upload" aria-hidden="true">
              <i class="fa fa-file-excel-o" aria-hidden="true">
              </i>
              </i>
              </button>   
              <br />
              <form action="CategoryExcel.php" method="post">
                  <input type="hidden" value="<?=$profileuserid?>" name="vendor_id">
                    <button class="btn btn-primary" name="operation" value="ProductsExportsAll">  
              <i class="fa fa-download" aria-hidden="true"></i>
              <i class="fa fa-file-excel-o" aria-hidden="true"></i>
              </button>
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

                <?php 
                $Array_cate=array();
                foreach($dbf->fetchOrder("vendor_catagory","vendor_id='$profileuserid'","","catagory_id","") as $categories){
                array_push($Array_cate,$categories['catagory_id']);
                }
                if(!empty($Array_cate)){
                  $category_list = implode(',',$Array_cate);
                  $Cate_condi  = "product_catagory_1_id IN($category_list)";
                }else{
                  $Cate_condi = "product_catagory_1_id='-1'";
                }
            
                ?>
                <select name="category" id="FillCategory" class="form-control select2" onchange="GetSubCategory(this.value)" style="width:100%;">
                <option value="">~~Select Category~~</option>
    			<?php  foreach($dbf->fetchOrder("product_catagory_1",$Cate_condi,"product_catagory_1_id ASC","product_catagory_1_name,product_catagory_1_id","") as $DirName){?>
    			<option value="<?=$DirName['product_catagory_1_id']?>" <?= ($category==$DirName['product_catagory_1_id'])?"selected":""?>><?=$DirName['product_catagory_1_name']?></option>
   			    <?php }?>
                </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
              
                <select name="Subcategory" class="form-control select2" id="subCategory"  style="width:100%;" >
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
                <button class="btn btn-primary" name="operations" value="Fillter" type="button" onclick="GetAllTable()">Fillter</button>
                <a href="" class="btn btn-default">Refresh</a>
                </div>
              </div>
            </div>
            </form>
            Show  
            <select name="" id="SelectEntity" onchange="GetAllTable()">
            <option>10</option>
            <option>25</option>
            <option>50</option>
            <option>100</option>
            </select>entries
            <!-- <div class="row">
            <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Enter Your Category" id="FillCategory" onkeyup="GetAllTable()">
            </div>
            <div class="col-md-4">
            <input type="text" class="form-control"  placeholder="Enter Your Sub Category" id="subCategory" onkeyup="GetAllTable()">
            </div>
            </div> -->
              <table  class="table table-bordered table-striped" id="tblCustomers" >
                   <!--<table id="example1" class="table table-bordered table-striped">-->
                <thead>
                <tr>
     <th id="facility_header">Sl. No </th>
     <th>Image</th>
     <th>Name</th>
     <th>Gallery</th>
     <th>Variation</th>
     <th>Category</th>
     <th>Sub Category</th>
     <th>Status</th>
   </tr>
 </thead>
 <tbody id="DataTables">
 
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
     <th>Status</th>
     
   </tr>
                </tfoot>
              </table>
             <span id="TextEntity"></span>
<div class="pagination"></div>
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->

    

   
</div>
  <!-- /.content-wrapper -->
  <style>
  	.gg{ border:solid 1px #f9f9f9; position:relative; border-radius:5px;}
	.btt{ position:absolute; left:0; top:0;}
  </style>



<script>


function GetVarion(varid){
    $('#VariationModal').modal('show'); 

    var url="vendor_getAjax.php";
    $.post(url,{"choice":"variations","product_id":varid,"vendor_id":<?= $profileuserid?>},function(res){
     
      res = res.split('!next!');
      //  console.log(res)
      $('#VariationModallabel').html(res[0]);
      $('#VariProdid').val(res[1]);
      $('#NewPrice').html(res[2]);
      $('#measure').html(res[3]);
});
  }
  function AddPrice(Measure_id){
      
      var product =$('#VariProdid').val();
      var price_vari =$('#MesasuresId'+Measure_id).data('measureid');
      var sale_price = $('#SalesPriceId'+Measure_id).val();
      var mrp_price = $('#MrpPriceId'+Measure_id).val();
      var stockqty = $('#stockqty'+Measure_id).val();
   if(!sale_price){
    alert('Sale Price	Not Be Blank*');
   }else if(!mrp_price){
    alert('Mrp Price	Not Be Blank*');
   }else if(!stockqty){
    alert('stock quantity	Not Be Blank*');
   }else{
    //  if(mrp_price>sale_price){
    
         if(parseFloat(mrp_price)>parseFloat(sale_price)){
	   var url="vendor_getAjax.php";
 $.post(url,{"operation":"AddVariotion","measure":price_vari,"mrp_price":mrp_price,"stockqty":stockqty,"sale_price":sale_price,"vendor_id":<?= $profileuserid?>,"product":product},function(res){
  // alert(res);
  $('#NewPrice').html(res);
});
   }else{
    alert('Sales Price Not Greater Than MRP Price');
   }
 }
 }

 function UpdatePriceVari(Measure_id,Variation_id){
 
      var product =$('#VariProdid').val();
      var price_vari =$('#MesasuresId'+Measure_id).data('measureid');
      var sale_price = $('#SalesPriceId'+Measure_id).val();
      var mrp_price = $('#MrpPriceId'+Measure_id).val();
        var stockqty = $('#stockqty'+Measure_id).val();
//   if(!sale_price){
//     alert('Sale Price	Not Be Blank*');
//   }else if(!mrp_price){
//     alert('Mrp Price	Not Be Blank*');
//   }else{
    if(parseFloat(mrp_price)>parseFloat(sale_price)){
	   var url="vendor_getAjax.php";
 $.post(url,{"operation":"UpdateVariotion","measure":price_vari,"stockqty":stockqty,"mrp_price":mrp_price,"sale_price":sale_price,"vendor_id":<?= $profileuserid?>,"product":product,"Variation_id":Variation_id},function(res){
  // alert(res);
  $('#NewPrice').html(res);
});
    }else{
        
    alert('Sales Price Not Greater Than MRP Price');
    
   }
//  }
 }

    
//     function PriceVar(arg){
//     // alert(arg)
// 	 var conf=confirm("Are you sure want to delete this Record?");
//     if(conf){
//        document.getElementById('PriceVari'+arg).style.display = "none"; 
	   
// 	   var url="vendor_getAjax.php";
//  $.post(url,{"choice":"PriceVariDel","price_vari":arg},function(res){
// //    alert('ss');
// //  alert(res);
// });
//     }
	
// 	  }
</script>

<!-- Variation Modal -->
<div class="modal fade" id="VariationModal" tabindex="-1" role="dialog" aria-labelledby="VariationModallabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" >
      <div class="modal-header">
        <h5 class="modal-title" id="VariationModallabel"></h5>
      </div>
      <div class="modal-body">
      
            <input type="hidden" id="VariProdid">        

<div class="table-responsive">
<table class="table " id="myTable">
      <thead>
      <tr>
      <th>Measure Ment</th>
      <th>MRP Price</th>
      <th>Sale Price</th>
       <th>Stock Quantity</th>
      <th>Action</th>
      </tr>
      </thead>

  <tbody id="NewPrice">
  </tbody>
   </table>

</div>
      </div>
      <div class="modal-footer">
        <a  class="btn btn-default" href="">Close</a>
      </div>
    </div>
  </div>
</div>
<!-- Variation Modal -->

<!-- Your Brands -->
<div class="modal fade" id="modal-default-add">
              
              <form  action="" enctype="multipart/form-data" method="post">  
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Add  Your Brand</h4>
                  
                </div>
                <div class="modal-body">
                  <div class="form-group">
                  <label>Enter Brands Name</label>
                  <input type="text" class="form-control" name="country" id="country" placeholder="Enter Brands Name" required />
                  
                  
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Image</label>
                    <input type="file" class="form-control" id="img" name="img" >
                  </div>
                  
                  
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary pull-left " value="addcountry" name="submit">Add </button>
                  <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                  
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            
            </form>
            <!-- /.modal-dialog -->
  </div>

<!-- Your Brands -->
<!-- <script>
function GetProductsTable(){
  $("#divpreloader").css("display", "");
  $("#prestatus").css("display", "");
  $.post('Productable.php',{"opearions":"GetProdTable","profileuserid":<?= $profileuserid?>},function(res){
    // console.log(res);
    $('#ProductTable').html(res);

    $('#prestatus').fadeOut(); // will first fade out the loading animation 
        $('#divpreloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
        $('body').delay(350).css({'overflow':'visible'});
  $("#divpreloader").css("display", "none");
  $("#prestatus").css("display", "none");
  })
 
}
GetProductsTable();
(".input-sm").change(function(){
  alert('ss');
});
</script> -->


<script>


function GetAllTable(){
  
  var subCategory = $('#subCategory').val();
  var Category = $('#FillCategory').val();
    var NUmRow = $('#SelectEntity').val();
    var profile_id='<?= $profileuserid?>';
    $.post('ProdAjax.php',{'num_row':NUmRow,"pagenum":0,"Category":Category,"subCategory":subCategory,"profile_id":profile_id},function(res){
        // alert(res);
        res = res.split('!next!');
    $('#DataTables').html(res[0]);
    $('.pagination').html(res[1]);
    $('#TextEntity').text(res[2]);
    // alert(res[1]);
    });
}
GetAllTable();

function Forward(){
  var subCategory = $('#subCategory').val();
  var Category = $('#FillCategory').val();
  var profile_id='<?= $profileuserid?>';
   var pagenum = $('.actives').data('page');
   pagenum = pagenum+1;
   var NUmRow = $('#SelectEntity').val();
    $.post('ProdAjax.php',{'num_row':NUmRow,"pagenum":pagenum,"Category":Category,"subCategory":subCategory,"profile_id":profile_id},function(res){
        // alert(res);
        res = res.split('!next!');
    $('#DataTables').html(res[0]);
    $('.pagination').html(res[1]);
    $('#TextEntity').text(res[2]);
    // alert(res[1]);
    });
}

function Back(){
 
  var subCategory = $('#subCategory').val();
  var Category = $('#FillCategory').val();
    var pagenum = $('.actives').data('page');
    var profile_id='<?= $profileuserid?>';
    // alert(pagenum);
   pagenum = pagenum-1;
  //  if(pagenum==1){
  //   pagenum = "";
  //  }
   var NUmRow = $('#SelectEntity').val();
    $.post('ProdAjax.php',{'num_row':NUmRow,"pagenum":pagenum,"Category":Category,"subCategory":subCategory,"profile_id":profile_id},function(res){
        // alert(res);
        res = res.split('!next!');
    $('#DataTables').html(res[0]);
    $('.pagination').html(res[1]);
    $('#TextEntity').text(res[2]);
    // alert(res[1]);
    });
}

function PageNUm(num){
  
  var subCategory = $('#subCategory').val();
  var Category = $('#FillCategory').val();
  var NUmRow = $('#SelectEntity').val();
  var profile_id='<?= $profileuserid?>';
    $.post('ProdAjax.php',{'num_row':NUmRow,"num_val":num,"Category":Category,"subCategory":subCategory,"profile_id":profile_id},function(res){
        // alert(res);
        res = res.split('!next!');
    $('#DataTables').html(res[0]);
    $('.pagination').html(res[1]);
    $('#TextEntity').text(res[2]);
    // alert(res[2]);
    });
    }
</script>

<script type="text/javascript">
     var table = $('table');
    
    $('th')
        .wrapInner('<span title="sort this column"/>')
        .each(function(){
            
            var th = $(this),
                thIndex = th.index(),
                inverse = false;
            
            th.click(function(){
                
                table.find('td').filter(function(){
                    
                    return $(this).index() === thIndex;
                    
                }).sortElements(function(a, b){
                    
                    return $.text([a]) > $.text([b]) ?
                        inverse ? -1 : 1
                        : inverse ? 1 : -1;
                    
                }, function(){
                    
                    // parentNode is the element we want to move
                    return this.parentNode; 
                    
                });
                
                inverse = !inverse;
                    
            });
                
        });

</script>


<script>
  function  GetSubCategory(arg) {
      //  alert(arg);
       var val =arg;
var url="getAjax.php";
  $.post(url,{"choice":"getSubcate","value":val,"subcategory":"val"},function(res){
 $('#subCategory').html(res);
// alert(res)
});
  
  } 

//   function  GetSubCategory2(val) {

// var url="vendor_getAjax.php";
//   $.post(url,{"choice":"getSubcate2","value":val,"subcategory2":"<?= $subcategory2?>"},function(res){
//  $('#Subcateid2').html(res);
// // alert(res)
// });
  
//   }
</script>
<?php include('footer.php')?>