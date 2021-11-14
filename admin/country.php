<?php include('header.php')?>
<?php include('sidebar.php')?>
  
<?php 
  ########################## insert  #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addcountry'){
$country=trim($dbf->checkXssSqlInjection($_REQUEST['country']));
	
$cntcountry=$dbf->countRows("country","country_name='$country'");


if($cntcountry==0){
$string="country_name='$country', created_date=NOW()";
$dbf->insertSet("country",$string);
header("Location:country.php?msg=success");
}else{	
header("Location:country.php?msg=exit");
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
               
                  
                  $string="country_id='$id',country_name='$name'";
                  $dbf->insertSet("country",$string);

                 }
                 header("Location:country.php?msg=Importsuccess");


              }
}?>



     
<?php
########################## DELETE  #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
 $id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	
	$dbf->deleteFromTable("country","country_id='$id'");
	$dbf->deleteFromTable("state","Country_id='$id'");
	$dbf->deleteFromTable("city","country_id='$id'");
	header("Location:country.php");
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
        Country
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
<p>Country Add Successfully</p>
</div>
 <?php }?>
 
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='Importsuccess'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Country Imported Successfully</p>
</div>
 <?php }?>

       
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='successup'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Country Updated Successfully</p>
</div>
 <?php }?>
 
 
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Country Already Exit</p>
</div>
 <?php }?>
 
 
 
            <div class="box-header">
            <?php if(in_array('16.1',$Job_assign)){?>
              <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add Country</button></h3>
            <?php }?>
              <div class="modal fade" id="modal-default-add">
              
            <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Country </h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                <label>Enter Country Name</label>
                <input type="text" class="form-control" name="country" id="country" placeholder="Enter Country Name" required />
                
                
                </div>
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="addcountry" name="submit">Add Country</button>
                <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
          <!-- /.modal-dialog -->
        </div>
        
          <button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal212">Import Country</button>
          
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
                  <?php if(in_array('16.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <th style="display:none">Delete</th>
                </tr>
                </thead>
                <tbody>
                
                    <?php
					$i=1;
					 foreach($dbf->fetchOrder("country","","country_id ASC","","") as $resBanner){
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $resBanner['country_name'];?></td>
                  <?php if(in_array('16.2',$Job_assign)){?>
            <td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?php echo $resBanner['country_id'];?>" ><i class="fa fa-edit"></i></a></td>
                  <?php }?>

<td style="display:none" > <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $resBanner['country_id'];?>');" class="btn btn-social-icon btn-danger"  ><i class="fa fa-trash"></i></a>
                  
                  </td>
                </tr>
                <?php $i++; } ?>
                
                
                
               
               
               
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>Country Name</th>
                  <?php if(in_array('16.2',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <th style="display:none">Delete</th>
                </tr>
                </tfoot>
              </table>
 
 
 
 
 <?php 
########################## EDIT COUNTRY #############################
$bannerId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='editcountry'){
	
	
	$id=$dbf->checkXssSqlInjection($_REQUEST['pagesId']);
	$country=trim($dbf->checkXssSqlInjection($_REQUEST['country']));
  $string="country_name='$country', created_date=NOW()";
  
	$cntcountry=$dbf->countRows("country","country_name='$country' AND country_id!='$id'");
if($cntcountry==0){
	$dbf->updateTable("country",$string,"country_id='$id'");
  header("Location:country.php?msg=successup");
}else{	
header("Location:country.php?msg=exit");

}
}
?>
 
              
<?php
$i=1;
foreach($dbf->fetchOrder("country","","country_id ASC","","") as $resBanner){
?>
              <div class="modal fade" id="modal-default-edit<?php echo $resBanner['country_id'];?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Country</h4>
              </div>
              <div class="modal-body">
                
                <form  action="" method="post" enctype="multipart/form-data" name="frm" id="frm" >

		   <input type="hidden" class="form-control" name="pagesId"  value="<?php echo $resBanner['country_id'];?>">
	<input type="text" class="form-control" name="country" id="country" value="<?php echo $resBanner['country_name'];?> " required/>

                
              </div>
              <div class="modal-footer">
                <button class="btn btn-default pull-left" type="submit" name="submit" value="editcountry">Update Country</button>
                
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
