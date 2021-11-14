<?php include('header.php')?>
<?php include('sidebar.php')?>
<?php 
include_once '../admin/includes/class.Main.php';
$dbf = new User();

?>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

  <style>
.pagination {
  display: inline-block;
  float:right;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  border: 1px solid #ddd;
}

.pagination a.actives {
  background-color: #3c8dbc;
  color: white;
  border: 1px solid #3c8dbc;
}

.pagination a:hover:not(.actives) {background-color: #ddd;}

.pagination a:first-child {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
}

.pagination a:last-child {
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
}
</style>

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
            Show  
            <select name="" id="SelectEntity" onchange="GetAllTable()">
            <option>10</option>
            <option>25</option>
            <option>50</option>
            <option>100</option>
            </select>entries
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
    <th>Sl. No </th>
     <th>Image</th>
     <th>Name</th>
     <th>Gallery</th>
     <th>Variation</th>
     <th>Category</th>
     <th>Sub Category</th>
     <th>Sub Category2</th>
     <th>Status</th>
   </tr>
 </thead>
 <tbody id="DataTables">
 
</tbody>
<tfoot>
<tr>
    <th>Sl. No </th>
     <th>Image</th>
     <th>Name</th>
     <th>Gallery</th>
     <th>Variation</th>
     <th>Category</th>
     <th>Sub Category</th>
     <th>Sub Category2</th>
     <th>Status</th>
     
   </tr>
                </tfoot>
              </table>
<div class="pagination">

</div>
            </div>
            <!-- /.box-body -->
          </div>

    </section>


<script>
function GetAllTable(){
    var NUmRow = $('#SelectEntity').val();
    $.post('ProdAjax.php',{'num_row':NUmRow},function(res){
        // alert(res);
        res = res.split('!next!');
    $('#DataTables').html(res[0]);
    $('.pagination').html(res[1]);
    // alert(res[1]);
    });
}
GetAllTable();

function Forward(){
   var pagenum = $('.actives').data('page');
   pagenum = pagenum+1;
   var NUmRow = $('#SelectEntity').val();
    $.post('ProdAjax.php',{'num_row':NUmRow,"pagenum":pagenum},function(res){
        // alert(res);
        res = res.split('!next!');
    $('#DataTables').html(res[0]);
    $('.pagination').html(res[1]);
    // alert(res[1]);
    });
}

function Back(){
    var pagenum = $('.actives').data('page');
   
   pagenum = pagenum-1;
   if(pagenum==1){
    pagenum = "";
   }
   var NUmRow = $('#SelectEntity').val();
    $.post('ProdAjax.php',{'num_row':NUmRow,"pagenum":pagenum},function(res){
        // alert(res);
        res = res.split('!next!');
    $('#DataTables').html(res[0]);
    $('.pagination').html(res[1]);
    // alert(res[1]);
    });
}
</script>

    <!-- /.content -->
    
   <!-- gallery --> 
     <!-- Modal -->

 </div>
</div>
