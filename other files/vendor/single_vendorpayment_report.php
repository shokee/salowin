<?php include('header.php')?>
<?php include('sidebar.php')?>
 
<?php 
$vendor=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;
$vendorid=$dbf->fetchSingle("user",'*',"id='$vendor'");
?>
 
  <?php 
  ########################## insert state #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='payment'){
	
$vend_id=$dbf->checkXssSqlInjection($_REQUEST['vend_id']);	
$date=($_REQUEST['date']);
$amount=($_REQUEST['amount']);
$pay_mod=$dbf->checkXssSqlInjection($_REQUEST['pay_mod']);
$remark=$dbf->checkXssSqlInjection($_REQUEST['remark']);


$string="date='$date',amount='$amount', payment_mode ='$pay_mod', remark ='$remark', vendor_id ='$vend_id' ";
$dbf->insertSet("payment_vendor",$string);
header("Location:single_vendorpayment_report.php?editId=$vendorid[id]");
}
?>

            
   
<?php
########################## DELETE CITY #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
	
	$dbf->deleteFromTable("city","city_id='$id'");
	header("Location:city.php");
}
?>
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


  <form name="frm_deleteBanner" id="frm_deleteBanner" action="" method="post">
  <input type="hidden" name="operation" id="operation" value="">
  <input type="hidden" name="city_id" id="country_id" value="">
  </form>
  
  



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Payment
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Payment</li>
      </ol>
    </section>
    

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
      
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Payment Add Successfully</p>
</div>
<?php }?>
 





 
 
            <div class="box-header">
             <!-- <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Make Payment</button></h3>-->
              
              <!--<div class="modal fade" id="modal-default-add">
            <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Payment </h4>
              </div>
              <div class="modal-body">
              
              <input type="text" class="form-control" name="vend_id" value="<?= $vendorid['id']?>">
              
               <div class="form-group">
             <label class="uk-margin-top" for="inlineFormCustomSelect">Select Date</label>
      		    <input type="date" class="datepicker-inline" name="date">
                </div>
                
                
                <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form-control" name="amount">
                </div>
              
                <div class="form-group">
                <label>Payment Mode</label>
                <select class="form-control" name="pay_mod">
                	<option value="1">Cash</option>
                    <option value="2">Cheqe</option>
                    <option value="3">Online Payment</option>
                </select>
                </div>
                
                  <div class="form-group">
                <label>Remark</label>
                <input type="text" class="form-control" name="remark">
                </div>
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="payment" name="submit">Submit </button>
              <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
          
          </form>
        </div>-->
        
            </div>
            
            
            
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Slno.</th>
                  <th> Date</th>
                  <th>Amount</th>
                  <th>Payment Mode</th>
                  <th>Remark</th>
                  <!--<th>Edit</th>
                  <th>Delete</th>-->
                </tr>
                </thead>
                <tbody>
                
          <?php
		  $i=1;
		  foreach($dbf->fetchOrder("payment_vendor","vendor_id='$profileuserid'","payment_vendor_id DESC","","") as $resBanner){
		  ?>
          <tr>
          <td><?php echo $i;?></td>
          <td>  <?= date('d.m.Y',strtotime($resBanner['date']))?> </td>
          <td>Rs. <?php echo number_format("$resBanner[amount]",2); ?>/-</td>
          <td>
          	 <?php
if ($resBanner['payment_mode'] =="1") {
    echo "Cash";
} elseif ($resBanner['payment_mode'] == "2") {
    echo "Cheqe";
} else {
    echo "online Payment";
}
?> 
          </td>
          <td><?=$resBanner['remark']?></td>
          <!--<td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?//=$resBanner['payment_vendor_id']?>" ><i class="fa fa-edit"></i></a></td>
          <td> <a href="javascript:void(0);" onClick="deleteRecord('<?php// echo $resBanner['city_id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a></td>-->
          </tr>
          <?php $i++; } ?>
          
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th> Date</th>
                  <th>Amount</th>
                  <th>Payment Mode</th>
                  <th>Remark</th>
                  <!--<th>Edit</th>
                  <th>Delete</th>-->
                </tr>
                </tfoot>
              </table>
              
              
              
              
    <?php 
########################## EDIT STATE #############################
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editcity'){
	
	
	$pagesId=($_REQUEST['pagesId']);
	$amount=($_REQUEST['amount']);
	$pmode=($_REQUEST['pmode']);
	$remark=($_REQUEST['remark']);
	$string="amount='$amount', payment_mode='$pmode', remark='$remark' ";
	
	$dbf->updateTable("payment_vendor",$string,"payment_vendor_id='$pagesId'");
	
header("Location:single_vendorpayment_report.php?editId=$vendorid[id]");
}
?>          
              
              
              
 <?php
$i=1;
foreach($dbf->fetchOrder("payment_vendor","","payment_vendor_id ASC","","") as $resBanner){
?>
<div class="modal fade" id="modal-default-edit<?= $resBanner['payment_vendor_id']?>">
    <div class="modal-dialog">
       <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Payment </h4>
              </div>
              <div class="modal-body">
               <div class="form-group">
               <input type="hidden" class="form-control" name="pagesId" value="<?=$resBanner['payment_vendor_id']?>">
               
             <label class="uk-margin-top" for="inlineFormCustomSelect">Select Date</label>
      		    <input type="test" class="form-control" name="amount" value="<?= date('d.m.Y',strtotime($resBanner['date']))?>" readonly>
                </div>
                
                
                <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form-control" name="amount" value="<?php echo number_format("$resBanner[amount]",2); ?>">
                </div>
              
                <div class="form-group">
                <label>Payment Mode</label>
                <select class="form-control" name="pmode">
                	<option value="1"  <?php if($resBanner['payment_mode']==1){ echo"selected";}?> >cash</option>
                    <option value="2"  <?php if($resBanner['payment_mode']==2){ echo"selected";}?> >check</option>
                    <option value="3"  <?php if($resBanner['payment_mode']==3){ echo"selected";}?>>online Payment</option>
                </select>
                
                
                
                 
                </div>
                
                  <div class="form-group">
                <label>Remark</label>
                <input type="text" class="form-control" name="remark" value="<?=$resBanner['remark']?>">
                </div>
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="editcity" name="submit" id="submit">Submit </button>
              <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
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
