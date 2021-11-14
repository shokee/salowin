<?php include('header.php')?>
<?php include('sidebar.php')?>
   
   <?php 
  $Prod_id=$dbf->checkSqlInjection($_REQUEST['id']);
  $product=$dbf->fetchSingle("product",'product_name',"product_id='$Prod_id'");
?>
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?= $product['product_name']?>
        <small> Of Varitions</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Product</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
<form action="" enctype="multipart/form-data" method="post">
<input type="hidden" name="product_id" id="product_id" value="<?php echo $Prod_id;?>">
       <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
          <div class="row">
          	<div class="col-sm-4">
            <!-- form start -->
              <div class="box-body">
                <div class="form-group">
                <label for="unit<?= $Prod_id?>">Enter Weight</label>
                 <input type="text" name="unit" id="unit" class="form-control" placeholder="Enter Weight" required autocomplete="off">
                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <div class="col-sm-4">
            <!-- form start -->
              <div class="box-body">
                <div class="form-group">
                <label for="measure">Select Measurement</label>
                <select name="measure" id="measure" class="form-control select2" required style="width:100%;">
                    <option value="">~~Select Measurement~~</option>
                    <?php foreach( $dbf->fetchOrder("units","","unit_name","","")as $Measures){?>
                    <option value="<?= $Measures['unit_id']?>"><?= $Measures['unit_name']?></option>
                    <?php }?>
                </select>
                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <div class="col-sm-4">
          <div class="box-footer">
		  <br>
          <button class="btn btn-primary" name="operation" value="AddVariotion" type="button" onclick="AddPrice(<?= $Prod_id?>)">Submit</button>
              </div>

            </div>
          </div>
            

              
<div class="row">
<div class="col-md-12">
<table class="table">
      <thead>
      <tr>
      <th>Weight</th>
      <th>Measure Ment</th>
      <th>Delete</th>
      </tr>
      </thead>

  <tbody id="NewPrice">
      
<?php foreach($dbf->fetchOrder("price_varition","product_id='$Prod_id'","price_varition_id DESC ","","") 
as $VariPrie){  $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$VariPrie[measure_id]'"); ?>
 <tr id="PriceVari<?=$VariPrie['price_varition_id']?>">
 <td><?= $VariPrie['units']?></td>
 <td><?= $Measure['unit_name']?></td>
 <td><button class="btn btn-danger" onclick="PriceVar(<?=$VariPrie['price_varition_id']?>,<?= $Prod_id;?>)" type="button"><i class="fa fa-trash" aria-hidden="true"></i>
</button></td>
 </tr> 
<?php }?>   

</tbody>
   </table>
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
  <script>

    function AddPrice(arg){
      // alert(arg);
      var product = $('#product_id').val();
      var unit = $('#unit').val();
      var measure = $('#measure').val();      
	   var url="getAjax.php";
 $.post(url,{"operation":"AddVariotion","unit":unit,"measure":measure,"product_id":product},function(res){
 
 if(res!='error'){
  $('#NewPrice').html(res);
//  alert(res);
 }else{
  alert('Duplicate Date Error!!!');
 }
});
    }


    function PriceVar(arg,prod_id){
	 var conf=confirm("Are you sure want to delete this Record?");
    if(conf){
       document.getElementById('PriceVari'+arg).style.display = "none"; 
	   
	   var url="getAjax.php";
 $.post(url,{"choice":"PriceVariDel","price_vari":arg,"product_id":prod_id},function(res){
//    alert('ss');
//  alert(res);
});
    }
	
	  }
  </script>
   <?php include('footer.php')?>