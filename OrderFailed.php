<?php 
ob_start();
session_start();
include_once 'admin/includes/class.Main.php';
 $dbf = new User();
include("header.php");

if(isset($_POST['txnid']) && $_POST['txnid']!=''){
     
  $status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"];
 $txnid=$_POST["txnid"];

 $posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$salt="erWwbL6h5W";
// Salt should be same Post Request 


        $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        
		 $hash = hash("sha512", $retHashSeq);
  
     if($hash == $posted_hash) {
$user_ip= $_SESSION['userid'];
 $from="order@grocemart.com";
$addres_id = $_SESSION['addres_id'];
$shipChar = $_SESSION['shipChar'];
$ship_typ = $_SESSION['ship_typ'];
$order_id =$_SESSION['order_id'];
$pay_mode = "Online";

   if($_SESSION['wallet_amnt']!='' && $_SESSION['wallet_amnt']!='0'){
  $Wallet=$dbf->fetchSingle("user","*","id='$user_ip'");
  $Wallet['wallet'];
    $wallet_deduc=$_SESSION['wallet_amnt'];
    $string = "amount='$wallet_deduc',remark='Brought Product',user_id='$user_ip',pay_type='0',date=NOW()";
    $ins_id=$dbf->insertSet("wallet_histru",$string);

     $updateWallet=$Wallet['wallet']-$wallet_deduc;
     $dbf->updateTable("user","wallet='$updateWallet'","id='$user_ip'");

     $User_Wallet=$dbf->fetchSingle("user",'wallet',"id='$user_ip'");
     $amount= $wallet_deduc+$User_Wallet['wallet'];
     $dbf->updateTable("user","wallet='$amount'","id='$user_ip'");
     $stringwalt = "amount='$amount',remark='Transction Failed Payment Refund.',user_id='$user_ip',date=NOW()";
     $ins_id=$dbf->insertSet("wallet_histru",$stringwalt);
    $pay_mode=$pay_mode."& Wallet Paid";
   }else{
    $pay_mode=$pay_mode;
     $wallet_deduc=0;
   }
 
  
  $Carts=$dbf->fetchOrder("cart","user_id='$user_ip'","","","");
$Address=$dbf->fetchSingle("address",'*',"address_id='$addres_id'");
$city=$dbf->fetchSingle("city",'*',"city_id='$Address[city_id]'");

  if(!empty($Carts)){
  $ArryProd_status=array();
  $CartArry=array();
  $ProdArry=array();
foreach ($Carts as $cart) {
$products=$dbf->fetchSingle("product",'*',"product_id='$cart[product_id]' AND status='1'");
$Price_Vari=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$cart[variation_id]'");
$Vari_price=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$Price_Vari[price_variation_id]'");
$Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price[measure_id]'");

   
$price=$Price_Vari['sale_price'];
 
$ordername=$products['product_name'].'-'.$Vari_price['units'].$Measure['unit_name'];
$string1="ordername='$ordername',qty='$cart[qty]',img='$products[primary_image]',price='$price',user_id='$user_ip',vendor_id='$cart[shop_id]',shipping_type='$ship_typ',shipping_charge='$shipChar',order_id='$order_id',address_id='$addres_id',payment_mode='$pay_mode',wallet='$wallet_deduc',status='9',txn_id='$txnid',created_date=NOW()";

$dbf->insertSet("orders",$string1);


         $to="$vendor[email]";
        
                    //Send mail to customer--------------------------------------
      $subject = "New Order Receive.";
   
      $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New Order Failed</title>
</head>

<body>
                     <table id="m_-7309890695392871332m_-5135203350509317470template_header" width="600" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                          <tr style="background:#000">
                            <td style="color:#fff; text-align:center;"><h1>New order For You</h1></td>
                          </tr>
                        </tbody>
                      </table>
                      <table  width="600" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <td  valign="top"><table width="100%" cellspacing="0" cellpadding="20" style="border:solid 1px #ccc;">
                              <tbody>
                                <tr>
                                  <td valign="top"><div >
                                    <p>Hi '.$vendor['full_name'].',</p>
                                    <p>Just to let you know — we'."'".'ve received your order '.$order_id.', and it is now being processed:</p>
                                    <p>Pay with '.$pay_mode.'.</p>
                                    <h2> [Order '.$order_id.' ] ('.date('F j, Y').')</h2>
                                    <div>
                                      <table width="100%" cellspacing="0" cellpadding="6" border="1">
                                        <thead>
                                          <tr>
                                            <th scope="col">Product</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Price</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                       <tr>
                                            <td>'. $ordername .'</td>
                                            <td>'. $cart['qty'].'</td>
                                            <td> Rs'.$price*$cart['qty'].' </td>
                                          </tr>
                                        </tbody>
                                        <tfoot>
                                          
                                          <tr>
                                            <th scope="row" colspan="2">Payment method:</th>
                                            <td>'.$pay_mode.'</td>
                                          </tr>
                                          <tr>
                                            <th scope="row" colspan="2">Total:</th>
                                            <td>Rs'.$price*$cart['qty'].'</td>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <table id="m_-7309890695392871332m_-5135203350509317470addresses" cellspacing="0" cellpadding="0" border="0">
                                      <tbody>
                                        <tr>
                                          <td width="50%" valign="top"><h2>Billing address</h2>
                                            <address>
                                              '.$Address['first_name']." ".$Address['last_name'].'<br />
                                              '.$Address['email'].'<br />
                                              '.$Address['address'].'<br />
                                             '.$city['city_name'].','.$Address['pincode'].'<br />
                                              Odisha<br />
                                              <a href="tel:'.$Address['number'].'" rel="noreferrer" target="_blank">'.$Address['number'].'</a> <br />
                                              <a href="mailto:'.$Address['email'].'" rel="noreferrer" target="_blank">'.$Address['email'].'</a>
                                            </address></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <p>Thanks for using <a href="https://grocemart.com/" rel="noreferrer" target="_blank" >grocemart.com</a>!</p>
                                  </div></td>
                                </tr>
                              </tbody>
                            </table></td>
                        </tbody>
                      </table>
            
</body>
</html>

';
     //echo $message;exit;
      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
       
      //More headers
      $headers .= 'From:'. $from . "\r\n";   
       
      mail($to,$subject,$message,$headers);
}
// Send To User 

// $state=$dbf->fetchSingle("state",'*',"city_id='$Address[city_id]'");

         $to="$Address[email],$_SESSION[email]";
                    //Send mail to customer--------------------------------------
      $subject = "Order Receive Successfully.";
   
      $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
                     <table id="m_-7309890695392871332m_-5135203350509317470template_header" width="600" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                          <tr style="background:#000">
                            <td style="color:#fff; text-align:center;"><h1>Thank you for your order</h1></td>
                          </tr>
                        </tbody>
                      </table>
                      <table  width="600" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <td  valign="top"><table width="100%" cellspacing="0" cellpadding="20" style="border:solid 1px #ccc;">
                              <tbody>
                                <tr>
                                  <td valign="top"><div >
                                    <p>Hi '.$Address['first_name'].',</p>
                                    <p>Just to let you know — we'."'".'ve received your order '.$order_id.', and it is now being processed:</p>
                                    <p>Pay with '.$pay_mode.'.</p>
                                    <h2> [Order '.$order_id.' ] ('.date('F j, Y').')</h2>
                                    <div>
                                      <table width="100%" cellspacing="0" cellpadding="6" border="1">
                                        <thead>
                                          <tr>
                                            <th scope="col">Product</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Price</th>
                                          </tr>
                                        </thead>
                                        <tbody>';
                                        
                                        $total=$shipChar;
                                        foreach ($Carts as $cart) {
$products=$dbf->fetchSingle("product",'*',"product_id='$cart[product_id]' AND status='1'");
$Price_Vari=$dbf->fetchSingle("price_varition",'*',"variation_values='$cart[variation_id]'");

if(!empty($Price_Vari)){
$price=$Price_Vari['price'];
}else{
$price=$products['sales_price'];
}
$Arry_Vari=array();
  array_push($ArryProd_status, $products['status']);
  foreach ($dbf->fetchOrder("variation","variation_id IN ($cart[variation_id])","","","") as $proVari) {
    array_push($Arry_Vari, $proVari['variation_name']);
  }


  $ordername=$products['product_name'].','.implode(',',$Arry_Vari);
  $total=$total+$price*$cart['qty'];
   $message.=' <tr>
                                            <td>'. $ordername .'</td>
                                            <td>'. $cart['qty'].'</td>
                                            <td> Rs'.$price*$cart['qty'].' </td>
                                          </tr>';
                                           
                                           }
                                          $message.=  '
                                        </tbody>
                                        <tfoot>
                                          <tr>
                                            <th scope="row" colspan="2">Delivery Chr.:</th>
                                            <td>Rs'.$shipChar.'</td>
                                          </tr>
                                          <tr>
                                            <th scope="row" colspan="2">Payment method:</th>
                                            <td>'.$pay_mode.'</td>
                                          </tr>
                                          <tr>
                                            <th scope="row" colspan="2">Total:</th>
                                            <td>Rs'.$total.'</td>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <table id="m_-7309890695392871332m_-5135203350509317470addresses" cellspacing="0" cellpadding="0" border="0">
                                      <tbody>
                                        <tr>
                                          <td width="50%" valign="top"><h2>Billing address</h2>
                                            <address>
                                              '.$Address['first_name']." ".$Address['last_name'].'<br />
                                              '.$Address['email'].'<br />
                                              '.$Address['address'].'<br />
                                             '.$city['city_name'].','.$Address['pincode'].'<br />
                                              Odisha<br />
                                              <a href="tel:'.$Address['number'].'" rel="noreferrer" target="_blank">'.$Address['number'].'</a> <br />
                                              <a href="mailto:'.$Address['email'].'" rel="noreferrer" target="_blank">'.$Address['email'].'</a>
                                            </address></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <p>Thanks for using <a href="https://grocemart.com/" rel="noreferrer" target="_blank" >grocemart.com</a>!</p>
                                  </div></td>
                                </tr>
                              </tbody>
                            </table></td>
                        </tbody>
                      </table>
            
</body>
</html>

';
    // echo $message;exit;
      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
       
      //More headers
      $headers .= 'From:'. $from . "\r\n";   
       
      mail($to,$subject,$message,$headers);



// WebAdd account details

// $usernames = '1984samirsahoo@gmail.com';

// $hash = 'Ei25fVbm6SY-zSjLl0H5sDA5F5YB2fU6cSmenvTFvL';


// // Message details

// $numbers = array($contact);

// $sender = urlencode('WEBADD');

// $message = rawurlencode('Login Sucess');


// $numbers = implode(',', $numbers);


// // Prepare data for POST request

// $data = array('username' => $usernames, 'hash' => $hash, 'numbers' => $numbers, "sender" => $sender, "message" => $message);


// // Send the POST request with cURL

// $ch = curl_init('http://sms.webadd.in/api2/send/');

// curl_setopt($ch, CURLOPT_POST, true);

// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// $response = curl_exec($ch);

// curl_close($ch);


// // Process your response here

// echo $response;



 $dbf->deleteFromTable("cart","user_id='$user_ip'");
 ?>
<script>
$(document).ready(function(){
  $('#NewOrder').modal({
      backdrop: 'static'
    });
    $("#NewOrder").modal('show');
   
  });
</script>
<div class="modal fade" id="NewOrder" tabindex="-1" role="dialog" aria-labelledby="NewOrder" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <div class="row">
        <h5 class="modal-title" id="NewOrder">Order Placed <span style="float: right;">#<?= $order_id?></span></h5>
        <h5 class="modal-title" id="NewOrder">Transaction Id <span style="float: right;">#<?= $txnid?></span></h5>
      </div>
      </div>
       <form action="user-dashboard.php" method="post">
      <div class="modal-body">
        <button  class="btn btn-secondary" type="submit"> 
          <img src="PayUPay/Transaction-Failed.png">
        </button>
      </div>
    </form>
     
    </div>
  </div>
</div>
<?php }else{
  header("Location:cart.php");exit;
  }}
  else{?>
    <script>
$(document).ready(function(){
  $('#NewOrder').modal({
      backdrop: 'static'
    });
    $("#NewOrder").modal('show');
   
  });
</script>
<div class="modal fade" id="NewOrder" tabindex="-1" role="dialog" aria-labelledby="NewOrder" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <div class="row">
        <h5 class="modal-title" id="NewOrder">Order Placed <span style="float: right;">#<?= $order_id?></span></h5>
      </div>
      </div>
       <form action="cart.php" method="post">
      <div class="modal-body">
        <button  class="btn btn-secondary" type="submit"> 
          <img src="PayUPay/invalid.png">
        </button>
      </div>
    </form>
     
    </div>
  </div>
</div>
 <?php  }
} else{
  header("Location:cart.php");exit;
}
?>


