<?php include('header.php')?>
<?php include('sidebar.php')?>
   
   <?php 
  $Prod_id=$dbf->checkSqlInjection($_REQUEST['id']);
  $product=$dbf->fetchSingle("product",'product_name',"product_id='$Prod_id'");
  ########################## Insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addgallery'){
	
	
	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/gallery/".$fname1;
		
		$destination_path1="images/gallery/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 600;
		$new_width1 = 600;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/gallery/".$fname1);
		
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
	$product_id=$dbf->checkXssSqlInjection($_REQUEST['product_id']);
	
	$string="image='$fname1', product_id='$product_id'";
	$dbf->insertSet("gallery",$string);
	
	header("Location:product_gallery.php?id=".$product_id);exit;
}
?>
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?= $product['product_name']?>
        <small>Gallery Image</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Product</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
<form action="" enctype="multipart/form-data" method="post">
<input type="hidden" name="product_id" id="product_id" value="<?php echo $Prod_id;?>">
       <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
          <div class="row">
          	<div class="col-sm-6">
            <!-- form start -->
              <div class="box-body">
                <div class="form-group">
				<label for="exampleInputEmail1">Select Images</label>
                  <input type="file" class="form-control" id="img" name="img" multiple placeholder="Primary Image" required accept="image/*">
                </div>
              </div>
              <!-- /.box-body -->
            </div>
            
            <div class="col-sm-6">
          <div class="box-footer">
		  <br>
		  <button type="submit" name="submit" id="submit" value="addgallery" class="btn btn-primary">Submit</button>
              </div>

            </div>
          </div>
            

              
<div class="row">

<?php
		$i=1;
		foreach($dbf->fetchOrder("gallery","product_id='$Prod_id'","gallery_id ASC","","") as $gallery){
		?>
                <div class="col-sm-3">
                <div class="gg" id="imgDel<?= $gallery['gallery_id']?>">
                <button class="btn btn-danger btt" id="idel" name="idel" type="button"  onClick="DelImg(<?=$gallery['gallery_id']?>)">X</button>
                <img src="images/gallery/thumb/<?php echo $gallery['image'];?>" width="100%">
                </div>
                
                </div>
         <?php $i++; } ?>
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
  <script>
    function DelImg(arg){
	 var conf=confirm("Are you sure want to delete this Image?");
    if(conf){
       document.getElementById('imgDel'+arg).style.display = "none"; 
	   
	   var url="getAjax.php";
 $.post(url,{"choice":"imgdel","img_id":arg},function(res){
 
 //alert(res)
});
    }
	
	  }
  </script>
   <?php include('footer.php')?>