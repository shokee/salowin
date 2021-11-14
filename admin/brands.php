<?php include('header.php')?>
<?php include('sidebar.php')?>
  
  <?php 
  ########################## insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addcountry'){
	
if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/brands/".$fname1;
		
		$destination_path1="images/brands/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/brands/".$fname1);
		
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
$country=$dbf->checkXssSqlInjection($_REQUEST['country']);
	


$cntcountry=$dbf->countRows("brands","brands_name='$country'");

if($cntcountry==0){
$string="brands_name='$country',images='$fname1', created_date=NOW()";
$dbf->insertSet("brands",$string);
header("Location:brands.php?msg=success");
}else{	
header("Location:brands.php?msg=exit");
}
}
?>


            
 <?php
########################## UPDATE STATUS  agent #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='update'){
   $id=$dbf->checkXssSqlInjection($_REQUEST['id']);
	$ststus=$dbf->checkXssSqlInjection($_REQUEST['ststus']);
	$dsply=$dbf->checkXssSqlInjection($_REQUEST['dsply']);
// 	$dbf->updateTable("brands","status='$ststus',display_in_home='$dsply'", "brands_id='$id'");
	if($ststus==''){
	   $dbf->updateTable("brands","display_in_home='$dsply'", "brands_id='$id'"); 
	}else{
	    $dbf->updateTable("brands","status='$ststus'", "brands_id='$id'");
	}

	 
	header("Location:brands.php");
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

function upDateDisplay(id,id1){
	//alert(id)
	if(id1=='1'){
		var msg ="Are you sure want to active this Record";
		}else{
			var msg ="Are you sure want to block this Record";
			}
	
	$("#display").val(id1);
	$("#id").val(id);
	var conf=confirm(msg);
	if(conf){
	   $("#frm_update").submit();
	}
}
</script>


<form name="frm_deleteBanner" id="frm_update" action="" method="post">
  <input type="hidden" name="operation" id="operation1" value="update">
  <input type="hidden" name="id" id="id" value="">
  <input type="hidden" name="ststus" id="status" value="">
  <input type="hidden" name="dsply" id="display" value="">
  </form>
  
  
             
   
<?php
########################## DELETE  #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
 
 $resBanner=$dbf->fetchSingle("brands","*","brands_id='$id'");	
	@unlink("images/brands/".$resBanner['images']);
	@unlink("images/brands/thumb/".$resBanner['images']);
	
	$dbf->deleteFromTable("brands","brands_id='$id'");
	header("Location:brands.php");
}






//Upload Excel Brand
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

$name =trim($worksheet->getCellByColumnAndRow(1, $i)->getValue());

$Chk_Brands=$dbf->countRows("brands","brands_name='$name'","");
if($Chk_Brands==0){
if(isset($img[$i-2]) && $img[$i-2]->getCoordinates()!=''){
  
  list($startColumn, $startRow) = PHPExcel_Cell::coordinateFromString($img[$i-2]->getCoordinates()); //Get the row and column of the picture
$imageFileName = "Category".$img[$i-2]->getCoordinates().mt_rand(10000, 99999);

switch($img[$i-2]->getExtension()) {
case 'jpeg':
    $imageFileName .= '.jpeg';
    $source = imagecreatefromjpeg($img[$i-2]->getPath());
    imagejpeg($source, "images/brands/".$imageFileName);
    imagejpeg($source, "images/brands/thumb/".$imageFileName);
    break;
case 'gif':
    $imageFileName .= '.gif';
    $source = imagecreatefromgif($img[$i-2]->getPath());
    imagejpeg($source, "images/brands/".$imageFileName);
    imagejpeg($source, "images/brands/thumb/".$imageFileName);
    break;
case 'png':
    $imageFileName .= '.png';
    $source = imagecreatefrompng($img[$i-2]->getPath());
    imagejpeg($source, "images/brands/".$imageFileName);
    imagejpeg($source, "images/brands/thumb/".$imageFileName);
    break;
 default:
 $imageFileName .= 'no-img.png';
 }
}else{
   $imageFileName = 'no-img.png';
}

$status = trim($worksheet->getCellByColumnAndRow(2, $i)->getValue());
$dbf->insertSet("brands","brands_name='$name',images='$imageFileName',status='$status'");
}

}
} 
header("Location:brands.php");exit;
}
else
{
  $error="Invalid File Format ,Please Check It!!!";

}

}
//Upload Excel Brand

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
        Brands
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
      
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Brand Add Successfully</p>
</div>
 <?php }?>
 
 
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Brand Already Exit</p>
</div>
 <?php }?>
 
            <div class="box-header">
              <h3 class="box-title">
              
              <?php if(in_array('10.1',$Job_assign)){?>
              <button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add </button>
              <?php }?>

              <?php if(in_array('10.2',$Job_assign)){?>
              <button class="btn btn-primary" data-toggle="modal" data-target="#Upload_Excel_prod">
              <i class="fa fa-upload" aria-hidden="true">
              <i class="fa fa-file-excel-o" aria-hidden="true">
              </i>
              </i>
              </button>
              <?php }?>
              <a class="btn btn-primary" href="dist/upload_excel/Brands.xlsx">
             
              <i class="fa fa-file-excel-o" aria-hidden="true">
            
              </i>
              </a>
              </h3>

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
      <label for="excel_upload_prod">Upload Brands</label>
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
              
            <form  action="" enctype="multipart/form-data" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add  </h4>
                
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
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Slno.</th>
                  <th>Brands Name</th>
                  <th>Images</th>
                  <?php if(in_array('10.3',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <th>Display</th>
                  <?php if(in_array('10.4',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('10.4',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
                
                    <?php
					$i=1;
					 foreach($dbf->fetchOrder("brands","","brands_id DESC","","") as $resBanner){
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $resBanner['brands_name'];?></td>
                 <td> <img src="images/brands/<?php echo $resBanner['images'];?>" width="50px" ></td>
                 <?php if(in_array('10.3',$Job_assign)){?>
                  <td>
 <?php if($resBanner['status']=='1'){?><button type="button" class="btn btn-success" onClick="upDateStatus(<?=$resBanner['brands_id']?>,0)">Active</button><?php }else{?><button type="button" class="btn btn-danger" onClick="upDateStatus(<?=$resBanner['brands_id']?>,1)">Block</button> <?php }?>
                  </td>
                 <?php }?>
                 
                 <?php if(in_array('10.3',$Job_assign)){?>
                  <td>
 <?php if($resBanner['display_in_home']=='1'){?><button type="button" class="btn btn-success" onClick="upDateDisplay(<?=$resBanner['brands_id']?>,0)">Dispaly To Home</button><?php }else{?><button type="button" class="btn btn-danger" onClick="upDateDisplay(<?=$resBanner['brands_id']?>,1)">Block</button> <?php }?>
                  </td>
                 <?php }?>
                 
   <?php if(in_array('10.4',$Job_assign)){?>
<td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?php echo $resBanner['brands_id'];?>" ><i class="fa fa-edit"></i></a></td>
<?php }?>
<?php if(in_array('10.5',$Job_assign)){?>
<td> <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['brands_id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a></td>
<?php }?>       
                </tr>
                <?php $i++; } ?>
                
                
                
               
               
               
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>Brands Name</th>
                  <th>Images</th>
                  <?php if(in_array('10.3',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <th>Display</th>
                  <?php if(in_array('10.4',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('10.4',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </tfoot>
              </table>
 
 
 
 
 <?php 
########################## EDIT  #############################
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editcountry'){

if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/brands/".$fname1;
		
		$destination_path1="images/brands/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/brands/".$fname1);
		
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
	
	
	$id=$dbf->checkXssSqlInjection($_REQUEST['pagesId']);
	$country=$dbf->checkXssSqlInjection($_REQUEST['country']);
	
	
	if($fname1!=''){
	$string="brands_name='$country', images='$fname1', created_date=NOW()";
	}else{
		$string="brands_name='$country', created_date=NOW()";
		}
	
	$dbf->updateTable("brands",$string,"brands_id='$id'");
	
	header('Location:brands.php?editId='.$id);exit;
}
?>
 
              
<?php
$i=1;
foreach($dbf->fetchOrder("brands","","brands_id ASC","","") as $resBanner){
?>
              <div class="modal fade" id="modal-default-edit<?php echo $resBanner['brands_id'];?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit </h4>
              </div>
              <div class="modal-body">
                
                <form  action="" method="post" enctype="multipart/form-data" name="frm" id="frm" >
                <div class="form-group">
                           <input type="hidden" class="form-control" name="pagesId"  value="<?php echo $resBanner['brands_id'];?>">
                    <input type="text" class="form-control" name="country" id="country" value="<?php echo $resBanner['brands_name'];?> "/>
                </div>
                 <div class="form-group">
                  <label for="exampleInputEmail1"> Images</label>
                  <input type="file" class="form-control" id="img" name="img" >
                </div>
                <img src="images/brands/<?php echo $resBanner['images'];?>" width="80px">
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
