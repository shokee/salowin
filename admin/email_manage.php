<?php include('header.php')?>
  <?php include('sidebar.php')?>

 <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<?php 
$mails=$dbf->fetchSingle("mail_management",'*',"");
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='addvendor'){

$email=$_POST['email'];
$id=$_POST['id'];
$subject=$_POST['subject'];

$dbf->updateTable("mail_management","email='$email',mail_sub='$subject'","mail_management_id='$id'");

header('Location:email_manage.php');
}


?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Update Email
        <small>Manage Email</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Update Email</li>
      </ol>

    </section>

    <!-- Main content -->
    <section class="content container-fluid">

     <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Update Email</h3>
             
              <?php   if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
               <script type="text/javascript">
                alert('Add Vendors Successfully.');
              </script>
              <h4 class="text-success">Add Vendors Successfully.</h4>
             <?php }?>
            </div>
            <!-- /.box-header -->
            <!-- insert  Vendor start -->
            
            
 
                        
     <!-- insert vendor End -->                    
                         
      <!-- form start -->                   
                         
                         
				<form  action="" method="post" enctype="multipart/form-data"> 
             <input type="hidden" name="id" value="<?= $mails['mail_management_id']?>">
              <div class="box-body"> 
                <div class="form-group">
                  <label> Subject</label>
                  <input type="text" name="subject" value="<?= $mails['mail_sub'] ?>" class="form-control" placeholder="Enter subject">
                </div>
                  <div class="form-group">
                    <label> Email Text </label>
                    <textarea placeholder="Enter Email Text" id="email" name="email"><?= $mails['email']?></textarea>
                  </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" name="submit"  value="addvendor" class="btn btn-primary" >Update</button>
              </div>
            </form>
            
            
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
     <script>
         CKEDITOR.replace( 'email' );

    </script>
   <?php include('footer.php')?>
