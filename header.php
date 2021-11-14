<?php
		ob_start();
		session_start();
		include_once "admin/includes/class.Main.php";
		$dbf=new User();
		?> 
  
    <?php
	
	
	if(	isset($_GET['phone_'])){
		
		$get_contact=$_GET['phone_'];
		$get_userDtls=$dbf->fetchSingle("user",'*',"contact_no='$get_contact'");
		if(!empty($get_userDtls)){
			$_SESSION['userid']=$get_userDtls[id];
			$_SESSION['usertype']='4';
		}else{
			echo "This Number is not registered.!";
			
			exit();
		}
		
		}
	
	
	
    $page=$dbf->fetchSingle("settingg","*","settingg_id='1'");
	$contact=$dbf->fetchSingle("cmc","*","id='8'");
          
 

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
  
     if(isset($_SESSION['userid'])==""){
    $user_ip = getUserIP();
   }else{
    $user_ip = $_SESSION['userid'];
   }

    ?>
    <html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $page['title'];?> | <?php echo $page['tagline'];?></title>
<link href="admin/images/<?php echo $page['logo'];?>" rel="shortcut icon"   />

  <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/uikit.min.css" />
        <script src="js/uikit.min.js"></script>
        <script src="js/uikit-icons.min.js"></script>
        
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/jquery.simplePagination.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">

  <?php
    $Arra_json=array();
    foreach($dbf->fetchOrder("product","product_id<>0","","","") as $valProd){ 
      $single_list=array('label'=>$valProd['product_name'],'value'=>'single-product-page.php?editId='.base64_encode($valProd['product_id']));
      array_push($Arra_json,$single_list);
    
  //$searVar .='"'.$valProd['product_name'].'"'.',';

}
?>


  <!-- Serch Bar -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  <!-- Serch Bar -->
  
  <script>
  $(document).ready(function() {
   
      $("input#myInput").autocomplete({
      source: <?php print_r( json_encode($Arra_json))?>,
      focus: function (event, ui) {
        $(event.target).val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $(event.target).val(ui.item.label);
        window.location = ui.item.value;
        return false;
      }
    });
    });
  </script>


</head>

<body>
<div class="uk-card uk-card-default uk-card-small  uk-card-body header_fixed" style="padding:10px;     box-shadow: 0 5px 15px rgb(0 0 0 / 0%);">
   
     <div class="uk-grid-small" uk-grid>
     
       
        <div class="uk-width-expand">
                        	<form class="uk-search uk-search-default topsearch uk-width-1-1"  action="search.php">
                                    <a href="" class="uk-search-icon-flip" uk-search-icon></a>
                                    <input class="usi uk-width-1-1 " type="text" id="myInput" placeholder="Search Product" name="s" >
                                </form>
            
        </div>
       
     </div>
     </div>
      <div style="padding:45px;"></div>
        <?php 
    $profile=$dbf->fetchSingle("user",'wallet',"id='$user_ip'");
    if($profile['wallet'] < 0 || $profile['wallet'] == ''){
        
        $dbf->updateTable("user","wallet=0","id='$user_ip'");
        
    }
    ?>