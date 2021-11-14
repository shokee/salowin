<?php include('header.php')?>
  <?php include('sidebar.php')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        All Today Trending
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"> All  Today Trending</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
           
         
            <div class="box-body">
            <?php if(in_array('35.1',$Job_assign)){?>
            <h3 class="box-title">
              <a  class="btn btn-info " href="add_todaytrend.php">Add  Today Trending</a>
              </h3>
            <?php }?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th> Product Image </th>
                  <th> Product Name </th>
                  <th> Pincode </th>
                  <?php if(in_array('35.2',$Job_assign)){?>
                  <th>Today Trend</th>
                  <?php }?>
                </tr>
                </thead>

                <tbody>
                <?php
         if($_SESSION['usertype']=='1'){
          $loc="";
        }else{
          $loc = " city_id='$_SESSION[city]'";
        }
  

                    $curDate = date('Y-m-d H:i:s');
					$i=1;
					 foreach($dbf->fetchOrder("today_trending",$loc,"today_trending_id DESC","","") as $TodaTrend){
                        $product=$dbf->fetchSingle("product",'*',"product_id='$TodaTrend[product_id]'");
                        $Pin=$dbf->fetchSingle("pincode",'*',"pincode_id='$TodaTrend[pin_id]'");
					?>
                    <tr id="Todaytrend<?= $TodaTrend['today_trending_id']?>">
                        <td >
                        <?php if($product['primary_image']<>''){?>
                        <img src="images/product/thumb/<?php echo $product['primary_image'];?> " width="50px" height="50px;" >
                        <?php }else{?>
                        <img src="images/default.png?> " width="50px" height="50px;">
                        <?php }?>
                        </td>
                        <td><?php echo $product['product_name'];?></td>
                        <td><?= $Pin['pincode']?></td>
                        <?php if(in_array('35.2',$Job_assign)){?>
                        <td>
                        
                        <button class="btn btn-success" onclick="RemoveTrend(<?= $TodaTrend['today_trending_id']?>)">Active</button>
                        
                        </td>
                        <?php }?>
                    </tr>
                  
                     <?php }?>
                </tbody>
                <tfoot>
                <tr>
                  <th> Product Image </th>
                  <th> Product Name </th>
                  <th> Pincode </th>
                  <?php if(in_array('35.2',$Job_assign)){?>
                  <th>Today Trend</th>
                  <?php }?>
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

   <script>
   function RemoveTrend(id){
    
    var con = confirm("Are You Sure To Remove?");
    if(con){
        var url="getAjax.php";
        $.post(url,{"choice":"RemoveTrending","trend_id":id},function(res){
        $('#Todaytrend'+id).css('display','none');
        });
    }
}

   
   </script>
