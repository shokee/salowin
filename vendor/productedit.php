<?php 
  
  ########################## Edit Product  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addproduct'){
	
	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="../admin/images/product/".$fname1;
		
		$destination_path1="../admin/images/product/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 600;
		$new_width1 = 600;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"../admin/images/product/".$fname1);
		
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
	$description=$dbf->checkXssSqlInjection($_REQUEST['description']);
	$brands_id=$dbf->checkXssSqlInjection($_REQUEST['brands_id']);
	$regular_price=$dbf->checkXssSqlInjection($_REQUEST['regular_price']);
	$sales_price=$dbf->checkXssSqlInjection($_REQUEST['sales_price']);
	$unit=$dbf->checkXssSqlInjection($_REQUEST['unit']);
	$stock=$dbf->checkXssSqlInjection($_REQUEST['stock']);
	$category1= $_REQUEST['category1'];
	$category2= $_REQUEST['category2'];
	$category3= $_REQUEST['category3'];
	
	
	
	
	if($fname1!=''){
	$string="product_name='$product_name', description='$description', brands_id='$brands_id', primary_image='$fname1', unit_id='$unit', regular_price='$regular_price', sales_price='$sales_price', stocks='$stock', created_date=NOW()";
	}else{
		$string="product_name='$product_name', description='$description', brands_id='$brands_id', unit_id='$unit', regular_price='$regular_price', sales_price='$sales_price', stocks='$stock',  created_date=NOW()";
		}
	
	$dbf->updateTable("product",$string,"product_id='$pagesId'");
	
	$dbf->deleteFromTable("pro_rel_cat1","product_id='$pagesId'");
	$dbf->deleteFromTable("pro_rel_cat2","product_id='$pagesId'");
	$dbf->deleteFromTable("pro_rel_cat3","product_id='$pagesId'");
		foreach($category1 as $cate1){
		$dbf->insertSet("pro_rel_cat1","product_id='$pagesId',catagory1_id='$cate1'");
		}
		foreach($category2 as $cate2){
		$dbf->insertSet("pro_rel_cat2","product_id='$pagesId',catagory2_id='$cate2'");
		}
		foreach($category3 as $cate3){
		$dbf->insertSet("pro_rel_cat3","product_id='$pagesId',catagory3_id='$cate3'");
		}
	header("Location:product_management.php");exit;
}
?>



<form action="" enctype="multipart/form-data" method="post">

<div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit  <?php echo $product['product_name'];?></h4>
               <input type="hidden" name="attributeid" id="attributeid"  value="<?php echo $product['product_id'];?>"  />
              </div>
              <div class="modal-body">
               <form action="" enctype="multipart/form-data" method="post">
					<div class="row">
          	<div class="col-sm-12">
            <!-- form start -->
              <div class="box-body">
                <div class="form-group">
                  <input type="hidden" class="form-control" id="pagesId" name="pagesId" value="<?php echo $product['product_id'];?>">
                  <label for="exampleInputEmail1">Product Name</label>
                  <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $product['product_name'];?>">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Product Short Description</label>
                  <textarea class="form-control" name="description" id="description"><?php echo $product['description'];?></textarea>
                </div>
                
                <div class="form-group">
                <label>Select Brand</label>
                <select class="form-control select2" name="brands_id" style="width:100%;">
                <option>select Brand</option>
    			<?php  foreach($dbf->fetchOrder("brands","status='1'","brands_id ASC","","") as $DirName){?>
    			<option value="<?=$DirName['brands_id']?>" <?php if($DirName['brands_id']==$product['brands_id']){ echo"selected";}?> ><?=$DirName['brands_name']?></option>
   			    <?php }?>
    			</select>
                
              </div>
                
                <div class="form-group">
                <label for="exampleInputEmail1">Regular Price</label>

                <div class="input-group">
                <span class="input-group-addon">Rs</span>
                <input type="text" class="form-control" name="regular_price" id="regular_price" value="<?php echo $product['regular_price'];?>">
                <span class="input-group-addon">
                <div class="selectdiv">
                <select>
                	 <option>Select </option>
                	<?php  foreach($dbf->fetchOrder("units","","unit_id ASC","","") as $DirName){?>
    			   <option value="<?=$DirName['unit_id']?>" <?php if($DirName['unit_id']==$product['unit_id']){ echo"selected";}?> > <?=$DirName['unit_name']?></option>
   			        <?php }?>
                </select>
                </div>
                </span>
              </div>
                </div>
                
                <div class="form-group">
                <label for="exampleInputEmail1">Sale Price</label>

                <div class="input-group">
                <span class="input-group-addon">Rs</span>
                <input type="text" class="form-control" name="sales_price" id="sales_price" value="<?php echo $product['regular_price'];?>">
                <span class="input-group-addon">
                <div class="selectdiv">
                <select name="unit" id="unit">
                    <option>Select </option>
                	<?php  foreach($dbf->fetchOrder("units","","unit_id ASC","","") as $DirName){?>
    			    <option value="<?=$DirName['unit_id']?>" <?php if($DirName['unit_id']==$product['unit_id']){ echo"selected";}?> > <?=$DirName['unit_name']?></option>
   			        <?php }?>
                </select>
                </div>
                </span>
              </div>
                </div>
                
          
              <div class="form-group">
                  <label for="exampleInputEmail1">available Stock</label>
                  <input type="text" class="form-control" id="stock" name="stock" value="<?php echo $product['stocks'];?>">
                </div>
                
                 <label>Selet Catagory</label>
              	<div class="pcat">
                <div class="form-group">
                	<ul>
                    <?php  $vendcat = $dbf->fetchSingle("vendor_catagory",'*',"vendor_id='$profileuserid'");?>
                    
                    	<?php  foreach($dbf->fetchOrder("product_catagory_1","product_catagory_1_id='$vendcat[vendor_catagory_id]'","product_catagory_1_name","","") as $product_catagory_1){?>
                    	<li><input type="checkbox"  value="<?= $product_catagory_1['product_catagory_1_id']?>" name="category1[]" <?php foreach($dbf->fetchOrder("pro_rel_cat1","product_id ='$product[product_id]'","","","") as $product_catagory_1sel){ if($product_catagory_1sel['catagory1_id']==$product_catagory_1['product_catagory_1_id']){ echo "checked";}}?>> <?= $product_catagory_1['product_catagory_1_name']?>
                        	<ul>
                            <?php  foreach($dbf->fetchOrder("product_catagory_2","product_catagory_1_id='$product_catagory_1[product_catagory_1_id]'","product_catagory_2_name","","") as $product_catagory_2){?>
                            	<li><input type="checkbox" value="<?= $product_catagory_2['product_catagory_2_id']?>" name="category2[]" <?php foreach($dbf->fetchOrder("pro_rel_cat2","product_id ='$product[product_id]'","","","") as $product_catagory_2sel){ if($product_catagory_2sel['catagory2_id']==$product_catagory_2['product_catagory_2_id']){ echo "checked";}}?>> <?= $product_catagory_2['product_catagory_2_name']?>
                                	<ul>
                                     <?php  foreach($dbf->fetchOrder("product_catagory_3","product_catagory_2_id='$product_catagory_2[product_catagory_2_id]'","product_catagory_3_name","","") as $product_catagory_3){?>
                                	<li><input type="checkbox" value="<?= $product_catagory_3['product_catagory_3_id']?>" name="category3[]"  <?php foreach($dbf->fetchOrder("pro_rel_cat3","product_id='$product[product_id]'","","","") as $product_catagory_3sel){ if($product_catagory_3sel['catagory3_id']==$product_catagory_3['product_catagory_3_id']){ echo "checked";}}?>> <?= $product_catagory_3['product_catagory_3_name']?></li>
                                    <?php }?>
                                    </ul>
                                </li>
                                <?php }?>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                    
                 </div>
                </div>
              
              <div class="form-group">
                  <label for="exampleInputEmail1">Primary Image</label>
                  <input type="file" class="form-control" id="img" name="img" placeholder="Primary Image">
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
              
            </div>
       </form>
