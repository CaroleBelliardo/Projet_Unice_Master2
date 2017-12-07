<?php
	// fonction qui permet d afficher les requetes sql et donc permet de jouer avec les données 
	/*  A FAIRE : 
		- champs html du departement et du pays afficher une liste ( plutot que de le taper) 
		- les includes a faire.
	*/

include ('../Config/Menupage.php');
	
if(isset($_POST['btn-signup']))
{	
	$text_departement = trim($_POST['text_departement'], ' ' );
	$text_pays = ucfirst(trim($_POST['text_pays'], ' '))	;

	$text_ville = ucfirst(trim($_POST['text_ville'], ' '))	;
	$text_codepostal = str_replace(' ','',$_POST['text_codepostal']);

	$text_numero = trim($_POST['text_numero'], ' ' );
	$text_rue = ucfirst(trim($_POST['text_rue'], ' '))	;

	$text_nom =  ucfirst(trim($_POST['text_nom'], ' '))	;
	$text_prenom = ucfirst(trim($_POST['text_prenom'], ' '))	;

	$text_telephone = trim($_POST['text_telephone'], ' ' );
	$text_chef = strip_tags($_POST['text_chef']);
	$text_nomService = strip_tags($_POST['text_nomService']);
	$text_motdepasse = strip_tags($_POST['text_motdepasse']);
	$text_motdepasse2 = strip_tags($_POST['text_motdepasse2']);

	$req_rechercheNomService = $auth_user->runQuery("SELECT nomService
									FROM Services
									WHERE nomService=:nomService");
	$req_rechercheNomService->execute(array('nomService'=>$text_nomService));
	$rechercheNomService=$req_rechercheNomService->fetch(PDO::FETCH_ASSOC);

	// Gestion des erreurs : 
	if((preg_match('/[0-9]+/',$text_nom) == 1)or ($text_nom=="")) {
		$error[] = "Veuillez entrer un nom uniquement composé de lettres !";}
	else if((preg_match('/[0-9]+/',$text_prenom) == 1)or ($text_prenom=="")) {
		$error[] = "Veuillez entrer un prénom uniquement composé de lettres !";}
	else if((preg_match('/[0-9]+/',$text_numero) == 0)or ($text_numero=="") )	{
		$error[] = "Veuillez entrer un numéro de rue !"; }
	else if($text_rue=="" )	{
		$error[] = "Il faut entrer un nom de rue valide !"; }
	else if($text_ville=="" )	{
		$error[] = "Veuillez entrer le nom d'une ville valide !"; }
	else if((strlen($text_codepostal) > 5) or  ($text_codepostal==""))	{
		$error[] = "Il faut entrer un code postal valide !"; }
	else if((strlen($text_departement) > 3)or  ($text_departement=="")) 	{
		$error[] = "Veuillez entrer un numéro de département de maximum 3 caractères alphanumériques (entrez 99 si le patient réside à l'étranger) !"; }
	else if ((preg_match('/[0-9]+/',$text_pays) == 1)or ($text_pays=="") or (strlen($text_pays) > 25))	{
		$error[] = "Veuillez entrer un pays (caractères numériques non acceptés)!"; }
	else if ($rechercheNomService['nomService'] ==""){
		$error[] = "Veuillez choisir un service valide !"; }
	// TEST SI NUMSS deja present
	else if ($text_motdepasse != $text_motdepasse2 ) {
		$error[] = "Vos deux mots de passe sont différents"; }
	else if (strlen($text_motdepasse) < 8) {
		$error[] = "Votre mot de passe est trop court"; }
	else 
	{ 
		// Creation du nom d'utilisateur et verification : 
		$UtilisateurNom = $text_nom[0].$text_prenom[0].$text_chef.date('is');
		$text_mail= $UtilisateurNom."@hopitalbim.fr";
		
		//$UtilisateurNom = 'cm14743';
		$stmt = $auth_user->runQuery("SELECT idEmploye FROM CompteUtilisateurs WHERE idEmploye=:UtilisateurNom");
		$stmt->execute(array(':UtilisateurNom'=>$UtilisateurNom));
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		if ($row['idEmploye']==$UtilisateurNom) {$error[] = "Nom d'utilisateur déjà existant !"; }
		else 
		{
			//Ajout des informations dans la base de donnée :
	
			try
			{
				//creation de l'utilisateur
				$auth_user->creerUtilisateur($UtilisateurNom,$text_motdepasse);
				
				
				$stmt = $auth_user->runQuery("SELECT * FROM Villes 
										WHERE codepostal=:text_codepostal AND nomVilles=:text_ville 
										AND departement=:text_departement AND pays=:text_pays");
				$stmt->execute(array('text_codepostal'=>$text_codepostal, 'text_ville'=>$text_ville, 'text_departement'=>$text_departement, 'text_pays'=>$text_pays));
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				$BDDidVilles=$row['idVilles'];
				if ($row['codepostal']==$text_codepostal and  $row['nomVilles']==$text_ville and $row['departement']==$text_departement and $row['pays']==$text_pays) 
				{
					// Test de l'adresse
					$stmt = $auth_user->runQuery("SELECT * FROM Adresses 
										WHERE numero=:text_numero AND rue=:text_rue AND VillesidVilles=:BDDidVilles");
					$stmt->execute(array('text_numero'=>$text_numero, 'text_rue'=>$text_rue, 'BDDidVilles'=>$BDDidVilles));
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$BDDidAdresse=$row['idAdresse'];
					if ($row['numero']==$text_numero and  $row['rue']==$text_rue and $row['VillesidVilles']==$BDDidVilles )
					{
						$ajoutemployes = $auth_user->conn->prepare("INSERT INTO Employes ( CompteUtilisateursidEmploye, nom, prenom,telephone,mail,ServicesnomService,AdressesidAdresse)
						VALUES (:UtilisateurNom, :text_nom,:text_prenom,:text_telephone, :text_mail,:text_nomService,:BDDidAdresse)");
						$ajoutemployes->execute(array(":UtilisateurNom"=>$UtilisateurNom,
													  ":text_nom"=>$text_nom,
													  ":text_prenom"=>$text_prenom,
													  ":text_telephone"=>$text_telephone,
													  ":text_mail"=>$text_mail,
													  ":text_nomService"=>$text_nomService,
													  ":BDDidAdresse"=>$BDDidAdresse));						
					}
					else 
					{
						$stmtAdresses = $auth_user->conn->prepare("INSERT INTO Adresses (numero, rue, VillesidVilles) 
											VALUES (:text_numero, :text_rue, :BDDidVilles )");			
						$stmtAdresses->execute(array(":text_numero"=>$text_numero,
													  ":text_rue"=>$text_rue,
													  ":BDDidVilles"=>$BDDidVilles));
						$stmt = $auth_user->runQuery("SELECT * FROM Adresses 
										WHERE numero=:text_numero AND rue=:text_rue AND VillesidVilles=:BDDidVilles");
						$stmt->execute(array('text_numero'=>$text_numero, 'text_rue'=>$text_rue, 'BDDidVilles'=>$BDDidVilles));
						$row=$stmt->fetch(PDO::FETCH_ASSOC);
						$BDDidAdresse=$row['idAdresse'];
						// -- Ajout Employes
						$ajoutemployes = $auth_user->conn->prepare("INSERT INTO Employes ( CompteUtilisateursidEmploye, nom, prenom,telephone,mail,ServicesnomService,AdressesidAdresse)
															VALUES (:UtilisateurNom, :text_nom,:text_prenom,:text_telephone, :text_mail,:text_nomService,:BDDidAdresse)");
						
						$ajoutemployes->execute(array(":UtilisateurNom"=>$UtilisateurNom,
													  ":text_nom"=>$text_nom,
													  ":text_prenom"=>$text_prenom,
													  ":text_telephone"=>$text_telephone,
													  ":text_mail"=>$text_mail,
													  ":text_nomService"=>$text_nomService,
													  ":BDDidAdresse"=>$BDDidAdresse));
					}
				}
				else 
				{
					// -- Ajout dans la table ville 
					$stmtville = $auth_user->conn->prepare("INSERT INTO Villes ( codepostal, nomVilles, departement, pays) 
													VALUES ( :text_codepostal, :text_ville, :text_departement, :text_pays)");	
					$stmtville->execute(array(":text_codepostal"=>$text_codepostal,
											  ":text_ville"=>$text_ville,
											  ":text_departement"=>$text_departement,
											  ":text_pays"=>$text_pays));										  
					$stmt = $auth_user->runQuery("SELECT * FROM Villes 
											WHERE codepostal=:text_codepostal AND nomVilles=:text_ville 
											AND departement=:text_departement AND pays=:text_pays");
					$stmt->execute(array('text_codepostal'=>$text_codepostal, 
										 'text_ville'=>$text_ville, 
										 'text_departement'=>$text_departement, 
										 'text_pays'=>$text_pays));
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$BDDidVilles=$row['idVilles'];
					// -- Ajout dans adresse
					$stmtAdresses = $auth_user->conn->prepare("INSERT INTO Adresses (numero, rue, VillesidVilles) 
													VALUES (:text_numero, :text_rue, :BDDidVilles )");	
												
					$stmtAdresses->execute(array('text_numero'=>$text_numero, 
										 'text_rue'=>$text_rue, 
										 'BDDidVilles'=>$BDDidVilles));
					$stmt = $auth_user->runQuery("SELECT * FROM Adresses 
												WHERE numero=:text_numero AND rue=:text_rue AND VillesidVilles=:BDDidVilles");
					$stmt->execute(array('text_numero'=>$text_numero, 'text_rue'=>$text_rue, 'BDDidVilles'=>$BDDidVilles));
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$BDDidAdresse=$row['idAdresse'];
					// -- Ajout Employes
					$ajoutemployes = $auth_user->conn->prepare("INSERT INTO Employes ( CompteUtilisateursidEmploye, nom, prenom,telephone,mail,ServicesnomService,AdressesidAdresse)
						VALUES (:UtilisateurNom, :text_nom,:text_prenom,:text_telephone, :text_mail,:text_nomService,:BDDidAdresse)");
						
					$ajoutemployes->execute(array(":UtilisateurNom"=>$UtilisateurNom,
												  ":text_nom"=>$text_nom,
												  ":text_prenom"=>$text_prenom,
												  ":text_telephone"=>$text_telephone,
												  ":text_mail"=>$text_mail,
												  ":text_nomService"=>$text_nomService,
												  ":BDDidAdresse"=>$BDDidAdresse));
				}
				//Creation du login et recupperation du mot de passe 
				//$auth_user->creerUtilisateur($UtilisateurNom,$text_motdepasse);
				//$auth_user->redirect('CompteUtilCreer.php?Valide');
				if ($text_chef =='1')
				{
					$ajoutchef = $auth_user->conn->prepare("INSERT INTO ChefServices ( EmployesCompteUtilisateursidEmploye, ServicesnomService) 
													VALUES ( :UtilisateurNom, :text_nomService)");	
					$ajoutchef->execute(array(':UtilisateurNom'=>$UtilisateurNom, 
												':text_nomService'=>$text_nomService));
				}
			$auth_user->redirect('CompteUtilCreer.php?Valide');
			}
			catch(PDOException $e)
			{			
				echo $e->getMessage();
			}	
		}
		
	}

}

?>

<!DOCTYPE html PUBLIC >
<html>
	<head>
		<title>Ajouter un utilisateur</title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" href="../Config/Style.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
	</head>

	<body>
    
		<div class="containerFormu">
    	
			<form method="post" class="form-signin">

            	<h2 class="form-signin-heading">Ajouter un utilisateur</h2> <hr />

            	<?php
				if(isset($error))
				{
			 		foreach($error as $error)
			 		{
				?>

                <div id="error"> &nbsp; <?php echo $error; ?> </div> <!-- Alert alert-danger-->

				<?php
					}
				}
				else if(isset($_GET['Valide']))
				{
				?>

                <div id="valide"> <!-- Alert alert-info-->
                	Utilisateur enregistré avec succés ! <a href='../Pageprincipale.php'>Page principale</a>
                </div>

            	<?php
				}
				?>

				<p><i>Complétez le formulaire. Les champs marqués par </i><em>*</em> sont <em>obligatoires.</em></p>

            	<div class="form-group" >

					<fieldset>
					<legend> Employé </legend> <!-- Titre du fieldset --> 
			
						<label for="text_nom">Nom <em>* </em> </label>
						<input type="text" class="form-control" name="text_nom" pattern="[A-Za-z]{1-25}" title="Caractère alphabétique, 25 caractères maximum"     placeholder=" Nom " value="<?php if(isset($error)){echo $text_nom;}?>" /><br>

						<label for="text_prenom">Prénom <em>* </em> </label>
						<input type="text" class="form-control" name="text_prenom" pattern="[A-Za-z]{1-25}" title="Caractère alphabétique, 25 caractères maximum"  placeholder=" Prénom " value="<?php if(isset($error)){echo $text_prenom;}?>" /><br>

						<label for="text_telephone">Téléphone </label>
						<input type="tel" class="form-control" name="text_telephone" pattern="[0-9]{1-15}" title="Caractère numérique, 15 caractères acceptés"    placeholder=" 06xxxxxxxx" value="<?php if(isset($error)){echo $text_telephone;}?>" /><br>

						<label for="text_nomService"> Service </label>
							<input list="text_nomService" name="text_nomService" size='85'> 
							<datalist id="text_nomService" >
								<?php 
								$req_service = $auth_user->runQuery("SELECT * FROM Services"); // permet de rechercher le nom d utilisateur 
								$req_service->execute(); // la meme 
								while ($row_service = $req_service->fetch(PDO::FETCH_ASSOC))
								{
								echo "<option value='".$row_service['nomService']."' 
								label='".$row_service['nomService']."'>".$row_service['nomService']."</option>";
								}
								?>
							</datalist>
							<br>
						<label for="text_chef">Chef </label>
							<input type="hidden"   name="text_chef" value="0"> Cochez si oui
							<input type="checkbox" name="text_chef" value="1">  </br>  

	<!-- Si besoin changer le type "text" en "password" pour cacher le mdp à l'écran -->
						<label for="text_motdepasse">Mot de passe <em>* </em></label>	 
						<input type="text" class="form-control" name="text_motdepasse" placeholder=" xxxxxxx " value="<?php if(isset($error)){echo $text_motdepasse;}?>"/><br>
						<label for="text_motdepasse2">Confirmer le mdp <em>* </em></label>	 
						<input type="text" class="form-control" name="text_motdepasse2" placeholder=" xxxxxxx " value="<?php if(isset($error)){echo "";}?>"/><br>

					</fieldset> <br>
			
					<fieldset>
					<legend> Adresse de l'employé </legend> <!-- Titre du fieldset --> 
			
						<label for="text_numero">Numéro de la rue <em>* </em></label>
						<input type="number" class="form-control" min="1" name="text_numero" pattern="[0-9]{1-6}" title="Caractère numérique, 6 caractères acceptés" placeholder=" Numéro" value="<?php if(isset($error)){echo $text_numero;}?>"/><br>

						<label for="text_rue">Nom de la rue <em>* </em></label>
						<input type="text" class="form-control" name="text_rue" pattern="{1-100}" title="Caractère alphabetique, 100 caractères maximum" placeholder=" Rue" value="<?php if(isset($error)){echo $text_rue;}?>" /><br>

						<label for="text_ville">Ville <em>* </em></label>
						<input type="text" class="form-control" name="text_ville"  pattern="[A-Za-z]{1-150}" title="Caractère alphabetique, 150 caractères maximum" placeholder=" Ville" value="<?php if(isset($error)){echo $text_ville;}?>"/><br>

						<label for="text_codepostal">Code Postal </label>
						<input type="text" class="form-control" name="text_codepostal" pattern="{5}" title="Caractère numérique, 5 caractères maximum" placeholder=" Code Postal" value="<?php if(isset($error)){echo $text_codepostal;}?>" /><br>

						<label for="text_departement">Département </label>
						<input type="text" class="form-control" name="text_departement" pattern="{3}" title="Caractère numérique, 5 caractères maximum" placeholder=" Département" value="<?php if(isset($error)){echo $text_departement;}?>" /><br>

						<label for="text_pays">Pays <em>* </em></label>
						<input type="text" class="form-control" name="text_pays"   pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"   placeholder=" Pays" value="<?php if(isset($error)){echo $text_pays;}?>" /><br>
			
					</fieldset> <br>
				</div> <!-- form-group // Formulaire principal --> 
            
            	<div class="form-group">
            		<button type="submit" class="btn btn-primary" name="btn-signup"> Valider </button>
            	</div>

        	</form>

		</div> <!-- containeFormu --> 

		<button class="abandon">
			<?php quitter1(); ?>
		</button>

		<?php include ('../Config/Footer.php'); //menu de navigation ?>

	</body>
	
</html>
