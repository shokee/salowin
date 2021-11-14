<?php include('header.php')?>
<?php include('sidebar.php')?>
  
  <?php 
  ########################## insert state #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addPin'){
	
$country=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
$state=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
 $city=$dbf->checkXssSqlInjection($_REQUEST['city']);
$pin=$dbf->checkXssSqlInjection($_REQUEST['pin']);

$cntPin=$dbf->countRows("pincode","country_id='$country' AND state_id='$state' AND city_id='$city' AND pincode='$pin'");

if($cntPin==0){
$string="country_id='$country',state_id='$state',city_id='$city',pincode='$pin'";
$dbf->insertSet("pincode",$string);
header("Location:pincode.php?msg=success");
}else{	
header("Location:pincode.php?msg=exit");
}
}
?>

 <?php 
  ########################## insert state #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='UpdPin'){
  $pin_id=$dbf->checkXssSqlInjection($_REQUEST['pin_id']);
$country=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
$state=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
 $city=$dbf->checkXssSqlInjection($_REQUEST['city']);
$pin=$dbf->checkXssSqlInjection($_REQUEST['pin']);

$cntPin=$dbf->countRows("pincode","country_id='$country' AND state_id='$state' AND city_id='$city' AND pincode='$pin' AND pincode_id!='$pin_id'");

if($cntPin==0){
$string="country_id='$country',state_id='$state',city_id='$city',pincode='$pin'";
$dbf->updateTable("pincode",$string,"pincode_id='$pin_id'");
header("Location:pincode.php?msg=success");
}else{  
header("Location:pincode.php?msg=exit");
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
                  $city_id= $objExcel->getActiveSheet()->getCell("C".$row)->getValue();
                  $state_id= $objExcel->getActiveSheet()->getCell("D".$row)->getValue();
                  $country_id = $objExcel->getActiveSheet()->getCell("E".$row)->getValue();
               
                  
                  $string="pincode_id='$id',pincode='$name',city_id='$city_id',state_id='$state_id',country_id='$country_id'";
                  $dbf->insertSet("pincode",$string);

                 }
                 header("Location:pincode.php?msg=Importsuccess");


              }
}?>
        
   
<?php
########################## DELETE CITY #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='StausChn'){
 $pin_id=$dbf->checkXssSqlInjection($_REQUEST['pin_id']);
  $status=$dbf->checkXssSqlInjection($_REQUEST['status']);
$dbf->updateTable("pincode","status='$status'","pincode_id='$pin_id'");
	header("Location:pincode.php");
}
?>
<script type="text/javascript">
function ChangeStuas(id,id2){

  if(id2=='1'){
    msg="Are You Sure To Active This Pincode?";
  }else{
     msg="Are You Sure To Block This Pincode?";
  }
  $("#pin_id").val(id);
    $("#status").val(id2);
	var conf=confirm(msg);
	if(conf){
	   $("#frm_statusch").submit();
	}
}
</script>


  <form name="frm_deleteBanner" id="frm_statusch" action="" method="post">
  <input type="hidden" name="operation"  value="StausChn">
  <input type="hidden" name="pin_id" id="pin_id" value="">
  <input type="hidden" name="status" id="status" value="">

  </form>
  
  



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pincode
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Pincode</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
      
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Pincode Add Successfully</p>
</div>
<?php }?>
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='Importsuccess'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Pincode Imported Successfully</p>
</div>
 <?php }?>
 
 
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Pincode Already Exist</p>
</div>
<?php }?>
 
 
            <div class="box-header">
            <?php if(in_array('19.1',$Job_assign)){?>
              <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add Pincode</button></h3>
            <?php }?>
              <div class="modal fade" id="modal-default-add">
            <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Pincode </h4>
              </div>
              <div class="modal-body">
               <div class="form-group">
             <label class="uk-margin-top" for="inlineFormCustomSelect">Select Country</label>
      		    <select class="form-control" name="country_id" onChange=" GetState(this.value)">
                <option value="" >--Select Country--</option>
    			<?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
    			<option value="<?=$countryName['country_id']?>" ><?=$countryName['country_name']?></option>
   			    <?php }?>
    			</select>
                </div>
                
                
                <div class="form-group">
                <label>Select State Name</label>
                <select class="form-control" name="state_id" id="stateres" onChange="GetCitys(this.value)">
    			 <option value="" >--Select State--</option>
    			 </select>
                </div>
              
                <div class="form-group">
                <label>Enter City Name</label>
                  <select class="form-control" id="cityres" name="city">
                       <option value="" >--Select City--</option>
                  </select>
                </div>
                <div class="form-group">
                <label>Enter Pincode</label>
                  <input type="text" class="form-control" name="pin" placeholder="Enter Pincode" maxlength="6">
                    
                </div>
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="addPin" name="submit">Add </button>
              <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
          <!-- /.modal-dialog -->
        </div>
        
        <button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal212">Import Pincode</button>
        
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
                  <th>Pincode</th>
                  <th>City Name</th>
                  <th>state Name</th>
                  <th>Country</th>
                  <?php if(in_array('19.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('19.3',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <th style="display:none">Delete</th>
                </tr>
                </thead>
                <tbody>
                
          <?php
		  $i=1;
		  foreach($dbf->fetchOrder("pincode","","pincode_id","","") as $Pincode){
		  $country = $dbf->fetchSingle("country",'*',"country_id='$Pincode[country_id]'");
		  $state = $dbf->fetchSingle("state",'*',"state_id='$Pincode[state_id]'");
        $City = $dbf->fetchSingle("city",'*',"city_id='$Pincode[city_id]'");
		  ?>
          <tr>
          <td><?php echo $i;?></td>
          <td><?= $Pincode['pincode']?></td>
          <td><?php echo $City['city_name'];?></td>
          <td><?php echo $state['state_name'];?></td>
          <td><?php echo $country['country_name'];?></td>
          <?php if(in_array('19.2',$Job_assign)){?>
          <td><?php if($Pincode['status']=='1'){?>
            <button class="btn btn-success" type="button" onclick="ChangeStuas(<?=$Pincode['pincode_id']?>,0)">Active</button>
          <?php }else{?>
            <button class="btn btn-danger" type="button" onclick="ChangeStuas(<?=$Pincode['pincode_id']?>,1)">Block</button>
          <?php }?>
          </td>
          <?php } ?>
          <?php if(in_array('19.3',$Job_assign)){?>
          <td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?=$Pincode['pincode_id']?>" ><i class="fa fa-edit"></i></a></td>
          <?php }?>
          </tr>
          <?php $i++; } ?>
          
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>Pincode</th>
                  <th>City Name</th>
                  <th>state Name</th>
                  <th>Country</th>
                  <?php if(in_array('19.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('19.3',$Job_assign)){?>
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
	$city_name=$dbf->checkXssSqlInjection($_REQUEST['city_name']);
	$string="country_id='$country_id', state_id='$state_id', city_name='$city_name', created_date=NOW()";
	
	$dbf->updateTable("city",$string,"city_id='$city_id'");
	
	header('Location:city.php?editId='.$id);exit;
}
?>          
              
              
              
 <?php
$i=1;
foreach($dbf->fetchOrder("pincode","","pincode_id","","") as $Pincode){
?>
  <div class="modal fade" id="modal-default-edit<?=$Pincode['pincode_id']?>">
            <form  action="" method="post">  
              <input type="hidden" name="pin_id" value="<?= $Pincode['pincode_id']?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Update Pincode </h4>
              </div>
              <div class="modal-body">
               <div class="form-group">
             <label class="uk-margin-top" for="inlineFormCustomSelect">Select Country</label>
              <select class="form-control" name="country_id" onChange="GetStates(this.value)">
                <option value="" >--Select Country--</option>
          <?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
          <option value="<?=$countryName['country_id']?>"  <?php if($countryName['country_id']==$Pincode['country_id']){ echo "selected";}?>><?=$countryName['country_name']?></option>
            <?php }?>
          </select>
                </div>
                
                
                <div class="form-group">
                <label>Select State Name</label>
                <select class="form-control" name="state_id" id="state" onChange="GetCItys(this.value)">
           <option value="" >--Select State--</option>
              <?php  foreach($dbf->fetchOrder("state","Country_id = '$Pincode[country_id]'","","","") as $state){?>
                <option value="<?=$state['state_id']?>" <?php if($state['state_id']==$Pincode['state_id']){ echo "selected";}?>><?=$state['state_name']?></option>
              <?php }?>
           </select>
                </div>
              
                <div class="form-group">
                <label>Select City Name</label>
                  <select class="form-control" id="City" name="city">
                       <option value="" >--Select City--</option>
                        <?php  foreach($dbf->fetchOrder("city","state_id = '$Pincode[state_id]'","","","") as $City){?>
                          <option value="<?= $City['city_id']?>" <?php if($City['city_id']==$Pincode['city_id']){ echo "selected";}?>><?= $City['city_name']?></option>
                        <?php }?>
                  </select>
                </div>
                <div class="form-group">
                <label>Enter Pincode</label>
                  <input type="text" class="form-control" name="pin" placeholder="Enter Pincode" maxlength="6" value="<?= $Pincode['pincode']?>">
                    
                </div>
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="UpdPin" name="submit">Update </button>
              <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
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
<script type="text/javascript">
  
   function  GetCitys(val) {
       // alert(val);
    
//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getCity","value":val},function(res){
 $('#cityres').html(res);
 // alert(res)
});
  
  } 

function GetStates(arg){

//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getState","value":arg},function(res){
 $('#state').html(res);
// alert(res)
});
}

function GetCItys(arg){

///$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getCity","value":arg},function(res){
 $('#City').html(res);
 // alert(res)
});
}
</script>

   <?php include('footer.php')?>
