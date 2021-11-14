<?php include('header.php')?>
<?php include('sidebar.php')?>
  
<?php 
  ########################## insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addtype'){
$type_name=trim($dbf->checkXssSqlInjection($_REQUEST['type_name']));
$cnttype=$dbf->countRows("type","type_name='$type_name'");
if($cnttype==0){


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
	
$string="type_name='$type_name',type_image='$fname1',created_date=NOW()";
$dbf->insertSet("type",$string);
header("Location:types.php?msg=success");
}else{	
header("Location:types.php?msg=exit");
}
}
}
?>
<?php
############################## Import Excel #########################


          if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='import'){ 
           
          
            

              $file=$_FILES['upload']['tmp_name'];
              require 'phpexcel/PHPExcel.php';
              require_once 'phpexcel/PHPExcel/IOFactory.php';
              $objExcel=PHPExcel_IOFactory::load($file);
              foreach($objExcel->getWorksheetIterator() as $worksheet){

                
                 $maxrow=$worksheet->getHighestRow();
                 for($row=2; $row<=$maxrow; $row++){

                  $id = $objExcel->getActiveSheet()->getCell("A".$row)->getValue();
                  $name = $objExcel->getActiveSheet()->getCell("B".$row)->getValue();
               
                  
                  $string="country_id='$id',country_name='$name'";
                  $dbf->insertSet("country",$string);

                 }
                 header("Location:country.php?msg=Importsuccess");


              }
}?>



     
<?php
########################## DELETE  #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $id=$dbf->checkXssSqlInjection($_REQUEST['type_id']);
	
	$dbf->deleteFromTable("type","type_id='$id'");
// 	$dbf->deleteFromTable("state","Country_id='$id'");
// 	$dbf->deleteFromTable("city","country_id='$id'");
	header("Location:types.php");
}
?>

 <form name="frm_deleteBanner" id="frm_deleteBanner" action="" method="post">
 <input type="hidden" name="operation" id="operation" value="">
 <input type="hidden" name="type_id" id="type_id" value="">
 </form>
                  
<script type="text/javascript">
function deleteRecord(id){
	$("#operation").val('delete');
	$("#type_id").val(id);
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
        Types
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
<p>Types Add Successfully</p>
</div>
 <?php }?>
 
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='Importsuccess'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Types Imported Successfully</p>
</div>
 <?php }?>

       
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='successup'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Types Updated Successfully</p>
</div>
 <?php }?>
 
 
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Types Already Exit</p>
</div>
 <?php }?>
 
 
 
            <div class="box-header">
            <?php if(in_array('16.1',$Job_assign)){?>
              <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add Types</button></h3>
            <?php }?>
              <div class="modal fade" id="modal-default-add">
              
            <form  action="" method="post" enctype="multipart/form-data">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Types </h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>Enter Types Name</label>
                <input type="text" class="form-control" name="type_name"  placeholder="Enter Types Name" required />
                </div>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>Enter Image Name</label>
                <input type="file" class="form-control" name="img"   required />
                </div>
              </div>
              
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="addtype" name="submit">Add Types</button>
                <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
          <!-- /.modal-dialog -->
        </div>
        
          <button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal212">Import Types</button>
          
              <div class="modal fade" id="myModal212" role="dialog">
    <div class="modal-dialog">
    
      <!--  --  --   -- Modal content   --   --   -    -- -->


      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
        <div class="modal-body">
           
           
            <label> Select Excel-file to upload:</label>
            <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="upload" class="form-control" required >

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary pull-left " value="import" name="submit"> Import</button>
            
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>
        
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Slno.</th>
                  <th>Types Name</th>
                  <th>Types Image</th>
                  <?php if(in_array('16.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                
                    <?php
					$i=1;
					 foreach($dbf->fetchOrder("type","","type_id ASC","","") as $resBanner){
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $resBanner['type_name'];?></td>
                  <td><img src="images/brands/<?php echo $resBanner['type_image'];?>" width="30"/></td>
                  <?php if(in_array('16.2',$Job_assign)){?>
            <td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?php echo $resBanner['type_id'];?>" ><i class="fa fa-edit"></i></a></td>
                  <?php }?>

<td> <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['type_id'];?>');" class="btn btn-social-icon btn-danger"  ><i class="fa fa-trash"></i></a>
                  
                  </td>
                </tr>
                <?php $i++; } ?>
                
                
                
               
               
               
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>Types Name</th>
                  <th>Types Image</th>
                  <?php if(in_array('16.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <th>Delete</th>
                </tr>
                </tfoot>
              </table>
 
 
 
 
 <?php 
########################## EDIT GROUPS #############################

$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='edittype'){

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
	
	
	$id=$dbf->checkXssSqlInjection($_REQUEST['type_id']);
	$type_name=$dbf->checkXssSqlInjection($_REQUEST['type_name']);
	
	
	if($fname1!=''){
	$string="type_name='$type_name', type_image='$fname1', created_date=NOW()";
	}else{
		$string="type_name='$type_name', created_date=NOW()";
		}
	
	$dbf->updateTable("type",$string,"type_id='$id'");
	
	header('Location:types.php?editId='.$id);exit;
}
?>
 
              
<?php
$i=1;
foreach($dbf->fetchOrder("type","","type_id ASC","","") as $resBanner){
?>
              <div class="modal fade" id="modal-default-edit<?php echo $resBanner['type_id'];?>">
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
                           <input type="hidden" class="form-control" name="type_id"  value="<?php echo $resBanner['type_id'];?>">
                    <input type="text" class="form-control" name="type_name" id="country" value="<?php echo $resBanner['type_name'];?> ">
                </div>
                 <div class="form-group">
                  <label for="exampleInputEmail1"> Images</label>
                  <input type="file" class="form-control" id="img" name="img" >
                </div>
                <img src="images/brands/<?php echo $resBanner['type_image'];?>" width="80px">
              </div>
              <div class="modal-footer">
                <button class="btn btn-default pull-left" type="submit" name="submit" value="edittype">Update </button>
                
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
