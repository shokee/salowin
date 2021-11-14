<?php
include_once '../admin/includes/class.Main.php';
$dbf = new User();


if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='imgdel'){
	$img_id = $_REQUEST['img_id'];
	 $dbf->deleteFromTable("gallery", "gallery_id='$img_id'");
	}
	if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='variDel'){
	 $vari_id = $_REQUEST['vari_id'];
	 $product_id = $_REQUEST['product_id'];
	 $user_id = $_POST['vendor_id'];

	 $GetAttr_id=$dbf->fetchOrder("attribute","attribute_id='$vari_id'","");

	 $Array_attri_id=array();
	 if(!empty($GetAttr_id)){
		foreach($GetAttr_id as $Attri){
		  array_push($Array_attri_id,$Attri['attribute_id']);
		}
		$list_of_attr=implode(',', $Array_attri_id);
	   
		$GetVarition_id=$dbf->fetchOrder("variation","attribute_id IN($list_of_attr)","");
		if(!empty($GetVarition_id)){
		  foreach($GetVarition_id as $varition){
			$dbf->deleteFromTable("price_varition", "find_in_set('$varition[variation_id]',variation_values)");
		  }
		}
	  }
	  $dbf->deleteFromTable("attribute", "attribute_id='$vari_id'");
	  $dbf->deleteFromTable("variation", "attribute_id='$vari_id'");
 
	  $GetAttr_ids=$dbf->fetchOrder("attribute","product_id='$product_id' AND vendor_id='$user_id'","");
	
	  $Array_attri_ids=array();
	  $Cnt_vari_price=0;
	  if(!empty($GetAttr_ids)){
		foreach($GetAttr_ids as $Attris){
		  array_push($Array_attri_ids,$Attris['attribute_id']);
		}
		$list_of_attrs=implode(',', $Array_attri_ids);
	   
		$GetVarition_ids=$dbf->fetchOrder("variation","attribute_id IN($list_of_attrs)","");
		if(!empty($GetVarition_ids)){
		  foreach($GetVarition_ids as $varitions){
		 $Cnt_vari_price+=1;
		  }
		}
	  }
	  if($Cnt_vari_price==0){
						
		$prod_details=$dbf->fetchOrder("product","product_id = '$product_id'","");
	  $array_name=$prod_details['vendor_id'];
	  if($array_name!=""){
   $vendor_id_array=explode(',',$array_name);
   }else{
	 $vendor_id_array=array();
   }
	 
   
		 function remove_element($array,$value) {
	 return array_diff($array, (is_array($value) ? $value : array($value)));
   }
	
   $del_arry = remove_element($vendor_id_array,$user_id);
	$vendor_id=implode(',',$del_arry);
		$dbf->updateTable("product","vendor_id='$vendor_id'", "product_id='$product_id'");

	}
	}
	if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='PriceVariDel'){
	 $price_vari = $_POST['price_vari'];

	 $Varition_value=$dbf->fetchSingle("variations_values",'vendor_id,price_variation_id',"variations_values_id='$price_vari'");
	 $Varition_price=$dbf->fetchSingle("price_varition",'product_id',"price_varition_id='$Varition_value[price_variation_id]'");
	 $dbf->deleteFromTable("variations_values","variations_values_id='$price_vari'");

	 $GetAttr_id=$dbf->fetchOrder("variations_values","price_variation_id='$Varition_value[price_variation_id]' AND vendor_id='$Varition_value[vendor_id]'","");
					if(empty($GetAttr_id)){
					
						$prod_details=$dbf->fetchSingle("product","vendor_id","product_id = '$Varition_price[product_id]'","");
						
					  $array_name=$prod_details['vendor_id'];
					  if($array_name!=""){
				   $vendor_id_array=explode(',',$array_name);
				   }else{
					 $vendor_id_array=array();
				   }
				//    print_r($vendor_id_array);exit;
				   
						 function remove_element($array,$value) {
					 return array_diff($array, (is_array($value) ? $value : array($value)));
				   }
					
				   $del_arry = remove_element($vendor_id_array,$Varition_value['vendor_id']);
					 $vendor_id=implode(',',$del_arry);
						$dbf->updateTable("product","vendor_id='$vendor_id'", "product_id='$Varition_price[product_id]'");
				
					}
	}
?>


<?php
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='getState'){
	 $state_id= $_REQUEST['value'];?>
	  <option value="" >--Select State--</option>
<?php  foreach($dbf->fetchOrder("state","Country_id ='$state_id'","state_id ASC","","") as $stateName){?>
    			<option value="<?=$stateName['state_id']?>" ><?=$stateName['state_name']?></option>
   			   
  <?php }}?>
  <?php if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='getCity'){
	   $state_id= $_REQUEST['value'];?>
	    <option value="" >--Select City--</option>
  <?php foreach($dbf->fetchOrder("city","state_id='$state_id'","city_id ASC","","") as $cityName){?>
    			<option value="<?=$cityName['city_id']?>" ><?=$cityName['city_name']?></option>
   			    <?php }}?>

<?php 
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='AddVariotion'){
	
	$measure = $_POST['measure'];
	$mrp_price = $_POST['mrp_price'];
	$sale_price = $_POST['sale_price'];
	$vendor_id = $_POST['vendor_id'];
	$product = $_POST['product'];
	$contents='';
		$string="price_variation_id='$measure',vendor_id='$vendor_id',mrp_price='$mrp_price',sale_price='$sale_price',product_id='$product'";
		$dbf->insertSet("variations_values",$string);
		foreach( $dbf->fetchOrder("price_varition","product_id='$product'","","","")as $Measures){
			$Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Measures[measure_id]'");
			$VariPrie=$dbf->fetchSingle("variations_values",'*',"price_variation_id='$Measures[price_varition_id]' AND vendor_id='$vendor_id' AND product_id='$product'");
			 $contents.='<tr>
			<td>
			<input type="text" value="'.$Measures['units'].$Measure['unit_name'].'" data-measureid="'.$Measures['price_varition_id'].'" readonly id="MesasuresId'.$Measures['price_varition_id'].'">
			</td>
			<td><input type="text" placeholder="Enter MRP Price" id="MrpPriceId'.$Measures['price_varition_id'].'" value="'.$VariPrie['mrp_price'].'"></td>
		<td><input type="text" placeholder="Enter Sale Price" id="SalesPriceId'.$Measures['price_varition_id'].'" value="'.$VariPrie['sale_price'].'"></td>
			<td><button class="btn btn-primary"  onclick="'.((!empty($VariPrie))?"UpdatePriceVari(".$Measures['price_varition_id'].",".$VariPrie['variations_values_id'].")":"AddPrice(".$Measures['price_varition_id'].")").'"><i class="fa'.((!empty($VariPrie))?" fa-pencil":" fa-plus").'" aria-hidden="true"></i></button></td>
			</tr> ';
		 }
		 echo $contents;exit;

	}

	if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='DeliverBoyUp'){
		$lat = $_POST['lat'];
	   $lng = $_POST['lng'];
	   $dboy = $_POST['dboy'];
	   $dbf->updateTable("user","lat='$lat',lng='$lng'","id='$dboy'");
   }

   if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='GetDboy'){
	$dboy=$_POST['dboy'];
	$Dboy=$dbf->fetchSingle("user","full_name,email,contact_no,profile_image","user_type='5' AND id='$dboy'");
	$contents.=$Dboy['full_name']."!next!";
	$contents.=$Dboy['email']."!next!";
	$contents.=$Dboy['contact_no']."!next!";
	$contents.='<img src="../admin/images/dboy/thumb/'.$Dboy['profile_image'].'" alt="'.$Dboy['full_name'].'" width="50%">';
	echo $contents;exit;
	}

	if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='variations'){
		$Prod_id = $_POST['product_id'];
		$vendor_id = $_POST['vendor_id'];
	   $product=$dbf->fetchSingle("product",'product_name',"product_id='$Prod_id'");
	   $contents.=$product['product_name'].'<small> Of Varitions</small>!next!';
	   $contents.=$Prod_id.'!next!';
	   foreach( $dbf->fetchOrder("price_varition","product_id='$Prod_id'","","","")as $Measures){
		$Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Measures[measure_id]'");
		$VariPrie=$dbf->fetchSingle("variations_values",'*',"price_variation_id='$Measures[price_varition_id]' AND vendor_id='$vendor_id' AND product_id='$Prod_id'");
		 $contents.='<tr>
		<td>
		<input type="text" value="'.$Measures['units'].$Measure['unit_name'].'" data-measureid="'.$Measures['price_varition_id'].'" readonly id="MesasuresId'.$Measures['price_varition_id'].'">
		</td>
		<td><input type="text" placeholder="Enter MRP Price" id="MrpPriceId'.$Measures['price_varition_id'].'" value="'.$VariPrie['mrp_price'].'"></td>
		<td><input type="text" placeholder="Enter Sale Price" id="SalesPriceId'.$Measures['price_varition_id'].'" value="'.$VariPrie['sale_price'].'"></td>
		<td><button class="btn btn-primary"  onclick="'.((!empty($VariPrie))?"UpdatePriceVari(".$Measures['price_varition_id'].",".$VariPrie['variations_values_id'].")":"AddPrice(".$Measures['price_varition_id'].")").'"><i class="fa'.((!empty($VariPrie))?" fa-pencil":" fa-plus").'" aria-hidden="true"></i></button></td>
		</tr> ';
	 }

	 echo $contents;exit;
   } 
 

   if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='UpdateVariotion'){
			
	$measure = $_POST['measure'];
	$mrp_price = $_POST['mrp_price'];
	$sale_price = $_POST['sale_price'];
	$vendor_id = $_POST['vendor_id'];
	$product = $_POST['product'];
	$Variation_id = $_POST['Variation_id'];
	$contents='';
		$string="mrp_price='$mrp_price',sale_price='$sale_price'";
		$dbf->updateTable("variations_values",$string,"variations_values_id='$Variation_id'");
		foreach( $dbf->fetchOrder("price_varition","product_id='$product'","","","")as $Measures){
			$Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Measures[measure_id]'");
			$VariPrie=$dbf->fetchSingle("variations_values",'*',"price_variation_id='$Measures[price_varition_id]' AND vendor_id='$vendor_id' AND product_id='$product'");
			 $contents.='<tr>
			<td>
			<input type="text" value="'.$Measures['units'].$Measure['unit_name'].'" data-measureid="'.$Measures['price_varition_id'].'" readonly id="MesasuresId'.$Measures['price_varition_id'].'">
			</td>
			<td><input type="text" placeholder="Enter MRP Price" id="MrpPriceId'.$Measures['price_varition_id'].'" value="'.$VariPrie['mrp_price'].'"></td>
			<td><input type="text" placeholder="Enter Sale Price" id="SalesPriceId'.$Measures['price_varition_id'].'" value="'.$VariPrie['sale_price'].'"></td>
			<td><button class="btn btn-primary"  onclick="'.((!empty($VariPrie))?"UpdatePriceVari(".$Measures['price_varition_id'].",".$VariPrie['variations_values_id'].")":"AddPrice(".$Measures['price_varition_id'].")").'"><i class="fa'.((!empty($VariPrie))?" fa-pencil":" fa-plus").'" aria-hidden="true"></i></button></td>
			</tr> ';
		 }
		 echo $contents;exit;
   }
   if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='getSubcate'){
	$cate_id = $_POST['value'];
	$subcategory = $_POST['subcategory'];
	
	?>
	<option value="">~~Select Subcategory~~</option>
	<?php 
 foreach($dbf->fetchOrder("product_catagory_2","product_catagory_1_id='$cate_id'","product_catagory_2_id ASC","product_catagory_2_name,product_catagory_2_id","") as $stateName){?>
		<option value="<?=$stateName['product_catagory_2_id']?>" <?= ($subcategory==$stateName['product_catagory_2_id'])?"selected":""?>><?=$stateName['product_catagory_2_name']?></option>
	<?php
		}
   }
   if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='getSubcate2'){
	$cate_id = $_POST['value'];
	$subcategory2 = $_POST['subcategory2'];
	?>
	<option value="">~~Select Subcategory2~~</option>
	<?php 
 foreach($dbf->fetchOrder("product_catagory_3","product_catagory_2_id='$cate_id'","product_catagory_3_name ASC","product_catagory_3_name,product_catagory_3_id","") as $stateName){?>
		<option value="<?=$stateName['product_catagory_3_id']?>" <?= ($subcategory2==$stateName['product_catagory_3_id'])?"selected":""?>><?=$stateName['product_catagory_3_name']?></option>
	<?php
		}
   }

   if(isset($_REQUEST['action']) && $_REQUEST['action']=='GetGiftDetails'){
	$gift_id = $_POST['gift_id'];
	$Gift=$dbf->fetchSingle("gifts",'*',"gifts_id='$gift_id'");
	echo $Gift['min']."-".$Gift['max']."!next!".$Gift['name'].'!next!'.$Gift['price'].'!next!'.$Gift['img'].'!next!'.$Gift['status'];
   }
?>