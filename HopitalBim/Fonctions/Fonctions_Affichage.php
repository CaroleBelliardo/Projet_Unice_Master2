<?php 
	
function Dumper ($var){ // affichage des valeurs des tableaux
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}




function quitter1()
	{
?>	
	<div class= "form-group"> 
	<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/projetm2/HopitalBim/Pageprincipale.php';?> "><button class="abandon">Abandonner</button></a>
	</div>
<?php 
	}
?>
