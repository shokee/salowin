<?php include('header.php')?>
  <?php include('sidebar.php')?>
  <?php 
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='edit_profile'){
	
	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="../admin/images/vendor/".$fname1;
		
		$destination_path1="../admin/images/vendor/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"../admin/images/vendor/".$fname1);
		
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
	$fullname=$dbf->checkXssSqlInjection($_REQUEST['full_name']);
	$email=$dbf->checkXssSqlInjection($_REQUEST['email']);
	$contactno=$dbf->checkXssSqlInjection($_REQUEST['contact_no']);
	$username=$dbf->checkXssSqlInjection($_REQUEST['user_name']);
	$password=base64_encode(base64_encode($_REQUEST['password']));
	
	if($fname1!=''){
	$string="full_name ='$fullname', email='$email', contact_no='$contactno', user_name='$username', password='$password', profile_image='$fname1', created_date=NOW()";
	}else{
		$string="full_name='$fullname', email='$email', contact_no='$contactno', user_name='$username',password='$password',  created_date=NOW()";
		}
	$dbf->updateTable("user",$string,"id='$profileuserid'");
	
	header('Location:profile.php?editId='.$bannerId);exit;
}

?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Profile
        <small>Update Profile</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Profile</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="" method="post" enctype="multipart/form-data">
              <div class="box-body">
              <div class="form-group">
                  <label for="exampleInputEmail1"> Name</label>
                  <input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo $profile['full_name'];?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Email </label>
                  <input type="email" class="form-control" id="email" name="email" value="<?php echo $profile['email'];?>" readonly>
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Contat No </label>
                  <input type="tel" class="form-control" id="contact_no" name="contact_no" value="<?php echo $profile['contact_no'];?>" readonly>
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">File input</label>
                  <input class="form-control"  type="file" name="img"  id="img" >
                
                <?php if($profile['profile_image']<>''){?>
        <img src="../admin/images/vendor/thumb/<?php echo $profile['profile_image'];?> "  width="100" height="100" >
        <?php }else{?>
         <img src="../admin/images/default.png?> " width="100" height="100"  >
        <?php }?>
                
                </div>
                
                <div class="form-group">
                  <label for="exampleInputPassword1">User Name</label>
                  <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $profile['user_name'];?>" readonly>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  <input type="text" class="form-control" name="password" id="password" value="<?php echo base64_decode(base64_decode($profile['password']));?>">
                </div>
                
                
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" name="submit" id="submit" value="edit_profile" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>
