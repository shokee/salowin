<?php
include_once '../../admin/includes/class.Main.php';
# Object initialization of the class

/*
 * Add error_reporting to track error in code
 */
error_reporting(E_ALL);
/*
 * Specify domains from which requests are allowed
 */
header('Access-Control-Allow-Origin: *');

/*
 * Specify which request methods are allowed
 */
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

/*
 * Additional headers which may be sent along with the CORS request
 * The X-Requested-With header allows jQuery requests to go through
 */
header('Access-Control-Allow-Headers: X-Requested-With');
/*
 * Additional header for app
 */
header('Content-Type:application/json');

$dbf=new User();
date_default_timezone_set('Asia/Kolkata');
// $server_url_link="https://ownmystore.com/admin/";

//Check Active User

function UserStatus($arg){
  $dbf=new User();
   $cntUserStatus=$dbf->countRows("user","id='$arg' AND status='1' AND user_type='4'","");
  if($cntUserStatus!=0){
    return true;
  }else{
    return false;
  }
}

//Check Active User
	//To send Firebase notification to android device==================================
	
  function sendPushNotification($to = '', $data1=array(), $data2=array()) {
		
    $apiKey = "AAAAenpRW_k:APA91bHGKFZ3hiZPeYAm1hU76okOAlYEqv7oAg-PtJxlYAZUH396FBjUHp7IZxrj0EO2_eK7qpLT5JDq8W8u4IDwfwd1zZBiqas-iizJtDcStJu4Pow7EGEDYYpKg5JFEaoUPh6VfDTk"; //Place Server Legacy Key insted of api key
    
    $fields = array( 'to' => $to, 'notification' => $data1, 'data' => $data2,'priority'=>'high');
// 		print_r(json_encode($fields));exit;
    $headers = array ( 'Authorization: key=' . $apiKey, 'Content-Type: application/json' );
    
    $url = 'https://fcm.googleapis.com/fcm/send';
  
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($fields) );
  
    $result = curl_exec ( $ch );
        // echo $result;exit;
    curl_close ( $ch );
    return json_decode($result, true);
  }	
  //To send Firebase notification to android device==================================
  


//Banner 

if(isset($_REQUEST['method']) && $_REQUEST['method']=="GetSilders"){
  $id=$_POST['id'];
  $pin=$_POST['pin_id'];
  if(UserStatus($id)){
    $Array_of_Banner=array();
    $BannerIMg = $dbf->fetchOrder("banner","pin_id='$pin'","","","");
    if(!empty($BannerIMg)){
    foreach($BannerIMg as $Banner){
         $img=$server_url_link.'images/banner/'.$Banner['banner_image'];
         array_push($Array_of_Banner,array("img"=>$img,"title"=>$Banner['banner_title']));
    }
    echo '{"success":"true","All_banner":'.json_encode($Array_of_Banner).'}';exit;
}else{
    echo '{"success":"false","msg":"No Image Availabel Yet."}';exit;
}
}else{
  echo '{"success":"false","msg":"Your Are Blocked."}';exit;
}

}

//Banner 

//Ads Img
if(isset($_REQUEST['method']) && $_REQUEST['method']=="GetAdsImg"){
  $id=$_POST['id'];
  $pin=$_POST['pin_id'];
  if(UserStatus($id)){
    $Array_of_Ads=array();
    $AdsIMg = $dbf->fetchOrder("addd","","","","");
    if(!empty($AdsIMg)){
    foreach($AdsIMg as $ads){
         $img=$server_url_link.'images/add/'.$ads['add_image'];
         array_push($Array_of_Ads,array("img"=>$img,"title"=>$ads['add_title'],"url"=>$ads['add_link']));
    }
    echo '{"success":"true","All_banner":'.json_encode($Array_of_Ads).'}';exit;
}else{
    echo '{"success":"false","msg":"No Ads Image Availabel Yet."}';exit;
}
}else{
  echo '{"success":"false","msg":"Your Are Blocked."}';exit;
}
}
//Ads Img
//Sign Up
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Signup"){
  $otp = rand(100000,999999);
$name=$dbf->checkXssSqlInjection($_POST['name']);
$email=$dbf->checkXssSqlInjection($_POST['email']);
$num=$dbf->checkXssSqlInjection($_POST['num']);
$fcm_id = $_POST['fcm_id'];
$password=base64_encode(base64_encode($_POST['password']));
$cntEmail=$dbf->countRows("user","email='$email'","");
$cntPhone=$dbf->countRows("user","contact_no='$num'","");
if($cntEmail!=0){
    echo '{"success":"false","msg":"Email Id Already Exist."}';exit;
}else if($cntPhone!=0){
    echo '{"success":"false","msg":"Phone Number Already Exist."}';exit;
}else{
    $string="full_name='$name', contact_no='$num', email='$email', password='$password',status='1',fcm_id='$fcm_id',user_type='4', created_date=NOW()";
    $ins_id=$dbf->insertSet("user",$string);
    echo '{"success":"true","msg":"Registration Successfully ,You Can Login Now."}';exit;

}
}


//Sign Up

//Sign In
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Signin"){
    $usr_name=$dbf->checkXssSqlInjection($_POST['usr_name']);
    $password=base64_encode(base64_encode($_POST['password']));
    $fcm_id = $_POST['fcm_id'];
    $cntPhone=$dbf->countRows("user","contact_no='$usr_name'  AND user_type='4'","");
    $cntEmail=$dbf->countRows("user","email='$usr_name' AND user_type='4'","");
    $username=$dbf->countRows("user","user_name='$usr_name' AND user_type='4'","");
    if($cntPhone!=0){
        $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"contact_no='$usr_name' AND password='$password' AND user_type='4'");
        if(!empty($ChkUser)){
          
          $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
          if($userStatus!=0){
            if($ChkUser['profile_image']!=''){
                $img=$server_url_link."images/thumb/".$ChkUser['profile_image'];
            }else{
                $img="";
            }
            $dbf->updateTable("user","fcm_id='$fcm_id'","id='$ChkUser[id]'");
            echo '{"success":"true","id":"'.$ChkUser['id'].'","name":"'.$ChkUser['full_name'].'","email":"'.$ChkUser['email'].'","img":"'.$img.'"}';exit;
          }else{
            echo '{"success":"false","msg":"Your Blocked."}';exit;
           }
          }else{
            echo '{"success":"false","msg":"Invalid Password."}';exit;
        }
    }else if($cntEmail!=0){
        $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"email='$usr_name' AND password='$password' AND user_type='4'");
        if(!empty($ChkUser)){
          $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
          if($userStatus!=0){
            if($ChkUser['profile_image']!=''){
                $img=$server_url_link."images/thumb/".$ChkUser['profile_image'];
            }else{
                $img="";
            }
            $dbf->updateTable("user","fcm_id='$fcm_id'","id='$ChkUser[id]'");
            echo '{"success":"true","id":"'.$ChkUser['id'].'","name":"'.$ChkUser['full_name'].'","email":"'.$ChkUser['email'].'","img":"'.$img.'"}';exit;
          }else{
            echo '{"success":"false","msg":"Your Blocked."}';exit;
          }
         }else{
            echo '{"success":"false","msg":"Invalid Password."}';exit;
        }
    }else if($username !=0){
        $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"user_name='$usr_name' AND password='$password' AND user_type='4'");
        if(!empty($ChkUser)){
          $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
          if($userStatus!=0){
            if($ChkUser['profile_image']!=''){
                $img=$server_url_link."images/thumb/".$ChkUser['profile_image'];
            }else{
                $img="";
            }
            $dbf->updateTable("user","fcm_id='$fcm_id'","id='$ChkUser[id]'");
            echo '{"success":"true","id":"'.$ChkUser['id'].'","name":"'.$ChkUser['full_name'].'","email":"'.$ChkUser['email'].'","img":"'.$img.'"}';exit;
          }else{
            echo '{"success":"false","msg":"Your Blocked."}';exit;
          }
        }else{
            echo '{"success":"false","msg":"Invalid Password."}';exit;
        }
    }else{
        echo '{"success":"false","msg":"Invalid Username."}';exit;
    }
}
//Sign In

//Forget Password
if(isset($_REQUEST['method']) && $_REQUEST['method']=="ForgetPassword"){
   $usr_name=$dbf->checkXssSqlInjection($_POST['usr_name']);

  $cntPhone=$dbf->countRows("user","contact_no='$usr_name'  AND user_type='4'","");
  $cntEmail=$dbf->countRows("user","email='$usr_name' AND user_type='4'","");
  $username=$dbf->countRows("user","user_name='$usr_name' AND user_type='4'","");
  $otp = rand(100000,999999);
  if($cntPhone!=0){
    $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image,contact_no',"contact_no='$usr_name' AND user_type='4'");
      $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
      if($userStatus!=0){
        $dbf->updateTable("user","otp='$otp'","id='$ChkUser[id]'");

          $apikey = "VsllEZjMRkKztBpUIKVmEA";
          $apisender = "DToDor";
          $msg ="Your OTP for GroGod login is  $otp";
          $num = $ChkUser['contact_no']; 
          $ms = rawurlencode($msg); 
          $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=0&number='.$num.'&text='.$ms.'&route=1';
          $ch=curl_init($url);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch,CURLOPT_POST,1);
          curl_setopt($ch,CURLOPT_POSTFIELDS,"");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
          $data = curl_exec($ch);

        echo '{"success":"true","id":"'.$ChkUser['id'].'","otp":"'.$otp.'"}';exit;
      }else{
        echo '{"success":"false","msg":"Your Blocked."}';exit;
       }
     
}else if($cntEmail!=0){
    $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image,contact_no',"email='$usr_name' AND user_type='4'");

      $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
      if($userStatus!=0){
        $dbf->updateTable("user","otp='$otp'","id='$ChkUser[id]'");

        $apikey = "VsllEZjMRkKztBpUIKVmEA";
        $apisender = "DToDor";
        $msg ="Your OTP for GroGod login is  $otp";
        $num = $ChkUser['contact_no']; 
        $ms = rawurlencode($msg); 
        $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=0&number='.$num.'&text='.$ms.'&route=1';
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
        $data = curl_exec($ch);
        echo '{"success":"true","id":"'.$ChkUser['id'].'","otp":"'.$otp.'"}';exit;
      }else{
        echo '{"success":"false","msg":"Your Blocked."}';exit;
       }
   
}else if($cntEmail!=0){
    $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image,contact_no',"user_name='$usr_name'  AND user_type='4'");
    
      $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
      if($userStatus!=0){
        $dbf->updateTable("user","otp='$otp'","id='$ChkUser[id]'");
        
        $apikey = "VsllEZjMRkKztBpUIKVmEA";
        $apisender = "DToDor";
        $msg ="Your OTP for GroGod login is  $otp";
        $num = $ChkUser['contact_no']; 
        $ms = rawurlencode($msg); 
        $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=0&number='.$num.'&text='.$ms.'&route=1';
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
        $data = curl_exec($ch);
        echo '{"success":"true","id":"'.$ChkUser['id'].'","otp":"'.$otp.'"}';exit;
      }else{
        echo '{"success":"false","msg":"Your Blocked."}';exit;
       }
      }else{
        echo '{"success":"false","msg":"Invalid Username."}';exit;
      }
}


//Forget Password

//Verify Forget OTP
if(isset($_REQUEST['method']) && $_REQUEST['method']=="verifyForgetOTP"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $otp=$dbf->checkXssSqlInjection($_POST['otp']);
  $password=base64_encode(base64_encode($dbf->checkXssSqlInjection($_POST['password'])));

  $cntUser=$dbf->countRows("user","id='$id' AND otp='$otp'","");
if($cntUser!=0){
  $dbf->updateTable("user","password='$password'","id='$id'");
  echo '{"success":"true","msg":"Password Reset Successfully."}';exit;
}else{
  echo '{"success":"false","msg":"Invalid OTP."}';exit;
}

}
//Verify Forget OTP

//Profile
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Profile"){
    $usr_id=$dbf->checkXssSqlInjection($_POST['user_id']);
    $ChkUser=$dbf->fetchSingle("user",'*',"id='$usr_id'");
    if(!empty($ChkUser)){
      $userStatus=$dbf->countRows("user","status='1' AND id='$usr_id'","");
      if($userStatus!=0){
        if($ChkUser['profile_image']!=''){
            $img=$server_url_link."images/thumb/".$ChkUser['profile_image'];
        }else{
            $img="";
        }
        echo '{"success":"true","id":"'.$ChkUser['id'].'","name":"'.$ChkUser['full_name'].'","email":"'.$ChkUser['email'].'","img":"'.$img.'"}';exit;
      }else{
        echo '{"success":"false","msg":"Your Blocked."}';exit;
       }
     }else{
      echo '{"success":"false","msg":"Invalid User."}';exit;
    }

    
}
//Profile

//Profile Update
if(isset($_REQUEST['method']) && $_REQUEST['method']=="ProfileUpdate"){
  $name=$dbf->checkXssSqlInjection($_POST['name']);
  $email=$dbf->checkXssSqlInjection($_POST['email']);
  $num=$dbf->checkXssSqlInjection($_POST['num']);
  $img=$_POST['img'];
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $cntEmail=$dbf->countRows("user","email='$email' AND id!='$id'","");
  $cntPhone=$dbf->countRows("user","contact_no='$num' AND id!='$id'","");
  if($cntEmail!=0){
    echo '{"success":"false","msg":"Email ID Already Exist."}';exit;
  }else if($cntPhone!=0){
    echo '{"success":"false","msg":"Phone Number Already Exist."}';exit;
  }else{
    if($img!=''){
    $upload_path="../../admin/images/thumb/";
    $user_img ="profile".date('Ymdhis'.$id.'.')."png";
    file_put_contents($upload_path.$user_img,base64_decode($img));
    $string="full_name='$name',email='$email',contact_no='$num',profile_image='$user_img',updated_date=NOW()";
    }else{
      $string="full_name='$name',email='$email',contact_no='$num',updated_date=NOW()";
    }
    $dbf->updateTable("user",$string,"id='$id'");
    echo '{"success":"true","msg":"Profile Updated Successfully."}';exit;
  }

}
//Profile Update


//Change Password
if(isset($_REQUEST['method']) && $_REQUEST['method']=="ChangePwd"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $password=base64_encode(base64_encode($_POST['password']));
  $password1=base64_encode(base64_encode($_POST['passwordnew']));

  $cntUser=$dbf->countRows("user","id='$id'","");
  if($cntUser!=0){
    $cntUserStatus=$dbf->countRows("user","id='$id' AND status='1'","");
    if($cntUserStatus!=0){
      $cntUserPwd=$dbf->countRows("user","id='$id' AND password='$password'","");
      if($cntUserPwd!=0){
        $dbf->updateTable("user","password='$password1'","id='$id'");
        echo '{"success":"true","msg":"Password Updated Successfully."}';exit;
      }else{
        echo '{"success":"false","msg":"Invaild Password."}';exit;
      }

    }else{
      echo '{"success":"false","msg":"Your Are Blocked."}';exit;
    }
  }else{
    echo '{"success":"false","msg":"Invalid User."}';exit;
  }
}
//Change Password

//My Order
if(isset($_REQUEST['method']) && $_REQUEST['method']=="MyOrder"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $cntUserStatus=$dbf->countRows("user","id='$id' AND status='1'","");
  if($cntUserStatus!=0){
    $Orderd_All = $dbf->fetchOrder("orders","user_id='$id'","created_date DESC","","order_id");
    if(!empty($Orderd_All)){
      $List_Order_array=array();
     
     
    foreach($Orderd_All as $Order){
      
      // $addres=$dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");
      // $address = $dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");   
      $city = $dbf->fetchSingle("city",'*',"city_id='$Order[city_id]'"); 
      $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$Order[pin]'");

      $address=array("name"=>$Order['fname'].' '.$Order['lname'],"contact"=>$Order['num'],"email"=>$Order['email'],
      "address"=>$Order['adress'],"city"=>$Order['city'],"pin"=>$Order['pin'],"lat"=>$Order['lat'],"lng"=>$Order['lng']);
    switch($Order['status']){
        case -1:
          $status="Canceled By Vendor";
        break;
        case 0:
          $status="New Order";
        break;
        case 1:
          $status="Order Received";
        break;
        case 2:
          $status="Processing";
        break;
        case 3:
          $status="Shipped";
        break;
        case 4:
          $status="Completed";
        break;
        case 5:
          $status="Return Approved";
        break;
        case 6:
          $status="Returned Process";
        break;
        case 7:
          $status="Returned Canceled";
        break;
        case 8:
          $status="Canceled";
        break;
        case 9:
          $status="Transaction Failed";
        break;
        case 10:
          $status="Processing";
        break;
        default:
        $status="Processingr";

    }

    $total=0;
    $Order_items_Array=array();
    foreach($dbf->fetchOrder("orders","order_id='$Order[order_id]' ","orders_id DESC","","") as $singleorder){
   
      if($singleorder['img']){
        $img=$server_url_link.'images/product/'.$singleorder['img'];
      }else{
        $img="";
      }
    
      if($singleorder['status']=='5'){ 
        $sub_total='-'.$singleorder['price']* $singleorder['qty'];
        $return_order='(Returned)';
        $total+=0;
       }else{
        $sub_total=$singleorder['price']* $singleorder['qty'];
        $total+=$sub_total;
          $return_order="";
        };

        if($singleorder['coupon_amnt']!=0){
          $CAmnt = $singleorder['coupon_amnt'];
          $Ccode=$singleorder['coupon_code'];
        }else{
          $CAmnt = "0";
          $Ccode= "";
        }

      array_push($Order_items_Array,array("produt_name"=>$singleorder['ordername'].$return_order,
      "img"=>$img,"price"=>$singleorder['price'],"qty"=>$singleorder['qty'],"sub_total"=>"$sub_total"));
    }

    $grand_total=$total+$Order['shipping_charge'];
    $grand_total=$grand_total-$Order['wallet']-$CAmnt;
    array_push($List_Order_array,array('order_id'=>$Order['order_id'],'order_date'=>date('d.m.Y',strtotime($Order['created_date'])),
    'pay_mode'=>$Order['payment_mode'],'status'=>$status,"view_deatails"=>$Order_items_Array,"total"=>"$total",
    "shipping_char"=>$Order['shipping_charge'],"wallet"=>'-'.$Order['wallet'],"grand_total"=>"$grand_total","coupon_code"=>$Ccode,"coupon_amnt"=>"$CAmnt","address"=>$address));
    }
    echo '{"success":"true","all_order":'.json_encode($List_Order_array).'}';exit;
  }else{
    echo '{"success":"false","msg":"No Order Availabel Yet."}';exit;
  } 
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
} 
//My Order

//All Addresss
if(isset($_REQUEST['method']) && $_REQUEST['method']=="getAddress"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $cntUserStatus=$dbf->countRows("user","id='$id' AND status='1'","");
  if($cntUserStatus!=0){
    $Addres=$dbf->fetchOrder("address","user_id='$id' AND is_delte='0'","address_id DESC","","");
    if(!empty($Addres)){
      $Array_of_Addrss=array();
    foreach($Addres as $addres){ 
      $city=$dbf->fetchSingle("city",'city_name',"city_id='$addres[city_id]'");
      $pincode=$dbf->fetchSingle("pincode",'pincode',"pincode_id='$addres[pincode]'");
      array_push($Array_of_Addrss,array("address_id"=>$addres['address_id'],'fname'=>$addres['first_name'],'lname'=>$addres['last_name'],'email'=>$addres['email'],"number"=>$addres['number'],
      "city"=>$city['city_name'],"city_id"=>$addres['city_id'],"address"=>$addres['address'],"pincode"=>$pincode['pincode'],"pin_id"=>$addres['pincode'],"lat"=>$addres['lat'],"lng"=>$addres['lng']));
  }
  echo '{"success":"true","all_ddress":'.json_encode($Array_of_Addrss).'}';exit;
}else{
  echo '{"success":"false","msg":"Address Not Availabel Yet."}';exit;
 }
}
 else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }

 }

//All Addresss

//Citys
if(isset($_REQUEST['method']) && $_REQUEST['method']=="getCity"){
   $id=$dbf->checkXssSqlInjection($_POST['id']);

if(UserStatus($id)){
  $arrya_of_City=array();
  $Citys=$dbf->fetchOrder("city","","city_name ASC","","");
  if(!empty($Citys)){
  foreach($Citys as $City){
    $Pins=$dbf->fetchOrder("pincode","city_id='$City[city_id]' AND status='1'","pincode ASC","","");
    if(!empty($Pins)){
      $arrya_of_PIN=array();
      foreach($Pins as $Pin){
        array_push($arrya_of_PIN,array('pincode_id'=>$Pin['pincode_id'],"pincode"=>$Pin['pincode']));
      }
      array_push($arrya_of_City,array('city_id'=>$City['city_id'],"city_name"=>$City['city_name'],"pin"=>$arrya_of_PIN));
    }
   
  }
  echo '{"success":"true","All_city":'.json_encode($arrya_of_City).'}';exit;
}else{
  echo '{"success":"false","msg":"City Not Availabel Yet."}';exit;
}
}else{
  echo '{"success":"false","msg":"Your Are Blocked."}';exit;
}
}
//Citys

//Pincode
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Pincode"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $city_id=$dbf->checkXssSqlInjection($_POST['city_id']);
if(UserStatus($id)){
  $arrya_of_PIN=array();
  $Pins=$dbf->fetchOrder("pincode","city_id='$city_id' AND status='1'","pincode ASC","","");
  if(!empty($Pins)){
  foreach($Pins as $Pin){
    array_push($arrya_of_PIN,array('pincode_id'=>$Pin['pincode_id'],"pincode"=>$Pin['pincode']));
  }
  echo '{"success":"true","all_pincode":'.json_encode($arrya_of_PIN).'}';exit;
}else{
  echo '{"success":"false","msg":"Pincode Not Availabel."}';exit;
}
}else{
  echo '{"success":"false","msg":"Your Are Blocked."}';exit;
}
}
//Pincode



//Add Address
if(isset($_REQUEST['method']) && $_REQUEST['method']=="addAddres"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
  $fname=$dbf->checkXssSqlInjection($_POST['fname']);
  $lname=$dbf->checkXssSqlInjection($_POST['lname']);
  $city_id=$dbf->checkXssSqlInjection($_POST['city_id']);
  $pin_id=$dbf->checkXssSqlInjection($_POST['pin_id']);
  $email=$dbf->checkXssSqlInjection($_POST['email']);
  $num=$dbf->checkXssSqlInjection($_POST['num']);
  $address=$dbf->checkXssSqlInjection($_POST['address']);
  $lat=$dbf->checkXssSqlInjection($_POST['lat']);
  $lng=$dbf->checkXssSqlInjection($_POST['lng']);
  $string="user_id='$id',first_name='$fname',last_name='$lname',city_id='$city_id',pincode='$pin_id',email='$email',number='$num',address='$address',lat='$lat',lng='$lng',created_date=NOW()";
  $ins_id=$dbf->insertSet("address",$string);
  echo '{"success":"true","msg":"Address Added Successfully.."}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
  
}
//Add Address

//Address Details
if(isset($_REQUEST['method']) && $_REQUEST['method']=="AddressDetails"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $Addres_id=$dbf->checkXssSqlInjection($_POST['Addres_id']);
  if(UserStatus($id)){
    $Address=$dbf->fetchSingle("address",'*',"address_id='$Addres_id'");
    if(!empty($Address)){
      $city = $dbf->fetchSingle("city",'*',"city_id='$Address[city_id]'"); 
      $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$Address[pincode]'");

      echo '{"success":"true","address_id":"'.$Addres_id.'","fname":"'.$Address["first_name"].'","lname":"'.$Address["last_name"].'","contact":"'.$Address['number'].'","email":"'.$Address['email'].'","address":"'.$Address['address'].'","city":"'.$city['city_name'].'","city_id":"'.$Address['city_id'].'","pincode":"'.$Address['pincode'].'","pin":"'.$pincode['pincode'].'","lat":"'.$pincode['lat'].'","lng":"'.$pincode['lng'].'"}';exit;
    }else{
      echo '{"success":"false","msg":"Inavlid Address."}';exit;
    }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Address Details


//Address Update
if(isset($_REQUEST['method']) && $_REQUEST['method']=="AddressUpdate"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $address_id=$dbf->checkXssSqlInjection($_POST['address_id']);
  $fname=$dbf->checkXssSqlInjection($_POST['fname']);
  $lname=$dbf->checkXssSqlInjection($_POST['lname']);
  $city_id=$dbf->checkXssSqlInjection($_POST['city_id']);
  $pin_id=$dbf->checkXssSqlInjection($_POST['pin_id']);
  $email=$dbf->checkXssSqlInjection($_POST['email']);
  $num=$dbf->checkXssSqlInjection($_POST['num']);
  $address=$dbf->checkXssSqlInjection($_POST['address']);
  $lat=$dbf->checkXssSqlInjection($_POST['lat']);
  $lng=$dbf->checkXssSqlInjection($_POST['lng']);
  if(UserStatus($id)){
  $string="first_name='$fname',last_name='$lname',city_id='$city_id',pincode='$pin_id',email='$email',number='$num',address='$address',updatd_dare=NOW(),lat='$lat',lng='$lng'";
  $dbf->updateTable("address",$string,"address_id='$address_id'");
  echo '{"success":"true","msg":"Address Updated Successfully."}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Address Update

// HoMe Api
if(isset($_REQUEST['method']) && $_REQUEST['method']=="HoMeApi"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $pin=$dbf->checkXssSqlInjection($_POST['pin_id']);
  $Array_of_Banner=array();
  if(UserStatus($id)){
  $BannerIMg = $dbf->fetchOrder("banner","pin_id='$pin'","","","");
  if(!empty($BannerIMg)){
  foreach($BannerIMg as $Banner){
       $img=$server_url_link.'images/banner/'.$Banner['banner_image'];
       array_push($Array_of_Banner,array("img"=>$img,"title"=>$Banner['banner_title']));
  }

 }else{
   $Array_of_Banner =array();
 }

 //Ads Img

 $Array_of_Ads=array();
    $AdsIMg = $dbf->fetchOrder("addd","pin_id='$pin' AND position!='4' LIMIT 0,6","","","");
    if(!empty($AdsIMg)){
      $i=1;
    foreach($AdsIMg as $ads){
         $img=$server_url_link.'images/add/'.$ads['add_image'];
         array_push($Array_of_Ads,array("img".$i++=>$img,"title"=>$ads['add_title'],"url"=>$ads['add_link']));
    }
    
    for($j=$i;$j<=6;$j++){
      array_push($Array_of_Ads,array("img".$j=>"","title"=>"","url"=>""));
    }
  
}else{
  for($k=1;$k<8;$k++){
  array_push($Array_of_Ads,array("img".$k=>"","title"=>"","url"=>""));
  }
}

//Single Banner Image
$SingleImg=$dbf->fetchSingle("addd",'*',"pin_id='$pin' AND position='4'");
if(!empty($SingleImg)){
  $imgs=$server_url_link.'images/add/'.$SingleImg['add_image'];
  array_push($Array_of_Ads,array("img7"=>$imgs,"title"=>$SingleImg['add_title'],"url"=>$SingleImg['add_link']));
}else{
  array_push($Array_of_Ads,array("img7"=>"","title"=>"","url"=>""));
 
}
//Single Banner Img
 //Ads Img
//Latest Products
$all_shop=$dbf->fetchOrder("user","user_type='3' AND pin='$pin'","id ASC","","");

$Arr_prod_id=array();
$Array_produts=array();
if(!empty($all_shop)){
foreach ($all_shop as $shop) {
  foreach($dbf->fetchOrder("product","find_in_set('$shop[id]',vendor_id)","product_id ASC","","") as $product){
   array_push($Arr_prod_id, $product['product_id']);
  }
}
if(!empty($Arr_prod_id)){
  $prod_id = implode(',', $Arr_prod_id);
  $All_Produts = $dbf->fetchOrder("product","status='1' AND product_id IN($prod_id)","product_id DESC LIMIT 0,8","","");
  if(!empty($All_Produts)){
    foreach($All_Produts as $product){
      $Brand = $dbf->fetchSingle("brands",'brands_name',"brands_id='$product[brands_id]' AND status='1'");
      $primary_img=$server_url_link."images/product/".$product['primary_image'];
    array_push($Array_produts,array("product_id"=>$product['product_id'],"product_name"=>$product['product_name'],"description"=>$product['description'],"brands_id"=>$product['brands_id'],
    "brand_name"=>$Brand['brands_name'],"img"=>$primary_img));
    }
  }else{
    $Array_produts=$Array_produts;
  }
}
}else{
$Array_produts=[];
}
//Latest Products

//Today Trending
$Arr_prodTren_id=array();
$Array_trend_produts=array();

$All_Trendig = $dbf->fetchOrder("today_trending","pin_id='$pin'","today_trending_id DESC","product_id","");
if(!empty($All_Trendig)){ 
  foreach($All_Trendig as $Trending){
    $product=$dbf->fetchSingle("product",'*',"product_id='$Trending[product_id]'");
      $Brand = $dbf->fetchSingle("brands",'brands_name',"brands_id='$product[brands_id]' AND status='1'");
      $primary_img=$server_url_link."images/product/".$product['primary_image'];
    array_push($Array_trend_produts,array("product_id"=>$product['product_id'],"product_name"=>$product['product_name'],"description"=>$product['description'],"brands_id"=>$product['brands_id'],
    "brand_name"=>$Brand['brands_name'],"img"=>$primary_img));
    }
    $Array_trend_produts=$Array_trend_produts;
}else{
$Array_trend_produts=[];
}
//Today Trending

//Nearest Shop
$NearEstShop=array();
if(!empty($all_shop)){
  foreach ($all_shop as $shop) {
    $country = $dbf->fetchSingle("country",'country_name',"country_id='$shop[country_id]'");
    $state = $dbf->fetchSingle("state",'state_name',"state_id='$shop[state_id]'");
    $city = $dbf->fetchSingle("city",'city_name',"city_id='$shop[city_id]'");
    if($shop['logo_image']){
    $logo_img = $server_url_link."images/vendor/".$shop['logo_image'];
    }else{
      $logo_img = $server_url_link."images/default.png";
    }
    if($shop['banner_image']){
      $shop_img = $server_url_link."images/vendor/".$shop['banner_image'];
    }else{
      $shop_img = $server_url_link."images/default.png";
    }
    array_push($NearEstShop,array("shop_id"=>$shop['id'],"shop_name"=>$shop['shop_name'],"logo"=>$logo_img,"img"=>$shop_img,"city_id"=>$shop['city_id'],"city_name"=>$city['city_name'],"state_id"=>$shop['state_id'],"state"=>$state['state_name'],"country_id"=>$shop['country_id'],"country"=>$country['country_name']));
  }
}else{
  $NearEstShop=[];
}
//Nearest Shop
 echo '{"success":"true","all_banner":'.json_encode($Array_of_Banner).',"all_adds":'.json_encode($Array_of_Ads).',"all_latest_products":'.json_encode($Array_produts).',"all_trending_products":'.json_encode($Array_trend_produts).',"nearest_shop":'.json_encode($NearEstShop).'}';exit;
}else{
  echo '{"success":"false","msg":"Your Are Blocked."}';exit;
}
}
// HoMe Api

//Category
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Category"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
    $CAtegory_of_array=array();
    $Category=$dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","","");
    if(!empty($Category)){
    foreach($Category as $product_catagory_1){
      $cate_img=  $server_url_link."images/category/".$product_catagory_1['img'];
      $SubCategory=$dbf->fetchOrder("product_catagory_2","product_catagory_1_id='$product_catagory_1[product_catagory_1_id]'","","","");
        $Array_of_subcate=array();
      if(!empty($SubCategory)){
          foreach($SubCategory as $subCate){
              $Arry_SubCategory2=array();
              $SubCategory2=$dbf->fetchOrder("product_catagory_3","product_catagory_2_id='$subCate[product_catagory_2_id]'","","","");
              if(!empty($SubCategory2)){
                foreach($SubCategory2 as $SubCate2){
                array_push($Arry_SubCategory2,array("subcate2_id"=>$SubCate2['product_catagory_3_id'],"sub_catename"=>$SubCate2['product_catagory_3_name']));
                }
              array_push($Array_of_subcate,array("subcate_id"=>$subCate['product_catagory_2_id'],"subcate_name"=>$subCate['product_catagory_2_name'],"subcate2"=>$Arry_SubCategory2));
              }else{
              array_push($Array_of_subcate,array("subcate_id"=>$subCate['product_catagory_2_id'],"subcate_name"=>$subCate['product_catagory_2_name'],"subcate2"=>$Arry_SubCategory2));
              }
            }
      array_push($CAtegory_of_array,array('category_id'=>$product_catagory_1['product_catagory_1_id'],"category_name"=>$product_catagory_1['product_catagory_1_name'],"img"=>$cate_img,"subcate"=>$Array_of_subcate));
     }else{
      array_push($CAtegory_of_array,array('category_id'=>$product_catagory_1['product_catagory_1_id'],"category_name"=>$product_catagory_1['product_catagory_1_name'],"img"=>$cate_img,"subcate"=>$Array_of_subcate));
     }
    }
    echo '{"success":"true","all_category":'.json_encode($CAtegory_of_array).'}';exit;
  }else{
    echo '{"success":"false","msg":"Category Not Availabel Yet."}';exit;
  }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Category
//Brand
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Brands"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
    $Brands_of_array=array();
    $Brands=$dbf->fetchOrder("brands","status='1'","brands_id ASC","","");
    if(!empty($Brands)){
        foreach($Brands as $Brand){
          $Brand_img = $server_url_link."images/brands/".$Brand['images'];
          array_push($Brands_of_array,array('brand_id'=>$Brand['brands_id'],"brand_name"=>$Brand['brands_name'],"img"=>$Brand_img));
        }
        echo '{"success":"true","All_brands":'.json_encode($Brands_of_array).'}';exit;
    }else{
      echo '{"success":"true","All_brands":'.json_encode($Brands_of_array).'}';exit;
    }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Brand
//Wallet Amnt
if(isset($_REQUEST['method']) && $_REQUEST['method']=="WalletAmnt"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
    $Wallet_array=array();
    $Wallet_amnt=$dbf->fetchSingle("user",'wallet',"id='$id'");
    $Wallet_hist=$dbf->fetchOrder("wallet_histru","status='1'","brands_id ASC","","");
    if(!empty($Wallet_hist)){
      foreach($Wallet_hist as $wlt_his){
        if($wlt_his['pay_type']=='0'){
          $Pay_type="Debit";
        }else{
          $Pay_type="Credit";
        }
    array_push($Wallet_array,array("amount"=>$wlt_his['amount'],"pay_type"=>$Pay_type,"remark"=>$wlt_his['remark'],"date"=>date('d-m-Y',strtotime($wlt_his['date']))));
      }
    }
    echo '{"success":"true","wallet":"'.$Wallet_amnt['wallet'].'","wallet_history":'.json_encode($Wallet_array).'}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Wallet Amnt


//Category Wise Shop
if(isset($_REQUEST['method']) && $_REQUEST['method']=="CateWiseShop"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $category_id=$dbf->checkXssSqlInjection($_POST['category_id']);
  $pin=$dbf->checkXssSqlInjection($_POST['pin_id']);
  if(UserStatus($id)){
    $Arry_of_shop=array();

    $ArryProdId=array();
    foreach ($dbf->fetchOrder("pro_rel_cat1","catagory1_id='$category_id'","","product_id","") as $prod_id) {
        array_push($ArryProdId, $prod_id['product_id']);
    }
    if(!empty($ArryProdId)){
      $prod_id = implode(',',$ArryProdId);
      $Shops_id=array();

          foreach ($dbf->fetchOrder("product","product_id IN($prod_id)","","vendor_id","") as $vendor) {
            if($vendor['vendor_id']!=''){
          array_push($Shops_id, $vendor['vendor_id']);
          }
          }
        if(!empty($Shops_id)){
          $Shops_id=implode(',', $Shops_id);
          $all_shop=$dbf->fetchOrder("user","user_type='3' AND status='1'  AND pin='$pin' AND id IN($Shops_id)","id ASC","","");
          if(!empty($all_shop)){
            foreach($all_shop as $shop){
              $country = $dbf->fetchSingle("country",'country_name',"country_id='$shop[country_id]'");
              $state = $dbf->fetchSingle("state",'state_name',"state_id='$shop[state_id]'");
              $city = $dbf->fetchSingle("city",'city_name',"city_id='$shop[city_id]'");
              if($shop['logo_image']){
              $logo_img = $server_url_link."images/vendor/".$shop['logo_image'];
              }else{
                $logo_img = $server_url_link."images/default.png";
              }
              if($shop['banner_image']){
                $shop_img = $server_url_link."images/vendor/".$shop['banner_image'];
              }else{
                $shop_img = $server_url_link."images/default.png";
              }

              array_push($Arry_of_shop,array("shop_id"=>$shop['id'],"shop_name"=>$shop['shop_name'],"logo"=>$shop_img,"img"=>$shop_img,"city_id"=>$shop['city_id'],"city_name"=>$city['city_name'],"state_id"=>$shop['state_id'],"state"=>$state['state_name'],"country_id"=>$shop['country_id'],"country"=>$country['country_name']));
          echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;
            }
          }else{
            echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;
          }
        }else{
          echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;
        }
    }else{
      echo '{"success":"true","all_shops":No shop Found}';exit;

    }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Category Wise Shop

//SubCategory Wise Shop
if(isset($_REQUEST['method']) && $_REQUEST['method']=="SubCateWiseShop"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $category_id=$dbf->checkXssSqlInjection($_POST['subcategory_id']);
  $pin=$dbf->checkXssSqlInjection($_POST['pin_id']);
  if(UserStatus($id)){
    $Arry_of_shop=array();

    $ArryProdId=array();
    foreach ($dbf->fetchOrder("pro_rel_cat2","catagory2_id='$category_id'","","product_id","") as $prod_id) {
        array_push($ArryProdId, $prod_id['product_id']);
    }
    if(!empty($ArryProdId)){
      $prod_id = implode(',',$ArryProdId);
      $Shops_id=array();

          foreach ($dbf->fetchOrder("product","product_id IN($prod_id)","","vendor_id","") as $vendor) {
            if($vendor['vendor_id']!=''){
          array_push($Shops_id, $vendor['vendor_id']);
          }
          }
        if(!empty($Shops_id)){
          $Shops_id=implode(',', $Shops_id);
          $all_shop=$dbf->fetchOrder("user","user_type='3' AND status='1'  AND pin='$pin' AND id IN($Shops_id)","id ASC","","");
          if(!empty($all_shop)){
            foreach($all_shop as $shop){
              $country = $dbf->fetchSingle("country",'country_name',"country_id='$shop[country_id]'");
              $state = $dbf->fetchSingle("state",'state_name',"state_id='$shop[state_id]'");
              $city = $dbf->fetchSingle("city",'city_name',"city_id='$shop[city_id]'");
              if($shop['logo_image']){
              $logo_img = $server_url_link."images/vendor/".$shop['logo_image'];
              }else{
                $logo_img = $server_url_link."images/default.png";
              }
              if($shop['banner_image']){
                $shop_img = $server_url_link."images/vendor/".$shop['banner_image'];
              }else{
                $shop_img = $server_url_link."images/default.png";
              }

              array_push($Arry_of_shop,array("shop_id"=>$shop['id'],"shop_name"=>$shop['shop_name'],"logo"=>$shop_img,"img"=>$shop_img,"city_id"=>$shop['city_id'],"city_name"=>$city['city_name'],"state_id"=>$shop['state_id'],"state"=>$state['state_name'],"country_id"=>$shop['country_id'],"country"=>$country['country_name']));
          echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;
            }
          }else{
            echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;
          }
        }else{
          echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;
        }
    }else{
      echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;

    }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//SubCategory Wise Shop


//SubCategory2 Wise Shop
if(isset($_REQUEST['method']) && $_REQUEST['method']=="SubCateWiseShop2"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $category_id=$dbf->checkXssSqlInjection($_POST['subcategory_id2']);
  $pin=$dbf->checkXssSqlInjection($_POST['pin_id']);
  if(UserStatus($id)){
    $Arry_of_shop=array();

    $ArryProdId=array();
    foreach ($dbf->fetchOrder("pro_rel_cat3","catagory3_id='$category_id'","","product_id","") as $prod_id) {
        array_push($ArryProdId, $prod_id['product_id']);
    }
    if(!empty($ArryProdId)){
      $prod_id = implode(',',$ArryProdId);
      $Shops_id=array();

          foreach ($dbf->fetchOrder("product","product_id IN($prod_id)","","vendor_id","") as $vendor) {
            if($vendor['vendor_id']!=''){
          array_push($Shops_id, $vendor['vendor_id']);
          }
          }
        if(!empty($Shops_id)){
          $Shops_id=implode(',', $Shops_id);
          $all_shop=$dbf->fetchOrder("user","user_type='3' AND status='1'  AND pin='$pin' AND id IN($Shops_id)","id ASC","","");
          if(!empty($all_shop)){
            foreach($all_shop as $shop){
              $country = $dbf->fetchSingle("country",'country_name',"country_id='$shop[country_id]'");
              $state = $dbf->fetchSingle("state",'state_name',"state_id='$shop[state_id]'");
              $city = $dbf->fetchSingle("city",'city_name',"city_id='$shop[city_id]'");
              if($shop['logo_image']){
              $logo_img = $server_url_link."images/vendor/".$shop['logo_image'];
              }else{
                $logo_img = $server_url_link."images/default.png";
              }
              if($shop['banner_image']){
                $shop_img = $server_url_link."images/vendor/".$shop['banner_image'];
              }else{
                $shop_img = $server_url_link."images/default.png";
              }

              array_push($Arry_of_shop,array("shop_id"=>$shop['id'],"shop_name"=>$shop['shop_name'],"logo"=>$shop_img,"img"=>$shop_img,"city_id"=>$shop['city_id'],"city_name"=>$city['city_name'],"state_id"=>$shop['state_id'],"state"=>$state['state_name'],"country_id"=>$shop['country_id'],"country"=>$country['country_name']));
          echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;
            }
          }else{
            echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;
          }
        }else{
          echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;
        }
    }else{
      echo '{"success":"true","all_shops":'.json_encode($Arry_of_shop).'}';exit;

    }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//SubCategory2 Wise Shop



//Shop Products
if(isset($_REQUEST['method']) && $_REQUEST['method']=="ShopofProduct"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $shop_id=$dbf->checkXssSqlInjection($_POST['shop_id']);

if(UserStatus($id)){
                   $prod_array=array();
                   foreach($dbf->fetchOrder("product","find_in_set('$shop_id',vendor_id)","product_id","","") as $prod_id){
                     array_push($prod_array,$prod_id['product_id']);
                   }
                   $product_id = implode(',', $prod_array);
 
                   $product_array_catagory_1=array();
                    foreach($dbf->fetchOrder("pro_rel_cat1","product_id IN ($product_id)","","catagory1_id","") as $catagory1_id){
                     array_push($product_array_catagory_1,$catagory1_id['catagory1_id']);
                   }
                 

                   $product_array_catagory_2=array();
                    foreach($dbf->fetchOrder("pro_rel_cat2","product_id IN ($product_id)","","catagory2_id","") as $catagory2_id){
                     array_push($product_array_catagory_2,$catagory2_id['catagory2_id']);
                   }
                   $pod_cate2=implode(',', $product_array_catagory_2);
 
 
                   $product_array_catagory_3=array();
                    foreach($dbf->fetchOrder("pro_rel_cat3","product_id IN ($product_id)","","catagory3_id","") as $catagory3_id){
                     array_push($product_array_catagory_3,$catagory3_id['catagory3_id']);
                   }
                   $pod_cate3=implode(',', $product_array_catagory_3);

                   $ProCategies=array();
                   if(!empty($product_array_catagory_1)){
                    $pod_cate1=implode(',', $product_array_catagory_1);
                   $Prod_cate=$dbf->fetchOrder("product_catagory_1","product_catagory_1_id IN($pod_cate1)","product_catagory_1_name","","");
                   foreach($Prod_cate as $product_catagory_1){
                     $Subcate_array=array();
                    $Product_sub=$dbf->fetchOrder("product_catagory_2","product_catagory_2_id IN ($pod_cate2) AND product_catagory_1_id='$product_catagory_1[product_catagory_1_id]'","product_catagory_2_name","","");
                    if(!empty($Product_sub)){ 
                    foreach($Product_sub as $product_catagory_2){
                      $Array_subcate2 = array();
                      $sub_cate2 =$dbf->fetchOrder("product_catagory_3","product_catagory_3_id IN ($pod_cate3) AND product_catagory_2_id='$product_catagory_2[product_catagory_2_id]'","","","");
                      if(!empty($sub_cate2)){
                      foreach($sub_cate2 as $product_catagory_3){
                        array_push($Array_subcate2,array('subcate_id2'=>$product_catagory_3['product_catagory_3_id'],'subcate_name2'=>$product_catagory_3['product_catagory_3_name']));
                      }
                      array_push($Subcate_array,array('subcate_id'=>$product_catagory_2['product_catagory_2_id'],'subcate_name'=>$product_catagory_2['product_catagory_2_name'],"all_cate2"=>$Array_subcate2));
                      }
                    }
                    $cate_img=  $server_url_link."images/category/".$product_catagory_1['img'];
                    array_push($ProCategies,array('category_id'=>$product_catagory_1['product_catagory_1_id'],'category_name'=>$product_catagory_1['product_catagory_1_name'],"img"=>$cate_img,"all_subcate"=>$Subcate_array));
                   }
                 } 
                  }
 echo '{"success":"true","All_categories":'.json_encode($ProCategies).'}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Shop Products

//Product Single Page
if(isset($_REQUEST['method']) && $_REQUEST['method']=="productSingle"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $product_id=$dbf->checkXssSqlInjection($_POST['product_id']);
  $shop_id=$dbf->checkXssSqlInjection($_POST['shop_id']);
  if(UserStatus($id)){
    $product=$dbf->fetchSingle("product",'*',"product_id='$product_id'");
    $Product_Gallery_array=array();
    $Product_Gallery=$dbf->fetchOrder("gallery","product_id='$product_id'","gallery_id ASC","","");

    foreach($Product_Gallery as $gallery){
      $img = $server_url_link."images/gallery/thumb/".$gallery['image'];
      array_push($Product_Gallery_array,array("img"=>$img));
    }
$All_Variotions=$dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id='$shop_id'","","","");
$Price_of_Array=array();
if(!empty($All_Variotions)){
  foreach($All_Variotions as $varition){
    $Vari_price_single=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$varition[price_variation_id]'");

    $Measure_Single=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price_single[measure_id]'");
    array_push($Price_of_Array,array("price_varition_id"=>$varition['variations_values_id'],"mrp_price"=>$varition['mrp_price'],"sale_price"=>$varition['sale_price'],"weight"=> $Vari_price_single['units'],"unit"=>$Measure_Single['unit_name']));
  }  
}
$primary_img=$server_url_link."images/product/".$product['primary_image'];
$Oth_prod_Array=array();
$All_Produts=$dbf->fetchOrder("product","status='1' AND find_in_set('$shop_id',vendor_id) AND $product_id='$product_id'","product_id ASC","","product_id");
  foreach($All_Produts as $oth_product){
    $FirstVari_price=$dbf->fetchSingle("variations_values",'*',"product_id='$oth_product[product_id]' AND vendor_id='$shop_id'");
    $Vari_price=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$FirstVari_price[price_variation_id]'");
    $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price[measure_id]'");
    $Brand = $dbf->fetchSingle("brands",'brands_name',"brands_id='$oth_product[brands_id]' AND status='1'");
    $oth_primary_img=$server_url_link."images/product/".$oth_product['primary_image'];

    $All_Variotions=$dbf->fetchOrder("variations_values","product_id='$oth_product[product_id]' AND vendor_id='$shop_id'","","","");
    $OthPrice_of_Array = array();
    if(!empty($All_Variotions)){
      foreach($All_Variotions as $varition){
        $Vari_price_single=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$varition[price_variation_id]'");

        $Measure_Single=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price_single[measure_id]'");
        array_push($OthPrice_of_Array,array("price_varition_id"=>$varition['variations_values_id'],"mrp_price"=>$varition['mrp_price'],"sale_price"=>$varition['sale_price'],"weight"=> $Vari_price_single['units'],"unit"=>$Measure_Single['unit_name']));
      }  
    }

      array_push($Oth_prod_Array,array("product_id"=>$oth_product['product_id'],"product_name"=>$oth_product['product_name'],"description"=>$oth_product['description'],"brands_id"=>$oth_product['brands_id'],
      "brand_name"=>$Brand['brands_name'],"img"=>$oth_primary_img,"price_varition_id"=>$FirstVari_price['variations_values_id'],"mrp_price"=>$FirstVari_price['mrp_price'],"sale_price"=>$FirstVari_price['sale_price'],"weight"=>$Vari_price['units'],"unit"=>$Measure['unit_name'],"Oth_price_vari"=>$OthPrice_of_Array));
  }
    echo '{"success":"true","product_id":"'.$product['product_id'].'","product_name":"'.$product['product_name'].'","description":"'.$product['description'].'","img":"'.$primary_img.'","product_gallery":'.json_encode($Product_Gallery_array).',"Price_varitions":'.json_encode($Price_of_Array).',"other_products":'.json_encode($Oth_prod_Array).'}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Product Single Page

//Shipping Charge
if(isset($_REQUEST['method']) && $_REQUEST['method']=="ShippingChr"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $Shipping_Array=array();
  if(UserStatus($id)){
  $All_ShippingChr=$dbf->fetchOrder("shipping","status='1'","","","");
  if(!empty($All_ShippingChr)){
  foreach ($All_ShippingChr as $Shipping) {
    array_push($Shipping_Array,array("ship_id"=>$Shipping['shipping_id'],"ship_type"=>$Shipping['name'],"ship_price"=>$Shipping['price']));
  }
  echo '{"success":"true","msg":'.json_encode($Shipping_Array).'}';exit;
}else{
  echo '{"success":"true","msg":'.json_encode($Shipping_Array).'}';exit;
}
}else{
  echo '{"success":"false","msg":"Your Are Blocked."}';exit;
}
}
//Shipping Charge

//Check Out Used Wallet
if(isset($_REQUEST['method']) && $_REQUEST['method']=="PaidtWalletAmnt"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
    $Wallet=$dbf->fetchSingle("user","wallet","id='$id'");
    echo '{"success":"true","wallet_ammt":"'.$Wallet['wallet'].'"}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Check Out Used Wallet

//Apply Couponcode
if(isset($_REQUEST['method']) && $_REQUEST['method']=="GetCouponCode"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $code=$dbf->checkXssSqlInjection($_POST['c_code']);
  $price=$dbf->checkXssSqlInjection($_POST['cart_price']);
  $date = date('Y-m-d');
  if(UserStatus($id)){
    $CntCoupon=$dbf->countRows("coupon_code","code='$code'","");
    if($CntCoupon!=0){
      $Couponcode=$dbf->fetchSingle("coupon_code",'*',"code='$code'");
      if($Couponcode['valid_uo_to']>=$date){
        if($Couponcode['used_up_to']>0){
          if($Couponcode['price_cart']<=$price){
            if($Couponcode['discount_type']=='2'){
              $mnt_dedc=($price*$Couponcode['discount_value'])/100;
              }else{
              $mnt_dedc=$Couponcode['discount_value'];
            }
            echo '{"success":"true","coupon_amnt":"'.$mnt_dedc.'"}';exit;
          }else{
            echo '{"success":"false","msg": "Is Applicable Only Cart Price Equal And Greater "'.$Couponcode['price_cart'].'}';exit;
          }
        }else{
          echo '{"success":"false","msg":"Not Available Coupon Code."}';exit;
        }
      }else{
        echo '{"success":"false","msg":"Expired Coupon Code."}';exit;
      }
    }else{
      echo '{"success":"false","msg":"Invalid Coupon Code."}';exit;
    }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Apply  Couponcode
//Product Wise Shop
if(isset($_REQUEST['method']) && $_REQUEST['method']=="GetProductWiseShop"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $Product_id=$dbf->checkXssSqlInjection($_POST['product_id']);
  $pin=$dbf->checkXssSqlInjection($_POST['pin_id']);
  if(UserStatus($id)){
      $Array_of_Shop = array();
      $Arr_Of_prod = array();
      $Products=$dbf->fetchOrder("product","product_id='$Product_id'","product_id ASC","","");
if(!empty($Products)){
   foreach($Products as $Vendor){
     array_push($Arr_Of_prod, $Vendor['vendor_id']);
  }
  if(!empty($Arr_Of_prod)){
    $vendor_list=implode(',',$Arr_Of_prod);
    $all_shop=$dbf->fetchOrder("user","user_type='3' AND status='1' AND id IN($vendor_list) AND pin='$pin'","id ASC","","");
    if(!empty($all_shop)){
      foreach($all_shop as $shop){
        $country = $dbf->fetchSingle("country",'country_name',"country_id='$shop[country_id]'");
        $state = $dbf->fetchSingle("state",'state_name',"state_id='$shop[state_id]'");
        $city = $dbf->fetchSingle("city",'city_name',"city_id='$shop[city_id]'");
        if($shop['logo_image']){
        $logo_img = $server_url_link."images/vendor/".$shop['logo_image'];
        }else{
          $logo_img = $server_url_link."images/default.png";
        }
        if($shop['banner_image']){
          $shop_img = $server_url_link."images/vendor/".$shop['banner_image'];
        }else{
          $shop_img = $server_url_link."images/default.png";
        }
        array_push($Array_of_Shop,array("shop_id"=>$shop['id'],"shop_name"=>$shop['shop_name'],"logo"=>$shop_img,"img"=>$shop_img,"city_id"=>$shop['city_id'],"city_name"=>$city['city_name'],"state_id"=>$shop['state_id'],"state"=>$state['state_name'],"country_id"=>$shop['country_id'],"country"=>$country['country_name']));
      }
     
      echo '{"success":"true","all_shops":'.json_encode($Array_of_Shop).'}';exit;
    }else{
      echo '{"success":"true","all_shops":'.json_encode($Array_of_Shop).'}';exit;
    }
  }else{
    echo '{"success":"true","all_shops":'.json_encode($Array_of_Shop).'}';exit;
  }
}else{
  echo '{"success":"true","all_shops":'.json_encode($Array_of_Shop).'}';exit;
}
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Product Wise Shop



//Order Of Process
if(isset($_REQUEST['method']) && $_REQUEST['method']=="OrderProcess"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
  $local_url="http://localhost:8080/grogod/";
  $server_url="http://grogod.com/";
  $order_id = strtoupper('GRO-'.$dbf->randomPassword());
  $shop_id =  $_POST['shop_id'];
  $user_id =  $id;
  $addres_id = $_POST['adresss_id'];
  $shipChar = $_POST['shipChar'];
  $ship_typ = $_POST['shiptype'];
  $pay_mode = $_POST['payment_type'];
  $Wallet_paid =  $_POST['wallet_paid'];
  $product_id =$_POST['product_id'];
  $product_id = explode(',',$product_id);
  $qty = $_POST['qty'];
  $qty =explode(',',$qty);
  $validation_id = $_POST['price_variation_id'];
  $validation_id =explode(',',$validation_id);
  $coupon_code = $_POST['coupon_code'];
  $coupon_amnt = $_POST['code_amnt'];
  $delivery_date = $_POST['delivery_date'];
  $delivery_tme = $_POST['delivery_tme'];

  if($pay_mode=='cod'){
    if($Wallet_paid!='' && $Wallet_paid!='0'){
      $Wallet=$dbf->fetchSingle("user","wallet","id='$id'");
      $Wallet['wallet'];
        $wallet_deduc=$Wallet_paid;
        $Wallet_histry_str = "amount='$wallet_deduc',remark='Brought Product',user_id='$id',pay_type='0',date=NOW()";
        $ins_id=$dbf->insertSet("wallet_histru",$Wallet_histry_str);
    
         $updateWallet=$Wallet['wallet']-$wallet_deduc;
         $dbf->updateTable("user","wallet='$updateWallet'","id='$id'");
        $pay_mode=$pay_mode."& Wallet Paid";
       }else{
        $pay_mode=$pay_mode;
         $wallet_deduc=0;
       }
     
       if($coupon_amnt==0){
        $coupon_code="";
        }else{
          $coupon_code= $coupon_code;
          $Couponcode=$dbf->fetchSingle("coupon_code",'used_up_to',"code='$coupon_code'");
          $qtys=$Couponcode['used_up_to']-1;
            $dbf->updateTable("coupon_code","used_up_to='$qtys'","code='$coupon_code'");
        }
      
      
     
       $Address=$dbf->fetchSingle("address",'*',"address_id='$addres_id'");
       $city=$dbf->fetchSingle("city",'*',"city_id='$Address[city_id]'");
       $Pin=$dbf->fetchSingle("pincode",'*',"pincode_id='$Address[pincode]'");
         $ArryProd_status=array();
         $CartArry=array();
         $ProdArry=array();
      //  foreach ($Carts as $cart) {
        for($k=0;$k<count($product_id);$k++){
       $products=$dbf->fetchSingle("product",'*',"product_id='$product_id[$k]' AND status='1'");
       $Price_Vari=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$validation_id[$k]'");
       $Vari_price=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$Price_Vari[price_variation_id]'");
       $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price[measure_id]'");
    
    
    
       $vendor=$dbf->fetchSingle("user",'*',"id='$shop_id'");
       $EmailUser=$dbf->fetchSingle("user",'email',"id='$user_id'");
       $price=$Price_Vari['sale_price'];
        
         $ordername=$products['product_name'].'-'.$Vari_price['units'].$Measure['unit_name'];
       $string1="ordername='$ordername',qty='$qty[$k]',img='$products[primary_image]',price='$price',user_id='$user_id',vendor_id='$shop_id',shipping_type='$ship_typ',shipping_charge='$shipChar',
       order_id='$order_id',address_id='$addres_id',payment_mode='$pay_mode',coupon_code='$coupon_code',coupon_amnt='$coupon_amnt',wallet='$wallet_deduc',created_date=NOW(),
       fname='$Address[first_name]',lname='$Address[last_name]',num='$Address[number]',email='$Address[email]',adress='$Address[address]',
       city='$city[city_name]',pin='$Pin[pincode]',lat='$Address[lat]',lng='$Address[lng]',delivery_date='$delivery_date',time_slot='$delivery_tme'";
       $dbf->insertSet("orders",$string1);
        }
        $User=$dbf->fetchSingle("user",'full_name,contact_no',"id='$user_id'");
        $Shop=$dbf->fetchSingle("user",'shop_name,fcm_id',"id='$shop_id'");
        $apikey = "VsllEZjMRkKztBpUIKVmEA";
        $apisender = "DToDor";
        $msg ="Dear $User[full_name], Your order has been received by  $Shop[shop_name]. We will shortly confirm the delivery. Kindly use your order number $order_id for  reference ";
        $num = $User['contact_no']; 
        $ms = rawurlencode($msg); 
        $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=0&number='.$num.'&text='.$ms.'&route=1';
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
        $data = curl_exec($ch);

        $to=$Shop['fcm_id'];
      	$data1=array(
          'title'=>"OwnMyStore",
          'body'=>"New Order Received,#$order_id"
        );
        $data2=array(''=>'');
        sendPushNotification($to,$data1,$data2); 
       
  echo '{"success":"true","msg":"Order Create Successfully.","order_id":"'.$order_id.'"}';exit;
  }else{
    echo '{"success":"false","msg":"Online Payment Not Availabel Yet.."}';exit;
  }
}else{
  echo '{"success":"false","msg":"Your Are Blocked."}';exit;
} 
}
//Order Of Process



//Cancel Order 
if(isset($_REQUEST['method']) && $_REQUEST['method']=="CancelOrder"){
$id=$dbf->checkXssSqlInjection($_POST['id']);
$cancel_order_id=$dbf->checkXssSqlInjection($_POST['Order_id']);
if(UserStatus($id)){
  $dbf->updateTable("orders","status='8'","order_id='$cancel_order_id'");
    $Ordder_pay=$dbf->fetchSingle("orders",'*',"order_id='$cancel_order_id'");
    $User_Wallet=$dbf->fetchSingle("user",'wallet',"id='$Ordder_pay[user_id]'");
    if(strtolower($Ordder_pay['payment_mode'])==strtolower('cod& Wallet Paid')){
        $amount= $Ordder_pay['wallet']+$User_Wallet['wallet'];
        $dbf->updateTable("user","wallet='$amount'","id='$Ordder_pay[user_id]'");
        $string = "amount='$Ordder_pay[wallet]',remark='Cancel Order',user_id='$id',date=NOW()";
        $dbf->insertSet("wallet_histru",$string);
    }
   if(strtolower($Ordder_pay['payment_mode'])==strtolower('online& Wallet Paid')){
        
        $total_amnt=0;
        foreach($dbf->fetchOrder("orders","order_id='$cancel_order_id'","","price,qty","") as $CancelAmnt){
        $total_amnt+=$CancelAmnt['qty']*$CancelAmnt['price'];
        }

        $amount= ($total_amnt+$Ordder_pay['shipping_charge'])-$Ordder_pay['coupon_amnt'];
        $hist_amnt= $amount+$User_Wallet['wallet'];
        $dbf->updateTable("user","wallet='$hist_amnt'","id='$Ordder_pay[user_id]'");
        $string = "amount='$amount',remark='Cancel Order',user_id='$id',date=NOW()";
        $ins_id=$dbf->insertSet("wallet_histru",$string);

        $User=$dbf->fetchSingle("user",'full_name,contact_no',"id='$id'");
        $apikey = "VsllEZjMRkKztBpUIKVmEA";
        $apisender = "DToDor";
        $msg ="Dear $User[full_name], Your order $cancel_order_id  was cancelled as per your request. The amount will be added in your wallet for your next purchase. Kindly leave your valuable feedback.";
        $num = $User['contact_no']; 
        $ms = rawurlencode($msg); 
        $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=0&number='.$num.'&text='.$ms.'&route=1';
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
        $data = curl_exec($ch);


    }if(strtolower($Ordder_pay['payment_mode'])==strtolower('online')){
         
        $total_amnt=0;
        foreach($dbf->fetchOrder("orders","order_id='$cancel_order_id'","","price,qty","") as $CancelAmnt){
        $total_amnt+=$CancelAmnt['qty']*$CancelAmnt['price'];
        }

        $amount= ($total_amnt+$Ordder_pay['shipping_charge'])-$Ordder_pay['coupon_amnt'];
        $hist_amnt= $amount+$User_Wallet['wallet'];
        $dbf->updateTable("user","wallet='$hist_amnt'","id='$Ordder_pay[user_id]'");
        $string = "amount='$amount',remark='Cancel Order',user_id='$id',date=NOW()";
        $ins_id=$dbf->insertSet("wallet_histru",$string);


        $User=$dbf->fetchSingle("user",'full_name,contact_no',"id='$id'");
        $apikey = "VsllEZjMRkKztBpUIKVmEA";
        $apisender = "DToDor";
        $msg ="Dear $User[full_name], Your order $cancel_order_id  was cancelled as per your request. The amount will be added in your wallet for your next purchase. Kindly leave your valuable feedback.";
        $num = $User['contact_no']; 
        $ms = rawurlencode($msg); 
        $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=0&number='.$num.'&text='.$ms.'&route=1';
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
        $data = curl_exec($ch);
    }else{
      $User=$dbf->fetchSingle("user",'full_name,contact_no',"id='$id'");
      $apikey = "VsllEZjMRkKztBpUIKVmEA";
      $apisender = "DToDor";
      $msg ="Dear $User[full_name], Your order $cancel_order_id  was cancelled as per your request. The amount will be added in your wallet for your next purchase. Kindly leave your valuable feedback.";
      $num = $User['contact_no']; 
      $ms = rawurlencode($msg); 
      $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=0&number='.$num.'&text='.$ms.'&route=1';
      $ch=curl_init($url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch,CURLOPT_POST,1);
      curl_setopt($ch,CURLOPT_POSTFIELDS,"");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
      $data = curl_exec($ch);
    }

  echo '{"success":"true","msg":"Your Order Canceled Successfully."}';exit;
}else{
  echo '{"success":"false","msg":"Your Are Blocked."}';exit;
}
}
//Cancel Order 


//Time Slot
if(isset($_REQUEST['method']) && $_REQUEST['method']=="getTimeSlot"){
   $id=$dbf->checkXssSqlInjection($_POST['id']);
   $date=date('Y-m-d',strtotime($dbf->checkXssSqlInjection($_POST['date'])));
  $dates= date('Y-m-d');
  $date1=date_create($date);
  $date2=date_create($dates);
  $diff=date_diff($date1,$date2);
  $dateDif = $diff->format("%R%a");
  $date = $_POST['date'];
  if(UserStatus($id)){
  $Array_of_time=array();
	if($dateDif<0){
    array_push($Array_of_time,array('times'=>'7-9'));
    array_push($Array_of_time,array('times'=>'9-11'));
    array_push($Array_of_time,array('times'=>'11-13'));
    array_push($Array_of_time,array('times'=>'13-15'));
    array_push($Array_of_time,array('times'=>'15-17'));
    array_push($Array_of_time,array('times'=>'17-18'));
     }else{
       
      if($dateDif==0){
       
        array_push($Array_of_time,array('times'=>'7-9'));
        array_push($Array_of_time,array('times'=>'9-11'));
        array_push($Array_of_time,array('times'=>'11-13'));
        array_push($Array_of_time,array('times'=>'13-15'));
        array_push($Array_of_time,array('times'=>'15-17'));
        array_push($Array_of_time,array('times'=>'17-18'));
     
      $curTime = date('H:i');
    
   
       if(strtotime($curTime)>=strtotime('6:59')){
        array_splice($Array_of_time,0,1);
       }
     if(strtotime($curTime)>=strtotime('8:59')){
      array_splice($Array_of_time,0,2);
     }
     if(strtotime($curTime)>=strtotime('10:59')){
      array_splice($Array_of_time,0,3);
     }
     if(strtotime($curTime)>=strtotime('12:59')){
      array_splice($Array_of_time,0,4);
     }
     if(strtotime($curTime)>=strtotime('14:59')){
      array_splice($Array_of_time,0,5);
     }
     if( strtotime($curTime)>=strtotime('16:59')){
      array_splice($Array_of_time,0,6);
     }
  }else{
    array_push($Array_of_time);
  }
      
  }

  echo '{"success":"true","Your_time_slot":'.json_encode($Array_of_time).'}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Time Slot


// Get Category Wise Products
if(isset($_REQUEST['method']) && $_REQUEST['method']=="CateWiseProd"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $shop_id=$dbf->checkXssSqlInjection($_POST['shop_id']);
    $cate_id=$dbf->checkXssSqlInjection($_POST['cate_id']);
  $subcate_id=$dbf->checkXssSqlInjection($_POST['subcate_id']);
  $sort=$dbf->checkXssSqlInjection($_POST['sort']);

  switch($sort){
    case 'nameAsc':
      $orderBy="product_name ASC";
    break;
    case 'nameDesc':
      $orderBy="product_name DESC";
    break;
  default:
  $orderBy="product_id DESC";
  }
  
  
  if(UserStatus($id)){
    
$ArrysubCate1=[];
if($subcate_id!=''){
foreach($dbf->fetchOrder("pro_rel_cat3","catagory3_id IN ($subcate_id)","","product_id","") as $valsubCate1){
array_push($ArrysubCate1,$valsubCate1['product_id']);
}
}else{
 foreach($dbf->fetchOrder("pro_rel_cat1","catagory1_id IN ($cate_id)","","product_id","") as $valsubCate1){
array_push($ArrysubCate1,$valsubCate1['product_id']);
}   
}
$Product_array=array();
// if(!empty($ArrysubCate1)){
  $Subcategory1 = implode(',', $ArrysubCate1);
  if($cate_id){
    $condi = " product_id IN ($Subcategory1)  AND status='1' AND find_in_set('$shop_id',vendor_id)";
  }else{
    $condi= " status='1' AND find_in_set('$shop_id',vendor_id)";
  }
  $All_Produts=$dbf->fetchOrder("product",$condi,$orderBy,"","product_id");
if(!empty($All_Produts)){
  foreach($All_Produts as $product){
    $Price_of_Array=array();
      $FirstVari_price=$dbf->fetchSingle("variations_values",'*',"product_id='$product[product_id]' AND vendor_id='$shop_id'");
    $Vari_price=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$FirstVari_price[price_variation_id]'");
    $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price[measure_id]'");
    $Brand = $dbf->fetchSingle("brands",'brands_name',"brands_id='$product[brands_id]' AND status='1'");
    $primary_img=$server_url_link."images/product/".$product['primary_image'];

    $All_Variotions=$dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id='$shop_id'","","","");
    if(!empty($All_Variotions)){
      foreach($All_Variotions as $varition){
        $Vari_price_single=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$varition[price_variation_id]'");

        $Measure_Single=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price_single[measure_id]'");
        array_push($Price_of_Array,array("price_varition_id"=>$varition['variations_values_id'],"mrp_price"=>$varition['mrp_price'],"sale_price"=>$varition['sale_price'],"weight"=> $Vari_price_single['units'],"unit"=>$Measure_Single['unit_name']));
      }  
    }

  array_push($Product_array,array("product_id"=>$product['product_id'],"product_name"=>$product['product_name'],"description"=>$product['description'],"brands_id"=>$product['brands_id'],
  "brand_name"=>$Brand['brands_name'],"img"=>$primary_img,"price_varition_id"=>$FirstVari_price['variations_values_id'],"mrp_price"=>$FirstVari_price['mrp_price'],"sale_price"=>$FirstVari_price['sale_price'],"weight"=>$Vari_price['units'],"unit"=>$Measure['unit_name'],"Oth_price_vari"=>$Price_of_Array));
  }
  echo '{"success":"true","all_product":'.json_encode($Product_array).'}';exit;
  }else{
    echo '{"success":"true","all_product":'.json_encode($Product_array).'}';exit;
  }
// }else{
// echo '{"success":"true","all_product":'.json_encode($Product_array).'}';exit;
// }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}

// Get Category Wise Products

//All Latest Products
if(isset($_REQUEST['method']) && $_REQUEST['method']=="AllLatestProd"){
  $pin = $dbf->checkXssSqlInjection($_POST['pin']);
  $id = $dbf->checkXssSqlInjection($_POST['id']);

  
  if(UserStatus($id)){
$all_shop=$dbf->fetchOrder("user","user_type='3' AND pin='$pin'","id ASC","","");
$Arr_prod_id=array();
$Array_produts=array();
if(!empty($all_shop)){
foreach ($all_shop as $shop) {
  foreach($dbf->fetchOrder("product","find_in_set('$shop[id]',vendor_id)","product_id ASC","","") as $product){
   array_push($Arr_prod_id, $product['product_id']);
  }
  $prod_id = implode(',', $Arr_prod_id);
  $All_Produts = $dbf->fetchOrder("product","status='1' AND product_id IN($prod_id)","product_id DESC","","");
  if(!empty($All_Produts)){
    foreach($All_Produts as $product){
      $Brand = $dbf->fetchSingle("brands",'brands_name',"brands_id='$product[brands_id]' AND status='1'");
      $primary_img=$server_url_link."images/product/".$product['primary_image'];
    array_push($Array_produts,array("product_id"=>$product['product_id'],"product_name"=>$product['product_name'],"description"=>$product['description'],"brands_id"=>$product['brands_id'],
    "brand_name"=>$Brand['brands_name'],"img"=>$primary_img));
    }
  }else{
    $Array_produts=$Array_produts;
  }
 }
}else{
$Array_produts=[];
}
echo '{"success":"true","All_latest_prod":'.json_encode($Array_produts).'}';exit;
}else{
  echo '{"success":"false","msg":"Your Are Blocked."}';exit;
}
}
//All Latest Products



else{
    header('location:http://grogod.com');
}
?>