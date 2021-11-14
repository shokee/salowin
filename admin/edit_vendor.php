<?php include('header.php')?>
  <?php include('sidebar.php');
      $Vendor_id=$dbf->checkSqlInjection($_REQUEST['id']);
  $agent=$dbf->fetchSingle("user",'*',"user_type='3' AND id='$Vendor_id'");
  ?>
  <style>
#myMap {
   height: 500px;
   width: 100%;
}
</style>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCm-V7-i7-hH5pI0nMxjb6l064Ma30xt-Q"></script>



 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Vendor
        <small>Update Vendor</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Vendor</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
    <form action="" enctype="multipart/form-data" method="post" id="UpdateVendor">
      
    <input type="hidden" name="Submits" value="editagent">
    <input type="hidden" class="form-control" name="pagesId"  value="<?php echo $agent['id'];?>">
    
       <div class="row">
        <!-- left column -->
        <div class="col-md-12">
       
          <!-- general form elements -->
          <div class="box box-primary">
          <div class="row">
          <span class="text-success" id="customerSucces"></span>
         <span class="text-danger" id="customerError"></span>
          	<div class="col-sm-6">
              <div class="box-body">
                <div class="form-group">
                    <label >Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $agent['full_name'];?>" required>
                </div>
             </div>
            </div>
            <div class="col-sm-6">
             <div class="box-body">
                <div class="form-group">
                    <label >Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $agent['email'];?>" required>
                </div>
              </div>  
            </div>
            <div class="col-sm-6">
             <div class="box-body">
                <div class="form-group">
                <label >Contact No</label>
                <input type="tel" class="form-control" id="contat" name="contact" value="<?php echo $agent['contact_no'];?>"  title="Enter Only 10 digit Mobile no "  pattern="[1-9]{1}[0-9]{9}" required>
              </div>
             </div> 
            </div>
            <div class="col-sm-6">
             <div class="box-body">
           		 <div class="form-group">
                  <label >Profile Image</label>
                  <input type="file" class="form-control" id="img" name="img" accept="image/*">
                </div>
             </div>
            </div>
           <div class="col-sm-6">
            <div class="box-body">
            <div class="form-group">
                  <label >Username</label>
                  <input type="text" class="form-control" id="username" name="username" value="<?php echo $agent['user_name'];?>" required>
            </div>
           </div> 
         </div>
            
         <div class="col-sm-6">
          <div class="box-body">
          <div class="form-group">
                  <label >Password</label>
                  <input type="text" class="form-control" id="password" name="password" value="<?php echo base64_decode(base64_decode($agent['password']));?>" required>
            </div>
           </div> 
         </div>
         
         <div class="col-sm-6">
          <div class="box-body">
          <div class="form-group">
                  <label >Vendor Commition (in % only)</label>
                  <input type="text" class="form-control" id="commition"  name="commition" value="<?php echo $agent['commition'];?>" >
            </div>
           </div> 
         </div>
         
            <div class="col-sm-6">
             <div class="box-body">
           		 <div class="form-group">
                  <label >ID Proof (Voter/Adhar Copy)</label>
                  <input type="file" class="form-control" id="idproof"  name="idproof"  accept="image/*">
                </div>
             </div>
            </div>
         

         <div class="col-sm-6">
          <div class="box-body">
          <div class="form-group">
                  <label >Shop Name</label>
                  <input type="text" class="form-control" id="shopname" name="shopname" value="<?php echo $agent['shop_name'];?>" >
            </div>
           </div> 
        </div>

        <div class="col-sm-6">
         <div class="box-body">
          <div class="form-group">
                  <label >Shop Address </label>
                  <input type="text" class="form-control search_addr" id="address" value="<?php echo $agent['address1'];?>" >
          </div>
        </div> 
       </div>
       
        <div class="col-sm-6">
             <div class="box-body">
           		 <div class="form-group">
                  <label >Shop Registration Certificate Copy</label>
                  <input type="file" class="form-control" id="shopcopy"  name="shopcopy"   accept="image/*">
                </div>
             </div>
            </div>
       
       <div class="col-sm-6">
        <div class="box-body">
         <div class="form-group">
                <label>Select Catagory</label>
                <select class="form-control select2" multiple="multiple" data-placeholder="Select a State" name="catagory_id[]" style="width: 100%;" required>
    			<?php  foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","","") as $CatName){?>
    			<option value="<?=$CatName['product_catagory_1_id']?>" 
                <?php  foreach($dbf->fetchOrder("vendor_catagory","vendor_id='$agent[id]'","","","") as $vendorCate){ 
                if($vendorCate['catagory_id']==$CatName['product_catagory_1_id']){ echo "selected";}}?>>
                <?=$CatName['product_catagory_1_name']?></option>
   			    <?php }?>
                </select>
          </div>
        </div>
        </div>

        <div class="col-sm-6">
         <div class="box-body">
          <div class="form-group" >
                  <label >GST No.</label>
                  <input type="text" class="form-control" id="gstnum" name="gst" value="<?php echo $agent['gst_no'];?>">
            </div>
         </div>   
        </div>
        
         <div class="col-sm-6">
             <div class="box-body">
           		 <div class="form-group">
                  <label >GST Copy</label>
                  <input type="file" class="form-control" id="gstcopy" name="gstcopy"   accept="image/*">
                </div>
             </div>
            </div>
            
                <div class="col-sm-6">
         <div class="box-body">
          <div class="form-group" >
                  <label >Account No.</label>
                  <input type="text" class="form-control" id="accno"  name="accno" value="<?php echo $agent['ac_num'];?>"  data-autocomplete="off" >
            </div>
         </div>   
        </div>
        
           <div class="col-sm-6">
         <div class="box-body">
          <div class="form-group" >
                  <label >IFSC Code</label>
                  <input type="text" class="form-control" id="ifsc" name="ifsc" value="<?php echo $agent['ifsc_code'];?>"  data-autocomplete="off" >
            </div>
         </div>   
        </div>
        
           <div class="col-sm-6">
             <div class="box-body">
           		 <div class="form-group">
                  <label >Passbook Copy</label>
                  <input type="file" class="form-control" id="passbook" name="passbook"   accept="image/*">
                </div>
             </div>
            </div>

        <div class="col-sm-6">
         <div class="box-body">
         <div class="form-group">
                <label class="" for="inlineFormCustomSelect">Select Country</label>
                <select class="form-control" name="country_id"  onChange="GetState(this.value)">
                    <?php  foreach($dbf->fetchOrder("country","","country_id ASC","","") as $DirName){?>
                    <option value="<?=$DirName['country_id']?>" <?php if($DirName['country_id']==$agent['country_id']){ echo"selected";}?>><?=$DirName['country_name']?></option>
                    <?php }?>
                </select>
         </div>
        </div>
        </div>

        <div class="col-sm-6">
        <div class="box-body">
         <div class="form-group">
                <label class="" >Select State</label>
                <select class="form-control" name="state_id"   onChange="GetCity(this.value)" id="stateres">
                <?php  foreach($dbf->fetchOrder("state","Country_id='$agent[country_id]'","state_name","","") as $stateName){?>
                <option value="<?=$stateName['state_id']?>" <?php if($stateName['state_id']==$agent['state_id']){ echo"selected";}?>><?=$stateName['state_name']?></option>
                <?php }?>
                 </select>
         </div>
       </div>  
       </div>

        <div class="col-sm-6">
         <div class="box-body">
            <div class="form-group">
                 <label class="" for="inlineFormCustomSelect" >Select City</label>
                 <select class="form-control" name="city_id" onchange="UpdateChangepin(this.value)"  id="cityres">
                    <?php  foreach($dbf->fetchOrder("city","state_id='$agent[state_id]'","city_name","","") as $stateName){?>
                    <option value="<?=$stateName['city_id']?>" <?php if($stateName['city_id']==$agent['city_id']){ echo"selected";}?>><?=$stateName['city_name']?></option>
                    <?php }?>
                </select>
            </div>
         </div>
        </div>

        <div class="col-sm-6">
         <div class="box-body">
            	<div class="form-group">
                 <label class="" for="inlineFormCustomSelect">Select Pin</label>
                 <select class="uk-select"  name="zcode" id="zcodeup" >
                    <option value="">--Select Pincode--</option>
                    <?php  foreach($dbf->fetchOrder("pincode","city_id='$agent[city_id]'","pincode_id ASC","","") as $Pinsnam){?>
                    <option value="<?=$Pinsnam['pincode_id']?>" <?php if($Pinsnam['pincode_id']==$agent['pin']){ echo"selected";}?>><?=$Pinsnam['pincode']?></option>
                    <?php }?>
                </select>
            </div>
          </div>  
        </div>
            
        
        <!--<div class="col-sm-12">-->
        <!-- <div class="box-body">-->
        <!--    <div class="form-group">-->
        <!--        <input type="text" id="search_location" class="form-control search_addr" placeholder="Search location" value="<?php echo $agent['address1'];?>" required>-->


        <!--    <div id="myMap"></div>-->
        <!--    <input type="hidden" class="search_addr"  required name="address" value="<?php echo $agent['address1'];?>">-->
        <!--     <input type="hidden" class="search_latitude"  required name="lat" value="<?php echo $agent['lat'];?>">-->
        <!--     <input type="hidden" class="search_longitude" required name="lng" value="<?php echo $agent['lng'];?>">-->
        <!--    </div>-->
        <!--  </div>  -->
        <!--</div>-->
        
           
            <div class="col-sm-12">
          <div class="box-footer">
                <button type="submit" name="submit" id="submit" value="addproduct" class="btn btn-primary">Update</button>
              </div>
            </div>
          </div>
            

              
          </div>
          <!-- /.box -->
          </div>
           
          </div>
</form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Select2 -->
   <?php include('footer.php')?>
   <script type="text/javascript">
  	function SelectOnPin(arg){
  var url="getAjax.php";
  $.post(url,{"choice":"changPin","value":arg},function(res){
 $('#zcode').html(res);
// alert(res)
});
}

  	function UpdateChangepin(arg){
  var url="getAjax.php";
  $.post(url,{"choice":"changPin","value":arg},function(res){
 $('#zcodeup').html(res);
// alert(res)
});
}


var gstnum = document.getElementById('gstnum');


gstnum.addEventListener("input", onChangeGstdNumber);

function onChangeGstdNumber(e) {   
     var  gstnums = gstnum.value;

     var substr1 = gstnums.substring(0,2);
     var substr2 = gstnums.substring(3,8);
      var substr3 = gstnums.substring(8,12);
      var substr4 = gstnums.substring(12,13);
       var substr5 = gstnums.substring(13,15);
       var substr6 = gstnums.substring(15,17);
       var substr7 = gstnums.substring(17,19);
      
    // Do not allow users to write invalid characters
    var substrs1 = substr1.replace(/[^\d]/g, "");
     var substrs2 = substr2.replace(/[^a-zA-Z]+/, "");
      var substrs3 = substr3.replace(/[^\d]/g, "");
      var substrs4 = substr4.replace(/[^a-zA-Z]+/, "");
      var substrs5 = substr5.replace(/[^\d]/g, "");
      var substrs6 = substr6.replace(/[^a-zA-Z]+/, "");
      var substrs7 = substr7.replace(/[^\d]/g, "");

      var sect2=substrs2.toUpperCase()+substrs3+substrs4.toUpperCase();

      if(substrs1.length==2){
      
        output =substrs1+" ";
      }if(substrs1.length<2){
         output =substrs1;
      }
      if(sect2.length==10){
        output=output+sect2+" ";
      }if(sect2.length<10){
        output=output+sect2;
      }
      if(substrs5.length==1){
        output=output+substrs5+" ";
      }
      if(substrs5.length==0){
       
         output=output+substrs5;
      }
       if(substrs6.length==1){
        output=output+substrs6.toUpperCase()+" ";
      }
      if(substrs6.length==0){
       
         output=output+substrs6.toUpperCase();
      }
       if(substrs7.length==1){
        output=output+substrs7;
      }
      if(substrs7.length==0){
       
         output=output+substrs7;
      }
   // alert(substrs1)
   gstnum.value=output;
}

  </script>
  

  <script type="text/javascript">
    $(document).ready(function (e) {
 $("#UpdateVendor").on('submit',(function(e) {
    $("#divpreloader").css("display", "block");

  e.preventDefault();
  $.ajax({
         url: "getAjaxadd.php",
   type: "POST",
   data:  new FormData(this),
   contentType: false,
         cache: false,
   processData:false,
   beforeSend : function()
   {
    //console.log(this);
   },
   success: function(res)
      {
        
       // makes sure the whole site is loaded 
        $('#prestatus').fadeOut(); // will first fade out the loading animation 
        $('#divpreloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
        $('body').delay(350).css({'overflow':'visible'});
       
       
        document.getElementById("customerSucces").innerHTML="";
        document.getElementById("customerError").innerHTML="";
       if(res=='succes'){
      
document.getElementById("customerSucces").innerHTML="Vendor Updated  Successfully.";
            }else{
 if(res=='phnErro'){
  document.getElementById("customerError").innerHTML="Phone Number Already Exist.";
 }else if(res=='userErro'){
  document.getElementById("customerError").innerHTML="User Name Already Exist.";
 }else if(res=='imgError'){
  document.getElementById("customerError").innerHTML="Profile Image Not Correct format.";
 }else{
document.getElementById("customerError").innerHTML="Email Id Already Exist.";
 }
}
      },
     error: function(e) 
      {
    //$("#err").html(e).fadeIn();
      }          
    });
 }));
});
</script> 
<script>
var geocoder;
var map;
var marker;

/*
 * Google Map with marker
 */
function initialize() {
    var initialLat = $('.search_latitude').val();
    var initialLong = $('.search_longitude').val();
    initialLat = initialLat?initialLat:20.5937;
    initialLong = initialLong?initialLong:78.9629;

    var latlng = new google.maps.LatLng(initialLat, initialLong);
    var options = {
        zoom: 18,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("myMap"), options);

    geocoder = new google.maps.Geocoder();

    marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: latlng
    });

    google.maps.event.addListener(marker, "dragend", function () {
        var point = marker.getPosition();
        map.panTo(point);
        geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                marker.setPosition(results[0].geometry.location);
                $('.search_addr').val(results[0].formatted_address);
                $('.search_latitude').val(marker.getPosition().lat());
                $('.search_longitude').val(marker.getPosition().lng());
            }
        });
    });

}


    //load google map
    initialize();
    
    /*
     * autocomplete location search
     */
    var PostCodeid = '#search_location';
    $(function () {
        $(PostCodeid).autocomplete({
            source: function (request, response) {
                geocoder.geocode({
                    'address': request.term
                }, function (results, status) {
                    response($.map(results, function (item) {
                        return {
                            label: item.formatted_address,
                            value: item.formatted_address,
                            lat: item.geometry.location.lat(),
                            lon: item.geometry.location.lng()
                        };
                    }));
                });
            },
            select: function (event, ui) {
                $('.search_addr').val(ui.item.value);
                $('.search_latitude').val(ui.item.lat);
                $('.search_longitude').val(ui.item.lon);
                var latlng = new google.maps.LatLng(ui.item.lat, ui.item.lon);
                marker.setPosition(latlng);
                initialize();
            }
        });
    });
    
    /*
     * Point location on google map
     */
    $('.get_map').click(function (e) {
        var address = $(PostCodeid).val();
        geocoder.geocode({'address': address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                marker.setPosition(results[0].geometry.location);
                $('.search_addr').val(results[0].formatted_address);
                $('.search_latitude').val(marker.getPosition().lat());
                $('.-').val(marker.getPosition().lng());
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
        e.preventDefault();
    });

    //Add listener to marker for reverse geocoding
    google.maps.event.addListener(marker, 'drag', function () {
        geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('.search_addr').val(results[0].formatted_address);
                    $('.search_latitude').val(marker.getPosition().lat());
                    $('.search_longitude').val(marker.getPosition().lng());
                }
            }
        });
    });

</script>