 <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2016 <a href="#">Company name</a>.</strong> All rights reserved.
  </footer>


</div>
<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="../admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- CK Editor -->
<script src="../admin/bower_components/ckeditor/ckeditor.js"></script>

<!-- Select2 -->
<script src="../admin/bower_components/select2/dist/js/select2.full.min.js"></script>



<!-- AdminLTE App -->
<script src="../admin/dist/js/adminlte.min.js"></script>



<!-- DataTables -->
<script src="../admin/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- page script -->

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
  })
</script>


<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1',)
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
  })
  
   $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor2',)
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
  })
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
         
<script src="../admin/bower_components/select2/dist/js/select2.full.min.js"></script>
<style>
.selectdiv {
  position: relative;
  /*Don't really need this just for demo styling*/
  padding:0; 
  float: left;
  min-width: 100px;
}

/*To remove button from IE11, thank you Matt */
select::-ms-expand {
     display: none;
}

.selectdiv:after {
  content: '<>';
  font: 17px "Consolas", monospace;
  color: #333;
  -webkit-transform: rotate(90deg);
  -moz-transform: rotate(90deg);
  -ms-transform: rotate(90deg);
  transform: rotate(90deg);
  right: 0px;
  /*Adjust for position however you want*/
  
  top: -3px;
  padding: 0 0 2px;
  border-bottom: 1px solid #999;
  /*left line */
  
  position: absolute;
  pointer-events: none;
}

.selectdiv select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  /* Add some styling */
  
  display: block;
  width: 100%;
  max-width: 320px;
  float: right;
  margin: 0px 0px;
  padding: 0px 4px;
  color: #333;
  background-color: #ffffff;
  background-image: none;
  border: 0px solid #cccccc;
  -ms-word-break: normal;
  word-break: normal;
}
.pcat{ height:235px; overflow-y:scroll; border:solid 1px #d2d6de;}
.pcat ul { list-style:none; padding-left:15px;}


}
</style>

<script>
  function  GetState(val) {
       // alert(val);
    
//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getState","value":val},function(res){
 $('#stateres').html(res);
// alert(res)
});
  
  } 
  
   function  GetCity(val) {
       // alert(val);
    
//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getCity","value":val},function(res){
 $('#cityres').html(res);
// alert(res)
});
  
  } 

  
  </script>		
    
    <!-- Firebase -->
<script src="https://www.gstatic.com/firebasejs/4.12.1/firebase.js"></script>

<script>
            // Replace your Configuration here..
            var config = {
                apiKey: "AIzaSyBOikcda99xzF3kTNLzpbXA_n6EgUkTDTI",
                authDomain: "ownmystore.firebaseapp.com",
                databaseURL: "https://ownmystore.firebaseio.com",
                projectId: "ownmystore",
				/*storageBucket: "practice-e0e65.firebaseio.com,
				messagingSenderId: "851837622908"*/
            };
            firebase.initializeApp(config);
			
			

</script>

 <?php foreach($dbf->fetchOrder("user","user_type='5'","id ASC","id","") as $agent){?> 
<script>

// get firebase database reference...
var cars_Ref = firebase.database().ref('Dboy<?= $agent["id"]?>');

	    
				
           // this event will be triggered when a new object will be added in the database...
           cars_Ref.on('child_added', function (data) {
            //    console.log(data.val()['latitude']);
              //console.log(data.val()['longitude']);
              var lat = data.val()["latitude"];
              var lng = data.val()["longitude"];
            var url="getAjax.php";
            $.post(url,{"choice":"DeliverBoyUp","lat":lat,"lng":lng,"dboy":<?= $agent["id"]?>},function(res){
            // alert(res)
            });
            });

            // this event will be triggered on location change of any car...
            cars_Ref.on('child_changed', function (data) {
              var lat = data.val()["latitude"];
              var lng = data.val()["longitude"];
            var url="getAjax.php";
            $.post(url,{"choice":"DeliverBoyUp","lat":lat,"lng":lng,"dboy":<?= $agent["id"]?>},function(res){
            // alert(res)
            });
               
            });

            // If any car goes offline then this event will get triggered and we'll remove the marker of that car...  
            cars_Ref.on('child_removed', function (data) {
                markers[data.key].setMap(null);
                // cars_count--;
                // document.getElementById("cars").innerHTML = cars_count;
            });
	
</script>
        <?php }?>
</body>
</html>