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
      </ol>
    </section>
    <?php 
 $Total_Sale=0;

 foreach($dbf->fetchOrder("orders","status IN(4,7)","","","") as $Total_cal){
   $Total_Sale+=($Total_cal['qty']*$Total_cal['price']);
 }
 $TotalPaid=$dbf->fetchSingle("payment_vendor",'SUM(amount) as Payment',"");
 $TotalComision=$dbf->fetchSingle("payment_vendor",'SUM(comm_amnt) as Payment',"");
 
?>
    <!-- Main content -->
    <section class="content">
    
      <!-- Main row -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <?php if($_SESSION['usertype']=='1'){?>
              <h3><?= number_format($dbf->countRows("orders","",""));?><sup style="font-size: 20px"></sup></h3>
              <?php }else{
               $Loc_Pin=$dbf->fetchSingle("city",'city_name',"city_id='$_SESSION[city]'");
                ?>
              <h3><?= number_format($dbf->countRows("orders","city='$Loc_Pin[city_name]'",""));?></h3>
              <?php }?>
              
              <p>ALL Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <?php if(in_array('3',$Job_assign)){?>
            <a href="orders.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            <?php }?>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
            <?php if($_SESSION['usertype']=='1'){?>
              <h3><?= number_format($dbf->countRows("orders","status='0'",""));?></h3>
              <?php }else{
                $Loc_Pin=$dbf->fetchSingle("city",'city_name',"city_id='$_SESSION[city]'");
                ?>
                <h3><?= number_format($dbf->countRows("orders","status='0' AND city='$Loc_Pin[city_name]'",""));?></h3>
              <?php }?>
              <p>New Orders </p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <?php if(in_array('3',$Job_assign)){?>
            <a href="orders.php"  class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            <?php }?>
          </div>
        </div>
        
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
            <?php if($_SESSION['usertype']=='1'){?>
              <h3><?= number_format($dbf->countRows("orders","status!='4'",""));?></h3>
              <?php }else{
                $Loc_Pin=$dbf->fetchSingle("city",'city_name',"city_id='$_SESSION[city]'");
                ?>
             <h3><?= number_format($dbf->countRows("orders","status='4' AND pin='$Loc_Pin[city_name]'",""));?></h3>
              <?php }?>
              <p>Pending Orders </p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <?php if(in_array('13',$Job_assign)){?>
            <a href="orders.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            <?php }?>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
            <?php if($_SESSION['usertype']=='1'){?>
              <h3><?= number_format($dbf->countRows("orders","status='4'",""));?></h3>
              <?php }else{
                $Loc_Pin=$dbf->fetchSingle("city",'city_name',"city_id='$_SESSION[city]'");
                ?>
             <h3><?= number_format($dbf->countRows("orders","status='4' AND pin='$Loc_Pin[city_name]'",""));?></h3>
              <?php }?>
              <p>Complete Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <?php if(in_array('3',$Job_assign)){?>
            <a href="orders.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            <?php }?>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row (main row) -->
    <div class="row">
        <div class="col-sm-6">
            
<div id="piechart"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day'],
  ['Customer', <?= number_format($dbf->countRows("user","user_type='4'",""));?>],
  ['Delivery Boy', <?= number_format($dbf->countRows("user","user_type='5'",""));?>],
  ['Vendor', <?= number_format($dbf->countRows("user","user_type='3'",""));?>],
  
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':' Registered Users', 'width':500, 'height':400};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>
        </div>
        <div class="col-sm-6">
            <div id="myChart1" style="width:100%; max-width:600px; height:400px;"></div>
            
<script>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
var data = google.visualization.arrayToDataTable([
  ['12AM-2Am','Price'],
  ['2AM-4AM', <?php $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "02:00 am";
                    $end = "04:00 am";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price +($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  ['4AM-6AM',   <?php     $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "04:00 am";
                    $end = "06:00 am";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price +($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  ['6AM-8AM',   <?php     $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "06:00 am";
                    $end = "08:00 am";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price +($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  ['8AM-10AM',   <?php     $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "08:00 am";
                    $end = "10:00 am";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price + ($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  ['10AM-12Noon',   <?php     $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "10:00 am";
                    $end = "12:00 pm";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price + ($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  ['12AM-2PM', <?php     $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "12:00 pm";
                    $end = "02:00 pm";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price + ($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  ['2PM-4PM',<?php     $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "02:00 pm";
                    $end = "04:00 pm";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price + ($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  ['4PM-6PM',<?php     $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "04:00 pm";
                    $end = "06:00 pm";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price + ($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  ['6PM-8PM',<?php     $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "06:00 pm";
                    $end = "08:00 pm";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price + ($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  ['8PM-10PM',<?php     $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "08:00 pm";
                    $end = "10:00 pm";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price + ($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  ['10PM-12PM',<?php     $price=00;   
             foreach($dbf->fetchOrder("orders","DATE(`created_date`) = CURDATE()","orders_id DESC","","") as $resBanner){
                 $orderTime=date('h:i a', strtotime($resBanner['created_date']));
                    $start = "10:00 pm";
                    $end = "12:00 am";
                    $date1 = DateTime::createFromFormat('h:i a', $orderTime);
                    $date2 = DateTime::createFromFormat('h:i a', $start);
                    $date3 = DateTime::createFromFormat('h:i a', $end);
                    if ($date1 >= $date2 && $date1 < $date3)
                    {
                      $price=$price + ($resBanner['price']*$resBanner['qty'])+$resBanner['shipping_charge'];
                    }
             }
             echo $price;
            ?>],
  
  
]);

var options = {
  title:'Daily Peak Order Time '
};

var chart = new google.visualization.BarChart(document.getElementById('myChart1'));
  chart.draw(data, options);
}
</script>
        </div>
        
        <div class="col-sm-12">
            <h3></h3>
            <div id="myChart" style="width:100%; max-width:1500px; height:600px;"></div>
            

<script>
google.charts.load('current',{packages:['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
// Set Data
var data = google.visualization.arrayToDataTable([
  ['Date', 'Price'],
  
  <?php $month=date("m");
  if($month == '02'){
      $count=29;
  }else if($month == '04' || $month == '06'|| $month == '09' || $month == '11'){
      $count=31;
  }else{
      $count=32;
  }
  
  for($i=01;$i < $count; $i++){
      
        if($i <10){
        $start=date("Y-m-".'0'.$i.' 00:00:00');
        $ending=date("Y-m-".'0'.$i.' 23:59:59');
        }else{
            $start=date("Y-m-".$i.' 00:00:00');
            $ending=date("Y-m-".$i.' 23:59:59');
        }
$TotalAmnt= $dbf->fetchSingle("orders",'SUM(price*qty+shipping_charge) as total_price',"created_date BETWEEN '$start' AND '$ending'"); ?>
        
        [<?= $i?>,<?php if(empty($TotalAmnt['total_price'])){echo "00";}else{ echo $TotalAmnt['total_price'];}?>]<?php if($i < 31){ echo",";}?>
        
 <?php }?>
]);
// Set Options
var options = {
  title: 'Sales report',
  hAxis: {title: ' Date'},
  vAxis: {title: 'Price'},
  legend: 'none'
};
// Draw
var chart = new google.visualization.LineChart(document.getElementById('myChart'));
chart.draw(data, options);
}
</script>
        </div>
    </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>
