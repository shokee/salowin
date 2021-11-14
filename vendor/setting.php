<?php include('header.php')?>
  <?php include('sidebar.php')?>


<?php 
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='edit_profile'){
	
//logo images	


	if($_FILES['img1']['name']!='' && (($_FILES['img1']['type'] == "image/gif") || ($_FILES['img1']['type'] == "image/jpeg") || ($_FILES['img1']['type'] == "image/pjpeg") || ($_FILES['img1']['type'] == "image/png") || ($_FILES['img1']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img1']['name'], "."), 1);
		$source_path1="../admin/images/vendor/".$fname1;
		
		$destination_path1="../admin/images/vendor/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img1']['tmp_name'],"../admin/images/vendor/".$fname1);
		
		if($_FILES['img1']['type'] == "image/JPG" || $_FILES['img1']['type'] == "image/JPEG" || $_FILES['img1']['type'] == "image/jpg" || $_FILES['img1']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['img1']['type'] == "image/gif" || $_FILES['img1']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['img1']['type'] == "image/png" || $_FILES['img1']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
	}
	
	//@unlink("../admin/images/vendor/".$fname1);
	

	
	
	$shopname=$dbf->checkXssSqlInjection($_REQUEST['shopname']);
	$address=$dbf->checkXssSqlInjection($_REQUEST['address']);
	$gst=$dbf->checkXssSqlInjection($_REQUEST['gst']);
	$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
	$city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
	
	if($fname1!=''){
	$string="shop_name ='$shopname', address1='$address', gst_no='$gst', country_id='$country_id', state_id='$state_id', city_id='$city_id', banner_image='$fname1', created_date=NOW()";
	}else{
	$string="shop_name ='$shopname', address1='$address', gst_no='$gst', country_id='$country_id', state_id='$state_id', city_id='$city_id',  created_date=NOW()";
		}
	$dbf->updateTable("user",$string,"id='$profileuserid'");
	
	header('Location:setting.php');
}

?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Shop Setting of
        <small><?php echo $profile['shop_name'];?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard </a></li>
        <li class="active">Setting</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box box-primary">
      <div class="box-header with-border">
              <h3 class="box-title">Shop Setting </h3>
            </div>
        <form action="" enctype="multipart/form-data" method="post">
        <div class="row">
        	<div class="col-sm-8">
            <div class="box-body">
            	<div class="form-group">
                  <label for="exampleInputEmail1">Shop Name </label>
                  <input type="text" class="form-control" name ="shopname" id="shopname" value="<?php echo $profile['shop_name'];?>">
                </div>
                
              
              <div class="form-group">
                <label>Product Sale Catagory</label>
                
              <select class="form-control select2" multiple="multiple" data-placeholder="Select a State" name="catagory_id[]" style="width: 100%;" disabled="">
          <?php  foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","","") as $CatName){?>
          <option value="<?=$CatName['product_catagory_1_id']?>"  <?php foreach($dbf->fetchOrder("vendor_catagory","vendor_id='$profileuserid'","","catagory_id","") as $ven_cate){ if($ven_cate['catagory_id']==$CatName['product_catagory_1_id']){echo "selected";}}?>><?=$CatName['product_catagory_1_name']?></option>
            <?php }?>
                </select>
              </div>
           



                 <div class="form-group">
                  <label for="exampleInputEmail1">Banner Image </label>
                  <input type="file" class="form-control" name ="img1" id="img"  accept="image/*">
                </div>
                
                 <div class="form-group">
                  <label for="">Address </label>
                  <input type="text" class="form-control" name ="address" id="address" value="<?php echo $profile['address1'];?>">
                </div>
                
                 <div class="form-group">
                  <label for="">Commition (%) </label>
                  <input type="text" class="form-control" name ="commition" readonly  value="<?php echo $profile['commition'];?>">
                </div>
                
                 <div class="form-group">
                  <label for="">GST </label>
                  <input type="text" class="form-control" name ="gst" id="gst" value="<?php echo $profile['gst_no'];?>">
                </div>
               
                
                <div class="form-group">
           <label>Select Country</label>
           <select class="form-control" name="country_id">
		   <?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $DirName){?>
           <option value="<?=$DirName['country_id']?>" <?php if($DirName['country_id']==$profile['Country_id']){ echo"selected";}?>><?=$DirName['country_name']?></option>
           <?php }?>
           </select>
           </div>
           
            <div class="form-group">
           <label>Select state</label>
           <select class="form-control" name="state_id">
		   <?php  foreach($dbf->fetchOrder("state","","state_id ASC","","") as $stateName){?>
           <option value="<?=$stateName['state_id']?>" <?php if($stateName['state_id']==$profile['state_id']){ echo"selected";}?>><?=$stateName['state_name']?></option>
           <?php }?>
           </select>
           </div>
           
            <div class="form-group">
           <label>Select city</label>
           <select class="form-control" name="city_id">
		   <?php  foreach($dbf->fetchOrder("city","","city_id ASC","","") as $stateName){?>
           <option value="<?=$stateName['city_id']?>" <?php if($stateName['city_id']==$profile['city_id']){ echo"selected";}?>><?=$stateName['city_name']?></option>
           <?php }?>
           </select>
           </div>
            <div class="form-group">
                  <label for="">Account No. </label>
                  <input type="text" class="form-control" name ="accno" readonly  value="<?php echo $profile['ac_num'];?>">
                </div>
                 <div class="form-group">
                  <label for="">IFSC code </label>
                  <input type="text" class="form-control" name ="ifsc"  readonly value="<?php echo $profile['ifsc_code'];?>">
                </div>
                
                <div class="uk-grid-small uk-child-width-expandd"  uk-grid>
                    <div>
                <?php if($profile['id_proof_fside'] != ''){?>
                 <img src="../admin/images/vendor/<?php echo $profile['id_proof_fside'];?>" width="100%">
                 <?php }else{?>
                  <img src="../admin/images/default.png" width="100%"  >
                 <?php }?>
                 </div>
                 <div>
                 <?php if($profile['shop_redg_proof'] != ''){?>
                  <img src="../admin/images/vendor/<?php echo $profile['shop_redg_proof'];?>" width="100%">
                   <?php }else{?>
                  <img src="../admin/images/default.png" width="100%"  >
                 <?php }?></div>
                 <div>
                 <?php if($profile['gst_copy'] != ''){?>
                   <img src="../admin/images/vendor/<?php echo $profile['gst_copy'];?>" width="100%">
                   <?php }else{?>
                  <img src="../admin/images/default.png" width="100%"  >
                 <?php }?>
                 </div>
                 <div>
                   <?php if($profile['passbook_copy'] != ''){?>
                    <img src="../admin/images/vendor/<?php echo $profile['passbook_copy'];?>" width="100%">
                    <?php }else{?>
                  <img src="../admin/images/default.png" width="100%"  >
                 <?php }?>
                 </div>
           </div>
             <button class="btn btn-primary" name="submit" type="submit" id="submit" value="edit_profile">Submit</button>     
            </div>	</div>
            <div class="col-sm-4">
            <p>Banner Image</p>
            <div style="border:solid 10px #CCC; width:90%; margin:5%"> 
           
            <?php if($profile['banner_image']<>''){?>
        <img src="../admin/images/vendor/<?php echo $profile['banner_image'];?>" width="100%">
        <?php }else{?>
         <img src="../admin/images/default.png" width="100%"  >
        <?php }?>
            <div class="clearfix"></div>
            </div>
            
            <p>Logo Image</p>
           <div style="border:solid 10px #CCC; width:90%; margin:5%"> 
           
            <?php if($profile['logo_image']<>''){?>
        <img src="../admin/images/vendor/<?php echo $profile['logo_image'];?>" width="100%">
        <?php }else{?>
         <img src="../admin/images/default.png" width="100%"  >
        <?php }?>
           
           
            
             <button type="button" class="btn btn-primary" style="width:100%" data-toggle="modal" data-target="#modal-default">
                Change Shop Logo
              </button>
            <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Change Shop Logo</h4>
              </div>
              <form action="" method="post" enctype="multipart/form-data">
              <div class="modal-body">
                <p>Select Logo</p>
                <input type="file" class="form-control" name="img" id="img" accept="image/*"/>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" id="submit" value="logo" class="btn btn-primary">Save changes</button>
              </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
            
            <div class="clearfix"></div>
            </div>
            
            
            </div>
        </div>
        </form>
      
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <?php 
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='logo'){
	
//logo images	


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
	
	//@unlink("../admin/images/vendor/".$fname1);
	
	$string="logo_image='$fname1', created_date=NOW()";
	
	$dbf->updateTable("user",$string,"id='$profileuserid'");
	
	header('Location:setting.php');
}

?>
  
   <?php include('footer.php')?>
