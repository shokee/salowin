<?php include('header.php')?>
<?php include('sidebar.php')?>
 
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?= $product['product_name']?>
        <small>Gallery Image</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Product</li>
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

<?php
$All_Gallery=$dbf->fetchOrder("gallery","product_id='$Prod_id'","gallery_id ASC","","");
    $i=1;
    if(!empty($All_Gallery)){
		foreach($All_Gallery as $gallery){
		?>
                <div class="col-sm-3">
                <div class="gg" id="imgDel<?= $gallery['gallery_id']?>">
                <button class="btn btn-danger btt" id="idel" name="idel" type="button"  onClick="DelImg(<?=$gallery['gallery_id']?>)">X</button>
                <img src="images/gallery/thumb/<?php echo $gallery['image'];?>" width="100%">
                </div>
                
                </div>
         <?php $i++; } }else{ echo' <div class="col-sm-12"><img src="https://cdn.dribbble.com/users/1228320/screenshots/6439736/nodata.png"></div>';}?>
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

  
   <?php include('footer.php')?>