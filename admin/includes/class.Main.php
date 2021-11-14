<?php
include_once 'config.php';
include_once 'class.dbFunctions.php';

//Involves Any User operations*********************************************************************************************************************
class User extends Dbfunctions
{

//Registration process 
public function register_user($company_id, $email, $password)
{
$password = md5($password);
$sql = mysqli_query("SELECT id from users WHERE email = '$email'");
$no_rows = mysqli_num_rows($sql);
if ($no_rows == 0)
{
$result = mysqli_query("INSERT INTO users values ('', '$company_id','$email','$password')") or die(mysqli_error());
return $result;
}
else
{
return FALSE;
}
}

// Login process
public function check_login($emailusername, $password)
{
//$password = md5($password);
$result = mysqli_query("SELECT * from admin WHERE email  = '$emailusername'  and password = '$password' AND active_status='1'");
$admin_data = mysqli_fetch_array($result);
$no_rows = mysqli_num_rows($result);
if ($no_rows == 1)
{
$_SESSION['login'] = true;
	
$_SESSION['admin_id'] = $admin_data['id'];
return TRUE;
}
else
{
return FALSE;
}
}
// Getting name
public function get_fullname($uid)
{
$result = mysqli_query("SELECT name FROM users WHERE uid = $uid");
$user_data = mysqli_fetch_array($result);
echo $user_data['name'];
}

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}



// Getting email
public function get_email($uid)
{
$result = mysqli_query("SELECT email FROM users WHERE id = $uid");
$user_data = mysqli_fetch_array($result);
echo $user_data['email'];
}

// Getting session 
public function get_session()
{
return $_SESSION['login'];
}

// Logout 
public function user_logout()
{
$_SESSION['login'] = FALSE;
session_destroy();
}

}
//**************************************************************************************************************************************************************

//Class for Database oriented Functions*****************************************************************************************************************************

?>