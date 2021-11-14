<?php 
ob_start();
session_start();
include_once "admin/includes/class.Main.php";
$dbf=new User();
function getUserIP()
	{
		// Get real visitor IP behind CloudFlare network
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
				  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
				  $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];
	
		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}
	
		return $ip;
	}
		   if(isset($_SESSION['userid'])==""){
	$user_ip = getUserIP();
   }else{
	$user_ip = $_SESSION['userid'];
   }


if(isset($_REQUEST['action']) && $_REQUEST['action']=='RemtoNEwCart'){
	$dbf->deleteFromTable("cart","user_id='$user_ip'");
	$ip=getUserIP();
	$dbf->updateTable("cart","user_id='$user_ip'","user_id='$ip'");
		header("Location:cart.php");exit;
}


if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='addtoCart'){
	//$cart_date=date("Y-m-d");
	 $shop_id = $dbf->checkXssSqlInjection($_POST['shop_id']);
	 $prod_id = $dbf->checkXssSqlInjection($_POST['product_id']);
	 $qty = $dbf->checkXssSqlInjection($_POST['qty']);
    $varition_id = $dbf->checkXssSqlInjection($_POST['varition_id']);


		$chkUserIP=$dbf->countRows("cart","user_id='$user_ip'");

if($chkUserIP==0){
	$chkStatus=$dbf->countRows("product","product_id='$prod_id' AND status='1'");
	if($chkStatus!=0){
	$numRecord=$dbf->countRows("cart","user_id='$user_ip' AND  product_id='$prod_id' AND variation_id='$varition_id'");
	if($numRecord!=0){
		
		$tempData=$dbf->fetchSingle("cart","*","user_id='$user_ip' AND product_id='$prod_id' AND variation_id='$varition_id'");
		$product_qty=($tempData['qty']+$qty);
		
		$dbf->updateTable("cart","qty='$product_qty'","cart_id='$tempData[cart_id]'");
		
    include('cart_update.php');
	}else{
    
		$string="user_id='$user_ip',shop_id='$shop_id',product_id='$prod_id',qty='$qty',variation_id='$varition_id',created_date=NOW()";
	
		$dbf->insertSet("cart",$string);
		
     include('cart_update.php');
 }
}else{
	$error="Stock_error";
}
}else{
 $chkUserShop=$dbf->countRows("cart","user_id='$user_ip' AND shop_id='$shop_id'");
if($chkUserShop!=0){
$chkStatus=$dbf->countRows("product","product_id='$prod_id' AND status='1'");
	if($chkStatus!=0){
	$numRecord=$dbf->countRows("cart","user_id='$user_ip' AND  product_id='$prod_id' AND variation_id='$varition_id'");
	if($numRecord!=0){
		
		$tempData=$dbf->fetchSingle("cart","*","user_id='$user_ip' AND product_id='$prod_id' AND variation_id='$varition_id'");
		$product_qty=($tempData['qty']+$qty);
		
		$dbf->updateTable("cart","qty='$product_qty'","cart_id='$tempData[cart_id]'");
		
    include('cart_update.php');
	}else{
		$string="user_id='$user_ip',product_id='$prod_id',qty='$qty',variation_id='$varition_id',shop_id='$shop_id',created_date=NOW()";
	
		$dbf->insertSet("cart",$string);
    include('cart_update.php');
	}
}else{
	echo"Stock_error";
}
}else{
  echo"ShopErorror";exit;
}
}
}


if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='RemtoCart'){
	$dbf->deleteFromTable("cart","user_id='$user_ip'");

	$shop_id = $dbf->checkXssSqlInjection($_POST['shop_id']);
	$prod_id = $dbf->checkXssSqlInjection($_POST['product_id']);
	$qty = $dbf->checkXssSqlInjection($_POST['qty']);
   $varition_id = $dbf->checkXssSqlInjection($_POST['varition_id']);

	$string="user_id='$user_ip',product_id='$prod_id',qty='$qty',variation_id='$varition_id',shop_id='$shop_id',created_date=NOW()";
	$dbf->insertSet("cart",$string);
    include('cart_update.php');
}

?>