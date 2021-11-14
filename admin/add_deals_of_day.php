<?php include('header.php')?>
  <?php include('sidebar.php')?>
  <?php

if(isset($_REQUEST['action']) && $_REQUEST['action']=='Search'){
    $city=$dbf->checkXssSqlInjection($_REQUEST['city']);
    $pin=$dbf->checkXssSqlInjection($_REQUEST['pin']);
    $condi=" AND city_id='$city' AND pin='$pin'";
  }else{
    $condi=" AND id='-1'";
  }

  if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='UpdateTodayDeals'){
    $product_id=$dbf->checkXssSqlInjection($_POST['product_id']);
    $date=$dbf->checkXssSqlInjection($_POST['date']);
    $time=$dbf->checkXssSqlInjection($_POST['time']);
    $city=$dbf->checkXssSqlInjection($_REQUEST['city']);
    $pin=$dbf->checkXssSqlInjection($_REQUEST['pin']);
    $date_time = $date." ".$time;
   
   $cntNUm= $dbf->countRows("dealsof_of_day","product_id='$product_id' AND city_id='$city'","");
   if($cntNUm!=0){
    $dbf->updateTable("dealsof_of_day","datetime='$date_time'", "product_id='$product_id' AND city_id='$city'");
   }else{
    $string ="product_id='$product_id',pin_id='$pin',city_id='$city',datetime='$date_time'";
    $dbf->insertSet("dealsof_of_day",$string);
   }
  }
?>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Deals Of The Day
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"> Add Deals Of The Day</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
           
         
            <div class="box-body">
            <h3 class="box-title">
              <a  class="btn btn-info " href="dealsofday.php">Back</a>
              </h3>

              <form action="" method="post">
              <div class="row">
                    <div class="col-md-3">
                    <div class="form-group">
                    <select name="city"  class="form-control select2" onchange="UpdateChangepin(this.value)">
                    <option value="">~~Select City~~</option>
                                <?php  
                    foreach($dbf->fetchOrder("city","","city_name ASC","","") as $stateName){?>
                    <option value="<?=$stateName['city_id']?>" <?php if($_POST['city']==$stateName['city_id']){ echo"selected";}?>><?=$stateName['city_name']?></option>
                    <?php }?>
                    </select>
                    </div>
                    </div>
                    <div class="col-md-3">
                    <select name="pin" id="loc_selected_pin" class="form-control select2">
                    <option value="">~~Select Pincode~~</option>
                    </select>
                    </div>
                    <div class="col-md-3">
                    <button class="btn btn-primary" name="action" value="Search">Search</button>
                    <a class="btn btn-default" href="">Refresh</a>
                    </div>
                </div>
              </form>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th> Product Image </th>
                  <th> Product Name </th>
                  <th> Pincode </th>
                  <th>Deals Of The Day</th>
                </tr>
                </thead>

                <tbody>
                <?php
                    $i=1;
                    $vendor_codi="";
                    $AllVendor=$dbf->fetchOrder("user","user_type='3'".$condi,"","id","");
                    foreach($AllVendor as $vendor){
                        // array_push($array_of_vendor,$vendor['id']);
                        $vendor_codi .=" find_in_set('$vendor[id]',vendor_id) OR";
                    }
                     
                   
                    if(!empty($AllVendor)){
                        $vendor_codi=substr($vendor_codi,0,-2);
					 foreach($dbf->fetchOrder("product","product_id<>0 AND $vendor_codi","today_dealing_date_time DESC","","") as $product){
                        $Pin=$dbf->fetchSingle("pincode",'*',"pincode_id='$_POST[pin]'");
                        $TodayDeals=$dbf->fetchSingle("dealsof_of_day","datetime","product_id='$product[product_id]' AND pin_id='$_POST[pin]'");
                   
                    
                    ?>
                    <tr>
                        <td>
                        <?php if($product['primary_image']<>''){?>
                        <img src="images/product/thumb/<?php echo $product['primary_image'];?> " width="50px" height="50px;" >
                        <?php }else{?>
                        <img src="images/default.png?> " width="50px" height="50px;">
                        <?php }?>
                        </td>
                        <td><?php echo $product['product_name'];?></td>
                        <td><?= $Pin['pincode']?></td>
                        <td>
                        <button class="btn btn-default" onclick="GetDeaLsModal(<?= $product['product_id']?>)">Update Date Time</button>
                        <span class="uk-button uk-button-default "  id="demo<?= $product['product_id']?>"></span>
                        </td>
                    </tr>
                    <script>
    var today = "<?= date('M d, Y H:i:s',strtotime($TodayDeals['datetime']))?>";
   

// Set the date we're counting down to
var countDownDate<?= $product['product_id']?> = new Date(today).getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
 //alert(countDownDate)
  // Find the distance between now and the count down date
  var distance = countDownDate<?= $product['product_id']?> - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="demo"
  document.getElementById("demo<?= $product['product_id']?>").innerHTML = days +"d "+hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // If the count down is over, write some text 
  if (distance <= 0) {
    clearInterval(x);
    document.getElementById("demo<?= $product['product_id']?>").innerHTML = "EXPIRED";
  }
}, 1000);
</script>


                     <?php }}?>
                </tbody>
                <tfoot>
                <tr>
                  <th> Product Image </th>
                  <th> Product Name </th>
                  <th> Pincode </th>
                  <th>Deals Of The Day</th>
                </tr>
                </tfoot>
              </table>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

    </section>
    <!-- /.content -->
  </div>
  <div class="modal modal-default fade" id="TodayDeals">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Today Deals</h4>
              </div>
              <div class="modal-body">
            <form action="" method="post">
              <input type="hidden" name="product_id" value="" id="DelasProdid">
              <input type="hidden" name='pin' value="<?= $_POST['pin']?>">
              <input type="hidden" name='city' value="<?= $_POST['city']?>">
         <label>To Date and time </label>
       <div class="uk-child-width-1-2" uk-grid>
       <div><input type="date" class="uk-input " name="date" min="<?= date('Y-m-d')?>" id="DealDate"/></div>
       <div><input type="time" class="uk-input "  name="time" id="DealTime"/></div>
       <div><button class="uk-button uk-button-default" type="submit" name="operation" value="UpdateTodayDeals">Update</button></div>
     </div>
   </form>
              </div>
           
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
  <!-- /.content-wrapper -->

   <?php include('footer.php')?>
<script>
  function UpdateChangepin(arg){
     
  var url="getAjax.php";
  $.post(url,{"choice":"changPin","value":arg,'pin':"<?= $_POST['pin']?>"},function(res){
 $('#loc_selected_pin').html(res);
// alert(res)
});
}
UpdateChangepin(<?=$_POST['city']?>);
</script>
<script>
function GetDeaLsModal(prod_id){
    var url="getAjax.php";
    $.post(url,{"choice":"GetTodayDeals","product_id":prod_id,"pin":"<?= $_POST['pin']?>"},function(res){
 $('#loc_selected_pin').html(res);
//  alert(res)
 res  = res.split('!next!');
$('#DealDate').val(res[0].trim());
$('#DealTime').val(res[1].trim());
$('#DelasProdid').val(prod_id);
});
  $("#TodayDeals").modal("show");
}
</script>