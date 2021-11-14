<?php include('header.php')?>
<?php include('sidebar.php')?>
  
<?php 
$Cate_id=$dbf->checkSqlInjection($_REQUEST['id']);
$Subcatete_gory=$dbf->fetchSingle("product_catagory_2",'*',"product_catagory_2_id='$Cate_id'");
 ########################## insert state #############################
 if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editstate'){
	
	
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['subcategory']);
	$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_name=$_REQUEST['state'];

  if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
  
    $fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
    $source_path1="images/subcategory/".$fname1;
    
    $destination_path1="images/subcategory/thumb/".$fname1;  
    $imgsize1 = getimagesize($source_path1);    
    $new_height1 = 400;
    $new_width1 = 1160;   
    $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");           
    move_uploaded_file($_FILES['img']['tmp_name'],"images/subcategory/".$fname1);
    
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
    if($fname1!=''){
      $string="product_catagory_1_id='$country_id',product_catagory_2_name='$state_name',img='$fname1',created_date=NOW()";
    }else{
      $string="product_catagory_1_id='$country_id',product_catagory_2_name='$state_name',created_date=NOW()";
    }
	$dbf->updateTable("product_catagory_2",$string,"product_catagory_2_id='$state_id'");
	header('Location:catagory-2.php?id='.$state_id);exit;
}
?>


 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Subcategory 2
        <small>Subcategory 2</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Subcategory</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
<form action="" enctype="multipart/form-data" method="post">
    <input type="hidden" name="subcategory" value="<?= $Cate_id?>">
       <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
          <div class="row">
          <div class="col-sm-12">
          <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
        <div class="uk-alert-success" uk-alert>
        <a class="uk-alert-close" uk-close></a>
        <p>Subcategory Added Successfully</p>
        </div>
        <?php }?>
        <div>
          	<div class="col-sm-4">
              <div class="box-body">
                <div class="form-group">
                <label>Select catagory Name</label>
                <select class="form-control" name="country_id" required onchange="GetSubCategory(this.value)">
                <option value="">~~Select Category~~</option>
    			<?php  foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","product_catagory_1_name,product_catagory_1_id","") as $DirName){?>
    			<option value="<?=$DirName['product_catagory_1_id']?>" <?php if($Subcatete_gory['product_catagory_1_id']==$DirName['product_catagory_1_id']){ echo"selected";}?>><?=$DirName['product_catagory_1_name']?></option>
   			    <?php }?>
    			</select>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="box-body">
                <div class="form-group">
                <label>Enter Subcategory Name</label>
                <input type="text" class="form-control" name="state" id="state" placeholder="Enter Subcategory Name" required value="<?= $Subcatete_gory['product_catagory_2_name']?>"/>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="box-body">
                <div class="form-group">
                <label>Select Subcategory Images</label>
                <input type="file" class="form-control" name="img" accept="image/*"/>
                  <?php if($Subcatete_gory['img']!=''){?>
                   <img src="images/subcategory/<?= $Subcatete_gory['img']?>" alt="" width="50" height="50">
                  <?php }else{?>
                    <img src="images/subcategory/no-img.png" alt="" width="50" height="50">
                  <?php }?>
                </div>
              </div>
            </div>
            
            
            <div class="col-sm-12">
          <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-left " value="editstate" name="submit">Update</button>
              </div>
            </div>
          </div>
            
              
          </div>
          <!-- /.box -->
          </div>
           
          </div>
</form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Select2 -->
   <?php include('footer.php')?>
<script>
  function  GetSubCategory(val) {
       // alert(val);
    
//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getSubcate","value":val},function(res){
 $('#state_id').html(res);
// alert(res)
});
  
  } 

</script>