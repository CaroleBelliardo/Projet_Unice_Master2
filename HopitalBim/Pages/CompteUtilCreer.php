<?php
	// fonction qui permet d afficher les requetes sql et donc permet de jouer avec les données 
	/*  A FAIRE : 
		- champs html du departement et du pays afficher une liste ( plutot que de le taper) 
		- les includes a faire.
	*/

include ('../Config/Menupage.php');
	
if(isset($_POST['btn-signup']))
{	
	 // ici je pense faire un include de $dep a $adresse tout foutre dans un seul et meme document car c est chiant a regarder 
	$text_pays = strip_tags($_POST['text_pays']);	
	$text_departement = strip_tags($_POST['text_departement']);	
	$text_codepostal = strip_tags($_POST['text_codepostal']);			
	$text_ville = strip_tags($_POST['text_ville']);
	$text_rue = strip_tags($_POST['text_rue']);	
	$text_numero = strip_tags($_POST['text_numero']);	

	$text_nom = strip_tags($_POST['text_nom']);	
	$text_prenom = strip_tags($_POST['text_prenom']);	
	$text_telephone = strip_tags($_POST['text_telephone']);	
	$text_mail = strip_tags($_POST['text_mail']);	
	
	$text_chef = strip_tags($_POST['text_chef']);
	$text_nomService = strip_tags($_POST['text_nomService']);	
	$text_motdepasse = strip_tags($_POST['text_motdepasse']);

		 // Gestion des erreurs : 
	if ($text_nom==""){$error[] = "Il faut un nom !"; }
	else if($text_prenom=="")	{$error[] = "Il faut un prénom !"; }
	else if ($text_motdepasse=="")	{$error[] = "Il faut un mot de passe !"; }
	else 
	{ 
		// Creation du nom d'utilisateur et verification : 
		$UtilisateurNom = $text_nom[0].$text_prenom[0].$text_chef.date('is');
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
	</head>



	<body>
		<form method="post" class="form-signin">
			<h2 class="form-signin-heading">Ajouter un utilisateur</h2><hr />
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
				<i class=""></i>Utilisateur enregistré avec succes<a href='../Pageprincipale.php'>Page principale</a>
				</div>
	<?php
			}
	?>
			
			<div class="form-group" >
				<fieldset>
					<legend> Employé </legend> <!-- Titre du fieldset --> 
					<p>
						<input type="text" class="form-control" name="text_nom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"     placeholder="Nom :" value="<?php if(isset($error)){echo $text_nom;}?>" /><br>
						<input type="text" class="form-control" name="text_prenom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"  placeholder="Prénom :" value="<?php if(isset($error)){echo $text_prenom;}?>" /><br>
						<input type="text" class="form-control" name="text_telephone" pattern="[0-9]{1-15}" title="Caractère numérique, 15 caractères acceptés"    placeholder="Numero de telephone :" value="<?php if(isset($error)){echo $text_telephone;}?>" /><br>
						<input type="text" class="form-control" name="text_mail" pattern="{1-60}" title="Caractère numérique, 15 caractères acceptés" placeholder="Mail :" value="<?php if(isset($error)){echo $text_mail;}?>" /><br>			
						</br>
						Service : <?php liste_Services($auth_user) ?>
						Chef : 	<input type="hidden"   name="text_chef" value="0">
								<input type="checkbox" name="text_chef" value="1"></br>   
						<input type="text" class="form-control" name="text_motdepasse" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"  placeholder="Mot de passe utilisateur :" value="<?php if(isset($error)){echo $text_motdepasse;}?>" /><br>
					</p>
				</fieldset>
			
				<fieldset>
					<legend> Adresse employé </legend> <!-- Titre du fieldset --> 
					<p>
						<input type="text" class="form-control" name="text_numero" pattern="[0-9]{1-6}" title="Caractère numérique, 6 caractères acceptés"          placeholder="Entrer numero de la rue :" value="<?php if(isset($error)){echo $text_numero;}?>" /><br>
						<input type="text" class="form-control" name="text_rue"    pattern="[A-Za-z]{1-100}" title="Caractère alphabetique, 100 caractères maximum" placeholder="Entrer le nom de la rue :" value="<?php if(isset($error)){echo $text_rue;}?>" /><br>
						<input type="text" class="form-control" name="text_ville"  pattern="[A-Za-z]{1-150}" title="Caractère alphabetique, 150 caractères maximum" placeholder="Entrer le nom de la ville :" value="<?php if(isset($error)){echo $text_ville;}?>" /><br>
						<input type="text" class="form-control" name="text_codepostal" pattern="[0-9]{5}" title="Caractère numérique, 5 caractères maximum"         placeholder="Entrer le code postal :" value="<?php if(isset($error)){echo $text_codepostal;}?>" /><br>
						<input type="text" class="form-control" name="text_departement" pattern="[0-9]{2}" title="Caractère numérique, 5 caractères maximum"        placeholder="Entrer le departement :" value="<?php if(isset($error)){echo $text_departement;}?>" /><br>
						<input type="text" class="form-control" name="text_pays"   pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"   placeholder="Entrer le pays :" value="<?php if(isset($error)){echo $text_pays;}?>" /><br>
					</p>
				</fieldset>
			</div>
			<div class="clearfix"></div><hr />
			<div class="form-group">
				<button type="submit" class="btn btn-primary" name="btn-signup">
					<i class=""></i>Valider
				</button>
			</div>
		</form>
		</div>
		<?php quitter1() ?>	
		
		<div id="footer"> <!-- Faire les liens vers les documents  -->
			<a href="<?php echo $LienSite ?>Pages/readme.php"> Conditions d'utilisation </a> |
			<a href="<?php echo $LienSite ?>Pages/contact.php"> Contact </a> | © 2017
		</div>
	</body>
</html>
