 
 
 <?php include('header.php')?>
 <?php include('sidebar.php')?>

                    <div class="content-wrapper">
                     <div class="uk-card uk-card-body uk-card-default uk-margin-bottom uk-card-small">
     
      <ol class="uk-breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Testimonial</li>
      </ol>
 </div>
 
                       <div class="uk-card uk-card-default uk-card-body">
  <?php 
  
  ########################## insert specialization #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addcountry'){
	
	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/".$fname1;
		
		$destination_path1="images/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 1160;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/".$fname1);
		
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
$description=base64_encode($_REQUEST['description']);
	
$string="username='$country', message='$description', image='$fname1', created_date=NOW()";
$dbf->insertSet("testimonial",$string);
	
	
header("Location:testimonial.php?msg=success");
	
}
?>

<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>specialization Add Successfully</p>
</div>
 <?php }?>
            
            
   
<?php
########################## DELETE  #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $id=$dbf->checkXssSqlInjection($_REQUEST['specialization_id']);
 
 $testimg=$dbf->fetchSingle("testimonial","*","testimonial_id='$id'");	
 @unlink("images/".$testimg['image']);
	@unlink("images/thumb/".$testimg['image']);
	
	$dbf->deleteFromTable("testimonial","testimonial_id='$id'");
	header("Location:testimonial.php");
}
?>

 <form name="frm_deleteBanner" id="frm_deleteBanner" action="" method="post">
 <input type="hidden" name="operation" id="operation" value="">
 <input type="hidden" name="specialization_id" id="specialization_id" value="">
 </form>
                  
<script type="text/javascript">
function deleteRecord(id){
	$("#operation").val('delete');
	$("#specialization_id").val(id);
	var conf=confirm("Are you sure want to delete this Record");
	if(conf){
	   $("#frm_deleteBanner").submit();
	}
}
</script>




  <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <?php if(in_array('24.1',$Job_assign)){?>
	<a class="uk-button uk-button-primary uk-margin-bottom" href="#modal-center" uk-toggle >Add Testimonial </a>
    <?php }?>
    
    <div id="modal-center" class="uk-flex-top" uk-modal >
              
            <form  action="" method="post" enctype="multipart/form-data">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                  
                <h4 class="modal-title">Add Testimonial </h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>User Name </label>
                <input type="text" class="form-control" name="country" id="country"  required />
                </div>
                
                 <div class="form-group">
                <label> Testimonial</label>
                <textarea class="form-control" name="description" id="description" placeholder="Enter Testimonial " required> </textarea>
                </div>
                
                 <div class="form-group">
                <label> Image </label>
                <input type="file" class="form-control"  name="img" id="img"  required />
                </div>
                
                
              </div>
              <div class="modal-footer">
              <button type="submit" class="uk-button uk-button-primary " value="addcountry" name="submit">Add Testimonial</button>
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
          <!-- /.modal-dialog -->
        </div>
        
        
        
        
        
      <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Slno.</th>
                  <th> Name</th>
                  <th style="width:400px;">Testimonial</th>
                  <th>image</th>
                  <!--<th>Edit</th>-->
                  <?php if(in_array('24.2',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
                
                    <?php
					$i=1;
					 foreach($dbf->fetchOrder("testimonial","","testimonial_id ASC","","") as $tstimonial){
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $tstimonial['username'];?></td>
                   <td><?php echo base64_decode($tstimonial['message']);?></td>
                  <td>
				  <?php if($tstimonial['image']<>''){?>
                  <img src="images/thumb/<?php echo $tstimonial['image'];?> "   style="width:40px; height:40px;" >
                  <?php }else{?>
                  <img src="images/default.jpg?> "  style="width:40px; height:40px;"  >
                  <?php }?>
    				</td>
<td style="display:none"><a class="uk-icon-button uk-button-primary " uk-icon="file-edit" href="#modal-center<?php echo $tstimonial['testimonial_id'];?>" uk-toggle ></a></td>
<td> <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $tstimonial['testimonial_id'];?>');" class="uk-icon-button uk-button-danger" uk-icon="trash" ></a>
                  
                  </td>
                </tr>
                <?php $i++; } ?>
                </tbody>
                <tfoot>
                <tr>
                 <th>Slno.</th>
                  <th> Name</th>
                  <th>Testimonial</th>
                  <th>image</th>
<!--                  <th>Edit</th>
      <?php if(in_array('24.2',$Job_assign)){?> 
-->                  <th>Delete</th>
      <?php }?>
                </tr>
                </tfoot>
              </table>
 
 
 
 
 <?php 
########################## EDIT COUNTRY #############################
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editcountry'){
	
	
	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="../images/".$fname1;
		
		$destination_path1="../images/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"../images/".$fname1);
		
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
	$description=base64_encode($_REQUEST['description']);
	
	
	
	if($fname1!=''){
	$string="username='$country', message='$description', image='$fname1', created_date=NOW()";
	}else{
		$string="username='$country', message='$description', created_date=NOW()";
		}
	$dbf->updateTable("testimonial",$string,"testimonial_id='$id'");
	
	header('Location:testimonial.php?editId='.$id);exit;
}
?>
 
              
<?php
$i=1;
foreach($dbf->fetchOrder("testimonial","","testimonial_id ASC","","") as $resBanner){
?>
              <div id="modal-center<?php echo $resBanner['testimonial_id'];?>" class="uk-flex-top" uk-modal>
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                 
                <h4>Edit Testimonial</h4>
              </div>
              <div class="modal-body">
                
                <form  action="" method="post" enctype="multipart/form-data" name="frm" id="frm" >

		   <input type="hidden" class="form-control" name="pagesId"  value="<?php echo $resBanner['testimonial_id'];?>">
	<input type="text" class="form-control" name="country" id="country" value="<?php echo $resBanner['username'];?> "/>
    <textarea type="text" class="form-control uk-margin-top" name="description" id="description" >
    <?php echo base64_decode($resBanner['message']);?>
    </textarea>
    <input type="file" name="img" id="img" class="form-control uk-margin-top" />

                
              </div>
              <div class="modal-footer">
                <button class="uk-button uk-button-primary" type="submit" name="submit" value="editcountry">Update specialization</button>
                
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

    <!-- /.content -->
  <!-- /.content-wrapper -->
  </div>
  </div>
   <?php include('footer.php')?>
