<?php 
	
function Dumper ($var){ // affichage des valeurs des tableaux
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}


function liste_Services($auth_user) // affiche un menu déroulant listant les services proposé par l'hopital
	{ 
?>
	<div class="form-group" >
		<select name="text_nomService">
<?php 
			$stmt = $auth_user->runQuery("SELECT nomService FROM Services"); // permet de rechercher le nom d utilisateur 
			$stmt->execute(); // la meme 
			echo "<option value='NULL'>Standard</option>";
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			echo "<option value='".$row['nomService']."'>".$row['nomService']."</option>";
			}
	?>
			</select></br >
		</div>
	
<?php
	}



function Affiche_dateFr($d) // AFFICHE la date au format francais (ex : jeudi 2 novembre 2017)
	{
		setlocale(LC_TIME, 'fr_FR.utf8','fra'); 
		$date_time = new DateTime($d);
		
		$intl_date_formatter = new IntlDateFormatter('fr_FR',
													 IntlDateFormatter::FULL,
													 IntlDateFormatter::NONE);
		echo $intl_date_formatter->format($date_time);
	}



	
function test($auth_user)
	{ 
?>
	<div class="form-group" >
		<input type="text" class="" name="text_numSS" pattern="[0-9]{15}" title="Caractère numérique, 15 caractères acceptés"        placeholder="Numero Securité Sociale :" value="<?php if(isset($error)){echo $text_numSS;}?>" /><br>
	</div>
	
<?php
	}


function quitter1()
	{
?>	
	<div class= "form-group"> 
		<?php $LienSite = 'http://'.$_SERVER['HTTP_HOST'].'/projetm2/HopitalBim/';?> 
	
		<a href="<?php echo $LienSite ?>Pageprincipale.php">
		<input type="button" name="Abbandonner "value="Abbandonner"/></a>
	</div>
<?php 
	}
?>	

<?php function quitter2()
	{
?>	
	<div class="btn btn-primary" > 
		<?php $LienSite = 'http://'.$_SERVER['HTTP_HOST'].'/projetm2/HopitalBim/';?> 
		<a href="<?php echo $LienSite ?>Pageprincipale.php" title="Abbandonner"><img src="<?php echo $LienSite ?>Images/quitter.jpeg" width="150" "/></a>
	</div>
<?php 
	}
?>

