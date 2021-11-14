<?php include('header.php')?>
<?php include('sidebar.php')?>
<?php 
foreach($dbf->fetchOrder("order_sending","","","","") as $OrderSending){
    $Add_order_time = $OrderSending['order_time'];

    $startTime = $OrderSending['order_time'];

//adding 10 minutes
$convertedTime = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($startTime)));
?>
<script>
var today = "<?= date('M d, Y H:i:s',strtotime($convertedTime))?>";
   

// Set the date we're counting down to
var countDownDate= new Date(today).getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
 //alert(countDownDate)
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="demo"
  document.getElementById("demo").innerHTML = days +"d "+hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // If the count down is over, write some text 
  if (distance <= 0) {
      alert('dd');
    clearInterval(x);
    <?php  
    
  
    $dbf->deleteFromTable("order_sending","order_id='$OrderSending[order_id]'");?>
  }
}, 1000);
</script>
<?php }?>
<h3 id="demo"></h3>
