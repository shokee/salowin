<?php include('header.php')?>
<?php include('sidebar.php')?>
  
  <?php 
  ########################## insert state #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addcity'){
	
$country=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
$state=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
$city=trim($dbf->checkXssSqlInjection($_REQUEST['city_name']));

$cntstate=$dbf->countRows("city","city_name='$city' and state_id='$state' AND country_id='$country'");

if($cntstate==0){
$string="country_id='$country',state_id='$state', city_name='$city', created_date=NOW()";
$dbf->insertSet("city",$string);
header("Location:city.php?msg=success");
}else{	
header("Location:city.php?msg=exit");
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
                  $state_id= $objExcel->getActiveSheet()->getCell("C".$row)->getValue();
                  $country_id = $objExcel->getActiveSheet()->getCell("D".$row)->getValue();
               
                  
                  $string="city_id='$id',city_name='$name',state_id='$state_id',country_id='$country_id'";
                  $dbf->insertSet("city",$string);

                 }
                 header("Location:city.php?msg=Importsuccess");


              }
}?>

            
   
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
        City
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">City</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
      
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>City Add Successfully</p>
</div>
<?php }?>
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='Importsuccess'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>City Imported Successfully</p>
</div>
 <?php }?>
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='successup'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>City Updated Successfully</p>
</div>
<?php }?>
 
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>City Already Exit</p>
</div>
<?php }?>
 
 
            <div class="box-header">
            <?php if(in_array('18.1',$Job_assign)){?>
              <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add City</button></h3>
            <?php }?>
              <div class="modal fade" id="modal-default-add">
            <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add City </h4>
              </div>
              <div class="modal-body">
               <div class="form-group">
             <label class="uk-margin-top" for="inlineFormCustomSelect">Select Country</label>
      		    <select class="form-control" name="country_id" onChange=" GetState(this.value)" required>
                <option value="" >--Select Country--</option>
    			<?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
    			<option value="<?=$countryName['country_id']?>" ><?=$countryName['country_name']?></option>
   			    <?php }?>
    			</select>
                </div>
                
                
                <div class="form-group">
                <label>Select State Name</label>
                <select class="form-control" name="state_id" id="stateres" onChange="GetCity(this.value)" required>
    			 <option value="" >--Select State--</option>
    			 </select>
                </div>
              
                <div class="form-group">
                <label>Enter City Name</label>
                <input type="text" class="form-control" name="city_name" id="city_name" placeholder="Enter City Name" required/>
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
        
        <button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal212">Import City</button>
        
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
                  <th>City Name</th>
                  <th>state Name</th>
                  <th>Country</th>
                  <?php if(in_array('18.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <th style="display:none">Delete</th>
                </tr>
                </thead>
                <tbody>
                
          <?php
		  $i=1;
		  foreach($dbf->fetchOrder("city","","city_id ASC","","") as $resBanner){
		  $country = $dbf->fetchSingle("country",'*',"country_id='$resBanner[country_id]'");
		  $state = $dbf->fetchSingle("state",'*',"state_id='$resBanner[state_id]'");
		  ?>
          <tr>
          <td><?php echo $i;?></td>
          <td><?php echo $resBanner['city_name'];?></td>
          <td><?php echo $state['state_name'];?></td>
          <td><?php echo $country['country_name'];?></td>
          <?php if(in_array('18.2',$Job_assign)){?>
          <td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?=$resBanner['city_id']?>" ><i class="fa fa-edit"></i></a></td>
          <?php }?>
          <td style="display:none"> <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['city_id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a></td>
          </tr>
          <?php $i++; } ?>
          
                </tbody>
                <tfoot>
                <tr>
                 <th>Slno.</th>
                  <th>City Name</th>
                  <th>state Name</th>
                  <th>Country</th>
                  <?php if(in_array('18.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <th style="display:none">Delete</th>
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
  $city_name=trim($dbf->checkXssSqlInjection($_REQUEST['city_name']));
  $cntstate=$dbf->countRows("city","city_name='$city_name' and state_id='$state_id' AND country_id='$country_id' AND city_id!='$city_id'");

if($cntstate==0){
  $string="country_id='$country_id', state_id='$state_id', city_name='$city_name', created_date=NOW()";
  $dbf->updateTable("city",$string,"city_id='$city_id'");
  header("Location:city.php?msg=successup");
}else{	
header("Location:city.php?msg=exit");
}
}

?>          
              
              
              
 <?php
$i=1;
foreach($dbf->fetchOrder("city","","city_id ASC","","") as $resBanner){
?>
<div class="modal fade" id="modal-default-edit<?= $resBanner['city_id']?>">
    <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Edit State</h4>
           </div>
           <div class="modal-body">
           
           <form  action="" method="post" enctype="multipart/form-data" name="frm" id="frm" >
		   <input type="hidden" class="form-control" name="pagesId"  value="<?php echo $resBanner['city_id'];?>" required>
           
           <div class="form-group">
           <label class="uk-margin-top" for="inlineFormCustomSelect">Select Country</label>
      		<select class="form-control" name="country_id" onChange=" GetState(this.value)" required>
		   <?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $DirName){?>
           <option value="<?=$DirName['country_id']?>" <?php if($DirName['country_id']==$resBanner['Country_id']){ echo"selected";}?>><?=$DirName['country_name']?></option>
           <?php }?>
           </select>
           </div>
           
           <div class="form-group">
           <label>Select state</label>
           <select class="form-control" name="state_id" required>
		   <?php  foreach($dbf->fetchOrder("state","","state_id ASC","","") as $stateName){?>
           <option value="<?=$stateName['state_id']?>" <?php if($stateName['state_id']==$resBanner['state_id']){ echo"selected";}?>><?=$stateName['state_name']?></option>
           <?php }?>
           </select>
           </div>
           
           <div class="form-group">
           <label>Enter City Name</label>
		   <input type="text" class="form-control" name="city_name" id="country" value="<?php echo $resBanner['city_name'];?>" required/>
           </div>
                
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary pull-left" type="submit" name="submit" value="editcity">Update </button>
                
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
