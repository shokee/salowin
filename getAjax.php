<?php
ob_start();
session_start();
include_once 'admin/includes/class.Main.php';
$dbf = new User();

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

 function UpdateCarts(){?>
  <span>
             <?php  $Carts=$dbf->fetchOrder("cart","user_id='$user_ip'","","","");?>
            <button uk-icon="icon: cart; ratio:1.5"> </button><span class="uk-badge md"><?= count($Carts)?></span> 
            <strong>Cart</strong>
             </span> 
            
             
    
    <div uk-dropdown class="uk-padding-remove " style="width:350px;">
    <table class="uk-table uk-table-divider uk-background-default uk-text-center uk-table-small uk-table-middle uk-table-striped ">
  <?php  
if(!empty($Carts)){
      $Subtotal=0;
foreach ($Carts as $cart) {
     $products=$dbf->fetchSingle("product",'*',"product_id='$cart[product_id]'");
$Price_Vari=$dbf->fetchSingle("price_varition",'*',"variation_values='$cart[variation_id]'");

$Arry_Vari=array();
  array_push($ArryProd_status, $products['status']);
  foreach ($dbf->fetchOrder("variation","variation_id IN ($cart[variation_id])","","","") as $proVari) {
    array_push($Arry_Vari, $proVari['variation_name']);
  }

  ?>
  <tr>
    <td width="100"><img src="admin/images/product/<?php echo $products['primary_image'];?>" width="60" height="60" /></td>
    <td><a href="#"><?= $products['product_name']?>,<?= implode(',',$Arry_Vari)?></a></td>
    <td><?= $cart['qty']?></td>
    <td>Price: <ins><?php if(empty($Price_Vari)){$Subtotal=$Subtotal+$cart['qty']*$products['sales_price'];
     echo $cart['qty']*$products['sales_price'].'.00';}else{ 
      $Subtotal=$Subtotal+$cart['qty']*$Price_Vari['price'];
      echo $cart['qty']*$Price_Vari['price'].'.00';}?></ins>
</td>
    
    <!-- <td>
   <button class="uk-icon-button uk-button-danger" type="button" onclick="DeleCArtItem(<?//= $cart['cart_id']?>)"><span uk-icon="icon: trash" class="uk-icon"></span></button>
    
    </td>-->
  </tr>
<?php }}else{?>
  <tr><td colspan="6" style="text-align: center;color:red;">Catr Is Empty</td></tr>
<?php }?>
    <tr>
        	<td></td>
        	<td colspan="2"><b>Sub-Total</b></td>
            <td><?= $Subtotal.".00";?></td>
            <td></td>
        </tr>
      
         <tr>
        	
            <td colspan="5"> <a href="cart.php" class="btn btn-primary"><span uk-icon="cart" class="uk-icon"></span> View Cart</a>
        	</td>
            
        </tr>
</table>
    </div>

<?php } 


if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='CateFillter'){
$category=$_POST['category'];
$category=implode(",",$category);
$subcategory=$_POST['subcategory'];
$subcategory=implode(",",$subcategory);
$subcategory1=$_POST['subcategory1'];
$subcategory1=implode(",",$subcategory1);
$shop_id=$_POST['shop_id'];


$ArryCate=[];
 foreach($dbf->fetchOrder("pro_rel_cat1","catagory1_id IN ($category)","","product_id","") as $valCate){
 array_push($ArryCate,$valCate['product_id']);
 }
 $category = implode(',', $ArryCate);


$ArrysubCate=[];
 foreach($dbf->fetchOrder("pro_rel_cat2","catagory2_id IN ($subcategory)","","product_id","") as $valsubCate){
 array_push($ArrysubCate,$valsubCate['product_id']);
 }
$Subcategory = implode(',', $ArrysubCate);

$ArrysubCate1=[];
 foreach($dbf->fetchOrder("pro_rel_cat3","catagory3_id IN ($subcategory1)","","product_id","") as $valsubCate1){
 array_push($ArrysubCate1,$valsubCate1['product_id']);
 }

$Subcategory1 = implode(',', $ArrysubCate1);

if($Subcategory1!='')
    {
         $Subcategory1="product_id IN ($Subcategory1)";
       
    }else
     { 
     $Subcategory1="product_id<>0";
    }
if($Subcategory!=''){
    $Subcategory="product_id IN ($Subcategory)";
}else{ 
$Subcategory="product_id<>0";
}
if($category!=''){
    $category=" product_id IN ($category)";
}else{
     $category="product_id<>0";
}

		foreach($dbf->fetchOrder("product","$category AND  $Subcategory AND $Subcategory1 AND status='1' AND find_in_set('$shop_id',vendor_id)","product_id ASC","","product_id") as $product){
    $FirstVari_price=$dbf->fetchSingle("variations_values",'*',"product_id='$product[product_id]' AND vendor_id='$shop_id'");
    $Vari_price=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$FirstVari_price[price_variation_id]'");
    $Measure=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price[measure_id]'");
		?>
			<div class="pages">
          
            <div class="uk-card uk-card-body uk-card-default uk-padding-remove uk-margin-bottom uk-text-center">
            <div>
          <a href="single-product-page.php?editId=<?php echo $product['product_id'];?>&shop=<?= $shop_id?>">
            <div class=" uk-position-relative">
            
            <div class="uk-cover-container">
             <?php if($product['primary_image']<>''){?>
            
        <img src="admin/images/product/<?php echo $product['primary_image'];?>" uk-cover >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"  uk-cover >
        <?php }?>
        
        <canvas width="300" height="300"></canvas>
       
        </div>
            
            
            </div>
           </a>
            <div class="uk-padding-small pd">
                        <a href="single-product-page.php?editId=<?php echo $product['product_id'];?>&shop=<?= $shop_id?>">
          <?php 
          
          
          $chkAttiru=$dbf->countRows("variations_values","product_id='$product[product_id]' AND vendor_id='$shop_id'");
          
          ?>
            <h5 class="uk-margin-remove"><?php echo $product['product_name'];?></h5>
            
            <!-- <del><small>Rs. <?php echo $product['sales_price'];?> / <?php echo $unit['unit_name'];?></small></del> -->
          </a>
            <?php if($chkAttiru==0){?>
             <h3 class="text-danger">Stock Not Available</h3>
          <?php }else{?>
            <ins>Rs. <?php echo $FirstVari_price['sale_price'];?> <del><small>Rs. <?php echo $FirstVari_price['mrp_price'];?></small></del> / <?= $Vari_price['units'].$Measure['unit_name']?></ins> (<?=  round(abs(($FirstVari_price['sale_price']/$FirstVari_price['mrp_price']*100)-100))?>%OFF)<br />
                
                <div class="uk-grid-small uk-child-width-expand" uk-grid>
                 <div class="uk-grid-collapse uk-child-width-expand" uk-grid>
                  <div>
                    <div class="quantity buttons_added">
                      <input type="button" value="-" class="minus">
                        <input type="number" step="1" min="1" max=""  value="1" title="Qty"
                         class="input-text qty text" size="4"  name="qty" id="qty<?= $FirstVari_price['variations_values_id']?>">
                       <input type="button" value="+" class="plus">
                    </div>
                  </div>
                 
                <div class="uk-width-auto">
                 <button   class="uk-button uk-button-small uk-button-danger uk-width-1-1  "  style="border-radius:0px; padding:5px;" type="button" 
                 onclick="ChangeCart(<?=  $product['product_id']?>,<?= $FirstVari_price['variations_values_id'] ?>)">  
                 <span uk-icon="icon: cart; ratio:0.9"></span> 
                  </button>
                  </div>
                </div>
                 </div> 
                
          <?php }?>
            </div>
            </div>
            <?php  $All_Variotions=$dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id='$shop_id' LIMIT 1,20","","",""); 
          
              if(!empty($All_Variotions)){ ?>
            <div class="uk-margin-remove uk-padding-small uk-text-left uk-width-1-1" uk-dropdown="pos: bottom">
           
            <ul class="uk-list uk-list-divider">
              <?php
             
              foreach($All_Variotions as $varition){
           
                $Vari_price_single=$dbf->fetchSingle("price_varition",'*',"price_varition_id='$varition[price_variation_id]'");

                $Measure_Single=$dbf->fetchSingle("units",'unit_name',"unit_id='$Vari_price_single[measure_id]'");
                
              ?>
            	<li>
              <form >
           
             Rs.<?=$varition['sale_price']?>  <del><small>Rs. <?php echo $varition['mrp_price'];?></small></del>/ <?= $Vari_price_single['units'].$Measure_Single['unit_name']?> (<?=  round(abs(($varition['sale_price']/$varition['mrp_price']*100)-100))?>%OFF)
              <div class="uk-grid-small" uk-grid>
             <div class="uk-width-expand"> <div class="quantity buttons_added">
	<input type="button" value="-" class="minus">
    <input type="number" step="1" min="1" max=""  value="1" title="Qty" class="input-text qty text" size="4" pattern=""  name="qty" id="qty<?= $varition['variations_values_id']?>">
    <input type="button" value="+" class="plus">
</div></div>
				
             <div class="uk-width-auto"> 
             <button class="uk-button uk-button-danger uk-float-right" style="border-radius:0px; padding:5px;"  onclick="ChangeCart(<?=  $product['product_id']?>,<?= $varition['variations_values_id']?>)" type="button">
              	<span uk-icon="icon: cart; ratio:0.9"></span> 
              </button>
               </div>
             </div>
              </form>
              </li>

            
              <?php }?>
            </ul>
             
            </div>
            <?php }?>
            </div>
            
</div>
        	</div>



        <?php  } ?>


 <script type="text/javascript">
var items = $(".pages");
    var numItems = items.length;
    //alert(numItems);

    var perPage = 8;

    items.slice(perPage).hide();

    $('#pagination-container').pagination({
        items: numItems,
        itemsOnPage: perPage,
        prevText: "&laquo;",
        nextText: "&raquo;",
        onPageClick: function (pageNumber) {
            var showFrom = perPage * (pageNumber - 1);
            var showTo = showFrom + perPage;
            items.hide().slice(showFrom, showTo).show();
        }
    });
    </script>
        <?php } 
        
        if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='AddCart'){
$user_id = $_POST['user_id'];
$prod_id = $_POST['prod_id'];
$variation  = $_POST['variation'];
$shopId = $_POST['attribute'];
$qty = $_POST['qty'];

$checkqty=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$variation'");
if($checkqty['qty'] <= 0 || $checkqty['qty'] == ''){
    
     $dbf->deleteFromTable("cart","user_id='$user_id' AND  product_id='$prod_id' AND shop_id='$shopId' AND variation_id='$variation'");
     
    echo 'failed';
    
}else{

if($qty==0){
  $dbf->deleteFromTable("cart","user_id='$user_id' AND  product_id='$prod_id' AND shop_id='$shopId' AND variation_id='$variation'");
}else{


$cartcount=$dbf->countRows("cart","user_id='$user_id' AND  product_id='$prod_id' AND shop_id='$shopId' AND variation_id='$variation'");

if($cartcount==0){
$string="shop_id='$shopId', product_id='$prod_id', user_id='$user_id', qty='$qty', variation_id='$variation', created_date=NOW()";
$dbf->insertSet("cart",$string);

}else{	

$tempData=$dbf->fetchSingle("cart","*","user_id='$user_id' AND product_id='$prod_id' AND shop_id='$shopId' AND variation_id='$variation'");
$dbf->updateTable("cart","qty='$qty'","cart_id='$tempData[cart_id]'");

}
}

echo  $count_cart = $dbf->countRows("cart","user_id='$user_id'","");
}

}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='SortItemAccordingly'){
   $sort = $_POST['sort'];
   $condition = $_POST['condition'];
   $loadedNo = $_POST['loadedNo'];
   
   
   if($sort != ''){
       
       $soringItem_array=array();
        foreach($dbf->fetchOrder("product"," status='1' AND ( vendor_id IS NOT NULL) ".$condition,"product_id DESC LIMIT 15 OFFSET ".$loadedNo,"","") as $product){
                        if($product['vendor_id'] == '' || $product['vendor_id'] == 0){
                            continue;
                        }else{
                            if($sort == 1){
                            $all_shop=$product['vendor_id'];
                                foreach($dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id IN($all_shop)","sale_price ASC","","") as $lowestVndr){
                                    $singlevdrID=$lowestVndr['vendor_id'];
                                    $soringItem_array[$product['product_id']]=$lowestVndr['sale_price'];
                                    break;
                                }
                                
                            }else{
                                
                                   $all_shop=$product['vendor_id'];
                                foreach($dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id IN($all_shop)","sale_price DESC","","") as $lowestVndr){
                                    $singlevdrID=$lowestVndr['vendor_id'];
                                    $soringItem_array[$product['product_id']]=$lowestVndr['sale_price'];
                                    break;
                                } 
                            }  
                                
                              } 
                              }
                               if($sort == 1){
                              asort($soringItem_array);
                               }else{
                                 arsort($soringItem_array);  
                               }
                              
                              foreach($soringItem_array as $key => $val){
                                  
                                  		 foreach($dbf->fetchOrder("product","product_id='$key'","","","") as $product){
                        if($product['vendor_id'] == '' || $product['vendor_id'] == 0){

                            continue;

                        }else{
                            $all_shop=$product['vendor_id'];
                            
                                foreach($dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id IN($all_shop)","sale_price ASC","","") as $lowestVndr){
                                    $singlevdrID=$lowestVndr['vendor_id'];
                                    break;
                                }
                                 $All_Variotions=$dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id='$singlevdrID'","sale_price ASC","","");
                            
                             
                                // include("productloop.php");
                                
                                include("productlooplist.php");
                                

                              } $i++; }
                                  
                              }
                              exit();
       
       
   }else{
       
       foreach($dbf->fetchOrder("product"," status='1' AND ( vendor_id IS NOT NULL) ".$condition,"product_id DESC LIMIT 15 OFFSET ".$loadedNo,"","") as $product){
                        if($product['vendor_id'] == '' || $product['vendor_id'] == 0){

                            continue;

                        }else{
                            $all_shop=$product['vendor_id'];
                            
                                foreach($dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id IN($all_shop)","sale_price ASC","","") as $lowestVndr){
                                    $singlevdrID=$lowestVndr['vendor_id'];
                                    break;
                                }
                                 $All_Variotions=$dbf->fetchOrder("variations_values","product_id='$product[product_id]' AND vendor_id='$singlevdrID'","sale_price ASC","","");
                                
                                include("productlooplist.php");
                                
                              } 
                              } 
       
   }
 
}

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='checkAvailability'){
$user_id = $_POST['user_id'];
$prod_id = $_POST['prod_id'];
$variation  = $_POST['variation'];

$checkqty=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$variation'");
if($checkqty['qty'] <= 0 || $checkqty['qty'] == ''){
     
    echo 'OutOfStock';
    
}else{
    
    $count_cart = $dbf->countRows("cart","user_id='$user_id' AND product_id='$prod_id' AND variation_id='$variation'","");
    
    if($count_cart >0){
        
        echo 'InCart';
        
    }else{
       echo 'InStock'; 
    }
    
     
}

}

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='newPrice'){

$varition_id=$_POST['varition_id'];

   $priceVari=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$varition_id'");
if(empty($priceVari)){
   ?>
    <h4 class="text-danger">
    No Price Available,Please Refresh Page.
    </h4>
  <?php }else{?>
      <h4>
      <ins>Rs. <?php echo $priceVari['sale_price'];?><del><small>Rs. <?php echo $priceVari['mrp_price'];?></small></del></ins> 
    </h4>
    <?php }
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='SelctedCity'){

     $ip=getUserIP();
    $city_id=$_POST['city_id'];
     $pin=$_POST['pin'];
     $area_id=$_POST['SelectaRea'];

    $cntSelected=$dbf->countRows("selcted_loction","ip='$ip'","");
    if($area_id!=''){
      $Area_id=$area_id;
    }else{
      $Area = $dbf->fetchSingle("area",'*',"pin_id='$pin'");
      $Area_id = $Area['area_id'];
    }
       
    if($cntSelected==0){
   
    $dbf->insertSet("selcted_loction","ip='$ip',city_id='$city_id',pin_id='$pin',area_id='$Area_id'");
    }else{
        $dbf->updateTable("selcted_loction","city_id='$city_id',pin_id='$pin',area_id='$Area_id'","ip='$ip'");
    }
 if($city_id!=''){
        $cit_id=" AND city_id='$city_id' AND pin='$pin'";
      }else{
        $cit_id=" AND city_id='-1'";
      }

$all_shop=$dbf->fetchOrder("user","user_type='3' AND status='1'".$cit_id,"id ASC","","");
    if(count($all_shop)!=0){
                     foreach($all_shop as $agent){
                     $country = $dbf->fetchSingle("country",'*',"country_id='$agent[country_id]'");
                     $state = $dbf->fetchSingle("state",'*',"state_id='$agent[state_id]'");
                     $city = $dbf->fetchSingle("city",'*',"city_id='$agent[city_id]'");
                    
       $contnt.='<div> 
        <div class="uk-card uk-card-default">
            <div class="uk-card-media-top uk-text-center">
            <div class="uk-cover-container">';
             if($agent['banner_image']<>''){
        $contnt.='<img src="admin/images/vendor/'.$agent['banner_image'].'" uk-cover >';
        }else{
          $contnt.='<img src="admin/images/default.png"  alt="User Image"  uk-cover >';
        }
        $contnt.='<canvas width="600" height="400"></canvas>
        </div>
                
            </div>
            <div class="uk-card-body uk-padding-small">
                
                <div class="uk-grid uk-grid-small">
                    <div class="uk-width-auto"><div class="sn">
                    <div class="uk-cover-container">';
             if($agent['logo_image']<>''){
              $contnt.='<img src="admin/images/vendor/'.$agent['logo_image'].'" uk-cover >';
        }else{
          $contnt.='<img src="admin/images/default.png"  alt="User Image"  uk-cover >';
        }
        $contnt.='<canvas width="50" height="50"></canvas>
        </div>
        
                    </div>
                    </div>
                    <div class="uk-width-expand"><h5 class="uk-margin-remove">'.$agent['shop_name'].'</h5>
                    <p class="uk-margin-remove-top"><small>'.$city['city_name'].','.$state['state_name'].' ,'.$country['country_name'].'</small> </p>
                    </div>
                </div>
                <hr />
                <a href="shop-single-page.php?editId='.$agent['id'].'" class="uk-button uk-button-secondary uk-width-1-1">Go To Shop</a>
           </div>
        </div>
    </div>';
 }
 $contnt.='!next!';
}else {
  $contnt.= "<h3 class='uk-text-danger uk-text-center'>No Shop Available Yet.</h3>!next!";
}
$All_area=$dbf->fetchOrder("area","pin_id='$pin'","","","");
$Selected_city=$dbf->fetchSingle("selcted_loction",'area_id',"ip='$ip'");
if(!empty($All_area)){
$contnt.='
<div class="col-md-6">
<select id="SelectaRea" class="uk-select" onchange="SubmitLocation()">';
 foreach($All_area as $Area){
$contnt.='<option value="'.$Area['area_id'].'"';
if($Area['area_id']==$Selected_city['area_id']){ $contnt.="selected";}
$contnt.='>'. $Area['name'].'</option>';
}
$contnt.='</select>
</div>';
 }else{
  //  $contnt="";
 }




$all_shops=$dbf->fetchOrder("user","user_type='3'".$cit_id,"id ASC","","");
$Arr_prod_id=array();
foreach ($all_shops as $shops) {
foreach($dbf->fetchOrder("product","find_in_set('$shops[id]',vendor_id)","product_id ASC","","") as $product){
array_push($Arr_prod_id, $product['product_id']);
}
}
if(!empty($Arr_prod_id)){
$prod_id = implode(',', $Arr_prod_id);
$condi=" AND product_id IN($prod_id)";
}else{
  $condi=" AND product_id ='-1'";
}
$Arra_json=array();


foreach($dbf->fetchOrder("brands","display_in_home='1'","brands_id","","") as $brand){ 
  if(!empty($brand)){
  $single_list=array('label'=>$brand['brands_name'],'value'=>'product-category.php?brand='.base64_encode($brand['brands_id']));

  array_push($Arra_json,$single_list);
  }
}

// foreach($dbf->fetchOrder("product","status='1' AND ( vendor_id IS NOT NULL )","","product_name,product_id","") as $valProd){ 
//   if(!empty($valProd)){
//   $single_list=array('label'=>$valProd['product_name'],'value'=>'single-product-page.php?editId='.base64_encode($valProd['product_id']));

//   array_push($Arra_json,$single_list);
//   }
// }

 foreach($dbf->fetchOrder("product_catagory_2","","product_catagory_2_id ASC","","") as $product_catagory_1){
  if(!empty($product_catagory_1)){
  $single_list=array('label'=>$product_catagory_1['product_catagory_2_name'],'value'=>'product-category.php?subcat='.base64_encode($product_catagory_1['product_catagory_2_id']));

  array_push($Arra_json,$single_list);
  }
}
foreach($dbf->fetchOrder("product","status='1' AND ( vendor_id IS NOT NULL )","","product_name,product_id","") as $valProd){ 
  if(!empty($valProd)){
  $single_list=array('label'=>$valProd['product_name'],'value'=>'single-product-page.php?editId='.base64_encode($valProd['product_id']));

  array_push($Arra_json,$single_list);
  }
}
echo $contnt.'!next!'.json_encode($Arra_json);

}



if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='ClickRecentViews'){
$user_ip=$_POST['user_ip'];
$prod_id=$_POST['prod_id'];
$cntProd= $dbf->countRows("recent_views","product_id='$prod_id'");
if($cntProd==0){
 $string="product_id='$prod_id',user_id='$user_ip'";
 $dbf->insertSet("recent_views",$string);
}
}



if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='changPin'){
$City_id = $_REQUEST['value'];
    ?>
<option value="">--Select Pincode--</option>
<?php 
foreach ($dbf->fetchOrder("pincode","city_id='$City_id'","","","") as $Pincode) {
?>
<option value="<?= $Pincode['pincode_id']?>"><?= $Pincode['pincode']?></option>
<?php }}?>

<?php

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='AddWish'){
$user_id = $_POST['user_id'];
$prod_id = $_POST['prod_id'];
$vari_id = $_POST['vari_id'];

$checkWish=$dbf->fetchSingle("wishlist",'*',"user_id='$user_id' AND product_id='$prod_id'");
if(empty($checkWish)){

$string="user_id='$user_id',product_id='$prod_id',vari_id='$vari_id'";
 $dbf->insertSet("wishlist",$string);
 ?>
<button class="uk-button uk-button-secondary" type="button" onclick="removeToWish(<?= $prod_id?>)" >Remove To Wishlist</button>
<?php }else{
    
    $dbf->updateTable("wishlist","vari_id='$vari_id'","user_id='$user_id' AND product_id='$prod_id'");
}
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='RemoveWish'){
 $user_id = $_POST['user_id'];
$prod_id = $_POST['prod_id'];
$vari_id = $_POST['vari_id'];

$dbf->deleteFromTable("wishlist","product_id='$prod_id' AND user_id='$user_id'");
?>
 <button class="uk-button uk-button-secondary" type="button" onclick="addTwoWish(<?= $prod_id?>)" >Add To Wishlist</button>
 
 
 <?php } ?>



   <?php 
   // Latest Products

   if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='LatestCity'){
     $ip=getUserIP();
     $Arr_prod_id = array();
    $city_id=$_POST['city_id'];
    $pin=$_POST['pin'];
       if($city_id!=''){
        $cit_id=" AND city_id='$city_id' AND pin='$pin'";
      }else{
        $cit_id="";
      }
$all_shop=$dbf->fetchOrder("user","user_type='3'".$cit_id,"id ASC","","");

foreach ($all_shop as $shop) {
 foreach($dbf->fetchOrder("product","find_in_set('$shop[id]',vendor_id)","product_id ASC","","") as $product){
  array_push($Arr_prod_id, $product['product_id']);
 }
}
 

 $prod_id = implode(',', $Arr_prod_id);
 $condi="AND product_id IN($prod_id)";
      ?>
 <?php
        $i=1;
        $All_Produts = $dbf->fetchOrder("product","status='1' ".$condi,"product_id ASC","","");
        if(!empty($All_Produts)){
        foreach($All_Produts as $product){
        // $unit = $dbf->fetchSingle("units",'*',"unit_id='$product[unit_id]'");
        ?>

         <li>
            <a href="product-shop.php?prod=<?= base64_encode($product['product_id'])?>&ty=lat" class="uk-text-secondary" onclick="RecentlyViews(<?= $product['product_id']?>)" >
             <div class="uk-card-media-left uk-cover-container">
        <img src="admin/images/product/<?php echo $product['primary_image'];?>" alt=""  uk-cover>
        <canvas width="400" height="400"></canvas>
    </div>
              <h6 class="uk-margin-remove"><?php echo $product['product_name'];?></h6>
                <hr class="uk-margin-small" />
                    <div>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i  uk-icon="icon: star; ratio:0.8"></i>
                    </div>
            </a>
        </li>
        
        <?php $i++; } }else{?>
        <h3 class="text-danger text-center">No Product Available.</h3>

      <?php }}?>

               <?php if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='TodayDrend'){

                // TODAY TRENDING
     $Arr_prod_id = array();
    $city_id=$_POST['city_id'];
    $pin=$_POST['pin'];

     if($city_id!=''){
        $cit_id=" AND city_id='$city_id' AND pin='$pin'";
      }else{
        $cit_id="";
      }
$all_shop=$dbf->fetchOrder("user","user_type='3'".$cit_id,"id ASC","","");

foreach ($all_shop as $shop) {
 foreach($dbf->fetchOrder("product","find_in_set('$shop[id]',vendor_id)","product_id ASC","","") as $product){
  array_push($Arr_prod_id, $product['product_id']);
 }
}

  
$prod_id = implode(',', $Arr_prod_id);
 $condi="AND product_id IN($prod_id)";
 ?>
 <?php
        $i=1;
        $All_Produts = $dbf->fetchOrder("product","status='1' AND trendingg='1' ".$condi,"product_id ASC","","");
        if($All_Produts){
        foreach($All_Produts as $product){
        //$unit = $dbf->fetchSingle("units",'*',"unit_id='$product[unit_id]'");
        ?>
        <li>
            <a href="product-shop.php?prod=<?= base64_encode($product['product_id'])?>&ty=trend" class="uk-text-secondary" onclick="RecentlyViews(<?= $product['product_id']?>)" >
             <div class="uk-card-media-left uk-cover-container">
        <img src="admin/images/product/<?php echo $product['primary_image'];?>" alt=""  uk-cover>
        <canvas width="400" height="400"></canvas>
    </div>
              <h6 class="uk-margin-remove"><?= $product['product_name'];?></h6>
                <hr class="uk-margin-small" />
                 
                  <div>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i  uk-icon="icon: star; ratio:0.8"></i>
                </div>
            </a>
        </li>
           
             <?php }}else{?>
                 <h3 class="text-danger uk-text-center">No Product Available Yet.</h3>   
 <?php }}?>







          <?php if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='DalsOfDay'){
            // Deals Of Day

     $Arr_prod_id = array();
    $city_id=$_POST['city_id'];
     $pin=$_POST['pin'];

     if($city_id!=''){
         $cit_id=" AND city_id='$city_id' AND pin='$pin'";
      }else{
        $cit_id="";
      }
$all_shop=$dbf->fetchOrder("user","user_type='3'".$cit_id,"id ASC","","");

foreach ($all_shop as $shop) {
 foreach($dbf->fetchOrder("product","find_in_set('$shop[id]',vendor_id)","product_id ASC","","") as $product){
  array_push($Arr_prod_id, $product['product_id']);
 }
}
 
$prod_id = implode(',', $Arr_prod_id);
 $condi="AND product_id IN($prod_id)";
    ?>
 <?php
        $i=1;
         $date_time=date('Y-m-d H:i');
         $All_Produts=$dbf->fetchOrder("product","status='1' AND today_dealing_date_time>='$date_time' ".$condi,"product_id ASC","","");
         if($All_Produts){
        foreach($All_Produts as $product){
        // $unit = $dbf->fetchSingle("units",'*',"unit_id='$product[unit_id]'");
        ?>
               <div>
       <a href="product-shop.php?prod=<?= base64_encode($product['product_id'])?>&ty=deal"  onclick="RecentlyViews(<?= $product['product_id']?>)">
        <div class="uk-grid uk-grid-small">
          <div class="uk-width-auto"><img src="admin/images/product/<?php echo $product['primary_image'];?>" width="100"  /></div>
          <div class="uk-width-expand">
               <h6 class="uk-margin-remove"><?= $product['product_name'];?> </h6>
                    <span id="demo<?=$product['product_id']?>"></span>
                    <div>
                    <a href="" class="uk-text-success" uk-icon="icon: star; ratio:0.8"></a>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                    <i  class="uk-text-success" uk-icon="icon: star; ratio:0.8"></i>
                  </div>
            </div>
            </div>
       </a>
     </div>
      <script>
    var today = "<?= date('M d, Y H:i:s',strtotime($product['today_dealing_date_time']))?>";
   

// Set the date we're counting down to
var countDownDate<?= $product['product_id']?> = new Date(today).getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
 //alert(countDownDate)
  // Find the distance between now and the count down date
  var distance = countDownDate<?= $product['product_id']?> - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="demo"
  document.getElementById("demo<?= $product['product_id']?>").innerHTML = days +"d "+hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // If the count down is over, write some text 
  if (distance <= 0) {
    clearInterval(x);
    //document.getElementById("displayofDay<?= $product['product_id']?>").style.display = "none";
    //document.getElementById("demo<?= $product['product_id']?>").innerHTML = "EXPIRED";
  }
}, 1000);
</script>
          <?php }}else{?>  
          <h3 class="text-danger uk-text-center">No Product Available Yet.</h3>   
    <?php }}?>


     <?php 
 
// <!--Recently  Viewed-->

     if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='RecentViews'){
   
     $Arr_prod_id = array();
     $user_ip=$_POST['user_ip'];
    $city_id=$_POST['city_id'];
      $pin=$_POST['pin'];

     if($city_id!=''){
       $cit_id=" AND city_id='$city_id' AND pin='$pin'";
      }else{
        $cit_id="";
      }
$all_shop=$dbf->fetchOrder("user","user_type='3'".$cit_id,"id ASC","","");

foreach ($all_shop as $shop) {
 foreach($dbf->fetchOrder("product","find_in_set('$shop[id]',vendor_id)","product_id ASC","","") as $product){
  array_push($Arr_prod_id, $product['product_id']);
 }
}
 $prod_id = implode(',', $Arr_prod_id);
 $condi="AND product_id IN($prod_id)";

 ?>


<?php $All_Recnt=$dbf->fetchOrder("recent_views","user_id='$user_ip' ".$condi,"recent_views_id DESC","","");
if(!empty($All_Recnt)){
?>

  <div class="uk-card uk-card-body uk-card-default border uk-border-rounded uk-card-x-small uk-margin-bottom">
     <div class="featured">
    <div class="bgg uk-border-rounded"> <h5 class="uk-h5 uk-padding-small">Recent Views</h5> </div> 
    <?php  foreach ($All_Recnt as $Rec_vies) {
      $Products=$dbf->fetchSingle("product",'*',"product_id='$Rec_vies[product_id]' AND status='1'");?>  
      <a href="product-shop.php?prod=<?= base64_encode($Products['product_id'])?>&ty=rece">
    <div class="uk-card uk-grid-collapse uk-margin-small" uk-grid>
    <div class="uk-card-media-left uk-cover-container uk-width-1-4">
        <img src="admin/images/product/<?php echo $Products['primary_image'];?>" alt="" uk-cover>
        <canvas width="300" height="300"></canvas>
    </div>
    <div class="uk-width-expand">
           <div class="uk-margin-small-left">
           
            <h5 class="uk-margin-remove"><?= $Products['product_name'];?> </h5>
                  <div>
                    <i class="uk-text-danger" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-danger" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-danger" uk-icon="icon: star; ratio:0.8"></i>
                    <i class="uk-text-danger" uk-icon="icon: star; ratio:0.8"></i>
                    <i  uk-icon="icon: star; ratio:0.8"></i>
                  </div>
    </div>
</div>
</a>
  <hr />
  <?php }?>  


    
    <p></p>
</div>  
</div> 

<?php }}?>

<?php  
// Category Wise Location

  if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='CateLocation'){
  $Arr_prod_id = array();
    $city_id=$_POST['city_id'];
      $pin=$_POST['pin'];
      $category_id = $_POST['category_id'];

if($city_id!=''){
       $cit_id=" AND city_id='$city_id' AND pin='$pin'";
      }else{
        $cit_id="";
      }

$ArryProdId=array();
foreach ($dbf->fetchOrder("pro_rel_cat1","catagory1_id='$category_id'","","product_id","") as $prod_id) {
    array_push($ArryProdId, $prod_id['product_id']);
}
$prod_id = implode(',',$ArryProdId);

$Shops_id=array();

foreach ($dbf->fetchOrder("product","product_id IN($prod_id)","","vendor_id","") as $vendor) {
  if($vendor['vendor_id']!=''){
array_push($Shops_id, $vendor['vendor_id']);
}
}
if(!empty($Shops_id)){
$Shops_id=implode(',', $Shops_id);
$Shops_id = " AND id IN($Shops_id)";
}else{
$Shops_id="";
}

$all_shop=$dbf->fetchOrder("user","user_type='3' AND status='1'".$cit_id.$Shops_id,"id ASC","","");
    if(count($all_shop)!=0){
                     foreach($all_shop as $agent){
                     $country = $dbf->fetchSingle("country",'*',"country_id='$agent[country_id]'");
                     $state = $dbf->fetchSingle("state",'*',"state_id='$agent[state_id]'");
                     $city = $dbf->fetchSingle("city",'*',"city_id='$agent[city_id]'");
                    ?>
       <div> 
        <div class="uk-card uk-card-default">
            <div class="uk-card-media-top uk-text-center">
            <div class="uk-cover-container">
             <?php if($agent['banner_image']<>''){?>
        <img src="admin/images/vendor/<?php echo $agent['banner_image'];?> " uk-cover >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"  uk-cover >
        <?php }?>
        <canvas width="600" height="400"></canvas>
        </div>
                
            </div>
            <div class="uk-card-body uk-padding-small">
                
                <div class="uk-grid uk-grid-small">
                    <div class="uk-width-auto"><div class="sn">
                    <div class="uk-cover-container">
             <?php if($agent['logo_image']<>''){?>
        <img src="admin/images/vendor/<?php echo $agent['logo_image'];?> " uk-cover >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"  uk-cover >
        <?php }?>
        <canvas width="50" height="50"></canvas>
      
        </div>
        
                    </div>
                    </div>
                    <div class="uk-width-expand"><h5 class="uk-margin-remove"><?php echo $agent['shop_name'];?></h5>
                    <p class="uk-margin-remove-top"><small><?php echo $city['city_name'];?> , <?php echo $state['state_name'];?> , <?php echo $country['country_name'];?></small> </p>
                    </div>
                </div>
                <hr />
                <a href="shop-single-page.php?editId=<?php echo $agent['id'];?>" class="uk-button uk-button-secondary uk-width-1-1">Go To Shop</a>
           </div>
        </div>
    </div>

<?php }}else {
  echo "<h3 class='uk-text-danger uk-text-center'>No Shop Available Yet.</h3>";
}



}
?>



<?php  
//Sub Category Wise Location

  if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='SubCateLocation'){
  $Arr_prod_id = array();
    $city_id=$_POST['city_id'];
      $pin=$_POST['pin'];
      $category_id = $_POST['category_id'];

if($city_id!=''){
       $cit_id=" AND city_id='$city_id' AND pin='$pin'";
      }else{
        $cit_id="";
      }

$ArryProdId=array();
foreach ($dbf->fetchOrder("pro_rel_cat2","catagory2_id='$category_id'","","product_id","") as $prod_id) {
    array_push($ArryProdId, $prod_id['product_id']);
}
$prod_id = implode(',',$ArryProdId);

$Shops_id=array();

foreach ($dbf->fetchOrder("product","product_id IN($prod_id)","","vendor_id","") as $vendor) {
  if($vendor['vendor_id']!=''){
array_push($Shops_id, $vendor['vendor_id']);
}
}
if(!empty($Shops_id)){
$Shops_id=implode(',', $Shops_id);
$Shops_id = " AND id IN($Shops_id)";
}else{
$Shops_id="";
}

$all_shop=$dbf->fetchOrder("user","user_type='3' AND status='1'".$cit_id.$Shops_id,"id ASC","","");
    if(count($all_shop)!=0){
                     foreach($all_shop as $agent){
                     $country = $dbf->fetchSingle("country",'*',"country_id='$agent[country_id]'");
                     $state = $dbf->fetchSingle("state",'*',"state_id='$agent[state_id]'");
                     $city = $dbf->fetchSingle("city",'*',"city_id='$agent[city_id]'");
                    ?>
       <div> 
        <div class="uk-card uk-card-default">
            <div class="uk-card-media-top uk-text-center">
            <div class="uk-cover-container">
             <?php if($agent['banner_image']<>''){?>
        <img src="admin/images/vendor/<?php echo $agent['banner_image'];?> " uk-cover >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"  uk-cover >
        <?php }?>
        <canvas width="600" height="400"></canvas>
        </div>
                
            </div>
            <div class="uk-card-body uk-padding-small">
                
                <div class="uk-grid uk-grid-small">
                    <div class="uk-width-auto"><div class="sn">
                    <div class="uk-cover-container">
             <?php if($agent['logo_image']<>''){?>
        <img src="admin/images/vendor/<?php echo $agent['logo_image'];?> " uk-cover >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"  uk-cover >
        <?php }?>
        <canvas width="50" height="50"></canvas>
        </div>
        
                    </div>
                    </div>
                    <div class="uk-width-expand"><h5 class="uk-margin-remove"><?php echo $agent['shop_name'];?></h5>
                    <p class="uk-margin-remove-top"><small><?php echo $city['city_name'];?> , <?php echo $state['state_name'];?> , <?php echo $country['country_name'];?></small> </p>
                    </div>
                </div>
                <hr />
                <a href="shop-single-page.php?editId=<?php echo $agent['id'];?>" class="uk-button uk-button-secondary uk-width-1-1">Go To Shop</a>
           </div>
        </div>
    </div>

<?php }}else {
  echo "<h3 class='uk-text-danger uk-text-center'>No Shop Available Yet.</h3>";
}



}
?>

<?php  
//Sub2 Category Wise Location

  if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='SubCatesLocation'){
  $Arr_prod_id = array();
    $city_id=$_POST['city_id'];
      $pin=$_POST['pin'];
      $category_id = $_POST['category_id'];

if($city_id!=''){
       $cit_id=" AND city_id='$city_id' AND pin='$pin'";
      }else{
        $cit_id="";
      }

$ArryProdId=array();
foreach ($dbf->fetchOrder("pro_rel_cat3","catagory3_id='$category_id'","","product_id","") as $prod_id) {
    array_push($ArryProdId, $prod_id['product_id']);
}
$prod_id = implode(',',$ArryProdId);

$Shops_id=array();

foreach ($dbf->fetchOrder("product","product_id IN($prod_id)","","vendor_id","") as $vendor) {
  if($vendor['vendor_id']!=''){
array_push($Shops_id, $vendor['vendor_id']);
}
}
if(!empty($Shops_id)){
$Shops_id=implode(',', $Shops_id);
$Shops_id = " AND id IN($Shops_id)";
}else{
$Shops_id="";
}

$all_shop=$dbf->fetchOrder("user","user_type='3' AND status='1'".$cit_id.$Shops_id,"id ASC","","");
    if(count($all_shop)!=0){
                     foreach($all_shop as $agent){
                     $country = $dbf->fetchSingle("country",'*',"country_id='$agent[country_id]'");
                     $state = $dbf->fetchSingle("state",'*',"state_id='$agent[state_id]'");
                     $city = $dbf->fetchSingle("city",'*',"city_id='$agent[city_id]'");
                    ?>
       <div> 
        <div class="uk-card uk-card-default">
            <div class="uk-card-media-top uk-text-center">
            <div class="uk-cover-container">
             <?php if($agent['banner_image']<>''){?>
        <img src="admin/images/vendor/<?php echo $agent['banner_image'];?> " uk-cover >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"  uk-cover >
        <?php }?>
        <canvas width="600" height="400"></canvas>
        </div>
                
            </div>
            <div class="uk-card-body uk-padding-small">
                
                <div class="uk-grid uk-grid-small">
                    <div class="uk-width-auto"><div class="sn">
                    <div class="uk-cover-container">
             <?php if($agent['logo_image']<>''){?>
        <img src="admin/images/vendor/<?php echo $agent['logo_image'];?> " uk-cover >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"  uk-cover >
        <?php }?>
        <canvas width="50" height="50"></canvas>
        </div>
        
                    </div>
                    </div>
                    <div class="uk-width-expand"><h5 class="uk-margin-remove"><?php echo $agent['shop_name'];?></h5>
                    <p class="uk-margin-remove-top"><small><?php echo $city['city_name'];?> , <?php echo $state['state_name'];?> , <?php echo $country['country_name'];?></small> </p>
                    </div>
                </div>
                <hr />
                <a href="shop-single-page.php?editId=<?php echo $agent['id'];?>" class="uk-button uk-button-secondary uk-width-1-1">Go To Shop</a>
           </div>
        </div>
    </div>

<?php }}else {
  echo "<h3 class='uk-text-danger uk-text-center'>No Shop Available Yet.</h3>";
}



}



if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='Set_Prod_wise'){

     $ip=getUserIP();
    $city_id=$_POST['city_id'];
     $pin=$_POST['pin'];
     $product_id=$_POST['product_id'];

if($city_id!=''){
       $cit_id=" AND city_id='$city_id' AND pin='$pin'";
      }else{
        $cit_id="";
      }


    $cntSelected=$dbf->countRows("selcted_loction","ip='$ip'","");

    if($cntSelected==0){
    $dbf->insertSet("selcted_loction","ip='$ip',city_id='$city_id',pin_id='$pin'");
    }else{
        $dbf->updateTable("selcted_loction","city_id='$city_id',pin_id='$pin'","ip='$ip'");
    }



$Arr_Of_prod = array();
foreach($dbf->fetchOrder("product","product_id='$product_id'","product_id ASC","vendor_id","") as $Vendor){
 
  array_push($Arr_Of_prod,$Vendor['vendor_id']);

 }

if(!empty($Arr_Of_prod)){
 $vendor_list=implode(',',$Arr_Of_prod);
$vendor_list = " AND id IN($vendor_list)";
}else{
$vendor_list=" AND id='0'";
}

$all_shop=$dbf->fetchOrder("user","user_type='3' AND status='1'".$vendor_list.$cit_id,"id ASC","","");
    if(count($all_shop)!=0){
                     foreach($all_shop as $agent){
                     $country = $dbf->fetchSingle("country",'*',"country_id='$agent[country_id]'");
                     $state = $dbf->fetchSingle("state",'*',"state_id='$agent[state_id]'");
                     $city = $dbf->fetchSingle("city",'*',"city_id='$agent[city_id]'");
                    ?>
       <div> 
        <div class="uk-card uk-card-default">
            <div class="uk-card-media-top uk-text-center">
            <div class="uk-cover-container">
             <?php if($agent['banner_image']<>''){?>
        <img src="admin/images/vendor/<?php echo $agent['banner_image'];?> " uk-cover >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"  uk-cover >
        <?php }?>
        <canvas width="600" height="400"></canvas>
        </div>
                
            </div>
            <div class="uk-card-body uk-padding-small">
                
                <div class="uk-grid uk-grid-small">
                    <div class="uk-width-auto"><div class="sn">
                    <div class="uk-cover-container">
             <?php if($agent['logo_image']<>''){?>
        <img src="admin/images/vendor/<?php echo $agent['logo_image'];?> " uk-cover >
        <?php }else{?>
         <img src="admin/images/default.png"  alt="User Image"  uk-cover >
        <?php }?>
        <canvas width="50" height="50"></canvas>
        </div>
        
                    </div>
                    </div>
                    <div class="uk-width-expand"><h5 class="uk-margin-remove"><?php echo $agent['shop_name'];?></h5>
                    <p class="uk-margin-remove-top"><small><?php echo $city['city_name'];?> , <?php echo $state['state_name'];?> , <?php echo $country['country_name'];?></small> </p>
                    </div>
                </div>
                <hr />
                <a href="shop-single-page.php?editId=<?php echo $agent['id'];?>" class="uk-button uk-button-secondary uk-width-1-1">Go To Shop</a>
           </div>
        </div>
    </div>

<?php }}else {
  echo "<h3 class='uk-text-danger uk-text-center'>No Shop Available Yet.</h3>";
}

}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='LocationBanner'){
  $city_id=$_POST['city_id'];
   $pin=$_POST['pin'];
   $All_banners= $dbf->fetchOrder("banner","pin_id='$pin'","banner_id DESC","","");
   if(!empty($All_banners)){
   foreach($All_banners as $resBanner){
   ?>
   <li>
   <img src="admin/images/banner/<?php echo $resBanner['banner_image'];?>" alt="<?php echo $resBanner['banner_title'];?>" uk-cover>
   </li>
   
<?php }}}?>

<?php 
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='LocationSidebar'){
  $city_id=$_POST['city_id'];
  $pin=$_POST['pin'];
  $contnt="";
   foreach($dbf->fetchOrder("addd","position='5' AND city_id='$city_id' AND pin_id='$pin'","add_id DESC LIMIT 5","","") as $resBanner){
  
  $contnt.='<div class="uk-position-relative uk-margin-bottom" >
   <a href="'.$resBanner['add_link'].'">
    <img src="admin/images/add/'.$resBanner['add_image'].'" class="uk-border-rounded" >
   </a>
  </div>';
   }
   $contnt.="!next!";
	foreach($dbf->fetchOrder("addd","position='1'  AND city_id='$city_id' AND pin_id='$pin'","add_id DESC  LIMIT 3","","") as $resSideBar){
    $contnt.= '<a href=" '.$resSideBar['add_link'].'">
                      <img src="admin/images/add/'. $resSideBar['add_image'].'" class="uk-margin-bottom uk-border-rounded uk-width-1-1" style="height:126px;">
                     </a>';
        			
            } 
    $contnt.="!next!";
                     foreach($dbf->fetchOrder("addd","position='2' AND city_id='$city_id' AND pin_id='$pin'","add_id DESC LIMIT 3","","") as $resBannerLatest){
                  
                      $contnt.=' <div class="uk-position-relative uk-margin-bottom" >
                     <a href="'.$resBannerLatest['add_link'].'">
                      <img src="admin/images/add/'.$resBannerLatest['add_image'].'" class="uk-border-rounded" >
                     </a>
                    </div>';
                     }
    $contnt.="!next!";
 
  
                     foreach($dbf->fetchOrder("addd","position='4' AND city_id='$city_id' AND pin_id='$pin'","add_id DESC  LIMIT 1","","") as $resBanner_td_tren){
                    
                      $contnt.='<div class="uk-position-relative uk-margin-bottom" >
                     <a href="'.$resBanner_td_tren['add_link'].'">
                      <img src="admin/images/add/'.$resBanner_td_tren['add_image'].'" class="uk-border-rounded" >
                     </a>
                    </div>';
                    }
                    $contnt.="!next!";
             
    
                    foreach($dbf->fetchOrder("addd","position='3' AND city_id='$city_id' AND pin_id='$pin'","add_id DESC  LIMIT 2","","") as $resBanner){
                    $contnt.='<div class="uk-position-relative uk-margin-bottom" >
                    <a href="'.$resBanner['add_link'].'" >
                     <img src="admin/images/add/'.$resBanner['add_image'].'" class="uk-border-rounded" >
                    </a>
                   </div>';
            } 

            $contnt.="!next!";
            $All_banners= $dbf->fetchOrder("banner","pin_id='$pin'","banner_id DESC","","");
            if(!empty($All_banners)){
            foreach($All_banners as $resBanner){
            
            $contnt.='<li>
            <img src="admin/images/banner/'.$resBanner['banner_image'].'" alt="'.$resBanner['banner_title'].'" uk-cover>
            </li>';
           }}
  
     print_r($contnt);exit;  
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='DeliverBoyUp'){
  $lat = $_POST['lat'];
 $lng = $_POST['lng'];
 $dboy = $_POST['dboy'];
 $dbf->updateTable("user","lat='$lat',lng='$lng'","id='$dboy'");
}

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='CartUpdate'){
  $qty = $_POST['qty'];
  $cartid = $_POST['cartid'];
  $user_ip = $_POST['user_ip'];
$dbf->updateTable("cart","qty='$qty'","cart_id='$cartid'");
$Carts=$dbf->fetchOrder("cart","user_id='$user_ip'","","","");

if(!empty($Carts)){
 $ArryProd_status=array();
 $CartArry=array();
 $ProdArry=array();
 $Subtotal=0;
foreach ($Carts as $cart) {
  $Price_Vari=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$cart[variation_id]'");
 $Subtotal+=$cart['qty']*$Price_Vari['sale_price'];
} 
$Singlecart=$dbf->fetchSingle("cart",'*',"cart_id='$cartid'");
 $Price_Vari1=$dbf->fetchSingle("variations_values",'*',"variations_values_id='$Singlecart[variation_id]'");

if(empty($Price_Vari1))
  {
    $html_content.='<h3 class="uk-text-danger"> This Product Out Of Stock,Please Select Another Product</h3>!next!';
  }
  else{ 
    $single_cartprice= $Singlecart['qty']*$Price_Vari1['sale_price'];
    $html_content.='Price:'.$single_cartprice.'/- !next!';
  }

    $html_content .= $Subtotal.'!next!';
  $checkShippingCondtn=$dbf->fetchSingle("shipping",'*',"shipping_id='6'");
  if($Subtotal > $checkShippingCondtn['price']){
      $applicable_shipping=2;
  }else{
      $applicable_shipping=1;
  }
    $deliver_price=0;
  foreach ($dbf->fetchOrder("shipping","status='1'","shipping_id DESC","","") as $Shipping) {
      if($Shipping['shipping_id'] == $applicable_shipping){
          continue;
      }
      $deliver_price=$Shipping['price'];

      $html_content.='<p class="uk-grid uk-grid-small" style="font-size:16px;"><span class="uk-width-expand"> 
      <input type="hidden" id="shiptype'.$Shipping['shipping_id'].'" value="'.$Shipping['name'].'">
      <input type="radio" name="delivery_type" onclick="DeliveryType('.$Shipping['price'].','.$Subtotal.')" checked class="procced" value="'.$Shipping['shipping_id'].'"/>
      '.$Shipping['name'].':</span><span class="uk-width-auto"> ₹'.$Shipping['price'].'</span></p>';
  }
$html_content.="!next!";
$html_content.=$deliver_price;
echo $html_content;exit;
}
}

