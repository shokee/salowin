<?php include('header.php')?>
<?php include('sidebar.php')?>
  
  <?php 
  ########################## insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addcountry'){
$country=$_REQUEST['country'];
	
	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
  
    $fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
    $source_path1="images/category/".$fname1;
    
    $destination_path1="images/category/thumb/".$fname1;  
    $imgsize1 = getimagesize($source_path1);    
    $new_height1 = 400;
    $new_width1 = 1160;   
    $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");           
    move_uploaded_file($_FILES['img']['tmp_name'],"images/category/".$fname1);
    
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

$string="product_catagory_1_name='$country',img='$fname1',created_date=NOW()";
$dbf->insertSet("product_catagory_1",$string);
  
header("Location:catagory-1.php?msg=success");
  }else{
  header("Location:catagory-1.php?msg=imgErro");

  }
  //@unlink("../banner-img/".$fname1);

	
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
$img=$objWorksheet->getDrawingCollection();



$Category =trim($worksheet->getCellByColumnAndRow(1, $i)->getValue());
$SubCategory = trim($worksheet->getCellByColumnAndRow(2, $i)->getValue());
$SubCategory = explode('|',$SubCategory);
$SubCategory2 = trim($worksheet->getCellByColumnAndRow(3, $i)->getValue());
$SubCategory2 = explode('|',$SubCategory2);
$Chk_Cate=$dbf->countRows("product_catagory_1","product_catagory_1_name='$Category'","");
if($Chk_Cate==0){
  if($Category!=''){
  if(isset($img[$i-2]) && $img[$i-2]->getCoordinates()!=''){
  
    list($startColumn, $startRow) = PHPExcel_Cell::coordinateFromString($img[$i-2]->getCoordinates()); //Get the row and column of the picture
  $imageFileName = "Category".$img[$i-2]->getCoordinates().mt_rand(10000, 99999);
  
  switch($img[$i-2]->getExtension()) {
  case 'jpeg':
      $imageFileName .= '.jpeg';
      $source = imagecreatefromjpeg($img[$i-2]->getPath());
      imagejpeg($source, "images/category/".$imageFileName);
      imagejpeg($source, "images/category/thumb/".$imageFileName);
      break;
  case 'gif':
      $imageFileName .= '.gif';
      $source = imagecreatefromgif($img[$i-2]->getPath());
      imagejpeg($source, "images/category/".$imageFileName);
      imagejpeg($source, "images/category/thumb/".$imageFileName);
      break;
  case 'png':
      $imageFileName .= '.png';
      $source = imagecreatefrompng($img[$i-2]->getPath());
      imagejpeg($source, "images/category/".$imageFileName);
      imagejpeg($source, "images/category/thumb/".$imageFileName);
      break;
   default:
   $imageFileName .= 'no-img.png';
   }
  }else{
     $imageFileName = 'no-img.png';
  } 

$CateGory_id=$dbf->insertSet("product_catagory_1","product_catagory_1_name='$Category',img='$imageFileName'");
}else{
  $Categpry=$dbf->fetchSingle("product_catagory_1",'product_catagory_1_id',"product_catagory_1_name='$Category'");
  $CateGory_id = $Categpry['product_catagory_1_id'];
}
  for($j=0;$j<count($SubCategory);$j++){
    $Chk_SubCate=$dbf->countRows("product_catagory_2","product_catagory_2_name='$SubCategory[$j]' AND product_catagory_1_id='$CateGory_id'","");
  if($Chk_SubCate==0){
    $SubCateGory_id=$dbf->insertSet("product_catagory_2","product_catagory_2_name='$SubCategory[$j]',product_catagory_1_id='$CateGory_id'");
    }else{
      $SubCategry=$dbf->fetchSingle("product_catagory_2",'product_catagory_2_id',"product_catagory_2_name='$SubCategory[$j]' AND product_catagory_1_id='$CateGory_id'");
      $SubCateGory_id = $SubCategry['product_catagory_2_id'];
    }


    $SubCategorys2=explode(',',$SubCategory2[$j]);
    
  foreach($SubCategorys2 as $sub_cate2){
      
    $Chk_SubCate2=$dbf->countRows("product_catagory_3","product_catagory_3_name='$sub_cate2' AND product_catagory_2_id='$SubCateGory_id' AND product_catagory_1_id='$CateGory_id'","");
      if($Chk_SubCate2==0){
    $dbf->insertSet("product_catagory_3","product_catagory_3_name='$sub_cate2',product_catagory_2_id='$SubCateGory_id',product_catagory_1_id='$CateGory_id'");
      }
      
  }
}
}
}
} 
header("Location:catagory-1.php");exit;
}
else
{
  $error="Invalid File Format ,Please Check It!!!";

}

}

?>
 <?php 
//Upload Excel Product
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='Export'){
    include("php/PHPExcel.php");
    $object = new PHPExcel();
    $object->setActiveSheetIndex(0);    
    $table_columns = array("Slno","Category Name");
    $column = 0;
  
    
    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $column++;
    }
  
    $excel_row = 2;
  
    $i=1;
    foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","","") as $Cateory){
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row,$i);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row,$Cateory['product_catagory_1_name']);
      $excel_row++;					
    }
  
    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
  
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition:attachment;filename="grogodCategory.xls"');
    $object_writer->save('php://output');

    header("Location:catagory-1.php");exit;
  }
?>
   
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Catagory Add Successfully</p>
</div>
 <?php }?>
            
    <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='imgErro'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Image Format Error!!!!</p>
</div>
 <?php }?>       
   
<?php
########################## DELETE COUNTRY #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	
	$dbf->deleteFromTable("product_catagory_1","product_catagory_1_id='$id'");
	header("Location:catagory-1.php");
}
?>

 <form name="frm_deleteBanner" id="frm_deleteBanner" action="" method="post">
 <input type="hidden" name="operation" id="operation" value="">
 <input type="hidden" name="country_id" id="country_id" value="">
 </form>
                  
<script type="text/javascript">
function deleteRecord(id){
	$("#operation").val('delete');
	$("#country_id").val(id);
	var conf=confirm("Are you sure want to delete this Record");
	if(conf){
	   $("#frm_deleteBanner").submit();
	}
}
</script>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        catagory level 1
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
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
              <h3 class="box-title">
              <?php if(in_array('7.1',$Job_assign)){?>
              <button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add" type="button">Add Catagory</button>
              <?php }?>
              <?php if(in_array('7.2',$Job_assign)){?>
              <button class="btn btn-primary" data-toggle="modal" data-target="#Upload_Excel_prod" type="button">
              <i class="fa fa-upload" aria-hidden="true">
              <i class="fa fa-file-excel-o" aria-hidden="true">
              </i>
              </i>
              </button>
              <?php }?>
              
              <a class="btn btn-primary" href="dist/upload_excel/Categories.xlsx">
              <i class="fa fa-file-excel-o" aria-hidden="true"></i>
              </a>
              <?php if(in_array('7.3',$Job_assign)){?>
              <button class="btn btn-primary" name="operation" value="cateExport">  
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
      <label for="excel_upload_prod">Upload Categories</label>
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
              <div class="modal fade" id="modal-default-add">
              
            <form  action="" method="post" enctype="multipart/form-data">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Catagory </h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>Enter atagory Name</label>
                <input type="text" class="form-control" name="country" id="country" placeholder="Enter Catagory Name" required />
                
                
                </div>
                  <div class="form-group">
                <label>Category Image</label>
                <input type="file" class="form-control" name="img" required accept="image/*" />
                
                
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
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Slno.</th>
                  <th>Catagory Name</th>
                  <?php if(in_array('7.4',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('7.5',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
                
                    <?php
					$i=1;
					 foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","","") as $resBanner){
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $resBanner['product_catagory_1_name'];?></td>
     <?php if(in_array('7.4',$Job_assign)){?>
<td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?php echo $resBanner['product_catagory_1_id'];?>" ><i class="fa fa-edit"></i></a></td>
     <?php }?>
     <?php if(in_array('7.5',$Job_assign)){?>
<td> <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['product_catagory_1_id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a>
     <?php }?>            
                  </td>
                </tr>
                <?php $i++; } ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>Catagory Name</th>
                  <?php if(in_array('7.4',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('7.5',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </tfoot>
              </table>
 
 
 
 
 <?php 
########################## EDIT COUNTRY #############################
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editcountry'){
	
	
	$id=$dbf->checkXssSqlInjection($_REQUEST['pagesId']);
	$country=$_REQUEST['country'];
  if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
  
    $fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
    $source_path1="images/category/".$fname1;
    
    $destination_path1="images/category/thumb/".$fname1;  
    $imgsize1 = getimagesize($source_path1);    
    $new_height1 = 400;
    $new_width1 = 1160;   
    $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");           
    move_uploaded_file($_FILES['img']['tmp_name'],"images/category/".$fname1);
    
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

if($fname1!=''){
 $string="product_catagory_1_name='$country',img='$fname1',created_date=NOW()";
}else{
  $string="product_catagory_1_name='$country', created_date=NOW()";
}
	$dbf->updateTable("product_catagory_1",$string,"product_catagory_1_id='$id'");
	
	header('Location:catagory-1.php?editId='.$id);exit;
}
?>
           
<?php
$i=1;
foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","","") as $resBanner){
?>
              <div class="modal fade" id="modal-default-edit<?php echo $resBanner['product_catagory_1_id'];?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit </h4>
              </div>
              <div class="modal-body">
                
                <form  action="" method="post" enctype="multipart/form-data" >
 <div class="form-group">
		   <input type="hidden" class="form-control" name="pagesId"  value="<?php echo $resBanner['product_catagory_1_id'];?>">
	<input type="text" class="form-control" name="country" id="country" value="<?php echo $resBanner['product_catagory_1_name'];?> "/>
</div>

 <div class="form-group">
                <label>Category Image</label>
                <input type="file" class="form-control" name="img"  accept="image/*" />
                
                <img src="images/category/<?= $resBanner[img]?>" width="50">
                </div>
                
              </div>
              <div class="modal-footer">
                <button class="btn btn-default pull-left" type="submit" name="submit" value="editcountry">Update </button>
                
                </form>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
              
<?php }?>             
              
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>
