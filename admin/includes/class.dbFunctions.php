<?php
include_once 'class.Pagination.php';
class Dbfunctions extends Pagination
{
	public $dbcon;	
	//Database connect 
	public function __construct(){		
		$this->dbcon = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD,DB_DATABASE) or die('Oops connection error -> ' . mysqli_connect_error());								
	}

public function getFieldList($tblName, $fldName, $aSeparator = ",", $defaultVal = "", $encloseChar="", $optCondition = "")
	{
		if(trim($optCondition) != "")
		{
			$condition = " WHERE " . $optCondition;
		}
		else
		{
			$condition = "";
		}
		
		$fieldList = "";
	
		$tmpSql = "SELECT " . $fldName . " FROM " . $tblName . " " . $condition;
		//echo("<br>SQL=>".$tmpSql."<br>");
		$rs = mysqli_query($tmpSql);
		if( (!($rs)) || (!($rec=mysqli_fetch_array($rs))) )
		{
			//not found, do nothing
		}
		else
		{
			do 
			{
				if($fieldList != "")
				{
					$fieldList = $fieldList . $aSeparator;
				}
				$fieldList = $fieldList . $encloseChar . $rec[$fldName] . $encloseChar;
			} while(($rec=mysqli_fetch_array($rs)));
		}
		
		if($fieldList == "")
		{
			$fieldList = $defaultVal;
		}

		return $fieldList;
	}
	
	
	
// ************************END************************************************************	

///SECURITY Section Starts Here***********************************************************

//Check security for hacking
	public function checkSecurity($server){    
		if(isset($server['REQUEST_METHOD']) && $server['REQUEST_METHOD']=='POST'){
//			echo "main";exit;
			if(false !== strpos($server['SERVER_NAME'],SERVER_NAME)){      
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//Protect form XSS
	public function checkXss($string='',$stripTag=true,$htmlspecialcharacter=true){
		if($stripTag){
			$string=strip_tags($string);
			$string = str_ireplace( '%3Cscript', '', $string );
		}		
		if($htmlspecialcharacter){
			$string=htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
		}		
		return $string;
	}
	//Check xss
	public function checkXssSqlInjection($string='',$stripTag=true,$htmlspecialcharacter=true,$mysql_real_escape=true){
		if($stripTag){
			$string=strip_tags($string);
		}
		if($htmlspecialcharacter){
			$string=htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
		}
		if($mysql_real_escape){
			$string=mysqli_real_escape_string($this->dbcon,$string);
		}
		return $string;
	}
	/*public function checkXssSqlInjection($string='',$stripTag=true,$htmlspecialcharacter=true,$mysql_real_escape=true){
		if($stripTag){
			$string=strip_tags($string);
		}
		if($htmlspecialcharacter){
			$string=htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
		}
		if($mysql_real_escape){
			$string=mysql_real_escape_string($string);
		}
		return $string;
	}*/
	//Check sqlinjection
	public function checkSqlInjection($string='',$mysql_real_escape=true){		
		if($mysql_real_escape){
			$string=mysqli_real_escape_string($this->dbcon,$string);
		}
		return $string;
	}	
	//Clean xss
	function xss_clean($data){
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do{
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }while ($old_data !== $data);
        // we are done...
        return $data;
}

///SECURITY Section Ends Here*************************************************************
	
	//CHECKING EXISTANCE  IN A TABLE
	public function existsInTable($tblName, $condition)
	{
	
	if(trim($condition) != "")
		{
			$condition = " WHERE " . $condition;
		}
		else
		{
			$condition = "";
		}
		//echo ("select * from " . $tblName . " where " . $condition)."<br>";
		
		$rs = mysqli_query("select * from " . $tblName . $condition);
		if( (!($rs)) || (!($rec=mysqli_fetch_array($rs))) )
		{
			//not found
			return 0;
		}
		else
		{
			//found
			return 1;
		}
	}
// ********************************END****************************************************************************************	

//Next Auto increment value of a table.
public function autoIncrement($tblName,$string, $condition)
{
$query_next = mysqli_query("SHOW TABLE STATUS LIKE '". $tblName."'");
$row_next = mysqli_fetch_array($query_next);
 $next_id = $row_next[Auto_increment] ;//exit;
 return $next_id;
}
// ********************************END****************************************************************************************	


public function getDataFromTable($tblName, $fldName,$optCondition){
		$defaultVal="";		
		if(trim($optCondition) != ""){
			$condition = $optCondition ;
		}else{
			$condition = "";
		}	
		//echo "select " . $fldName . " from " . $tblName . " where " . $condition."<br>";	
		$rs = mysqli_query($this->dbcon,"select " . $fldName . " from " . $tblName . " where " . $condition);
		if( (!($rs)) || (!($rec=mysqli_fetch_array($rs))) ){						
			return $defaultVal;
		}else if(is_null($rec[0])){			
			return $defaultVal;
		}else{		
			return $rec[0];
		}
	}
	
	#######################################################################
	########################## INSERTSET ##################################
	#######################################################################
	public function insertSet($tblName,$string){		
// 	echo "INSERT INTO " . $tblName . " SET " . $string;exit;
		$rs= mysqli_query($this->dbcon,"INSERT INTO  " . $tblName . " SET " .  $string);
		if($rs){
			$lastId=mysqli_insert_id($this->dbcon);
			return $lastId;
		}else{
			return 0;
		}
	}
	#######################################################################
	########################## DELETEFROMTABLE ############################
	#######################################################################
	public function deleteFromTable($tblName, $condition){	
		if(trim($condition) != ""){
			$condition = " WHERE " . $condition;
		}else{
			$condition = "";
		}
		// echo "DELETE FROM " . $tblName . $condition;exit;
		return $rs= mysqli_query($this->dbcon,"DELETE FROM " . $tblName . $condition);
	}
	
	#######################################################################
	########################## UPDATETABLE#################################
	#######################################################################
	public function updateTable($tblName,$string, $condition){
		$condition = " WHERE " . $condition;
	   	$sql="UPDATE " . $tblName . " SET " .  $string . $condition;
		$sql."<br>";//exit;
		//echo "UPDATE " . $tblName . " SET " .  $string . $condition."<br>";	exit;
	   	$rs= mysqli_query($this->dbcon,$sql);
		
	}
	
	##############################################################
	############### Simple Query #################################
	##############################################################
	public function simpleQuery($sql){	
	//echo $sql."<br>";		
		$result=mysqli_query($this->dbcon,$sql);					
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{			
			return array();
		}
	}
	#######################################################################
	########################## FETCH ######################################
	#######################################################################
	public function fetch($tblName,$optCondition="",$optorder="",$optlimit="",$optorderType="ASC"){
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}		
		if(trim($optlimit) != ""){
			$limit = " ".$optlimit;
		}else{
			$limit = "";
		}
		if(trim($optorder) != ""){
			$sql="SELECT * FROM " . $tblName . $condition ." ORDER BY ". $optorder." ".$optorderType. $limit;
		}else{
			$sql="SELECT * FROM " . $tblName . $condition. $limit;
		}
	    //echo $sql;//exit;
		$result = mysqli_query($this->dbcon,$sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{			
			return array();
		}
	}
	
	//FETCH  ROWS FROM A TABLE USING DISTINCT
	function fetchDistinct($tblName,$distinctname,$optCondition="",$optorder="",$optlimit="",$optorderType="ASC") 
	{
		if(trim($optCondition) != "")
		{
		$condition = " WHERE " . $optCondition;
		}
		else
		{
		$condition = "";
		}
		
		if(trim($optlimit) != "")
		{
		$limit = " ".$optlimit;
		}
		else
		{
			$limit = "";
		}
		
		if(trim($optorder) != "")	
		{
		 $sql="SELECT distinct(".$distinctname.") FROM " . $tblName . $condition ." ORDER BY ". $optorder." ".$optorderType. $limit;
		 }
		 else
		 {
		 $sql="SELECT distinct(".$distinctname.") FROM " . $tblName . $condition. $limit;
		 }
		 //echo $sql;
		 $result = mysqli_query($this->dbcon,$sql);
		 if(!$result){
		  trigger_error("Problem selecting data");
		 }
		 while($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
		  $result_array[] = $row;
		 }
		 if(count($result_array)>0)
		 {
		 return $result_array;	
		 }
		 else
		 {
		 $default_val=array();
		 return $default_val;
		 }
		}
	
	################################################################
	######################FETCHING ROWS############################
	################################################################	
	function fetchOrder($tblName,$optCondition="",$orderby="",$field="",$groupby=""){
		if($field==""){
			$sql = "SELECT * FROM ".$tblName;
		}else{
			$sql = "SELECT ".$field." FROM ".$tblName;
		}		
		if(trim($optCondition) != ""){
			$sql = $sql." WHERE " . $optCondition;
		}
		if($groupby != ""){
			$sql = $sql." group by " . $groupby;
		}
		if(trim($orderby) != "" ){
			$sql = $sql." order by " . $orderby;
		}		
// 		echo $sql;
		$result = mysqli_query($this->dbcon,$sql);
	
		if($result){

		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}

	
		if(count(isset($result_array))>0){
			if(!empty($result_array)){
			return $result_array;
			}else{
				$default_val=array();
				return $default_val;	
			}
		}else{
			$default_val=array();
			return $default_val;
		}	
		}else{
			$default_val=array();
			return $default_val;
		}
	}
	# With Limit
	function fetchDatatfrommultipleTable($tblName,$optCondition="",$orderby="",$field="",$optlimit="",$optorderType="ASC"){
		if($field==""){
			$sql = "SELECT * FROM ".$tblName;
		}else{
			$sql = "SELECT ".$field." FROM ".$tblName;
		}		
		if(trim($optCondition) != ""){
			$sql = $sql." WHERE " . $optCondition;
		}
		
		if(trim($orderby) != "" ){
			$sql = $sql." order by " . $orderby;
		}		
		
		if(trim($optorderType)!=""){
			$sql=$sql." ".$optorderType;
		}
		if(trim($optlimit) != ""){
			$sql = $sql.$optlimit;
		}
	//echo $sql;exit;
		$result = mysqli_query($this->dbcon,$sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{
			$default_val=array();
			return $default_val;
		}
	}  
	#############################################################
	################ STRRECORDID ################################
	#############################################################
	function fetchSingle($tblName,$field='*',$optCondition=""){
		if(trim($optCondition)!= ""){
			$sql = "SELECT ".$field." from ".$tblName." WHERE " . $optCondition;
		}else{
			$sql = "SELECT ".$field." from ".$tblName;
		}			
		//echo $sql."<br>";
		$result = mysqli_query($this->dbcon,$sql);
		
		return mysqli_fetch_assoc($result);
	}
	#############################################################
	################ COUNTROWS ##################################
	#############################################################
	function countRows($tblName,$optCondition="",$groupby="") {
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}
		
		if($groupby!=""){
			$sql="SELECT * FROM " . $tblName . $condition." group by ".$groupby;
		}else{
			$sql="SELECT * FROM " . $tblName . $condition;
		}
	    //echo $sql."<br/>";//exit;
		$result = mysqli_query($this->dbcon,$sql);		
		return $result->num_rows;
	}
//********************************END****************************************************************************************	


/*Format Date Time to d-M-Y or any other...*/
	public function formatMyDateTime($a_date, $a_format, $is_time_stamp = 0, $a_default_value = "")
	{
		if(is_null($a_date))
		{
			return($a_default_value);
		}else{
			if($is_time_stamp == 1)
			{
				//--- supplied date time is a TimeStamp, so no conversion required
				$tmpdt_stamp = $a_date;
			}else{
				//--- supplied date time is not a TimeStamp, but a string
				$tmpdt_stamp = strtotime($a_date);
			}
			return(date($a_format, $tmpdt_stamp));
		}
	}
	
	
	
//String cut to a limited words************************************************	
public function cut($string, $max_length){
	if (strlen($string) > $max_length){
		$string = substr($string, 0, $max_length);
		$pos = strrpos($string, " ");
		if($pos === false) {
				return substr($string, 0, $max_length)."...";
		}
		return substr($string, 0, $pos)."...";
	}else{
		return $string;
	}
}	
//***********************************************	


 //calculate years of age (input string: YYYY-MM-DD)
  public function age($birthday){
    list($year,$month,$day) = explode("-",$birthday);
    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0)
      $year_diff--;
    return $year_diff;
  }
  
  
//Text Area formatting************************************************	
public function textArea($string){
	$str = str_replace("\r",'<br>',$string); 
 $str=stripslashes($str);	//exit;
$str=mysqli_real_escape_string($str);
return $str;
}	
//***********************************************	  


//FIND URL OF THE SITE *******************************************************
public function get_server(){
	$protocol = 'http';
	if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') {
		$protocol = 'https';
	}
	$host = $_SERVER['HTTP_HOST'];
	$baseUrl = $protocol . '://' . $host;
	if (substr($baseUrl, -1)=='/') {
		$baseUrl = substr($baseUrl, 0, strlen($baseUrl)-1);
	}
	return $baseUrl;
}

//********************************************************************************

//FIND URL OF THE SITE *******************************************************

public function createStars($green) {
	$white=5-$green;
for($i=1;$i<=$green;$i++)
	{
	echo '<img src="images/green_star.gif" width="16" height="16" align="top">  ';
	}
for($i=1;$i<=$white;$i++)
	{
	echo '<img src="images/white_star.gif" width="16" height="16" align="top">  ';
	}

}
  
public function dateTimeDiff($data_ref){
	$time =  strtotime($data_ref)-time(); // to get the time since that moment
		
	if($time<0){
		echo "CLOSED";
	}else{
		$tokens = array (
		31536000 => 'year',
		2592000 => 'month',
		/*604800 => 'week',*/
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
		);
		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			
			return "Closing in ".$numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
		} 	
	}
}
}
//********************************************************************************

//FIND URL OF THE SITE *******************************************************

function get_server() {
	$protocol = 'http';
	if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') {
		$protocol = 'https';
	}
	$host = $_SERVER['HTTP_HOST'];
	$baseUrl = $protocol . '://' . $host;
	if (substr($baseUrl, -1)=='/') {
		$baseUrl = substr($baseUrl, 0, strlen($baseUrl)-1);
	}
	return $baseUrl;
}

//********************************************************************************

//FETCH SINGLE ROW or specific Column FROM A TABLE (Kishor - 17-09-2011)
function strRecordID($tblName,$field,$optCondition="") 
{
	if(trim($optCondition) != "")
	{
		$sql = "SELECT ".$field." from ".$tblName." WHERE " . $optCondition;
	}
	else
	{
		$sql = "SELECT ".$field." from ".$tblName;
	}
	//echo $sql;
	$result = mysqli_query($sql);
	return mysqli_fetch_array($result);
}
// ********************************END****************************************************************************************	

/*****************Start-Show short string******************/
	function short_str($str, $len, $cut = true)
	{
		if (strlen( $str ) <= $len) return $str;
	   
		return ($cut ? substr( $str, 0, $len) : substr($str, 0, strrpos(substr($str, 0, $len ), ' ' ))) . '...';
	}
	/*****************End-Show short string*******************/
	/***************** Start-  Store special Characters ****************/
	function change($a)
	{
 		$a = str_replace ("'", "`", $a); //$a = str_replace (array("?", "'"), array("??", "''"), $a);
		return $a;
	}
	/***************** End -  Store special Characters ****************/
	
	function generatecombodt($endval, $shval) 
	{
		for ($i=0;$i<=$endval;$i++)
		{
		 
		  if($i<=9)
		  {
		    $i='0'.$i;
		  }
		  
		  if($i==0)
		  {
		    $j='dd';
		  }
		  else
		  {
		  	$j=$i;
		  }
  			if($shval == $i)
				$showDetails .= "<OPTION value=\"$i\" selected>$j</OPTION>"; 
			else
 				$showDetails .= "<OPTION value=\"$i\">$j</OPTION>"; 
		}
 		echo $showDetails;
	}
	
	/************* noof qty combo**************/
	function generatecombomon($endval, $shval) 
	{
		for ($i=1;$i<=$endval;$i++)
		{
			 if($i<=9)
		  {
		    $i='0'.$i;
		  }
		  
		  if($i==0)
		  {
		    $j='dd';
		  }
		  else
		  {
		  	$j=$i;
		  }
			if($shval == $i)
				$showDetails .= "<OPTION value=\"$i\" selected>$i</OPTION>"; 
			else
				$showDetails .= "<OPTION value=\"$i\">$i</OPTION>";
		}
		echo $showDetails;
	}
/************* End -  noof qty combo **************/
	
	/*****Start - Function To get the Month Name******/
	function findmonthname($var)
	{
		if($var == 1)
		{
			$MonthMame='Jan';
		} 
		else if($var == 2)
		{
			$MonthMame='Feb';
		}
		else if($var == 3)
		{
			$MonthMame='Mar';
		} 
		else if($var == 4)
		{
			$MonthMame='Apr';
		} 
		else if($var == 5)
		{
			$MonthMame='May';
		}
		else if($var == 6)
		{
			$MonthMame='Jun';
		}
		else if($var == 7)
		{
			$MonthMame='Jul';
		}
		else if($var == 8)
		{
			$MonthMame='Aug';
		}
		else if($var == 9)
		{
			$MonthMame='Sep';
		}
		else if($var == 10)
		{
			$MonthMame='Oct';
		}
		else if($var == 11)
		{
			$MonthMame='Nov';
		}
		else if($var == 12)
		{
			$MonthMame='Dec';
		}
		return $MonthMame;
	}
	
	/************* noof qty combo**************/
	function generatecomboyear($endval, $shval) 
	{
		for ($i=1910;$i<=$endval;$i++)
		{
			if($shval == $i)
				$showDetails .= "<OPTION value=\"$i\" selected>$i</OPTION>"; 
			else
				$showDetails .= "<OPTION value=\"$i\">$i</OPTION>";
		}
		echo $showDetails;
	}
/************* End -  noof qty combo **************/

	function curPageName() 
	{
		return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
		
?>