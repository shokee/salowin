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
        Add Today Trenings
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"> Add Today Trenings</li>
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
              <a  class="btn btn-info " href="todaytrend.php">Back</a>
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
                  <th>Today Trending</th>
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
                        $Todaytrending=$dbf->fetchSingle("today_trending","*","product_id='$product[product_id]' AND pin_id='$_POST[pin]'");
                   
                    
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
                        <td id="Changests<?=$product['product_id'] ?>">
                        <?php  if(empty($Todaytrending)){?>
                        <button class="btn btn-danger" onclick="UpdateTrending(<?= $product['product_id']?>,1)">Block</button>
                        <?php }else{?>
                            <button class="btn btn-success" onclick="UpdateTrending(<?= $product['product_id']?>,0)">Active</button>
                        <?php }?>
                        </td>
                    </tr>
                     <?php }}?>
                </tbody>
                <tfoot>
                <tr>
                  <th> Product Image </th>
                  <th> Product Name </th>
                  <th> Pincode </th>
                  <th>Today Trending</th>
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

function UpdateTrending(prod_is,sts){
    if(sts==1){
        var msg = "Are You Sure To Active?";
    }else{
        var msg = "Are You Sure To Block?";
    }
    var con = confirm(msg);
    if(con){
        var url="getAjax.php";
        $.post(url,{"choice":"UpdateSts","prod_id":prod_is,'pin':"<?= $_POST['pin']?>","city":"<?= $_POST['city']?>","status":sts},function(res){
        $('#Changests'+prod_is).html(res);
        // alert(res)
        });
    }
}
</script>
