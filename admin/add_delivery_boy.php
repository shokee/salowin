<?php include('header.php')?>
  <?php include('sidebar.php')?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Add Delivery Boy
      
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Add Delivery Boy</li>
      </ol>
    </section>
<?php  if(isset($_REQUEST['Submit']) && $_REQUEST['Submit']=='Submit'){
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
            


$cntcontact=$dbf->countRows("user","contact_no='$contact'");
$cntemail=$dbf->countRows("user","email='$email'");
$cntDlnum=$dbf->countRows("user","dl_num='$driving_licnum'");
$cntAcnum=$dbf->countRows("user","dl_num='$acnum'");
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
        
        

        if($_FILES['bsideId']['name']!='' && (($_FILES['bsideId']['type'] == "image/gif") || ($_FILES['bsideId']['type'] == "image/jpeg") || ($_FILES['bsideId']['type'] == "image/pjpeg") || ($_FILES['bsideId']['type'] == "image/png") || ($_FILES['bsideId']['type'] == "image/bmp"))){
	
            $fname2 =time().".".substr(strrchr($_FILES['bsideId']['name'], "."), 1);
            $source_path2="images/bsideId/".$fname2;
            
            $destination_path2="images/bsideId/thumb/".$fname2;	
            $imgsize2 = getimagesize($source_path2);		
            $new_height2 = 400;
            $new_width2 = 400;		
            $destimg2=ImageCreateTrueColor($new_width2,$new_height2) or die("Problem In Creating image");						
            move_uploaded_file($_FILES['bsideId']['tmp_name'],"images/bsideId/".$fname2);
            
            if($_FILES['bsideId']['type'] == "image/JPG" || $_FILES['bsideId']['type'] == "image/JPEG" || $_FILES['bsideId']['type'] == "image/jpg" || $_FILES['bsideId']['type']=='image/jpeg' ){
                //for small                
                $srcimg2=ImageCreateFromJPEG($source_path2) or die("Problem In opening Source Image");
                ImageCopyResampled($destimg2,$srcimg2,0,0,0,0,$new_width2,$new_height2,ImageSX($srcimg2),ImageSY($srcimg2)) or die("Problem In resizing");
                ImageJPEG($destimg2,$destination_path2,100) or die("Problem In saving");
            }else if($_FILES['bsideId']['type'] == "image/gif" || $_FILES['bsideId']['type'] == "image/GIF"){  
                //for small          
                $srcimg2 = imagecreatefromgif($source_path1);
                ImageCopyResampled($destimg2,$srcimg2,0,0,0,0,$new_width2,$new_height2,ImageSX($srcimg2),ImageSY($srcimg2));
                ImageJPEG($destimg2,$destination_path2,100) or die("Problem In saving");
            }else if($_FILES['bsideId']['type'] == "image/png" || $_FILES['bsideId']['type'] == "image/PNG"){ 
                 //for small          
                $srcimg2 = imagecreatefrompng($source_path2);
                ImageCopyResampled($destimg2,$srcimg2,0,0,0,0,$new_width2,$new_height2,ImageSX($srcimg2),ImageSY($srcimg2));
                ImageJPEG($destimg2,$destination_path2,100) or die("Problem In saving"); 
            }

            if($_FILES['frc']['name']!='' && (($_FILES['frc']['type'] == "image/gif") || ($_FILES['frc']['type'] == "image/jpeg") || ($_FILES['frc']['type'] == "image/pjpeg") || ($_FILES['frc']['type'] == "image/png") || ($_FILES['frc']['type'] == "image/bmp"))){
	
                $fname3 =time().".".substr(strrchr($_FILES['frc']['name'], "."), 1);
                $source_path3="images/frc/".$fname3;
                
                $destination_path3="images/frc/thumb/".$fname3;	
                $imgsize3= getimagesize($source_path3);		
                $new_height3 = 400;
                $new_width3 = 400;		
                $destimg3=ImageCreateTrueColor($new_width3,$new_height3) or die("Problem In Creating image");						
                move_uploaded_file($_FILES['frc']['tmp_name'],"images/frc/".$fname3);
                
                if($_FILES['frc']['type'] == "image/JPG" || $_FILES['frc']['type'] == "image/JPEG" || $_FILES['frc']['type'] == "image/jpg" || $_FILES['frc']['type']=='image/jpeg' ){
                    //for small                
                    $srcimg3=ImageCreateFromJPEG($source_path3) or die("Problem In opening Source Image");
                    ImageCopyResampled($destimg3,$srcimg3,0,0,0,0,$new_width3,$new_height3,ImageSX($srcimg3),ImageSY($srcimg3)) or die("Problem In resizing");
                    ImageJPEG($destimg3,$destination_path3,100) or die("Problem In saving");
                }else if($_FILES['frc']['type'] == "image/gif" || $_FILES['frc']['type'] == "image/GIF"){  
                    //for small          
                    $srcimg3 = imagecreatefromgif($source_path3);
                    ImageCopyResampled($destimg3,$srcimg3,0,0,0,0,$new_width3,$new_height3,ImageSX($srcimg3),ImageSY($srcimg3));
                    ImageJPEG($destimg3,$destination_path3,100) or die("Problem In saving");
                }else if($_FILES['frc']['type'] == "image/png" || $_FILES['frc']['type'] == "image/PNG"){ 
                     //for small          
                    $srcimg3 = imagecreatefrompng($source_path3);
                    ImageCopyResampled($destimg3,$srcimg3,0,0,0,0,$new_width3,$new_height3,ImageSX($srcimg3),ImageSY($srcimg3));
                    ImageJPEG($destimg3,$destination_path3,100) or die("Problem In saving"); 
                }
                

                if($_FILES['brc']['name']!='' && (($_FILES['brc']['type'] == "image/gif") || ($_FILES['brc']['type'] == "image/jpeg") || ($_FILES['brc']['type'] == "image/pjpeg") || ($_FILES['brc']['type'] == "image/png") || ($_FILES['brc']['type'] == "image/bmp"))){
	
                    $fname4 =time().".".substr(strrchr($_FILES['brc']['name'], "."), 1);
                    $source_path4="images/brc/".$fname4;
                    
                    $destination_path4="images/brc/thumb/".$fname4;	
                    $imgsize4 = getimagesize($source_path4);		
                    $new_height4 = 400;
                    $new_width4 = 400;		
                    $destimg4=ImageCreateTrueColor($new_width4,$new_height4) or die("Problem In Creating image");						
                    move_uploaded_file($_FILES['brc']['tmp_name'],"images/brc/".$fname4);
                    
                    if($_FILES['brc']['type'] == "image/JPG" || $_FILES['brc']['type'] == "image/JPEG" || $_FILES['brc']['type'] == "image/jpg" || $_FILES['brc']['type']=='image/jpeg' ){
                        //for small                
                        $srcimg4=ImageCreateFromJPEG($source_path4) or die("Problem In opening Source Image");
                        ImageCopyResampled($destimg4,$srcimg4,0,0,0,0,$new_width4,$new_height4,ImageSX($srcimg4),ImageSY($srcimg4)) or die("Problem In resizing");
                        ImageJPEG($destimg4,$destination_path4,100) or die("Problem In saving");
                    }else if($_FILES['brc']['type'] == "image/gif" || $_FILES['brc']['type'] == "image/GIF"){  
                        //for small          
                        $srcimg4 = imagecreatefromgif($source_path4);
                        ImageCopyResampled($destimg4,$srcimg4,0,0,0,0,$new_width4,$new_height4,ImageSX($srcimg4),ImageSY($srcimg4));
                        ImageJPEG($destimg4,$destination_path4,100) or die("Problem In saving");
                    }else if($_FILES['brc']['type'] == "image/png" || $_FILES['brc']['type'] == "image/PNG"){ 
                         //for small          
                        $srcimg4 = imagecreatefrompng($source_path4);
                        ImageCopyResampled($destimg4,$srcimg4,0,0,0,0,$new_width4,$new_height4,ImageSX($srcimg4),ImageSY($srcimg4));
                        ImageJPEG($destimg4,$destination_path4,100) or die("Problem In saving"); 
                    }

                
                    
                if($_FILES['doc_ins']['name']!='' && (($_FILES['doc_ins']['type'] == "image/gif") || ($_FILES['doc_ins']['type'] == "image/jpeg") || ($_FILES['doc_ins']['type'] == "image/pjpeg") || ($_FILES['doc_ins']['type'] == "image/png") || ($_FILES['doc_ins']['type'] == "image/bmp"))){
	
                    $fname5 =time().".".substr(strrchr($_FILES['doc_ins']['name'], "."), 1);
                    $source_path5="images/doc_ins/".$fname5;
                    
                    $destination_path5="images/doc_ins/thumb/".$fname5;	
                    $imgsize5 = getimagesize($source_path5);		
                    $new_height5 = 400;
                    $new_width5 = 400;		
                    $destimg5=ImageCreateTrueColor($new_width5,$new_height5) or die("Problem In Creating image");						
                    move_uploaded_file($_FILES['doc_ins']['tmp_name'],"images/doc_ins/".$fname5);
                    
                    if($_FILES['doc_ins']['type'] == "image/JPG" || $_FILES['doc_ins']['type'] == "image/JPEG" || $_FILES['doc_ins']['type'] == "image/jpg" || $_FILES['doc_ins']['type']=='image/jpeg' ){
                        //for small                
                        $srcimg5=ImageCreateFromJPEG($source_path5) or die("Problem In opening Source Image");
                        ImageCopyResampled($destimg5,$srcimg5,0,0,0,0,$new_width5,$new_height5,ImageSX($srcimg5),ImageSY($srcimg5)) or die("Problem In resizing");
                        ImageJPEG($destimg5,$destination_path5,100) or die("Problem In saving");
                    }else if($_FILES['doc_ins']['type'] == "image/gif" || $_FILES['doc_ins']['type'] == "image/GIF"){  
                        //for small          
                        $srcimg5 = imagecreatefromgif($source_path5);
                        ImageCopyResampled($destimg5,$srcimg5,0,0,0,0,$new_width5,$new_height5,ImageSX($srcimg5),ImageSY($srcimg5));
                        ImageJPEG($destimg5,$destination_path5,100) or die("Problem In saving");
                    }else if($_FILES['doc_ins']['type'] == "image/png" || $_FILES['doc_ins']['type'] == "image/PNG"){ 
                         //for small          
                        $srcimg5 = imagecreatefrompng($source_path5);
                        ImageCopyResampled($destimg5,$srcimg5,0,0,0,0,$new_width5,$new_height5,ImageSX($srcimg5),ImageSY($srcimg5));
                        ImageJPEG($destimg5,$destination_path5,100) or die("Problem In saving"); 
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
                        $string="full_name='$name',password='$password',email='$email',contact_no='$contact',alter_cnum='$altcontact',user_type='5',country_id='$country_id',state_id='$state_id',city_id='$city_id',status='0',addr_prof_type='$addtess_prof',id_proof_fside='$fname1',id_proof_bside='$fname2',dl_num='$driving_licnum',doc_rc='$fname3',doc_lic='$fname4',valid_lic='$validateLic',doc_inc='$fname5',valid_inc='$validateins',ac_holder_name='$acname',ac_num='$acnum',branch='$branch',ifsc_code='$ifsc',profile_image='$fname6',created_date=NOW()";
                
                            $dbf->insertSet("user",$string);
                            header("Location:add_delivery_boy.php?msg=succes");exit;
                    }else{
                        $error="Profile Image Not Correct Format";
                    }
                }else{
                    $error="Document of insurance In Image Not Correct Format";
                }
    
                }else{
                    $error="Document of licence In Image Not Correct Format";
                }
            }else{
                $error="Document of rc In Image Not Correct Format";
            }
        }else{
            $error="Id Proof Back Side Image Not Correct Format";
        } 

	}else{
        $error="Id Proof Front Side Image Not Correct Format";
    }

 }
}?>
    <!-- Main content -->
    
    
    <section class="content container-fluid">
 
      
      <!-- Modal content-->
      <div class="modal-content">
      
      <form action="" enctype="multipart/form-data" method="post" id="AddVendor">
      
      <input type="hidden" id="Submits" name="Submits" value="addagent">
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
<p>New Delivery Boy Add Successfully</p>
</div>
<?php }?>

        </div>
        <div class="modal-body">
        <div class="row">
        	<div class="col-sm-6">
           	 <div class="form-group">
                  <label >Name</label>
                  <input type="text" class="form-control" id="name" name="name"  placeholder="Enter full name" required autocomplete="off"  value="<?= $_POST['name']?>">
                </div>
             </div>
            <div class="col-sm-6">
            <div class="form-group">
                  <label >Email address</label>
                  <input type="email" class="form-control"  id="email" name="email" placeholder="Enter email"  required autocomplete="off"  value="<?= $_POST['email']?>">
                </div>
             </div>
             <div class="col-sm-6"><div class="form-group">
                  <label >Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password"  required autocomplete="off" value="<?= $_POST['password']?>">
                </div></div>
            <div class="col-sm-6">
            <div class="form-group">
                  <label >Contact No</label>
                  <input type="tel" class="form-control" id="contat" name="contact" title="Enter Contact Number" placeholder="Enter Contact Number" pattern="[1-9]{1}[0-9]{9}"  required autocomplete="off"  value="<?= $_POST['contact']?>">
                </div>
            </div>
            <div class="col-sm-6">
            <div class="form-group">
                  <label >Alternative Contact No</label>
                  <input type="tel" class="form-control" id="altcontat" name="altcontact" title="Enter Alternative Contact Number" placeholder="Enter Alternative Contact Number" pattern="[1-9]{1}[0-9]{9}" required autocomplete="off" value="<?= $_POST['altcontact']?>">
                </div>
            </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="addtess_prof">Select Addres Proof Type</label>
                            <select name="addtess_prof" id="addtess_prof" class="form-control" required>
                                <option value="">--Select Address Proof Type--</option>
                                <option value="1" <?php if($_POST['addtess_prof']=='1'){ echo "selected";}?>>Adhar card </option>
                                <option value="2" <?php if($_POST['addtess_prof']=='2'){ echo "selected";}?>>Voter Id card</option>
                            </select>
                        
                    </div>
                </div>

            <div class="col-sm-6">
           		 <div class="form-group">
                  <label >Id Proof Front Side Image (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                  <input type="file" class="form-control chekPhotoSiz1" id="fsideId" name="fsideId" required  accept="image/*" required value="<?= $_POST['fsideId']?>">
                  <span id="ErrorMsg1" class="text-danger"></span>
                </div>
                </div>
                <div class="col-sm-6">
           		 <div class="form-group">
                  <label >Id Proof Back Side Image (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                  <input type="file" class="form-control chekPhotoSiz2" id="bsideId" name="bsideId" required  accept="image/*" required value="<?= $_POST['bsideId']?>">
                   <span id="ErrorMsg2" class="text-danger"></span>
                </div>
                </div>
                <div class="col-sm-6"><div class="form-group">
                <label class="" for="inlineFormCustomSelect">Select Country</label>
      		    <select class="form-control" name="country_id" onChange=" GetState(this.value)" required>
                <option value="" >--Select Country--</option>
    			<?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
    			<option value="<?=$countryName['country_id']?>"><?=$countryName['country_name']?></option>
   			    <?php }?>
    			</select>
                </div></div>
            <div class="col-sm-6"><div class="form-group">
                <label class="" >Select State</label>
       			 <select class="form-control" name="state_id" id="stateres" onChange="GetCity(this.value)" required >
    			 <option value="" >--Select State--</option>
    			 </select>
                </div></div>
            <div class="col-sm-6">
            	<div class="form-group">
                 <label class="" for="inlineFormCustomSelect">Select City</label>
     			 <select class="form-control" name="city_id" id="cityres" required onchange="SelectOnPin(this.value)">
    			 <option value="" >--Select City--</option>
    			</select>
                </div>
            </div>
            <div class="col-sm-6"><div class="form-group">
                  <label >Driving Licence number</label>
                  <input type="text" class="form-control chekPhotoSiz" id="driving_licnum" name="driving_licnum" placeholder="Enter Driving Licence number" required autocomplete="off" value="<?= $_POST['driving_licnum']?>">
                </div></div>
            <div class="col-sm-6"><div class="form-group">
                  <label >Document of rc In Image (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                  <input type="file" class="form-control chekPhotoSiz3" id="frc" name="frc" required  accept="image/*" required >
                   <span id="ErrorMsg3" class="text-danger"></span>
                </div></div>
                <div class="col-sm-6"><div class="form-group">
                  <label >Document of licence In Image (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                  <input type="file" class="form-control chekPhotoSiz4" id="brc" name="brc" required  accept="image/*" required >
                   <span id="ErrorMsg4" class="text-danger"></span>
                </div></div>
            <div class="col-sm-6">
            	<div class="form-group">
                <label>Valid Date Of Licence </label>
                    <input type="date" class="form-control" name="validateLic" required min="<?= date('Y-m-d')?>" value="<?= $_POST['validateLic']?>">
              </div>
            </div>
            <div class="col-sm-6">
            <div class="form-group">
                  <label >Document of insurance  In Image (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                  <input type="file" class="form-control chekPhotoSiz5" id="doc_ins" name="doc_ins" required  accept="image/*" required >
                   <span id="ErrorMsg5" class="text-danger"></span>
                </div></div>
                <div class="col-sm-6">
            	<div class="form-group">
                <label>Valid Date Of Insurance </label>
                    <input type="date" class="form-control" name="validateins" required min="<?= date('Y-m-d')?>" value="<?= $_POST['validateins']?>">
              </div>
            </div>
      
                <div class="col-sm-6">
            	<div class="form-group">
                <label>Account Beneficiary Name </label>
                    <input type="text" class="form-control" name="acname" required autocomplete="off" placeholder="Enter Account Beneficiary Name" value="<?= $_POST['acname']?>">
              </div>
            </div>
            <div class="col-sm-6">
            	<div class="form-group">
                <label>Account No </label>
                    <input type="number" class="form-control" name="acnum" required autocomplete="off" placeholder="Enter Account No." value="<?= $_POST['acnum']?>">
              </div>
            </div>
            <div class="col-sm-6">
            	<div class="form-group">
                <label>Branch </label>
                    <input type="text" class="form-control" name="branch" required autocomplete="off" placeholder="Enter Branch" value="<?= $_POST['branch']?>">
              </div>
            </div>
            <div class="col-sm-6">
            	<div class="form-group">
                <label>IFSC CODE </label>
                    <input type="text" class="form-control" name="ifsc" required autocomplete="off" placeholder="Enter IFSC CODE" value="<?= $_POST['ifsc']?>">
              </div>
            </div>
            <div class="col-sm-6">
            <div class="form-group">
                <label>Profile (<span  class="text-danger">Image Size Must Be Less Than 1 Mb</span>)</label>
                    <input type="file" class="form-control chekPhotoSiz6" name="img" required autocomplete="off"  accept="image/*">
                     <span id="ErrorMsg6" class="text-danger"></span>
              </div>
            </div>


        </div>
                
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit"  name="Submit" value="Submit" id="Submit">Submit</button> 
          
         
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
