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
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addBanner'){

	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/banner/".$fname1;
		
		$destination_path1="images/banner/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 1160;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/banner/".$fname1);
		
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
	$banner_title=$dbf->checkXssSqlInjection($_REQUEST['banner_title']);
	$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
	$city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
	$zcode = $dbf->checkXssSqlInjection($_REQUEST['zcode']);
	$url = $_POST['url'];
	$string="banner_title='$banner_title', banner_image='$fname1',country_id='$country_id',state_id='$state_id',city_id='$city_id',pin_id='$zcode',url='$url',created_date=NOW()";
	$dbf->insertSet("banner",$string);
	
	header("Location:add-banner.php?msg=succes");exit;
}
?>




<div class="app-main__inner">
 <div class="uk-card uk-card-body uk-card-default uk-bakground-muted">
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='succes'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>New Delivery Boy Add Successfully</p>
</div>
<?php }?>
 <div class="uk-grid uk-grid-small">
 <div class="uk-width-expand"></div>
 <div class="uk-width-auto">
 <a href="banner-management.php" class="uk-button uk-button-secondary  uk-margin-bottom"><span uk-icon="icon: chevron-double-left"></span> BACK </a>
 </div>
 </div>
<form  action="" method="post" enctype="multipart/form-data" name="frm" id="frm">
 <h5>Title</h5>
    <input type="text" placeholder="Title" class="uk-input"  id="banner_title" name="banner_title" autocomplete="off" required />
	<h5>URL</h5>
    <input type="text" placeholder="URL" class="uk-input"  id="banner_title" name="url" autocomplete="off" required />
    
    <h5>Upload Image</h5>
    <div class="js-upload uk-placeholder uk-text-center">
    <span uk-icon="icon: cloud-upload"></span>
    <span class="uk-text-middle">Attach binaries by dropping them here or</span>
    <div uk-form-custom>
        <input type="file" name="img" required id="img">
        <span class="uk-link">selecting one</span>
    </div>

</div>

<!--<div class="row">
		<div class="col-md-3">
		<label class="" >Select Contry</label>
		<select class="form-control" name="country_id" onChange=" GetState(this.value)" required>
                <option value="" >--Select Country--</option>
    			<?php //  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
    			<option value="<?//=$countryName['country_id']?>" ><?//=$countryName['country_name']?></option>
   			    <?//php }?>
    			</select>
		</div>

		<div class="col-md-3">
		<label class="" >Select State</label>
       			 <select class="form-control" name="state_id" id="stateres" onChange="GetCity(this.value)" required>
    			 <option value="" >--Select State--</option>
    			 </select>
		</div>

		<div class="col-md-3">
		<label class="" for="inlineFormCustomSelect">Select City</label>
     			 <select class="form-control" name="city_id" id="cityres" required onchange="SelectOnPin(this.value)">
    			 <option value="" >--Select City--</option>
    			</select>
		</div>
		<div class="col-md-3">
		<label class="" for="inlineFormCustomSelect">Select Pin</label>
                <select class="uk-select" required="" name="zcode" id="zcode" >
                  <option value="">--Select Pincode--</option>
                </select>
		</div>
	</div>-->
<br>



<button class="uk-button uk-button-secondary" type="submit" name="submit" id="submit" value="addBanner"  >Submit</button>

</form>




</div>
</div>
 </section>
    <!-- /.content -->
  </div>
  <script>
    	function SelectOnPin(arg){
  var url="getAjax.php";
  $.post(url,{"choice":"changPin","value":arg},function(res){
 $('#zcode').html(res);
// alert(res)
});
}
</script>
  <!-- /.content-wrapper -->
<?php include("footer.php") ?>