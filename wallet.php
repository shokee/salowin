<?php include("header.php"); ?>
<div class="uk-container">
<div class=" uk-card-body uk-border-rounded uk-text-center " >
                                 
    <h4 class="uk-margin-remove" >Wallet Balance: </h4>
    
  
    
    <h1 class="uk-margin-remove"  >&#8377;<?= number_format($profile['wallet'],2)?></h1>
  
  

 
    
                        </div>
                        </div>
<?php 
$footerIcon='Wallet';
include("footer.php"); ?>