<?php include('header.php')?>
<?php include('sidebar.php')?>
  
<?php 
  ########################## insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addShipping'){
$sname=$dbf->checkXssSqlInjection($_REQUEST['sname']);
$scharge=$dbf->checkXssSqlInjection($_REQUEST['scharge']);
$cntshipping=$dbf->countRows("shipping","name='$sname'");
if($cntshipping==0){
$string="name='$sname',price='$scharge'";
$dbf->insertSet("shipping",$string);
header("Location:shiping_char.php?msg=success");
}else{	
header("Location:shiping_char.php?msg=exit");
}
}

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='UpdateShipping'){
$shipping_id = $dbf->checkXssSqlInjection($_REQUEST['shipping_id']);
$sname=$dbf->checkXssSqlInjection($_REQUEST['sname']);
$scharge=$dbf->checkXssSqlInjection($_REQUEST['scharge']);
$cntshipping=$dbf->countRows("shipping","name='$sname' AND shipping_id!='$shipping_id'");
if($cntshipping==0){
$string="name='$sname',price='$scharge'";
$dbf->updateTable("shipping",$string,"shipping_id='$shipping_id'");
header("Location:shiping_char.php?msg=success");
}else{  
header("Location:shiping_char.php?msg=exit");
}
}
?>

     
<?php

// if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='UpdateStatus'){
//   $status_id=$dbf->checkXssSqlInjection($_REQUEST['status_id']);
//   $status=$dbf->checkXssSqlInjection($_REQUEST['status']);
//   $dbf->updateTable("shipping","status='$status'","shipping_id='$status_id'");
//   header("Location:shiping_char.php");
// }
?>

 
                  
<script type="text/javascript">


function ChangeStatus(arg,arg1){
  if(arg1=='1'){
    var msg = "Are Sure To Active This Shipping?";
  }else{
     var msg = "Are Sure To Block This Shipping?";
  }
    var conf=confirm(msg);
    $("#status_id").val(arg);
  $("#status").val(arg1);
  if(conf){
     $("#frm_change").submit();
  }
}
</script>

 <form name="frm_deleteBanner" id="frm_change" action="" method="post">
 <input type="hidden" name="operation" value="UpdateStatus">
 <input type="hidden" name="status_id" id="status_id" value="">
  <input type="hidden" name="status" id="status" value="">
 </form>



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Shiping Charge
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Sipping Charge</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
      
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Shipping Add Successfully</p>
</div>
 <?php }?>
 
 
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Shipping Already Exit</p>
</div>
 <?php }?>
 
 
            <div class="box-header">
            <?php if(in_array('28.1',$Job_assign)){?>
              <!--<h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add Shipping</button></h3>-->
            <?php }?>
              <div class="modal fade" id="modal-default-add">
              
            <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Shipping </h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>Enter Shipping Name</label>
                <input type="text" class="form-control" name="sname"  placeholder="Enter Shipping Name" required />
                
                
                </div>
                 <div class="form-group">
                <label>Enter Shipping Price</label>
                <input type="text" class="form-control" name="scharge"  placeholder="Enter Shipping Price" required />
                
                
                </div>
                
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="addShipping" name="submit">Add Shipping</button>
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
                  <th>Shipping Name</th>
                  <th>Shipping Price</th>
                  <?php if(in_array('28.2',$Job_assign)){?>
                  <!--<th>Status</th>-->
                  <?php }?>
                  <?php if(in_array('28.3',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                 
                </tr>
                </thead>
                <tbody>
                
                    <?php
					$i=1;
					 foreach($dbf->fetchOrder("shipping","","shipping_id DESC","","") as $shiping){
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $shiping['name'];?></td>
                  <td>&#8377; <?php echo $shiping['price'];?></td>
                  <?php if(in_array('28.2',$Job_assign)){?>
                  <!--<td>-->
                  <!--  <?php if($shiping['status']=='0'){?>-->
                  <!--  <button class="btn btn-danger" type="button" onclick="ChangeStatus(<?= $shiping['shipping_id']?>,1)">Block</button>-->
                  <!--<?php }else { ?>-->
                  <!--  <button class="btn btn-success" type="button" onclick="ChangeStatus(<?= $shiping['shipping_id']?>,0)">Active</button>-->
                  <!--<?php }?>-->
                  <!--</td>-->
                  <?php }?>
                  <?php if(in_array('28.3',$Job_assign)){?>
                 <td>
                 <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?php echo $shiping['shipping_id'];?>" ><i class="fa fa-edit"></i></a>
                 </td>
                  <?php }?>
                </tr>
                <?php $i++; } ?>
                
                
                
               
               
               
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>Shipping Name</th>
                  <th>Shipping Price</th>
                  <?php if(in_array('28.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('28.3',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                 
                </tr>
                </tfoot>
              </table>
 
 
 
 
 <?php 
########################## EDIT COUNTRY #############################
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editcountry'){
	
	
	$id=$dbf->checkXssSqlInjection($_REQUEST['pagesId']);
	$country=$dbf->checkXssSqlInjection($_REQUEST['country']);
	$string="country_name='$country', created_date=NOW()";
	
	$dbf->updateTable("country",$string,"country_id='$id'");
	
	header('Location:country.php?editId='.$id);exit;
}
?>
 
              
<?php

   foreach($dbf->fetchOrder("shipping","","shipping_id DESC","","") as $shiping){
?>
              <div class="modal fade" id="modal-default-edit<?php echo $shiping['shipping_id'];?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Shipping</h4>
              </div>
              <form action="" method="post">
                <input type="hidden" name="shipping_id" value="<?= $shiping['shipping_id'] ?>">
              <div class="modal-body">
                <div class="form-group">
                <label>Enter Shipping Name</label>
                <input type="text" class="form-control" name="sname"   placeholder="Enter Shipping Name" required value="<?= $shiping['name']?>" />
                
                
                </div>
                 <div class="form-group">
                <label>Enter Shipping Price</label>
                <input type="text" class="form-control" name="scharge"  placeholder="Enter Shipping Price" required  value="<?= $shiping['price'] ?>" />
                
                
                </div>
                
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="UpdateShipping" name="submit">Update Shipping</button>
                <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                
              </div>
            </form>
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
