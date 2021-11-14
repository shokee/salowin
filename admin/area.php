<?php include('header.php')?>
<?php include('sidebar.php')?>
  
  <?php 
  ########################## insert state #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addArea'){

$pin=$dbf->checkXssSqlInjection($_REQUEST['pin']);
$area=$dbf->checkXssSqlInjection($_REQUEST['area']);
$cntPin=$dbf->countRows("area","pin_id='$pin' AND name='$area'");

if($cntPin==0){
$string="pin_id='$pin',name='$area'";
$dbf->insertSet("area",$string);
header("Location:area.php?msg=success");
}else{	
header("Location:area.php?msg=exit");
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
                  $pin_id= $objExcel->getActiveSheet()->getCell("C".$row)->getValue();
                  
               
                  
                  $string="area_id='$id',name='$name',pin_id='$pin_id',status='1'";
                  $dbf->insertSet("area",$string);

                 }
                 header("Location:area.php?msg=Importsuccess");


              }
}?>

 <?php 
  ########################## insert state #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='UpArea'){
  $area_id=$dbf->checkXssSqlInjection($_REQUEST['area_id']);
  $pin=$dbf->checkXssSqlInjection($_REQUEST['pin']);
  $area=$dbf->checkXssSqlInjection($_REQUEST['area']);
$cntArar=$dbf->countRows("area","pin_id='$pin' AND name='$area' AND pincode_id!='$pin_id'");

if($cntArar==0){
$string="pin_id='$pin',name='$area'";
$dbf->updateTable("area",$string,"area_id='$area_id'");
header("Location:area.php?msg=success");
}else{  
header("Location:area.php?msg=exit");
}
}
?>
        
   
<?php
########################## DELETE CITY #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='StausChn'){
    $area_id=$dbf->checkXssSqlInjection($_REQUEST['area_id']);
  $status=$dbf->checkXssSqlInjection($_REQUEST['status']);
$dbf->updateTable("area","status='$status'","area_id='$area_id'");
	header("Location:area.php");
}
?>
<script type="text/javascript">
function ChangeStuas(id,id2){

  if(id2=='1'){
    msg="Are You Sure To Active This Area?";
  }else{
     msg="Are You Sure To Block This Area?";
  }
  $("#area_id").val(id);
    $("#status").val(id2);
	var conf=confirm(msg);
	if(conf){
	   $("#frm_statusch").submit();
	}
}
</script>


  <form name="frm_deleteBanner" id="frm_statusch" action="" method="post">
  <input type="hidden" name="operation"  value="StausChn">
  <input type="hidden" name="area_id" id="area_id" value="">
  <input type="hidden" name="status" id="status" value="">

  </form>
  
  



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Area
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Area</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
      
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Area Add Successfully</p>
</div>
<?php }?>

 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='Importsuccess'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Area Imported Successfully</p>
</div>
 <?php }?>
 
 
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Area Already Exist</p>
</div>
<?php }?>
 
 
            <div class="box-header">
            <?php if(in_array('34.1',$Job_assign)){?>
              <h3 class="box-title"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default-add">Add Area</button></h3>
            <?php }?>
              <div class="modal fade" id="modal-default-add">
            <form  action="" method="post">  
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Area</h4>
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
                  <select class="form-control" id="cityres" name="city" onchange="UpdateChangepin(this.value)">
                       <option value="" >--Select City--</option>
                  </select>
                </div>
                <div class="form-group">
                <label>Enter Pincode</label>
                <select name="pin" class="form-control" id="loc_selected_pin" required>
                <option value="">--Select Pincode--</option>
               </select> 
                </div>
           
              <div class="form-group">
                <label>Enter Area</label>
                    <input type="text" name="area" class="form-control" required placeholder="Enter Area" >
                </div>
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="addArea" name="submit">Add </button>
              <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          
          </form>
          <!-- /.modal-dialog -->
        </div>
        
        <button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal212">Import Area</button>
        
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
                  <th>Area</th>
                  <th>Pincode</th>
                  <?php if(in_array('34.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('34.3',$Job_assign)){?>
                  <th>Edit</th>
                  <?php }?>
                  <th style="display:none">Delete</th>
                </tr>
                </thead>
                <tbody>
                
          <?php
		  $i=1;
		  foreach($dbf->fetchOrder("area","","pin_id","","") as $Area){
           $Pincode = $dbf->fetchSingle("pincode",'pincode',"pincode_id='$Area[pin_id]'");
		  ?>
          <tr>
          <td><?php echo $i;?></td>
          <td><?php echo $Area['name'];?></td>
          <td><?= $Pincode['pincode']?></td>
          <?php if(in_array('34.2',$Job_assign)){?>
          <td><?php if($Area['status']=='1'){?>
            <button class="btn btn-success" type="button" onclick="ChangeStuas(<?=$Area['area_id']?>,0)">Active</button>
          <?php }else{?>
            <button class="btn btn-danger" type="button" onclick="ChangeStuas(<?=$Area['area_id']?>,1)">Block</button>
          <?php }?>
          </td>
          <?php } ?>
          <?php if(in_array('34.3',$Job_assign)){?>
          <td> <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target="#modal-default-edit<?=$Area['area_id']?>" ><i class="fa fa-edit"></i></a></td>
          <?php }?>
          </tr>
          <?php $i++; } ?>
          
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th>Area</th>
                  <th>Pincode</th>
                  <?php if(in_array('34.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('34.3',$Job_assign)){?>
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
foreach($dbf->fetchOrder("area","","pin_id","","") as $Area){
    $Pin = $dbf->fetchSingle("pincode",'pincode_id,country_id,state_id,city_id',"pincode_id='$Area[pin_id]'");
    $country = $dbf->fetchSingle("country",'country_id',"country_id='$Pincode[country_id]'");
    $state = $dbf->fetchSingle("state",'state_id',"state_id='$Pincode[state_id]'");
  $City = $dbf->fetchSingle("city",'city_id',"city_id='$Pincode[city_id]'");

?>
  <div class="modal fade" id="modal-default-edit<?= $Area['area_id']?>">
            <form  action="" method="post">  
              <input type="hidden" name="area_id" value="<?= $Area['area_id']?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Update Area </h4>
              </div>
              <div class="modal-body">
               <div class="form-group">
             <label class="uk-margin-top" for="inlineFormCustomSelect">Select Country</label>
              <select class="form-control" name="country_id" onChange="UpGetStates(this.value,<?= $Area['area_id']?>)">
                <option value="" >--Select Country--</option>
          <?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
          <option value="<?=$countryName['country_id']?>"  <?php if($countryName['country_id']==$Pin['country_id']){ echo "selected";}?>><?=$countryName['country_name']?></option>
            <?php }?>
          </select>
                </div>
                
                
                <div class="form-group">
                <label>Select State Name</label>
                <select class="form-control" name="state_id" id="state<?= $Area['area_id']?>" onChange="upGetCItys(this.value,<?= $Area['area_id']?>)">
           <option value="" >--Select State--</option>
              <?php  foreach($dbf->fetchOrder("state","Country_id = '$Pin[country_id]'","","","") as $state){?>
                <option value="<?=$state['state_id']?>" <?php if($state['state_id']==$Pin['state_id']){ echo "selected";}?>><?=$state['state_name']?></option>
              <?php }?>
           </select>
                </div>
              
                <div class="form-group">
                <label>Select City Name</label>
                  <select class="form-control" id="City<?= $Area['area_id']?>" name="city" onchange="UpdateChangepin(this.value,<?= $Area['area_id']?>)">
                       <option value="" >--Select City--</option>
                        <?php  foreach($dbf->fetchOrder("city","state_id = '$Pin[state_id]'","","","") as $City){?>
                          <option value="<?= $City['city_id']?>" <?php if($City['city_id']==$Pin['city_id']){ echo "selected";}?>><?= $City['city_name']?></option>
                        <?php }?>
                  </select>
                </div>
               
                <div class="form-group">
                <label>Select Pincode</label>
                <select name="pin" class="form-control" id="loc_selected_pin<?= $Area['area_id']?>" required>
                <option value="">--Select Pincode--</option>
                <?php 
                    foreach ($dbf->fetchOrder("pincode","city_id='$Pin[city_id]'","","","") as $Pincode) {
                        ?>
                        <option value="<?= $Pincode['pincode_id']?>"  <?= ($Pincode['pincode_id']==$Pin['pincode_id'])?"selected":""?>><?= $Pincode['pincode']?></option>
                    <?php } ?>
               </select> 
                </div>
           
              <div class="form-group">
                <label>Enter Area</label>
                <input type="text" name="area" class="form-control" required placeholder="Enter Area" value="<?= $Area['name']?>">
                </div>
              
              </div>
              <div class="modal-footer">
              <button type="submit" class="btn btn-primary pull-left " value="UpArea" name="submit">Update </button>
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
function UpGetStates(arg,arg1){

//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getState","value":arg},function(res){
 $('#state'+arg1).html(res);
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

function upGetCItys(arg,arg2){
    var url="getAjax.php";
  $.post(url,{"choice":"getCity","value":arg},function(res){
 $('#City'+arg2).html(res);
//  alert(res)
});
}
function UpdateChangepin(arg,city=""){
  var url="getAjax.php";

  $.post(url,{"choice":"changPin","value":arg},function(res){
 $('#loc_selected_pin'+city).html(res);
// alert(res)
});
}
</script>

   <?php include('footer.php')?>
