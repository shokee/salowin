<form action="" enctype="multipart/form-data" method="post">

<div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Attribute of <?php echo $product['product_name'];?></h4>
               <input type="hidden" name="product_id" id=""  value="<?php echo $product['product_id'];?>"  />
              </div>
              <div class="modal-body">
               <div class="table table-responsive">
<table class="table table-responsive table-striped table-bordered">
<thead>
	<tr>
    	<td>Attribute</td>
    	<td>Variation with | Separetor</td>
    	<td>BTN</td>
    </tr>
</thead>
	<tr>
    	<td> <input type="text" class="form-control" name="attribute[]" placeholder="Enter Attribute like Colr , Size etc" required /></td>
    	<td><input type="text" class="form-control" name="variation[]" placeholder="Enter variation with separator" required /></td>
    	
    </tr>
<tbody id="TextBoxContainer<?php echo $product['product_id'];?>">
</tbody>
<tfoot>
  <tr>
    <th colspan="5">
  <button id="btnAdd<?php echo $product['product_id'];?>" type="button" class="btn btn-primary" data-toggle="tooltip" data-original-title="Add more controls"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp; Add&nbsp;</button></th>
  </tr>
</tfoot>
</table>
</div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" value="AddVariation" name="operation">Save changes</button>
              </div>
			  
<table class="table">
 <tr>
	<th>Attribute</th>
	<th>Variation</th>
	<th>Action</th>
</tr>
<?php foreach($dbf->fetchOrder("attribute","product_id='$product[product_id]'","","","") as $Attir){
	
	$Varition=$dbf->fetchOrder("variation","attribute_id='$Attir[attribute_id]'");
	$ArryVari=array();
	foreach($Varition as $vari){
		array_push($ArryVari,$vari['variation_name']);
	}
	?>
<tr id="vari<?= $Attir['attribute_id']?>">
	<td><?= $Attir['attribute_name']?></td>
	<td><?php echo  implode('|',$ArryVari)?></td>
	<td><button type="button" class="btn btn-danger" onclick="DelVari(<?= $Attir['attribute_id']?>)"><i class="glyphicon glyphicon-remove-sign"></i></button></td>
</tr>
<?php }?>
 </table>
            </div>
 </form>

 <script>
 $(function () {
    $("#btnAdd<?php echo $product['product_id'];?>").bind("click", function () {
        var div = $("<tr />");
        div.html(GetDynamicTextBox(""));
        $("#TextBoxContainer<?php echo $product['product_id'];?>").append(div);
    });
    $("body").on("click", ".remove", function () {
        $(this).closest("tr").remove();
    });
});
function GetDynamicTextBox(value) {
    return '<td><input name = "attribute[]" type="text" value = "' + value + '" class="form-control" /></td>' + '<td><input name = "variation[]" type="text"  value = "' + value + '" class="form-control" /></td>'  + '<td><button type="button" class="btn btn-danger remove"><i class="glyphicon glyphicon-remove-sign"></i></button></td>'
}
</script>

 <script>
  function DelVari(arg){
	 var conf=confirm("Are you sure want to delete this Record?");
    if(conf){
       document.getElementById('vari'+arg).style.display = "none"; 
	   
	   var url="getAjax.php";
 $.post(url,{"choice":"variDel","vari_id":arg},function(res){
 
 //alert(res);
});
    }
	
	  }
  </script>
 </script>