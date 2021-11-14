<?php include('header.php')?>
<?php include('sidebar.php')?>
  
<?php 
 ########################## insert state #############################
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addcity'){
	
    $country=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
    $state=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
    $city=$_REQUEST['city_name'];
        
        
    $string="product_catagory_1_id='$country',product_catagory_2_id='$state', product_catagory_3_name='$city',  created_date=NOW()";
    $dbf->insertSet("product_catagory_3",$string);
        
        
    header("Location:category3add.php?msg=success");
        
    }
?>


 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Subcategory 
        <small>Subcategory</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Subcategory 2</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
<form action="" enctype="multipart/form-data" method="post">

       <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
          <div class="row">
          <div class="col-sm-12">
          <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
        <div class="uk-alert-success" uk-alert>
        <a class="uk-alert-close" uk-close></a>
        <p>Subcategory Added Successfully</p>
        </div>
        <?php }?>
        <div>
          	<div class="col-sm-6">
              <div class="box-body">
                <div class="form-group">
                <label>Select catagory Name</label>
                <select class="form-control" name="country_id" required onchange="GetSubCategory(this.value)">
                <option value="">~~Select Category~~</option>
    			<?php  foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","product_catagory_1_name,product_catagory_1_id","") as $DirName){?>
    			<option value="<?=$DirName['product_catagory_1_id']?>" ><?=$DirName['product_catagory_1_name']?></option>
   			    <?php }?>
    			</select>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="box-body">
                <div class="form-group">
                <label>Select Sub catagory</label>
                <select class="form-control" name="state_id" required id="state_id">
                <option value="">~~Select Subcategory~~</option>
    			
    			</select>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="box-body">
                <div class="form-group">
                <label>Enter sub catagory 2</label>
                <input type="text" class="form-control" name="city_name" id="city_name" placeholder="Enter subcatagory 2 Name" />
                </div>
              </div>
            </div>
            <div class="col-sm-12">
          <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-left " value="addcity" name="submit">Submit</button>
              </div>
            </div>
          </div>
            

              
          </div>
          <!-- /.box -->
          </div>
           
          </div>
</form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Select2 -->
   <?php include('footer.php')?>
<script>
  function  GetSubCategory(val) {
       // alert(val);
    
//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getSubcate","value":val},function(res){
 $('#state_id').html(res);
// alert(res)
});
  
  } 

</script>