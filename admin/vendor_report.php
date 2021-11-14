<?php include('header.php')?>
  <?php include('sidebar.php')?>
  
  <?php 
if($_SESSION['usertype']=='1'){
  $loc="";
  $locCond="";
}else{    
  $Loc_Pin=$dbf->fetchSingle("city",'city_name',"city_id='$_SESSION[city]'");
  $loc = " AND city='$Loc_Pin[city_name]'";
  $locCond=" AND city_id='$_SESSION[city]'";
}

  $Total_Sale=0;
$Array_of_vendor=array();
foreach($dbf->fetchOrder("orders","status IN(4,7)".$loc,"","qty,price,vendor_id","") as $Total_cal){
  $Total_Sale+=($Total_cal['qty']*$Total_cal['price']);
  array_push($Array_of_vendor,$Total_cal['vendor_id']);
}
if($_SESSION['usertype']=='1'){
  $TotalPaid=$dbf->fetchSingle("payment_vendor",'SUM(amount) as Payment',"");
  $TotalComision=$dbf->fetchSingle("payment_vendor",'SUM(comm_amnt) as Payment',"");
} else{
  if(empty($Array_of_vendor)){
    $vendorfnd=implode(',',$Array_of_vendor);
    $vendorfnd  = " vendor_id IN ($vendorfnd)";
  }else{
    $vendorfnd = "";
  }
  $TotalPaid=$dbf->fetchSingle("payment_vendor",'SUM(amount) as Payment ',$vendorfnd);
  $TotalComision=$dbf->fetchSingle("payment_vendor",'SUM(comm_amnt) as Payment',$vendorfnd);
}  


  //   $Total_Vendor_Amnt = $Total_Sale - $Total_comi;
  //  $Totaldue = $Total_Vendor_Amnt - $TotalPaid['Payment'];

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
      <div class="col-sm-3"><div class="callout callout-info">
                <h4>Total Sales  :<br> <b style="font-size:24px"> &#8377;. <?=  number_format($Total_Sale,'2')?></b></h4>
              </div></div>
      
        <div class="col-sm-3"><div class="callout callout-warning">
                <h4>Total Paid  : <b style="font-size:24px"> <br> &#8377;. <?= number_format($TotalPaid['Payment'],'2')?></b></h4>
              </div></div>
              <div class="col-sm-3"><div class="callout callout-danger">
                <h4>Total Commission  : <b style="font-size:24px"><br>  &#8377;. <?= number_format($TotalComision['Payment'],'2')?></b></h4>
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
              <table id="example1" class="table table-bordered table-striped" >
                <thead>
                <tr>
                 <th>Sl No.</th>
                  <th>Vendor Name</th>
                  <th>Shop name</th>
                  <th>Total Amount</th>
                 
                  <!-- <th>Vendor Amount</th> -->
                  <th>Paid Amount <br>To Vendor </th>
                  
                  <th> Action</th>
                </tr>
                </thead>
                <tbody>
               
                 <?php
          $i=1;

           foreach($dbf->fetchOrder("user","user_type='3'".$locCond,"id ASC","","") as $agent){
                     $Vendor_total_amnt=0;
                      $Vendor_comission=0;
                      $Vendor_rel_amn=0;
                          foreach($dbf->fetchOrder("orders","vendor_id='$agent[id]' AND  status IN(4,7)","","","") as $Vendor_amnt){
                                $Vendor_total_amnt=$Vendor_total_amnt+($Vendor_amnt['qty']*$Vendor_amnt['price']);
                             $Vendor_comission+=$Vendor_amnt['commssion_amount'];
                              $Vendor_rel_amn+= $Vendor_total_amnt-$Vendor_comission;
                            }
                $PaidAmont=$dbf->fetchSingle("payment_vendor","SUM(amount) as paid_amnt" ,"vendor_id='$agent[id]'");
                $Vendor_amnt = $Vendor_total_amnt-$Vendor_comission;
          ?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $agent['full_name'];?></td>
                  <td><?php echo $agent['shop_name'];?></td>
                   <td><?php if($Vendor_total_amnt!=''){ echo '₹'.number_format($Vendor_total_amnt,2);}else{ echo "₹0";}?>
                     
                   </td>
                 
                  <!-- <td>₹<?= $Vendor_amnt?></td> -->
                  <td>₹<?php if($PaidAmont['paid_amnt']!=''){ echo $PaidAmont['paid_amnt'];}else{ echo "0";}?> </td>
                  

                 <td> 
                 <?php if(in_array('26.1',$Job_assign)){?>
                 <a href="single_vendor_report.php?editId=<?php echo $agent['id'];?>" class="btn btn-sm btn-success">Order Details</a>
                 <?php }?>
                 <?php if(in_array('26.2',$Job_assign)){?>
                  <a href="single_vendorpayment_report.php?editId=<?php echo $agent['id'];?>" class="btn btn-sm btn-success">Make Clearance</a>
                 <?php }?>
                 <?php if(in_array('26.3',$Job_assign)){?>
                 <a href="clearance_report.php?editId=<?php echo $agent['id'];?>" class="btn btn-sm btn-success">All Clearance Details</a>
                 <?php }?>
                </td>
                </tr>
                <?php $i++; } ?>
               
                </tbody>
                <tfoot>
                <tr>
                <th>Sl No.</th>
                  <th>Vendor Name</th>
                  <th>Shop name</th>
                  <th>Total Amount</th>
                  
                  <!-- <th>Vendor Amount</th> -->
                  <th>Paid Amount <br>To Vendor </th>
                  
                  <th> Action</th>
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
