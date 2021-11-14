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
        CMS
        <small>Contain Management Systeam</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">CMS</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
                    <div class="app-main__inner">
<?php
########################## DELETE BANNER #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
	$id=$dbf->checkXssSqlInjection($_REQUEST['id']);
	
	$resBanner=$dbf->fetchSingle("ecmember","*","id='$id'");	
	@unlink("images/cms".$resBanner['image']);
	@unlink("images/cms/thumb/".$resBanner['image']);
	
	$dbf->deleteFromTable("cmc","id='$id'");
	header("Location:cms-management.php");
}
?>

<script type="text/javascript">
function deleteRecord(id){
	$("#operation").val('delete');
	$("#id").val(id);
	var conf=confirm("Are you sure want to delete this Record");
	if(conf){
	   $("#frm_deleteBanner").submit();
	}
}
</script>


 	<div class="uk-card uk-card-body uk-card-default">
<!--   <a href="cms-add.php" class="btn btn-primary">Add Pages</a>
    <hr>-->
    
     <div class="box-body">
  <table id="example1" class="table table-bordered table-striped">
  <tr>
    <th>Slno.</th>
    <th>Name</th>
    <th>Image</th>
    <?php if(in_array('21.1',$Job_assign)){?>
    <th>Edit</th>
    <?php }?>
    <?php if(in_array('21.2',$Job_assign)){?>
    <th>Delete</th>
    <?php }?>
 </tr>

  
  	 <?php
	$i=1;
	 foreach($dbf->fetchOrder("cmc","","id ASC","","") as $resBanner){
	?>
      <tr>
    <td ><?php echo $i;?></td>
    <td><?php echo $resBanner['fullname'];?></td>
    <td>
    <?php 
	if($resBanner['image']!=""){?>
    <img src="images/cms/<?php echo $resBanner['image'];?> "   style="width:50px; " >
    <?php }else{?>
    <img src="images/default.png?> "  style="width:50px;"  >
<?php }?>
    
    </td>
    <?php if(in_array('21.1',$Job_assign)){?>
    <td>
      <?php if($resBanner['id']!='8'){?>
    <a href="cms-edit.php?editId=<?php echo $resBanner['id'];?>" class="uk-icon-button uk-button-primary" uk-icon="file-edit"></a>
    <?php }else{?>
    <a href="contactus.php?editId=<?php echo $resBanner['id'];?>" class="uk-icon-button uk-button-primary" uk-icon="file-edit"></a>
    <?php }?>
    </td>
    <?php }?>
    <?php if(in_array('21.2',$Job_assign)){?>
   <td><a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['id'];?>');" class="uk-icon-button uk-button-danger" uk-icon="trash"></a></td>
    <?php }?>
   </tr>
     <?php $i++; } ?>
     
     <tr>
    <th>Slno.</th>
    <th>Name</th>
    <th>Image</th>
    <?php if(in_array('21.1',$Job_assign)){?>
    <th>Edit</th>
    <?php }?>
    <?php if(in_array('21.2',$Job_assign)){?>
    <th>Delete</th>
    <?php }?>
 </tr>
</table>
</div>
   <form name="frm_deleteBanner" id="frm_deleteBanner" action="" method="post">
                    <input type="hidden" name="operation" id="operation" value="">
                    <input type="hidden" name="id" id="id" value="">
                  </form>
                  
                  
  </div>
    </div>
 </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>