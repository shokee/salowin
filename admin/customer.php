<?php include('header.php')?>
 <?php include('sidebar.php')?>
<?php
########################## UPDATE STATUS  agent #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='update'){
   $id=$dbf->checkXssSqlInjection($_REQUEST['id']);
	$ststus=$dbf->checkXssSqlInjection($_REQUEST['ststus']);
	 $dbf->updateTable("user","status='$ststus'", "id='$id'");
	header("customer.php");
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
  
  
  
 
 
 
 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Customer
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Customer</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="row">
      	<div class="col-xs-12">
        	<div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sl No.</th>
                  <th>Customer Name</th>
                  <th>Contat No</th>
                  <th>Emails</th>
                  <th>Wallet</th>
                  <th>Image</th>
                  <?php if(in_array('12.1',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('12.2',$Job_assign)){?>
                  <th>Action</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
               
                 <?php
					$i=1;
					 foreach($dbf->fetchOrder("user","user_type='4'","id ASC","","") as $agent){
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $agent['full_name'];?></td>
                  <td><?php echo $agent['contact_no'];?></td>
                  <td><?php echo $agent['email'];?></td>
                  <td>&#8377;<?php echo $agent['wallet'];?></td>
                  <td>
                   <?php if($agent['profile_image']<>''){?>
        <img src="images/thumb/<?php echo $agent['profile_image'];?>" width="30px" height="30px">
        <?php }else{?>
         <img src="images/default.png?> " width="30px" height="30px"  >
        <?php }?>
                  </td>
                  <?php if(in_array('12.1',$Job_assign)){?>
                  <td>
                  <!--<?php if($agent['status']=='1'){?><button type="button" class="btn btn-success" onClick="upDateStatus(<?=$agent['id']?>,0)">Active</button><?php }else{?><button type="button" class="btn btn-danger" onClick="upDateStatus(<?=$agent['id']?>,1)">Block</button> <?php }?>-->
                  
                  <?php if($agent['status']=='1'){?><button type="button" class="btn btn-success"  uk-toggle="target: #modal-example<?=$agent['id']?>"  >Active</button><?php }else{?><button type="button" class="btn btn-danger"  uk-toggle="target: #modal-example<?=$agent['id']?>" >Block</button> <?php }?>
                 
                  <div id="modal-example<?=$agent['id']?>" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <form  action="" method="post"> 
        <h5>Name: <?php echo $agent['full_name'];?></h5>
        <input type="hidden" name="userid" value="<?=$agent['id']?>" />
        <textarea class="uk-textarea" placeholder="reasone" id="reasone" name="reasone"><?=$agent['reasone']?></textarea>
        <p></p>
        <select class="uk-select" name="status">
            <option value="1">Active</option>
            <option value="0">Block</option>
        </select>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="submit" value="editstatus" name="submit" >Save</button>
        </p>
        </form>
    </div>
</div>
                  </td>
                  <?php }?>
                  <?php if(in_array('12.2',$Job_assign)){?>
			            	<td> 
                    <a class="btn btn-social-icon btn-success" data-toggle="modal" data-target="#modal-default-view<?php echo $agent['id'];?>" ><i class="fa fa-eye"></i></a>
                  </td>
                  <?php }?>
                </tr>
                <?php $i++; } ?>
               
                </tbody>
                <tfoot>
                <tr>
                  <th>Sl No.</th>
                  <th>Customer Name</th>
                  <th>Contat No</th>
                  <th>Emails</th>
                  <th>Wallet</th>
                  <th>Image</th>
                  <?php if(in_array('12.1',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('12.2',$Job_assign)){?>
                  <th>Action</th>
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
           foreach($dbf->fetchOrder("user","user_type='4'","id ASC","","") as $agent){
          ?>
   <!-- Modal -->
      <div class="modal fade" id="modal-default-view<?php echo $agent['id'];?>" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">View Customer</h4>
        </div>
        <div class="modal-body">
              <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="hidden" class="form-control" id="agentid" name="agentid" value="<?php echo $agent['id'];?>">
                  <input type="text" class="form-control" id="name" name="name" value="<?php echo $agent['full_name'];?>" disabled>
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?php echo $agent['email'];?>" disabled>
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Contact No</label>
                  <input type="tel" class="form-control" id="contat" name="contact" value="<?php echo $agent['contact_no'];?>" disabled>
                </div>
                
                <img src="images/thumb/<?php echo $agent['profile_image'];?>" width="100px" height="100px">
                <div class="form-group">
              <label for="exampleInputEmail1">User Name</label>
                  <input type="text" class="form-control" id="accountno" name="accountno" value="<?php echo $agent['user_name'];?>" disabled>
            </div>
                <div class="form-group">
              <label for="exampleInputEmail1">Password</label>
                  <input type="text" class="form-control" id="accountno" name="accountno" value="<?php echo base64_decode(base64_decode($agent['password']));?>" disabled>
            </div>
            <div class="form-group">
              <?php $country=$dbf->fetchSingle("country",'*',"country_id='$agent[country_id]'");?>
              <label for="">Country</label>
              <input type="text" class="form-control" id="accountno" name="accountno" value="<?php echo $country['country_name'];?>" disabled>
            </div>
            <div class="form-group">
              <?php $state=$dbf->fetchSingle("state",'*',"state_id='$agent[state_id]'");?>
              <label for="">State</label>
              <input type="text" class="form-control" id="accountno" name="accountno" value="<?php echo $state['state_name'];?>" disabled>
            </div>
            <div class="form-group">
              <?php $city=$dbf->fetchSingle("city",'*',"city_id='$agent[city_id]'");?>
              <label for="">City</label>
              <input type="text" class="form-control" id="accountno" name="accountno" value="<?php echo $city['city_name'];?>" disabled>
            </div>
                
                 <div class="form-group">
                  <label for="exampleInputEmail1">Address 1</label>
                  <textarea class="form-control" name="address" id="address" disabled><?php echo $agent['address1'];?></textarea>
                </div>
                 <div class="form-group">
                  <label for="exampleInputEmail1">Address 2</label>
                  <textarea class="form-control" name="address" id="address" disabled><?php echo $agent['address2'];?></textarea>
                </div>
                
                
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
       
      </div>
      
    </div>
  </div>
  
  
  
  
   <?php 
########################## EDIT COUNTRY #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editstatus'){
	
	
	$id=$dbf->checkXssSqlInjection($_REQUEST['userid']);
	$reasone=trim($dbf->checkXssSqlInjection($_REQUEST['reasone']));
	$status=$dbf->checkXssSqlInjection($_REQUEST['status']);
  $string="reasone='$reasone', status='$status', created_date=NOW()";
  


	$dbf->updateTable("user",$string,"id='$id'");
  header("Location:customer.php?msg=successup");
	



}
?>
  
  <?php $i++; } ?>
   <?php include('footer.php')?>
