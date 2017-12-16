<?php 
	
function Dumper ($var){ // affichage des valeurs des tableaux
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
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
 

function quitter1()
	{
?>	
	<div class= "form-group"> 
	<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/projetm2/HopitalBim/Pageprincipale.php';?> "><button class="abandon">Abandonner</button></a>
	</div>
<?php 
	}
?>
