<?php include('header.php')?>
  <?php include('sidebar.php')?> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        All Deals Of The Day
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"> All Deals Of The Day</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
           
         
            <div class="box-body">
            <?php if(in_array('25.1',$Job_assign)){?>
            <h3 class="box-title">
              <a  class="btn btn-info " href="add_deals_of_day.php">Add Deals Of The Day</a>
              </h3>
            <?php }?>
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

      if($_SESSION['usertype']=='1'){
        $loc="";
      }else{
        $loc = " AND city_id='$_SESSION[city]'";
      }

                    $curDate = date('Y-m-d H:i:s');
					$i=1;
					 foreach($dbf->fetchOrder("dealsof_of_day","datetime>='$curDate'".$loc,"datetime DESC","","") as $TodayDeals){
                        $product=$dbf->fetchSingle("product",'*',"product_id='$TodayDeals[product_id]'");
                        $Pin=$dbf->fetchSingle("pincode",'*',"pincode_id='$TodayDeals[pin_id]'");
                        $TodayDeals=$dbf->fetchSingle("dealsof_of_day","datetime","product_id='$TodayDeals[product_id]' AND pin_id='$TodayDeals[pin_id]'");
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
                        <td><span class="uk-button uk-button-default "  id="demo<?= $product['product_id']?>"></span></td>
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
                     <?php }?>
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
  <!-- /.content-wrapper -->
  
   <?php include('footer.php')?>
