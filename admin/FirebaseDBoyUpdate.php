<?php include('header.php')?>
<?php include('sidebar.php')?>
  <!-- jQuery CDN -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Firebase -->
<script src="https://www.gstatic.com/firebasejs/4.12.1/firebase.js"></script>

<script>
            // Replace your Configuration here..
            var config = {
                apiKey: "AIzaSyCjqeOvfNlNbvC6phzJprlXnAUzI0RVdjs",
                authDomain: "https://grogod-8ef08.firebaseio.com",
                databaseURL: "https://grogod-8ef08.firebaseio.com",
                projectId: "grogod-8ef08",
				/*storageBucket: "practice-e0e65.firebaseio.com,
				messagingSenderId: "851837622908"*/
            };
            firebase.initializeApp(config);
			
			

</script>

<!-- <?php foreach($dbf->fetchOrder("user","user_type='5'","id ASC","id","") as $agent){?> -->
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

            // // If any car goes offline then this event will get triggered and we'll remove the marker of that car...  
            // cars_Ref.on('child_removed', function (data) {
            //     markers[data.key].setMap(null);
            //     // cars_count--;
            //     // document.getElementById("cars").innerHTML = cars_count;
            // });
	
</script>
        <?php }?>