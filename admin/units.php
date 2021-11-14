<?php include('header.php')?>
<?php include('sidebar.php')?>
  
<?php 
########################## insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addcountry'){
$country=$dbf->checkXssSqlInjection($_REQUEST['country']);
	
$cntcountry=$dbf->countRows("units","unit_name='$country'");

if($cntcountry==0){
$string="unit_name='$country', created_date=NOW()";
$dbf->insertSet("units",$string);
header("Location:units.php?msg=success");
}else{	
header("Location:units.php?msg=exit");
}
}
?>
            
            
   
<?php
########################## DELETE  #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	
	$dbf->deleteFromTable("units","unit_id='$id'");
	header("Location:units.php");
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
        MEASURE MENT
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
           
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Unit Add Successfully</p>
</div>
 <?php }?>
 
 
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Unit Already Exit</p>
</div>
 <?php }?>
 
            <div class="box-header">
            <?php if(in_array('11.1',$Job_assign)){?>
              <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add </button></h3>
            <?php }?>
              <div class="modal fade" id="modal-default-add">
              
            <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add  </h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>Enter Unit Name</label>
                <input type="text" class="form-control" name="country" id="country" placeholder="Enter Unit Name" required />
                
                
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
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Slno.</th>
                  <th>Unit Name</th>
                  <?php if(in_array('11.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('11.3',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
                
                    <?php
					$i=1;
					 foreach($dbf->fetchOrder("units","","unit_id ASC","","") as $resBanner){
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $resBanner['unit_name'];?></td>
                  <?php if(in_array('11.2',$Job_assign)){?>
<td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?php echo $resBanner['unit_id'];?>" ><i class="fa fa-edit"></i></a></td>
                  <?php }?>
                  <?php if(in_array('11.3',$Job_assign)){?>
<td> <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['unit_id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a>
                  <?php }?>
                  </td>
                </tr>
                <?php $i++; } ?>
                
                
                
               
               
               
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>Unit Name</th>
                  <?php if(in_array('11.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('11.3',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </tfoot>
              </table>
 
 
 
 
 <?php 
########################## EDIT  #############################
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editcountry'){
	
	
	$id=$dbf->checkXssSqlInjection($_REQUEST['pagesId']);
	$country=trim($dbf->checkXssSqlInjection($_REQUEST['country']));
	
	
	
$cntcountry=$dbf->countRows("units","unit_name='$country' AND unit_id!='$id' ");

if($cntcountry==0){
$string="unit_name='$country', created_date=NOW()";
$dbf->updateTable("units",$string,"unit_id='$id'");
header("Location:units.php?msg=success");
}else{	
header("Location:units.php?msg=exit");
}
}
?>
	
	
              
<?php
$i=1;
foreach($dbf->fetchOrder("units","","unit_id ASC","","") as $resBanner){
?>
              <div class="modal fade" id="modal-default-edit<?php echo $resBanner['unit_id'];?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit </h4>
              </div>
              <div class="modal-body">
                
                <form  action="" method="post" enctype="multipart/form-data" name="frm" id="frm" >

		   <input type="hidden" class="form-control" name="pagesId"  value="<?php echo $resBanner['unit_id'];?>">
	<input type="text" class="form-control" name="country" id="country" value="<?=$resBanner['unit_name'];?>"/>

                
              </div>
              <div class="modal-footer">
                <button class="btn btn-default pull-left" type="submit" name="submit" value="editcountry">Update </button>
                
                </form>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
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
  
   <?php include('footer.php')?>
