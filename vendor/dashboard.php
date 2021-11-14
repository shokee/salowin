<?php include('header.php')?>
<?php include('sidebar.php')?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboardoard</a></li>
        <li class="active">Dashboardoard</li>
      </ol>
    </section>
<?php 
 $Total_Sale=0;

 foreach($dbf->fetchOrder("orders","status IN(4,7) AND vendor_id='$profileuserid'","","","") as $Total_cal){
   $Total_Sale+=($Total_cal['qty']*$Total_cal['price']);
 }
 $TotalPaid=$dbf->fetchSingle("payment_vendor",'SUM(amount) as Payment',"vendor_id='$profileuserid'");
 $TotalComision=$dbf->fetchSingle("payment_vendor",'SUM(comm_amnt) as Payment',"vendor_id='$profileuserid'");


?>




    <!-- Main content -->
    <section class="content">
    
      <!-- Main row -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= number_format($dbf->countRows("orders","vendor_id='$profileuserid' AND status='0'",""));?></h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="orders.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= number_format($dbf->countRows("orders","vendor_id='$profileuserid'",""));?><sup style="font-size: 20px"></sup></h3>

              <p>All Orders </p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="orders.php"  class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= number_format($dbf->countRows("orders","vendor_id='$profileuserid' AND status='4'",""));?></h3>

              <p>Complite Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="user.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        
        <!-- ./col -->
      </div>
      <!-- /.row (main row) -->
<div class="row">
    	<div class="col-sm-3"><div class="callout callout-info">
                <h4>Total Sales  :<br> <b style="font-size:24px"> Rs. <?= number_format($Total_Sale,2)?></b></h4>
              </div></div>
        <div class="col-sm-3"><div class="callout callout-success">
                <h4>Total Commition  : <b style="font-size:24px"><br> Rs.<?php echo number_format($TotalComision['Payment'],2); ?></b></h4>
              </div></div>
        <div class="col-sm-3"><div class="callout callout-warning">
                <h4>Total receive  : <b style="font-size:24px"> <br> Rs.<?= number_format($TotalPaid['Payment'],2)?></b></h4>
              </div></div>
          
              
    </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>
