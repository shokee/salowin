 
 <?php 
 $Job_assign = explode(',',$profile['roles']);

 ?>
 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php if($profile['profile_image']<>''){?>
        <img src="images/thumb/<?php echo $profile['profile_image'];?> "  class="img-circle" alt="User Image" >
        <?php }else{?>
         <img src="images/default.png?> " class="img-circle" alt="User Image"  >
        <?php }?>
        </div>
        <div class="pull-left info">
          <p><?php echo $profile['full_name'];?></p>
          <!-- Status -->
          User Name:<?php echo $profile['full_name'];?>
        </div>
      </div>

     

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header"><i class="fa fa-envelope-o "></i> <?php echo $profile['email'];?></li>
        <!-- Optionally, you can add icons to the links -->
        <?php if(in_array('1',$Job_assign)){?>
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <?php }?>
        <?php if(in_array('2',$Job_assign)){?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-shopping-cart"></i> <span>All Orders</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <?php if(in_array('3',$Job_assign)){?>
          <li><a href="orders.php"><i class="fa fa-circle-o"></i> <span>Orders</span></a></li>
           <li><a href="ofline_order.php"><i class="fa fa-circle-o"></i> <span>Ofline Order</span></a></li>
          <?php }?>
          <?php if(in_array('4',$Job_assign)){?>
          <li><a href="retundprod.php"><i class="fa fa-circle-o"></i>Returned Orders</a></li>
          <?php }?>
          </ul>
        </li>
        <?php }?>



        <?php if(in_array('5',$Job_assign)){?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-file-excel-o"></i> <span>Product</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <?php if(in_array('6',$Job_assign)){?>
           <li><a href="product_management.php"><i class="fa fa-circle-o"></i>Product  </a></li>
          <?php }?>
          <?php if(in_array('7',$Job_assign)){?>
          	<li><a href="catagory-1.php"><i class="fa fa-circle-o"></i>Product Catagory-1 </a></li>
          <?php }?>
          <?php if(in_array('8',$Job_assign)){?>
            <li><a href="catagory-2.php"><i class="fa fa-circle-o"></i>Product Sub-Catagory-2 </a></li>
          <?php }?>
          <!--<?php if(in_array('9',$Job_assign)){?>-->
          <!--  <li><a href="catagory-3.php"><i class="fa fa-circle-o"></i> Product Sub-Catagory-3</a></li>-->
          <!--<?php }?>-->
          <?php if(in_array('10',$Job_assign)){?>
            <li><a href="brands.php"><i class="fa fa-circle-o"></i>Brand </a></li>
          <?php }?>
          <?php if(in_array('10',$Job_assign)){?>
            <li><a href="groups_management.php"><i class="fa fa-circle-o"></i>Groups </a></li>
          <?php }?>
          <?php if(in_array('10',$Job_assign)){?>
            <li><a href="types.php"><i class="fa fa-circle-o"></i>Types </a></li>
          <?php }?>
          <?php if(in_array('11',$Job_assign)){?>
            <li><a href="units.php"><i class="fa fa-circle-o"></i>Measure Ment</a></li>
          <?php }?>
          </ul>
        </li>
        <?php }?>
        <?php if(in_array('12',$Job_assign)){?>
        <li><a href="customer.php"><i class="fa fa-user"></i> <span>Customer</span></a></li>
        <?php }?>
        <?php if(in_array('13',$Job_assign)){?>
        <li ><a href="vendor.php"><i class="fa fa-users"></i> <span>Vendor </span></a></li>
        <?php }?>
        <?php if(in_array('14',$Job_assign)){?>
        <li ><a href="sub_admin.php"><i class="fa fa-users"></i> <span>Sub Admin </span></a></li>
        <?php }?>
        <?php if(in_array('15',$Job_assign)){?>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-map"></i> <span>Location</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <?php if(in_array('16',$Job_assign)){?>
          	<li><a href="country.php"><i class="fa fa-circle-o"></i>country</a></li>
          <?php }?>
          <?php if(in_array('17',$Job_assign)){?>
            <li><a href="state.php"><i class="fa fa-circle-o"></i>state</a></li>
          <?php }?>
          <?php if(in_array('18',$Job_assign)){?>
            <li><a href="city.php"><i class="fa fa-circle-o"></i> city</a></li>
          <?php }?>
          <?php if(in_array('19',$Job_assign)){?>
            <li><a href="pincode.php"><i class="fa fa-circle-o"></i> Pincode</a></li>
          <?php }?>
          <?php if(in_array('34',$Job_assign)){?>
            <li><a href="area.php"><i class="fa fa-circle-o"></i> Area</a></li>
          <?php }?>
          </ul>
        </li>
        <?php }?>
        <?php if(in_array('20',$Job_assign)){?>
        <li class=" treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Content Management </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <?php if(in_array('21',$Job_assign)){?>
          	<li><a href="cms-management.php"><i class="fa fa-circle-o"></i> All pages</a></li>
          <?php }?>
          <?php if(in_array('22',$Job_assign)){?>
            <li><a href="banner-management.php"><i class="fa fa-circle-o"></i> Banner Management</a></li>
          <?php }?>
          <?php if(in_array('23',$Job_assign)){?>
            <!-- <li><a href="add_management.php"><i class="fa fa-circle-o"></i>Advertising Management </a></li> -->
          <?php }?>
          <?php if(in_array('24',$Job_assign)){?>
            <!-- <li><a href="testimonial.php"><i class="fa fa-circle-o"></i>Testimonial  </a></li> -->
          <?php }?>
          

          <?php if(in_array('25',$Job_assign)){?>
             <li><a href="hopage_product.php"><i class="fa fa-circle-o"></i>Deals Of The Day</a></li> 
          <?php }?>
          <?php if(in_array('35',$Job_assign)){?>
            <!-- <li><a href="todaytrend.php"><i class="fa fa-circle-o"></i>Today Trending</a></li> -->
          <?php }?>
          </ul>
        </li>
        <?php }?>
        <?php if(in_array('26',$Job_assign)){?>
         <li>
          <a href="vendor_report.php">
            <i class="fa fa-file-excel-o"></i> <span>Report</span>
          </a>
        </li>
        <?php }?>
        <?php if(in_array('27',$Job_assign)){?>
         <li><a href="setting.php"><i class="fa fa-cog"></i> <span>Setting</span></a></li>
        <?php }?>
        <?php if(in_array('28',$Job_assign)){?>
           <li><a href="shiping_char.php"><i class="fa fa-ship"></i> <span>Shipping Charge</span></a></li> 
        <?php }?>
        <?php if(in_array('29',$Job_assign)){?>
          <!--<li><a href="comision.php"><i class="fa fa-calculator"></i> <span>Commission Slab</span></a></li>-->
          <!--<li><a href="shipping.php"><i class="fa fa-calculator"></i> <span>Shippingcharge</span></a></li>-->
        <?php }?>
        <?php if(in_array('30',$Job_assign)){?>
          <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>Mail Management</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
          <?php if(in_array('30.1',$Job_assign)){?>
            <li><a href="sms_manage.php">Manage SMS</a></li>
          <?php }?>
          <?php if(in_array('30.2',$Job_assign)){?>
            <li><a href="email_manage.php">Manage Email</a></li>
          <?php }?>
          <?php if(in_array('30.3',$Job_assign)){?>
             <li><a href="user_mailing.php">User</a></li>
          <?php }?>
          <?php if(in_array('30.4',$Job_assign)){?>
             <li><a href="vendor_mail.php">Vendor</a></li>
          <?php }?>
          </ul>
        </li>
        <?php }?>
        <?php if(in_array('31',$Job_assign)){?>
        <li><a href="coupon.php"><i class="fa fa-gift"></i>Manage Coupon <span></span></a></li>
        <?php }?>
        <?php if(in_array('32',$Job_assign)){?>
        <li><a href="wallet.php"><i class="fa  fa-credit-card"></i>Manage Wallet <span></span></a></li>
        <?php }?>
        <?php if(in_array('33',$Job_assign)){?>
        <li><a href="delivery_boy.php"><i class="fa  fa-truck"></i>Manage Delivery Boy<span></span></a></li>
        <?php }?>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>