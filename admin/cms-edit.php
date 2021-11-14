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
        Page Header
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
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
	
	if($fname1!=''){
	$string="fullname='$fullname', details='$content', image='$fname1', created_date=NOW()";
	}else{
		$string="fullname='$fullname', details='$content', created_date=NOW()";
		}
	$dbf->updateTable("cmc",$string,"id='$bannerId'");
	
	header('Location:cms-edit.php?editId='.$bannerId);exit;
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
<input type="hidden" class="uk-input" name="pagesId"  value="<?php echo $ecmember['id'];?>">
    <div class="uk-margin-small">
 <h5>Title</h5>
    <input type="text" placeholder="Title" class="uk-input"  id="fullname" name="fullname" value="<?php echo $ecmember['fullname'];?>" />
    
    </div>
    
	<div class="uk-margin-small uk-width-1-1">
     <h5>Description </h5>
    <textarea name="content"  id="editor1"  rows="10" cols="80"> <?php echo $ecmember['details'];?> </textarea>
    </div>

    
    <h5>Upload Image</h5>
    <div class="js-upload uk-placeholder uk-text-center">
    <span uk-icon="icon: cloud-upload"></span>
    <span class="uk-text-middle">Attach binaries by dropping them here or</span>
    <div uk-form-custom>
        <input type="file" name="img"  id="img" value="<?php echo $ecmember['image'];?>">
        <span class="uk-link">selecting one</span>
    </div>
</div>

 										<?php if($ecmember['image']<>''){?>
                                        <img src="images/cms/thumb/<?php echo $ecmember['image'];?> "   style="width:100px; height:100px;" >
                                        <?php }else{?>
                                         <img src="images/default.jpg?> "  style="width:100px; height:100px;"  >
                                        <?php }?>
                                        




<br><p></p>

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
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1',)
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
  })
  
</script>