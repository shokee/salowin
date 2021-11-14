<?php include('header.php')?>
<?php include('sidebar.php')?>
  
  <style>
  /* ul{
    list-style-type:none;
  } */
  
  </style>
  <?php 
  
  ########################## Insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addagent'){
	
	$name=$dbf->checkXssSqlInjection($_REQUEST['name']);
	$email=$dbf->checkXssSqlInjection($_REQUEST['email']);
	$contact=$dbf->checkXssSqlInjection($_REQUEST['contact']);
	$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
	$city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
	$username=$dbf->checkXssSqlInjection($_REQUEST['username']);
	$password=base64_encode(base64_encode($_REQUEST['password']));
	
	$cntcontact=$dbf->countRows("user","contact_no='$contact'");
$cntemail=$dbf->countRows("user","email='$email'");
$cntuser=$dbf->countRows("user","user_name='$username'");

if($cntcontact!=0){
	header("Location:sub_admin.php?msg=numexit");
	}elseif($cntemail!=0){
	header("Location:sub_admin.php?msg=mailexit");
	}elseif($cntuser!=0){
	header("Location:sub_admin.php?msg=userexit");	
	}else{
	
	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/".$fname1;
		
		$destination_path1="images/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/".$fname1);
		
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

	
	$string="full_name='$name', email='$email', contact_no='$contact', profile_image='$fname1', country_id='$country_id', state_id='$state_id', city_id='$city_id', user_name='$username', password='$password', user_type='2', status='0', created_date=NOW()";
	$dbf->insertSet("user",$string);
	
	header("Location:sub_admin.php?msg=success");
}
}
?>


<?php
########################## UPDATE STATUS  agent #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='update'){
   $id=$dbf->checkXssSqlInjection($_REQUEST['id']);
	$ststus=$dbf->checkXssSqlInjection($_REQUEST['ststus']);
	 $dbf->updateTable("user","status='$ststus'", "id='$id'");
	header("sub_admin.php");
}
?>
 
<script type="text/javascript">
function upDateStatus(id,id1){
	//alert(id)
	if(id1=='1'){
		var msg ="Are you sure want to active this Record";
		}else{
			var msg ="Are you sure want to block this Record";
			}
	
	$("#status").val(id1);
	$("#id").val(id);
	var conf=confirm(msg);
	if(conf){
	   $("#frm_update").submit();
	}
}
</script>


<form name="frm_deleteBanner" id="frm_update" action="" method="post">
  <input type="hidden" name="operation" id="operation" value="update">
  <input type="hidden" name="id" id="id" value="">
  <input type="hidden" name="ststus" id="status" value="">
  </form>
  
  
  
  
  
  
  
  
  <?php
########################## DELETE agent #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
	$banner_id=$dbf->checkXssSqlInjection($_REQUEST['banner_id']);

	$resBanner=$dbf->fetchSingle("user","*","id='$banner_id'");	
	@unlink("images/".$resBanner['profile_image']);
	@unlink("images/thumb/".$resBanner['profile_image']);
	
	$dbf->deleteFromTable("user","id='$banner_id'");
	
	header("Location:sub_admin.php");
}
?>

<script type="text/javascript">
function deleteRecord(banner_id){
		$("#operation1").val('delete');
	$("#banner_id").val(banner_id);
	var conf=confirm("Are you sure want to delete this Record");
	if(conf){
	   $("#frm_deleteBanner").submit();
	}
}
</script>


 <form name="frm_deleteBanner" id="frm_deleteBanner" action="" method="post">
 <input type="hidden" name="operation" id="operation1" value="">
 <input type="hidden" name="banner_id" id="banner_id" value="">
 </form>
 
 
 <?php 
 ########################## update agent #############################
		if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editagent'){
			
	$agentid=$_REQUEST['agentid'];
	$name=$dbf->checkXssSqlInjection($_REQUEST['name']);
	$email=$dbf->checkXssSqlInjection($_REQUEST['email']);
	$contact=$dbf->checkXssSqlInjection($_REQUEST['contact']);
	$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
	$city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
	$username=$dbf->checkXssSqlInjection($_REQUEST['username']);
	$password=base64_encode(base64_encode($_REQUEST['password']));
	
	$cntcontact=$dbf->countRows("user","contact_no='$contact' AND id!='$agentid'");
	$cntemail=$dbf->countRows("user","email='$email' AND id!='$agentid'");
	$cntuser=$dbf->countRows("user","user_name='$username' AND id!='$agentid'");

	if($cntcontact!=0){
	header("Location:sub_admin.php?msg=numexit");
	}elseif($cntemail!=0){
	header("Location:sub_admin.php?msg=mailexit");
	}elseif($cntuser!=0){
	header("Location:sub_admin.php?msg=userexit");	
	}else{
	
		if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/".$fname1;
		
		$destination_path1="images/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/".$fname1);
		
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
	
	
	if($fname1!=''){
	$string="full_name='$name', email='$email', contact_no='$contact', profile_image='$fname1', country_id='$country_id', state_id='$state_id', city_id='$city_id', user_name='$username', password='$password', user_type='2', created_date=NOW()";
	}else{
		$string="full_name='$name', email='$email', contact_no='$contact', country_id='$country_id', state_id='$state_id', city_id='$city_id', user_name='$username', password='$password', user_type='2',created_date=NOW()";
		}
	$dbf->updateTable("user",$string,"id='$agentid'");
	
	header('Location:sub_admin.php?editId='.$pagesId);exit;
}
}
?>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sub Admin
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dash Board</a></li>
        <li class="active">Sub Admin</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="row">
      	<div class="col-xs-12">
        	<div class="box">
            
            
           
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Subadmin Add Successfully</p>
</div>
<?php }?>
 
 
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='numexit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Contact No  Already Exit</p>
</div>
<?php }?> 

<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='mailexit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Email   Already Exit</p>
</div>
<?php }?> 

<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='userexit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Username   Already Exit</p>
</div>
<?php }?>


            <div class="box-header">
            <?php if(in_array('14.1',$Job_assign)){?>
              <h3 class="box-title"><button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal">Add Sub Admin</button></h3>
            <?php }?>
               <!-- Modal -->
			<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      <form action="" enctype="multipart/form-data" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Sub Admin</h4>
        </div>
        <div class="modal-body">
          		<div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Contact No</label>
                  <input type="tel" class="form-control" id="contat" name="contact" placeholder="contact no" required>
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Profile Image</label>
                  <input type="file" class="form-control" id="img" name="img" required>
                </div>
                 
                 <div class="form-group">
                <label class="" for="inlineFormCustomSelect">Select Country</label>
      		    <select class="form-control" name="country_id" onChange=" GetState(this.value)" required>
                <option value="" >--Select Country--</option>
    			<?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
    			<option value="<?=$countryName['country_id']?>" ><?=$countryName['country_name']?></option>
   			    <?php }?>
    			</select>
                </div>
            	 <div class="form-group">
                <label class="" >Select State</label>
       			 <select class="form-control" name="state_id" id="stateres" onChange="GetCity(this.value)" required>
    			 <option value="" >--Select State--</option>
    			 </select>
                </div>
                 <div class="form-group">
                 <label class="" for="inlineFormCustomSelect">Select City</label>
     			 <select class="form-control" name="city_id" id="cityres" required>
    			 <option value="" >--Select City--</option>
    			</select>
                </div>
                
                 <div class="form-group">
                  <label for="exampleInputEmail1">Username</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                </div>
                
                
                
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit" id="submit" name="submit" value="addagent" >Submit</button> 
          
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>


            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped table-responsive" >
                <thead>
                <tr>
                  <th>Sl No.</th>
                  <th>Employee Name</th>
                  <th>Contat No</th>
                  <th>Emails</th>
                  <th>City</th>
                  <th>Image</th>
                  <?php if(in_array('14.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('14.3',$Job_assign)){?>
                  <th>Action</th>
                  <?php }?>
                  <?php if(in_array('14.4',$Job_assign)){?>
                  <th>Role</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
               
                 <?php
                  if($_SESSION['usertype']=='1'){
                       $loc="";
                      }else{    
                        $loc = " AND city_id='$_SESSION[city]'";
                    }
					$i=1;
					 foreach($dbf->fetchOrder("user","user_type='2' $loc","id ASC","","") as $agent){
					 $city = $dbf->fetchSingle("city",'*',"city_id='$agent[city_id]'");
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $agent['full_name'];?></td>
                  <td><?php echo $agent['contact_no'];?></td>
                  <td><?php echo $agent['email'];?></td>
                  <td><?php echo $city['city_name'];?></td>
                  <td><img src="images/thumb/<?php echo $agent['profile_image'];?>" width="30px" height="30px"></td>
                  <?php if(in_array('14.2',$Job_assign)){?>
                  <td>
                  <?php if($agent['status']=='1'){?><button type="button" class="btn btn-success" onClick="upDateStatus(<?=$agent['id']?>,0)">Active</button><?php }else{?><button type="button" class="btn btn-danger" onClick="upDateStatus(<?=$agent['id']?>,1)">Block</button> <?php }?>
                  </td>
                  <?php }?>
                  <?php if(in_array('14.3',$Job_assign)){?>
			          	<td> 
                     <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?php echo $agent['id'];?>" ><i class="fa fa-edit"></i></a>   
                      <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $agent['id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a>
                  </td>
                  <?php }?>
                  <?php if(in_array('14.4',$Job_assign)){?>
                  <td><button class="btn btn-primary" onclick="AllRoles(<?= $agent['id']?>)">Role</button></td>
                  <?php }?>
                </tr>
                <?php $i++; } ?>
               
                </tbody>
                <tfoot>
                <tr>
                  <th>Sl No.</th>
                  <th>Employee Name</th>
                  <th>Contat No</th>
                  <th>Emails</th>
                  <th>City</th>
                  <th>Image</th>
                  <?php if(in_array('14.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('14.3',$Job_assign)){?>
                  <th>Action</th>
                  <?php }?>
                  <?php if(in_array('14.4',$Job_assign)){?>
                  <th>Role</th>
                  <?php }?>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
  
   <?php
					$i=1;
					 foreach($dbf->fetchOrder("user","user_type='2'","id ASC","","") as $agent){
					?>
   <!-- Modal -->
			<div class="modal fade" id="modal-default-edit<?php echo $agent['id'];?>" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      <form action="" enctype="multipart/form-data" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Agent</h4>
        </div>
        <div class="modal-body">
          		<div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="hidden" class="form-control" id="agentid" name="agentid" value="<?php echo $agent['id'];?>">
                  <input type="text" class="form-control" id="name" name="name" value="<?php echo $agent['full_name'];?>">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?php echo $agent['email'];?>">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Contact No</label>
                  <input type="tel" class="form-control" id="contat" name="contact" value="<?php echo $agent['contact_no'];?>">
                </div>
                
            <div class="form-group">
                  <label for="exampleInputEmail1">Profile Image</label>
                  <input type="file" class="form-control" id="img" name="img" >
                </div>
                <img src="images/thumb/<?php echo $agent['profile_image'];?>" width="30px" height="30px">
                <div class="form-group">
           <label>Select City</label>
           <select class="form-control" name="country_id">
		   <?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $DirName){?>
           <option value="<?=$DirName['city_id']?>" <?php if($DirName['country_id']==$agent['country_id']){ echo"selected";}?>><?=$DirName['country_name']?></option>
           <?php }?>
           </select>
           </div>
           <div class="form-group">
           <label>Select City</label>
           <select class="form-control" name="state_id">
		   <?php  foreach($dbf->fetchOrder("state","","state_id ASC","","") as $DirName){?>
           <option value="<?=$DirName['state_id']?>" <?php if($DirName['state_id']==$agent['state_id']){ echo"selected";}?>><?=$DirName['state_name']?></option>
           <?php }?>
           </select>
           </div>
                <div class="form-group">
           <label>Select City</label>
           <select class="form-control" name="city_id">
		   <?php  foreach($dbf->fetchOrder("city","","city_id ASC","","") as $DirName){?>
           <option value="<?=$DirName['city_id']?>" <?php if($DirName['city_id']==$agent['city_id']){ echo"selected";}?>><?=$DirName['city_name']?></option>
           <?php }?>
           </select>
           </div>
           
<div class="form-group">
              <label for="exampleInputEmail1">Username</label>
                  <input type="text" class="form-control" id="username" name="username" value="<?php echo $agent['user_name'];?>">
            </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Password</label>
                  <input type="text" class="form-control" name="password" id="password" value="<?php echo base64_decode(base64_decode($agent['password']));?>"  >
                </div>
                
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit" id="submit" name="submit" value="editagent" >Submit</button> 
          
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>
  
  <?php $i++; } ?>
  
   <?php include('footer.php')?>
<!-- Modal -->
<div id="MyRole" class="modal fade" role="dialog" >
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content"  style="width:500px;">
      <div class="modal-header"  >
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Role</h4>
      </div>
      <div class="modal-body" id="RolesBySubadmin">
  
      </div>
      <div class="modal-footer" id="SubmiBtn">
     
      </div>
    </div>

  </div>
</div>
<script>
function UpdateRole(id){
  var Array_of_roles = [];
  $('.jobs:checked').each(function(){
    Array_of_roles.push($(this).val());
        });
$.post('getAjax.php',{'choice':"UpdateRole","id":id,"jobs":Array_of_roles},function(res){
$('#JobsResp').text('Jobs Updated Successfully.');
});

}

function AllRoles(id){

  $.post('getAjax.php',{'choice':"GetAllRoles","id":id},function(res){
    res = res.split('!next!');
    $('#RolesBySubadmin').html(res[0]);
    $('#SubmiBtn').html(res[1]);
    $('#MyRole').modal('show');
  });

}

</script>
<!-- //GetAllRoles -->