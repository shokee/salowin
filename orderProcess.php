<?php 
ob_start();
session_start();
include_once 'admin/includes/class.Main.php';
 $dbf = new User();
include "header.php";
if(isset($_REQUEST['action']) && $_REQUEST['action']=='newOrders'){
  
$addres_id = $_POST['adresss_id'];
$shipChar = $_POST['shipChar'];
$ship_typ = $_POST['shiptype'];
$coupon_code = $_POST['coupon_code'];
$coupon_amnt = $_POST['code_amnt'];

$local_url="http://localhost:8080/grogod/";
$server_url="http://grogod.com/";

$order_id = strtoupper('SLW-'.$dbf->randomPassword());

 $pay_mode = $_POST['payment_type'];

 if($addres_id !=''){
  $addres_id=$addres_id;
}else{
  $fnane = $_POST['fnane'];
  $lanme = $_POST['lanme'];
  $cname = $_POST['cname'];
  $zcode = $_POST['zcode'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $lat = $_POST['lat'];
  $lng = $_POST['lng'];
  
  $string="user_id='$user_ip',first_name='$fnane',last_name='$lanme',city_id='$cname',pincode='$zcode',email='$email',number='$phone',address='$address',lat='$lat',lng='$lng',created_date=NOW()";
  $ins_id=$dbf->insertSet("address",$string);
  $addres_id=$ins_id;
}


// if($pay_mode!='cod'){
//   $MERCHANT_KEY = "uiy5oB9z";
//   // Merchant Key and Salt as provided by Payu.
//   if(empty($posted['txnid'])) {
//     // Generate random transaction id
//     $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
//   } else {
//     $txnid = $posted['txnid'];
//   }
// $_SESSION['addres_id']=$addres_id;
// $_SESSION['shipChar'] = $shipChar;
// $_SESSION['ship_typ'] = $ship_typ;
// $_SESSION['order_id'] = $order_id;
// $_SESSION['wallet_amnt']=$_POST['wamntdeduc'];
// $_SESSION['coupon_code'] = $coupon_code;
// $_SESSION['coupon_amnt'] = $coupon_amnt;
// $Carts=$dbf->fetchOrder("cart","user_id='$user_ip'","","","");
// $Tolal=0;
// foreach ($Carts as $cart) {
//   $Price_Vari=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$cart[variation_id]'");
// $price=$Price_Vari['sale_price'];
// $Tolal+=$price*$cart['qty'];
// }
// $Payment_total = ($Tolal+$shipChar)-$_POST['wamntdeduc'];
// // print_r($Payment_total);exit;
// $Address=$dbf->fetchSingle("address",'*',"address_id='$addres_id'");
?>


<!--// <form action="" method="post" id="payuForm">-->
<!--//     <img src="PayUPay/payumoney.jpg" alt="payumoney" width="100%" height="auto">-->
<!--//       <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />-->
<!--//       <input type="hidden" name="hash" value="<?php echo $hash ?>"/>-->
<!--//       <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />-->
<!--//      <input type="hidden" name="send" value="PayUMoneyAmnt">-->
<!--//      <input  type="hidden" name="amount" value="<?= $Payment_total?>" />-->
<!--//      <input   type="hidden" name="firstname" id="firstname" value="<?= $Address['first_name'].' '. $Address['last_name']?>" />-->
<!--//      <input  type="hidden" name="email" id="email" value="<?= $Address['email'] ?>" />-->
<!--//      <input  type="hidden" name="phone" value="<?= $Address['number'] ?>" />-->
<!--//      <textarea name="productinfo"  hidden> <?= 'Order_id: '.$order_id?></textarea>-->
<!--//      <input name="surl" value="<?= $local_url.'OrderSucess.php'?>" size="64"  type="hidden"/>-->
<!--//      <input name="furl" value="<?= $local_url.'OrderFailed.php'?>" size="64"  type="hidden" />-->
<!--//      <input type="hidden" name="service_provider" value="payu_paisa" size="64" />-->
<!--//      <input name="curl" value="<?= $local_url.'cart.php'?>"  type="hidden"/>-->
<!--//     </form>-->
<!--// <script>-->
<!--// var payuForm = $('#payuForm');-->
<!--//  payuForm.submit();-->
<!--// </script>-->


 <?php
// }else{
    
//  $pay_mode ="cod";


 

   $Carts=$dbf->fetchOrder("cart","user_id='$user_ip'","","","");
   $Address=$dbf->fetchSingle("address",'*',"address_id='$addres_id'");
   $city=$dbf->fetchSingle("city",'*',"city_id='$Address[city_id]'");
   $pincode=$dbf->fetchSingle("pincode",'pincode',"pincode_id='$Address[pincode]'");
  
     if(!empty($Carts)){
         
          if($coupon_amnt==0){
  $coupon_code="";
  }else{
    $coupon_code= $coupon_code;
    $Couponcode=$dbf->fetchSingle("coupon_code",'used_up_to',"code='$coupon_code'");
    $qty=$Couponcode['used_up_to']-1;
      $dbf->updateTable("coupon_code","used_up_to='$qty'","code='$coupon_code'");
  }


   if($_POST['wamntdeduc']!='' && $_POST['wamntdeduc']!='0'){
  $Wallet=$dbf->fetchSingle("user","*","id='$user_ip'");
  $Wallet['wallet'];
    $wallet_deduc=$_POST['wamntdeduc'];
    $string = "amount='$wallet_deduc',remark='Brought Product',user_id='$user_ip',pay_type='0',date=NOW()";
    $ins_id=$dbf->insertSet("wallet_histru",$string);

     $updateWallet=$Wallet['wallet']-$wallet_deduc;
     $dbf->updateTable("user","wallet='$updateWallet'","id='$user_ip'");
   $pay_mode=$pay_mode;
   }else{
    $pay_mode=$pay_mode;
     $wallet_deduc=0;
   }
     
     $ArryProd_status=array();
     $CartArry=array();
     $ProdArry=array();
     
   foreach ($Carts as $cart) {
 
   $products=$dbf->fetchSingle("product",'*',"product_id='$cart[product_id]' AND status='1'");
   $Price_Vari=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$cart[variation_id]'");
   $Vari_price=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$Price_Vari[price_variation_id]'");
   $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price[measure_id]'");

    

   $vendor=$dbf->fetchSingle("user",'*',"id='$products[vendor_id]'");
   
   $price=$Price_Vari['sale_price'];
    
     $ordername=$products['product_name'].'-'.$Vari_price['units'].$Measure['unit_name'];
   $string1="ordername='$ordername',qty='$cart[qty]',img='$products[primary_image]',price='$price',user_id='$user_ip',vendor_id='$cart[shop_id]',shipping_type='$ship_typ',shipping_charge='$shipChar',order_id='$order_id',address_id='$addres_id',payment_mode='$pay_mode',coupon_code='$coupon_code',coupon_amnt='$coupon_amnt',wallet='$wallet_deduc',
   fname='$Address[first_name]',lname='$Address[last_name]',num='$Address[number]',email='$Address[email]',adress='$Address[address]',city='$city[city_name]',pin='$pincode[pincode]',lat='$Address[lat]',lng='$Address[lng]',created_date=NOW()";
   
   $dbf->insertSet("orders",$string1);

    $updateqty=$Price_Vari['qty'] - $cart['qty'];
    $dbf->updateTable("variations_values","qty='$updateqty'","variations_values_id='$Price_Vari[variations_values_id]'");

   // $Prod_Qty=$dbf->fetchSingle("product",'*',"product_id='$cart[product_id]'");
   // $pro_qty=$Prod_Qty['stocks']-$cart['qty'];
   // if($pro_qty<=0){
   // $string2="stocks='$pro_qty',status='0'";
   // }else{
   // $string2="stocks='$pro_qty'";
   // }
   // $dbf->updateTable("product",$string2,"product_id='$cart[product_id]'");
   
   
            $to="$vendor[email]";
           
                       //Send mail to customer--------------------------------------
         $subject = "New Order Receive.";
      
         $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html xmlns="http://www.w3.org/1999/xhtml">
   <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>New Order Receive</title>
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
   
         <div class="modal-header">
             <div style="text-align:center;">
             <h3 style="padding:0; margin-bottom:0;">Your order is on the way!</h3>
           <h5   style="padding:0; margin:0;">Order Number  #<?= $order_id?></h5></div>
         </div>
          <form action="user-dashboard.php" method="post" style="border:solid 0px #000">
         
             <img src="61198-delivery-service.gif">
          
       </form>
        
       
   <?php 
     }

// }
}


if(isset($_REQUEST['send']) && $_REQUEST['send']=='PayUMoneyAmnt'){
  $SALT = "erWwbL6h5W";
// Merchant Key and Salt as provided by Payu.

$PAYU_BASE_URL = "https://sandboxsecure.payu.in";		// For Sandbox Mode
//$PAYU_BASE_URL = "https://secure.payu.in";			// For Production Mode

$action = '';

$posted = array();
if(!empty($_POST)) {
    //print_r($_POST);
  foreach($_POST as $key => $value) {    
    $posted[$key] = $value; 
	
  }
}

$formError = 0;


$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash']) && sizeof($posted) > 0) {
  if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
		  || empty($posted['service_provider'])
  ) {
    $formError = 1;
  } else {
    //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
	$hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';	
	foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }

    $hash_string .= $SALT;


    $hash = strtolower(hash('sha512', $hash_string));
    $action = $PAYU_BASE_URL . '/_payment';
  }
} elseif(!empty($posted['hash'])) {
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}
?>
<html>
<script>
var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
  <head>
 
  </head>
  <body onLoad="submitPayuForm()">
    <?php if($formError) { ?>
	
      <span style="color:red">Please fill all mandatory fields.</span>
      <br/>
      <br/>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" name="payuForm" >
    <img src="PayUPay/payumoney.jpg" alt="payumoney" width="100%" height="auto">
      <input type="hidden" name="send" value="PayUMoneyAmnt">
      <input type="hidden" name="key" value="<?php echo (empty($_POST['key'])) ? '' : $_POST['key'] ?>" />
      <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
      <input type="hidden" name="txnid" value="<?php echo (empty($_POST['txnid'])) ? '' : $_POST['txnid'] ?>"/>
      <input name="amount" value="<?php echo (empty($_POST['amount'])) ? '' : $_POST['amount'] ?>" type="hidden"/>
      <input name="firstname" id="firstname" value="<?php echo (empty($_POST['firstname'])) ? '' : $_POST['firstname']; ?>" type="hidden"/>
      <input name="email" id="email" value="<?php echo (empty($_POST['email'])) ? '' : $_POST['email']; ?>" type="hidden"/>
      <input name="phone" value="<?php echo (empty($_POST['phone'])) ? '' : $_POST['phone']; ?>" type="hidden"/>
      <textarea name="productinfo" hidden><?php echo (empty($_POST['productinfo'])) ? '' : $_POST['productinfo'] ?></textarea>
      <input name="surl" value="<?php echo (empty($_POST['surl'])) ? '' : $_POST['surl'] ?>" size="64" type="hidden"/>
      <input name="furl" value="<?php echo (empty($_POST['furl'])) ? '' : $_POST['furl'] ?>" size="64" type="hidden"/>
      <input type="hidden" name="service_provider" value="payu_paisa" size="64" />
      <input name="curl" value="<?php echo (empty($_POST['curl'])) ? '' : $_POST['curl'] ?>" type="hidden"/>
    </form>
    </body>
    </html>
    <script>

<?php 
}
?>

<?php include("footer.php"); ?>
