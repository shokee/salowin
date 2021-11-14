<?php include('header.php')?>
<?php include('sidebar.php')?>
  
  <?php 
  ########################## insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addcountry'){
	
if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/add/".$fname1;
		
		$destination_path1="images/add/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/add/".$fname1);
		
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
$add_name=$dbf->checkXssSqlInjection($_REQUEST['add_name']);
$add_url=$dbf->checkXssSqlInjection($_REQUEST['add_url']);
  
$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
	$city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
  $zcode = $dbf->checkXssSqlInjection($_REQUEST['zcode']);
  $position = $dbf->checkXssSqlInjection($_REQUEST['position']);
$string="add_title='$add_name', add_link='$add_url',country_id='$country_id',state_id='$state_id',city_id='$city_id',pin_id='$zcode',position='$position',add_image='$fname1', created_date=NOW()";
$dbf->insertSet("addd",$string);
	
	
header("Location:add_management.php?msg=success");
	
}
?>
            

             
   
<?php
########################## DELETE  #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
 
 $resBanner=$dbf->fetchSingle("addd","*","add_id='$id'");	
	@unlink("images/add/".$resBanner['add_image']);
	@unlink("images/add/thumb/".$resBanner['add_image']);
	
	$dbf->deleteFromTable("addd","add_id='$id'");
	header("Location:add_management.php");
}
if(isset($_REQUEST['action']) && $_REQUEST['action']=='Fillters'){
  $city=$dbf->checkXssSqlInjection($_REQUEST['city']);
  $pin=$dbf->checkXssSqlInjection($_REQUEST['pin']);
  $condi=" AND city_id='$city' AND pin_id='$pin'";
}else{
  $condi="";
}
?>

 <form name="frm_deleteBanner" id="frm_deleteBanner" action="" method="post">
 <input type="hidden" name="operation" id="operation" value="">
 <input type="hidden" name="country_id" id="country_id" value="">
 </form>
                  
<script type="text/javascript">
function deleteRecord(id){
	$("#operation").val('delete');
	$("#country_id").val(id);
	var conf=confirm("Are you sure want to delete this Record");
	if(conf){
	   $("#frm_deleteBanner").submit();
	}
}
</script>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Advertise
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
            <div class="box-header">
            <?php if(in_array('23.1',$Job_assign)){?>
              <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add </button></h3>
            <?php }?>
              <div class="modal fade" id="modal-default-add">
              
            <form  action="" enctype="multipart/form-data" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add  </h4>
              </div>
              
              <div class="modal-body">
              <div class="col-md-6">
                <div class="form-group">
                <label>Enter Add Name</label>
                <input type="text" class="form-control" name="add_name" id="add_name" placeholder="Enter Add Name" required />
                </div>
                </div>

                <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1"> Image</label>
                  <input type="file" class="form-control" id="img" name="img" >
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                <label>Enter Add Url</label>
                <input type="text" class="form-control" name="add_url" id="add_url" placeholder="url" required />
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                <label>Select Positon Of Banner</label>
                <select class="form-control" name="position" required>
                <option value="">~~Select Position~~</option>
                <option value="1">Banner Side</option>
                <option value="2">After Latest Product</option>
                <option value="3">After Deals of the day</option>
                <option value="4">After Trending Product</option>
                <option value="5">Sidebar Image</option>
                </select>
                </div>
                </div>
      
<div class="row">
		<div class="col-md-3">
		<label class="" >Select Contry</label>
		<select class="form-control" name="country_id" onChange=" GetState(this.value)" required>
                <option value="" >--Select Country--</option>
    			<?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
    			<option value="<?=$countryName['country_id']?>" ><?=$countryName['country_name']?></option>
   			    <?php }?>
    			</select>
		</div>

		<div class="col-md-3">
		<label class="" >Select State</label>
       			 <select class="form-control" name="state_id" id="stateres" onChange="GetCity(this.value)" required>
    			 <option value="" >--Select State--</option>
    			 </select>
		</div>

		<div class="col-md-3">
		<label class="" for="inlineFormCustomSelect">Select City</label>
     			 <select class="form-control" name="city_id" id="cityres" required onchange="SelectOnPin(this.value)">
    			 <option value="" >--Select City--</option>
    			</select>
		</div>
		<div class="col-md-3">
		<label class="" for="inlineFormCustomSelect">Select Pin</label>
                <select class="uk-select" required="" name="zcode" id="zcode" >
                  <option value="">--Select Pincode--</option>
                </select>
		</div>
	</div>
  </div>
              
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="addcountry" name="submit">Add </button>
                <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                
              </div>
              
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
          <!-- /.modal-dialog -->
        </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?php if($_SESSION['usertype']=='1'){
              $loc="";

              ?>
            <form action="" method="post">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <select name="city" id="" class="form-control select2" onchange="UpdateChangepin(this.value)" required>
          <option value="">--Select City--</option>
          <?php  
          foreach($dbf->fetchOrder("city","","city_name ASC","","") as $stateName){?>
           <option value="<?=$stateName['city_id']?>" <?php if($_POST['city']==$stateName['city_id']){ echo"selected";}?>><?=$stateName['city_name']?></option>
           <?php }?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
        <select name="pin" class="form-control select2" id="loc_selected_pin" required>
          <option value="">--Select Pin--</option>
          </select>
        </div>
      </div>
  
      <div class="col-md-4">
        <div class="form-group">
        <button class="btn btn-primary" name="action" value="Fillters">Fillter</button>
        <a href="" class="btn btn-default">Refresh</a>
        </div>
      </div>
    </div>
    </form>
    <?php }else{          
     $loc = "city_id='$_SESSION[city]' ";
     }?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Slno.</th>
                  <th>Brands Name</th>
                  <th>Images</th>
                  <th>url</th>
                  <th>City</th>
                  <th>Pin</th>
                  <?php if(in_array('23.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('23.3',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
                
                    <?php
					$i=1;
					 foreach($dbf->fetchOrder("addd",$loc.$condi,"pin_id","","") as $resBanner){
            $City=$dbf->fetchSingle("city",'*',"city_id='$resBanner[city_id]'");
            $Pin=$dbf->fetchSingle("pincode",'*',"pincode_id='$resBanner[pin_id]'");
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $resBanner['add_title'];?></td>
                  <td> <img src="images/add/<?php echo $resBanner['add_image'];?>" width="50px" ></td>
                  <td> <?php echo $resBanner['add_link'];?>" </td>
                  <td><?= $City['city_name']?></td>
                  <td><?= $Pin['pincode']?></td>
 <?php if(in_array('23.2',$Job_assign)){?>
<td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?php echo $resBanner['add_id'];?>" ><i class="fa fa-edit"></i></a></td>
 <?php }?>
 <?php if(in_array('23.3',$Job_assign)){?>
<td> <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['add_id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a>
 <?php }?>              
                  </td>
                </tr>
                <?php $i++; } ?>
                
                
                
               
               
               
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>Brands Name</th>
                  <th>Images</th>
                  <th>url</th>
                  <th>City</th>
                  <th>Pin</th>
                  <?php if(in_array('23.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('23.3',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </tfoot>
              </table>
 
 
 
 
 <?php 
########################## EDIT  #############################
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editcountry'){

if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/add/".$fname1;
		
		$destination_path1="images/add/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/add/".$fname1);
		
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
	
	
  $id=$dbf->checkXssSqlInjection($_REQUEST['addid']);
  
	$add_name=$dbf->checkXssSqlInjection($_REQUEST['add_name']);
  $add_url=$dbf->checkXssSqlInjection($_REQUEST['add_url']);
    
  $country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
    $state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
    $city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
    $zcode = $dbf->checkXssSqlInjection($_REQUEST['zcode']);
    $position = $dbf->checkXssSqlInjection($_REQUEST['position']);


	
	if($fname1!=''){
    $string="add_title='$add_name', add_link='$add_url',country_id='$country_id',state_id='$state_id',city_id='$city_id',pin_id='$zcode',position='$position',add_image='$fname1', updated_date=NOW()";
	}else{
    $string="add_title='$add_name', add_link='$add_url',country_id='$country_id',state_id='$state_id',city_id='$city_id',pin_id='$zcode',position='$position', updated_date=NOW()";
		}
	$dbf->updateTable("addd",$string,"add_id='$id'");
	
	header('Location:add_management.php?editId='.$id);exit;
}
?>
 
              
<?php
$i=1;
foreach($dbf->fetchOrder("addd","","pin_id","","") as $resBanner){
?>
              <div class="modal fade" id="modal-default-edit<?php echo $resBanner['add_id'];?>">
              <form  action="" enctype="multipart/form-data" method="post"> 
              <input type="hidden" name="addid" value="<?php echo $resBanner['add_id'];?>"> 
          <div class="modal-dialog">
            <div class="modal-content">
              
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit </h4>
              </div>

              <div class="modal-body">
              <div class="col-md-6">
                <div class="form-group">
                <label>Enter Add Name</label>
                <input type="text" class="form-control" name="add_name" id="add_name" placeholder="Enter Add Name"
                 required  value="<?= $resBanner['add_title']?>"/>
                </div>
                </div>

                <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1"> Image</label>
                  <input type="file" class="form-control" id="img" name="img" >
                  <img src="images/add/<?= $resBanner['add_image']?>" alt="<?= $resBanner['add_title']?>" width="50">
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                <label>Enter Add Url</label>
                <input type="text" class="form-control" name="add_url" id="add_url" placeholder="url" required  value="<?= $resBanner['add_link']?>"/>
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                <label>Select Positon Of Banner</label>
                <select class="form-control" name="position" required>
                <option value="">~~Select Position~~</option>
                <option value="1" <?= ($resBanner['position']=='1')?"selected":""?>>Banner Side</option>
                <option value="2" <?= ($resBanner['position']=='2')?"selected":""?>>After Latest Product</option>
                <option value="3" <?= ($resBanner['position']=='3')?"selected":""?>>After Deals of the day</option>
                <option value="4" <?= ($resBanner['position']=='4')?"selected":""?>>After Trending Product</option>
                <option value="5" <?= ($resBanner['position']=='5')?"selected":""?>>Sidebar Image</option>
                </select>
                </div>
                </div>
      
<div class="row">
		<div class="col-md-3">
		<label class="" >Select Contry</label>
		<select class="form-control" name="country_id" onChange="UpGetState(this.value,<?= $resBanner['add_id']?>)" required>
                <option value="" >--Select Country--</option>
    			<?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
    			<option value="<?=$countryName['country_id']?>" <?= ($countryName['country_id']==$resBanner['country_id'])?"selected":""?>
          ><?=$countryName['country_name']?></option>
   			    <?php }?>
    			</select>
		</div>

		<div class="col-md-3">
		<label class="" >Select State</label>
       			 <select class="form-control" name="state_id" id="stateres<?= $resBanner['add_id']?>" onChange="upGetCity(this.value,<?= $resBanner['add_id']?>)" required>
    			 <option value="" >--Select State--</option>

           <?php  foreach($dbf->fetchOrder("state","Country_id ='$resBanner[country_id]'","state_id ASC","","") as $stateName){?>
    			<option value="<?=$stateName['state_id']?>" <?= ($stateName['state_id']==$resBanner['state_id'])?"selected":""?>><?=$stateName['state_name']?></option>
           <?php }?>
    			 </select>
		</div>

		<div class="col-md-3">
		<label class="" for="inlineFormCustomSelect">Select City</label>
     			 <select class="form-control" name="city_id" id="cityres<?= $resBanner['add_id']?>" required onchange="SelectOnPin(this.value,<?= $resBanner['add_id']?>)">
    			 <option value="" >--Select City--</option>
           <?php foreach($dbf->fetchOrder("city","state_id='$resBanner[state_id]'","city_id ASC","","") as $cityName){?>
    			<option value="<?=$cityName['city_id']?>" <?= ($cityName['city_id']==$resBanner['city_id'])?"selected":""?>><?=$cityName['city_name']?></option>
   			    <?php }?>
    			</select>
		</div>
		<div class="col-md-3">
		<label class="" for="inlineFormCustomSelect">Select Pin</label>
                <select class="uk-select" required="" name="zcode" id="zcode<?= $resBanner['add_id']?>" >
                  <option value="">--Select Pincode--</option>
                  <?php 
                  foreach ($dbf->fetchOrder("pincode","city_id='$resBanner[city_id]'","","","") as $Pincode) {?>
                    <option value="<?= $Pincode['pincode_id']?>" <?= ($Pincode['pincode_id']==$resBanner['pin_id'])?"selected":""?>><?= $Pincode['pincode']?></option>
                  <?php } ?>
                </select>
		</div>
	</div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary pull-left " value="editcountry" name="submit">Update</button>
                <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
              </div>

            </div>
            </div>

            <!-- /.modal-content -->
          </div>
          </form>
          <!-- /.modal-dialog -->
        </div>
              
<?php }?>             
              
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
    	function SelectOnPin(arg,Addid=''){
  var url="getAjax.php";
  $.post(url,{"choice":"changPin","value":arg},function(res){
 $('#zcode'+Addid).html(res);
// alert(res)
});
}

function UpGetState(country,Addid){
  var url="getAjax.php";
  $.post(url,{"choice":"getState","value":country},function(res){
 $('#stateres'+Addid).html(res);
// alert(res)
});
  
}

function  upGetCity(val,Addid) {
      //  alert(val);
    
//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getCity","value":val},function(res){
 $('#cityres'+Addid).html(res);
// alert(res)
});
  
  } 

</script>

   <?php include('footer.php')?>
   <script>
  function UpdateChangepin(arg){
  var url="getAjax.php";

  $.post(url,{"choice":"changPin","value":arg,'pin':"<?= $_POST['pin']?>"},function(res){
 $('#loc_selected_pin').html(res);
// alert(res)
});
}

UpdateChangepin(<?=$_POST['city']?>);
</script>