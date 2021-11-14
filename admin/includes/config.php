<?php

/*if($_SERVER['REMOTE_ADDR']=="127.0.0.1")
 {*/ 
 define('DB_SERVER', 'localhost');  
 define('DB_USERNAME', 'salowcbm_salowin_new');
 define('DB_PASSWORD', 'I4[G7T@3TzTU');
 define('DB_DATABASE', 'salowcbm_salowin_new');
 
 /*define('DB_SERVER', 'localhost');  
 define('DB_USERNAME', 'apolloal_ktravel');
 define('DB_PASSWORD', 'kanede@321~');
 define('DB_DATABASE', 'apolloal_kanede');*/
 
 //Javior Database detail----------
 /*define('DB_SERVER', 'localhost');  
 define('DB_USERNAME', 'handelst_schoolA');
 define('DB_PASSWORD', 'school@2017~');
 define('DB_DATABASE', 'handelst_school');*/
 
 ################## Constats ###################
 define('PROJECT_NAME','ecomerce');
 define('SERVER_NAME','localhost');
 //define('SERVER_NAME','http://agenciahandel.com');
 ################## Constats ###################
 
/* }
 else
 {
	 
 define('DB_SERVER', 'localhost');  
 define('DBUSER', 'cybertro_crzuser');
 define('DBPASS', 'crazzyuser123');
 define('DBNAME', 'cybertro_crazzy');
 
 /*define('DB_SERVER', 'localhost');  
 define('DBUSER', 'yourdiv1_ydadmin');
 define('DBPASS', 'z1ntpRLS~F6c');
 define('DBNAME', 'crz');
 
 }*/

class DB_Class
{
	function __construct()
	{
		$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD,DB_DATABASE) or die('Oops connection error -> ' . mysqli_connect_error());
	}
}


/**********************General WebSite Settings************************************/
//define('DATE_FORMAT', 'd-M-Y');
//define('DATE_TIME_FORMAT', 'd-M-Y, h:i a');
date_default_timezone_set("UTC");
?>
<?php ini_set("display_errors",0);?>