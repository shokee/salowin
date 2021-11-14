<?php include('header.php')?>
  <?php include('sidebar.php')?>


<?php
########################## UPDATE STATUS  agent #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='update'){
   $id=$dbf->checkXssSqlInjection($_REQUEST['id']);
	$ststus=$dbf->checkXssSqlInjection($_REQUEST['ststus']);
	 $dbf->updateTable("product","trendingg='$ststus'", "product_id='$id'");
	header("Location:hopage_product.php");
}
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='UpdateTodayDeals'){
  $product_id=$dbf->checkXssSqlInjection($_POST['product_id']);
  $date=$dbf->checkXssSqlInjection($_POST['date']);
  $time=$dbf->checkXssSqlInjection($_POST['time']);
  $date_time = $date." ".$time;
   $dbf->updateTable("product","today_dealing_date_time='$date_time'", "product_id='$product_id'");
  header("Location:hopage_product.php");
}
?>

<script type="text/javascript">
function upDateStatus(id,id1){
	//alert(id)
	if(id1=='1'){
		var msg ="Are you sure want to active this Record";
		}else{
			var msg ="Are you sure want to block this Record";
			}
	
	$("#status").val(id1);
	$("#id").val(id);
	var conf=confirm(msg);
	if(conf){
	   $("#frm_update").submit();
	}
}
</script>


<form name="frm_deleteBanner" id="frm_update" action="" method="post">
  <input type="hidden" name="operation" id="operation" value="update">
  <input type="hidden" name="id" id="id" value="">
  <input type="hidden" name="ststus" id="status" value="">
  </form>
  
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Page Header
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
   <section class="content container-fluid">
      <div class="box">
            <div class="box-header">
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sl. No </th>
                  <th>Image</th>
                  <th>Name</th>
                  <!--<th>Stock</th>-->
                  <!--<th>Price</th>-->
                  <?php if(in_array('25.1',$Job_assign)){?>
                  <!--<th>Today Trending</th>-->
                  <?php }?>
                
                  <th>Today Deals</th>
                 
                </tr>
                </thead>
                <tbody>
                 <?php
					$i=1;
					 foreach($dbf->fetchOrder("product","","product_id ASC","","") as $product){
					 $unit = $dbf->fetchSingle("units",'*',"unit_id='$product[unit_id]'");
					?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td> 
				  <?php if($product['primary_image']<>''){?>
                  <img src="images/product/thumb/<?php echo $product['primary_image'];?> " width="50px" height="50px;" >
                  <?php }else{?>
                  <img src="images/default.png?> " width="50px" height="50px;">
                  <?php }?>
        		</td>
                  <td><?php echo $product['product_name'];?></td>
                 <!-- <td><?php echo $product['stocks'];?>-<?php echo $unit['unit_name'];?></td>-->
                 <!-- <td>-->
                 <!--<del> Rs.<?php echo $product['regular_price'];?> /<?php echo $unit['unit_name'];?></del> <br>-->
                 <!-- <b>Rs.<?php echo $product['sales_price'];?> /<?php echo $unit['unit_name'];?> </b>-->
                 <!-- </td>-->
                  <!--<?php if(in_array('25.1',$Job_assign)){?>-->
                  <!--<td>-->
                  <!--<?php if($product['trendingg']=='1'){?><button type="button" class="btn btn-success" onClick="upDateStatus(<?=$product['product_id']?>,0)">Active</button><?php }else{?><button type="button" class="btn btn-danger" onClick="upDateStatus(<?=$product['product_id']?>,1)">Block</button> <?php }?>-->
                  <!--</td>-->
                  <!--<?php }?>-->
                 
                  <td><button class="btn btn-default" data-toggle="modal" data-target="#TodayDeals<?= $product['product_id']?>">Update Date Time</button>
                    <span class="uk-button uk-button-default "  id="demo<?= $product['product_id']?>"></span>
                  </td>
             
                </tr>
                
                <?php $i++; } ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Sl. No </th>
                  <th>Image</th>
                  <th>Name</th>
                  <!--<th>Stock</th>-->
                  <!--<th>Price</th>-->
                  <?php if(in_array('25.1',$Job_assign)){?>
                  <!--<th>Today Trending</th>-->
                  <?php }?>
                 
                  <th>Today Deals</th>
                
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
     <?php
          $i=1;
           foreach($dbf->fetchOrder("product","","product_id ASC","","") as $product){
     $date = date('Y-m-d',strtotime($product['today_dealing_date_time']));
     $time = date('H:i',strtotime($product['today_dealing_date_time']));
            ?>
    <div class="modal modal-default fade" id="TodayDeals<?= $product['product_id']?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Today Deals</h4>
              </div>
              <div class="modal-body">
            <form action="" method="post">
              <input type="hidden" name="product_id" value="<?= $product['product_id']?>">
         <label>To Date and time </label>
       <div class="uk-child-width-1-2" uk-grid>
       <div><input type="date" class="uk-input " value="<?=$date?>" name="date" min="<?= date('Y-m-d')?>"/></div>
       <div><input type="time" class="uk-input " value="<?= $time?>" name="time"/></div>
       <div><button class="uk-button uk-button-default" type="submit" name="operation" value="UpdateTodayDeals">Update</button></div>
     </div>
   </form>
              </div>
           
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
         <script>
    var today = "<?= date('M d, Y H:i:s',strtotime($product['today_dealing_date_time']))?>";
   

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
   <?php include('footer.php')?>
 