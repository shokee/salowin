<?php include('header.php')?>
<?php include('sidebar.php')?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Payment
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Payment</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">

 

            
            
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Slno.</th>
                  <th> Date</th>
                  <th>Amount</th>
                  <th>Payment Mode</th>
                  <th>Remark</th>
                </tr>
                </thead>
                <tbody>
                
          <?php
		  $i=1;
		  foreach($dbf->fetchOrder("payment_vendor","vendor_id='$profileuserid'","payment_vendor_id DESC","","") as $resBanner){
		  ?>
          <tr>
          <td><?php echo $i;?></td>
          <td><?= date('d.m.Y',strtotime($resBanner['date']))?></td>
          <td>Rs.<?= number_format($resBanner['amount'],2)?>/-</td>
          <td><?= $resBanner['payment_mode']?></td>
          <td><?= $resBanner['remark']?></td>
  
          </tr>
          <?php $i++; } ?>
          
                </tbody>
                <tfoot>
                <tr>
                  <th>Slno.</th>
                  <th> Date</th>
                  <th>Amount</th>
                  <th>Payment Mode</th>
                  <th>Remark</th>
                </tr>
                </tfoot>
              </table>
            
              
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


   <?php include('footer.php')?>
