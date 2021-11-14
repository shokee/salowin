<?php include('header.php')?>
<?php include('sidebar.php')?>
   
<?php
########################## DELETE CITY #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
	
	$dbf->deleteFromTable("product_catagory_3","product_catagory_3_id='$id'");
	header("Location:catagory-3.php");
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
        Catagory level 3
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
            <div class="box-header">
              <form action="CategoryExcel.php" method="post">
              <?php if(in_array('9.1',$Job_assign)){?>
              <h3 class="box-title">
              <a class="btn btn-primary" href="category3add.php">Add </a>
              </h3>
              <?php }?>
              <?php if(in_array('9.2',$Job_assign)){?>
              <button class="btn btn-primary" name="operation" value="SubcateExport2">  
              <i class="fa fa-download" aria-hidden="true"></i>
              <i class="fa fa-file-excel-o" aria-hidden="true"></i>
              </button>
              <?php }?>
              </form>
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
                <label>Select catagory Name</label>
                <select class="form-control" name="country_id">
    			<?php  foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","product_catagory_1_name,product_catagory_1_id","") as $DirName){?>
    			<option value="<?=$DirName['product_catagory_1_id']?>" ><?=$DirName['product_catagory_1_name']?></option>
   			    <?php }?>
    			</select>
                </div>
                
                
                <div class="form-group">
                <label>Select Sub catagory</label>
                <select class="form-control" name="state_id">
    			<?php  foreach($dbf->fetchOrder("product_catagory_2","","product_catagory_2_id ASC","product_catagory_2_name,product_catagory_2_id","") as $stateName){?>
    			<option value="<?=$stateName['product_catagory_2_id']?>" ><?=$stateName['product_catagory_2_name']?></option>
   			    <?php }?>
    			</select>
                </div>
              
                <div class="form-group">
                <label>Enter sub catagory</label>
                <input type="text" class="form-control" name="city_name" id="city_name" placeholder="Enter subcatagory Name" />
                </div>
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="addcity" name="submit">Add </button>
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
                  <th>subcatagory2 Name</th>
                  <th>subcatagory Name</th>
                  <th>catagory</th>
                  <?php if(in_array('9.3',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('9.4',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </thead>
                <tbody>
                
          <?php
		  $i=1;
		  foreach($dbf->fetchOrder("product_catagory_3","","product_catagory_3_id ASC","product_catagory_1_id,product_catagory_2_id,product_catagory_3_name,product_catagory_3_id,product_catagory_3_id","") as $resBanner){
		  $country = $dbf->fetchSingle("product_catagory_1",'*',"product_catagory_1_id='$resBanner[product_catagory_1_id]'");
		  $state = $dbf->fetchSingle("product_catagory_2",'*',"product_catagory_2_id='$resBanner[product_catagory_2_id]'");
		  ?>
          <tr>
          <td><?php echo $i;?></td>
          <td><?php echo $resBanner['product_catagory_3_name'];?></td>
          <td><?php echo $state['product_catagory_2_name'];?></td>
          <td><?php echo $country['product_catagory_1_name'];?></td>
          <?php if(in_array('9.3',$Job_assign)){?>
          <td> <a class="btn btn-social-icon btn-primary" href="category3edit.php?id=<?=$resBanner['product_catagory_3_id']?>" ><i class="fa fa-edit"></i></a></td>
          <?php }?>
          <?php if(in_array('9.4',$Job_assign)){?>
          <td> <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['product_catagory_3_id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a></td>
          <?php }?>
          </tr>
          <?php $i++; } ?>
          
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>subcatagory2 Name</th>
                  <th>subcatagory Name</th>
                  <th>catagory</th>
                  <?php if(in_array('9.3',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <?php if(in_array('9.4',$Job_assign)){?>
                  <th>Delete</th>
                  <?php }?>
                </tr>
                </tfoot>
              </table>
              
              
              
              
    <?php 
########################## EDIT STATE #############################
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editcity'){
	
	
	$city_id=$dbf->checkXssSqlInjection($_REQUEST['pagesId']);
	$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
	$city_name=$_REQUEST['city_name'];
	$string="product_catagory_3_id='$country_id', product_catagory_2_id='$state_id', product_catagory_3_name='$city_name', created_date=NOW()";
	
	$dbf->updateTable("product_catagory_3",$string,"product_catagory_3_id='$city_id'");
	
	header('Location:catagory-3.php?editId='.$id);exit;
}
?>          
              
              
              
 
              
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 		

   <?php include('footer.php')?>
