<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
 $dbf = new User();

 $login=$dbf->fetchSingle("user","*","user_name='$_REQUEST[username]'  and user_type IN (1,2) AND status='1'");
	// print_r($login);exit;
	$password=base64_decode(base64_decode($login['password']));
	if($_REQUEST['username'] ==$login['user_name'] && $_REQUEST['password']==$password ){
		//to keep record of the no of login

		$cntlogin=$dbf->fetchSingle("admin","*","id='$login[id]'  and user_type IN (1,2)");
	
		$_SESSION['userid']=$login['id'];
		$_SESSION['email']=$login['email'];
		$_SESSION['city']=$login['city_id'];
		$_SESSION['usertype']=$login['user_type'];
		
		header("Location:dashboard.php");exit;
	}else{
		header("Location:index.php?msg=error");exit;
	}
	
	?>
