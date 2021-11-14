<?php include('header.php')?>
  <?php include('sidebar.php')?>
  
  <!-- UIkit CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/css/uikit.min.css" />

<!-- UIkit JS -->
<script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit-icons.min.js"></script>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        CMS
        <small>Contain Management Systeam</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">CMS</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
                    <div class="app-main__inner">


<?php 
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;
$ecmember=$dbf->fetchSingle("cmc",'*',"id='$bannerId'");
?>


<?php 
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='ecmember'){
	
	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/cms/".$fname1;
		
		$destination_path1="images/cms/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/cms/".$fname1);
		
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
	$fullname=$dbf->checkXssSqlInjection($_REQUEST['fullname']);
	$content=($_REQUEST['content']);
	$email=($_REQUEST['email']);
	$contact=($_REQUEST['contact']);
	$map=($_REQUEST['map']);
	$content=($_REQUEST['content']);
	
	if($fname1!=''){
	$string="fullname='$fullname', email='$email', contact='$contact', iframe='$map', details='$content', image='$fname1', created_date=NOW()";
	}else{
		$string="fullname='$fullname', email='$email', contact='$contact', iframe='$map', details='$content', created_date=NOW()";
		}
	$dbf->updateTable("cmc",$string,"id='$bannerId'");
	
	header('Location:contactus.php?editId='.$bannerId);exit;
}

?>



<div class="app-main__inner">
 <div class="uk-card uk-card-body uk-card-default uk-bakground-muted">
 <div class="uk-grid uk-grid-small">
 <div class="uk-width-expand"></div>
 <div class="uk-width-auto">
 <a href="cms-management.php" class="uk-button uk-button-secondary  uk-margin-bottom"><span uk-icon="icon: chevron-double-left"></span> BACK </a>
 </div>
 </div>
<form  action="" method="post" enctype="multipart/form-data" name="frm" id="frm">
<div class="uk-grid uk-grid-small uk-child-width-1-1@s uk-grid-match">
<input type="hidden" class="uk-input" name="pagesId"  value="<?php echo $ecmember['id'];?>">
	<div class="uk-margin-small">
    <h6>Title</h5>
    <input type="text"  class="uk-input"  id="fullname" name="fullname" value="<?php echo $ecmember['fullname'];?>" />
    </div>
    <div class="uk-margin-small">
    <h6>Email</h5>
    <input type="email"  class="uk-input"  id="email" name="email" value="<?php echo $ecmember['email'];?>"  />
    </div>
    
    <div class="uk-margin-small">
    <h6>Contact No</h5>
    <input type="text" class="uk-input"  id="contact" name="contact" value="<?php echo $ecmember['contact'];?>"  />
    </div>
    
    <div class="uk-margin-small">
    <h6>Google Map Iframe</h5>
    <textarea class="uk-textarea" name="map" id="map" ><?php echo $ecmember['iframe'];?></textarea>
    </div>
    
	<div class="uk-margin-small uk-width-1-1">
     <h5>Address</h5>
    <textarea name="content"  id="editor1"  rows="10" cols="80" ><?php echo $ecmember['details'];?></textarea>
    </div>
	<div class="uk-margin-small-small uk-width-1-1">
    <h5>Upload Image</h5>
    <div class="js-upload uk-placeholder uk-text-center">
    <span uk-icon="icon: cloud-upload"></span>
    <span class="uk-text-middle">Attach binaries by dropping them here or</span>
    <div uk-form-custom>
        <input type="file" name="img"  id="img">
        <span class="uk-link">selecting one</span>
    </div>
</div>
</div>

</div>
<p>&nbsp;</p>
<button class="uk-button uk-button-secondary" type="submit" name="submit" id="submit" value="ecmember"  >Submit</button>

</form>


</div>
</div>

</div>

</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include("footer.php") ?>