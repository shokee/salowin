<?php include('header.php')?>
  <?php include('sidebar.php')?>
  
  <?php 
  
   $Total_wallet_amnt=$dbf->fetchSingle("user",'SUM(wallet) as wallet',"user_type='4'");
    
  ?>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Wallet
        <small>All Customer Wallet</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Wallet Report</li>
      </ol>
    </section>
    
   <section class="content-header">
    
    <div class="" style="padding:0px; margin:0px;">
    <div class="row">
      <div class="col-sm-3"><div class="callout callout-info">
                <h4>Total Wallet Amount  :<br> <b style="font-size:24px"> Rs. <?=  number_format($Total_wallet_amnt['wallet'],'2')?></b></h4>
              </div></div>
      
              
              
     </div>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box">
            <div class="box-header">
              <h3 class="box-title">All Transcation Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped" >
                <thead>
                <tr>
                 <th>Sl No.</th>
                  <th>User Name</th>
                  <th>Amount</th>
                  <th>Wallet</th>
                </tr>
                </thead>
                <tbody>
               
                 <?php
          $i=1;

           foreach($dbf->fetchOrder("user","user_type='4' AND wallet!='0'","id ASC","","") as $agent){
                   ?>

                   <tr>
                       <td><?= $i++?></td>
                       <td><?= $agent['full_name']?></td>
                       <td>&#8377;<?= $agent['wallet']?></td>
                       <td><button class="btn btn-primary" data-toggle="modal" data-target="#walletHistory<?= $agent['id']?>">Wallet History</button></td>
                       <!-- Button trigger modal -->


                   </tr>
                <?php  } ?>
               
                </tbody>
                <tfoot>
                <tr>
                 <th>Sl No.</th>
                  <th>User Name</th>
                  <th>Amount</th>
                  <th>Wallet</th>
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
  <!-- Modal -->
  <?php    foreach($dbf->fetchOrder("user","user_type='4' AND wallet!='0'","id ASC","","") as $agent){?>
<div class="modal fade" id="walletHistory<?= $agent['id']?>" tabindex="-1" role="dialog" aria-labelledby="walletHistory" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Wallet History</h5>
      </div>
      <div class="modal-body">
      <table class="table table-bordered table-striped" >
                <thead>
                <tr>
                 <th>Sl No.</th>
                  <th>Amount</th>
                  <th>Payment Type</th>
                  <th>Remark</th>
                  <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php    
                $j=1;
                foreach($dbf->fetchOrder("wallet_histru","user_id='$agent[id]'","wallet_histru_id ","","") as $Payment_history){?>
                <tr>
                  <td><?= $j++?></td>
                  <td>Rs.<?=   $Payment_history['amount'] ?></td>
                  <td><?php if($Payment_history['pay_type']==1){ echo"Credit"; }else{ echo"Debit";} ?></td>
                  <td><?=   $Payment_history['remark'] ?></td>
                  <td><?=   date('d-m-Y h:m:s a' ,strtotime($Payment_history['date'])) ?></td>
                </tr>
                <?php }?>
                </tbody>
                <thead>
                <tr>
                 <th>Sl No.</th>
                  <th>Amount</th>
                  <th>Payment Type</th>
                  <th>Remark</th>
                  <th>Date</th>
                </tr>
                </thead>
        
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <?php }?>
   <?php include('footer.php')?>
