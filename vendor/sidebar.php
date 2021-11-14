 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php if($profile['profile_image']<>''){?>
        <img src="../admin/images/vendor/thumb/<?php echo $profile['profile_image'];?> "  class="img-circle" alt="User Image" >
        <?php }else{?>
         <img src="../admin/images/default.png?> " class="img-circle" alt="User Image"  >
        <?php }?>
        </div>
        <div class="pull-left info">
          <p><?php echo $profile['full_name'];?></p>
          <!-- Status -->
          <a href="#"><i class="fa fa-envelope-o "></i> <?php echo $profile['email'];?></a>
        </div>
      </div>

     

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">HEADER</li>
        <!-- Optionally, you can add icons to the links -->
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
         <li ><a href="orders.php"><i class="fa fa-file-excel-o"></i> <span>Orders</span></a>
        <li ><a href="product_management.php"><i class="fa fa-file-excel-o"></i> <span>Product</span></a>
        </li>
         <li class=" treeview">
          <a href="#">
            <i class="fa fa-file-excel-o"></i> <span>Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          	<li><a href="single_vendor_report.php"><i class="fa fa-circle-o"></i>Order Report</a></li>
             <li><a href="payment.php"><i class="fa fa-circle-o"></i> Payment Report </a></li>
          </ul>
        </li>
          <li><a href="retundprod.php"><i class="fa fa-circle-o"></i>Returned Orders</a></li>
          <li><a href="manage_gift.php"><i class="fa fa-gift"></i> <span>Manage Gift</span></a></li>
        <li><a href="setting.php"><i class="fa fa-cog"></i> <span>Shop Setting</span></a></li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>