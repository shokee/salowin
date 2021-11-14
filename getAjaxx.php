<?php
include_once 'admin/includes/class.Main.php';
$dbf = new User();

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='ApplyCoupon'){
  // Apply CouponCode -->
 $code = $_POST['coupon_code'];
 $date = date('Y-m-d');
  $price = $_POST['price'];
$CntCoupon=$dbf->countRows("coupon_code","code='$code'","");
if($CntCoupon!=0){
$Couponcode=$dbf->fetchSingle("coupon_code",'*',"code='$code'");

if($Couponcode['valid_uo_to']<$date){
echo "Expired Coupon Code";exit;
}elseif ($Couponcode['used_up_to']<=0){
echo "Not Available Coupon Code";exit;
}else if($Couponcode['price_cart']>$price){
echo $code." Is Applicable Only Cart Price Equal And Greater ".$Couponcode['price_cart'];exit;
}else{
	if($Couponcode['discount_type']=='2'){

	$mnt_dedc=($price*$Couponcode['discount_value'])/100;
	}else{
	$mnt_dedc=$price-$Couponcode['discount_value'];
}
	echo "CodeAplicable ".$mnt_dedc;exit;
}

}else{
  echo "Invalid Coupon Code";exit;
}
}

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='numVerify'){
	//  $otp = rand(1000,9999);
	$otp = '1212';
	$mobile_num=$_POST['mobile_num'];
	$Cnt_num=$dbf->countRows("user","contact_no='$mobile_num' AND user_type='4'","");
	if($Cnt_num!=0){
		$dbf->updateTable("user","otp='$otp'","contact_no='$mobile_num'");
		echo $mobile_num;exit;
	}else{
		echo "error";
	}
}

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='veryFyOtp'){
	$Otp=$_POST['otp'];
	$mobile_num=$_POST['mobile_num'];
	$Cnt_user=$dbf->countRows("user","contact_no='$mobile_num' AND otp='$Otp'","");
	if($Cnt_user!=0){
		echo 'succcess';exit;
	}else{
		echo 'error';exit;
	}
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='PasswordChange'){
	$pwd=base64_encode(base64_encode($_POST['password']));
	$mobile_num=$_POST['mobile_num'];
	$dbf->updateTable("user","password='$pwd'","contact_no='$mobile_num'");
		echo "Success";exit;
	
}
if(isset($_REQUEST['opeartions']) && $_REQUEST['opeartions']=='UpdatePassword'){
	$password = base64_encode(base64_encode($_POST['password']));
	$newpassword =  base64_encode(base64_encode($_POST['newpassword']));
	$confipassword =  base64_encode(base64_encode($_POST['confipassword']));
	$user_id =  $_POST['user_id'];
  $cntPwd=$dbf->countRows("user","id='$user_id' AND password='$password'");
  if($cntPwd!=0){
	if($newpassword==$confipassword){
		$dbf->updateTable("user","password='$confipassword'","id='$user_id'");
		echo "succes";exit;
	}else{
		echo "confirm Password Not Match.";exit;
	}
  }else{
	echo"pwderr";exit;
  }
}


if(isset($_REQUEST['operations']) && $_REQUEST['operations']=='AddAddres'){

	$fnane = $_POST['fnane'];
	$lanme = $_POST['lanme'];
	$cname = $_POST['cname'];
	$zcode = $_POST['zcode'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$user_ip = $_POST['user_ip'];
	$string="user_id='$user_ip',first_name='$fnane',last_name='$lanme',city_id='$cname',pincode='$zcode',email='$email',number='$phone',lat='$lat',lng='$lng',address='$address',created_date=NOW()";
  $ins_id=$dbf->insertSet("address",$string);
 foreach($dbf->fetchOrder("address","user_id='$user_ip' AND is_delte='0'","address_id DESC","","")as $addres){ 
    $city=$dbf->fetchSingle("city",'*',"city_id='$addres[city_id]'");
    $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$addres[pincode]'");
    ?>
                <div class="uk-card uk-card-body uk-background-muted uk-card-small uk-margin-bottom ">
        <?= $addres['first_name'].' '.$addres['last_name'].','.$addres['email'].',<br>'.$addres['number'].','.$city['city_name'].','.$addres['address'].','.$pincode['pincode']?>
        <!-- <button class="btn btn-primary"uk-toggle="target: #modal-address<?= $addres['address_id']?>">Edit</button> -->
         <button class="btn btn-danger" type="button" onclick="addresDelte(<?=$addres['address_id']?>)">Delete</button>
      </div>    
<?php 
}
}

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='DleteAdres'){
	$addres_id = $_POST['addres_id'];
	 $dbf->updateTable("address","is_delte='1'","address_id='$addres_id'");
	 echo "succes";exit;	
	}
?>
