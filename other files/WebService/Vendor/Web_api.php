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
   $cntUserStatus=$dbf->countRows("user","id='$arg' AND status='1' AND user_type='3'","");
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
  

//Sign In
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Signin"){
  $usr_name=$dbf->checkXssSqlInjection($_POST['usr_name']);
   $password=base64_encode(base64_encode($_POST['password']));
   $fcm_id = $_POST['fcm_id'];
  $cntPhone=$dbf->countRows("user","contact_no='$usr_name'  AND user_type='3'","");
  $cntEmail=$dbf->countRows("user","email='$usr_name' AND user_type='3'","");
  $username=$dbf->countRows("user","user_name='$usr_name' AND user_type='3'","");
  if($cntPhone!=0){
      $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"contact_no='$usr_name' AND password='$password' AND user_type='3'");
      if(!empty($ChkUser)){
        
        $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
        if($userStatus!=0){
          if($ChkUser['profile_image']!=''){
              $img=$server_url_link."images/vendor/thumb/".$ChkUser['profile_image'];
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
      $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"email='$usr_name' AND password='$password' AND user_type='3'");
      if(!empty($ChkUser)){
        $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
        if($userStatus!=0){
          if($ChkUser['profile_image']!=''){
              $img=$server_url_link."images/vendor/thumb/".$ChkUser['profile_image'];
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
      $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,profile_image',"user_name='$usr_name' AND password='$password' AND user_type='3'");
      if(!empty($ChkUser)){
        $userStatus=$dbf->countRows("user","status='1' AND id='$ChkUser[id]'","");
        if($userStatus!=0){
          if($ChkUser['profile_image']!=''){
              $img=$server_url_link."images/vendor/thumb/".$ChkUser['profile_image'];
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


//Products
if(isset($_REQUEST['method']) && $_REQUEST['method']=="YourProducts"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
    $Array_of_Product=array();
    $list_cate_array=array();
    foreach($dbf->fetchOrder("vendor_catagory","vendor_id='$id'","","","") as $cate_id){
     array_push($list_cate_array,$cate_id['catagory_id']);
    }
    if(!empty($list_cate_array)){
      $list_cate=implode(',',$list_cate_array);
      $list_prod_array=array();
      foreach($dbf->fetchOrder("pro_rel_cat1","catagory1_id IN($list_cate)","","","") as $product_id){
       array_push($list_prod_array,$product_id['product_id']);
      }

      if(!empty($list_prod_array)){
        $prod_id = implode(',',$list_prod_array);
        $All_Products=$dbf->fetchOrder("product","product_id IN($prod_id)","product_id ASC","","");
        if(!empty($All_Products)){
          
           foreach($All_Products as $product){

            $vendor_id_array=explode(',',$product['vendor_id']);
            if(in_array($id,$vendor_id_array)){
              $status = "Active";
              $status_id ="1";
            }else{
              $status = "Block";
              $status_id ="0";
            }
             
             //Variations
             $All_Variations=$dbf->fetchOrder("variations_values","vendor_id='$id' AND product_id='$product[product_id]'","variations_values_id DESC ","variations_values_id","");
              $Cnt_Varition = count($All_Variations);
             //Variations  

            $Brand = $dbf->fetchSingle("brands",'brands_name',"brands_id='$product[brands_id]' AND status='1'");
            $primary_img=$server_url_link."images/product/".$product['primary_image'];
            array_push($Array_of_Product,array("product_id"=>$product['product_id'],"product_name"=>$product['product_name'],"description"=>$product['description'],"brands_id"=>$product['brands_id'],
            "brand_name"=>$Brand['brands_name'],"img"=>$primary_img,"no_of_varitions"=>"$Cnt_Varition","status"=>$status,"status_id"=>$status_id));
           }
          echo '{"success":"true","all_products":'.json_encode($Array_of_Product).'}';exit;
        }else{
          echo '{"success":"true","all_products":'.json_encode($Array_of_Product).'}';exit;
        }
      }else{
        echo '{"success":"true","all_products":'.json_encode($Array_of_Product).'}';exit;
      }

    }else{
      echo '{"success":"true","all_products":'.json_encode($Array_of_Product).'}';exit;
    }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Products


//Product Deatils
if(isset($_REQUEST['method']) && $_REQUEST['method']=="ProductDetails"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $product_id=$dbf->checkXssSqlInjection($_POST['product_id']);
  if(UserStatus($id)){
    $Product=$dbf->fetchSingle("product",'product_name,description,brands_id,primary_image,vendor_id',"product_id='$product_id'");
    $Brand = $dbf->fetchSingle("brands",'brands_name',"brands_id='$Product[brands_id]' AND status='1'");
    $Array_of_gallery = array();
    $Array_of_variations = array();
    //Gallery Img
    $Prod_Gallery=$dbf->fetchOrder("gallery","product_id='$product_id'","gallery_id ASC","","");
      if(!empty($Prod_Gallery)){
        foreach($Prod_Gallery as $gallery){
          $img = $server_url_link."images/gallery/thumb/".$gallery['image'];
          array_push($Array_of_gallery,array("img"=>$img));
        }
      }
      $primary_img=$server_url_link."images/product/".$Product['primary_image'];
      array_push($Array_of_gallery,array("img"=>$primary_img));
   //Gallery Img


    //Variations
    $All_Variations=$dbf->fetchOrder("variations_values","vendor_id='$id' AND product_id='$product_id'","variations_values_id DESC ","","");
           
    $vendor_id_array=explode(',',$Product['vendor_id']);
    if(in_array($id,$vendor_id_array)){
      $status = "Active";
      $status_id ="1";
    }else{
      $status = "Block";
      $status_id ="0";
    }

    if(!empty($All_Variations)){
   
      foreach($All_Variations as $Price_Vari){
        
      $VariPrie=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$Price_Vari[price_variation_id]'");

      $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$VariPrie[measure_id]'"); 
      array_push($Array_of_variations,array("price_varition_id"=>$Price_Vari['variations_values_id'],"Measure Ment"=>$VariPrie['units'].$Measure['unit_name'],"MRP Price"=>$Price_Vari['mrp_price'],"Sale Price"=>$Price_Vari['sale_price']));
      }
    }
 //Variations   

 //Measure Ment
  $array_of_variation_id=array();
 $All_Variatio=$dbf->fetchOrder("price_varition","product_id='$product_id'","","","");
          
if(!empty($All_Variatio)){
foreach($All_Variatio as $Variations){
   $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Variations[measure_id]'");
     array_push($array_of_variation_id,array("price_variation_id"=>$Variations['price_varition_id'],"measure_ment"=>$Variations['units'].$Measure['unit_name']));
   }

 }
//Measure Ment


   echo '{"success":"true","product_id":"'.$product_id.'","product_name":"'.$Product['product_name'].'","description":"'.$Product['description'].'",
    "brands_id":"'.$Product['brands_id'].'","brand_name":"'.$Brand['brands_name'].'","gallery_img":'.json_encode($Array_of_gallery).'
    ,"price_variation":'.json_encode($Array_of_variations).',"status":"'.$status.'","status_id":"'.$status_id.'","all_variations":'.json_encode($array_of_variation_id).'}';exit;
  }else{
    echo '{"success":"false","pro":"Your Are Blocked."}';exit;
  }

}
//Product Deatils
//Select Measurement
if(isset($_REQUEST['method']) && $_REQUEST['method']=="SelectMeasureMent"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $product_id=$dbf->checkXssSqlInjection($_POST['product_id']);
  if(UserStatus($id)){
    $array_of_variation_id=array();
    $All_Variatio=$dbf->fetchOrder("price_varition","product_id='$product_id'","","","");
  
    if(!empty($All_Variatio)){
    foreach($All_Variatio as $Variations){
      $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Variations[measure_id]'");
      array_push($array_of_variation_id,array("price_variation_id"=>$Variations['price_varition_id'],"measure_ment"=>$Variations['units'].$Measure['unit_name']));
    }
    echo '{"success":"true","all_variations":'.json_encode($array_of_variation_id).'}';exit;
  }
    echo '{"success":"true","all_variations":'.json_encode($array_of_variation_id).'}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Select Measurement

//Add Variations
if(isset($_REQUEST['method']) && $_REQUEST['method']=="AddVariations"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $price_variation_id=$dbf->checkXssSqlInjection($_POST['price_variation_id']);
  $mrpprice=$dbf->checkXssSqlInjection($_POST['mrpprice']);
  $saleprice=$dbf->checkXssSqlInjection($_POST['saleprice']);
  $product_id=$dbf->checkXssSqlInjection($_POST['product_id']);
  if(UserStatus($id)){
    $cntPrice=$dbf->countRows("variations_values","price_variation_id='$price_variation_id' AND vendor_id='$id' AND mrp_price='$mrpprice' AND sale_price='$saleprice' AND product_id='$product_id'");
	if($cntPrice==0){
    $dbf->deleteFromTable("variations_values","price_variation_id='$price_variation_id'");
    $string="price_variation_id='$price_variation_id',vendor_id='$id',mrp_price='$mrpprice',sale_price='$saleprice',product_id='$product_id'";
    $dbf->insertSet("variations_values",$string);
    echo '{"success":"true","msg":"Price Variation Addedd Successfully."}';exit;
  }else{
    echo '{"success":"false","msg":"Price Variation Already Exist."}';exit;
  }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Add Variations

//Delete Variations
if(isset($_REQUEST['method']) && $_REQUEST['method']=="DeleteVariations"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $price_vari = $dbf->checkXssSqlInjection($_POST['price_variation_id']);
  if(UserStatus($id)){
    $Varition_value=$dbf->fetchSingle("variations_values",'vendor_id,price_variation_id,product_id',"variations_values_id='$price_vari'");
   
    $dbf->deleteFromTable("variations_values","variations_values_id='$price_vari'");

    $GetAttr_id=$dbf->fetchOrder("variations_values","product_id='$Varition_value[product_id]' AND vendor_id='$Varition_value[vendor_id]'","");
           if(empty($GetAttr_id)){
           
             $prod_details=$dbf->fetchSingle("product","vendor_id","product_id = '$Varition_value[product_id]'","");
             
             $array_name=$prod_details['vendor_id'];
             if($array_name!=""){
            $vendor_id_array=explode(',',$array_name);
            }else{
            $vendor_id_array=array();
            }
         //    print_r($vendor_id_array);exit;
            
              function remove_element($array,$value) {
            return array_diff($array, (is_array($value) ? $value : array($value)));
            }
           
            $del_arry = remove_element($vendor_id_array,$Varition_value['vendor_id']);
            $vendor_id=implode(',',$del_arry);
             $dbf->updateTable("product","vendor_id='$vendor_id'", "product_id='$Varition_value[product_id]'");
             echo '{"success":"false","msg":"Product Is Blocked Now."}';exit;
           }else{
            echo '{"success":"true","msg":"Price Deleted Successfully."}';exit;
           }
 
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Delete Variations

//Change Status
if(isset($_REQUEST['method']) && $_REQUEST['method']=="ChangeStaus"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $product_id=$dbf->checkXssSqlInjection($_POST['product_id']);
  $status=$dbf->checkXssSqlInjection($_POST['status']);
  $Vendor_id=$dbf->fetchSingle("product",'vendor_id',"product_id='$product_id'");
  $vendor_id_array =explode(',',$Vendor_id['vendor_id']);

  if(UserStatus($id)){
    $CheckVarition=$dbf->fetchOrder("variations_values","vendor_id='$id' AND product_id='$product_id'","variations_values_id DESC ","","");
    if(!empty($CheckVarition)){

      if($status=='0'){

        function remove_element($array,$value) {
    return array_diff($array, (is_array($value) ? $value : array($value)));
  }
   
  $del_arry = remove_element($vendor_id_array,$id);
   $vendor_id=implode(',',$del_arry);
     $dbf->updateTable("product","vendor_id='$vendor_id'", "product_id='$product_id'");
    }else{
      array_push($vendor_id_array,$id);
      $vendor_id=implode(',',$vendor_id_array);
     $dbf->updateTable("product","vendor_id='$vendor_id'", "product_id='$product_id'");
    }
    echo '{"success":"true","msg":"Status Updated Successfully."}';exit;
    }else{
      echo '{"success":"false","msg":"Varition Not Create Yet,Please Create Variation Then Activate."}';exit;
    }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Change Status


//Orders
if(isset($_REQUEST['method']) && $_REQUEST['method']=="YourOrders"){
   $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
    $Array_of_order = array();
 
    $All_Orders=$dbf->fetchOrder("orders","vendor_id='$id'","created_date DESC","order_id,created_date,payment_mode,status,shipping_charge,address_id,fname,lname
    num,email,adress,city,pin,lat,lng","order_id");
    if(!empty($All_Orders)){
    foreach($All_Orders as $Order){
        
      $city = $dbf->fetchSingle("city",'*',"city_id='$Order[city_id]'"); 
      $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$Order[pin]'");
      // $address=array("name"=>$address['first_name'].' '.$address['last_name'],"contact"=>$address['number'],"email"=>$address['email'],
      // "address"=>$address['address'],"city"=>$city['city_name'],"pin"=>$pincode['pincode']);

      $address=array("name"=>$Order['fname'].' '.$Order['lname'],"contact"=>$Order['num'],"email"=>$Order['email'],
      "address"=>$Order['adress'],"city"=>$Order['city'],"pin"=>$Order['pin'],"lat"=>$Order['lat'],"lng"=>$Order['lng']);


      switch($Order['status']){
        case -1:
          $status="Cancel By Vendor";
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
          $status="Returned Cancel";
        break;
        case 8:
          $status="Cancel By Customer";
        break;
        case 9:
          $status="Payment Failed";
        break;
        default:
        $status="Processing";

    }

    $View_order=$dbf->fetchOrder("orders","order_id='$Order[order_id]'","orders_id DESC","","");
    $Array_of_view = array();
    $total=0;
      if(!empty($View_order)){
     
        foreach($View_order as $singleorder){
   
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

            switch($singleorder['status']){
              case -1:
                $status="Cancel By Vendor";
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
                $status="Returned Cancel";
              break;
              case 8:
                $status="Cancel By Customer";
              break;
              case 9:
                $status="Payment Failed";
              break;
              default:
              $status="Processing";
      
          }
    
          array_push($Array_of_view,array("produt_name"=>$singleorder['ordername'].$return_order,
          "img"=>$img,"price"=>$singleorder['price'],"qty"=>$singleorder['qty'],"sub_total"=>"$sub_total","product_status"=>$prodstatus));
        }
      }
      $grand_total=$total+$Order['shipping_charge'];
      $grand_total=$grand_total-$Order['wallet']-$CAmnt;
      
        array_push($Array_of_order,array('order_id'=>$Order['order_id'],"order_date"=>date('d.m.Y',strtotime($Order['created_date'])),"pay_mode"=>$Order['payment_mode'],
        "status"=>$status,"view_details"=>$Array_of_view,"total"=>"$total",
        "shipping_char"=>$Order['shipping_charge'],"wallet"=>'-'.$Order['wallet'],"grand_total"=>"$grand_total",
        "coupon_code"=>$Ccode,"coupon_amnt"=>"$CAmnt","address"=>$address));
    }
    echo '{"success":"true","All_orderd":'.json_encode($Array_of_order).'}';exit;
  } else{
    echo '{"success":"true","All_orderd":'.json_encode($Array_of_order).'}';exit;
  }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Orders



//Order Status Change
if(isset($_REQUEST['method']) && $_REQUEST['method']=="OrdersTatus"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  $order_id=$dbf->checkXssSqlInjection($_POST['order_id']);
  $status=$dbf->checkXssSqlInjection($_POST['status']);
  $Ordder_used_id=$dbf->fetchSingle("orders",'user_id',"order_id='$order_id'");
  $User_fcm=$dbf->fetchSingle("user",'fcm_id',"id='$Ordder_used_id[user_id]'");
  if(UserStatus($id)){
   $dbf->updateTable("orders","status='$status'","order_id='$order_id'");
   if($status=='-1'){
   
    $Ordder_pay=$dbf->fetchSingle("orders",'*',"order_id='$order_id'");
    $User_Wallet=$dbf->fetchSingle("user",'wallet',"id='$Ordder_pay[user_id]'");
    if(strtolower($Ordder_pay['payment_mode'])==strtolower('cod& Wallet Paid')){
        $amount= $Ordder_pay['wallet']+$User_Wallet['wallet'];
      
        $dbf->updateTable("user","wallet='$amount'","id='$Ordder_pay[user_id]'");
        $string = "amount='$Ordder_pay[wallet]',remark='Cancel Order',user_id='$Ordder_pay[user_id]',date=NOW()";

        $ins_id=$dbf->insertSet("wallet_histru",$string);
    }
    if(strtolower($Ordder_pay['payment_mode'])==strtolower('online& Wallet Paid')){
      $total_amnt=0;
      foreach($dbf->fetchOrder("orders","order_id='$order_id'","","price,qty","") as $CancelAmnt){
        $total_amnt+=$CancelAmnt['qty']*$CancelAmnt['price'];
      }
  
       $amount= ($total_amnt+$Ordder_pay['shipping_charge'])-$Ordder_pay['coupon_amnt'];
        $hist_amnt= $amount+$User_Wallet['wallet'];
        $dbf->updateTable("user","wallet='$hist_amnt'","id='$Ordder_pay[user_id]'");
        $string = "amount='$amount',remark='Cancel Order',user_id='$Ordder_pay[user_id]',date=NOW()";
        $ins_id=$dbf->insertSet("wallet_histru",$string);
    }if(strtolower($Ordder_pay['payment_mode'])==strtolower('online')){
      $total_amnt=0;
      foreach($dbf->fetchOrder("orders","order_id='$order_id'","","price,qty","") as $CancelAmnt){
        $total_amnt+=$CancelAmnt['qty']*$CancelAmnt['price'];
      }
  
         $amount= ($total_amnt+$Ordder_pay['shipping_charge'])-$Ordder_pay['coupon_amnt'];

         $hist_amnt= $amount+$User_Wallet['wallet'];
        $dbf->updateTable("user","wallet='$hist_amnt'","id='$Ordder_pay[user_id]'");
        $string = "amount='$amount',remark='Cancel Order',user_id='$Ordder_pay[user_id]',date=NOW()";
        $ins_id=$dbf->insertSet("wallet_histru",$string);
    }
    $to=$User_fcm['fcm_id'];
  	$data1=array(
      'title'=>"OwnMyStore",
      'body'=>"Order Was Canceled,#$order_id"
    );
    $data2=array(''=>'');
    sendPushNotification($to,$data1,$data2); 
    echo '{"success":"true","msg":"Order Cancel By Vendor"}';exit;

  }elseif($status=='1'){

    $Ordder_pay=$dbf->fetchSingle("orders",'user_id',"order_id='$order_id'");
    $User=$dbf->fetchSingle("user",'full_name,contact_no',"id='$Ordder_pay[user_id]'");
    $Shop=$dbf->fetchSingle("user",'shop_name',"id='$id'");
  
    $apikey = "VsllEZjMRkKztBpUIKVmEA";
    $apisender = "DToDor";
    $msg ="Dear $User[full_name], Your order $order_id is confirmed by  $Shop[shop_name].Our executive will call once the packing is ready. Kindly use your order number $order_id for  reference.";
    $num = $User['contact_no']; 
    $ms = rawurlencode($msg); 
    $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=8&flashsms=1&number='.$num.'&text='.$ms.'&route=1';
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,"");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
    $data = curl_exec($ch);

    $to=$User_fcm['fcm_id'];
  	$data1=array(
      'title'=>"OwnMyStore",
      'body'=>"Order is been processed,#$order_id"
    );
    $data2=array(''=>'');
    sendPushNotification($to,$data1,$data2); 


    echo '{"success":"true","msg":"Order Received By Vendor"}';exit;
   }else{

    $Vendor=$dbf->fetchSingle("orders",'vendor_id',"order_id='$order_id'");
    $Shop=$dbf->fetchSingle("user",'lat,lng,address1,shop_name',"id='$Vendor[vendor_id]'");

    $lat = $Shop['lat'];
    $lng = $Shop['lng'];

    //Get All Assign Status Active
$Active_Delivery_Boy=$dbf->fetchOrder("orders","status IN(2,3)","","d_boy_id","");
if(!empty($Active_Delivery_Boy)){
  $Array_of_dboy=array();
  foreach($Active_Delivery_Boy as $Act_boy){
    if($Act_boy['d_boy_id']!=''){
    array_push($Array_of_dboy,$Act_boy['d_boy_id']);
    }
  }
  if(!empty($Array_of_dboy)){
    $dboy_id = implode(',',$Array_of_dboy);
    $Condi=" AND id NOT IN($dboy_id) ";
  }else{
    $Condi="";
  }

}else{
  $Condi="";
}


//Get All Sending Requests
$ActiveRequest_Delivery_Boy=$dbf->fetchOrder("order_sending","","","dboy_id","");
if(!empty($ActiveRequest_Delivery_Boy)){
$Arry_of_reqactive=array();
foreach($ActiveRequest_Delivery_Boy as $ReqAct_boy){
  array_push($Arry_of_reqactive,$ReqAct_boy['dboy_id']);
}
if(!empty($Array_of_dboy)){
  $reqdboy_id = implode(',',$Arry_of_reqactive);
  $condi1=" AND id NOT IN($reqdboy_id) ";
}else{
  $condi1="";
}
}else{
  $condi1="";
}

$Dboy=$dbf->fetchOrder("user","status='1' AND user_type='5' AND online='1' $Condi $condi1","distance LIMIT 0,5","id,fcm_id,(6371  * acos(cos(radians($lat)) * cos(radians(lat)) * cos(radians(lng) - radians($lng)) + sin(radians($lat)) * sin( radians(lat)))) as distance","");
   
    $Curtime = date('Y-m-d H:i:s');

    // print_r($Dboy);exit;
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
          $data2=array("notification_type"=>"new_order","pay_load"=>'{ "total_amnt": "300","lat":"'.$lat.'", "lng": "'.$lng.'","order_id": "'.$order_id.'","sho_adress": "'.$Shop['address1'].'","shop_name":"'.$Shop['shop_name'].'","time":"'.$Cur_date.'"}');
          $data3=array("ttl"=>"5s");
          $data4= array("headers"=>array("TTL"=>"5"));
          sendPushNotification($to,$data1,$data2,$data3,$data4);
    //Send Push Notification


      //Insert Sending Information
      $CntOrder_id=$dbf->countRows("order_sending","order_id='$order_id'","");
      if($CntOrder_id==0){
      $dbf->insertSet("order_sending","order_id='$order_id',dboy_id='$Dboyids',cur_time='$Curtime',order_time='$Curtime'");
      }
      //Insert Sending Information
      echo '{"success":"true","msg":"Order Processing By Vendor"}';exit;
    }else{
      $dbf->updateTable("orders","status='10'","order_id='$order_id'");
      echo '{"success":"true","msg":"Delivery Boy Not Found!!!"}';exit;
    }
    echo '{"success":"true","msg":"Order Processing By Vendor"}';exit;
  }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Order Status Change



//Order Returns
if(isset($_REQUEST['method']) && $_REQUEST['method']=="OrderReturns"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
    $Array_of_return=array();
    $All_Returns=$dbf->fetchOrder("orders","status IN(5,6,7) AND vendor_id='$id'","created_date DESC","","");
    if(!empty($All_Returns)){
    foreach($All_Returns as $Order){
         
      $address = $dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");   
      $city = $dbf->fetchSingle("city",'*',"city_id='$address[city_id]'"); 
      $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$address[pincode]'");
      $address=array("name"=>$address['first_name'].' '.$address['last_name'],"contact"=>$address['number'],"email"=>$address['email'],
      "address"=>$address['address'],"city"=>$city['city_name'],"pin"=>$pincode['pincode']);


      $addres=$dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");
      $vendor=$dbf->fetchSingle("user",'*',"id='$Order[vendor_id]'");
      switch($Order['status']){
        case 6:
        $ret_status = " New Returned ";
        break;
        case 5:
          $ret_status = " Return Compeleted";
        break;
        default:
          $ret_status = " Return Canceled";
     
      }
      $View_order=$dbf->fetchOrder("orders","order_id='$Order[order_id]'","orders_id DESC","","");
    $Array_of_view = array();
    $total=0;
      if(!empty($View_order)){
     
        foreach($View_order as $singleorder){
   
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

            switch($singleorder['status']){
              case 0:
                $prodstatus="New Order";
              break;
              case 1:
                $prodstatus="Order Received";
              break;
              case 2:
                $prodstatus="Processing";
              break;
              case 3:
                $prodstatus="Shipped";
              break;
              case 4:
                $prodstatus="Completed";
              break;
              case 5:
                $prodstatus="Return Approved";
              break;
              case 6:
                $prodstatus="Returned Process";
              break;
              case 7:
                $prodstatus="Returned Cancel";
              break;
              case 8:
                $prodstatus="Cancel By Customer";
              break;
              default:
              $prodstatus="Cancel By Vendor";
      
          }
    
          array_push($Array_of_view,array("produt_name"=>$singleorder['ordername'].$return_order,
          "img"=>$img,"price"=>$singleorder['price'],"qty"=>$singleorder['qty'],"sub_total"=>"$sub_total","product_status"=>$prodstatus));
        }
      }
      $grand_total=$total+$Order['shipping_charge'];
      $grand_total=$grand_total-$Order['wallet']-$CAmnt;
      
      array_push($Array_of_return,array("order_id"=>$Order['order_id'],"order_date"=>date('d.m.Y',strtotime($Order['created_date'])),"shop_name"=>$vendor['shop_name'],"payment_mode"=>$Order['payment_mode'],"reason"=>$Order['reason'],"status"=>$ret_status,"view_details"=>$Array_of_view,
      "total"=>"$total","shipping_char"=>$Order['shipping_charge'],"wallet"=>'-'.$Order['wallet'],"grand_total"=>"$grand_total",
      "coupon_code"=>$Ccode,"coupon_amnt"=>"$CAmnt","address"=>$address));
    }
    echo '{"success":"true","all_returns":'.json_encode($Array_of_return).'}';exit;
  }else{
    echo '{"success":"true","all_returns":'.json_encode($Array_of_return).'}';exit;
  } 
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//All Location
if(isset($_REQUEST['method']) && $_REQUEST['method']=="GetAllLoc"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
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
            }
            array_push($Array_of_state,array('state_id'=>$state['state_id'],'state_name'=>$state['state_name'],"all_cities"=>$Array_of_city));
          }
        }
        array_push($Array_of_loc,array('country_id'=>$contry['country_id'],"country_name"=>$contry['country_name'],"all_states"=>$Array_of_state));
      }
      echo '{"success":"false","all_countries":'.json_encode($Array_of_loc).'}';exit;
    }else{
      echo '{"success":"false","all_loc":'.json_encode($Array_of_loc).'}';exit;
    }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//All Location

//Shop Details 
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Shop_details"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);

  if(UserStatus($id)){
    $Shop=$dbf->fetchSingle("user",'shop_name,banner_image,logo_image,address1,gst_no,country_id,state_id,city_id',"id='$id'");
    $Country=$dbf->fetchSingle("country",'country_name',"country_id='$Shop[country_id]'");
    $State=$dbf->fetchSingle("state",'state_name',"state_id='$Shop[state_id]'");
    $City=$dbf->fetchSingle("city",'city_name',"city_id='$Shop[city_id]'");
    $Category=$dbf->fetchOrder("vendor_catagory","vendor_id='$id'","","catagory_id","");
    $Array_of_vendor_cate=array();
    foreach($Category as $cate){
     
      $Vendor_cate=$dbf->fetchOrder("product_catagory_1","product_catagory_1_id='$cate[catagory_id]'","product_catagory_1_id ASC","product_catagory_1_name","");
      foreach($Vendor_cate as $V_cate){
        array_push($Array_of_vendor_cate,$V_cate['product_catagory_1_name']);
        
      }
      $cate_name=implode(',',$Array_of_vendor_cate);
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



    $Banner_img=$server_url_link."images/vendor/".$Shop['banner_image'];
    $logo=$server_url_link."images/vendor/".$Shop['logo_image'];
    echo '{"success":"true","shop_name":"'.$Shop['shop_name'].'","sale_cate":"'.$cate_name.'","banner_img":"'.$Banner_img.'","logo":"'.$logo.'",
    "address":"'.$Shop['address1'].'","gst":"'.$Shop['gst_no'].'","country_id":"'.$Shop['country_id'].'","country_name":"'.$Country['country_name'].'",
    "state_id":"'.$Shop['state_id'].'","state_name":"'.$State['state_name'].'","city_id":"'.$Shop[city_id].'","city_name":"'.$City['city_name'].'","all_countries":'.json_encode($Array_of_loc).'}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }

}
//Shop Details 


//Shop Update
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Shop_Update"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
  $shop_name=$dbf->checkXssSqlInjection($_POST['shop_name']);
  $banner_img=$_POST['banner_img'];
  $logo=$_POST['logo'];
  $address=$dbf->checkXssSqlInjection($_POST['address']);
  $gst=$dbf->checkXssSqlInjection($_POST['gst']);
  $country_id=$dbf->checkXssSqlInjection($_POST['country_id']);
  $state_id=$dbf->checkXssSqlInjection($_POST['state_id']);
  $city_id=$dbf->checkXssSqlInjection($_POST['city_id']);

  $Vendor_imgs=$dbf->fetchSingle("user",'banner_image,logo_image',"id='$id'");


$upload_path="../../admin/images/vendor/";
$upload_path1="../../admin/images/vendor/thumb/";
if($banner_img!=''){
  $user_img = $shop_name."_banner".date('Ymdhis'.$id.'.')."png";

  @unlink("../../admin/images/vendor/".$Vendor_imgs['banner_image']);
  @unlink("../../admin/images/vendor/thumb/".$Vendor_imgs['banner_image']);
  
 file_put_contents($upload_path.$user_img,base64_decode($banner_img));
 file_put_contents($upload_path1.$user_img,base64_decode($banner_img));
 $banber_img = "banner_image = '$user_img' ,";
}
else{
  $banber_img = "";
}
if($logo!=''){
  $user_img1 = $shop_name."_logo".date('Ymdhis'.$id.'.')."png";

  @unlink("../../admin/images/vendor/".$Vendor_imgs['logo_image']);
  @unlink("../../admin/images/vendor/thumb/".$Vendor_imgs['logo_image']);

 file_put_contents($upload_path.$user_img1,base64_decode($logo));
 file_put_contents($upload_path1.$user_img1,base64_decode($logo));
 $logo_img = "logo_image = '$user_img1' ,";
}else{
  $logo ="";
}

$string="shop_name ='$shop_name', address1='$address', gst_no='$gst', country_id='$country_id',$banber_img $logo_img  state_id='$state_id', city_id='$city_id',created_date=NOW()";

$dbf->updateTable("user",$string,"id='$id'");
echo '{"success":"true","msg":"Shop Updated Successfully.."}';exit;
}else{
  echo '{"success":"false","msg":"Your Are Blocked."}';exit;
}
}
//Shop Update



//Get Profile
if(isset($_REQUEST['method']) && $_REQUEST['method']=="GetProfile"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
    $Vendor=$dbf->fetchSingle("user",'full_name,email,contact_no,profile_image,user_name',"id='$id'");
    $img = $server_url_link."images/vendor/thumb/".$Vendor['profile_image'];
    echo '{"success":"true","name":"'.$Vendor['full_name'].'","email":"'.$Vendor['email'].'","number":"'.$Vendor['contact_no'].'",
    "img":"'.$img.'","username":"'.$Vendor['user_name'].'"}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }

}
//Get Profile

//Update Profile
if(isset($_REQUEST['method']) && $_REQUEST['method']=="UpdateProfile"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);
  if(UserStatus($id)){
    $name=$dbf->checkXssSqlInjection($_POST['name']);
    $email=$dbf->checkXssSqlInjection($_POST['email']);
    $num=$dbf->checkXssSqlInjection($_POST['num']);
    $username=$dbf->checkXssSqlInjection($_POST['username']);
    $img=$_POST['img'];

  $cntEmail=$dbf->countRows("user","email='$email' AND id!='$id'","");
  $cntPhone=$dbf->countRows("user","contact_no='$num' AND id!='$id'","");
  $cntUser=$dbf->countRows("user","user_name='$username' AND id!='$id'","");
  if($cntEmail!=0){
    echo '{"success":"false","msg":"Email ID Already Exist."}';exit;
  }else if($cntPhone!=0){
    echo '{"success":"false","msg":"Phone Number Already Exist."}';exit;
  }else if($cntUser!=0){
    echo '{"success":"false","msg":"User Name Already Exist."}';exit;
  }else{
    $Vendor_imgs=$dbf->fetchSingle("user",'profile_image',"id='$id'");


    $upload_path="../../admin/images/vendor/";
    $upload_path1="../../admin/images/vendor/thumb/";
    if($img!=''){
      $user_img = "Vendorprofile".date('Ymdhis'.$id.'.')."png";
    
      @unlink("../../admin/images/vendor/".$Vendor_imgs['profile_image']);
      @unlink("../../admin/images/vendor/thumb/".$Vendor_imgs['profile_image']);
      
     file_put_contents($upload_path.$user_img,base64_decode($img));
     file_put_contents($upload_path1.$user_img,base64_decode($img));
     $banber_img = "profile_image = '$user_img' ,";
     $string="full_name='$name',email='$email',user_name='$username',contact_no='$num',profile_image='$user_img',created_date=NOW()";
    }
    else{
      $string="full_name='$name',email='$email',user_name='$username',contact_no='$num',created_date=NOW()";
    }
    $dbf->updateTable("user",$string,"id='$id'");
    echo '{"success":"true","msg":"Profile Updated Successfully.."}';exit;
  }
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Update Profile

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

//Order Report
if(isset($_REQUEST['method']) && $_REQUEST['method']=="OrderReport"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);

  if(UserStatus($id)){
    $Total_Sale=0;

    foreach($dbf->fetchOrder("orders","status IN(4,7) AND vendor_id='$id'","","","") as $Total_cal){
      $Total_Sale+=($Total_cal['qty']*$Total_cal['price']);
    }
       $TotalPaid=$dbf->fetchSingle("payment_vendor",'SUM(amount) as Payment',"vendor_id='$id'");
       $TotalComision=$dbf->fetchSingle("payment_vendor",'SUM(comm_amnt) as Payment',"vendor_id='$id'");
       $All_order_array=array();
       $All_orders=$dbf->fetchOrder("orders","status IN(4,7) AND vendor_id='$id'","created_date DESC","","order_id");
       foreach($All_orders as $Order){
        $Array_order_details=array();

        $address = $dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");   
        $city = $dbf->fetchSingle("city",'*',"city_id='$address[city_id]'"); 
        $pincode=$dbf->fetchSingle("pincode",'*',"pincode_id='$address[pincode]'");
        $address=array("name"=>$address['first_name'].' '.$address['last_name'],"contact"=>$address['number'],"email"=>$address['email'],
        "address"=>$address['address'],"city"=>$city['city_name'],"pin"=>$pincode['pincode']);

        $Order_total=0;
         foreach($dbf->fetchOrder("orders","order_id='$Order[order_id]' AND status IN(4,7)","","price,qty","") as $Ordermant){
           $Order_total+=$Ordermant['qty']*$Ordermant['price'];
        }
        $Order_total =($Order_total+$Order['shipping_charge'])-$Order['wallet']-$Order['coupon_amnt'];
        $addres=$dbf->fetchSingle("address",'*',"address_id='$Order[address_id]'");

        $View_order=$dbf->fetchOrder("orders","order_id='$Order[order_id]'","orders_id DESC","","");
        $Array_of_view = array();
        $total=0;
          if(!empty($View_order)){
         
            foreach($View_order as $singleorder){
       
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
    
                switch($singleorder['status']){
                  case 0:
                    $prodstatus="New Order";
                  break;
                  case 1:
                    $prodstatus="Order Received";
                  break;
                  case 2:
                    $prodstatus="Processing";
                  break;
                  case 3:
                    $prodstatus="Shipped";
                  break;
                  case 4:
                    $prodstatus="Completed";
                  break;
                  case 5:
                    $prodstatus="Return Approved";
                  break;
                  case 6:
                    $prodstatus="Returned Process";
                  break;
                  case 7:
                    $prodstatus="Returned Cancel";
                  break;
                  case 8:
                    $prodstatus="Cancel By Customer";
                  break;
                  default:
                  $prodstatus="Cancel By Vendor";
          
              }
        
              array_push($Array_of_view,array("produt_name"=>$singleorder['ordername'].$return_order,
              "img"=>$img,"price"=>$singleorder['price'],"qty"=>$singleorder['qty'],"sub_total"=>"$sub_total","product_status"=>$prodstatus));
            }
          }
          $grand_total=$total+$Order['shipping_charge'];
          $grand_total=$grand_total-$Order['wallet']-$CAmnt;
          

      

        array_push($All_order_array,array("order_id"=>$Order['order_id'],"order_date"=>date('d.m.Y',strtotime($Order['created_date'])),"total_amnt"=>number_format("$Order_total",2),
        "view_details"=>$Array_of_view,"total"=>"$total",
        "shipping_char"=>$Order['shipping_charge'],"wallet"=>'-'.$Order['wallet'],"grand_total"=>"$grand_total",
        "coupon_code"=>$Ccode,"coupon_amnt"=>"$CAmnt","address"=>$address));
      }
    echo '{"success":"true","total_sale":"'.$Total_Sale.'","total_paid":"'.number_format($TotalPaid['Payment'],2).'","total_comission":"'.number_format($TotalComision['Payment'],2).'",
      "all_orders":'.json_encode($All_order_array).'}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Order Report



//Payment Reports
if(isset($_REQUEST['method']) && $_REQUEST['method']=="PaymentReports"){
  $id=$dbf->checkXssSqlInjection($_POST['id']);

  if(UserStatus($id)){
    $array_of_payments=array();
    $All_payments=$dbf->fetchOrder("payment_vendor","vendor_id='$id'","payment_vendor_id DESC","","");
    if(!empty($All_payments)){
      foreach($All_payments as $Payment){
        array_push($array_of_payments,array("date"=>date('d.m.Y',strtotime($Payment['date'])),"amount"=>number_format($Payment['amount'],2),"pay_mode"=>$Payment['payment_mode'],"remark"=>$Payment['remark']));
      }
    }
    echo '{"success":"true","All_payments":'.json_encode($array_of_payments).'}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Payment Reports

//Home Api
if(isset($_REQUEST['method']) && $_REQUEST['method']=="Dashboard"){

  $profileuserid=$dbf->checkXssSqlInjection($_POST['id']);
  
  if(UserStatus($profileuserid)){
    $NewOrder=number_format($dbf->countRows("orders","vendor_id='$profileuserid' AND status='0'",""));
    $All_order=number_format($dbf->countRows("orders","vendor_id='$profileuserid'",""));
    $Complete_order=number_format($dbf->countRows("orders","vendor_id='$profileuserid' AND status='4'",""));
    $Total_Sale=0;

    foreach($dbf->fetchOrder("orders","status IN(4,7) AND vendor_id='$profileuserid'","","","") as $Total_cal){
      $Total_Sale+=($Total_cal['qty']*$Total_cal['price']);
    }
    $TotalPaid=$dbf->fetchSingle("payment_vendor",'SUM(amount) as Payment',"vendor_id='$profileuserid'");
    $TotalComision=$dbf->fetchSingle("payment_vendor",'SUM(comm_amnt) as Payment',"vendor_id='$profileuserid'");

    echo '{"success":"true","new_order":"'.$NewOrder.'","all_order":"'.$All_order.'","compelete":"'.$Complete_order.'",
      "total_sale":"'.$Total_Sale.'","total_commis":"'.number_format($TotalComision['Payment'],2).'","total_recive":"'.number_format($TotalPaid['Payment'],2).'"}';exit;
  }else{
    echo '{"success":"false","msg":"Your Are Blocked."}';exit;
  }
}
//Home Api




















