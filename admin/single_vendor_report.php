<?php include('header.php')?>
  <?php include('sidebar.php')?>
  <?php 
$vendor=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;
$vendorid=$dbf->fetchSingle("user",'*',"id='$vendor'");


?>


<?php 
if(isset($_REQUEST['action']) && $_REQUEST['action']=='StatusUpdate'){
  $status = $_POST['cstatus'];
  $order_id = $_POST['order_id'];
$dbf->updateTable("orders","status='$status'","orders_id='$order_id'");
header("Location:orders.php");
}?>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Orders
        <small>Manage All Orders Here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Orders</li>
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
                 
                  foreach($dbf->fetchOrder("orders","vendor_id='$vendor' AND  status IN(4,7)","created_date DESC","","order_id") as $Order){
                     $addres=$dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");
                     $vendor=$dbf->fetchSingle("user",'*',"id='$Order[vendor_id]'");
                      $Vendor_total_amnt=0;
                      $Vendor_comission=0;
                      $Vendor_rel_amn=0;
                      foreach($dbf->fetchOrder("orders","order_id='$Order[order_id]' AND status IN(4,7)","","","") as $Vendor_amnt){
                     $Vendor_total_amnt=$Vendor_total_amnt+($Vendor_amnt['qty']*$Vendor_amnt['price']);
                     $Vendor_comission+=$Vendor_amnt['commssion_amount'];
                      $Vendor_rel_amn= $Vendor_total_amnt-$Vendor_comission;
                   }
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
             <img src="images/<?php echo $page['logo'];?>" width="100" >
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
          <td>&#8377; . <?= $singleorder['price']?></td>
          <td><?= $singleorder['qty']?></td>
          <td> <?php if($singleorder['status']=='5'){?><span style="color:red;font-size: 20px;font-weight: bold;">-</span><?php }?>
          &#8377; .<?php echo $singleorder['price']* $singleorder['qty']; ?>.00</td>
          </tr>
          <?php $i++; } ?>
          <tr>
          <td></td>
          <td></td>
          <td>TOTAL</td>
          <td><b><?= $totalqty['total_qty']?></b></td>
          <td><b>&#8377; .<?= $prototal['pro_total']-$return_Amnt?></b></td>
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
          <td><b>&#8377; . <?= $singleorder['shipping_charge']?></b></td>
          </tr>
          
          <tr>
          <td></td>
          <td></td>
          <td><span class="uk-text-danger">SUB TOTAL</span></td>
          <td><b></b></td>
          <td><b>&#8377; . <?php echo number_format($prototal['pro_total']+ $singleorder['shipping_charge']-$singleorder['wallet']-$return_Amnt,2); ?></b></td>
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
                <tfoot>
               <tr>
                      <th scope="col">Sl No</th>
                    <th scope="col">Order Id </th>
                    <th scope="col">Order Date</th>
                     <th scope="col">Total Amount </th>
                    <th scope="col">View Details </th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
    
   <!-- gallery --> 
     <!-- Modal -->
<script type="text/javascript">
     function printPageArea(areaID){
      
    var printContent = document.getElementById(areaID);
    var WinPrint = window.open('', '', 'width=1000,height=700');
    WinPrint.document.write(printContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}

  </script>
  </div>
   <?php include('footer.php')?>
