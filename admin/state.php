<?php include('header.php')?>
  <?php include('sidebar.php')?>
  
  <?php 
  ########################## insert state #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addstate'){
	
$country=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
$state=trim($dbf->checkXssSqlInjection($_REQUEST['state']));
	
$cntstate=$dbf->countRows("state","state_name='$state' and Country_id='$country'");

if($cntstate==0){
$string="country_id='$country',state_name='$state', created_date=NOW()";
$dbf->insertSet("state",$string);
header("Location:state.php?msg=success");
}else{	
header("Location:state.php?msg=exit");
}
}
		

	

?>

<?php
############################## Import Excel #########################


          if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='import'){ 
           
          
            

              $file=$_FILES['upload']['tmp_name'];
              require 'phpexcel/PHPExcel.php';
              require_once 'phpexcel/PHPExcel/IOFactory.php';
              $objExcel=PHPExcel_IOFactory::load($file);
              foreach($objExcel->getWorksheetIterator() as $worksheet){

                
                 $maxrow=$worksheet->getHighestRow();
                 for($row=2; $row<=$maxrow; $row++){

                  $id = $objExcel->getActiveSheet()->getCell("A".$row)->getValue();
                  $name = $objExcel->getActiveSheet()->getCell("B".$row)->getValue();
                  $country_id = $objExcel->getActiveSheet()->getCell("C".$row)->getValue();
               
                  
                  $string="state_id='$id',state_name='$name',Country_id='$country_id'";
                  $dbf->insertSet("state",$string);

                 }
                 header("Location:state.php?msg=Importsuccess");


              }
}?>


            
   
<?php
########################## DELETE STATE #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
	
	$dbf->deleteFromTable("state","state_id='$id'");
	$dbf->deleteFromTable("city","state_id='$id'");
	header("Location:state.php");
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
  <input type="hidden" name="state_id" id="country_id" value="">
  </form>
  
  



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        State
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
<p>State Add Successfully</p>
</div>
 <?php }?>
 
  <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='Importsuccess'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>State Imported Successfully</p>
</div>
 <?php }?>
 
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='upsucess'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>State Updated Successfully</p>
</div>
 <?php }?>
 
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>State Already Exit</p>
</div>
 <?php }?>
 
 
            <div class="box-header">
            <?php if(in_array('17.1',$Job_assign)){?>
              <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add State</button></h3>
            <?php }?>
              <div class="modal fade" id="modal-default-add">
            <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add State </h4>
              </div>
              <div class="modal-body">
               <div class="form-group">
                <label>Select Country Name</label>
                <select class="form-control" name="country_id" required>
                <option value=""> Select Country</option>
    			<?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $DirName){?>
    			<option value="<?=$DirName['country_id']?>" ><?=$DirName['country_name']?></option>
   			    <?php }?>
    			</select>
                </div>
              
                <div class="form-group">
                <label>Enter State Name</label>
                <input type="text" class="form-control" name="state" id="state" placeholder="Enter State Name" required />
                </div>
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="addstate" name="submit">Add State</button>
              <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
          <!-- /.modal-dialog -->
        </div>
        
        <button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal212">Import State</button>
        
             <div class="modal fade" id="myModal212" role="dialog">
    <div class="modal-dialog">
    
      <!--  --  --   -- Modal content   --   --   -    -- -->


      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
        <div class="modal-body">
           
           
            <label> Select Excel-file to upload:</label>
            <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="upload" class="form-control" required >

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary pull-left " value="import" name="submit"> Import</button>
            
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>
        
            </div>
            
            
            
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Slno.</th>
                  <th>Country Name</th>
                  <th>Country</th>
                  <?php if(in_array('17.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <th style="display:none">Delete</th>
                </tr>
                </thead>
                <tbody>
                
          <?php
		  $i=1;
		  foreach($dbf->fetchOrder("state","","state_id ASC","","") as $resBanner){
		  $country = $dbf->fetchSingle("country",'*',"country_id='$resBanner[Country_id]'");
		  ?>
          <tr>
          <td><?php echo $i;?></td>
          <td><?php echo $resBanner['state_name'];?></td>
          <td><?php echo $country['country_name'];?></td>
          <?php if(in_array('17.2',$Job_assign)){?>
          <td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?=$resBanner['state_id']?>" ><i class="fa fa-edit"></i></a></td>
          <?php }?>
      <td style="display:none"> <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['state_id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a></td>
          </tr>
          <?php $i++; } ?>
          
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>Country Name</th>
                  <th>Country</th>
                  <?php if(in_array('17.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <th style="display:none">Delete</th>
                </tr>
                </tfoot>
              </table>
              
              
              
              
    <?php 
########################## EDIT STATE #############################
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editstate'){
	
	
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['pagesId']);
	$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_name=trim($dbf->checkXssSqlInjection($_REQUEST['state_name']));
  $string="country_id='$country_id',state_name='$state_name', created_date=NOW()";
  
  $cntstate=$dbf->countRows("state","state_name='$state_name' and Country_id='$country_id' AND state_id!='$state_id'");
  if($cntstate==0){
  $dbf->updateTable("state",$string,"state_id='$state_id'");
  header('Location:state.php?msg=upsucess');exit;
  }else{
    header('Location:state.php?msg=exit');exit;
  }
	
}
?>          
              
              
              
 <?php
$i=1;
foreach($dbf->fetchOrder("state","","state_id ASC","","") as $resBanner){
?>
<div class="modal fade" id="modal-default-edit<?= $resBanner['state_id']?>">
    <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Edit State</h4>
           </div>
           <div class="modal-body">
           
           <form  action="" method="post" enctype="multipart/form-data" name="frm" id="frm" >
		   <input type="hidden" class="form-control" name="pagesId"  value="<?php echo $resBanner['state_id'];?>">
           
           <div class="form-group">
           <label>Select Country</label>
           <select class="form-control" name="country_id" required>
		   <?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $DirName){?>
           <option value="<?=$DirName['country_id']?>" <?php if($DirName['country_id']==$resBanner['Country_id']){ echo"selected";}?>><?=$DirName['country_name']?></option>
           <?php }?>
           </select>
           </div>
           
           <div class="form-group">
           <label>Enter State Name</label>
		   <input type="text" class="form-control" name="state_name" id="country" value="<?php echo $resBanner['state_name'];?> " required/>
           </div>
                
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary pull-left" type="submit" name="submit" value="editstate">Update State</button>
                
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
