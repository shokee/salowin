<?php include('header.php')?>
<?php include('sidebar.php')?>
  
<?php 
  ########################## insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='AddSlab'){
$frmamnt=$dbf->checkXssSqlInjection($_REQUEST['frmamnt']);
$toamnt=$dbf->checkXssSqlInjection($_REQUEST['toamnt']);
$comAmnt=$dbf->checkXssSqlInjection($_REQUEST['comAmnt']);

$cntshipping=$dbf->countRows("commsion_slab","frm_amnt<='$frmamnt' AND `to_amnt`>='$toamnt'");
if($cntshipping==0){
$string="frm_amnt='$frmamnt',to_amnt='$toamnt',dis_percent='$comAmnt'";
$dbf->insertSet("commsion_slab",$string);
header("Location:comision.php?msg=success");
}else{	
header("Location:comision.php?msg=exit");
}
}

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='UpdateSlab'){
$slab_id=$dbf->checkXssSqlInjection($_REQUEST['slab_id']);
$frmamnt=$dbf->checkXssSqlInjection($_REQUEST['frmamnt']);
$toamnt=$dbf->checkXssSqlInjection($_REQUEST['toamnt']);
$comAmnt=$dbf->checkXssSqlInjection($_REQUEST['comAmnt']);
$cntshipping=$dbf->countRows("commsion_slab","to_amfrm_amnt<='$frmamnt' AND `to_amnt`>='$toamnt' AND commsion_slab_id!='$slab_id'");
if($cntshipping==0){
$string="frm_amnt='$frmamnt',to_amnt='$toamnt',dis_percent='$comAmnt'";
$dbf->updateTable("commsion_slab",$string,"commsion_slab_id='$slab_id'");
header("Location:comision.php?msg=success_up");
}else{  
header("Location:comision.php?msg=exit");
}
}
?>

     
<?php

if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='UpdateStatus'){
  $status_id=$dbf->checkXssSqlInjection($_REQUEST['status_id']);
  $status=$dbf->checkXssSqlInjection($_REQUEST['status']);
  $dbf->updateTable("commsion_slab","status='$status'","commsion_slab_id='$status_id'");
  header("Location:comision.php");exit;
}
?>

 
                  
<script type="text/javascript">


function ChangeStatus(arg,arg1){
  if(arg1=='1'){
    var msg = "Are Sure To Active This Commission Slab?";
  }else{
     var msg = "Are Sure To Block This Commission Slab?";
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
       Commission Slab
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active"> Commission Slab</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
      
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>New Commission Slab Add Successfully</p>
</div>
 <?php }?>
 
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success_up'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>New Commission Slab Add Successfully</p>
</div>
 <?php }?>
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Commission Slab Already Exist</p>
</div>
 <?php }?>
 
 
            <div class="box-header">
            <?php if(in_array('29.1',$Job_assign)){?>
              <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add Slabs</button></h3>
            <?php }?>
              <div class="modal fade" id="modal-default-add">
              
            <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Commission Slab </h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>From Amount</label>
                <input type="text" class="form-control" name="frmamnt"  placeholder="Enter from Amount" required  id="from_price"   onkeyup="ComparePrice()"/>
                
                
                </div>
                 <div class="form-group">
                <label>To Amount</label>
                <input type="text" class="form-control" name="toamnt"  placeholder="Enter To Amount"  id="to_price" required onkeyup="ComparePrice()" />
                
                
                </div>
                <div class="form-group">
                <label>Commission  Amount In (%)</label>
                <input type="text" class="form-control" name="comAmnt"  placeholder="Enter Commission In %" required />
                
                
                </div>
                
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="AddSlab" name="submit" id="submit" >Add Slab</button>
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
                  <th>From Amount</th>
                  <th>To Amount</th>
                  <th>Commission In(%)</th>
                  <?php if(in_array('29.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('29.3',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
                
                    <?php
					$i=1;
					 foreach($dbf->fetchOrder("commsion_slab","","commsion_slab_id ","","") as $com_slab){
					?>
                <tr>
                  <td><?php echo $i++;?></td>
                  <td><?php echo number_format($com_slab['frm_amnt'],2);?></td>
                  <td><?php echo number_format($com_slab['to_amnt'],2);?></td>
                  <td><?php echo number_format($com_slab['dis_percent'],2);?></td>
                  <?php if(in_array('29.2',$Job_assign)){?>
                  <td>
                    <?php if($com_slab['status']=='0'){?>
                    <button class="btn btn-danger" type="button" onclick="ChangeStatus(<?= $com_slab['commsion_slab_id']?>,1)">Block</button>
                  <?php }else { ?>
                    <button class="btn btn-success" type="button" onclick="ChangeStatus(<?= $com_slab['commsion_slab_id']?>,0)">Active</button>
                  <?php }?>
                  </td>
                  <?php }?>
                  <?php if(in_array('29.3',$Job_assign)){?>
                  <td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?php echo $com_slab['commsion_slab_id'];?>" ><i class="fa fa-edit"></i></a></td>
                  <?php }?>
                </tr>
                <?php  } ?>
                
                
                
               
               
               
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>From Amount</th>
                  <th>To Amount</th>
                  <th>Commission In(%)</th>
                  <?php if(in_array('29.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('29.3',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                </tr>
                </tfoot>
              </table>
 
 
 
 
 
              
<?php

   foreach($dbf->fetchOrder("commsion_slab","","commsion_slab_id ","","") as $com_slab){
?>
             <div class="box-header">

              <div class="modal fade" id="modal-default-edit<?= $com_slab['commsion_slab_id']?>">
              
            <form  action="" method="post">  
              <input type="hidden" name="slab_id" value="<?= $com_slab['commsion_slab_id']?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Update Commission Slab </h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>From Amount</label>
                <input type="text" class="form-control" name="frmamnt"  placeholder="Enter from Amount" required  id="from_price<?= $com_slab['commsion_slab_id']?>"   onkeyup="ComparePrice<?= $com_slab['commsion_slab_id']?>()"/ autocomplete="off" value="<?= number_format($com_slab['frm_amnt'],2)?>">
                
                
                </div>
                 <div class="form-group">
                <label>To Amount</label>
                <input type="text" class="form-control" name="toamnt"  placeholder="Enter To Amount"  id="to_price<?= $com_slab['commsion_slab_id']?>" required onkeyup="ComparePrice<?= $com_slab['commsion_slab_id']?>()" autocomplete="off" value="<?= number_format($com_slab['to_amnt'],2)?>"/>
                
                
                </div>
                <div class="form-group">
                <label>Commission  Amount In (%)</label>
                <input type="text" class="form-control" name="comAmnt"  placeholder="Enter Commission In %" required autocomplete="off" value="<?= number_format($com_slab['dis_percent'],2)?>" />
                
                
                </div>
                
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="UpdateSlab" name="submit" id="submit<?= $com_slab['commsion_slab_id']?>" >Update Slab</button>
                <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
          <!-- /.modal-dialog -->
        </div>
            </div>
             
              <script type="text/javascript">
    function  ComparePrice<?= $com_slab['commsion_slab_id']?>() {

      var sale_price = document.getElementById('from_price<?= $com_slab['commsion_slab_id']?>').value;
      var reg_price = document.getElementById('to_price<?= $com_slab['commsion_slab_id']?>').value;
      var submit = document.getElementById('submit<?= $com_slab['commsion_slab_id']?>');
   if(parseFloat(sale_price)>parseFloat(reg_price) || parseFloat(sale_price)==parseFloat(reg_price)){
    submit.disabled  =true;
      }else{
    submit.disabled  =false;

      }
    }

  </script>
   
<?php }?>             
              
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
   <script type="text/javascript">
    function  ComparePrice() {
      var sale_price = document.getElementById('from_price').value;
      var reg_price = document.getElementById('to_price').value;
      var submit = document.getElementById('submit');
   if(parseFloat(sale_price)>parseFloat(reg_price) || parseFloat(sale_price)==parseFloat(reg_price)){
    submit.disabled  =true;
      }else{
    submit.disabled  =false;

      }
    }

  </script>
  
   <?php include('footer.php')?>
