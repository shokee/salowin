<?php include('header.php')?>
  <?php include('sidebar.php')?> 


<?php
$mails=$dbf->fetchSingle("mail_management",'*',"");
########################## UPDATE STATUS #############################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='SendMail'){
  $mode=$_POST['mode'];
  $selectUser=$_POST['selectUser'];
$user_id = implode(',',$selectUser);
if($mode=='1'){
  
  header("location:user_mailing.php");
}else{
$All_mails=array();
foreach ($dbf->fetchOrder("user","id IN ($user_id)","","email","") as $valUser) {
 array_push($All_mails,$valUser['email']);
}
$all_mail=implode(',',$All_mails);
 $subject = "$mails[mail_sub]";
 $from = "syflextechnotest@gmail.com";
   $to = "$all_mail";
    $message = "$mails[email]";
   
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
     
    //More headers
    $headers .= 'From:'. $from . "\r\n";   
     
    mail($to,$subject,$message,$headers);
    // echo $message;exit;
    //Send mail to customer--------------------------------------
  header("location:user_mailing.php");
}
}
?>
 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        All Users
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">All Users</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
            <!-- <div class="box-header">
              <h3 class="box-title"><a href="vendor_add.php" class="btn btn-primary">Add Vendor</a></h3>
            </div> -->
            <!-- /.box-header -->
            <div class="box-body">
              <form action="" method="post">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                   <th><input type="checkbox" name="checkall" id="checkall" onClick="check_uncheck_checkbox(this.checked);" />Check All
                </th>
                  <th> Name</th>
                  <th> Email</th>
                  <th>Contact No</th>
                  <th>Pincode</th>
                </tr>
                </thead>
                <tbody>
                
                 <?php
                 $i=1;
                 foreach($dbf->fetchOrder("user","user_type='4'","id DESC","","") as $resBanner){
                  $Pin=$dbf->fetchSingle("pincode",'*',"pincode_id='$resBanner[pin]'");
                 ?>
                <tr>
            <td><input type="checkbox"  name="selectUser[]" value="<?= $resBanner['id']?>"></td>
   				 <td><?php echo $resBanner['full_name'];?></td>
   				 <td><?php echo $resBanner['email'];?></td>
           <td> <?php echo $resBanner['contact_no'];?></td>
           <td><?= $Pin['pincode']?></td>   
                 
                </tr>
                <?php $i++; } ?>
                </tbody>
                <tfoot>
                <tr>
                  <th></th>
                  <th> Name</th>
                  <th> Email</th>
                  <th>Contact No</th>
                  <th>Pincode</th>
                </tr>
                </tfoot>
              </table>
             <div class="row">
              <div class="col-md-2">
                <select class="form-control" name="mode" required="">
                <option value="">--Select Mail Type--</option>
                <option value="1">SMS</option>
                <option value="2">E-Mail</option>
              </select>
            </div>
            <div class="col-md-6">
              <button class="btn btn-primary" name="operation" value="SendMail">Send Mail</button>
            </div>
             </div>
            </form>
            </div>
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
  <script type="text/javascript">
    function check_uncheck_checkbox(isChecked) {
  if(isChecked) {
    $('input[name="selectUser[]"]').each(function() { 
      this.checked = true; 
    });
  } else {
    $('input[name="selectUser[]"]').each(function() {
      this.checked = false;
    });
  }
}
  </script>
   <?php include('footer.php')?>
