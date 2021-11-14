<?php 
include_once '../includes/class.Main.php';
header('Content-Type:application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: X-Requested-With');
$dbf=new User();
date_default_timezone_set('Asia/Kolkata');
$local_url = "https://collegeprojectz.com/salowin/";


################################  Registration  ##############################

         if(isset($_REQUEST['method']) && $_REQUEST['method']=="register"){

            $fullname = $dbf->checkXssSqlInjection($_REQUEST['fullname']);
            $contact = $dbf->checkXssSqlInjection($_REQUEST['contact']);
            $email = $dbf->checkXssSqlInjection($_REQUEST['email']);
        
            $cntPhone=$dbf->countRows("user","contact_no='$contact'","");

            if($email =='' ||  $contact=='' || $fullname =='')
            {
                $result["success"]="false";
                $result["msg"]="Please fill up all mandatory fields.";
                header('Content-type: application/json', true, 401);
                echo $x=json_encode($result);
            }else{
            
            if($cntPhone==0){
              $cntEmail=$dbf->countRows("user","email='$email'","");
              if($cntEmail == 0){
                
            $string="full_name='$fullname', contact_no='$contact', email='$email', user_type='4', created_date=NOW()";
               $inst_id = $dbf->insertSet("user",$string);
                         
            if($inst_id){
                $ChkUser=$dbf->fetchSingle("user",'id,full_name,email,contact_no',"id='$inst_id' AND user_type='4'");
              $result["success"]="true";
              $result["msg"]="Successfully Register..";
              $result["id"]=$ChkUser['id'];
          $result["name"]=$ChkUser['full_name'];
          $result["email"]=$ChkUser['email'];
          $result["contact_no"]=$ChkUser['contact_no'];
              header('Content-type: application/json', true, 200);
              echo $x=json_encode($result);exit;
            }else{
              $result["success"]="false";
              $result["msg"]="Invalid Register..";
              header('Content-type: application/json', true, 400);
              echo $x=json_encode($result);exit;
            }
          
          }else{
            $result["success"]="false";
            $result["msg"]="Email Id Already Exist.";
            header('Content-type: application/json', true, 409);
            echo $x=json_encode($result);exit;
          }
          }else{
            $result["success"]="false";
            $result["msg"]="Mobile No. Already Exist.";
            header('Content-type: application/json', true, 409);
            echo $x=json_encode($result);exit;
          }
        }
        }
      // End Registration

?>