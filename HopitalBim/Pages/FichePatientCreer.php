<?php
	// fonction qui permet d afficher les requetes sql et donc permet de jouer avec les données 
	/*  A FAIRE : 
		- champs html du departement et du pays afficher une liste ( plutot que de le taper) 
		- les includes a faire.
	*/
	require_once("../Session.php"); // requis pour se connecter la base de donnée 
	
	require_once("../classe.Systeme.php"); // va permettre d effectuer les requettes sql en orienté objet.
	$auth_user = new Systeme(); // PRIMORDIAL pour les requetes 
	$user_id = $_SESSION['idEmploye']; // permet de conserver la session
	$stmt = $auth_user->runQuery("SELECT * FROM CompteUtilisateurs WHERE idEmploye=:user_name"); // permet de rechercher le nom d utilisateur 
	$stmt->execute(array(":user_name"=>$user_id)); // la meme 
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC); // permet d afficher l identifiant du gars sur la page, ce qui faudrai c est le nom
	
	if(isset($_POST['btn-signup']))
{
 // ici je pense faire un include de $dep a $adresse tout foutre dans un seul et meme document car c est chiant a regarder 
	$text_departement = strip_tags($_POST['text_departement']);	
	$text_pays = strip_tags($_POST['text_pays']);	
	
	$text_ville = strip_tags($_POST['text_ville']);
	$text_codepostal = strip_tags($_POST['text_codepostal']);	
	
	$text_numero = strip_tags($_POST['text_numero']);	
	$text_rue = strip_tags($_POST['text_rue']);	
	
	$text_numSS = strip_tags($_POST['text_numSS']);	
	$text_nom = strip_tags($_POST['text_nom']);	
	$text_prenom = strip_tags($_POST['text_prenom']);	
	$text_dateNaissance = strip_tags($_POST['text_dateNaissance']);	
	$text_telephone = strip_tags($_POST['text_telephone']);	
	$text_mail = strip_tags($_POST['text_mail']);	
	$text_sexe = strip_tags($_POST['text_sexe']);	
	$text_taille = strip_tags($_POST['text_taille']);	
	$text_poids = strip_tags($_POST['text_poids']);	
	$text_commentaires = strip_tags($_POST['text_commentaires']);	
		
	// TEST SI NUMSS deja present
	$stmt = $auth_user->runQuery("SELECT numSS FROM Patients WHERE numSS=:text_numSS ");
	$stmt->execute(array('text_numSS'=>$text_numSS));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	
	 // pas besoin car s auto incremente : $text_idAdresse = strip_tags($_POST['text_idAdresse']);	
	//  pour la gestion des erreurs plus bas aussi ajouter un include et tout foutre dans un autre dossier
	if($text_numSS=="")	{
		$error[] = "Il faut ajouter un numéro de sécurité sociale"; }
	else if ($text_nom==""){
		$error[] = "Il faut un nom !"; }
	else if($text_prenom=="")	{
		$error[] = "Il faut un prénom !"; }
	// TEST SI NUMSS deja present
	else if ($row['numSS']==$text_numSS ) {
		$error[] = "Le patient est deja présent dans la base de donnée"; }
	// Ajouter autant de elseif que l on veut pour gerer les erreurs  // a mettre dans un fichier + include

	else
	{
		try
		{
			/* TEST LE DEPARTEMENT */
			$stmt = $auth_user->runQuery("SELECT departement,pays FROM Departements WHERE departement=:text_departement AND pays=:text_pays");
			$stmt->execute(array('text_departement'=>$text_departement, 'text_pays'=>$text_pays));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			if($row['departement']==$text_departement and $row['pays']==$text_pays)  {
				/* TEST La base de donnée Code postals  */
				$stmt = $auth_user->runQuery("SELECT villes, Departementsdepartement, codepostal, Departementspays FROM CodesPostaux 
												WHERE villes=:text_ville AND Departementsdepartement=:text_departement AND codepostal=:text_codepostal AND Departementspays=:text_pays");
				$stmt->execute(array('text_ville'=>$text_ville,'text_departement'=>$text_departement, 'text_codepostal'=>$text_codepostal,'text_pays'=>$text_pays));
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
					if($row['Departementspays']==$text_pays and $row['Departementsdepartement']==$text_departement and $row['codepostal']==$text_codepostal and $row['villes']==$text_ville )  {
						$stmt = $auth_user->runQuery("SELECT numero, rue, CodesPostauxvilles FROM Adresses 
														WHERE numero=:text_numero AND rue=:text_rue AND CodesPostauxvilles=:text_ville");
						$stmt->execute(array('text_numero'=>$text_numero, 'text_rue'=>$text_rue, 'text_ville'=>$text_ville));
						$row=$stmt->fetch(PDO::FETCH_ASSOC);
							if($row['numero']==$text_numero and $row['rue']==$text_rue and $row['CodesPostauxvilles']==$text_ville )  
							{
								// --------- ici recherche de l id de la putin de ville : 
								$rechercheidadresse = $auth_user->runQuery( "SELECT idAdresse FROM Adresses 
																					WHERE numero=:text_numero 
																					AND rue=:text_rue 
																					AND CodesPostauxvilles=:text_ville" ); 
								$rechercheidadresse->execute(array('text_numero'=>$text_numero,'text_rue'=>$text_rue, 'text_ville'=>$text_ville));
								$row=$rechercheidadresse->fetch(PDO::FETCH_ASSOC);

								$rechercheidadresse =$row["idAdresse"];

								$ajoutpatient = $auth_user->conn->prepare("INSERT INTO Patients (numSS, nom, prenom, dateNaissance, telephone, mail, sexe, taille_cm, poids_kg, commentaires, AdressesidAdresse) 
														VALUES (:text_numSS, :text_nom, :text_prenom, :text_dateNaissance, :text_telephone, :text_mail, :text_sexe, :text_taille, :text_poids, :text_commentaires, :rechercheidadresse)");
		
								$ajoutpatient->bindparam(":text_numSS", $text_numSS );
								$ajoutpatient->bindparam(":text_nom", $text_nom);
								$ajoutpatient->bindparam(":text_prenom", $text_prenom);
								$ajoutpatient->bindparam(":text_dateNaissance", $text_dateNaissance);
								$ajoutpatient->bindparam(":text_telephone", $text_telephone);
								$ajoutpatient->bindparam(":text_mail", $text_mail);
								$ajoutpatient->bindparam(":text_sexe", $text_sexe);
								$ajoutpatient->bindparam(":text_taille", $text_taille);
								$ajoutpatient->bindparam(":text_poids", $text_poids);
								$ajoutpatient->bindparam(":text_commentaires", $text_commentaires);
								$ajoutpatient->bindparam(":rechercheidadresse", $rechercheidadresse);
								$ajoutpatient->execute();									
								//-------------
								$auth_user->redirect('FichePatientCreer.php?Valide');
							}	
							else
							{
								// Ajout de la rue rue etc dans la base
							$stmtrueville = $auth_user->conn->prepare("INSERT INTO Adresses(idAdresse, numero, rue, CodesPostauxvilles ) 
															VALUES (DEFAULT, :text_numero, :text_rue ,:text_ville )");		
							$stmtrueville->bindparam(":text_numero", $text_numero );
							$stmtrueville->bindparam(":text_rue", $text_rue);
							$stmtrueville->bindparam(":text_ville", $text_ville);
							$stmtrueville->execute();
							//-------------
							$rechercheidadresse = $auth_user->runQuery( "SELECT idAdresse FROM Adresses 
																					WHERE numero=:text_numero 
																					AND rue=:text_rue 
																					AND CodesPostauxvilles=:text_ville" ); 
							$rechercheidadresse->execute(array('text_numero'=>$text_numero,'text_rue'=>$text_rue, 'text_ville'=>$text_ville));
							$row=$rechercheidadresse->fetch(PDO::FETCH_ASSOC);
							$rechercheidadresse =$row["idAdresse"];
							$ajoutpatient = $auth_user->conn->prepare("INSERT INTO Patients (numSS, nom, prenom, dateNaissance, telephone, mail, sexe, taille_cm, poids_kg, commentaires, AdressesidAdresse) 
														VALUES (:text_numSS, :text_nom, :text_prenom, :text_dateNaissance, :text_telephone, :text_mail, :text_sexe, :text_taille, :text_poids, :text_commentaires, :rechercheidadresse)");
							$ajoutpatient->bindparam(":text_numSS", $text_numSS );
							$ajoutpatient->bindparam(":text_nom", $text_nom);
							$ajoutpatient->bindparam(":text_prenom", $text_prenom);
							$ajoutpatient->bindparam(":text_dateNaissance", $text_dateNaissance);
							$ajoutpatient->bindparam(":text_telephone", $text_telephone);
							$ajoutpatient->bindparam(":text_mail", $text_mail);
							$ajoutpatient->bindparam(":text_sexe", $text_sexe);
							$ajoutpatient->bindparam(":text_taille", $text_taille);
							$ajoutpatient->bindparam(":text_poids", $text_poids);
							$ajoutpatient->bindparam(":text_commentaires", $text_commentaires);
							$ajoutpatient->bindparam(":rechercheidadresse", $rechercheidadresse);
							$ajoutpatient->execute();	
							$auth_user->redirect('FichePatientCreer.php?Valide');

							}
					}
					else
					{
					$stmtCodepostal = $auth_user->conn->prepare("INSERT INTO CodesPostaux(villes, Departementsdepartement, Departementspays ) 
															VALUES (:text_ville, :text_departement ,:text_codepostal ,:text_pays)");		
					$stmtCodepostal->bindparam(":text_departement", $text_departement );
					$stmtCodepostal->bindparam(":text_pays", $text_pays);
					$stmtCodepostal->bindparam(":text_ville", $text_ville);
					$stmtCodepostal->bindparam(":text_codepostal", $text_codepostal);
					$stmtCodepostal->execute();
					//-------------
					$stmtrueville = $auth_user->conn->prepare("INSERT INTO Adresses(idAdresse, numero, rue, codepostal, CodesPostauxvilles ) 
															VALUES (DEFAULT, :text_numero, :text_rue ,:text_ville )");		
					$stmtrueville->bindparam(":text_numero", $text_numero );
					$stmtrueville->bindparam(":text_rue", $text_rue);
					$stmtrueville->bindparam(":text_ville", $text_ville);
					$stmtrueville->execute();
					//-------------
					$rechercheidadresse = $auth_user->runQuery( "SELECT idAdresse FROM Adresses 
																					WHERE numero=:text_numero 
																					AND rue=:text_rue 
																					AND CodesPostauxvilles=:text_ville" ); 
					$rechercheidadresse->execute(array('text_numero'=>$text_numero,'text_rue'=>$text_rue, 'text_ville'=>$text_ville));
					$row=$rechercheidadresse->fetch(PDO::FETCH_ASSOC);
			    	$rechercheidadresse =$row["idAdresse"];
					$ajoutpatient = $auth_user->conn->prepare("INSERT INTO Patients (numSS, nom, prenom, dateNaissance, telephone, mail, sexe, taille_cm, poids_kg, commentaires, AdressesidAdresse) 
													VALUES (:text_numSS, :text_nom, :text_prenom, :text_dateNaissance, :text_telephone, :text_mail, :text_sexe, :text_taille, :text_poids, :text_commentaires, :rechercheidadresse)");
					$ajoutpatient->bindparam(":text_numSS", $text_numSS );
					$ajoutpatient->bindparam(":text_nom", $text_nom);
					$ajoutpatient->bindparam(":text_prenom", $text_prenom);
					$ajoutpatient->bindparam(":text_dateNaissance", $text_dateNaissance);
					$ajoutpatient->bindparam(":text_telephone", $text_telephone);
					$ajoutpatient->bindparam(":text_mail", $text_mail);
					$ajoutpatient->bindparam(":text_sexe", $text_sexe);
					$ajoutpatient->bindparam(":text_taille", $text_taille);
					$ajoutpatient->bindparam(":text_poids", $text_poids);
					$ajoutpatient->bindparam(":text_commentaires", $text_commentaires);
					$ajoutpatient->bindparam(":rechercheidadresse", $rechercheidadresse);
					$ajoutpatient->execute();	
					$auth_user->redirect('FichePatientCreer.php?Valide');
					}
			}
			else
			{
			
			$stmtdepartements = $auth_user->conn->prepare("INSERT INTO Departements (departement, pays) 
											VALUES (:text_departement, :text_pays) ");		
			$stmtdepartements->bindparam(":text_departement", $text_departement );
			$stmtdepartements->bindparam(":text_pays", $text_pays);
			$stmtdepartements->execute();	
			// --------------------------------- 
			
			$stmtCodepostal = $auth_user->conn->prepare("INSERT INTO CodesPostaux(villes, Departementsdepartement, codepostal, Departementspays ) 
														VALUES (:text_ville, :text_departement ,:text_codepostal ,:text_pays)");		
			$stmtCodepostal->bindparam(":text_departement", $text_departement );
			$stmtCodepostal->bindparam(":text_pays", $text_pays);					
			$stmtCodepostal->bindparam(":text_ville", $text_ville);
			$stmtCodepostal->bindparam(":text_codepostal", $text_codepostal);
			$stmtCodepostal->execute();
			// --------------------------------- 
			$stmtrueville = $auth_user->conn->prepare("INSERT INTO Adresses(numero, rue, codepostal, CodesPostauxvilles ) 
														VALUES (:text_numero, :text_rue ,:text_ville )");		
			$stmtrueville->bindparam(":text_numero", $text_numero );
			$stmtrueville->bindparam(":text_rue", $text_rue);
			$stmtrueville->bindparam(":text_ville", $text_ville);
			$stmtrueville->execute();
			//------------
			$rechercheidadresse = $auth_user->runQuery( "SELECT idAdresse FROM Adresses 
																					WHERE numero=:text_numero 
																					AND rue=:text_rue 
																					AND CodesPostauxvilles=:text_ville" ); 
			$rechercheidadresse->execute(array('text_numero'=>$text_numero,'text_rue'=>$text_rue, 'text_ville'=>$text_ville));
			$row=$rechercheidadresse->fetch(PDO::FETCH_ASSOC);
			$rechercheidadresse =$row["idAdresse"];
			$ajoutpatient = $auth_user->conn->prepare("INSERT INTO Patients (numSS, nom, prenom, dateNaissance, telephone, mail, sexe, taille_cm, poids_kg, commentaires, AdressesidAdresse) 
														VALUES (:text_numSS, :text_nom, :text_prenom, :text_dateNaissance, :text_telephone, :text_mail, :text_sexe, :text_taille, :text_poids, :text_commentaires, :rechercheidadresse)");
			$ajoutpatient->bindparam(":text_numSS", $text_numSS );
			$ajoutpatient->bindparam(":text_nom", $text_nom);
			$ajoutpatient->bindparam(":text_prenom", $text_prenom);
			$ajoutpatient->bindparam(":text_dateNaissance", $text_dateNaissance);
			$ajoutpatient->bindparam(":text_telephone", $text_telephone);
			$ajoutpatient->bindparam(":text_mail", $text_mail);
			$ajoutpatient->bindparam(":text_sexe", $text_sexe);
			$ajoutpatient->bindparam(":text_taille", $text_taille);
			$ajoutpatient->bindparam(":text_poids", $text_poids);
			$ajoutpatient->bindparam(":text_commentaires", $text_commentaires);
			$ajoutpatient->bindparam(":rechercheidadresse", $rechercheidadresse);
			$ajoutpatient->execute();	
			$auth_user-> redirect('FichePatientCreer.php?Valide');
			
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

}



?>
<!DOCTYPE html PUBLIC >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Bonjour</title>
</head>

<body>
<?php include ('../Config/Menupage.php'); ?>

    <p class="h4">Session : <?php print($userRow['idEmploye']); ?></p> 
    <p class="" style="margin-top:5px;">
<div class="signin-form">

<div class="container">
    	
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Enregistrer une fiche patient</h2><hr />
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
                      <i class=""></i>Patient enregistré avec succes<a href='Pageprincipale.php'>Page principale</a>
                 </div>
                 <?php
			}
			?>
			
			
            <div class="form-group" >
            <input type="text" class="form-control" name="text_numSS" pattern="[0-9]{15}" title="Caractère numérique, 15 caractères acceptés"        placeholder="Numero Securité Sociale :" value="<?php if(isset($error)){echo $text_numSS;}?>" /><br>
            <input type="text" class="form-control" name="text_nom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"     placeholder="Nom :" value="<?php if(isset($error)){echo $text_nom;}?>" /><br>
            <input type="text" class="form-control" name="text_prenom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"  placeholder="Prénom :" value="<?php if(isset($error)){echo $text_prenom;}?>" /><br>
            <input type="date" class="form-control" name="text_dateNaissance" placeholder="" value="<?php if(isset($error)){echo $text_dateNaissance;}?>" /><br>
            <input type="text" class="form-control" name="text_telephone" pattern="[0-9]{0-15}" title="Caractère numérique, 15 caractères acceptés"    placeholder="Numero de telephone :" value="<?php if(isset($error)){echo $text_telephone;}?>" /><br>
            <input type="text" class="form-control" name="text_mail" placeholder="Mail :" value="<?php if(isset($error)){echo $text_mail;}?>" /><br>
			
			<label   class="form-control" > Sexe :&nbsp;&nbsp;      
			<input type="radio"  name="text_sexe" value="M" checked="checked"  style="display: inline; !important;"/>Masculin&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio"  name="text_sexe" value="F" style="display: inline;!important;" />Feminin
			</label><br>			
            <input type="text" class="form-control" name="text_taille" pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder="Taille en cm :" value="<?php if(isset($error)){echo $text_taille;}?>" /><br>
            <input type="text" class="form-control" name="text_poids"  pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder="Poids en kg :" value="<?php if(isset($error)){echo $text_poids;}?>" /><br>
            <input type="text" class="form-control" name="text_commentaires" placeholder="Entrer commentaires :" value="<?php if(isset($error)){echo $text_commentaires;}?>" /><br>
            <input type="text" class="form-control" name="text_numero" pattern="[0-9]{1-6}" title="Caractère numérique, 6 caractères acceptés"         placeholder="Entrer numero de la rue :" value="<?php if(isset($error)){echo $text_numero;}?>" /><br>
            <input type="text" class="form-control" name="text_rue"    pattern="[A-Za-z]{1-100}" title="Caractère alphabetique, 100 caractères maximum" placeholder="Entrer le nom de la rue :" value="<?php if(isset($error)){echo $text_rue;}?>" /><br>
			<input type="text" class="form-control" name="text_ville"  pattern="[A-Za-z]{1-150}" title="Caractère alphabetique, 150 caractères maximum" placeholder="Entrer le nom de la ville :" value="<?php if(isset($error)){echo $text_ville;}?>" /><br>
            <input type="text" class="form-control" name="text_codepostal" pattern="[0-9]{5}" title="Caractère numérique, 5 caractères maximum"       placeholder="Entrer le code postal :" value="<?php if(isset($error)){echo $text_codepostal;}?>" /><br>
            <input type="text" class="form-control" name="text_departement" pattern="[0-9]{2}" title="Caractère numérique, 5 caractères maximum"      placeholder="Entrer le departement :" value="<?php if(isset($error)){echo $text_departement;}?>" /><br>
			<input type="text" class="form-control" name="text_pays"   pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum" placeholder="Entrer le pays :" value="<?php if(isset($error)){echo $text_pays;}?>" /><br>

			</div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-signup">
                	<i class=""></i>Valider
                </button>
            </div>
        </form>
       </div>
</div>

</div>

</body>


</html>