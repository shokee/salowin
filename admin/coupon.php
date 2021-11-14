<?php include('header.php')?>
<?php include('sidebar.php')?>
  
<?php 
  ########################## insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addCoupon'){
$coname=$dbf->checkXssSqlInjection($_REQUEST['coname']);
$cocode=$dbf->checkXssSqlInjection($_REQUEST['cocode']);
$cotype=$dbf->checkXssSqlInjection($_REQUEST['cotype']);
$disvalue=$dbf->checkXssSqlInjection($_REQUEST['disvalue']);
$validate=$dbf->checkXssSqlInjection($_REQUEST['validate']);
$noofuse=$dbf->checkXssSqlInjection($_REQUEST['noofuse']);
$priceapplay = $dbf->checkXssSqlInjection($_REQUEST['priceapplay']);

$cntCode=$dbf->countRows("coupon_code","code='$cocode'");
if($cntCode==0){
$string="name='$coname',code='$cocode',discount_type='$cotype',discount_value='$disvalue',valid_uo_to='$validate',used_up_to='$noofuse',price_cart='$priceapplay',create_date=NOW()";
$dbf->insertSet("coupon_code",$string);
header("Location:coupon.php?msg=success");
}else{	
$error="Coupon Code Already Exist!!!";
}
}
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='UpdateCoupon'){
 $coupon_id=$dbf->checkXssSqlInjection($_REQUEST['coupon_id']);
$coname=$dbf->checkXssSqlInjection($_REQUEST['coname']);
$cocode=$dbf->checkXssSqlInjection($_REQUEST['cocode']);
$cotype=$dbf->checkXssSqlInjection($_REQUEST['cotype']);
$disvalue=$dbf->checkXssSqlInjection($_REQUEST['disvalue']);
$validate=$dbf->checkXssSqlInjection($_REQUEST['validate']);
$noofuse=$dbf->checkXssSqlInjection($_REQUEST['noofuse']);
$priceapplay = $dbf->checkXssSqlInjection($_REQUEST['priceapplay']);

$cntCode=$dbf->countRows("coupon_code","code='$cocode' AND coupon_code_id!='$coupon_id'");
if($cntCode==0){
$string="name='$coname',code='$cocode',discount_type='$cotype',discount_value='$disvalue',valid_uo_to='$validate',used_up_to='$noofuse',price_cart='$priceapplay',update_date=NOW()";
$dbf->updateTable("coupon_code",$string,"coupon_code_id='$coupon_id'");
header("Location:coupon.php?msg=success");
}else{  
$error="Coupon Code Already Exist!!!";
}
}
?>

     
<?php
########################## DELETE  #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $coupon_id=$dbf->checkXssSqlInjection($_REQUEST['coupon_id']);
	$dbf->deleteFromTable("coupon_code","coupon_code_id='$coupon_id'");
	header("Location:coupon.php");
}
?>

 <form name="frm_deleteBanner" id="frm_deleteBanner" action="" method="post">
 <input type="hidden" name="operation" id="operation" value="">
 <input type="hidden" name="coupon_id" id="coupon_id" value="">
 </form>
                  
<script type="text/javascript">
function deleteRecord(id){
	$("#operation").val('delete');
	$("#coupon_id").val(id);
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
        Coupon Code
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Coupon Code</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
      
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>New Coupon Add Successfully</p>
</div>
 <?php }?>
 
 
 <?php if(isset($error)){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p><?= $error?></p>
</div>
 <?php }?>
 
 
            <div class="box-header">
            <?php if(in_array('31.1',$Job_assign)){?>
              <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add Coupon</button></h3>
            <?php }?>
              <div class="modal fade" id="modal-default-add">
              
            <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Coupon </h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>Coupon Name</label>
                <input type="text" class="form-control" name="coname" id="" placeholder="Enter Coupon Name" required value="<?= $_POST['coname']?>" />
                </div>
                <div class="form-group">
                <label>Coupon Code</label>
                <input type="text" class="form-control" name="cocode" id="" placeholder="Enter Coupon Code" required value="<?= $_POST['cocode']?>"/>
                <?php if(isset($error)){?><span class="text-danger"><?= $error?></span><?php }?>
                
                
                </div>
                 <div class="form-group">
                <label>Discount Type</label>
                <select class="form-control" name="cotype" required="">
                  <option value="">--Coupon Type--</option>
                  <option value="1" <?php if($_POST['cotype']=='1'){echo "selected";}?>>Flat</option>
                  <option value="2"  <?php if($_POST['cotype']=='2'){echo "selected";}?>>%</option>

                </select>  
                
                </div>
                <div class="form-group">
                <label>Discount Value</label>
                <input type="text" class="form-control" name="disvalue" id="" placeholder="Enter Discount Value" required  value="<?= $_POST['disvalue']?>"/>              
                </div>

                  <div class="form-group">
                <label>Valid Up To</label>
                <input type="date" class="form-control" name="validate" id=""  required  value="<?= $_POST['validate']?>"/>              
                </div>

                   <div class="form-group">
                <label>No. Of Uses</label>
                <input type="number" class="form-control" name="noofuse" id="" placeholder="Enter No. Of Uses" required min="0" value="<?= $_POST['noofuse']?>"/>              
                </div>
                
                 <div class="form-group">
                <label>Applay Of Cart Price</label>
                <input type="text" class="form-control" name="priceapplay" id="" placeholder="EnterApplay Of Cart Price" required  value="<?= $_POST['priceapplay']?>"/>              
                </div>
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="addCoupon" name="submit">Add Coupon</button>
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
                  <th>Coupon Name</th>
                  <th>Coupon Code</th>
                  <th>Discount Type</th>
                  <th>Discount Value</th>
                  <th>Valid Upto</th>
                  <th>No. Of Uses</th>
                  <th>Applay Of Cart Price</th>
                  <?php if(in_array('31.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('31.3',$Job_assign)){?>
                  <th >Delete</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
                
                    <?php
					$i=1;
					 foreach($dbf->fetchOrder("coupon_code","","coupon_code_id DESC","","") as $Cop_code){
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $Cop_code['name'];?></td>
                    <td><?php echo $Cop_code['code'];?></td>
                      <td><?php if($Cop_code['discount_type']=='1'){ echo "Flat";}else{ echo "%";}?></td>
                      <td><?php echo $Cop_code['discount_value'];?></td>
                       <td><?php echo date('d-m-Y',strtotime($Cop_code['valid_uo_to']));?></td>
                         <td><?php echo $Cop_code['used_up_to'];?></td>
                          <td><?php echo $Cop_code['price_cart'];?></td>
                          <?php if(in_array('31.2',$Job_assign)){?>
                         <td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?php echo $Cop_code['coupon_code_id'];?>" ><i class="fa fa-edit"></i></a></td>
                          <?php }?>
                          <?php if(in_array('31.3',$Job_assign)){?>
                      <td> <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $Cop_code['coupon_code_id'];?>');" class="btn btn-social-icon btn-danger"  ><i class="fa fa-trash"></i></a>
                          <?php }?>
                  </td>
                </tr>


                    <div class="modal fade" id="modal-default-edit<?php echo $Cop_code['coupon_code_id'];?>">
              
            <form  action="" method="post">  
              <input type="hidden" name="coupon_id" value="<?= $Cop_code['coupon_code_id']?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Update Coupon </h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>Coupon Name</label>
                <input type="text" class="form-control" name="coname" id="" placeholder="Enter Coupon Name" required value="<?= $Cop_code['name']?>" />
                
                
                </div>
                <div class="form-group">
                <label>Coupon Code</label>
                <input type="text" class="form-control" name="cocode" id="" placeholder="Enter Coupon Code" required value="<?=$Cop_code['code']?>"/>
                <?php if(isset($error)){?><span class="text-danger"><?= $error?></span><?php }?>
                
                
                </div>
                 <div class="form-group">
                <label>Discount Type</label>
                <select class="form-control" name="cotype" required="">
                  <option value="">--Coupon Type--</option>
                  <option value="1" <?php if($Cop_code['discount_type']=='1'){echo "selected";}?>>Flat</option>
                  <option value="2"  <?php if($Cop_code['discount_type']=='2'){echo "selected";}?>>%</option>

                </select>  
                
                </div>
                <div class="form-group">
                <label>Discount Value</label>
                <input type="text" class="form-control" name="disvalue" id="" placeholder="Enter Discount Value" required  value="<?= $Cop_code['discount_value']?>"/>              
                </div>

                  <div class="form-group">
                <label>Valid Up To</label>
                <input type="date" class="form-control" name="validate" id=""  required  value="<?= $Cop_code['valid_uo_to']?>"/>              
                </div>

                   <div class="form-group">
                <label>No. Of Uses</label>
                <input type="number" class="form-control" name="noofuse" id="" placeholder="Enter No. Of Uses" required min="1" value="<?= $Cop_code['used_up_to']?>"/>              
                </div>
                    <div class="form-group">
                <label>Applay Of Cart Price</label>
                <input type="text" class="form-control" name="priceapplay" id="" placeholder="EnterApplay Of Cart Price" required  value="<?= $Cop_code['price_cart']?>"/>              
                </div>
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="UpdateCoupon" name="submit">Update Coupon</button>
                <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
          <!-- /.modal-dialog -->
        </div>
                <?php $i++; } ?>
                
                
                
               
               
               
                </tbody>
                
              </table>
 
 
 

            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>
