<?php include('header.php')?>
  <?php include('sidebar.php')?>
  
<?php 
if(isset($_REQUEST['action']) && $_REQUEST['action']=='StatusUpdate'){
  $status = $_POST['status'];
  $order_id = $_POST['order_id'];

$dbf->updateTable("orders","status='$status'","orders_id='$order_id'");
if($status=='5'){
  $Orders=$dbf->fetchSingle("orders",'user_id,price',"orders_id='$order_id'");
   $Users=$dbf->fetchSingle("user",'wallet',"id='$Orders[user_id]'");
    $amnt=$Users['wallet']+$Orders['price'];
$dbf->updateTable("user","wallet='$amnt'","id='$Orders[user_id]'");
}
header("Location:retundprod.php");
}?>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Orders Returned
        <small>Manage All Returned Here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"> Orders Returned</li>
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
                    <th scope="col">Vendor Name</th>
                     <th scope="col">Payment </th>
                      <th scope="col">Reason</th>
                     <th scope="col">Status </th>
                     <th scope="col">View Details </th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                  $i=1;
                  foreach($dbf->fetchOrder("orders","status IN(5,6,7) AND vendor_id='$profileuserid'","created_date DESC","","") as $Order){
                     $addres=$dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");
                     $vendor=$dbf->fetchSingle("user",'*',"id='$Order[vendor_id]'");
                    ?>
                  <tr>
                  <td><?= $i++?></td>
                  <td><?= $Order['order_id']?></td>
                  <td><?= date('d.m.Y',strtotime($Order['created_date']))?></td>
                  <td><?= $vendor['shop_name']?></td>
                  <td><?= $Order['payment_mode']?></td>
                  <td><?= $Order['reason']?></td>
                <td>
                     <?php if($Order['status']=='6'){?>
                  <button class="uk-button uk-button-small uk-button-primary"  >
                New Returned 
                 </button>
               <?php }else if($Order['status']=='5'){?>
                  <button class="uk-button uk-button-small uk-label-success">Return Compeleted</button>
               <?php }else{?>
                   <button class="uk-button uk-button-small uk-button-danger">Return Canceled</button>
               <?php }?>
   
             
                  </td>
             
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
  Address1: <?php echo $address['adress1'];?>, <br />
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
                <div class="modal fade" id="myModal<?= $Order['orders_id']?>" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Shipping Address</h4>
        </div>
        <div class="modal-body">
       <?= $addres['first_name'].' '.$addres['first_name'].','.$addres['email'].',<br>'.$addres['number'].','.$city['city_name'].','.$addres['address'].','.$addres['pincode']?><br>
       <h3>Shiping Type:<?= $Order['shipping_type']?></h3>
       <h3>Payment Mode:<?= $Order['payment_mode']?></h3>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
                  </tr>

             <?php  }?>       
           
<!-- 
                    <div class="modal fade" id="myModals<?= $Order['orders_id']?>" role="dialog">
    <div class="modal-dialog">
    
  
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Order Status</h4>
        </div>
        <form action="" method="post">
          <input type="hidden" name="action" value="StatusUpdate">
           <input type="hidden" name="order_id" value="<?= $Order['orders_id']?>">
        <div class="modal-body">
        <select class="form-control" name="cstatus">
          <?php  foreach($dbf->fetchOrder("status","","","","") as $stauts){?>
          <option value="<?= $stauts['status_id']?>" <?php if($stauts['status_id']==$Order['status']){ echo 'selected';}?> <?php if($stauts['status_id']<$Order['status']){ echo "disabled";}?>> <?= $stauts['name']?></option>
        <?php }?>
        </select>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Update Status</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
      </div>
      
    </div>
  </div>   -->   
                </tbody>
                <tfoot>
               <tr>
                    <th scope="col">Sl No</th>
                    <th scope="col">Order Id </th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Vendor Name</th>
                     <th scope="col">Payment </th>
                 <th scope="col">Status </th>
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

  </div>

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

function ChangeOrderstatus(arg1,arg2){
// alert(arg2)
if(arg1){
 $("#order_id").val(arg2);
 $("#status").val(arg1);
 $("#ChangeOrders").submit();
}
}

  </script>
<form id="ChangeOrders" action="" method="post">
   <input type="hidden" name="action" value="StatusUpdate">
   <input type="hidden" name="order_id" id="order_id">
   <input type="hidden" name="status" id="status">
</form>

   <?php include('footer.php')?>
}
