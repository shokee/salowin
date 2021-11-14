<?php include('header.php')?>
  <?php include('sidebar.php')?>
<link rel="stylesheet" href="dist/style.css">  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Manage Offline Order 
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"> Offline Order</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
 

      <div class="box">
        
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){?>
<div class="uk-alert-success" uk-alert>
<a class="uk-alert-close" uk-close></a>
<p>New Order Added Successfully</p>
</div>
 <?php }?>
 
 <?php 
 if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='book_table'){
     

    $party_num=$dbf->checkXssSqlInjection($_REQUEST['party_num']);
	$party_name=$dbf->checkXssSqlInjection($_REQUEST['party_name']);
	$Party_email=$dbf->checkXssSqlInjection($_REQUEST['Party_email']);
	$date=$dbf->checkXssSqlInjection($_REQUEST['date']);
	$country_id=$dbf->checkXssSqlInjection($_REQUEST['country_id']);
	$state_id=$dbf->checkXssSqlInjection($_REQUEST['state_id']);
	$city_id=$dbf->checkXssSqlInjection($_REQUEST['city_id']);
	$pin=$dbf->checkXssSqlInjection($_REQUEST['pin']);
	$vendor_id=$dbf->checkXssSqlInjection($_REQUEST['vendor_id']);
	$address=$dbf->checkXssSqlInjection($_REQUEST['address']);
	
	$grandTotal=$dbf->checkXssSqlInjection($_REQUEST['grandTotal']);
// 	$discount=$dbf->checkXssSqlInjection($_REQUEST['discount']);
// 	if($discount == ''){
// 	    $discount=0;
	    
// 	}
		$paid=$dbf->checkXssSqlInjection($_REQUEST['paid']);
	$due=$dbf->checkXssSqlInjection($_REQUEST['due']);
	$paymentType=$dbf->checkXssSqlInjection($_REQUEST['paymentType']);
	$deliveryCharge=$dbf->checkXssSqlInjection($_REQUEST['deliveryCharge']);
	
	$PArty=$dbf->fetchSingle("user",'*',"contact_no='$party_num' AND user_type='4'");
if(empty($PArty)){
    
    $password=base64_encode(base64_encode($party_num));
    
    $stringsss="full_name='$party_name',contact_no='$party_num',user_name='$Party_email',password='$password',email='$Party_email',user_type='4'";
    $userIId=$dbf->insertSet("user",$stringsss);
    
}else{
   $userIId= $PArty['id'];
}
$order_id = strtoupper('DB-'.$dbf->randomPassword());	   

	$checkAddress=$dbf->fetchSingle("address",'*',"user_id='$userIId'");
	if(empty($checkAddress)){
$stringg="user_id='$userIId',first_name='$party_name',city_id='$city_id',pincode='$pin',email='$Party_email',number='$party_num',address='$address'";	    
	    $addid=$dbf->insertSet("address",$stringg);
	}else{
   $addid= $checkAddress['address_id'];
}

	
	//Array Of Element
$product=$_POST['product'];
$variation=$_POST['variation'];
$qty=$_POST['qty'];
$sub_total=$_POST['sub_total'];
//Array Of Element

for($j=0;$j<count($product);$j++){
    
    $getVar= $dbf->fetchSingle("price_varition",'*',"price_varition_id='$variation[$j]'");
    $unit=$dbf->fetchSingle("units",'*',"unit_id='$getVar[measure_id]'");
    
      $ordername=$product[$j].$getVar['units'].$unit['unit_name'];
      
  $string1="ordername='$ordername',qty='$qty[$j]',price='$sub_total[$j]',user_id='$userIId',vendor_id='$vendor_id',shipping_charge='$deliveryCharge',
  order_id='$order_id',address_id='$addid',fname='$party_name',num='$party_num',email='$Party_email',adress='$address',city='$city_id',pin='$pin',
  payment_mode='$paymentType',status='0'";
  
  $dbf->insertSet("orders",$string1);
  
}

	
	header("Location:ofline_order.php?msg=success");exit;
     
 }
 
 
 ?>
 
 
  <form enctype="multipart/form-data" method="post" action="">
      		<div class="box-header">
      		    <div class="uk-grid-small  uk-grid-collapse uk-child-width-expand" uk-grid>
             <div class="uk-width-1-12">
                
              <div class="uk-grid-small uk-child-width-1-5" uk-grid>
              <div class="form-group">
              	<label>Contact No</label>
                <input type="tel"  value="" onchange="GetCliantsDeatisl(this.value)" class="form-control"  pattern="[6789][0-9]{9}" name="party_num" required>
                </div>
                
                
                
              <div class="form-group">
              	<label>First Name</label>
                <input type="text"    class="form-control" id="party_name"  name="party_name" required>
                </div>
              <!--<div class="form-group">-->
              <!--	<label>Last Name</label>-->
              <!--  <input type="text"    class="form-control" id="party_name"  name="party_name" required>-->
              <!--  </div>-->
              
                
                <div class="form-group">
              	<label>E-Mail</label>
                <input type="mail"  value="" class="form-control" id="Party_email" name="Party_email" required>
                </div>
                
                
                <div class="form-group">
                <label>Date</label>
                <div >
     
                  <input type="date" value="" id="checkdate" onchange="changedateto()" class="form-control " data-inputmask="'alias': 'dd/mm/yyyy'" data-mask  required name="date">
                </div>
                </div>
                
                <div class="form-group">
             <label class="" for="inlineFormCustomSelect">Select Country</label>
      		    <select class="form-control select2" name="country_id" onChange=" GetState(this.value)">
                <option value="" >--Select Country--</option>
    			<?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $countryName){?>
    			<option value="<?=$countryName['country_id']?>" ><?=$countryName['country_name']?></option>
   			    <?php }?>
    			</select>
                </div>
                
                
                <div class="form-group">
                <label>Select State Name</label>
                <select class="form-control select2" name="state_id" id="stateres" onChange="GetCitys(this.value)">
    			 <option value="" >--Select State--</option>
    			 </select>
                </div>
              
                <div class="form-group">
                <label>Enter City Name</label>
                  <select class="form-control select2" id="cityres" name="city_id" onchange="UpdateChangepin(this.value)">
                       <option value="" >--Select City--</option>
                  </select>
                </div>
                <div class="form-group">
                <label>Enter Pincode</label>
                <select name="pin" class="form-control select2" id="loc_selected_pin" onchange="UpdateChangearea(this.value)" >
                <option value="">--Select Pincode--</option>
               </select> 
                </div>
           
              <div class="form-group">
                <label>Select Vendor</label>
                    <select name="vendor_id" class="form-control select2" id="loc_selected_area" onchange="GetAllProduct(this.value)" required>
                <option value="">--Select --</option>
               </select>
                </div>
                
                 <div class="form-group">
                <label>Address</label>
                    <textarea  name="address" class="form-control" required placeholder="Enter Address" rows="2" ></textarea>
                    <!--<input type="text" name="area" class="form-control" required placeholder="Enter Address" >-->
                </div>
                
               
                
                <!-- <div class="form-group">-->
                <!--<label>Select Vendor</label>-->
                <!--   <select class="form-control select2" >-->
                <!--       <option>Vendor Name1</option>-->
                <!--       <option>Vendor Name2</option>-->
                <!--   </select>-->
                <!--</div>-->
                
                
                
           
               
              </div>
               
              
              </div>
              <div>
                  


<div class="uk-grid uk-child-width-expand">
    <div>
        <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Product</th>
                        
                        <!--<th>Price</th>-->
                    </tr>
                    </thead> 
    <tbody id="addProductss">
        
  <!--      <?php $i=1; foreach($dbf->fetchOrder("product","","product_id ASC","","") as $resBanner){?>-->
        
  <!--<tr>-->
  <!--   <td><button type="button" id="itemselected<?= $resBanner['product_id'];?>" onclick="addItemPrice(this.value)" name="menu[]" value="<?= $resBanner['product_id'];?>" >+</button></td>-->
  <!--   <td><?= $resBanner['product_name'];?> </td>-->
    <!--<td>  Rs. <ins><?= $resBanner['sales_price'];?></ins></td>-->
  <!--</tr>-->
  <!--<?php $i++; }?>-->
  
</tbody>
</table>
    </div>
    <div>
        
       <div class="table table-responsive">
<table class="table table-responsive table-striped table-bordered">
<thead>
	<tr>
    	<td>Product Name</td>
    	<td>Choose Variation</td>
    	<td>Quantity</td>
        <td>Sub Total</td>
        <td>Remove</td>
    </tr>
</thead>
<tbody id="TextBoxContainer">
<tr>
    	<!--<td><div class="form-group">-->
     <!--           <input type="text" class="form-control product" placeholder="Item Name" readonly name="product[]" id="product1" required>-->
     <!--         </div></td>-->
    	<!--<td><div class="form-group"><input type="text" class="form-control rate" placeholder="Rate" readonly name="rate[]" id="rate1" required></div></td>-->
    	<!--<td><div class="form-group"><input type="number" min="1" class="form-control" placeholder="Quantity" name="qty[]" id="qty1" onkeyup="QuantityPrice(1)" onclick="QuantityPrice(1)" required></div></td>-->
    	<!--<td><div class="form-group"><input type="text" min="1" class="form-control" placeholder="Sub Total" name="sub_total[]" readonly id="sub_total1" required></div></td>-->
    	<!--<td><button type="button" class="btn btn-danger remove" ><i class="glyphicon glyphicon-remove-sign"></i></button></td>-->
    </tr>
</tbody>
<tfoot>
 
</tfoot>
</table>
</div> 
    </div>
</div>
    


</div>
                </div>
<p></p>


<p></p>

<p></p>

              

<hr>

<div class="row">
	<div class="col-md-6">
			  	<div class="form-group row">
				    <label for="subTotal" class="col-sm-3 control-label">Sub Amount</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="grandTotal" name="grandTotal" required readonly>
				    </div>
				  </div> <!--/form-group row-->			  
				  <!--<div class="form-group row">-->
				  <!--  <label for="vat" class="col-sm-3 control-label">CGST(2.5%)</label>-->
				  <!--  <div class="col-sm-9">-->
				  <!--    <input type="text" class="form-control" id="cgst" name="cgst" readonly>-->
				  <!--  </div>-->
				  <!--</div> <!--/form-group row-->			  
				  <!--<div class="form-group row">-->
				  <!--  <label for="totalAmount" class="col-sm-3 control-label">SGST(2.5%)</label>-->
				  <!--  <div class="col-sm-9">-->
				  <!--    <input type="text" class="form-control" id="sgst" name="sgst" readonly >-->
				  <!--  </div>-->
				  <!--</div> <!--/form-group row-->			  
				  <!--<div class="form-group row">-->
				  <!--  <label for="discount" class="col-sm-3 control-label"> Discount</label>-->
				  <!--  <div class="col-sm-9">-->
				  <!--    <input type="text" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off">-->
				  <!--  </div>-->
				  <!--</div> -->
				  
				   <!-- <div class="form-group">-->
                <!--<label>Delivery Charge</label>-->
                <!--    <input type="text" name="area" class="form-control" required placeholder="Delevery Charge" >-->
                <!--</div>-->
				  
				  
				  <!--/form-group row -->
				  <div class="form-group row">
				    <label  class="col-sm-3 control-label">Delivery Charge *</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control"  name="deliveryCharge" required placeholder="Enter Delevery Charge">
				    </div>
				  </div> <!--/form-group row-->
			  </div>
              
              <div class="col-md-6">
			  	<div class="form-group row">
				    <label for="paid" class="col-sm-3 control-label">Paid Amount</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="paid" name="paid" required autocomplete="off" onkeyup="paidAmount()">
				    </div>
				  </div> <!--/form-group row-->			  
				  <div class="form-group row">
				    <label for="due" class="col-sm-3 control-label">Due Amount</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="due" name="due" readonly>
				    </div>
				  </div> <!--/form-group row-->		
				  <div class="form-group row">
				    <label for="clientContact" class="col-sm-3 control-label">Payment Type</label>
				    <div class="col-sm-9">
				      <select class="form-control" name="paymentType" required id="paymentType">
				      	<option value="">~~SELECT~~</option>
				      	<option>Cheque</option>
				      	<option>Cash</option>
				      	<option>Credit Card</option>
				      </select>
				    </div>
				  </div> <!--/form-group row-->							  
				  <div class="form-group " style="text-align:right">
                  <div class="row">
                  	<div class="col-sm-6"><button class="btn btn-lg btn-success" style="width:100%;" type="submit" name="submit" value="book_table">SUBMIT</button></div>
                    <!-- <div class="col-sm-6"><button class="btn btn-lg btn-danger" style="width:100%;">PRINT AND SUBMIT</button></div> -->
                  </div>
				    
				    
				  </div> <!--/form-group-->							  
			  </div>
</div>
            </div>
            
            
      </div>
</form>
  
      
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  

  
  
  <script>
  function GetCliantsDeatisl(arg){
     // alert(arg);exit();
      
    var url="getAjax.php";
  $.post(url,{"choice":"Get_Party","party_num":arg},function(res){
     // alert(res);
    var Party_Detail = res.split('!next!');
 $('#party_name').val(Party_Detail[0]);
 $('#Party_email').val(Party_Detail[1]);
  })
  }
  
  function changeGuestNo()
  {
      var date = $('#checkdate').val();
      
      var time = $('#checktime').val();
      if(!date){
          
          exit();
      }
      
      if(time){
          
          definefoodtime(time);
          
      }else{
          exit();
      }
         
  }
  
  function changedateto()
  {
       var time = $('#checktime').val();
       
        if(time){
          
          definefoodtime(time);
          
      }else{
          
          exit();
      }
       
  }
  
  function  definefoodtime(arg)
  {
       var vendorID='<?= $profileuserid;?>';
       
        var adult = $('#no_of_adult').val();
        var child = $('#no_of_child').val();
        
        var total_sit=parseInt(adult) + parseInt(child);
       
      
       var date = $('#checkdate').val();
       if(!date){
           
           alert("plaese select date");
           location.reload();
           exit();
       }
     
              var url="getAjax.php";
  $.post(url,{"choice":"get_foodTime","time":arg},function(res){
        if(res == '0'){
            alert('At this time we are not allow for Booking');
            location.reload();
            exit();
        }
    $('#book_forr').val(res);
     $('#book_forr_data').val(res);
    
   <?php foreach($dbf->fetchOrder("tablebook","vendor_id='$profileuserid'","table_id ASC","","floorno") as $resBanner11){?>
   var floor='<?= $resBanner11['floorno'];?>';
     $.post(url,{"choice":"Chack_table","check_for":res,"vendorID":vendorID,"date":date,"time":arg,"total_shit":total_sit,"floor":floor},function(val){
    $('#table_available<?= $resBanner11['floorno'];?>').html(val);
   // alert(res);
  })
  <?php }?>
  
  })
      
      
  }
  

  </script>
  
  
<!-- menu Ajax Start -->

  <script>
    var i=0;
  function addItemPrice(val)
  {
       var j=i+1;
        var div = $('<tr id="myTableRow'+j+'" />');
        div.html(GetDynamicTextBox(""));
        $("#TextBoxContainer").append(div);
        
        //   $('#itemselected'+val).attr('disabled', true);
        
        
        GetProdetails(i,val);
    
  }
    
 function removeTR(arg) {

  
        $('#myTableRow'+arg).remove();
        
        //enable product check box
        
        GrandTotal();
 
}

function GetDynamicTextBox(value) {
    
  
    i++;
return  '<td><input name = "product[]" type="text" class="form-control product" id="product'+i+'" required readonly/></td>' + '<td><select name = "variation[]" onchange="getvariation('+i+')"  class="form-control variation" id="variation'+i+'" required ></select></td>' + '<td><input class="form-control" name = "qty[]" type="number" min="1" id="qty'+i+'" onkeyup="QuantityPrice('+i+')" onclick="QuantityPrice('+i+')" autocomplete="off" required/></td>' + '<td><input class="form-control" name = "sub_total[]" type="text"  id="sub_total'+i+'" readonly required/></td>' + '<td><button type="button" onclick="removeTR('+i+')" class="btn btn-danger"><i class="glyphicon glyphicon-remove-sign"></i></button></td>'
}</script>

<script>

function GetProdetails(arg,val){
 
 //alert(arg);exit();
  
  var product = $('#product'+arg);
  var variation = $('#variation'+arg);
  var qty = $('#qty'+arg);
 
  var product_id=val;


  // alert(product.val());
  var url="getAjax.php";
  $.post(url,{"choice":"purchae_Rate","prod_id":product_id},function(res){
    // alert(res);exit();
    var Party_qrt_rate= res.split('!next!');
    product.val(Party_qrt_rate[0]);
   variation.html(Party_qrt_rate[1]);
    qty.val(1);
   
    //GrandTotal();
    
})
}

function getvariation(arg)
{
     var variation = $('#variation'+arg).val();
      var sub_total = $('#sub_total'+arg);
     var qty = $('#qty'+arg).val();
     var url="getAjax.php";
  $.post(url,{"choice":"getProPrice","value":variation},function(res){

sub_total.val(res*qty);
GrandTotal();
 // alert(res)
});
    
}

function QuantityPrice(arg){
    var variation = $('#variation'+arg).val();
    if(variation == ''){
        alert('Please select Variation');
       return false; 
    }else{
        
    var qty = $('#qty'+arg).val();
 var url="getAjax.php";
  $.post(url,{"choice":"getProPrice","value":variation},function(res){
      
      var sub_total = $('#sub_total'+arg).val(res*qty);
  GrandTotal();
      
  });
  
}
  
  
}



function discountFunc(){
  GrandTotal();
}

function paidAmount(){
  GrandTotal();
}

function GrandTotal(){
 
  Total=0;
var grandTotal = $('#grandTotal');

  for(x = 1; x <= i; x++) {
    //  alert($('#sub_total'+x).val())
    if($('#sub_total'+x).val()){
      var sub_total=$('#sub_total'+x).val();
    }else{
      var sub_total =0;
    }
  Total+= Number(sub_total);
  }
  
var Grand_total_val=Total;
  
  if($('#discount').val()){
    $discount=$('#discount').val();
  }else{
    $discount= 0;
  }
  Grand_total_val = Grand_total_val-$discount;

  if($('#paid').val()){
    var paid_amnt=$('#paid').val();
  }else{
    var paid_amnt=0;
  }
  $('#due').val(Number(Grand_total_val)-Number(paid_amnt));
  $('#grandTotalValue').val(Grand_total_val);
  grandTotal.val(Total);
}

</script>
 
 <script type="text/javascript">
  
   function  GetCitys(val) {
       // alert(val);
    
//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getCity","value":val},function(res){
 $('#cityres').html(res);
 // alert(res)
});
  
  } 
  function GetStates(arg){

//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getState","value":arg},function(res){
 $('#state').html(res);
// alert(res)
});
}
function UpGetStates(arg,arg1){

//$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getState","value":arg},function(res){
 $('#state'+arg1).html(res);
// alert(res)
});
}

function GetCItys(arg){

///$(".data").css('display','none');
var url="getAjax.php";
  $.post(url,{"choice":"getCity","value":arg},function(res){
 $('#City').html(res);
 // alert(res)
});
}

function upGetCItys(arg,arg2){
    var url="getAjax.php";
  $.post(url,{"choice":"getCity","value":arg},function(res){
 $('#City'+arg2).html(res);
//  alert(res)
});
}
function UpdateChangepin(arg,city=""){
  var url="getAjax.php";

  $.post(url,{"choice":"changPin","value":arg},function(res){
 $('#loc_selected_pin'+city).html(res);
// alert(res)
});
}

function UpdateChangearea(arg){
  var url="getAjax.php";
 // alert(arg);

  $.post(url,{"choice":"changeArea","value":arg},function(res){
 $('#loc_selected_area').html(res);
// alert(res)
});
}

function GetAllProduct(arg){
    
      var url="getAjax.php";
 // alert(arg);

  $.post(url,{"choice":"getProduct","value":arg},function(res){
 $('#addProductss').html(res);
 //alert(res);
});
 
// if (arg) {
//     var id=arg;
//     <?php $vendorID = "<script>document.write(id)</script>"?>   
// }
// if (!arg) {
//     console.log('no Product');
// }
}

</script>
   
   
   <?php include('footer.php')?>
