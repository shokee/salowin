<?php include('header.php')?>
  <?php include('sidebar.php');
  $edit_id=isset($_REQUEST['edit_id'])?$dbf->checkSqlInjection($_REQUEST['edit_id']):0;
  $Delivery_boy=$dbf->fetchSingle("user",'*',"id='$edit_id'");
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Update Delivery Boy
      
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Update Delivery Boy</li>
      </ol>
    </section>
<?php  if(isset($_REQUEST['Submit']) && $_REQUEST['Submit']=='Submit'){
$user_id=$dbf->checkXssSqlInjection($_REQUEST['user_id']);
$name=$dbf->checkXssSqlInjection($_REQUEST['name']);
$email=$dbf->checkXssSqlInjection($_REQUEST['email']);
$password=base64_encode(base64_encode($dbf->checkXssSqlInjection($_REQUEST['password'])));
$altcontact=$dbf->checkXssSqlInjection($_REQUEST['altcontact']);
$contact= $dbf->checkXssSqlInjection($_REQUEST['contact']);
$addtess_prof=$dbf->checkXssSqlInjection($_REQUEST['addtess_prof']);
$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
$state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
$city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
$driving_licnum=$dbf->checkXssSqlInjection($_REQUEST['driving_licnum']);
$validateLic=$dbf->checkXssSqlInjection($_REQUEST['validateLic']);
$validateins=$dbf->checkXssSqlInjection($_REQUEST['validateins']);
$acname=$dbf->checkXssSqlInjection($_REQUEST['acname']);
$acnum=$dbf->checkXssSqlInjection($_REQUEST['acnum']);
$branch=$dbf->checkXssSqlInjection($_REQUEST['branch']);
$ifsc=$dbf->checkXssSqlInjection($_REQUEST['ifsc']);
            


$cntcontact=$dbf->countRows("user","contact_no='$contact' AND id!='$user_id'");
$cntemail=$dbf->countRows("user","email='$email' AND id!='$user_id'");
$cntDlnum=$dbf->countRows("user","dl_num='$driving_licnum' AND id!='$user_id'");
$cntAcnum=$dbf->countRows("user","dl_num='$acnum' AND id!='$user_id'");
if($cntcontact!=0){
	$error="Contact No. Already Exist";
	}elseif($cntemail!=0){
        $error="Email Id Already Exist";
	}elseif($cntDlnum!=0){
        $error="Driving Licence number Already Exist";
	}elseif($cntAcnum!=0){
        $error="Account No. Already Exist";
	}else{
		if($_FILES['fsideId']['name']!='' && (($_FILES['fsideId']['type'] == "image/gif") || ($_FILES['fsideId']['type'] == "image/jpeg") || ($_FILES['fsideId']['type'] == "image/pjpeg") || ($_FILES['fsideId']['type'] == "image/png") || ($_FILES['fsideId']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['fsideId']['name'], "."), 1);
		$source_path1="images/fsideId/".$fname1;
		
		$destination_path1="images/fsideId/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['fsideId']['tmp_name'],"images/fsideId/".$fname1);
		
		if($_FILES['fsideId']['type'] == "image/JPG" || $_FILES['fsideId']['type'] == "image/JPEG" || $_FILES['fsideId']['type'] == "image/jpg" || $_FILES['fsideId']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['fsideId']['type'] == "image/gif" || $_FILES['fsideId']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['fsideId']['type'] == "image/png" || $_FILES['fsideId']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
        }
        
    }

        if($_FILES['bsideId']['name']!='' && (($_FILES['bsideId']['type'] == "image/gif") || ($_FILES['bsideId']['type'] == "image/jpeg") || ($_FILES['bsideId']['type'] == "image/pjpeg") || ($_FILES['bsideId']['type'] == "image/png") || ($_FILES['bsideId']['type'] == "image/bmp"))){
	
            $fname2 =time().".".substr(strrchr($_FILES['bsideId']['name'], "."), 1);
            $source_path1="images/bsideId/".$fname2;
            
            $destination_path1="images/bsideId/thumb/".$fname2;	
            $imgsize1 = getimagesize($source_path1);		
            $new_height1 = 400;
            $new_width1 = 400;		
            $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
            move_uploaded_file($_FILES['bsideId']['tmp_name'],"images/bsideId/".$fname2);
            
            if($_FILES['bsideId']['type'] == "image/JPG" || $_FILES['bsideId']['type'] == "image/JPEG" || $_FILES['bsideId']['type'] == "image/jpg" || $_FILES['bsideId']['type']=='image/jpeg' ){
                //for small                
                $srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
                ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
                ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
            }else if($_FILES['bsideId']['type'] == "image/gif" || $_FILES['bsideId']['type'] == "image/GIF"){  
                //for small          
                $srcimg1 = imagecreatefromgif($source_path1);
                ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
                ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
            }else if($_FILES['bsideId']['type'] == "image/png" || $_FILES['bsideId']['type'] == "image/PNG"){ 
                 //for small          
                $srcimg1 = imagecreatefrompng($source_path1);
                ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
                ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
            }
        }
            if($_FILES['frc']['name']!='' && (($_FILES['frc']['type'] == "image/gif") || ($_FILES['frc']['type'] == "image/jpeg") || ($_FILES['frc']['type'] == "image/pjpeg") || ($_FILES['frc']['type'] == "image/png") || ($_FILES['frc']['type'] == "image/bmp"))){
	
                $fname3 =time().".".substr(strrchr($_FILES['frc']['name'], "."), 1);
                $source_path1="images/frc/".$fname3;
                
                $destination_path1="images/frc/thumb/".$fname3;	
                $imgsize1 = getimagesize($source_path1);		
                $new_height1 = 400;
                $new_width1 = 400;		
                $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
                move_uploaded_file($_FILES['frc']['tmp_name'],"images/frc/".$fname3);
                
                if($_FILES['frc']['type'] == "image/JPG" || $_FILES['frc']['type'] == "image/JPEG" || $_FILES['frc']['type'] == "image/jpg" || $_FILES['frc']['type']=='image/jpeg' ){
                    //for small                
                    $srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
                    ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
                    ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
                }else if($_FILES['frc']['type'] == "image/gif" || $_FILES['frc']['type'] == "image/GIF"){  
                    //for small          
                    $srcimg1 = imagecreatefromgif($source_path1);
                    ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
                    ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
                }else if($_FILES['frc']['type'] == "image/png" || $_FILES['frc']['type'] == "image/PNG"){ 
                     //for small          
                    $srcimg1 = imagecreatefrompng($source_path1);
                    ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
                    ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
                }
                
            }
                if($_FILES['brc']['name']!='' && (($_FILES['brc']['type'] == "image/gif") || ($_FILES['brc']['type'] == "image/jpeg") || ($_FILES['brc']['type'] == "image/pjpeg") || ($_FILES['brc']['type'] == "image/png") || ($_FILES['brc']['type'] == "image/bmp"))){
	
                    $fname4 =time().".".substr(strrchr($_FILES['brc']['name'], "."), 1);
                    $source_path1="images/brc/".$fname4;
                    
                    $destination_path1="images/brc/thumb/".$fname4;	
                    $imgsize1 = getimagesize($source_path1);		
                    $new_height1 = 400;
                    $new_width1 = 400;		
                    $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
                    move_uploaded_file($_FILES['brc']['tmp_name'],"images/brc/".$fname4);
                    
                    if($_FILES['brc']['type'] == "image/JPG" || $_FILES['brc']['type'] == "image/JPEG" || $_FILES['brc']['type'] == "image/jpg" || $_FILES['brc']['type']=='image/jpeg' ){
                        //for small                
                        $srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
                        ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
                        ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
                    }else if($_FILES['brc']['type'] == "image/gif" || $_FILES['brc']['type'] == "image/GIF"){  
                        //for small          
                        $srcimg1 = imagecreatefromgif($source_path1);
                        ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
                        ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
                    }else if($_FILES['brc']['type'] == "image/png" || $_FILES['brc']['type'] == "image/PNG"){ 
                         //for small          
                        $srcimg1 = imagecreatefrompng($source_path1);
                        ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
                        ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
                    }

                }
                    
                if($_FILES['doc_ins']['name']!='' && (($_FILES['doc_ins']['type'] == "image/gif") || ($_FILES['doc_ins']['type'] == "image/jpeg") || ($_FILES['doc_ins']['type'] == "image/pjpeg") || ($_FILES['doc_ins']['type'] == "image/png") || ($_FILES['doc_ins']['type'] == "image/bmp"))){
	
                    $fname5 =time().".".substr(strrchr($_FILES['doc_ins']['name'], "."), 1);
                    $source_path1="images/doc_ins/".$fname5;
                    
                    $destination_path1="images/doc_ins/thumb/".$fname5;	
                    $imgsize1 = getimagesize($source_path1);		
                    $new_height1 = 400;
                    $new_width1 = 400;		
                    $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
                    move_uploaded_file($_FILES['doc_ins']['tmp_name'],"images/doc_ins/".$fname5);
                    
                    if($_FILES['doc_ins']['type'] == "image/JPG" || $_FILES['doc_ins']['type'] == "image/JPEG" || $_FILES['doc_ins']['type'] == "image/jpg" || $_FILES['brdoc_insc']['type']=='image/jpeg' ){
                        //for small                
                        $srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
                        ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
                        ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
                    }else if($_FILES['doc_ins']['type'] == "image/gif" || $_FILES['doc_ins']['type'] == "image/GIF"){  
                        //for small          
                        $srcimg1 = imagecreatefromgif($source_path1);
                        ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
                        ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
                    }else if($_FILES['doc_ins']['type'] == "image/png" || $_FILES['doc_ins']['type'] == "image/PNG"){ 
                         //for small          
                        $srcimg1 = imagecreatefrompng($source_path1);
                        ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
                        ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
                    }
                 
                }


                if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
                    $fname6 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
                    $source_path6="images/dboy/".$fname6;
                    
                    $destination_path6="images/dboy/thumb/".$fname6;	
                    $imgsize6 = getimagesize($source_path6);		
                    $new_height6 = 400;
                    $new_width6 = 400;		
                    $destimg6=ImageCreateTrueColor($new_width6,$new_height6) or die("Problem In Creating image");						
                    move_uploaded_file($_FILES['img']['tmp_name'],"images/dboy/".$fname6);
                    
                    if($_FILES['img']['type'] == "image/JPG" || $_FILES['img']['type'] == "image/JPEG" || $_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type']=='image/jpeg' ){
                        //for small                
                        $srcimg6=ImageCreateFromJPEG($source_path6) or die("Problem In opening Source Image");
                        ImageCopyResampled($destimg6,$srcimg6,0,0,0,0,$new_width6,$new_height6,ImageSX($srcimg6),ImageSY($srcimg6)) or die("Problem In resizing");
                        ImageJPEG($destimg6,$destination_path6,100) or die("Problem In saving");
                    }else if($_FILES['img']['type'] == "image/gif" || $_FILES['img']['type'] == "image/GIF"){  
                        //for small          
                        $srcimg6 = imagecreatefromgif($source_path6);
                        ImageCopyResampled($destimg6,$srcimg6,0,0,0,0,$new_width6,$new_height6,ImageSX($srcimg6),ImageSY($srcimg6));
                        ImageJPEG($destimg6,$destination_path6,100) or die("Problem In saving");
                    }else if($_FILES['img']['type'] == "image/png" || $_FILES['img']['type'] == "image/PNG"){ 
                         //for small          
                        $srcimg6 = imagecreatefrompng($source_path6);
                        ImageCopyResampled($destimg6,$srcimg6,0,0,0,0,$new_width6,$new_height6,ImageSX($srcimg6),ImageSY($srcimg6));
                        ImageJPEG($destimg6,$destination_path6,100) or die("Problem In saving"); 
                    }
            
                }
                $resBanner=$dbf->fetchSingle("user","*","id='$user_id'");

               
                
                if($fname1!=''){
                    @unlink("images/fsideId/".$resBanner['id_proof_fside']);
                    @unlink("images/fsideId/thumb/".$resBanner['id_proof_fside']);
                    
                    $fname1 = " id_proof_fside='$fname1', ";
                }else{
                    $fname1 ="";
                }

                if($fname2!=''){
                    @unlink("images/bsideId/".$resBanner['id_proof_bside']);
                    @unlink("images/bsideId/thumb/".$resBanner['id_proof_bside']);

                    $fname2 =" id_proof_bside='$fname2', ";
                }else{
                    $fname2="";
                }

                if($fname3!=''){
                     
                @unlink("images/frc/".$resBanner['doc_rc']);
                @unlink("images/frc/thumb/".$resBanner['doc_rc']);

                    $fname3= "doc_rc='$fname3',";
                }else{
                    $fname3="";
                }
                
               if($fname4!=''){
                   
                @unlink("images/brc/".$resBanner['doc_lic']);
                @unlink("images/brc/thumb/".$resBanner['doc_lic']);
            
                $fname4 ="doc_lic='$fname4',";
               }else{
                $fname4="";
               }
               if($fname5!=''){
                $fname5 ="doc_inc='$fname5',";
                @unlink("images/doc_ins/".$resBanner['doc_inc']);
                @unlink("images/doc_ins/thumb/".$resBanner['doc_inc']);
               }else{
                $fname5="";
               }

               if($fname6!=''){
                $fname6 ="profile_image='$fname6',";
                @unlink("images/dboy/".$resBanner['profile_image']);
                @unlink("images/dboy/thumb/".$resBanner['profile_image']);
               }else{
                $fname6="";
               }
                $string="full_name='$name',password='$password',email='$email',contact_no='$contact',alter_cnum='$altcontact',user_type='5',country_id='$country_id',state_id='$state_id',city_id='$city_id',addr_prof_type='$addtess_prof',$fname1 $fname2 dl_num='$driving_licnum', $fname3  $fname4 valid_lic='$validateLic', $fname5 valid_inc='$validateins',ac_holder_name='$acname',ac_num='$acnum',branch='$branch',ifsc_code='$ifsc',$fname6 created_date=NOW()";
                $dbf->updateTable("user",$string,"id='$user_id'");
                header("Location:deliver_boy_edit.php?msg=succes&edit_id=".$user_id);exit;

	
 }
}?>
    <!-- Main content -->
    
    
    <section class="content container-fluid">
 
      
      <!-- Modal content-->
      <div class="modal-content">
      
      <form action="" enctype="multipart/form-data" method="post" id="AddVendor">
      
      <input type="hidden"  name="user_id" value="<?= $edit_id?>">
        <div class="modal-header">
        
        <div class="row">
      <?php if(isset($error) && $error!=''){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p><?= $error?>t</p>
</div>
<?php }?>
          
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='succes'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p> Delivery Updated Boy Add Successfully</p>
</div>
<?php }?>
        </div>
        <div class="modal-body">
        <div class="row">
        	<div class="col-sm-6">
           	 <div class="form-group">
                  <label >Name</label>
                  <input type="text" class="form-control" id="name" name="name"  placeholder="Enter full name" required autocomplete="off"  value="<?= $Delivery_boy['full_name']?>">
                </div>
             </div>
            <div class="col-sm-6">
            <div class="form-group">
                  <label >Email address</label>
                  <input type="email" class="form-control"  id="email" name="email" placeholder="Enter email"  required autocomplete="off"  value="<?= $Delivery_boy['email']?>">
                </div>
             </div>
             <div class="col-sm-6"><div class="form-group">
                  <label >Password</label>
                  <input type="text" class="form-control" id="password" name="password" placeholder="Enter Password"  required autocomplete="off" value="<?= base64_decode(base64_decode($Delivery_boy['password']))?>">
                </div></div>
            <div class="col-sm-6">
            <div class="form-group">
                  <label >Contact No</label>
                  <input type="tel" class="form-control" id="contat" name="contact" title="Enter Contact Number" placeholder="Enter Contact Number" pattern="[1-9]{1}[0-9]{9}"  required autocomplete="off"  value="<?= $Delivery_boy['contact_no']?>">
                </div>
            </div>
            <div class="col-sm-6">
            <div class="form-group">
                  <label >Alternative Contact No</label>
                  <input type="tel" class="form-control" id="altcontat" name="altcontact" title="Enter Alternative Contact Number" placeholder="Enter Alternative Contact Number" pattern="[1-9]{1}[0-9]{9}" required autocomplete="off" value="<?= $Delivery_boy['alter_cnum']?>">
                </div>
            </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="addtess_prof">Select Addres Proof Type</label>
                            <select name="addtess_prof" id="addtess_prof" class="form-control" required>
                                <option value="">--Select Address Proof Type--</option>
                                <option value="1" <?php if($Delivery_boy['addr_prof_type']=='1'){ echo "selected";}?>>Adhar card </option>
                                <option value="2" <?php if($Delivery_boy['addr_prof_type']=='2'){ echo "selected";}?>>Voter Id card</option>
                            </select>
                        
                    </div>
                </div>

            <div class="col-sm-6">
           		 <div class="form-group">
                  <label >Id Proof Front Side Image (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                  <input type="file" class="form-control chekPhotoSiz1" id="fsideId" name="fsideId"   accept="image/*"  >
                   <span id="ErrorMsg1" class="text-danger"></span>
                  <img src="images/fsideId/<?php echo $Delivery_boy['id_proof_fside'];?>" width="30px" height="30px">
                  
                </div>
                </div>
                <div class="col-sm-6">
           		 <div class="form-group">
                  <label >Id Proof Back Side Image (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                  <input type="file" class="form-control chekPhotoSiz2" id="bsideId" name="bsideId"   accept="image/*"  value="<?= $_POST['bsideId']?>">
                   <span id="ErrorMsg2" class="text-danger"></span>
                  <img src="images/bsideId/<?php echo $Delivery_boy['id_proof_bside'];?>" width="30px" height="30px">
                </div>
                </div>
                <div class="col-sm-6"><div class="form-group">
                <label class="" for="inlineFormCustomSelect">Select Country</label>
      		    <select class="form-control" name="country_id" onChange=" GetState(this.value)" required>
                <option value="" >--Select Country--</option>
    			<?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
    			<option value="<?=$countryName['country_id']?>" <?php if($countryName['country_id']==$Delivery_boy['country_id']){ echo"selected";}?>><?=$countryName['country_name']?></option>
   			    <?php }?>
    			</select>
                </div></div>
            <div class="col-sm-6"><div class="form-group">
                <label class="" >Select State</label>
                <select class="form-control" name="state_id" id="stateres" onChange="GetCity(this.value)" required >
		   <?php  foreach($dbf->fetchOrder("state","Country_id='$Delivery_boy[country_id]'","state_id ASC","","") as $stateName){?>
           <option value="<?=$stateName['state_id']?>" <?php if($stateName['state_id']==$Delivery_boy['state_id']){ echo"selected";}?>><?=$stateName['state_name']?></option>
           <?php }?>
           </select>
                </div></div>
            <div class="col-sm-6">
            	<div class="form-group">
                 <label class="" for="inlineFormCustomSelect">Select City</label>
     			 <select class="form-control" name="city_id" id="cityres" required>
                  <?php  foreach($dbf->fetchOrder("city","state_id='$Delivery_boy[state_id]'","city_id ASC","","") as $stateName){?>
                        <option value="<?=$stateName['city_id']?>" <?php if($stateName['city_id']==$Delivery_boy['city_id']){ echo"selected";}?>><?=$stateName['city_name']?></option>
                        <?php }?>
    			</select>
                </div>
            </div>
            <div class="col-sm-6"><div class="form-group">
                  <label >Driving Licence number </label>
                  <input type="text" class="form-control" id="driving_licnum" name="driving_licnum" placeholder="Enter Driving Licence number" required autocomplete="off" value="<?= $Delivery_boy['dl_num']?>">
                </div></div>
            <div class="col-sm-6"><div class="form-group">
                  <label >Document of rc In Image (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                  <input type="file" class="form-control chekPhotoSiz3" id="frc" name="frc"   accept="image/*" >
                   <span id="ErrorMsg3" class="text-danger"></span>
                  <img src="images/frc/<?php echo $Delivery_boy['doc_rc'];?>" width="30px" height="30px">
                </div></div>
                <div class="col-sm-6"><div class="form-group">
                  <label >Document of licence In Image (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                  <input type="file" class="form-control chekPhotoSiz4" id="brc" name="brc"   accept="image/*"  >
                   <span id="ErrorMsg4" class="text-danger"></span>
                  <img src="images/brc/<?php echo $Delivery_boy['doc_lic'];?>" width="30px" height="30px">
                </div></div>
            <div class="col-sm-6">
            	<div class="form-group">
                <label>Valid Date Of Licence </label>
                    <input type="date" class="form-control" name="validateLic" required min="<?= date('Y-m-d')?>" value="<?= $Delivery_boy['valid_lic']?>">
              </div>
            </div>
            <div class="col-sm-6"><div class="form-group">
                  <label >Document of insurance  In Image (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                  <input type="file" class="form-control chekPhotoSiz5" id="doc_ins" name="doc_ins"   accept="image/*"  >
                   <span id="ErrorMsg5" class="text-danger"></span>
                  <img src="images/doc_ins/<?php echo $Delivery_boy['doc_inc'];?>" width="30px" height="30px">
                </div></div>
                <div class="col-sm-6">
            	<div class="form-group">
                <label>Valid Date Of Insurance </label>
                    <input type="date" class="form-control" name="validateins" required min="<?= date('Y-m-d')?>" value="<?= $Delivery_boy['valid_inc']?>">
              </div>
            </div>
      
                <div class="col-sm-6">
            	<div class="form-group">
                <label>Account Beneficiary Name </label>
                    <input type="text" class="form-control" name="acname" required autocomplete="off" placeholder="Enter Account Beneficiary Name" value="<?= $Delivery_boy['ac_holder_name']?>">
              </div>
            </div>
            <div class="col-sm-6">
            	<div class="form-group">
                <label>Account No </label>
                    <input type="number" class="form-control" name="acnum" required autocomplete="off" placeholder="Enter Account No." value="<?= $Delivery_boy['ac_num']?>">
              </div>
            </div>
            <div class="col-sm-6">
            	<div class="form-group">
                <label>Branch </label>
                    <input type="text" class="form-control" name="branch" required autocomplete="off" placeholder="Enter Branch" value="<?= $Delivery_boy['branch']?>">
              </div>
            </div>
            <div class="col-sm-6">
            	<div class="form-group">
                <label>IFSC CODE </label>
                    <input type="text" class="form-control" name="ifsc" required autocomplete="off" placeholder="Enter IFSC CODE" value="<?= $Delivery_boy['ifsc_code']?>">
              </div>
            </div>

            <div class="col-sm-6">
            <div class="form-group">
                <label>Profile (<span  class="text-danger chekPhotoSiz6">Image Size Must Be Less Than 1 Mb</span>)</label>
                    <input type="file" class="form-control" name="img"  autocomplete="off" accept="image/*" >
                     <span id="ErrorMsg6" class="text-danger"></span>
                    <img src="images/dboy/<?php echo $Delivery_boy['profile_image'];?>" width="30px" height="30px">
              </div>
            </div>
            
        </div>
                
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit"  name="Submit" value="Submit" id="Submit">Update</button> 
          
         
        </div>
        <input type="hidden" id="ValidChek1" value="1">
        <input type="hidden" id="ValidChek2" value="1">
        <input type="hidden" id="ValidChek3" value="1">
        <input type="hidden" id="ValidChek4" value="1">
        <input type="hidden" id="ValidChek5" value="1">
        <input type="hidden" id="ValidChek6" value="1">
        </form>
      </div>
      
    </div>
 

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>
<script>

function MainImfValid(){
   

    var ValidChek1 = $('#ValidChek1').val();
    var ValidChek2 = $('#ValidChek2').val();
    var ValidChek3 = $('#ValidChek3').val();
    var ValidChek4 = $('#ValidChek4').val();
    var ValidChek5 = $('#ValidChek5').val();
    var ValidChek6 = $('#ValidChek6').val();
    if(ValidChek1==1 && ValidChek2==1 && ValidChek3==1 && ValidChek4==1 && ValidChek5==1 && ValidChek6==1){
        $('#Submit').prop('disabled', false); 
    }else{
        $('#Submit').prop('disabled', true);
    }
}
     $(".chekPhotoSiz1").change(function(){
         
         var bytes = this.files[0].size;
         var converted = bytes / (1024*1024);
            if(converted>1){
                 $('#ValidChek1').val(0);
                MainImfValid();
                $('#ErrorMsg1').text('Your Images Size Greater Than 1 Mb,');
            }else{
                 $('#ErrorMsg1').text('');
                 $('#ValidChek1').val(1);
                MainImfValid()
            }
});
     $(".chekPhotoSiz2").change(function(){
         
         var bytes = this.files[0].size;
         var converted = bytes / (1024*1024);
            if(converted>1){
                 $('#ValidChek2').val(0);
               MainImfValid()
                $('#ErrorMsg2').text('Your Images Size Greater Than 1 Mb,');
            }else{
                 $('#ValidChek2').val(1);
                 $('#ErrorMsg2').text('');
                MainImfValid()
            }
});
    
         $(".chekPhotoSiz3").change(function(){
         
         var bytes = this.files[0].size;
         var converted = bytes / (1024*1024);
            if(converted>1){
                 $('#ValidChek3').val(0);
               MainImfValid()
                $('#ErrorMsg3').text('Your Images Size Greater Than 1 Mb,');
            }else{
                $('#ErrorMsg3').text('');
                 $('#ValidChek3').val(1);
              MainImfValid()
            }
});
     $(".chekPhotoSiz4").change(function(){
         
         var bytes = this.files[0].size;
         var converted = bytes / (1024*1024);
            if(converted>1){
                 $('#ValidChek1').val(0);
                MainImfValid()
                $('#ErrorMsg4').text('Your Images Size Greater Than 1 Mb,');
            }else{
                $('#ErrorMsg4').text('');
                 $('#ValidChek4').val(1);
                MainImfValid()
            }
});
     $(".chekPhotoSiz5").change(function(){
         
         var bytes = this.files[0].size;
         var converted = bytes / (1024*1024);
            if(converted>1){
                 $('#ValidChek5').val(0);
               MainImfValid()
                $('#ErrorMsg5').text('Your Images Size Greater Than 1 Mb,');
            }else{
                $('#ErrorMsg5').text('');
                 $('#ValidChek5').val(1);
               MainImfValid() 
            }
});
     $(".chekPhotoSiz6").change(function(){
         
         var bytes = this.files[0].size;
         var converted = bytes / (1024*1024);
            if(converted>1){
                 $('#ValidChek6').val(0);
               MainImfValid();
                $('#ErrorMsg6').text('Your Images Size Greater Than 1 Mb,');
            }else{
                $('#ErrorMsg6').text('');
                 $('#ValidChek6').val(1);
                MainImfValid(); 
            }
});
</script>