<?php include('header.php')?>
  <?php include('sidebar.php')?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Website Setting
        <small>All Website Setting </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">setting</li>
      </ol>
    </section>

    <!-- Main content -->
    
     <?php 
 ########################## update agent #############################
		if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editagent'){
	
		if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/".$fname1;
		
		$destination_path1="images/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
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
  
  
  //Admin Login Img
if($_FILES['img1']['name']!='' && (($_FILES['img1']['type'] == "image/gif") || ($_FILES['img1']['type'] == "image/jpeg") || ($_FILES['img1']['type'] == "image/pjpeg") || ($_FILES['img1']['type'] == "image/png") || ($_FILES['img1']['type'] == "image/bmp"))){
	
  $fname =time().".".substr(strrchr($_FILES['img1']['name'], "."), 1);
  $source_path="images/".$fname;
  
  $destination_path="images/thumb/".$fname;	
  $imgsize = getimagesize($source_path);		
  $new_height = 289;
  $new_width = 316;		
  $destimg=ImageCreateTrueColor($new_width,$new_height) or die("Problem In Creating image");						
  move_uploaded_file($_FILES['img1']['tmp_name'],"images/".$fname);
  
  if($_FILES['img1']['type'] == "image/JPG" || $_FILES['img1']['type'] == "image/JPEG" || $_FILES['img1']['type'] == "image/jpg" || $_FILES['img1']['type']=='image/jpeg' ){
    //for small                
    $srcimg=ImageCreateFromJPEG($source_path) or die("Problem In opening Source Image");
    ImageCopyResampled($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing");
    ImageJPEG($destimg,$destination_path,100) or die("Problem In saving");
  }else if($_FILES['img1']['type'] == "image/gif" || $_FILES['img1']['type'] == "image/GIF"){  
    //for small          
    $srcimg = imagecreatefromgif($source_path);
    ImageCopyResampled($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg));
    ImageJPEG($destimg,$destination_path,100) or die("Problem In saving");
  }else if($_FILES['img1']['type'] == "image/png" || $_FILES['img1']['type'] == "image/PNG"){ 
     //for small          
    $srcimg = imagecreatefrompng($source_path);
    ImageCopyResampled($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg));
    ImageJPEG($destimg,$destination_path,100) or die("Problem In saving"); 
  }
}


//Admin Login Img
	
	//@unlink("../banner-img/".$fname1);
	$agentid=$_REQUEST['agentid'];
	$title=$dbf->checkXssSqlInjection($_REQUEST['title']);
	$tag=$dbf->checkXssSqlInjection($_REQUEST['tag']);
	$desc=$dbf->checkXssSqlInjection($_REQUEST['desc']);
	$facebook=$dbf->checkXssSqlInjection($_REQUEST['facebook']);
	$tweeter=$dbf->checkXssSqlInjection($_REQUEST['tweeter']);
	$google=$dbf->checkXssSqlInjection($_REQUEST['google']);
	$instagram=$dbf->checkXssSqlInjection($_REQUEST['instagram']);
	$linkdin=$dbf->checkXssSqlInjection($_REQUEST['linkdin']);
	if($fname!=''){
    $fname = " , login_img = '$fname'";
  }else{
    $fname = "";
  }
  
	if($fname1!=''){
	$string="title='$title', tagline='$tag', description='$desc', logo='$fname1', facebook='$facebook', tweeter='$tweeter', google='$google', linkdin='$linkdin', instagram='$instagram',  created_date=NOW() $fname";
	}else{
		$string="title='$title', tagline='$tag', description='$desc', facebook='$facebook', tweeter='$tweeter', google='$google', linkdin='$linkdin', instagram='$instagram',  created_date=NOW() $fname";
		}
	$dbf->updateTable("settingg",$string,"settingg_id='$agentid'");
	
	header('Location:setting.php?editId='.$pagesId);exit;
}
?>
    <section class="content container-fluid">

      <div class="row">
      	<div class="col-xs-12">
        	<div class="box">
            		<div class="modal-content">
    <?php
    $page=$dbf->fetchSingle("settingg","*","settingg_id='1'");
    ?>
    
      <form action="" enctype="multipart/form-data" method="post">
        <div class="modal-header">
          <h4 class="modal-title">Website Data</h4>
        </div>
        <div class="modal-body">
          		<div class="form-group">
                  <input type="hidden" class="form-control" id="agentid" name="agentid" value="<?php echo $page['settingg_id'];?>">
                  <label for="exampleInputEmail1">Website Title</label>
                  <input type="text" class="form-control" id="title" name="title" value="<?php echo $page['title'];?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Tag Line</label>
                  <input type="text" class="form-control" id="tag" name="tag" value="<?php echo $page['tagline'];?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1"> Logo</label>
                  <input type="file" class="form-control" id="img" name="img" >
                </div>
                
                
         <?php if($page['logo']<>''){?>
        <img src="images/<?php echo $page['logo'];?> " width="100" >
        <?php }else{?>
         <img src="images/default.png" width="100" >
        <?php }?>

        <div class="form-group">
                  <label for="exampleInputEmail1"> Logoin Image</label>
                  <input type="file" class="form-control" id="img1" name="img1" >
                </div>
                <?php if($page['login_img']<>''){?>
        <img src="images/<?php echo $page['login_img'];?> " width="100" >
        <?php }else{?>
         <img src="images/default.png" width="100" >
        <?php }?>
				<div class="form-group">
                  <label for="">Description</label>
                  <textarea class="form-control" name="desc" id="desc"><?php echo $page['description'];?></textarea>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Facebook </label>
                  <input type="text" class="form-control" id="facebook" name="facebook" value="<?php echo $page['facebook'];?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Twitter </label>
                  <input type="text" class="form-control" id="tweeter" name="tweeter" value="<?php echo $page['tweeter'];?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Google Plus </label>
                  <input type="text" class="form-control" id="google" name="google" value="<?php echo $page['google'];?>">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Linkdin  </label>
                  <input type="text" class="form-control" id="google" name="linkdin" value="<?php echo $page['linkdin'];?>">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Instagram  </label>
                  <input type="text" class="form-control" id="instagram" name="instagram" value="<?php echo $page['instagram'];?>">
                </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit" id="submit" name="submit" value="editagent" >Submit</button> 
          
          
        </div>
        </form>
      </div>
            </div>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>
