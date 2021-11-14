<?php include('header.php')?>
<?php include('sidebar.php')?>
  <!-- Content Wrapper. Contains page content -->
    <?php 
    //Insert Data
    $error = "";
    if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='Add_gift'){
        $giftType = $dbf->checkSqlInjection($_POST['giftType']);
        $giftType = explode('-',$giftType);
        $min = $giftType[0];
        $max = $giftType[1];
        $optionName = $dbf->checkSqlInjection($_POST['optionName']);
        $price = $dbf->checkSqlInjection($_POST['price']);
        $sts = $dbf->checkSqlInjection($_POST['sts']);

        if($_FILES['img1']['name']!='' && (($_FILES['img1']['type'] == "image/gif") || ($_FILES['img1']['type'] == "image/jpeg") || ($_FILES['img1']['type'] == "image/pjpeg") || ($_FILES['img1']['type'] == "image/png") || ($_FILES['img1']['type'] == "image/bmp"))){
	
            $fname1 =time().".".substr(strrchr($_FILES['img1']['name'], "."), 1);
            $source_path1="../admin/images/gift/".$fname1;
            
            $destination_path1="../admin/images/gift/thumb/".$fname1;	
            $imgsize1 = getimagesize($source_path1);		
            $new_height1 = 400;
            $new_width1 = 400;		
            $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
            move_uploaded_file($_FILES['img1']['tmp_name'],"../admin/images/gift/".$fname1);
            
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
            $string = "vendor_id='$profileuserid',min='$min',max='$max',name='$optionName',price='$price',img='$fname1',status='$sts',created_date=NOW()";
            $dbf->insertSet("gifts",$string);
            header('Location:manage_gift.php?msg=success');exit;
        }else{
            $error = "Image Not Have Correct Format!!!!";
        }

    }
    //Insert Data
    //Update
    if(isset($_REQUEST['operations']) && $_REQUEST['operations']=='UpdateSTS'){
      $UpdateSTSGiftId = $dbf->checkSqlInjection($_POST['UpdateSTSGiftId']);
      $UpdateSTSid = $dbf->checkSqlInjection($_POST['UpdateSTSid']);
      $dbf->updateTable("gifts","status='$UpdateSTSid'","gifts_id='$UpdateSTSGiftId'");
      header('Location:manage_gift.php');exit;
    }
    //Update
      //Deleted
      if(isset($_REQUEST['operations']) && $_REQUEST['operations']=='Dleted'){
        $DelteGidtId = $dbf->checkSqlInjection($_POST['DelteGidtId']);
        $gift=$dbf->fetchSingle("gifts",'img',"gifts_id='$DelteGidtId'");
        $dbf->deleteFromTable("gifts","gifts_id='$DelteGidtId'");
        @unlink("../admin/images/gift/thumb/".$gift['img']);
        @unlink("../admin/images/gift/".$gift['img']);
        header('Location:manage_gift.php');exit;
      }
      //Deleted

      //Update
      if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='Update_gift'){
        $gift_id = $dbf->checkSqlInjection($_POST['gift_id']);
        $giftType = $dbf->checkSqlInjection($_POST['upgiftType']);
        $giftType = explode('-',$giftType);
        $min = $giftType[0];
        $max = $giftType[1];
        $optionName = $dbf->checkSqlInjection($_POST['upoptionName']);
        $price = $dbf->checkSqlInjection($_POST['upprice']);
        $sts = $dbf->checkSqlInjection($_POST['upsts']);

        if($_FILES['upimg1']['name']!='' && (($_FILES['upimg1']['type'] == "image/gif") || ($_FILES['upimg1']['type'] == "image/jpeg") || ($_FILES['upimg1']['type'] == "image/pjpeg") || ($_FILES['upimg1']['type'] == "image/png") || ($_FILES['upimg1']['type'] == "image/bmp"))){
	
            $fname1 =time().".".substr(strrchr($_FILES['upimg1']['name'], "."), 1);
            $source_path1="../admin/images/gift/".$fname1;
            
            $destination_path1="../admin/images/gift/thumb/".$fname1;	
            $imgsize1 = getimagesize($source_path1);		
            $new_height1 = 400;
            $new_width1 = 400;		
            $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
            move_uploaded_file($_FILES['upimg1']['tmp_name'],"../admin/images/gift/".$fname1);
            
            if($_FILES['upimg1']['type'] == "image/JPG" || $_FILES['upimg1']['type'] == "image/JPEG" || $_FILES['upimg1']['type'] == "image/jpg" || $_FILES['upimg1']['type']=='image/jpeg' ){
                //for small                
                $srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
                ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
                ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
            }else if($_FILES['upimg1']['type'] == "image/gif" || $_FILES['upimg1']['type'] == "image/GIF"){  
                //for small          
                $srcimg1 = imagecreatefromgif($source_path1);
                ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
                ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
            }else if($_FILES['upimg1']['type'] == "image/png" || $_FILES['upimg1']['type'] == "image/PNG"){ 
                 //for small          
                $srcimg1 = imagecreatefrompng($source_path1);
                ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
                ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
            }
       
           
        }
        if($fname1!=''){
          $gift=$dbf->fetchSingle("gifts",'img',"gifts_id='$gift_id'");
          @unlink("../admin/images/gift/thumb/".$gift['img']);
          @unlink("../admin/images/gift/".$gift['img']);
        $string = "vendor_id='$profileuserid',min='$min',max='$max',name='$optionName',price='$price',img='$fname1',status='$sts',created_date=NOW()";
        }else{
          $string = "vendor_id='$profileuserid',min='$min',max='$max',name='$optionName',price='$price',status='$sts',created_date=NOW()";
        }
        $dbf->updateTable("gifts",$string,"gifts_id='$gift_id'");
        header('Location:manage_gift.php?msg=upsuccess');exit;
    }
      //Update
    ?>


<script type="text/javascript">
function UpdateSts(gift_id,sts){
	if(sts==1){
    var msg = "Are You Sure To Active This Record?";
  }else{
    var msg = "Are You Sure To Block This Record?";
  }
	var conf=confirm(msg);
	if(conf){
      $('#UpdateSTSGiftId').val(gift_id);
      $('#UpdateSTSid').val(sts);
	   $("#frm_sts_update").submit();
	}
}

function deleteRecord(gift_id){
var conf = confirm('Are You Sure To Delete This Record?');
if(conf){
    $('#DelteGidtId').val(gift_id);
	   $("#frm_delete").submit();
}
}
</script>
<form action="" method="post" id="frm_sts_update">
<input type="hidden" name="operations" value="UpdateSTS">
<input type="hidden" id="UpdateSTSGiftId" name="UpdateSTSGiftId">
<input type="hidden" id="UpdateSTSid" name="UpdateSTSid">
</form>

<form action="" method="post" id="frm_delete">
<input type="hidden" name="operations" value="Dleted">
<input type="hidden" id="DelteGidtId" name="DelteGidtId">
</form>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage Gift Option Value
        <small><?php echo $profile['shop_name'];?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard </a></li>
        <li class="active">Manage Gift</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

    <div class="col-md-5" id="AddGift">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Gift Option Value</h3><br>
              <span class="text-danger"><?=$error?></span>
              <?php if($_REQUEST['msg']=='success'){?>
              <span class="text-success">New Gift Card Added Successfully.</span>
              <?php }?>
              <?php if($_REQUEST['msg']=='upsuccess'){?>
              <span class="text-success">Updated Gift Card  Successfully.</span>
              <?php }?>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" enctype="multipart/form-data" action="" method="post">
              <div class="box-body">
                <div class="form-group">
                  <label for="giftType">Gift Type</label>
                  <input type="text" class="form-control" id="giftType" placeholder="i.e- ₹500-₹1000" name="giftType" required>
                </div>
                <div class="form-group">
                  <label for="optionName">Option Name</label>
                  <input type="text" class="form-control" id="optionName" name="optionName" placeholder="Enter Option Name" required>
                </div>
                <div class="form-group">
                  <label for="price">Price(INR)</label>
                  <input type="text" class="form-control" id="price" name="price" placeholder="Enter Price" value="0" required>
                </div>
                <div class="form-group">
                  <label for="img">Option Image</label>
                  <input type="file" id="img1" name="img1"class="form-control" required accept="image/*">
                </div>
                <div class="form-group">
                  <label> Active</label> <br>
                  <input type="radio" name="sts" id="" checked value="1" required>Yes
                  <input type="radio" name="sts" id="" value="0" required>No
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="submit" value="Add_gift">Submit</button>
              </div>
            </form>
          </div>
     </div>
     <div class="col-md-5" style="display:none;" id="UpdateGift">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Update Gift Option Value</h3><br>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" enctype="multipart/form-data" action="" method="post">
            <input type="hidden" name="gift_id" id="gift_id">
              <div class="box-body">
                <div class="form-group">
                  <label for="giftType">Gift Type</label>
                  <input type="text" class="form-control" id="upgiftType" placeholder="i.e- ₹500-₹1000" name="upgiftType" required>
                </div>
                <div class="form-group">
                  <label for="optionName">Option Name</label>
                  <input type="text" class="form-control" id="upoptionName" name="upoptionName" placeholder="Enter Option Name" required>
                </div>
                <div class="form-group">
                  <label for="price">Price(INR)</label>
                  <input type="text" class="form-control" id="upprice" name="upprice" placeholder="Enter Price" value="0" required>
                </div>
                <div class="form-group">
                  <label for="img">Option Image</label>
                  <input type="file" id="upimg1" name="upimg1"class="form-control"  accept="image/*">
                  <img src="" id="UpImg" width="50">
                </div>
                <div class="form-group">
                  <label> Active</label> <br>
                  <input type="radio" name="upsts" id="yes" checked value="1" required>Yes
                  <input type="radio" name="upsts" id="no" value="0" required>No
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="submit" value="Update_gift">Update</button>
              </div>
            </form>
          </div>
     </div>
     <div class="col-md-7">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Manage Gift Type</h3>
            </div>
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th scope="col">Sl No</th>
                    <th scope="col">Gift Card Option</th>
                    <th scope="col">Gift Card Type</th>
                    <th scope="col">Price</th>
                     <th scope="col">Status</th>
                     <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $i=1;
                  foreach($dbf->fetchOrder("gifts","vendor_id='$profileuserid'","gifts_id DESC","","") as $gift){
                ?>
                <tr>
                <td><?= $i++?></td>
                <td><?= $gift['name']?><br>
                    <img src="../admin/images/gift/thumb/<?=$gift['img']?>" alt="" width="120" height="60">
                </td>
                <td>₹<?= $gift['min']?>-₹<?= $gift['max']?></td>
                <td>₹<?= $gift['price']?></td>
                <td>
                <?php if($gift['status']==1){?>
                <button class="btn btn-success" onclick="UpdateSts(<?=$gift['gifts_id']?>,0)">Active</button>
                <?php }else{?>
                <button class="btn btn-danger" onclick="UpdateSts(<?=$gift['gifts_id']?>,1)">Block</button>
                <?php }?>
                </td>
                <td>  
                   <button class="btn btn-social-icon btn-primary"  onclick="UpdateGift(<?=$gift['gifts_id']?>)"><i class="fa fa-edit"></i></button>
                    <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $gift['gifts_id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a></td>
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                <tr>
                  <th scope="col">Sl No</th>
                    <th scope="col">Gift Card Option</th>
                    <th scope="col">Gift Card Type</th>
                    <th scope="col">Price</th>
                     <th scope="col">Status</th>
                     <th>Action</th>
                </tr>
                </tfoot>
              </table>
          </div>
     </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 
   <?php include('footer.php')?>
<script>
function UpdateGift(gift_id){
  $.post('getAjax.php',{'action':'GetGiftDetails','gift_id':gift_id},function(res){
    res  = res.split('!next!');
    $('#gift_id').val(gift_id);
    $('#upgiftType').val(res[0]);
    $('#upoptionName').val(res[1]);
    $('#upprice').val(res[2]);
    $('#UpImg').attr("src", "../admin/images/gift/thumb/"+res[3]);
    if(res[4]==1){
      $("#yes").prop("checked", true);
    }else{
      $("#no").prop("checked", true);
    }
    $('#AddGift').css('display','none');
    $('#UpdateGift').css('display','');
  });
}

</script>