<?php 
ob_start();
session_start();
include_once 'admin/includes/class.Main.php';
 $dbf = new User();
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
$ip = getUserIP();
 $login=$dbf->fetchSingle("user","*","user_name='$_REQUEST[username]'  and user_type='4'");
 $login_id = $login['user_name'];
 if(empty($login)){

 $login=$dbf->fetchSingle("user","*","email='$_REQUEST[username]'  and user_type='4'");
 $login_id = $login['email'];

if(empty($login)){
 $login=$dbf->fetchSingle("user","*","contact_no='$_REQUEST[username]'  and user_type='4'");
 $login_id = $login['contact_no'];

}
 }

   $CartShop=$dbf->fetchSingle("cart","*","user_id='$ip'");
    $password=base64_decode(base64_decode($login['password']));
    if($_REQUEST['username'] == $login_id && $_REQUEST['password']==$password ){

        //Check User Cart Data
        $chkCart=$dbf->countRows("cart","user_id='$ip'");
         if($chkCart!=0){

            //Check Cart Data With Login Id 
            $chkLoginID=$dbf->countRows("cart","user_id='$login[id]'");
            if ($chkLoginID!=0) {
                // Check Same Shop Cart Data
                 $chkUserShop=$dbf->countRows("cart","user_id='$login[id]' AND shop_id='$CartShop[shop_id]'");
                  if($chkUserShop!=0){
                      $dbf->updateTable("recent_views","user_id='$login[id]'","user_id='$ip'");
                     $dbf->updateTable("cart","user_id='$login[id]'","user_id='$ip'");
                        $_SESSION['userid']=$login['id'];
                        $_SESSION['email']=$login['email'];
                        
                        $_SESSION['usertype']="4";
                        
                        header("Location:user-dashboard.php");exit;
                  }else{
                      $dbf->updateTable("recent_views","user_id='$login[id]'","user_id='$ip'");
                     $_SESSION['userid']=$login['id'];
                    $_SESSION['email']=$login['email'];
                    $_SESSION['usertype']="4";

                     $_SESSION['shop_id']=$CartShop['shop_id'];
                    $_SESSION['prod_id']=$CartShop['product_id'];
                    $_SESSION['qty']=$CartShop['qty'];
                    $_SESSION['attribute']=$CartShop['attribute_id'];
                    $_SESSION['varition']=$CartShop['variation_id'];
                    header("Location:cart.php?msg=CartNew");exit;
                  }

            }else{
                  $dbf->updateTable("cart","user_id='$login[id]'","user_id='$ip'");
            $dbf->updateTable("recent_views","user_id='$login[id]'","user_id='$ip'");
        $_SESSION['userid']=$login['id'];
        $_SESSION['email']=$login['email'];
        
        $_SESSION['usertype']="4";
          header("Location:user-dashboard.php");exit;
            }
         }else{
        $dbf->updateTable("recent_views","user_id='$login[id]'","user_id='$ip'");
        $_SESSION['userid']=$login['id'];
        $_SESSION['email']=$login['email'];
        
        $_SESSION['usertype']="4";
          header("Location:user-dashboard.php");exit;

         }
    }else{
        header("Location:login.php?msg=error");exit;
    }
    
    ?>
