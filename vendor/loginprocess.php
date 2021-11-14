<?php 
ob_start();
session_start();
include_once '../admin/includes/class.Main.php';
 $dbf = new User();

 $login=$dbf->fetchSingle("user","*","user_name='$_REQUEST[username]'  and user_type='3'");
 $login_id = $login['user_name'];
 if(empty($login)){

	$login=$dbf->fetchSingle("user","*","email='$_REQUEST[username]'  and user_type='3'");
	$login_id = $login['email'];
   
   if(empty($login)){
	$login=$dbf->fetchSingle("user","*","contact_no='$_REQUEST[username]'  and user_type='3'");
	$login_id = $login['contact_no'];
   
   }
	}

	$password=base64_decode(base64_decode($login['password']));
	if($_REQUEST['username'] ==$login_id && $_REQUEST['password']==$password ){
		//to keep record of the no of login

		$cntlogin=$dbf->fetchSingle("user","*","id='$login[id]' and user_type='3'");
	
		$_SESSION['userid']=$login['id'];
		$_SESSION['email']=$login['email'];
		
		$_SESSION['usertype']="3";
		
		header("Location:dashboard.php");exit;
	}else{
		header("Location:index.php?msg=error");exit;
	}
	
	?>
