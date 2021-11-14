<?php include('header.php')?>
<?php include('sidebar.php')?>
 <?php 
     $Prod_id=$dbf->checkSqlInjection($_REQUEST['id']);
     $product=$dbf->fetchSingle("product",'*',"product_id='$Prod_id'");
 
 
 ?>
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?= $product['product_name']?>
        <small>Variation Price</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Variation Price </li>
      </ol>
    </section>

    <!-- Main content -->
<section class="content container-fluid">
<form action="" enctype="multipart/form-data" method="post">
<input type="hidden" name="product_id" value="<?=  $Prod_id?>" id="product_id">
<div class="row">
        <!-- left column -->
    <div class="col-md-12">
          <!-- general form elements -->
        <div class="box box-primary">
         <div class="row">
             <div class="col-md-4">
             <div class="box-body">
                 <div class="form-group">
                 <label for="measure">Select Measurement</label>
                    <select name="measure" id="measure" class="form-control select2" required style="width:100%;">
                            <option value="">~~Select Measurement~~</option>
                            <?php foreach( $dbf->fetchOrder("price_varition","product_id='$Prod_id'","","","")as $Measures){
                            $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Measures[measure_id]'");
                                ?>
                            <option value="<?= $Measures['price_varition_id']?>"><?= $Measures['units'].$Measure['unit_name']?></option>
                    <?php }?>
                    </select>
                 </div>
                </div> 
             </div>
             <div class="col-md-4">
               <div class="box-body">
                 <div class="form-group">
                 <label for="price">Enter MRP Price</label>
                 <input type="text" name="price" id="price" class="form-control" placeholder="Enter MRP price" required autocomplete="off">
                 </div>
               </div>  
             </div>
             <div class="col-md-4">
              <div class="box-body">
                 <div class="form-group">
                 <label for="price">Enter Sale Price</label>
                <input type="text" name="sprice" id="sprice" class="form-control" placeholder="Enter Sale price" required autocomplete="off">
                 </div>
               </div>  
             </div>
             <div class="col-sm-12">
          <div class="box-footer">
          <button class="btn btn-primary" name="operation" value="AddVariotion" type="button" onclick="AddPrice()">Submit</button>
              </div>
            </div>
         
         </div>
        
              
         <div class="row">
<div class="col-md-12">

<table class="table">
        <thead>
        <tr>
        <th>Measure Ment</th>
        <th>MRP Price</th>
        <th>Sale Price</th>
        <th>Delete</th>
        </tr>
        </thead>

    <tbody id="NewPrice">
    <?php foreach($dbf->fetchOrder("variations_values","vendor_id='$profileuserid' AND product_id='$Prod_id'","variations_values_id DESC ","","") 
		as $Price_Vari){ 

			$VariPrie=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$Price_Vari[price_variation_id]'");

    $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$VariPrie[measure_id]'"); ?>
		 <tr id="PriceVari<?=$Price_Vari['variations_values_id']?>">
		 <td><?= $VariPrie['units'].$Measure['unit_name']?></td>
		 <td><?= $Price_Vari['mrp_price']?></td>
		 <td><?= $Price_Vari['sale_price']?></td>
		 <td><button class="btn btn-danger" onclick="PriceVar(<?=$Price_Vari['variations_values_id']?>)" type="button"><i class="fa fa-trash" aria-hidden="true"></i>
</button></td>
		 </tr><?php } ?>
 </tbody>
     </table>

        </div>
    </div>
</div>
</form>
</section>
    <!-- /.content -->
  </div>

  <script>
    function AddPrice(){
      // alert(arg);
      var product = <?= $Prod_id?>;
      var price_vari = $('#measure').val();
      var mrp_price = $('#price').val();
      var sale_price = $('#sprice').val();
      
	   var url="getAjax.php";
 $.post(url,{"operation":"AddVariotion","measure":price_vari,"mrp_price":mrp_price,"sale_price":sale_price,"vendor_id":<?= $profileuserid?>,"product":product},function(res){
 
 if(res!='error'){
  $('#NewPrice').html(res);
//  alert(res);
 }else{
  alert('Duplicate Date Error!!!');
 }
});
    }

    function PriceVar(arg){
    // alert(arg)
	 var conf=confirm("Are you sure want to delete this Record?");
    if(conf){
       document.getElementById('PriceVari'+arg).style.display = "none"; 
	   
	   var url="getAjax.php";
 $.post(url,{"choice":"PriceVariDel","price_vari":arg},function(res){
//    alert('ss');
//  alert(res);
});
    }
	
	  }
  </script>
   <?php include('footer.php')?>