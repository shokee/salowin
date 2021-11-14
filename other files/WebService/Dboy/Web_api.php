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
$server_url_link="http://grogod.com/admin/";

//Check Active User

function UserStatus($arg){
  $dbf=new User();
   $cntUserStatus=$dbf->countRows("user","id='$arg' AND status='1' AND user_type='5'","");
  if($cntUserStatus!=0){
    return true;
  }else{
    return false;
  }
}

	//To send Firebase notification to android device==================================
  function sendPushNotification($to = '', $data1=array(), $data2=array()) {
		
    //$apiKey = "AAAAvcd967M:APA91bEdaIHwaPNCxu5ihUnw-YS_ePKfZAP9q-Ai-frcYeWeDZpze4nuhDXJ1g7aKv2CNqdOIPIUFtGH8QXXXevHFd2OX3y7dy6gtthA_YF-8iHQnTlAgHQcgPuCNKB5_nQkUw9gA2vj"; //Place Server Legacy Key insted of api key
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
//Check Active User


//New Deliveriboy
function GetDeliVeriBoy($lat,$lng,$order_id){

  $dbf=new User();
  //Get All Assign Status Active
$Active_Delivery_Boy=$dbf->fetchOrder("orders","status IN(2,3)","","d_boy_id","");

$Rejected_Boy=$dbf->fetchSingle("orders",'rejected_dboy',"order_id='$order_id'");
if(!empty($Active_Delivery_Boy)){
$Array_of_dboy=array();
foreach($Active_Delivery_Boy as $Act_boy){
  if($Act_boy['d_boy_id']!=''){
  array_push($Array_of_dboy,$Act_boy['d_boy_id']);
  }
}
if(!empty($Array_of_dboy)){
  $dboy_id = implode(',',$Array_of_dboy);
  $Condi=" AND id NOT IN($dboy_id) AND id NOT IN($Rejected_Boy[rejected_dboy])";
}else{
  $Condi=" AND id NOT IN($Rejected_Boy[rejected_dboy])";
}

}else{
$Condi=" AND id NOT IN($Rejected_Boy[rejected_dboy])";
}

$Dboy=$dbf->fetchOrder("user","status='1' AND user_type='5' AND online='1' $Condi","distance LIMIT 0,5","id,fcm_id,(6371  * acos(cos(radians($lat)) * cos(radians(lat)) * cos(radians(lng) - radians($lng)) + sin(radians($lat)) * sin( radians(lat)))) as distance","");
   
    $Curtime = date('Y-m-d H:i:s');

    
    //Check Delivery Boy Availabel Or Not
    if(!empty($Dboy)){
        $Arr_deboy_id=array();
      foreach($Dboy as $SingleDboy){
        array_push($Arr_deboy_id,$SingleDboy['id']);
      }
      $Dboyids=implode(',',$Arr_deboy_id);
      $Arr_deboy_fcm_id=array();
      foreach($Dboy as $SingleDboy){
        array_push($Arr_deboy_fcm_id,$SingleDboy['fcm_id']);
      }

      //Send Push Notification
        
          
        
            $to=implode(',',$Arr_deboy_fcm_id);
          $data1=array(''=>"");
          $data2=array("notification_type"=>"new_order","pay_load"=>'{ "total_amnt": "300","lat":"'.$lat.'", "lng": "'.$lng.'","order_id": "'.$order_id.'","sho_adress": "'.$Shop['address1'].'","shop_name":"'.$Shop['shop_name'].'"}');
                    
          sendPushNotification($to,$data1,$data2);
          //Send Push Notification
             //Update Sending Information
             $dbf->updateTable("order_sending","dboy_id='$Dboyids',cur_time='$Curtime'","order_id='$order_id'");
             //Update Sending Information

             return true;
        }else{
          $dbf->deleteFromTable("order_sending","order_id='$order_id'");
            return false;
          }          

}
//New Deliveriboy




//Sign In
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Signin"){
     $usr_name=$dbf->checkXssSqlInjection($_POST['usr_name']);
    $password=base64_encode(base64_encode($_POST['password']));
    $fcm_id=$_POST['fcm_id'];
    $cntPhone=$dbf->countRows("user","contact_no='$usr_name'  AND user_type='5'","");
    $cntEmail=$dbf->countRows("user","email='$usr_name' AND user_type='5'","");
    $username=$dbf->countRows("user","user_name='$usr_name' AND user_type='5'","");
    if($cntPhone!=0){
        $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"contact_no='$usr_name' AND password='$password' AND user_type='5'");
        if(!empty($ChkUser)){
          
          $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
          if($userStatus!=0){
            if($ChkUser['profile_image']!=''){
                $img=$server_url_link."images/dboy/thumb/".$ChkUser['profile_image'];
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
        $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"email='$usr_name' AND password='$password' AND user_type='5'");
        if(!empty($ChkUser)){
          $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
          if($userStatus!=0){
            if($ChkUser['profile_image']!=''){
                $img=$server_url_link."images/dboy/thumb/".$ChkUser['profile_image'];
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
    }else if($username!=0){
        $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"user_name='$usr_name' AND password='$password' AND user_type='5'");
        if(!empty($ChkUser)){
          $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
          if($userStatus!=0){
            if($ChkUser['profile_image']!=''){
                $img=$server_url_link."images/dboy/thumb/".$ChkUser['profile_image'];
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
 
   $cntPhone=$dbf->countRows("user","contact_no='$usr_name'  AND user_type='5'","");
   $cntEmail=$dbf->countRows("user","email='$usr_name' AND user_type='5'","");
   $username=$dbf->countRows("user","user_name='$usr_name' AND user_type='5'","");
   $otp=rand(1000,9999);
   if($cntPhone!=0){
     $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"contact_no='$usr_name' AND user_type='5'");
       
       $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
       if($userStatus!=0){
         $dbf->updateTable("user","otp='$otp'","id='$ChkUser[id]'");
         echo '{"success":"true","id":"'.$ChkUser['id'].'","otp":"'.$otp.'"}';exit;
       }else{
         echo '{"success":"false","msg":"Your Blocked."}';exit;
        }
      
 }else if($cntEmail!=0){
     $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"email='$usr_name' AND user_type='5'");
 
       $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
       if($userStatus!=0){
         $dbf->updateTable("user","otp='$otp'","id='$ChkUser[id]'");
         echo '{"success":"true","id":"'.$ChkUser['id'].'","otp":"'.$otp.'"}';exit;
       }else{
         echo '{"success":"false","msg":"Your Blocked."}';exit;
        }
    
 }else if($cntEmail!=0){
     $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"user_name='$usr_name'  AND user_type='5'");
     
       $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
       if($userStatus!=0){
         $dbf->updateTable("user","otp='$otp'","id='$ChkUser[id]'");
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


  //Profile
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Profile"){
    $usr_id=$dbf->checkXssSqlInjection($_POST['id']);
    $ChkUser=$dbf->fetchSingle("user",'*',"id='$usr_id'");
    if(!empty($ChkUser)){
      $userStatus=$dbf->countRows("user","status='1' AND id='$usr_id'","");
      if($userStatus!=0){
        if($ChkUser['profile_image']!=''){
            $img=$server_url_link."images/dboy/thumb/".$ChkUser['profile_image'];
        }else{
            $img="";
        }

        //All Location 
    $Array_of_loc= array();
    $Country=$dbf->fetchOrder("country","","country_name","country_id,country_name","");
    if(!empty($Country)){
      foreach($Country as $contry){
        $States=$dbf->fetchOrder("state","Country_id='$contry[country_id]'","state_name","state_id,state_name","");
        $Array_of_state = array();
        if(!empty($States)){
          foreach($States as $state){
            $Array_of_city=array();
            $Cities=$dbf->fetchOrder("city","state_id='$state[state_id]'","city_name","city_id,city_name","");
            if(!empty($Cities)){
              foreach($Cities as $city){
              array_push($Array_of_city,array('city_id'=>$city['city_id'],'city_name'=>$city['city_name']));
              }
              array_push($Array_of_state,array('state_id'=>$state['state_id'],'state_name'=>$state['state_name'],"all_cities"=>$Array_of_city));
            }
           
          }
          array_push($Array_of_loc,array('country_id'=>$contry['country_id'],"country_name"=>$contry['country_name'],"all_states"=>$Array_of_state));
        }
      
      }
    }

    //All Location
        $Country=$dbf->fetchSingle("country",'country_name',"country_id='$ChkUser[country_id]'");
        $State=$dbf->fetchSingle("state",'state_name',"state_id='$ChkUser[state_id]'");
        $City=$dbf->fetchSingle("city",'city_name',"city_id='$ChkUser[city_id]'");
        echo '{"success":"true","id":"'.$ChkUser['id'].'","name":"'.$ChkUser['full_name'].'","email":"'.$ChkUser['email'].'","img":"'.$img.'",
        "num":"'.$ChkUser['contact_no'].'","alt_num":"'.$ChkUser['alter_cnum'].'","country_id":"'.$ChkUser['country_id'].'","country_name":"'.$Country['country_name'].'",
        "state_id":"'.$ChkUser['state_id'].'","state_name":"'.$State['state_name'].'","city_id":"'.$ChkUser['city_id'].'","city_name":"'.$City['city_name'].'","all_loc":'.json_encode($Array_of_loc).'}';exit;
      }else{
        echo '{"success":"false","msg":"Your Blocked."}';exit;
       }
     }else{
      echo '{"success":"false","msg":"Invalid User."}';exit;
    }

    
}
//Profile


//Update Profile
if(isset($_REQUEST['method']) && $_REQUEST['method']=="UpdateProfile"){
    $id=$dbf->checkXssSqlInjection($_POST['id']);
    if(UserStatus($id)){
      $name=$dbf->checkXssSqlInjection($_POST['name']);
      $email=$dbf->checkXssSqlInjection($_POST['email']);
      $num=$dbf->checkXssSqlInjection($_POST['num']);
      $alt_num=$dbf->checkXssSqlInjection($_POST['alt_num']);
      $country_id=$dbf->checkXssSqlInjection($_POST['country_id']);
      $state_id=$dbf->checkXssSqlInjection($_POST['state_id']);
      $city_id=$dbf->checkXssSqlInjection($_POST['city_id']);
      $img=$_POST['img'];
  
    $cntEmail=$dbf->countRows("user","email='$email' AND id!='$id'","");
    $cntPhone=$dbf->countRows("user","contact_no='$num' AND id!='$id'","");
    $cntUser=$dbf->countRows("user","user_name='$username' AND id!='$id'","");
    if($cntEmail!=0){
      echo '{"success":"false","msg":"Email ID Already Exist."}';exit;
    }else if($cntPhone!=0){
      echo '{"success":"false","msg":"Phone Number Already Exist."}';exit;
    }else{
      $Vendor_imgs=$dbf->fetchSingle("user",'profile_image',"id='$id'");
  
  
      $upload_path="../../admin/images/dboy/";
      $upload_path1="../../admin/images/dboy/thumb/";
      if($img!=''){
        $user_img = "Dboyprofile".date('Ymdhis'.$id.'.')."png";
      
        @unlink("../../admin/images/dboy/".$Vendor_imgs['profile_image']);
        @unlink("../../admin/images/dboy/thumb/".$Vendor_imgs['profile_image']);
        
       file_put_contents($upload_path.$user_img,base64_decode($img));
       file_put_contents($upload_path1.$user_img,base64_decode($img));
       $banber_img = "profile_image = '$user_img' ,";
       $string="full_name='$name',email='$email',contact_no='$num',profile_image='$user_img',alter_cnum='$alt_num',country_id='$country_id',state_id='$state_id',city_id='$city_id',created_date=NOW()";
      }
      else{
        $string="full_name='$name',email='$email',contact_no='$num',alter_cnum='$alt_num',country_id='$country_id',state_id='$state_id',city_id='$city_id',created_date=NOW()";
      }
      $dbf->updateTable("user",$string,"id='$id'");
      echo '{"success":"true","msg":"Profile Updated Successfully.."}';exit;
    }
    }else{
      echo '{"success":"false","msg":"Your Are Blocked."}';exit;
    }
  }
//Update Profile



//Order History
if(isset($_REQUEST['method']) && $_REQUEST['method']=="OrderHisory"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
    $Orderd_All = $dbf->fetchOrder("orders","d_boy_id='$id'","created_date DESC","","order_id");
    if(!empty($Orderd_All)){
      $List_Order_array=array();
     
     
    foreach($Orderd_All as $Order){
      
      // $addres=$dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");
      $address = $dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");   
      $city = $dbf->fetchSingle("city",'*',"city_id='$address[city_id]'"); 
      $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$address[pincode]'");

      $address=array("name"=>$address['first_name'].' '.$address['last_name'],"contact"=>$address['number'],"email"=>$address['email'],
      "address"=>$address['address'],"city"=>$city['city_name'],"pin"=>$pincode['pincode']);
    switch($Order['status']){
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
          $status="Returned Cancel";
        break;
        case 8:
          $status="Cancel By Customer";
        break;
        case 10:
          $status="Delivery Boy Assigned";
        default:
        $status="Cancel By Vendor";

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

//Order History


//Check Online Status
if(isset($_REQUEST['method']) && $_REQUEST['method']=="OnlineChecker"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $status=$dbf->checkXssSqlInjection($_POST['status']);
  if(UserStatus($id)){
    $dbf->updateTable("user","online='$status'","id='$id'");
    echo '{"success":"true","msg":"Status Updated Successfully."}';exit;
  }else{
    if($status==0){
      $dbf->updateTable("user","online='$status'","id='$id'");
    }
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
  
}
//Check Online Status


//Delivery Boy  Accepts Process 
if(isset($_REQUEST['method']) && $_REQUEST['method']=="DeliverBoyAccept"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $order_id=$dbf->checkXssSqlInjection($_POST['order_id']);
  $Status=$dbf->checkXssSqlInjection($_POST['Status']);
  $shoplat=$dbf->checkXssSqlInjection($_POST['shoplat']);
  $shoplng=$dbf->checkXssSqlInjection($_POST['shoplng']);
  $Cur_date= date('Y-m-d H:i:s');

  if(UserStatus($id)){
    $OrderSending=$dbf->fetchSingle("order_sending",'*',"order_id='$order_id'");
  
    if(!empty($OrderSending)){
        $Check_Dboy=$dbf->fetchSingle("order_sending",'*',"order_id='$order_id' AND dboy_id IN($id)");
     
        if(!empty($Check_Dboy)){
            //Dboy Time Process is 1 min
            $Dboy_total_accept_time=(strtotime($Cur_date) - strtotime($Check_Dboy['cur_time'])) / 60;
              if($Dboy_total_accept_time<1){
                
                  if($Status==1){
                    $dbf->updateTable("orders","d_boy_id='$id'","order_id='$order_id'");
                    $dbf->deleteFromTable("order_sending","order_id='$order_id'");
                    echo '{"success":"true","msg":"Request Accepted Successfully."}';exit;
                  }else{
                      //Order Reject
                $Order_Reject=$dbf->fetchSingle("orders",'rejected_dboy',"order_id='$order_id'");
                    
                if($Order_Reject['rejected_dboy']!=''){
                $Reject_list=$Order_Reject['rejected_dboy'].",".$Check_Dboy['dboy_id'];
                }else{
                  $Reject_list=$Check_Dboy['dboy_id'];
                }
                $dbf->updateTable("orders","rejected_dboy='$Reject_list'","order_id='$order_id'");
                $dbf->updateTable("order_sending","dboy_id=''","order_id='$order_id'");
               
                if(GetDeliVeriBoy($shoplat,$shoplng,$order_id)){   
                  echo '{"success":"true","msg":"New Request Sending....."}';exit;
                  }else{
                    $dbf->updateTable("orders","status='10'","order_id='$order_id'");
                    $dbf->deleteFromTable("order_sending","order_id='$order_id'");
                    echo '{"success":"true","msg":"Delivery Boy Not Found!!!"}';exit;
                  }
                  }
              }else{
                //Order Reject
                $Order_Reject=$dbf->fetchSingle("orders",'rejected_dboy',"order_id='$order_id'");
               
                if($Order_Reject['rejected_dboy']!=''){
                
                $Reject_list=$Order_Reject['rejected_dboy'].",".$Check_Dboy['dboy_id'];
                }else{
                
                  $Reject_list=$Check_Dboy['dboy_id'];
                }
            
                $dbf->updateTable("orders","rejected_dboy='$Reject_list'","order_id='$order_id'");
                $dbf->updateTable("order_sending","dboy_id=''","order_id='$order_id'");
                 //Order Reject
               
                  if(GetDeliVeriBoy($shoplat,$shoplng,$order_id)){   
                    echo '{"success":"true","msg":"New Request Sending....."}';exit;
                  }else{
                    $dbf->updateTable("orders","status='10'","order_id='$order_id'");
                    $dbf->deleteFromTable("order_sending","order_id='$order_id'");
                    echo '{"success":"true","msg":"Delivery Boy Not Found!!!"}';exit;
                  }
        
                echo '{"success":"true","msg":"It\'s Too Late ,to accept request."}';exit;
              }
        }else{
          echo '{"success":"true","msg":"It\'s Too Late ,to accept request."}';exit;
        }
  }else{
    echo '{"success":"true","msg":"Order Is Auto Canceled."}';exit;
  }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Delivery Boy Order Delivery Process 



//Order Ongoing
if(isset($_REQUEST['method']) && $_REQUEST['method']=="OrderOngoing"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);

  if(UserStatus($id)){
    $Orderd_All = $dbf->fetchOrder("orders","d_boy_id='$id' AND status IN(2,3)","created_date DESC","","order_id");
    if(!empty($Orderd_All)){
      $List_Order_array=array();
     
     
    foreach($Orderd_All as $Order){
      
      $Shopaddres=$dbf->fetchSingle("user",'shop_name,contact_no,email,address1,city_id,pin,lat,lng',"id='$Order[vendor_id]'");
      $city = $dbf->fetchSingle("city",'city_name',"city_id='$Shopaddres[city_id]'"); 
      $pincode=$dbf->fetchSingle("pincode",'pincode',"pincode_id='$Shopaddres[pin]'");

      $address=array("name"=>$Order['fname'].' '.$Order['lname'],"contact"=>$Order['num'],"email"=>$Order['email'],
      "address"=>$Order['adress'],"city"=>$Order['city'],"pin"=>$Order['pin'],"lat"=>$Order['lat'],"lng"=>$Order['lng']);

      $shopaddress=array("name"=>$Shopaddres['shop_name'],"contact"=>$Shopaddres['contact_no'],"email"=>$Shopaddres['email'],
      "address"=>$Shopaddres['address1'],"city"=>$city['city_name'],"pin"=>$pincode['pincode'],"lat"=>$Shopaddres['lat'],"lng"=>$Shopaddres['lng']);
    switch($Order['status']){
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
          $status="On The Way";
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
          $status="Returned Cancel";
        break;
        case 8:
          $status="Cancel By Customer";
        break;
        case 10:
          $status="Delivery Boy Assigned";
        default:
        $status="Cancel By Vendor";

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
    "shipping_char"=>$Order['shipping_charge'],"wallet"=>'-'.$Order['wallet'],"grand_total"=>"$grand_total","coupon_code"=>$Ccode,"coupon_amnt"=>"$CAmnt","user_address"=>$address,"shopaddress"=>$shopaddress));
    }
    echo '{"success":"true","all_order":'.json_encode($List_Order_array).'}';exit;
  }else{
    echo '{"success":"false","msg":"No Order Availabel Yet."}';exit;
  } 
  }else{
      echo '{"success":"false","msg":"Your Are Blocked."}';exit;
    }

}

//Order Ongoing
//Pick Api
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Picked"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $order_id=$dbf->checkXssSqlInjection($_POST['order_id']);
  if(UserStatus($id)){
    $dbf->updateTable("orders","status='3'","order_id='$order_id'");
    //SMS Gateway
    $Ordder=$dbf->fetchSingle("orders",'user_id',"order_id='$order_id'");
    $User=$dbf->fetchSingle("user",'full_name,contact_no,fcm_id',"id='$Ordder[user_id]'");
   $Dboy=$dbf->fetchSingle("user",'full_name,contact_no',",user_id='$id'");
    $apikey = "VsllEZjMRkKztBpUIKVmEA";
    $apisender = "DToDor";
    $msg ="Dear $User[full_name], Your order has been picked by our executive Mr.$Dboy[full_name] - $Dboy[contact_no] and he is on the way to reach you shortly in 30 minutes";
    $num = $User['contact_no']; 
    $ms = rawurlencode($msg); 
    $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=1&number='.$num.'&text='.$ms.'&route=1';
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,"");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
    $data = curl_exec($ch);
    //SMS Gateway
    $to=$User['fcm_id'];
  	$data1=array(
      'title'=>"OwnMyStore",
      'body'=>"Order Out For Delivery,#$order_id"
    );
    $data2=array(''=>'');
    sendPushNotification($to,$data1,$data2); 

    echo '{"success":"true","msg":"Pickeup Successfully.","order_id":"'.$order_id.'"}';exit;
  }else{
      echo '{"success":"false","msg":"Your Are Blocked."}';exit;
    }

}
//Pick Api

//Delivered Api
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Delivered"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $order_id=$dbf->checkXssSqlInjection($_POST['order_id']);
  if(UserStatus($id)){
    $dbf->updateTable("orders","status='4'","order_id='$order_id'");

       //SMS Gateway
       $Ordder=$dbf->fetchSingle("orders",'user_id,vendor_id',"order_id='$order_id'");
       $User=$dbf->fetchSingle("user",'full_name,contact_no,fcm_id',"id='$Ordder[user_id]'");
      $Dboy=$dbf->fetchSingle("user",'full_name,contact_no',"user_id='$id'");
       $apikey = "VsllEZjMRkKztBpUIKVmEA";
       $apisender = "DToDor";
       $msg ="Dear $User[full_name], Your order has been delivered by our executive Mr.$Dboy[full_name] - $Dboy[contact_no] and kindly leave your valuable feedback for our growth and support";
       $num = $User['contact_no']; 
       $ms = rawurlencode($msg); 
       $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=1&number='.$num.'&text='.$ms.'&route=1';
       $ch=curl_init($url);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch,CURLOPT_POST,1);
       curl_setopt($ch,CURLOPT_POSTFIELDS,"");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
       $data = curl_exec($ch);
       //SMS Gateway
       $Arr_deboy_fcm_id = array();
       $Shop=$dbf->fetchSingle("user",'shop_name,fcm_id',"id='$Ordder[vendor_id]'");
        if($User['fcm_id']!=''){
          array_push($Arr_deboy_fcm_id,$User['fcm_id']);
        }
        if($Shop['fcm_id']!=''){
          array_push($Arr_deboy_fcm_id,$Shop['fcm_id']);
        }
       $to==implode(',',$Arr_deboy_fcm_id);
       $data1=array(
         'title'=>"OwnMyStore",
         'body'=>"Order Delivered Successfully,#$order_id"
       );
       $data2=array(''=>'');
       sendPushNotification($to,$data1,$data2); 
    echo '{"success":"true","msg":"Delivered Successfully.","order_id":"'.$order_id.'"}';exit;
  }else{
      echo '{"success":"false","msg":"Your Are Blocked."}';exit;
    }

}
//Delivered Api






