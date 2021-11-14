<!DOCTYPE html>
<html>
<head>
	<?php include("header.php"); ?>
</head>
<body>
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a  href="user-dashboard.php" class="close" >&times;</a>
             
            </div>
            <div class="modal-body">
				<img src="https://static.mytaxi.com/images/layout-1/icons/success.svg" width="50%">
                    
                </form>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function(){
		
		$('#myModal').modal({
    backdrop: 'static',
    keyboard: false
})

	});


</script>
<?php 
header("Location:user-dashboard.php");
?>
</body>
</html>