<?php include('header.php')?>
  <?php include('sidebar.php')?>
  
  <!-- UIkit CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/css/uikit.min.css" />

<!-- UIkit JS -->
<script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit-icons.min.js"></script>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Page Header
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid"> 
    

                    <div class="app-main__inner">
<?php
########################## DELETE BANNER #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
	$banner_id=$dbf->checkXssSqlInjection($_REQUEST['banner_id']);
	
	$resBanner=$dbf->fetchSingle("banner","*","banner_id='$banner_id'");	
	@unlink("images/banner/".$resBanner['banner_image']);
	@unlink("images/banner/thumb/".$resBanner['banner_image']);
	
	$dbf->deleteFromTable("banner","banner_id='$banner_id'");
	header("Location:banner-management.php");
}
if(isset($_REQUEST['action']) && $_REQUEST['action']=='Fillters'){
  $city=$dbf->checkXssSqlInjection($_REQUEST['city']);
  $pin=$dbf->checkXssSqlInjection($_REQUEST['pin']);
  $condi=" AND city_id='$city' AND pin_id='$pin'";
}else{
  $condi="";
}
?>

<script type="text/javascript">
function deleteRecord(banner_id){
	$("#operation").val('delete');
	$("#banner_id").val(banner_id);
	var conf=confirm("Are you sure want to delete this Record");
	if(conf){
	   $("#frm_deleteBanner").submit();
	}
}
</script>


<div class="app-main__inner">
 	<div class="uk-card uk-card-body uk-card-default">
   <?php if(in_array('22.1',$Job_assign)){?>
    <a href="add-banner.php" class="uk-button uk-button-primary uk-light">Add Banner</a>
   <?php }?>
    <hr>
    <?php if($_SESSION['usertype']=='1'){
              $loc="";

              ?>
    <form action="" method="post" style="display:none">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <select name="city" id="" class="form-control" onchange="UpdateChangepin(this.value)" required>
          <option value="">--Select City--</option>
          <?php  foreach($dbf->fetchOrder("city","","city_name ASC","","") as $stateName){?>
           <option value="<?=$stateName['city_id']?>" <?php if($Selected_city['city_id']==$stateName['city_id']){ echo"selected";}?>><?=$stateName['city_name']?></option>
           <?php }?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
        <select name="pin" class="form-control" id="loc_selected_pin" required>
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
     $loc = " city_id='$_SESSION[city]'";
     }?>
    <table class="uk-table uk-table-small uk-table-divider uk-table-striped">
  <tr>
    <th>Slno.</th>
    <th>Title</th>
    <th>Image</th>
    <th>City</th>
    <th>Pin</th>
    <th>Url</th>
    <?php if(in_array('22.2',$Job_assign)){?>
    <th>Delete</th>
    <?php }?>
  
  </tr>

  
  	 <?php
	$i=1;
	 foreach($dbf->fetchOrder("banner",$loc.$condi,"pin_id","","") as $resBanner){
    $City=$dbf->fetchSingle("city",'*',"city_id='$resBanner[city_id]'");
    $Pin=$dbf->fetchSingle("pincode",'*',"pincode_id='$resBanner[pin_id]'");
	?>
      <tr>
    <td ><?php echo $i;?></td>
    <td><?php echo $resBanner['banner_title'];?></td>
    <td><img src="images/banner/thumb/<?php echo $resBanner['banner_image'];?>" style="height:50px;"></td>
    <td><?= $City['city_name']?></td>
    <td><?= $Pin['pincode']?></td>
    <td><?= $resBanner['url']?></td>
    <?php if(in_array('22.2',$Job_assign)){?>
    <td><a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['banner_id'];?>');" class="uk-icon-button uk-button-danger" uk-icon="trash"></a></td>
    <?php }?>
    </tr>
     <?php $i++; } ?>
  
 
</table>

   <form name="frm_deleteBanner" id="frm_deleteBanner" action="" method="post">
                    <input type="hidden" name="operation" id="operation" value="">
                    <input type="hidden" name="banner_id" id="banner_id" value="">
                  </form>
                  
                  

    </div>
</div>

 </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include("footer.php") ?>
<script>
  function UpdateChangepin(arg){
  var url="getAjax.php";
  $.post(url,{"choice":"changPin","value":arg},function(res){
 $('#loc_selected_pin').html(res);
// alert(res)
});
}</script>