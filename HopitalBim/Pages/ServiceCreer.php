<?php
	// fonction qui permet d afficher les requetes sql et donc permet de jouer avec les données 
	/*  A FAIRE : 
		- plop

	*/
	include ('../Config/Menupage.php');
	include ('../Fonctions/Affichage.php');
	require_once("../session.php"); // requis pour se connecter la base de donnée 
	require_once("../classe.Systeme.php"); 	// va permettre d effectuer les requettes sql en orienté objet.

	$auth_user = new Systeme(); 			// PRIMORDIAL pour les requetes 
	$user_id = $_SESSION['idEmploye']; 		// permet de conserver la session
	$stmt = $auth_user->runQuery("SELECT * FROM CompteUtilisateurs WHERE idEmploye=:user_name"); // permet de rechercher le nom d utilisateur 
	$stmt->execute(array(":user_name"=>$user_id)); // la meme 
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC); // permet d afficher l identifiant du gars sur la page, ce qui faudrai c est le nom
	
	if(isset($_POST['btn-valider']))
{
 // Recuperation des champs entrés dans le formulaire : 
	// recuperation des information relatif à la table Services
	$text_nomService = ucfirst(trim($_POST['text_nomService']));	
	$text_telephone = strip_tags($_POST['text_telephone']);	
	$text_mail = $text_nomService."@hopitalbim.fr";   // l'adresse mail sera toujours = au nom de service+@hotpitalbim.fr
	$text_ouverture = date('h:i', strtotime($_POST['text_ouverture']));
	$text_fermeture = date('h:i', strtotime($_POST['text_fermeture'])); 
	// recuperation des information relatif à la table LocalisationServices
	$text_batiment = $_POST['text_batiment'];	
	$text_etage = $_POST['text_etage'];	
	$text_aile = $_POST['text_aile'];	
	
	// TEST si le service est deja present : 
	$stmt = $auth_user->runQuery("SELECT nomService FROM Services WHERE nomService=:nomService ");
	$stmt->execute(array('nomService'=>$text_nomService));
	$rechercheService=$stmt->fetch(PDO::FETCH_ASSOC);
		// Apres avoir realisé une requete pour rechercher les services, on va tester si celui est present dans la bdd
	if($text_nomService=="")	{
		$error[] = "Il faut ajouter un nom de service"; }
	else if ($rechercheService['nomService']==$text_nomService) {
		$error[] = "Le service est deja présent dans la base de donnée"; }
	else
	{
		try
		{
			// Ajout de la localisation en premier 
			$stmt = $auth_user->runQuery("SELECT * FROM LocalisationServices WHERE batiment=:batiment AND aile=:aile AND etage=:etage");
			$stmt->execute(array('batiment'=>$text_batiment, 'aile'=>$text_aile,'etage'=>$text_etage));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($row['batiment']==$text_batiment and $row['aile']==$text_aile and $row['etage']==$text_etage)  
			{
				$BddidLocalisation=$row['idLocalisation'];
				$ajoutService = $auth_user->runQuery("INSERT INTO Services (nomService, telephone, mail, horaire_ouverture, horaire_fermeture, LocalisationServicesidLocalisation) 
						VALUES (:nomService, :telephone, :mail, :horaire_ouverture, :horaire_fermeture, :LocalisationServicesidLocalisation) ");
				$ajoutService->execute(array('nomService'=>$text_nomService,
											'telephone'=>$text_telephone,
											'mail'=>$text_mail,
											'horaire_ouverture'=>$text_ouverture,
											'horaire_fermeture'=>$text_fermeture,
											'LocalisationServicesidLocalisation'=>$BddidLocalisation));
			}
			else
			{	
				//Ajout de la localisation 
				$ajoutLocalisation = $auth_user->runQuery("INSERT INTO LocalisationServices (batiment, aile, etage) 
															VALUES (:batiment, :aile, :etage) ");  // preparation de la requete SQL
				$ajoutLocalisation->execute(array('batiment'=>$text_batiment,
											'aile'=>$text_aile,
											'etage'=>$text_etage));   // execution de la requete SQL, ajout de la localisation du service 
				$stmt = $auth_user->runQuery("SELECT MAX(idLocalisation) FROM LocalisationServices");  // recuperation du dernier id rentrée
				$stmt->execute(); // recuperation du dernier id rentrée
				$BddidLocalisation = $stmt->fetch(PDO::FETCH_ASSOC)["MAX(idLocalisation)"]; // recuperation du dernier id localisation entrée dans la BDD
				
				$ajoutService = $auth_user->runQuery("INSERT INTO Services (nomService, telephone, mail, horaire_ouverture, horaire_fermeture, LocalisationServicesidLocalisation) 
						VALUES (:nomService, :telephone, :mail, :horaire_ouverture, :horaire_fermeture, :LocalisationServicesidLocalisation) "); // preparation de la requete SQL
				$ajoutService->execute(array('nomService'=>$text_nomService,
											'telephone'=>$text_telephone,
											'mail'=>$text_mail,
											'horaire_ouverture'=>$text_ouverture,
											'horaire_fermeture'=>$text_fermeture,
											'LocalisationServicesidLocalisation'=>$BddidLocalisation));   // execution de la requete SQL et ajout du service dans la base 
			} 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		$auth_user->redirect('ServiceCreer.php?Valide'); // une fois l ensemble des messages affiché, 
	}

}



?>
<!DOCTYPE html PUBLIC >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>ServiceCreer</title>
</head>

<body>
<p class="h4">Session : <?php print($userRow['idEmploye']); ?></p> 
<p class="" style="margin-top:5px;">

<div class="signin-form">
    	
        <form method="post" class="form-signin">

            <h2 class="form-signin-heading">Ajouter un nouveau service</h2><hr />

            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class=""></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['Valide']))
			{
			?>
                 <div class="alert alert-info">
                      <i class=""></i>Service ajouté avec succes<br><a href='Pageprincipale.php'>Page principale</a>
                 </div>
            <?php
			}
			?>
			
			<div class="form-group" >
			
			<fieldset>
			<legend> Localisation </legend> <!-- Titre du fieldset --> 
			<p>
				<input type="text" class="form-control" name="text_nomService" pattern="[A-Za-z]{1-20}" title="Majuscule en première lettre"        placeholder="Nom du service :" value="<?php if(isset($error)){echo $text_nomService;}?>" /><br><br>
				<input type="tel" class="form-control" name="text_telephone" pattern="[0-9]{1-15}" title="Veuillez rentrer un n° de téléphone correct"    placeholder="N° téléphone :" value="<?php if(isset($error)){echo $text_telephone;}?>" /><br><br>
				<label class="form-control"> Horaire d'ouverture : &nbsp;&nbsp;
				<input type="time" class="form-control" name="text_ouverture" value="<?php if(isset($error)){echo $text_ouverture;}?>" /><br><br>
				<label class="form-control"> Horaire de fermeture : &nbsp;&nbsp;
				<input type="time" class="form-control" name="text_fermeture" value="<?php if(isset($error)){echo $text_fermeture;}?>" />
			</p>
			</fieldset>
			
			<fieldset>
			<legend> Localisation </legend> <!-- Titre du fieldset --> 
			<p>
				Batiment : 
				<select name="text_batiment">
					<option value="A">A</option>
					<option value="B">B</option>
					<option value="C">C</option>
					<option value="D">D</option>
					<option value="E">E</option>
					<option value="F">F</option>
				</select><br><br>  
				Etage :  
				<select name="text_etage"> 
					<option value="0">RDC</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="-1">-1</option>
				</select><br><br> 
				Aile :  
				<select name="text_aile">
					<option value="a">a</option>
					<option value="b">b</option>
					<option value="c">c</option>
					<option value="d">d</option>
					<option value="e">e</option>
					<option value="f">f</option>
				</select>
			
			</p>
			</fieldset>
			
			</div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-valider">
                	<i class=""></i>Valider
                </button>
            </div>
        </form>
       </div>
</div>
	<?php 	quitter1()	;?>	
</body>


</html>
