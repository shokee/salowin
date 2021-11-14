<?php include('header.php')?>
<?php include('sidebar.php')?>
  
<?php 
 ########################## insert state #############################
 if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addstate'){
	
    $country=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
    $state=$_REQUEST['state'];
      	
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
    $string="product_catagory_1_id='$country',product_catagory_2_name='$state',img='$fname1',created_date=NOW()";
    $dbf->insertSet("product_catagory_2",$string);
    header("Location:category2add.php?msg=success");
    }else{
      header("Location:category2add.php?msg=Erro");
    }
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
    			<option value="<?=$DirName['product_catagory_1_id']?>" ><?=$DirName['product_catagory_1_name']?></option>
   			    <?php }?>
    			</select>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="box-body">
                <div class="form-group">
                <label>Enter Subcategory Name</label>
                <input type="text" class="form-control" name="state" id="state" placeholder="Enter Subcategory Name" required/>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="box-body">
                <div class="form-group">
                <label>Select Subcategory Images</label>
                <input type="file" class="form-control" name="img" accept="image/*" required/>
                </div>
              </div>
            </div>
            
            <div class="col-sm-12">
          <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-left " value="addstate" name="submit">Submit</button>
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