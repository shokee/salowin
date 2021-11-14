<?php include('header.php')?>
<?php include('sidebar.php')?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <?php
########################## UPDATE STATUS  agent #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='update'){
   $id=$dbf->checkXssSqlInjection($_REQUEST['id']);
	$ststus=$dbf->checkXssSqlInjection($_REQUEST['ststus']);
	 $dbf->updateTable("user","status='$ststus'", "id='$id'");
	header("Location:delivery_boy.php");
}
$city="";
if(isset($_REQUEST['operations']) && $_REQUEST['operations']=='Fillter'){
$city=$_POST['city'];
$condi = " AND city_id='$city'";
}else{
  $condi = "";
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
########################## DELETE  #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
	$banner_id=$dbf->checkXssSqlInjection($_REQUEST['banner_id']);

	$resBanner=$dbf->fetchSingle("user","*","id='$banner_id'");	
	$product=$dbf->fetchSingle("product","*","vendor_id='$banner_id'");
	
	@unlink("images/fsideId/".$resBanner['id_proof_fside']);
    @unlink("images/fsideId/thumb/".$resBanner['id_proof_fside']);
    
	@unlink("images/bsideId/".$resBanner['id_proof_bside']);
    @unlink("images/bsideId/thumb/".$resBanner['id_proof_bside']);
    
	@unlink("images/frc/".$resBanner['doc_rc']);
    @unlink("images/frc/thumb/".$resBanner['doc_rc']);

    @unlink("images/brc/".$resBanner['doc_lic']);
    @unlink("images/brc/thumb/".$resBanner['doc_lic']);

    @unlink("images/doc_ins/".$resBanner['doc_inc']);
    @unlink("images/doc_ins/thumb/".$resBanner['doc_inc']);

    @unlink("images/dboy/".$resBanner['profile_image']);
    @unlink("images/dboy/thumb/".$resBanner['profile_image']);
	$dbf->deleteFromTable("user","id='$banner_id'");

	
	header("Location:delivery_boy.php");
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
 
 
 
 
 
 




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Delivery Boy
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> DashBoard</a></li>
        <li class="active">Delivery Boy</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="row">
      	<div class="col-xs-12">
        	<div class="box">
           
            <div class="box-header">
            <?php if(in_array('33.1',$Job_assign)){?>
              <h3 class="box-title"><a class="btn btn-info " href="add_delivery_boy.php">Add  Delivery Boy</a></h3>
            <?php }?>
               <!-- Modal -->
		


            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <form action="" method="post">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <select name="city" id="" class="form-control select2">
                      <option value="">~~Select City~~</option>
                      <?php foreach($dbf->fetchOrder("city","","city_name","","") as $cityName){?>
    			                  <option value="<?=$cityName['city_id']?>" <?= ($city==$cityName['city_id'])?"selected":""?>><?=$cityName['city_name']?></option>
   			              <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                <div class="form-group">
                <button class="btn btn-success" name="operations" value="Fillter">Fillter</button>
                <a href="" class="btn btn-default">Refresh</a>
                </div>
                </div>
              </div>
            </form>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                 <th>Sl No.</th>
                 <th>Profile</th>
                  <th>Name</th>
                  <th>Contat No</th>
                  <th>Emails</th>
                  <th>Select City</th>
                  <?php if(in_array('33.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('33.3',$Job_assign)){?>
                  <th>Action</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
               
                 <?php
           if($_SESSION['usertype']=='1'){
            $locCond="";
          }else{    
            $locCond="city_id='$_SESSION[city]' AND ";
          }
          


					$i=1;
					 foreach($dbf->fetchOrder("user",$locCond."user_type='5' $condi","id ASC","","") as $agent){
                        $city=$dbf->fetchSingle("city",'city_name',"city_id='$agent[city_id]'");
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td> <a data-toggle="modal" data-target="#profile<?= $agent['id'] ?>"><img src="images/dboy/thumb/<?php echo $agent['profile_image'];?>" width="30px" height="30px"></a>

                  <div id="profile<?= $agent['id'] ?>" class="modal fade" role="dialog">
  				        	<div class="modal-dialog">

  
                    <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Profile</h4>
                    </div>
                    <div class="modal-body">
                    <img src="images/dboy/<?php echo $agent['profile_image'];?>" width="100%" height="100%"  id="img2">
                    </div>
                    </div>

					</div>
					</div>
          </td>
                  <td><?php echo $agent['full_name'];?></td>
                  <td><?php echo $agent['contact_no'];?></td>
                  <td><?php echo $agent['email'];?></td>
                  <td><?php echo $city['city_name'];?></td>
                  <?php if(in_array('33.2',$Job_assign)){?>
                  <td>
                  <?php if($agent['status']=='1'){?><button type="button" class="btn btn-success" onClick="upDateStatus(<?=$agent['id']?>,0)">Active</button><?php }else{?><button type="button" class="btn btn-danger" onClick="upDateStatus(<?=$agent['id']?>,1)">Block</button> <?php }?>
                  </td>
                  <?php }?>
                  <?php if(in_array('33.3',$Job_assign)){?>
                  <td>	
              <a class="btn btn-social-icon btn-success" href="deliveryboy_orders_history.php?edit_id=<?= $agent['id']?>" ><i class="fa fa-eye"></i></a>	
                <a class="btn btn-social-icon btn-primary" href="deliver_boy_edit.php?edit_id=<?= $agent['id']?>" ><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $agent['id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a>
                  </td>
                  <?php }?>
                </tr>
                <?php $i++; } ?>
               
                </tbody>
                <tfoot>
                <tr>
                 <th>Sl No.</th>
                 <th>Profile</th>
                  <th>Name</th>
                  <th>Contat No</th>
                  <th>Emails</th>
                  <th>Select City</th>
                  <?php if(in_array('33.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('33.3',$Job_assign)){?>
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
  
  
   <?php include('footer.php')?>
    <!-- Select2 -->
