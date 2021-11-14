<?php include('header.php')?>
<?php include('sidebar.php')?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <?php
########################## UPDATE STATUS  agent #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='update'){
   $id=$dbf->checkXssSqlInjection($_REQUEST['id']);
	$ststus=$dbf->checkXssSqlInjection($_REQUEST['ststus']);
	 $dbf->updateTable("user","status='$ststus'", "id='$id'");
	header("vendor.php");
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
	
	@unlink("images/vendor/".$resBanner['profile_image']);
	@unlink("images/vendor/thumb/".$resBanner['profile_image']);
	@unlink("images/vendor/".$resBanner['banner_image']);
	@unlink("images/vendor/thumb/".$resBanner['banner_image']);
	@unlink("images/vendor/".$resBanner['logo_image']);
	@unlink("images/vendor/thumb/".$resBanner['logo_image']);
//Product Images
// foreach ($dbf->fetchOrder("product","vendor_id='$banner_id'","","","") as $prodimg) {
// 	@unlink("images/product/".$prodimg['primary_image']);
// 	@unlink("images/product/thumb/".$prodimg['primary_image']);

// }
//Product Images
//Gallery Delete
	foreach ($dbf->fetchOrder("gallery","product_id='$product[product_id]'","","","") as $Gallery) {
		@unlink("images/gallery/".$Gallery['image']);
	@unlink("images/gallery/thumb/".$Gallery['image']);
	}
//Gallery Delete
	$dbf->deleteFromTable("gallery","product_id='$product[product_id]'");
	
	$dbf->deleteFromTable("user","id='$banner_id'");
	$dbf->deleteFromTable("vendor_catagory","vendor_id='$banner_id'");
	$dbf->deleteFromTable("product","vendor_id='$banner_id'");
	
	$dbf->deleteFromTable("attribute","product_id='$product[product_id]'");
	$dbf->deleteFromTable("variation","product_id='$product[product_id]'");
	$dbf->deleteFromTable("price_varition","product_id='$product[product_id]'");
	
	header("Location:vendor.php");
}

$city=$pin="";
if(isset($_REQUEST['operations']) && $_REQUEST['operations']=='Fillter'){
$city=$_POST['city'];
$pin=$_POST['pin'];
$condi = " AND pin='$pin' AND city_id='$city'";
}else{
  $condi = "";
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
        Vendor
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dash Board</a></li>
        <li class="active">Vendor</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="row">
      	<div class="col-xs-12">
        	<div class="box">
            
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>City Add Successfully</p>
</div>
<?php }?>
 
 
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='numexit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Contact No  Already Exit</p>
</div>
<?php }?> 

<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='mailexit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Email   Already Exit</p>
</div>
<?php }?> 

<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='userexit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Username   Already Exit</p>
</div>
<?php }?>


           
            <div class="box-header">
            <?php if(in_array('13.1',$Job_assign)){?>
              <h3 class="box-title">
              <a  class="btn btn-info " href="addvendor.php">Add Vendor</a>
              </h3>
            <?php }?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?php if($_SESSION['usertype']=='1'){
              $loc="";

              ?>
            <form action="" method="post">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <select name="city" id="" class="form-control select2" onchange="SelectOnPin(this.value)">
                      <option value="">~~Select City~~</option>
                      <?php foreach($dbf->fetchOrder("city","","city_name","","") as $cityName){?>
    			                  <option value="<?=$cityName['city_id']?>" <?= ($city==$cityName['city_id'])?"selected":""?>><?=$cityName['city_name']?></option>
   			              <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <select name="pin"  class="form-control select2" id="zcode"> 
                    <option value="">~~Select Pincode~~</option>
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
            <?php }else{    
                      
                        $loc = " AND city_id='$_SESSION[city]'";
                    }?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                 <th>Sl No.</th>
                  <th>Vendor Name</th>
                  <th>Contat No</th>
                  <th>Emails</th>
                  <th>Username</th>
                  <th> Image</th>
                  <th>Shop name</th>
                  <?php if(in_array('13.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  <?php if(in_array('13.3',$Job_assign)){?>
                  <th>Action</th>
                  <?php }?>
                  <th>Assign Product</th>
                </tr>
                </thead>
                <tbody>
               
                 <?php
					$i=1;
					 foreach($dbf->fetchOrder("user","user_type='3' $loc $condi","id ASC","","") as $agent){
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $agent['full_name'];?></td>
                  <td><?php echo $agent['contact_no'];?></td>
                  <td><?php echo $agent['email'];?></td>
                  <td><?php echo $agent['user_name'];?></td>
                  
                  <td>
                  <?php if($agent['profile_image']<>''){?>
        <img src="images/vendor/thumb/<?php echo $agent['profile_image'];?>" width="30px" height="30px">
        <?php }else{?>
         <img src="images/default.png?> " width="30px" height="30px"  >
        <?php }?>
        
                  
                  
                  </td>
                  <td><?php echo $agent['shop_name'];?></td>
                  <?php if(in_array('13.2',$Job_assign)){?>
                  <td>
                  <?php if($agent['status']=='1'){?><button type="button" class="btn btn-success" onClick="upDateStatus(<?=$agent['id']?>,0)">Active</button><?php }else{?><button type="button" class="btn btn-danger" onClick="upDateStatus(<?=$agent['id']?>,1)">Block</button> <?php }?>
                  </td>
                  <?php }?>
                  <?php if(in_array('13.3',$Job_assign)){?>
				      <td> 
            <a class="btn btn-social-icon btn-primary" href="edit_vendor.php?id=<?php echo $agent['id'];?>" ><i class="fa fa-edit"></i></a>

            <a href="javascript:void(0);" onClick="deleteRecord('<?php echo $agent['id'];?>');" class="btn btn-social-icon btn-danger" ><i class="fa fa-trash"></i></a>
                  </td>
                  <?php }?>
                       <td> 
            <a class="btn btn-primary" href="vendor_product_management.php?vndr_id=<?php echo $agent['id'];?>" >Add Product</a>
                  </td>
                </tr>
                <?php $i++; } ?>
               
                </tbody>
                <tfoot>
                <tr>
                 <th>Sl No.</th>
                  <th>Vendor Name</th>
                  <th>Contat No</th>
                  <th>Emails</th>
                  <th>Username</th>
                  <th> Image</th>
                  <th>Shop name</th>
                  <?php if(in_array('13.2',$Job_assign)){?>
                  <th>Status</th>
                  <?php }?>
                  
                   
                  
                  <?php if(in_array('13.3',$Job_assign)){?>
                  <th>Action</th>
                  <?php }?>
                  
                  <th>Assign Product</th>
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
  

 <!-- Select2 -->
  <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>

   <?php include('footer.php')?>
    <!-- Select2 -->
    <script>
  
  function SelectOnPin(arg){
  var url="getAjax.php";
  $.post(url,{"choice":"changPin","value":arg,"pin":"<?=$pin?>"},function(res){
 $('#zcode').html(res);
// alert(res)
});
}
SelectOnPin(<?=$city?>)
  </script>
  
 