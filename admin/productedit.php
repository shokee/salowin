<?php include('header.php')?>
<?php include('sidebar.php')?>
  
   <?php 
    $Prod_id=$dbf->checkSqlInjection($_REQUEST['id']);
	$product=$dbf->fetchSingle("product",'*',"product_id='$Prod_id'");
  
  ########################## Edit Product  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addproduct'){
	
	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/product/".$fname1;
		
		$destination_path1="images/product/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 600;
		$new_width1 = 600;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/product/".$fname1);
		
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
	$pagesId=$_REQUEST['pagesId'];
	$product_name=$dbf->checkXssSqlInjection($_REQUEST['product_name']);
	$description=$_REQUEST['description'];
	$brands_id=$dbf->checkXssSqlInjection($_REQUEST['brands_id']);
	$groups_id=$dbf->checkXssSqlInjection($_REQUEST['groups_id']);
	$type_id=$dbf->checkXssSqlInjection($_REQUEST['type_id']);
  if($brands_id==''){
    $brands_id="0";
  }
  if($groups_id==''){
    $groups_id="0";
  }
  if($type_id==''){
    $type_id="0";
  }

	$category1= $_REQUEST['category1'];
	$category2= $_REQUEST['category2'];
	


	if($fname1!=''){
	$string="product_name='$product_name', description='$description', brands_id='$brands_id', groups_id='$groups_id', type_id='$type_id', primary_image='$fname1', created_date=NOW()";
	}else{
		$string="product_name='$product_name', description='$description', brands_id='$brands_id', groups_id='$groups_id', type_id='$type_id',created_date=NOW()";
		}
	
	$dbf->updateTable("product",$string,"product_id='$pagesId'");
	
	$dbf->deleteFromTable("pro_rel_cat1","product_id='$pagesId'");
	$dbf->deleteFromTable("pro_rel_cat2","product_id='$pagesId'");

		foreach($category1 as $cate1){
		$dbf->insertSet("pro_rel_cat1","product_id='$pagesId',catagory1_id='$cate1'");
		}
		foreach($category2 as $cate2){
		$dbf->insertSet("pro_rel_cat2","product_id='$pagesId',catagory2_id='$cate2'");
		}
	header("Location:productedit.php?id=".$pagesId);exit;
}
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Product
        <small>Edit Your Product</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Product</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
<form action="" enctype="multipart/form-data" method="post">
<input type="hidden" name="pagesId" value="<?= $Prod_id?>">
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
                  <label for="exampleInputEmail1">Product Name</label>
                  <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Product Name" required value="<?= $product['product_name']?>">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Product Short Description</label>
                  <textarea id="editor1" class="form-control" name="description" id="description" required><?= $product['description']?></textarea>
                </div>
                
                <div class="form-group">
                 <label>Select Brand</label>
                    <select class="form-control select2" name="brands_id">
                        <option>Select A Brand </option>
            			<?php  foreach($dbf->fetchOrder("brands","","brands_id ASC","","") as $DirName){?>
            			<option value="<?=$DirName['brands_id']?>" <?php if($product['brands_id']==$DirName['brands_id']){ echo "selected";}?>><?=$DirName['brands_name']?></option>
       			        <?php }?>
        			</select>
                </div>
               
                <div class="form-group">
                 <label>Select Group</label>
                    <select class="form-control select2" name="groups_id">
                        <option>Select A Group </option>
            		<?php  foreach($dbf->fetchOrder("groups","","groups_id ASC","","") as $DirName){?>
            			<option value="<?=$DirName['groups_id']?>" <?php if($product['groups_id']==$DirName['groups_id']){ echo "selected";}?>><?=$DirName['groups_name']?></option>
       			        <?php }?>
        			</select>
                </div>
                
                <div class="form-group">
                 <label>Select Type</label>
                    <select class="form-control select2" name="type_id">
                        <option>Select A Type </option>
            			<?php  foreach($dbf->fetchOrder("type","","type_id ASC","","") as $DirName){?>
            			<option value="<?=$DirName['type_id']?>" <?php if($product['type_id']==$DirName['type_id']){ echo "selected";}?>><?=$DirName['type_name']?></option>
       			        <?php }?>
        			</select>
                </div>
             
              </div>
              <!-- /.box-body -->
            </div>
            <div class="col-sm-6">
            <!-- form start -->
              <div class="box-body">
            
				<?php 
				$array_of_category=array();
				foreach($dbf->fetchOrder("pro_rel_cat1","product_id='$Prod_id'","","catagory1_id","") as $Vendor_cate){
						array_push($array_of_category,$Vendor_cate['catagory1_id']);
				}
				$array_of_subcategory=array();
				foreach($dbf->fetchOrder("pro_rel_cat2","product_id='$Prod_id'","","catagory2_id","") as $Vendor_subcate){
						array_push($array_of_subcategory,$Vendor_subcate['catagory2_id']);
				}
				
				$array_of_subcategory2=array();
				foreach($dbf->fetchOrder("pro_rel_cat3","product_id='$Prod_id'","","catagory3_id","") as $Vendor_subcate2){
						array_push($array_of_subcategory2,$Vendor_subcate2['catagory3_id']);
				}
				
				?>
				
				  <div class="form-group">
                       
                <label for="inlineFormCustomSelect">Select  Category</label>
      		    <select class="form-control" name="category1[]" onChange="get1stCat(this.value)" required>
                <option value="" >--Select Category--</option>
    			<?php  foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","","") as $countryName){?>
    			<option value="<?=$countryName['product_catagory_1_id']?>" <?php if(in_array($countryName['product_catagory_1_id'], $array_of_category)){ echo "selected"; }?> ><?=$countryName['product_catagory_1_name']?></option>
   			    <?php }?>
    			</select>
                </div>
                
            	 <div class="form-group">
                <label class="" >Select Sub Category</label>
       			 <select class="form-control" name="category2[]" id="cat22" onChange="get2ndCat(this.value)" required>
    			 <option value="" >-- Select --</option>
    			<?php  foreach($dbf->fetchOrder("product_catagory_2","","product_catagory_2_id ASC","","") as $countryName){?>
    			<option value="<?=$countryName['product_catagory_2_id']?>" <?php if(in_array($countryName['product_catagory_2_id'], $array_of_subcategory)){ echo "selected"; }?> ><?=$countryName['product_catagory_2_name']?></option>
   			    <?php }?>
    			 
    			 </select>
                </div>
               
                
       <!--          <div class="form-group">-->
       <!--          <label>Select 2nd Sub Category</label>-->
     		<!--	 <select class="form-control" name="category3[]" id="cat33" required>-->
    			<!-- <option value="" >-- Select --</option>-->
    			<!-- 	<?php  foreach($dbf->fetchOrder("product_catagory_3","","product_catagory_3_id ASC","","") as $countryName){?>-->
    			<!--<option value="<?=$countryName['product_catagory_3_id']?>" <?php if(in_array($countryName['product_catagory_3_id'], $array_of_subcategory2)){ echo "selected"; }?> ><?=$countryName['product_catagory_3_name']?></option>-->
   			 <!--   <?php }?>-->
    			<!--</select>-->
       <!--         </div>-->
				
				
				
				
               <!--  <label>Selet Catagory</label>-->
              	<!--<div class="pcat">-->
               <!-- <div class="form-group">-->
               <!-- 	<ul>-->
               <!--     	<?php  foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_name","","") as $product_catagory_1){?>-->
               <!--     	<li><input type="checkbox"  value="<?= $product_catagory_1['product_catagory_1_id']?>" name="category1[]" <?php if(in_array($product_catagory_1['product_catagory_1_id'], $array_of_category)){ echo"checked";}?>> <?= $product_catagory_1['product_catagory_1_name']?>-->
               <!--         	<ul>-->
               <!--             <?php  foreach($dbf->fetchOrder("product_catagory_2","product_catagory_1_id='$product_catagory_1[product_catagory_1_id]'","product_catagory_2_name","","") as $product_catagory_2){?>-->
               <!--             	<li><input type="checkbox"  value=" <?= $product_catagory_2['product_catagory_2_id']?>" name="category2[]" <?php if(in_array( $product_catagory_2['product_catagory_2_id'], $array_of_subcategory)){ echo"checked";}?>> <?= $product_catagory_2['product_catagory_2_name']?>-->
               <!--                 	<ul>-->
               <!--                      <?php  foreach($dbf->fetchOrder("product_catagory_3","product_catagory_2_id='$product_catagory_2[product_catagory_2_id]'","product_catagory_3_name","","") as $product_catagory_3){?>-->
               <!--                 	<li><input type="checkbox"  value="<?= $product_catagory_3['product_catagory_3_id']?>" name="category3[]" <?php if(in_array($product_catagory_3['product_catagory_3_id'], $array_of_subcategory2)){ echo"checked";}?>> <?= $product_catagory_3['product_catagory_3_name']?></li>-->
               <!--                     <?php }?>-->
               <!--                     </ul>-->
               <!--                 </li>-->
               <!--                 <?php }?>-->
               <!--             </ul>-->
               <!--         </li>-->
               <!--         <?php } ?>-->
               <!--     </ul>-->
                    
               <!--  </div>-->
               <!-- </div>-->
              
              <div class="form-group">
                  <label for="exampleInputEmail1">Primary Image</label>
                  <input type="file" class="form-control" id="img" name="img" placeholder="Primary Image" >
				  <img src="images/product/thumb/<?php echo $product['primary_image'];?>" alt="<?= $product['product_name']?>" width="100" height="100">
                </div>
                
              </div>
              
            </div>
            <div class="col-sm-12">
          <div class="box-footer">
                <button type="submit" name="submit" id="submit" value="addproduct" class="btn btn-primary">Submit</button>
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
  
  
 <script type="text/javascript">
  
   function  get1stCat(val) {
       // alert(val);
    
var url="getAjax.php";
  $.post(url,{"choice":"getCat","value":val},function(res){
 $('#cat22').html(res);
 // alert(res)
});
  
  } 


function get2ndCat(val){


var url="getAjax.php";
  $.post(url,{"choice":"get2ndCat","value":val},function(res){
 $('#cat33').html(res);
// alert(res)
});
} 


</script>
  
  
   <?php include('footer.php')?>

   <script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1',)
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
  })
  
</script>