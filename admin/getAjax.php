<?php
include_once 'includes/class.Main.php';
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='imgdel'){
	$img_id = $_REQUEST['img_id'];
	 $dbf->deleteFromTable("gallery", "gallery_id='$img_id'");
	}
	if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='variDel'){
	 $vari_id = $_REQUEST['vari_id'];
	 $dbf->deleteFromTable("attribute", "attribute_id='$vari_id'");
	 $dbf->deleteFromTable("variation", "attribute_id='$vari_id'");
	 $dbf->deleteFromTable("variation", "attribute_id='$vari_id'");
	}
	if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='PriceVariDel'){
	 $price_id = $_REQUEST['price_id'];
	 $dbf->deleteFromTable("price_varition", " 	price_varition_id='$price_id'");
	}
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
   			    <?php }}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='changeArea'){
	   $state_id= $_REQUEST['value'];
	   //$pinDtls=$dbf->fetchSingle("pincode",'*',"pincode_id='$state_id'");
	   ?>
	    <option value="" >--Select Vendor--</option>
  <?php foreach($dbf->fetchOrder("user","pin='$state_id'","id ASC","","") as $cityName){?>
    			<option value="<?=$cityName['id']?>" ><?=$cityName['full_name']?></option>
   			    <?php }}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='changPin'){
$City_id = $_REQUEST['value'];
$pin = $_REQUEST['pin'];
    ?>
<option value="">--Select Pincode--</option>
<?php 
foreach ($dbf->fetchOrder("pincode","city_id='$City_id'","","","") as $Pincode) {
?>
<option value="<?= $Pincode['pincode_id']?>"  <?= ($pin==$Pincode['pincode_id'])?"selected":""?>><?= $Pincode['pincode']?></option>
<?php }}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='getProduct'){
	   $vendorId=$_REQUEST['value'];
    foreach($dbf->fetchOrder("product","vendor_id='$vendorId'","product_id ASC","","") as $resBanner){?>
  <tr>
     <td><button type="button" id="itemselected<?= $resBanner['product_id'];?>" onclick="addItemPrice(this.value)" name="menu[]" value="<?= $resBanner['product_id'];?>" >+</button></td>
     <td><?= $resBanner['product_name'];?> </td>
    <!--<td>  Rs. <ins><?= $resBanner['sales_price'];?></ins></td>-->
  </tr>
  <?php  } }
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='purchae_Rate'){
	   $prod_id=$_REQUEST['prod_id'];
    $proDetails=$dbf->fetchSingle("product",'*',"product_id='$prod_id'");
    echo $proDetails['product_name'].'!next! <option value="">-Select Variation-</option>';
    foreach($dbf->fetchOrder("price_varition","product_id='$prod_id'","price_varition_id ASC","","") as $resBanner){
    $unit=$dbf->fetchSingle("units",'*',"unit_id='$resBanner[measure_id]'");
    ?>
    <option value="<?= $resBanner['price_varition_id']?>"><?= $resBanner['units']?><?= $unit['unit_name']?></option>
  <?php } } 
  if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='getProPrice'){
      $variationId=$_REQUEST['value'];
      $proDetails=$dbf->fetchSingle("variations_values",'*',"price_variation_id='$variationId'");
      echo $proDetails['sale_price'];
  }
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='AddVariotion'){
	$unit = $_POST['unit'];
	$measure = $_POST['measure'];
	$product_id = $_POST['product_id'];
	$cntPrice=$dbf->countRows("price_varition","units='$unit' AND product_id='$product_id' AND measure_id='$measure'");
	if($cntPrice==0){
		$string="units='$unit',product_id='$product_id',measure_id='$measure'";
		$dbf->insertSet("price_varition",$string);
		foreach($dbf->fetchOrder("price_varition","product_id='$product_id'","price_varition_id DESC ","","") 
		as $VariPrie){ 
			 $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$VariPrie[measure_id]'"); ?>
		 <tr id="PriceVari<?=$VariPrie['price_varition_id']?>">
		 <td><?= $VariPrie['units']?></td>
		 <td><?= $Measure['unit_name']?></td>
		 <td><button class="btn btn-danger" onclick="PriceVar(<?=$VariPrie['price_varition_id']?>)"><i class="fa fa-trash" aria-hidden="true"></i>
</button></td>
		 </tr> 
		<?php }
	}else{
		echo"error";
	}

	}
	if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='PriceVariDel'){
		$price_vari = $_POST['price_vari'];
		$product_id =$_POST['product_id'];
		$dbf->deleteFromTable("price_varition","price_varition_id='$price_vari'");
   
		foreach($dbf->fetchOrder("price_varition","product_id='$product_id'","price_varition_id DESC ","","") 
		as $VariPrie){ 
			 $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$VariPrie[measure_id]'"); ?>
		 <tr id="PriceVari<?=$VariPrie['price_varition_id']?>">
		 <td><?= $VariPrie['units']?></td>
		 <td><?= $Measure['unit_name']?></td>
		 <td><button class="btn btn-danger" onclick="PriceVar(<?=$VariPrie['price_varition_id']?>)"><i class="fa fa-trash" aria-hidden="true"></i>
</button></td>
		 </tr> 
		<?php }
	   }
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='getCat'){
	 $product_id= $_REQUEST['value'];?>
	 
	  <option value="" >--Select sub-Category--</option>
<?php  foreach($dbf->fetchOrder("product_catagory_2","product_catagory_1_id ='$product_id'","product_catagory_2_id ASC","","") as $procatName){?>
    			<option value="<?=$procatName['product_catagory_2_id']?>" ><?=$procatName['product_catagory_2_name']?></option>
   			   
  <?php } }
  if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='get2ndCat'){
	 $product_id= $_REQUEST['value'];?>
	 
	  <option value="" >--Select 2nd sub-Category--</option>
<?php  foreach($dbf->fetchOrder("product_catagory_3","product_catagory_2_id ='$product_id'","product_catagory_3_id ASC","","") as $procatName){?>
    			<option value="<?=$procatName['product_catagory_3_id']?>" ><?=$procatName['product_catagory_3_name']?></option>
   			   
  <?php } }
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
	   if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='DeliverBoyUp'){
		    $lat = $_POST['lat'];
		   $lng = $_POST['lng'];
		   $dboy = $_POST['dboy'];
		   $dbf->updateTable("user","lat='$lat',lng='$lng'","id='$dboy'");
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
	   if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='variations'){
		 $Prod_id = $_POST['product_id'];
		$product=$dbf->fetchSingle("product",'product_name',"product_id='$Prod_id'");
		$contents.=$product['product_name'].'<small> Of Varitions</small>!next!';
		$contents.=$Prod_id.'!next!';
	 foreach($dbf->fetchOrder("price_varition","product_id='$Prod_id'","price_varition_id DESC ","","") 
as $VariPrie){ 
	 $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$VariPrie[measure_id]'");
 $contents.='<tr id="PriceVari'.$VariPrie['price_varition_id'].'">
 <td>'.$VariPrie['units'].'</td>
 <td>'.$Measure['unit_name'].'</td>
 <td><button class="btn btn-danger" onclick="PriceVar('.$VariPrie['price_varition_id'].','.$Prod_id.')" type="button"><i class="fa fa-trash" aria-hidden="true"></i>
</button>
</td>
 </tr>';
	} 
	echo $contents;exit;
 }  
 if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='Shops'){
	$pin = $_REQUEST['pin'];
	$shop = $_POST['shop'];
		?>
	<option value="">--Select Shop--</option>
	<?php 
	foreach ($dbf->fetchOrder("user","pin='$pin' AND user_type='3'","","","") as $Shops) {
	?>
	<option value="<?= $Shops['id']?>" <?= ($shop==$Shops['id'])?"selected":""?>><?= $Shops['shop_name']?></option>
<?php }}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='GetDboy'){
$dboy=$_POST['dboy'];
$Dboy=$dbf->fetchSingle("user","full_name,email,contact_no,profile_image","user_type='5' AND id='$dboy'");
$content.=$Dboy['full_name']."!next!";
$content.=$Dboy['email']."!next!";
$content.=$Dboy['contact_no']."!next!";
$content.='<img src="images/dboy/thumb/'.$Dboy['profile_image'].'" alt="'.$Dboy['full_name'].'" width="50%">';
echo $content;exit;
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='GetTodayDeals'){
	$product_id=$_POST['product_id'];
	$pin=$_POST['pin'];
	$product=$dbf->fetchSingle("product","today_dealing_date_time","product_id='$product_id'");
	
	$product=$dbf->fetchSingle("dealsof_of_day","datetime","product_id='$product_id' AND pin_id='$pin'");

	$date = date('Y-m-d',strtotime($product['datetime']));
	$time = date('H:i',strtotime($product['datetime']));
	echo $date.'!next!'.$time;
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='UpdateSts'){
	$product_id=$_POST['prod_id'];
	$pin=$_POST['pin'];
	$city=$_POST['city'];
	$status=$_POST['status'];
	if($status=='1'){
		$dbf->insertSet("today_trending","product_id='$product_id',city_id='$city',pin_id='$pin'");
	}else{
		$dbf->deleteFromTable("today_trending","product_id='$product_id' AND city_id='$city' AND pin_id='$pin'");
	}
	$Todaytrending=$dbf->fetchSingle("today_trending","*","product_id='$product_id' AND pin_id='$pin'");
 	if(empty($Todaytrending)){?>
		<button class="btn btn-danger" onclick="UpdateTrending(<?= $product_id ?>,1)">Block</button>
		<?php }else{?>
			<button class="btn btn-success" onclick="UpdateTrending(<?=$product_id?>,0)">Active</button>
	<?php  }
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='RemoveTrending'){
	$trend_id=$_POST['trend_id'];
	$dbf->deleteFromTable("today_trending","today_trending_id='$trend_id'");
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='GetAllRoles'){
 $id  = $_POST['id'];
  $Jobs_assign= $dbf->fetchSingle("user",'roles',"id='$id'");
	$User_role = explode(',',$Jobs_assign['roles']);
	
	$content=' <form action="" method="post">
	<h3 class="text-success text-center" id="JobsResp"></h3>
	<ul>
	<li><input type="checkbox"  class="jobs" value="1" name="roles[]" '.((in_array('1',$User_role)?"checked":"")).'>Dashboard </li>
	<li> <input type="checkbox" value="2" class="jobs" name="roles[]" '.((in_array('2',$User_role)?"checked":"")).'> All Orders
	<ul>
	<li> <input type="checkbox" value="3" class="jobs" name="roles[]" '.((in_array('3',$User_role)?"checked":"")).'> Orders
	  <ul>
	  <li> <input type="checkbox" value="3.1" class="jobs" name="roles[]" '.((in_array('3.1',$User_role)?"checked":"")).'>Status</li>
	  </ul>
	</li>
	<li><input type="checkbox" value="4" class="jobs" name="roles[]" '.((in_array('4',$User_role)?"checked":"")).'>Returns Orders 
	  <ul>
	  <li><input type="checkbox" value="4.1" class="jobs" name="roles[]" '.((in_array('4.1',$User_role)?"checked":"")).'>Status </li>
	  </ul>
	</li>
	</ul>
	</li>
	<li>
	<input type="checkbox" value="5" class="jobs" name="roles[]" '.((in_array('5',$User_role)?"checked":"")).'> Manage Products
	<ul>
	<li><input type="checkbox" value="6"  class="jobs" name="roles[]" '.((in_array('6',$User_role)?"checked":"")).'>Products
	  <ul>
	  <li><input type="checkbox" value="6.1" name="roles[]" '.((in_array('6.1',$User_role)?"checked":"")).'>Add<input type="checkbox" value="6.2" name="roles[]" '.((in_array('6.2',$User_role)?"checked":"")).'>Upload<input type="checkbox" value="6.3" name="roles[]" '.((in_array('6.3',$User_role)?"checked":"")).'>Download
	  <input type="checkbox" value="6.4" name="roles[]" '.((in_array('6.4',$User_role)?"checked":"")).'>Gallery<br><input type="checkbox" value="6.5" name="roles[]" '.((in_array('6.5',$User_role)?"checked":"")).'>Variations<input type="checkbox" value="6.6" name="roles[]" '.((in_array('6.6',$User_role)?"checked":"")).'>Status
	  <input type="checkbox" value="6.7" name="roles[]" '.((in_array('6.7',$User_role)?"checked":"")).'>Action
	 </li>
	  </ul>
	</li>
	<li><input type="checkbox" value="7"  class="jobs" name="roles[]" '.((in_array('7',$User_role)?"checked":"")).'>Category
	  <ul>
		<li><input type="checkbox" value="7.1" name="roles[]" '.((in_array('7.1',$User_role)?"checked":"")).'>Add<input type="checkbox" value="7.2" name="roles[]" '.((in_array('7.2',$User_role)?"checked":"")).'>Upload <input type="checkbox" value="7.3" name="roles[]" '.((in_array('7.3',$User_role)?"checked":"")).'>Download
		  <br><input type="checkbox" value="7.4" name="roles[]" '.((in_array('7.4',$User_role)?"checked":"")).'>Edit<input type="checkbox" value="7.5" name="roles[]" '.((in_array('7.5',$User_role)?"checked":"")).'>Delete
		</li>
	  
	  </ul>
	</li>
	<li><input type="checkbox" value="8"  class="jobs" name="roles[]" '.((in_array('8',$User_role)?"checked":"")).'>Sub Category
	<ul>
	  <li>
	  <input type="checkbox" value="8.1"  class="jobs" name="roles[]" '.((in_array('8.1',$User_role)?"checked":"")).'>Add <input type="checkbox" value="8.2"  class="jobs" name="roles[]" '.((in_array('8.2',$User_role)?"checked":"")).'>Download <input type="checkbox" value="8.3"  class="jobs" name="roles[]" '.((in_array('8.3',$User_role)?"checked":"")).'>Edit<br>
	  <input type="checkbox" value="8.4"  class="jobs" name="roles[]" '.((in_array('8.4',$User_role)?"checked":"")).'>Delete
	  </li>
	</ul>
	</li>
	<li><input type="checkbox" value="9"  class="jobs" name="roles[]" '.((in_array('9',$User_role)?"checked":"")).'>Sub Category 2
	<ul>
	  <li> <input type="checkbox" value="9.1"  class="jobs" name="roles[]" '.((in_array('9.1',$User_role)?"checked":"")).'>Add <input type="checkbox" value="9.2"  class="jobs" name="roles[]" '.((in_array('9.2',$User_role)?"checked":"")).'>Download<input type="checkbox" value="9.3"  class="jobs" name="roles[]" '.((in_array('9.3',$User_role)?"checked":"")).'>Edit <input type="checkbox" value="9.4"  class="jobs" name="roles[]" '.((in_array('9.4',$User_role)?"checked":"")).'>Delete</li>
	</ul>
	</li>
	<li><input type="checkbox" value="10" class="jobs" name="roles[]" '.((in_array('10',$User_role)?"checked":"")).'>Brand
	<ul>
	  <li><input type="checkbox" value="10.1" class="jobs" name="roles[]" '.((in_array('10.1',$User_role)?"checked":"")).'>Add <input type="checkbox" value="10.2" class="jobs" name="roles[]" '.((in_array('10.2',$User_role)?"checked":"")).'>Upload <input type="checkbox" value="10.3" class="jobs" name="roles[]" '.((in_array('10.3',$User_role)?"checked":"")).'>Status<br>
	  <input type="checkbox" value="10.4" class="jobs" name="roles[]" '.((in_array('10.4',$User_role)?"checked":"")).'>Edit <input type="checkbox" value="10.5" class="jobs" name="roles[]" '.((in_array('10.5',$User_role)?"checked":"")).'>Delete
	  </li>
	</ul>
	</li>
	<li><input type="checkbox" value="11" class="jobs" name="roles[]" '.((in_array('11',$User_role)?"checked":"")).'>Measure Ment
	<ul>
	  <li><input type="checkbox" value="11.1" class="jobs" name="roles[]" '.((in_array('11.1',$User_role)?"checked":"")).'>Add <input type="checkbox" value="11.2" class="jobs" name="roles[]" '.((in_array('11.2',$User_role)?"checked":"")).'>Edit <input type="checkbox" value="11.3" class="jobs" name="roles[]" '.((in_array('11.3',$User_role)?"checked":"")).'>Delete</li>
	</ul>
	</li>
	</ul>
	</li>
	<li> <input type="checkbox" value="12" class="jobs" name="roles[]" '.((in_array('12',$User_role)?"checked":"")).'>Customer
	<ul>
	  <li><input type="checkbox" value="12.1" class="jobs" name="roles[]" '.((in_array('12.1',$User_role)?"checked":"")).'>Status
	  <input type="checkbox" value="12.2" class="jobs" name="roles[]" '.((in_array('12.2',$User_role)?"checked":"")).'>Action</li>
	</ul>
	<li> <input type="checkbox" value="13" class="jobs" name="roles[]" '.((in_array('13',$User_role)?"checked":"")).'>Vendor
	<ul>
	  <li><input type="checkbox" value="13.1" class="jobs" name="roles[]" '.((in_array('13.1',$User_role)?"checked":"")).'>Add Vendor
	  <input type="checkbox" value="13.2" class="jobs" name="roles[]" '.((in_array('13.2',$User_role)?"checked":"")).'>Status
	  <input type="checkbox" value="13.3" class="jobs" name="roles[]" '.((in_array('13.3',$User_role)?"checked":"")).'>Action
	  </li>
  </ul>
  </li>
	  <li> <input type="checkbox" value="14" class="jobs" name="roles[]" '.((in_array('14',$User_role)?"checked":"")).'>Sub Admin
	 <ul>
	  <li><input type="checkbox" value="14.1" class="jobs" name="roles[]" '.((in_array('14.1',$User_role)?"checked":"")).'>Add
	  <input type="checkbox" value="14.2" class="jobs" name="roles[]" '.((in_array('14.2',$User_role)?"checked":"")).'>Status
	  <input type="checkbox" value="14.3" class="jobs" name="roles[]" '.((in_array('14.3',$User_role)?"checked":"")).'>Action
	  <input type="checkbox" value="14.4" class="jobs" name="roles[]" '.((in_array('14.4',$User_role)?"checked":"")).'>Role
	  </li>
	</ul>

	<li>
	<input type="checkbox" value="15" class="jobs" name="roles[]" '.((in_array('15',$User_role)?"checked":"")).'>Location
	  <ul>
	  <li>
	  <input type="checkbox" value="16" class="jobs" name="roles[]" '.((in_array('16',$User_role)?"checked":"")).'>Country
	  <ul>
		<li>
		<input type="checkbox" value="16.1" class="jobs" name="roles[]" '.((in_array('16.1',$User_role)?"checked":"")).'>Add
		<input type="checkbox" value="16.2" class="jobs" name="roles[]" '.((in_array('16.2',$User_role)?"checked":"")).'>Edit
		</li>
	  </ul>
	  </li>
	  <li>
	  <input type="checkbox" value="17" class="jobs" name="roles[]" '.((in_array('17',$User_role)?"checked":"")).'>State
	  <ul>
		<li>
		<input type="checkbox" value="17.1" class="jobs" name="roles[]" '.((in_array('17.1',$User_role)?"checked":"")).'>Add
		<input type="checkbox" value="17.2" class="jobs" name="roles[]" '.((in_array('17.2',$User_role)?"checked":"")).'>Edit
		</li>
	  </ul>
	  </li>
	  <li>
	  <input type="checkbox" value="18" class="jobs" name="roles[]" '.((in_array('18',$User_role)?"checked":"")).'>City
	  <ul>
		<li>
		<input type="checkbox" value="18.1" class="jobs" name="roles[]" '.((in_array('18.1',$User_role)?"checked":"")).'>Add
		<input type="checkbox" value="18.2" class="jobs" name="roles[]" '.((in_array('18.2',$User_role)?"checked":"")).'>Edit
		</li>
	  </ul>
	  </li>
	  <li>
	  <input type="checkbox" value="19" class="jobs" name="roles[]" '.((in_array('19',$User_role)?"checked":"")).'>Pincode
	  <ul>
		<li>
		<input type="checkbox" value="19.1" class="jobs" name="roles[]" '.((in_array('19.1',$User_role)?"checked":"")).'>Add
		<input type="checkbox" value="19.2" class="jobs" name="roles[]" '.((in_array('19.2',$User_role)?"checked":"")).'>Status
		<input type="checkbox" value="19.3" class="jobs" name="roles[]" '.((in_array('19.3',$User_role)?"checked":"")).'>Edit
		</li>
	  </ul>
	  </li>
	  <li>
	  <input type="checkbox" value="34" class="jobs" name="roles[]" '.((in_array('34',$User_role)?"checked":"")).'>Area
	  <ul>
		<li>
		<input type="checkbox" value="34.1" class="jobs" name="roles[]" '.((in_array('34.1',$User_role)?"checked":"")).'>Add
		<input type="checkbox" value="34.2" class="jobs" name="roles[]" '.((in_array('34.2',$User_role)?"checked":"")).'>Status
		<input type="checkbox" value="34.3" class="jobs" name="roles[]" '.((in_array('34.3',$User_role)?"checked":"")).'>Edit
		</li>
	  </ul>
	  </li>
	  </ul>
	</li>
	</li>

	<li>
	<input type="checkbox" value="20" class="jobs" name="roles[]" '.((in_array('20',$User_role)?"checked":"")).'>Contain Management
	<ul>
	  <li>
	  <input type="checkbox" value="21" class="jobs" name="roles[]" '.((in_array('21',$User_role)?"checked":"")).'>All Pages
		<ul>
		  <li><input type="checkbox" value="21.1" class="jobs" name="roles[]" '.((in_array('21.1',$User_role)?"checked":"")).'>Edit</li>
		  <li><input type="checkbox" value="21.2" class="jobs" name="roles[]" '.((in_array('21.2',$User_role)?"checked":"")).'>Delete</li>
		</ul>
	  </li>
	  <li>
	  <input type="checkbox" value="22" class="jobs" name="roles[]" '.((in_array('22',$User_role)?"checked":"")).'>Banner Management
		<ul>
		  <li><input type="checkbox" value="22.1" class="jobs" name="roles[]" '.((in_array('22.1',$User_role)?"checked":"")).'>Add</li>
		  <li><input type="checkbox" value="22.2" class="jobs" name="roles[]" '.((in_array('22.2',$User_role)?"checked":"")).'>Delete</li>
		</ul>
	  </li>
	  <li>
	  <input type="checkbox" value="23" class="jobs" name="roles[]" '.((in_array('23',$User_role)?"checked":"")).'>Advertising Managements
		<ul>
		  <li><input type="checkbox" value="23.1" class="jobs" name="roles[]" '.((in_array('23.1',$User_role)?"checked":"")).'>Add</li>
		  <li><input type="checkbox" value="23.2" class="jobs" name="roles[]" '.((in_array('23.2',$User_role)?"checked":"")).'>Edit</li>
		  <li><input type="checkbox" value="23.3" class="jobs" name="roles[]" '.((in_array('23.3',$User_role)?"checked":"")).'>Delete</li>
		</ul>
	  </li>
	  <li>
	  <input type="checkbox" value="24" class="jobs" name="roles[]" '.((in_array('24',$User_role)?"checked":"")).'>Testimonial
		<ul>
		  <li><input type="checkbox" value="24.1" class="jobs" name="roles[]" '.((in_array('24.1',$User_role)?"checked":"")).'>Add</li>
		  <li><input type="checkbox" value="24.2" class="jobs" name="roles[]" '.((in_array('24.2',$User_role)?"checked":"")).'>Delete</li>
		</ul>
	  </li>
	  <li>
	  <input type="checkbox" value="25" class="jobs" name="roles[]" '.((in_array('25',$User_role)?"checked":"")).'>Deals Of The Day
		<ul>
		  <li><input type="checkbox" value="25.1" class="jobs" name="roles[]" '.((in_array('25.1',$User_role)?"checked":"")).'>Add</li>
		</ul>
	  </li>
	  <li>
	  <input type="checkbox" value="35" class="jobs" name="roles[]" '.((in_array('35',$User_role)?"checked":"")).'>Today Trending
		<ul>
		  <li><input type="checkbox" value="35.1" class="jobs" name="roles[]" '.((in_array('35.1',$User_role)?"checked":"")).'>Add</li>
		  <li><input type="checkbox" value="35.2" class="jobs" name="roles[]" '.((in_array('35.2',$User_role)?"checked":"")).'>Status</li>
		</ul>
	  </li>
	</ul>
	</li>
	</li>
  <li>
  <input type="checkbox" value="26" class="jobs" name="roles[]" '.((in_array('26',$User_role)?"checked":"")).'>Reports
  <ul>
		<li>
		<input type="checkbox" value="26.1" class="jobs" name="roles[]" '.((in_array('26.1',$User_role)?"checked":"")).'>Order Details
		<input type="checkbox" value="26.2" class="jobs" name="roles[]" '.((in_array('26.2',$User_role)?"checked":"")).'>Make Clearance
		<input type="checkbox" value="26.3" class="jobs" name="roles[]" '.((in_array('26.3',$User_role)?"checked":"")).'>All Clearance Details
		</li>
	  </ul>
  </li>
  <li>
  <input type="checkbox" value="27" class="jobs" name="roles[]" '.((in_array('27',$User_role)?"checked":"")).'>Setting
  </li>
  <li>
  <input type="checkbox" value="28" class="jobs" name="roles[]" '.((in_array('28',$User_role)?"checked":"")).'>Shipping Charge
  <ul>
	<li>
	<input type="checkbox" value="28.1" class="jobs" name="roles[]" '.((in_array('28.1',$User_role)?"checked":"")).'>Add
	<input type="checkbox" value="28.2" class="jobs" name="roles[]" '.((in_array('28.2',$User_role)?"checked":"")).'>Status
	<input type="checkbox" value="28.3" class="jobs" name="roles[]" '.((in_array('28.3',$User_role)?"checked":"")).'>Edit
	</li>
  </ul>
  </li>
  <li>
  <input type="checkbox" value="29" class="jobs" name="roles[]" '.((in_array('29',$User_role)?"checked":"")).'>Commision Slab
  <ul>
	<li>
	<input type="checkbox" value="29.1" class="jobs" name="roles[]" '.((in_array('29.1',$User_role)?"checked":"")).'>Add
	<input type="checkbox" value="29.2" class="jobs" name="roles[]" '.((in_array('29.2',$User_role)?"checked":"")).'>Status
	<input type="checkbox" value="29.3" class="jobs" name="roles[]" '.((in_array('29.3',$User_role)?"checked":"")).'>Edit
	</li>
  </ul>
  </li>
  <li>
  <input type="checkbox" value="30" class="jobs" name="roles[]" '.((in_array('30',$User_role)?"checked":"")).'>Mail Management
	  <ul>
	  <input type="checkbox" value="30.1" class="jobs" name="roles[]" '.((in_array('30.1',$User_role)?"checked":"")).'>Manage SMS
	  <input type="checkbox" value="30.2" class="jobs" name="roles[]" '.((in_array('30.2',$User_role)?"checked":"")).'>Manage Email
	  <input type="checkbox" value="30.3" class="jobs" name="roles[]" '.((in_array('30.3',$User_role)?"checked":"")).'>User
	  <input type="checkbox" value="30.4" class="jobs" name="roles[]" '.((in_array('30.4',$User_role)?"checked":"")).'>Vendor
	  </ul>
  </li>
  <li>
  <input type="checkbox" value="31" class="jobs" name="roles[]" '.((in_array('31',$User_role)?"checked":"")).'>Coupon Management
  <ul>
	  <input type="checkbox" value="31.1" class="jobs" name="roles[]" '.((in_array('31.1',$User_role)?"checked":"")).'>Add
	  <input type="checkbox" value="31.2" class="jobs" name="roles[]" '.((in_array('31.2',$User_role)?"checked":"")).'>Edit
	  <input type="checkbox" value="31.3" class="jobs" name="roles[]" '.((in_array('31.3',$User_role)?"checked":"")).'>Delete
	  </ul>
  </li>
  <li>
  <input type="checkbox" value="32" class="jobs" name="roles[]" '.((in_array('32',$User_role)?"checked":"")).'>Wallet Management
  </li>
  <li>
  <input type="checkbox" value="33" class="jobs" name="roles[]" '.((in_array('33',$User_role)?"checked":"")).'>Delivery Boy Management
  <ul>
	<li>
	<input type="checkbox" value="33.1" class="jobs" name="roles[]" '.((in_array('33.1',$User_role)?"checked":"")).'>Add
	<input type="checkbox" value="33.2" class="jobs" name="roles[]" '.((in_array('33.2',$User_role)?"checked":"")).'>Status
	<input type="checkbox" value="33.3" class="jobs" name="roles[]" '.((in_array('33.3',$User_role)?"checked":"")).'>Action
	</li>
  </ul>
  </li>
  </ul>!next! 
  <button type="button" class="btn btn-primary" onclick="UpdateRole('.$id.')" >Submit</button>
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
  echo $content;
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='UpdateRole'){
	$id  = $_POST['id'];
	$jobs  = implode(',',$_POST['jobs']);
	$dbf->updateTable("user","roles='$jobs'","id='$id'");
}

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='Get_Party'){
$party_num=$_POST['party_num'];
$PArty=$dbf->fetchSingle("user",'*',"contact_no='$party_num' AND user_type='4'");

echo $PArty['full_name']."!next!".$PArty['email'];exit;
}
?>