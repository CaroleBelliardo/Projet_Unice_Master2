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
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
      echo "<option value='".$row['nomService']."'>".$row['nomService']."</option>"; 
      } ?> 
      </select> 
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
 
 
 

function quitter1()
	{
?>	
	<div class= "form-group"> 
	<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/projetm2/HopitalBim/Pageprincipale.php';?> "><button class="abandon">Abandonner</button></a>
	</div>
<?php 
	}
?>
