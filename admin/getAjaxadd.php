<?php
include_once 'includes/class.Main.php';
$dbf = new User(); 
//Add Vendor
if(isset($_REQUEST['Submits']) && $_REQUEST['Submits']=='addagent'){
	$name=$dbf->checkXssSqlInjection($_REQUEST['name']);
	$email=$dbf->checkXssSqlInjection($_REQUEST['email']);
	$contact=$dbf->checkXssSqlInjection($_REQUEST['contact']);
	$username=$dbf->checkXssSqlInjection($_REQUEST['username']);
	$password=base64_encode(base64_encode($_REQUEST['password']));
	$shopname=$dbf->checkXssSqlInjection($_REQUEST['shopname']);
	$address=$dbf->checkXssSqlInjection($_REQUEST['address']);
	$gst=$dbf->checkXssSqlInjection($_REQUEST['gst']);
	$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
	$city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
	$lat=$dbf->checkXssSqlInjection($_REQUEST['lat']);
	$lng=$dbf->checkXssSqlInjection($_REQUEST['lng']);
	$zcode = $dbf->checkXssSqlInjection($_REQUEST['zcode']);
	
		$commition=$dbf->checkXssSqlInjection($_REQUEST['commition']);
	$accno=$dbf->checkXssSqlInjection($_REQUEST['accno']);
	$ifsc=$dbf->checkXssSqlInjection($_REQUEST['ifsc']);

	
	
	$catagory_id=$_REQUEST['catagory_id'];
$cntcontact=$dbf->countRows("user","contact_no='$contact'");
$cntemail=$dbf->countRows("user","email='$email'");
$cntuser=$dbf->countRows("user","user_name='$username'");
if($cntcontact!=0){
	echo "phnErro";exit;
	}elseif($cntemail!=0){
	echo "emaiErro";exit;
	}elseif($cntuser!=0){
	echo "userErro";exit;
	}else{
	    
	    //upload id_Proodf
	    
	    
	    	if($_FILES['idproof']['name']!='' && (($_FILES['idproof']['type'] == "image/gif") || ($_FILES['idproof']['type'] == "image/jpeg") || ($_FILES['idproof']['type'] == "image/pjpeg") || ($_FILES['idproof']['type'] == "image/png") || ($_FILES['idproof']['type'] == "image/bmp"))){
	
		$idproof =time().".".substr(strrchr($_FILES['idproof']['name'], "."), 1);
		$source_path1="images/vendor/".$idproof;
		
		$destination_path1="images/vendor/thumb/".$idproof;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['idproof']['tmp_name'],"images/vendor/".$idproof);
		
		if($_FILES['idproof']['type'] == "image/JPG" || $_FILES['idproof']['type'] == "image/JPEG" || $_FILES['idproof']['type'] == "image/jpg" || $_FILES['idproof']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['idproof']['type'] == "image/gif" || $_FILES['idproof']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['idproof']['type'] == "image/png" || $_FILES['idproof']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
	    	}
	    //Upload Registration Proof	
	    	if($_FILES['shopcopy']['name']!='' && (($_FILES['shopcopy']['type'] == "image/gif") || ($_FILES['shopcopy']['type'] == "image/jpeg") || ($_FILES['shopcopy']['type'] == "image/pjpeg") || ($_FILES['shopcopy']['type'] == "image/png") || ($_FILES['shopcopy']['type'] == "image/bmp"))){
	
		$shopcopy =time().".".substr(strrchr($_FILES['shopcopy']['name'], "."), 1);
		$source_path1="images/vendor/".$shopcopy;
		
		$destination_path1="images/vendor/thumb/".$shopcopy;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['shopcopy']['tmp_name'],"images/vendor/".$shopcopy);
		
		if($_FILES['shopcopy']['type'] == "image/JPG" || $_FILES['shopcopy']['type'] == "image/JPEG" || $_FILES['shopcopy']['type'] == "image/jpg" || $_FILES['shopcopy']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['shopcopy']['type'] == "image/gif" || $_FILES['shopcopy']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['shopcopy']['type'] == "image/png" || $_FILES['shopcopy']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
	    	}
	    	
	    	//Upload GST copy
	    	
	    		if($_FILES['gstcopy']['name']!='' && (($_FILES['gstcopy']['type'] == "image/gif") || ($_FILES['gstcopy']['type'] == "image/jpeg") || ($_FILES['gstcopy']['type'] == "image/pjpeg") || ($_FILES['gstcopy']['type'] == "image/png") || ($_FILES['gstcopy']['type'] == "image/bmp"))){
	
		$gstcopy =time().".".substr(strrchr($_FILES['gstcopy']['name'], "."), 1);
		$source_path1="images/vendor/".$gstcopy;
		
		$destination_path1="images/vendor/thumb/".$gstcopy;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['gstcopy']['tmp_name'],"images/vendor/".$gstcopy);
		
		if($_FILES['gstcopy']['type'] == "image/JPG" || $_FILES['gstcopy']['type'] == "image/JPEG" || $_FILES['gstcopy']['type'] == "image/jpg" || $_FILES['gstcopy']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['gstcopy']['type'] == "image/gif" || $_FILES['gstcopy']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['gstcopy']['type'] == "image/png" || $_FILES['gstcopy']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
	    	}
	    	
	    	//Upload Passbook Copy
	    	
	    			if($_FILES['passbook']['name']!='' && (($_FILES['passbook']['type'] == "image/gif") || ($_FILES['passbook']['type'] == "image/jpeg") || ($_FILES['passbook']['type'] == "image/pjpeg") || ($_FILES['passbook']['type'] == "image/png") || ($_FILES['passbook']['type'] == "image/bmp"))){
	
		$passbook =time().".".substr(strrchr($_FILES['passbook']['name'], "."), 1);
		$source_path1="images/vendor/".$passbook;
		
		$destination_path1="images/vendor/thumb/".$passbook;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['passbook']['tmp_name'],"images/vendor/".$passbook);
		
		if($_FILES['passbook']['type'] == "image/JPG" || $_FILES['passbook']['type'] == "image/JPEG" || $_FILES['passbook']['type'] == "image/jpg" || $_FILES['passbook']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['passbook']['type'] == "image/gif" || $_FILES['passbook']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['passbook']['type'] == "image/png" || $_FILES['passbook']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
	    	}
	    	
	    	
	    	

	if($_FILES['img']['name']!='' && (($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp"))){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/vendor/".$fname1;
		
		$destination_path1="images/vendor/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/vendor/".$fname1);
		
		if($_FILES['img']['type'] == "image/JPG" || $_FILES['img']['type'] == "image/JPEG" || $_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['img']['type'] == "image/gif" || $_FILES['img']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['img']['type'] == "image/png" || $_FILES['img']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
	
	//@unlink("../banner-img/".$fname1);

$string="commition='$commition',ac_num='$accno',ifsc_code='$ifsc',id_proof_fside='$idproof',shop_redg_proof='$shopcopy',gst_copy='$gstcopy',passbook_copy='$passbook',full_name='$name', email='$email', contact_no='$contact', profile_image='$fname1', user_name='$username', password='$password', shop_name='$shopname', gst_no='$gst', country_id='$country_id', state_id='$state_id', city_id='$city_id', address1='$address', user_type='3', status='1',pin='$zcode',lat='$lat',lng='$lng',created_date=NOW()";

$vendor_id =$dbf->insertSet("user",$string);

foreach($catagory_id as $catagoryy){
	$string1="vendor_id='$vendor_id', catagory_id='$catagoryy'";
	$dbf->insertSet("vendor_catagory",$string1);
	
	}
 $from="speedyshop@speedyshop.in";
 $to=$email;
		                //Send mail to customer--------------------------------------
			$subject = "Register Successfully.";
   
			$message = '  
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register Successfully</title>
</head>

<body>


  
  <div style="background:#f9f9f9">

    <div marginwidth="0" marginheight="0">
      <div id="m_-7425744747174841539m_-2068209997140136048wrapper" dir="ltr">
        <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td valign="top" align="center"><div > </div>
                <table style="border: solid 1px #ccc;">
                  <tbody>
                    <tr>
                      <td valign="top" align="center"><table id="" width="600" cellspacing="0" cellpadding="0" border="0" style="background:#96588A;">
                        <tbody>
                          <tr>
                            <td style="color:#fff; text-align:center;"><h1>Welcome to Speedy Shop</h1></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                    <tr>
                      <td valign="top" align="center"><table id="m_-7425744747174841539m_-2068209997140136048template_body" width="600" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                          <tr>
                            <td id="m_-7425744747174841539m_-2068209997140136048body_content" valign="top"><table width="100%" cellspacing="0" cellpadding="20" border="0">
                              <tbody>
                                <tr>
                                  <td valign="top"><div id="m_-7425744747174841539m_-2068209997140136048body_content_inner">
                                    <p>Hi '. $name.',</p>
                                    <p>Thanks for creating an account on Speedy Shop. Your username is <strong>'.$username.' </strong> AND Password Is <strong> '.base64_decode(base64_decode($password)).' </strong>. You can access your account area to view orders, change your password, and more at:<a href="https://speedyshop.in/">https://speedyshop.in/</a></p> 
                                    
                                    <p>We look forward to seeing you soon.</p>
                                  </div></td>
                                </tr>
                              </tbody>
                            </table></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            <tr>
              <td valign="top" align="center"><table width="610"   style="background:#96588a; color:#fff; ">
                <tbody>
                  <tr>
                    <td valign="top"><table width="100%" cellspacing="0" cellpadding="10" border="0">
                      <tbody>
                        <tr>
                          <td colspan="2"  valign="middle"><p>Speedy Shop</p></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                </tbody>
              </table></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

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

$usernames = '1984samirsahoo@gmail.com';

$hash = 'Ei25fVbm6SY-zSjLl0H5sDA5F5YB2fU6cSmenvTFvL';


// Message details

$numbers = array($contact);

$sender = urlencode('WEBADD');

$message = rawurlencode('Login Sucess');


$numbers = implode(',', $numbers);


// Prepare data for POST request

$data = array('username' => $usernames, 'hash' => $hash, 'numbers' => $numbers, "sender" => $sender, "message" => $message);


// Send the POST request with cURL

$ch = curl_init('http://sms.webadd.in/api2/send/');

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

curl_close($ch);


// Process your response here

// echo $response;

echo "succes";exit;
}else{
	echo "imgError";exit;
}
}
}
//Add Vendor

if(isset($_REQUEST['Submits']) && $_REQUEST['Submits']=='editagent'){
    			
	$agentid=$dbf->checkXssSqlInjection($_REQUEST['pagesId']);
	$name=$dbf->checkXssSqlInjection($_REQUEST['name']);
	$email=$dbf->checkXssSqlInjection($_REQUEST['email']);
	$contact=$dbf->checkXssSqlInjection($_REQUEST['contact']);
	$username=$dbf->checkXssSqlInjection($_REQUEST['username']);
	$password=base64_encode(base64_encode($_REQUEST['password']));
	$shopname=$dbf->checkXssSqlInjection($_REQUEST['shopname']);
	$address=$dbf->checkXssSqlInjection($_REQUEST['address']);
	$gst=$dbf->checkXssSqlInjection($_REQUEST['gst']);
	$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
	$city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
	$lat=$dbf->checkXssSqlInjection($_REQUEST['lat']);
	$lng=$dbf->checkXssSqlInjection($_REQUEST['lng']);
	$zcode = $dbf->checkXssSqlInjection($_REQUEST['zcode']);
	
	$commition=$dbf->checkXssSqlInjection($_REQUEST['commition']);
	$accno=$dbf->checkXssSqlInjection($_REQUEST['accno']);
	$ifsc=$dbf->checkXssSqlInjection($_REQUEST['ifsc']);
	
	
	$catagory_id=$_REQUEST['catagory_id'];
			
$cntcontact=$dbf->countRows("user","contact_no='$contact' AND id!='$agentid'");
$cntemail=$dbf->countRows("user","email='$email' AND id!='$agentid'");
$cntuser=$dbf->countRows("user","user_name='$username' AND id!='$agentid'");

if($cntcontact!=0){
	echo "phnErro";exit;
	}elseif($cntemail!=0){
	echo "emaiErro";exit;
	}elseif($cntuser!=0){
	echo "userErro";exit;
	}else{
	    
	    	if($_FILES['idproof']['name']!='' && (($_FILES['idproof']['type'] == "image/gif") || ($_FILES['idproof']['type'] == "image/jpeg") || ($_FILES['idproof']['type'] == "image/pjpeg") || ($_FILES['idproof']['type'] == "image/png") || ($_FILES['idproof']['type'] == "image/bmp"))){
	
		$idproof =time().".".substr(strrchr($_FILES['idproof']['name'], "."), 1);
		$source_path1="images/vendor/".$idproof;
		
		$destination_path1="images/vendor/thumb/".$idproof;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['idproof']['tmp_name'],"images/vendor/".$idproof);
		
		if($_FILES['idproof']['type'] == "image/JPG" || $_FILES['idproof']['type'] == "image/JPEG" || $_FILES['idproof']['type'] == "image/jpg" || $_FILES['idproof']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['idproof']['type'] == "image/gif" || $_FILES['idproof']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['idproof']['type'] == "image/png" || $_FILES['idproof']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
		
		$dbf->updateTable("user","id_proof_fside='$idproof'","id='$agentid'");
		
	    	}
	    	
	    		if($_FILES['shopcopy']['name']!='' && (($_FILES['shopcopy']['type'] == "image/gif") || ($_FILES['shopcopy']['type'] == "image/jpeg") || ($_FILES['shopcopy']['type'] == "image/pjpeg") || ($_FILES['shopcopy']['type'] == "image/png") || ($_FILES['shopcopy']['type'] == "image/bmp"))){
	
		$shopcopy =time().".".substr(strrchr($_FILES['shopcopy']['name'], "."), 1);
		$source_path1="images/vendor/".$shopcopy;
		
		$destination_path1="images/vendor/thumb/".$shopcopy;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['shopcopy']['tmp_name'],"images/vendor/".$shopcopy);
		
		if($_FILES['shopcopy']['type'] == "image/JPG" || $_FILES['shopcopy']['type'] == "image/JPEG" || $_FILES['shopcopy']['type'] == "image/jpg" || $_FILES['shopcopy']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['shopcopy']['type'] == "image/gif" || $_FILES['shopcopy']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['shopcopy']['type'] == "image/png" || $_FILES['shopcopy']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
		
			$dbf->updateTable("user","shop_redg_proof='$shopcopy'","id='$agentid'");
	    	}
	    	
	    			if($_FILES['gstcopy']['name']!='' && (($_FILES['gstcopy']['type'] == "image/gif") || ($_FILES['gstcopy']['type'] == "image/jpeg") || ($_FILES['gstcopy']['type'] == "image/pjpeg") || ($_FILES['gstcopy']['type'] == "image/png") || ($_FILES['gstcopy']['type'] == "image/bmp"))){
	
		$gstcopy =time().".".substr(strrchr($_FILES['gstcopy']['name'], "."), 1);
		$source_path1="images/vendor/".$gstcopy;
		
		$destination_path1="images/vendor/thumb/".$gstcopy;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['gstcopy']['tmp_name'],"images/vendor/".$gstcopy);
		
		if($_FILES['gstcopy']['type'] == "image/JPG" || $_FILES['gstcopy']['type'] == "image/JPEG" || $_FILES['gstcopy']['type'] == "image/jpg" || $_FILES['gstcopy']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['gstcopy']['type'] == "image/gif" || $_FILES['gstcopy']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['gstcopy']['type'] == "image/png" || $_FILES['gstcopy']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
		
			$dbf->updateTable("user","gst_copy='$gstcopy'","id='$agentid'");
	    	}
	    
	    	
	    			if($_FILES['passbook']['name']!='' && (($_FILES['passbook']['type'] == "image/gif") || ($_FILES['passbook']['type'] == "image/jpeg") || ($_FILES['passbook']['type'] == "image/pjpeg") || ($_FILES['passbook']['type'] == "image/png") || ($_FILES['passbook']['type'] == "image/bmp"))){
	
		$passbook =time().".".substr(strrchr($_FILES['passbook']['name'], "."), 1);
		$source_path1="images/vendor/".$passbook;
		
		$destination_path1="images/vendor/thumb/".$passbook;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['passbook']['tmp_name'],"images/vendor/".$passbook);
		
		if($_FILES['passbook']['type'] == "image/JPG" || $_FILES['passbook']['type'] == "image/JPEG" || $_FILES['passbook']['type'] == "image/jpg" || $_FILES['passbook']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['passbook']['type'] == "image/gif" || $_FILES['passbook']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['passbook']['type'] == "image/png" || $_FILES['passbook']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
		
			$dbf->updateTable("user","passbook_copy='$passbook'","id='$agentid'");
	    	}
	    
	    
	    
	    
	    
	    

		if($_FILES['img']['name']!=''){
		    
		
		if(($_FILES['img']['type'] == "image/gif") || ($_FILES['img']['type'] == "image/jpeg") || ($_FILES['img']['type'] == "image/pjpeg") || ($_FILES['img']['type'] == "image/png") || ($_FILES['img']['type'] == "image/bmp")){
	
		$fname1 =time().".".substr(strrchr($_FILES['img']['name'], "."), 1);
		$source_path1="images/vendor/".$fname1;
		
		$destination_path1="images/vendor/thumb/".$fname1;	
		$imgsize1 = getimagesize($source_path1);		
		$new_height1 = 400;
		$new_width1 = 400;		
		$destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die("Problem In Creating image");						
		move_uploaded_file($_FILES['img']['tmp_name'],"images/vendor/".$fname1);
		
		if($_FILES['img']['type'] == "image/JPG" || $_FILES['img']['type'] == "image/JPEG" || $_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type']=='image/jpeg' ){
			//for small                
			$srcimg1=ImageCreateFromJPEG($source_path1) or die("Problem In opening Source Image");
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die("Problem In resizing");
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['img']['type'] == "image/gif" || $_FILES['img']['type'] == "image/GIF"){  
			//for small          
			$srcimg1 = imagecreatefromgif($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving");
		}else if($_FILES['img']['type'] == "image/png" || $_FILES['img']['type'] == "image/PNG"){ 
			 //for small          
			$srcimg1 = imagecreatefrompng($source_path1);
			ImageCopyResampled($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1));
			ImageJPEG($destimg1,$destination_path1,100) or die("Problem In saving"); 
		}
	}else{
		echo "imgError";exit;
	}
	}
	
	
	//@unlink("../banner-img/".$fname1);

	
	
	if($fname1!=''){
	$string="commition='$commition',ac_num='$accno',ifsc_code='$ifsc',full_name='$name', email='$email', contact_no='$contact', profile_image='$fname1', user_name='$username', password='$password', shop_name='$shopname', gst_no='$gst', country_id='$country_id', state_id='$state_id', city_id='$city_id', address1='$address', user_type='3', status='1',pin='$zcode',lat='$lat',lng='$lng',created_date=NOW()";
	}else{
		$string="commition='$commition',ac_num='$accno',ifsc_code='$ifsc',full_name='$name', email='$email', contact_no='$contact', user_name='$username', password='$password', shop_name='$shopname', gst_no='$gst', country_id='$country_id', state_id='$state_id', city_id='$city_id', address1='$address', user_type='3', status='1',pin='$zcode',lat='$lat',lng='$lng',created_date=NOW()";
		}
	$dbf->updateTable("user",$string,"id='$agentid'");
	$dbf->deleteFromTable("vendor_catagory","vendor_id='$agentid'");
	foreach($catagory_id as $catagoryy){
	$string1="vendor_id='$agentid', catagory_id='$catagoryy'";
	$dbf->insertSet("vendor_catagory",$string1);
	
	}
	echo "succes";exit;
	
    }
    

}  
?>