<?php include('header.php')?>
  <?php include('sidebar.php');

$vendor=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;

  ?>
 
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Report
        <small>All Clearance Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Agent Report</li>
      </ol>
    </section>
  

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
        
            <div class="box-header">
              <h3 class="box-title">All Transcation Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- <form action="" method="post">
            <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Form Date</label>
              <input type="date" class="form-control" name="frm_date" value="<?= $Frm_date_filter?>" readonly>
            </div>
          </div>
            <div class="col-md-3">
            <div class="form-group">
              <label>To Date</label>
              <input type="date" class="form-control" name="to_date"  value="<?= date('Y-m-d')?>">
            </div>
          </div>
           <div class="col-md-3">
            <div class="form-group">
              <br>
              <button class="btn btn-primary" name="operation" value="Fillter">Fillter</button>
              <a href="" class="btn btn-primary">Refresh</a>
            </div>
          </div>
        </div>
      </form> -->
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                 <tr>
                    <th scope="col">Sl No</th>
                    <th scope="col"> From Date </th>
                    <th scope="col"> To Date </th>
                     <th scope="col">Amount Paid</th>
                      <th scope="col">Payment Mode</th>
                       <th scope="col">Remark</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                  $j=1;
                 $Total_amnt=0;
                  foreach($dbf->fetchOrder("payment_vendor","vendor_id='$vendor'","payment_vendor_id DESC","","") as $Payments){
                    ?>
                  <tr>
                  <td><?= $j++?></td>
                  <td><?= date('d.m.Y',strtotime($Payments['from_date']))?></td>
                  <td><?= date('d.m.Y',strtotime($Payments['last_paid_date']))?></td>
                  <td>₹<?= number_format($Payments['amount'],2)?></td>
                  <td><?= $Payments['payment_mode']?></td>
                  <td><?= $Payments['remark']?></td>
                  </tr>

                    
           

                         <?php }?>
                </tbody>

              </table>
            
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>
