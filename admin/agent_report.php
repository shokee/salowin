 <?php include('header.php')?>
  <?php include('sidebar.php')?>
  
  <?php 
  $loansubtotal= $dbf->fetchSingle("transction",'SUM(transction_amount) as loan_amnt',"transction_type='1'"); 
  $rcvsubtotal= $dbf->fetchSingle("transction",'SUM(transction_amount) as loan_amnt',"transction_type='0'"); 
  $totaldue_amnt = $loansubtotal['loan_amnt'] - $rcvsubtotal['loan_amnt'];
  ?>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Report
        <small>All Customer Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Agent Report</li>
      </ol>
    </section>
    
   <section class="content-header">
    
    <div class="" style="padding:0px; margin:0px;">
    <div class="row">
    	<div class="col-sm-4"><div class="callout callout-info">
                <h4>Total Loan  : <b style="font-size:24px"> Rs. <?php echo $loansubtotal['loan_amnt'];?></b></h4>
              </div></div>
        <div class="col-sm-4"><div class="callout callout-success">
                <h4>Total Collection  : <b style="font-size:24px"> Rs. <?php echo $rcvsubtotal['loan_amnt'];?></b></h4>
              </div></div>
        <div class="col-sm-4"><div class="callout callout-warning">
                <h4>Total Due  : <b style="font-size:24px"> Rs. <?php echo $totaldue_amnt ;?></b></h4>
              </div></div>
    </div>
   			  
              
              
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
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>sl no</th>
                  <th>Agent Name</th>
                   <th>Contat No</th>
                  <th>Email</th>
                  <th>Collection Amount</th>
                  <th>Ation</th>
                </tr>
                </thead>
                <tbody>
                 <?php
					$i=1;
					 foreach($dbf->fetchOrder("admin","type='2'","id ASC","","") as $agent){
					 $transction = $dbf->fetchSingle("transction",'*',"transction_id='$agent[id]'");
					  $LoanAmnt= $dbf->fetchSingle("transction",'SUM(transction_amount) as loan_amnt',"agent_id='$agent[id]' AND transction_type='0'");
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $agent['full_name'];?></td>
                  <td><?php echo $agent['contact_no'];?></td>
                  <td><?php echo $agent['email'];?></td>
                  <td>Rs. <?php echo $LoanAmnt['loan_amnt'];?></td>
                  
				<td> 
 <a class="btn btn-social-icon btn-primary btn-sm" href="single_agent_transction_report.php?editId=<?php echo $agent['id'];?>" ><i class="fa fa-eye"></i></a>
                  </td>
                </tr>
                <?php $i++; } ?>
                
                </tbody>
                <tfoot>
                <tr>
                   <th>sl no</th>
                  <th>Agent Name</th>
                   <th>Contat No</th>
                  <th>Email</th>
                  <th>Collection Amount</th>
                  <th>Ation</th>
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
