<?php include('header.php')?>
  <?php include('sidebar.php')?>
  
  <?php 
 
  $vendor=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;
  $profile=$dbf->fetchSingle("user",'*',"id='$vendor'");

  $Total_Sale=0;

foreach($dbf->fetchOrder("orders","status IN(4,7) AND vendor_id='$vendor'","","","") as $Total_cal){
  $Total_Sale+=($Total_cal['qty']*$Total_cal['price']);
}
   $TotalPaid=$dbf->fetchSingle("payment_vendor",'SUM(amount) as Payment',"vendor_id='$vendor'");
   
   $TotalComision=$Total_Sale*$profile['commition']/100;
   
  // $TotalComision=$dbf->fetchSingle("payment_vendor",'SUM(comm_amnt) as Payment',"vendor_id='$vendor'");
    // $Total_Vendor_Amnt = $Total_Sale - $Total_comi;
  //  $Totaldue = $Total_Vendor_Amnt - $TotalPaid['Payment'];



$CntPayment=$dbf->fetchSingle("payment_vendor",'last_paid_date',"vendor_id='$vendor' ORDER BY payment_vendor_id DESC");
   if(!empty($CntPayment)){
    $Frm_date_filter=$CntPayment['last_paid_date'];
     $Frm_date_filter=date('Y-m-d', strtotime($Frm_date_filter. ' + 1 days'));
                          
   }else{
    $CntPayment=$dbf->fetchSingle("orders",'created_date',"vendor_id='$vendor' ORDER BY orders_id ASC");
    $Frm_date_filter=date('Y-m-d',strtotime($CntPayment['created_date']));
   }

   // $Frm_date_filter=y



$condi="";
//fillter
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='Fillter'){
  
                                $sch="";
                       
                        if($_POST['frm_date']!=""){
                            $sch=$sch." AND created_date BETWEEN '$_POST[frm_date] 00:00:00' AND ";
                        }
                        if($_POST['to_date']!=""){
                          $to_Date=date('Y-m-d', strtotime($_POST['to_date']));
                          $to_Dates=date('Y-m-d', strtotime($_POST['to_date'].' + 1 days'));
                           $sch=$sch." '$to_Dates 00:00:00'";
                        }
                            $condi=$sch;
                        
                      // echo $condi;exit;
  

}else{
  $todate=date('Y-m-d');
 $to_Date=date('Y-m-d', strtotime($todate.' + 1 days'));
  $condi = " AND created_date BETWEEN '$Frm_date_filter 00:00:00' AND '$to_Date 00:00:00'";
}

if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='Paid'){
  $vendor_id = $_POST['vendor_id'];
  $total_paid_amnt = $_POST['total_paid_amnt'];
  $last_paid_date = $_POST['last_paid_date'];
  $frm_date = $_POST['frm_date'];
  $remark = $_POST['remark'];
  $paymode = $_POST['paymode'];
  $commision_amnt=$_POST['commision_amnt'];
  $commision_percent=$_POST['commision_percent'];

  $string = "date=NOW(),last_paid_date='$last_paid_date',amount='$total_paid_amnt',payment_mode='$paymode',remark='$remark',from_date='$frm_date',vendor_id='$vendor_id',comm_amnt='$commision_amnt',comi_percent='$commision_percent'";
  $dbf->insertSet("payment_vendor",$string);
  header("Location:single_vendorpayment_report.php?editId=".$vendor_id);exit;
  }
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
                <h4>Total Sales  :<br> <b style="font-size:24px"> Rs. <?=  number_format($Total_Sale,'2')?></b></h4>
              </div></div>
      
        <div class="col-sm-3"><div class="callout callout-warning">
                <h4>Total Paid  : <b style="font-size:24px"> <br> Rs. <?= number_format($TotalPaid['Payment'],'2')?></b></h4>
              </div></div>
              <div class="col-sm-3"><div class="callout callout-danger">
                <h4>Total Commission  : <b style="font-size:24px"><br>  Rs. <?= number_format($TotalComision,'2')?></b></h4>
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
              <form action="" method="post">
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
              <input type="date" class="form-control" name="to_date"  value="<?= $to_Date?>">
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
      </form>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                 <tr>
                    <th scope="col">Sl No</th>
                    <th scope="col">Order Id </th>
                    <th scope="col">Order Date</th>
                     <th scope="col">Total Amount </th>
                    <th scope="col">View Details </th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                  $j=1;
                 $Total_amnt=0;
                  foreach($dbf->fetchOrder("orders","vendor_id='$vendor' AND  status IN(4,7) ".$condi,"created_date DESC","","order_id") as $Order){
                     $addres=$dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");
                   
                      $Vendor_total_amnt=0;
                      $Vendor_comission=0;
                      $Vendor_rel_amn=0;
                      foreach($dbf->fetchOrder("orders","order_id='$Order[order_id]' AND status IN(4,7)","","","") as $Vendor_amnt){
                     $Vendor_total_amnt=$Vendor_total_amnt+($Vendor_amnt['qty']*$Vendor_amnt['price']);
                     $Vendor_comission+=$Vendor_amnt['commssion_amount'];
                      $Vendor_rel_amn= $Vendor_total_amnt-$Vendor_comission;

                      
                   }
                   $Total_amnt+=$Vendor_total_amnt;
                      // $vendorTotalAmnt=$dbf->fetchSingle("orders","SUM(price) as ven_amnt" ,"order_id='$Order[order_id]' AND  status IN(4,7)'");
                    
                    ?>
                  <tr>
                  <td><?= $j++?></td>
                  <td><?= $Order['order_id']?></td>
                  <td><?= date('d.m.Y',strtotime($Order['created_date']))?></td>
                  <td>₹<?= number_format($Vendor_total_amnt,2)?></td>
                  <td>
                  <button class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-example<?= $Order['order_id']?>" > View Details</button>
                  
    
            <div id="modal-example<?= $Order['order_id']?>" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
           <div id="print_area<?= $Order['order_id']?>">
    <div class="uk-grid">
       <div style="text-align: center;"  class="uk-width-expand">
       <img src="../admin/logincss/images/img-01.png" width="100" >
    </div>
      <div class="uk-width-auto">Order Id : <?= $Order['order_id']?></div>
      <div class="uk-width-expand"></div>
      <div class="uk-width-auto" style="text-align: right;">Date: <?= date('d.m.Y',strtotime($Order['created_date']))?></div>
    </div>
    <hr />
      <h4 class="uk-text-center uk-margin-remove-top">Order Summery</h4>
        <div  style="border:solid 1px #ccc;">
        <table class="uk-table uk-table-small uk-table-divider">
        <tr class="uk-background-muted">
          <th>Sl No</th>
            <th>Product Details</th>
            <th> Price</th>
            <th> Qty</th>
            <th> Total</th>
        </tr>
        
         <?php
  $i=1;
  $return_Amnt=0;
   foreach($dbf->fetchOrder("orders","order_id='$Order[order_id]' ","orders_id DESC","","") as $singleorder){
  $totalqty= $dbf->fetchSingle("orders",'SUM(qty) as total_qty',"order_id='$Order[order_id]'");
  $prototal= $dbf->fetchSingle("orders",'SUM(qty*price) as pro_total',"order_id='$Order[order_id]'");
  $ReturnAmnt= $dbf->fetchSingle("orders",'qty,price',"orders_id='$singleorder[orders_id]' AND status='5'");
 $return_Amnt=$return_Amnt+$ReturnAmnt['qty']*$ReturnAmnt['price'];

  ?>
    
    <tr>
    <td><?php echo $i;?></td>
    <td><?= $singleorder['ordername']?><?php if($singleorder['status']=='5'){?><span style="color:red;font-size: 20px;">(Returned)</span><?php }?></td>
    <td><?= $singleorder['price']?></td>
    <td><?= $singleorder['qty']?></td>
    <td><?php if($singleorder['status']=='5'){?><span style="color:red;font-size: 20px;font-weight: bold;">-</span><?php }?>
    <?php echo $singleorder['price']* $singleorder['qty']; ?>.00</td>
    </tr>
    <?php $i++; } ?>
    <tr>
    <td></td>
    <td></td>
    <td>TOTAL</td>
    <td><b><?= $totalqty['total_qty']?></b></td>
    <td><b><?= $prototal['pro_total']-$return_Amnt?></b></td>
    </tr>
     <?php if($singleorder['wallet']!=0){?>
     <tr>
    <td></td>
    <td></td>
    <td>WALLET:</td>
    <td></td>
    <td><b>-<?= number_format($singleorder['wallet'],2)?></b></td>
    </tr>
  <?php }?>
     <tr>
    <td></td>
    <td></td>
    <td>SHIPPING CHARGE</td>
    <td><b></b></td>
    <td><b><?= $singleorder['shipping_charge']?></b></td>
    </tr>
    
    <tr>
    <td></td>
    <td></td>
    <td><span class="uk-text-danger">SUB TOTAL</span></td>
    <td><b></b></td>
    <td><b><?php echo number_format($prototal['pro_total']+ $singleorder['shipping_charge']-$singleorder['wallet']-$return_Amnt,2); ?></b></td>
    </tr>
    </table>
        </div>
        <?php $address = $dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");   ?>
        <?php $city = $dbf->fetchSingle("city",'*',"city_id='$address[city_id]'"); 
          $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$address[pincode]'");
        ?>
        <h5>Address</h5>
       
  <p> 
  Name: <?php echo $address['first_name'];?> <?php echo $address['last_name'];?><br />
  Contact No: <?php echo $address['number'];?> <br />
  Email : <?php echo $address['email'];?> <br />
  Address: <?php echo $address['address'];?>, <br />
  <?php echo $city['city_name'];?>,  Pin: <?php echo $pincode['pincode'];?>  <br />
  </p>
  </div>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
            <button class="uk-button uk-button-primary" type="button" onclick="printPageArea('<?='print_area'.$Order['order_id']?>')">Print</button>
        </p>
    
  </div>
</div>
                  </td>
                  </tr>

                    
           

                         <?php }?>
                </tbody>

              </table>
              <form action="" method="post">
                <input type="hidden" name="vendor_id" value="<?= $vendor?>">
                <div class="row">
                  <div class="col-sm-2"><h4>Total Amount:<br>Commission :<br>Paid Amount:</h4></div>
                  <div class="col-sm-2">
                    <h4><?= number_format($Total_amnt,2)?><br>
                   
                      <?php 
                        $ComisionPercent = $dbf->fetchSingle("commsion_slab",'dis_percent',"frm_amnt<='$Total_amnt' AND to_amnt>=$Total_amnt");
                       
                         $Comision=($Total_amnt*$profile['commition'])/100;?>
                        - <?= number_format($Comision,2)?>(<?= number_format($profile['commition'],2)?>%)
                         <br>
                      <?php    
                       echo $Vendor_amnt=number_format($Total_amnt-$Comision,2);
                         $Vendor_amnt=$Total_amnt-$Comision;
                       ?>
                    </h4>
                    <input type="hidden" name="frm_date" value="<?= $Frm_date_filter?>">
                    <input type="hidden" name="total_paid_amnt" value="<?= $Vendor_amnt?>">
                    <input type="hidden" name="commision_amnt" value="<?= $Comision?>">
                    <input type="hidden" name="commision_percent" value="<?= $profile['commition']?>">
                    <?php if($_POST['to_date']!=''){?>
                    <input type="hidden" name="last_paid_date" value="<?= $_POST['to_date']?>">
                  <?php }else{?>
                     <input type="hidden" name="last_paid_date" value="<?= date('Y-m-d')?>">
                <?php }?>
                  </div>
                  <div class="col-md-3">
                    <input type="text" name="remark" class="form-control" placeholder="Enter Your Remark" autocomplete="off">

                  </div>
                  <div class="col-md-3">
                    <input type="text" name="paymode" class="form-control" placeholder="Enter Payment Mode" autocomplete="off">
                    
                  </div>
                  <div class="col-sm-2"><button class="btn btn-success" name="operation" value="Paid">Clear Transaction</button></div>
                </div>
              </form>
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>
