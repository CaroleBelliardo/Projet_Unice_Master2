<?php $LienSite = 'http://'.$_SERVER['HTTP_HOST'].'/projetm2/HopitalBim/';?> 

<!DOCTYPE html>
<html>
<body>
<div class="container-fluid" style="margin-top:0px;">
    <div class="container">
        <hr />
        <h3>
		<a href="<?php echo $LienSite ?>Pageprincipale.php">Home</a> &nbsp; 
		<a href="<?php echo $LienSite ?>Patient.php">Patient</a> &nbsp; 
		<a href="<?php echo $LienSite ?>Maintenance.php">Maintenance</a> &nbsp; 
		<a href="<?php echo $LienSite ?>logout.php?logout=true">Deconnection</a> &nbsp; 
		</h3>
       	<hr />
</body>
</html>

