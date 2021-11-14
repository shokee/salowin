<?php include("header.php"); ?>

<?php if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='ForgetPwd'){

	$email = $_POST['emails'];
	 $chkEmail=$dbf->countRows("user","email='$email'");

	 if($chkEmail!=0){

	 	$users= $dbf->fetchSingle("user",'*',"email='$email'");
	 
		$password=base64_decode(base64_decode($users['password']));

	 	//Send mail to customer--------------------------------------
			$to=$email;

		$from = 'order@ownmystore.com';
		$subject = "Password Reset Successfully.";

		$message = "<table align='center' width='700px' style='border-radius:6px;'>   
				<tr>
				  <td colspan='2' align='center'>Own My Store</td>
				</tr>
				<tr>
				  <td height='25' colspan='2' align='left' valign='top' style='padding-left:10px;'>Dear 
				  $users[full_name], Your Password Reset Successfully at Own My Shop, You login details mention bellow.</td>
				</tr>
				<tr>
				  <td align='right' valign='top' height='5'></td>
				  <td align='left' valign='top' height='5'></td>
				</tr>
				<tr>
				  <td width='217' align='right' valign='top' height='25'><strong>Login Username  :</strong></td>
				  <td width='471' align='left' valign='top' height='25'>&nbsp;$users[user_name]</td>       
				</tr>
				<tr>
				  <td width='217' align='right' valign='top' height='25'><strong>Password :</strong></td>
				  <td width='471' align='left' valign='top' height='25'>&nbsp;$password</td>       
				</tr>
				<tr>
				  <td align='center'>&nbsp;</td>
				  <td align='center'>&nbsp;</td>
				</tr>
				<tr>
				  <td align='center'>&nbsp;</td>
				  <td align='center' height='25' style='font-family:Comic Sans MS, cursive;'>Thanks,</td>
				</tr> 
				<tr>
				  <td align='center'>&nbsp;</td>
				  <td align='center' height='25' style='font-family:Comic Sans MS, cursive;'>Own My Store.</td>
				</tr> 
				<tr>
				  <td align='center'>&nbsp;</td>
				  <td align='center'>&nbsp;</td>
				</tr>  
			  </table>";

		// echo $message;exit;
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
	   
		//More headers
		$headers .= 'From:'. $from . "\r\n";   
	   
		mail($to,$subject,$message,$headers);
		//echo $message;exit;
		//Send mail to customer--------------------------------------
		header("Location:login.php?msg=pwdreset");exit();
	 }else{
	 	$error = "Invalid Email Id";
	 }
}?>





        <div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>
	<div class="uk-width-1-1">
		<div class="uk-container">
			<div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
				<div class="uk-width-1-1@m">
					<div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body border uk-border-rounded">
 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>User Add Successfully</p>
</div>
<?php }?>
 
 
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='contacExit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Contact No  Already Exit</p>
</div>
<?php }?> 

<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='emailExit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Email   Already Exit</p>
</div>
<?php }?> 

<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='userExit'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Username   Already Exit</p>
</div>
<?php }?>
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='error'){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Invaild Username/Password</p>
</div>
<?php }?>
	<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='pwdreset'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>Your New Login Details Send To Your Email.</p>
</div>

<?php }?>
<?php if(isset($error)){?>
<div class="uk-alert-danger" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p><?= $error?></p>
</div>

<?php }?>
						<ul class="uk-tab uk-flex-center" uk-grid uk-switcher="animation: uk-animation-fade">
							<li><a href="#">Log In</a></li>
							<li><a href="#">Sign Up</a></li>
							<li class="uk-hidden">Forgot Password?</li>
						</ul>
						<ul class="uk-switcher uk-margin">
                        <li>
								<h3 class="uk-card-title uk-text-center">LOGIN</h3>
				<form  action="loginprocess.php" method="post">
									<div class="uk-margin">
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: mail"></span>
											<input class="uk-input " type="text" name="username" placeholder="username or  email or mobile number" required>
										</div>
									</div>
									<div class="uk-margin">
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: lock"></span>
											<input class="uk-input " type="password" name="password" placeholder="Password" required>	
										</div>
									</div>
									<div class="uk-margin uk-text-right@s uk-text-center uk-text-small">
										<a href="#" uk-switcher-item="2">Forgot Password?</a>
									</div>
									<div class="uk-margin">
										<button  class="uk-button uk-button-primary  uk-width-1-1" type="submit">Login</button>
									</div>
									<div class="uk-text-small uk-text-center">
										Not registered? <a href="#" uk-switcher-item="1">Create an account</a>
									</div>
								</form>
							</li>
							<li>
                            
                            
<?php 
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='registration'){

	$fullname=$dbf->checkXssSqlInjection($_REQUEST['fullname']);
	$contact=$dbf->checkXssSqlInjection($_REQUEST['contact']);
	$mail=$dbf->checkXssSqlInjection($_REQUEST['mail']);
	$username=$dbf->checkXssSqlInjection($_REQUEST['username']);
	$password=base64_encode(base64_encode($_REQUEST['password']));
	
	$string="full_name='$fullname', contact_no='$contact', email='$mail', user_name='$username', password='$password', user_type='4', created_date=NOW()";

	$cntUser=$dbf->countRows("user","user_name='$username'");
	$cntEmail=$dbf->countRows("user","email='$mail'");
	$cntConta=$dbf->countRows("user","contact_no='$contact'");

	if($cntUser!=0){
		header("Location:login.php?msg=userExit");
	}elseif($cntEmail!=0){
		header("Location:login.php?msg=emailExit");
	}elseif ($cntConta!=0) {
		header("Location:login.php?msg=contacExit");
	}else{

	$ins_id=$dbf->insertSet("user",$string);
		$login=$dbf->fetchSingle("user","*","id='$ins_id'");

	$dbf->updateTable("cart","user_id='$login[id]'","user_id='$ip'");
	$dbf->updateTable("recent_views","user_id='$login[id]'","user_id='$ip'");
		$_SESSION['userid']=$login['id'];
		$_SESSION['email']=$login['email'];
   $from="order@ownmystore.com";
	       $to=$mail;
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
                            <td style="color:#fff; text-align:center;"><h1>Welcome to Own My Store</h1></td>
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
                                    <p>Hi '. $fullname.',</p>
                                    <p>Thanks for creating an account on Own My Store. Your username is <strong>'.$username.' </strong> AND Password Is <strong> '.base64_decode(base64_decode($password)).' </strong>. You can access your account area to view orders, change your password, and more at:<a href="https://ownmystore.in/">https://ownmystore.in/</a></p> 
                                    
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
                          <td colspan="2"  valign="middle"><p>Own My Store</p></td>
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

echo $response;


		header("Location:user-dashboard.php");
		
        }
		
		
	

	
}
?>
	<h3 class="uk-card-title uk-text-center">REGISTER</h3>
								<form action="" method="post">
									<div class="uk-margin">
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: user"></span>
                                            
											<input class="uk-input" type="text" name="fullname" id="fullname" placeholder="Your full name" required>
										</div>
									</div>
                                    <div class="uk-margin">
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: phone"></span>
											<input class="uk-input " name="contact" id="contact" type="number" placeholder="Contact No" required min="0" autocomplete="off" onkeyup="MobileValid(this.value)">
										</div>
									</div>
									<div class="uk-margin">
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: mail"></span>
											<input class="uk-input " type="mail" name="mail" id="mail" placeholder="Email address" required>
										</div>
									</div>
                                    <div class="uk-margin">
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: user"></span>
											<input class="uk-input " type="text" name="username" id="username" placeholder="Username" required>
										</div>
									</div>
									<div class="uk-margin">
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: lock"></span>
											<input class="uk-input " type="password" name="password" id="password" placeholder="Set a password" required>	
										</div>
									</div>
									<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
								<label><input class="uk-checkbox" name="term" id="term" type="checkbox" checked="checked" required> I agree the Terms and Conditions.</label>
									</div>
									<div class="uk-margin">
										<button class="uk-button uk-button-primary  uk-width-1-1" type="submit" id="submit" name="submit" value="registration" onclick="SignUp()">Signup</button>
									</div>
									<div class="uk-text-small uk-text-center">
										Already have an account? <a href="#" uk-switcher-item="0">Log in</a>
									</div>
								</form>
							</li>
							
							<li >
								<div id="MObVertfy">
								<h3 class="uk-card-title uk-text-center">Forgot your password?</h3>
								<p class="uk-text-center uk-width-medium@s uk-margin-auto">Enter your Mobile Number.</p>
									<form action="" method="post">
									<div class="uk-margin">
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: phone"></span>
											<input class="uk-input " type="text" placeholder="Enter Your Phone Number" name="num" id="numforotp">
											<span id="otpError" class="uk-text-danger"></span>
										</div>
									</div>
									<div class="uk-margin">
										<button class="uk-button uk-button-primary  uk-width-1-1" name="operation" value="ForgetPwd" type="button" onclick="Sendotp()">Send OTP</button>
									</div>
									<div class="uk-text-small uk-text-center">
										<a href="#" uk-switcher-item="0">Back to login</a>
									</div>
								</form>
								</div>
								<div id="OtpVertfy" style="display:none;">
								<h3 class="uk-card-title uk-text-center">Verify OTP</h3>
								<p class="uk-text-center uk-width-medium@s uk-margin-auto" id="Shownum"></p>
									<form action="" method="post">
									<div class="uk-margin">
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: phone"></span>
											<input class="uk-input " type="text" placeholder="Enter Your OTP" name="num" id="Otp">
											<span id="CheckOtp" class="uk-text-danger"></span>
										</div>
										
									</div>
									<div class="uk-margin">
										<button class="uk-button uk-button-primary  uk-width-1-1" name="operation" value="ForgetPwd" type="button" onclick="VeriFyotp()">Verify OTP</button>
									</div>
									
								</form>
								</div>


								<div id="ChangePwd" style="display:none;">
								<h3 class="uk-card-title uk-text-center">Change Password</h3>
								<p class="uk-text-center uk-width-medium@s uk-margin-auto uk-text-success" id="PwdSucess"></p>
								<p class="uk-text-center uk-width-medium@s uk-margin-auto uk-text-danger" id="PwdError"></p>
									<form action="" method="post" id="ChangePwdFrm">
									<div class="uk-margin">
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: lock"></span>
											<input class="uk-input " type="password" placeholder="Enter New Password"  id="newPwd">
											<span id="newPwdEror" class="uk-text-danger"></span>
										</div>
									</div>
									<div class="uk-margin">	
										<div class="uk-inline uk-width-1-1">
											<span class="uk-form-icon" uk-icon="icon: lock"></span>
											<input class="uk-input " type="password" placeholder="Enter Confirm Password" id="confPwd">
											<span id="confPwdEror" class="uk-text-danger"></span>
										</div>
									</div>
									
									<div class="uk-margin">
										<button class="uk-button uk-button-primary  uk-width-1-1" name="operation" value="ForgetPwd" type="button" onclick="ChangessPwd()">Change Password</button>
									</div>
									
								</form>
								</div>
							</li>

							
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
  		    
    </div>        
            
<script>
function SignUp(){
var name= $('#fullname').val();
var num = $('#contact').val();
var email = $('#mail').val();
var username = $('#username').val();
var password = $('#password').val();
var term = $('#term').val();
if(!name){

}else if(!num){

}else if(!email){

}else if(!username){

}else if(!password){

}else if(!term){

}

}
</script>

<script>
function MobileValid(num){
	num = num.substring(0, 10);
	$('#contact').val(num);	
}


function Sendotp(){
	var numforotp=$('#numforotp').val();
	var otpError=$('#otpError');
	if(numforotp.length>0){
		var url="getAjaxx.php";
 $.post(url,{"choice":"numVerify","mobile_num":numforotp},function(res){
	
	 if(res=='error'){
		otpError.text("Invalid Phone Number.");
	 }else{
		$('#MObVertfy').css('display','none');
		$('#OtpVertfy').css('display','');
		$('#Shownum').html('OTP Send To This '+numforotp+' <a href="javascript:void(0)" onclick="EdiNumber()">Edit</a>');
	 }
});
	}else{
		otpError.text('Mobile Number Fields Is Required.');
	}
}
function EdiNumber(){
	$('#MObVertfy').css('display','');
	$('#OtpVertfy').css('display','none');
}

function VeriFyotp(){
var Otp =$('#Otp').val();
var numforotp=$('#numforotp').val();
if(Otp.length>0){
var url ="getAjaxx.php";
$.post(url,{'choice':'veryFyOtp','otp':Otp,"mobile_num":numforotp},function(res){
	
	if(res=='error'){
		$('#CheckOtp').text('Invalid OTP Entered.');
	}else{
	$('#OtpVertfy').css('display','none');
	$('#ChangePwd').css('display','');
	}
});
}else{
	$('#CheckOtp').text('OTP Fields Is Required.');
}
}

function ChangessPwd(){
	var newPwd =$('#newPwd').val();
	var confPwd =$('#confPwd').val();
	var numforotp=$('#numforotp').val();
	if(newPwd.length==0){
		$('#newPwdEror').text('New Password Fields Is Required.');
	}else if(confPwd==0){
		$('#confPwdEror').text('Confirm Password Fields Is Required.');
	}else{
		if(newPwd!=confPwd){
			$('#confPwdEror').text('Confirm Password Not Match.');
		}
		else{
			var url = "getAjaxx.php";
			$.post(url,{"choice":"PasswordChange","mobile_num":numforotp,"password":newPwd},function(res){
				if(res=='error'){
					$('#PwdError').text('Password Reset Failed,Please Try Again.');
				}else{
					newPwd =$('#newPwd').val("");
					 confPwd =$('#confPwd').val("");
					$('#PwdSucess').text('Password Reset Successfully.');
				}

			});
		}
	}
}

</script>
<?php include("footer.php"); ?>
