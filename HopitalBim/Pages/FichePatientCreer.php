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
	$text_departement = trim($_POST['text_departement'], ' ' );	
	$text_pays = ucfirst(trim($_POST['text_pays'], ' '))	;
	
	$text_ville = ucfirst(trim($_POST['text_ville'], ' '))	;
	$text_codepostal = trim($_POST['text_codepostal'], ' ');	
	
	$text_numero = trim($_POST['text_numero'], ' ' );	
	$text_rue = ucfirst(trim($_POST['text_rue'], ' '))	;

	$text_numSS = trim($_POST['text_numSS'], ' ' );	
	$text_nom =  ucfirst(trim($_POST['text_nom'], ' '))	;
	$text_prenom = ucfirst(trim($_POST['text_prenom'], ' '))	;
	$text_dateNaissance = strip_tags($_POST['text_dateNaissance']);	
		
	$text_telephone = trim($_POST['text_telephone'], ' ' );
	$text_mail = strip_tags($_POST['text_mail']);	
	$text_sexe = strip_tags($_POST['text_sexe']);	
	$text_taille = preg_replace("/[^0-9]/", "",trim($_POST['text_taille'], ' '));	
	$text_poids = preg_replace("/[^0-9]/", "",trim($_POST['text_poids'], ' '));	
	$text_commentaires = strip_tags($_POST['text_commentaires']);	

	$_SESSION['Patient']=$text_numSS ;	

	// TEST SI NUMSS deja present
	$stmt = $auth_user->runQuery("SELECT numSS FROM Patients WHERE numSS=:text_numSS ");
	$stmt->execute(array('text_numSS'=>$text_numSS));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	
	
	 // pas besoin car s auto incremente : $text_idAdresse = strip_tags($_POST['text_idAdresse']);	
	//  pour la gestion des erreurs plus bas aussi ajouter un include et tout foutre dans un autre dossier
	if($text_numSS==""  or (is_numeric($text_numSS)==FALSE ) or (strlen($text_numSS) < 15 ) or (strlen($text_numSS) > 15 ))	{
		$error[] = "Vérifiez que le numéro de sécurité sociale est correct"; }
	else if((preg_match('/[0-9]+/',$text_nom) == 1)or ($text_nom=="")) {// string only contain the a to z , A to Z,
		$error[] = "Veuillez entrer un nom uniquement composé de lettres !";}
	else if((preg_match('/[0-9]+/',$text_prenom) == 1)or ($text_prenom=="")) {// string only contain the a to z , A to Z,
		$error[] = "Veuillez entrer un prénom uniquement composé de lettres !";}
	else if(($_POST['text_dateNaissance'])=="")	{
		$error[] = "Veuillez respecter le format jj/mm/aaaa !"; }
	else if((preg_match('/[0-9]+/',$text_numero) == 0)or ($text_numero=="") )	{
		$error[] = "Veuillez entrer un numéro de rue !"; }
	else if($text_rue=="" )	{
		$error[] = "Il faut entrer un nom de rue valide !"; }
	else if(strlen($text_codepostal) > 5)	{
		$error[] = "Il faut entrer un code postal valide !"; }
	else if(strlen($text_departement) > 3) 	{
		$error[] = "Veuillez entrer un numéro de département de maximum 3 caractères alphanumériques (entrez 99 si le patient réside à l'étranger) !"; }
	else if ((preg_match('/[0-9]+/',$text_pays) == 1)or ($text_pays=="") or (strlen($text_pays) > 25))	{
		$error[] = "Veuillez entrer un pays (caractères numériques non acceptés)!"; }
	// TEST SI NUMSS deja present
	else if ($row['numSS']==$text_numSS ) {
		$error[] = "Le patient est déjà présent dans la base de donnée</br> Pour le modifier : <a href =# >ici</a>"; }   
	else
	{
		try
		{
		// Test si la ville est presente 

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
					// -- Ajout Patient
					$ajoutpatient = $auth_user->conn->prepare("INSERT INTO Patients (numSS, nom, prenom, dateNaissance, telephone, mail, sexe, taille_cm, poids_kg, commentaires, AdressesidAdresse) 
														VALUES (:text_numSS, :text_nom, :text_prenom, :text_dateNaissance, :text_telephone, :text_mail, :text_sexe, :text_taille, :text_poids, :text_commentaires, :BDDidAdresse)");
		
					$ajoutpatient->execute(array('text_numSS'=>$text_numSS,
											     'text_nom'=>$text_nom,
											     'text_prenom'=>$text_prenom,
											     'text_dateNaissance'=>$text_dateNaissance,
											     'text_telephone'=>$text_telephone,
											     'text_mail'=>$text_mail,
											     'text_sexe'=>$text_sexe,
											     'text_taille'=>$text_taille,
											     'text_poids'=>$text_poids,
												 'text_commentaires'=>$text_commentaires,
											     'BDDidAdresse'=>$BDDidAdresse));								
					//-------------
					$auth_user->redirect('FichePatientCreer.php?Valide');
				}
				else 
				{
					
					// -- Ajout dans adresse
					$stmtAdresses = $auth_user->runQuery("INSERT INTO Adresses (numero, rue, VillesidVilles) 
											VALUES (:text_numero, :text_rue, :BDDidVilles )");	
										
					$stmtAdresses->execute(array('text_numero'=>$text_numero,
											     'text_rue'=>$text_rue,
											     'BDDidVilles'=>$BDDidVilles));
					$stmt = $auth_user->runQuery("SELECT * FROM Adresses 
										WHERE numero=:text_numero AND rue=:text_rue AND VillesidVilles=:BDDidVilles");
					$stmt->execute(array('text_numero'=>$text_numero, 
										 'text_rue'=>$text_rue, 
										 'BDDidVilles'=>$BDDidVilles));
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$BDDidAdresse=$row['idAdresse'];
					// -- Ajout Patient
					$ajoutpatient = $auth_user->runQuery("INSERT INTO Patients (numSS, nom, prenom, dateNaissance, telephone, mail, sexe, taille_cm, poids_kg, commentaires, AdressesidAdresse) 
														VALUES (:text_numSS, :text_nom, :text_prenom, :text_dateNaissance, :text_telephone, :text_mail, :text_sexe, :text_taille, :text_poids, :text_commentaires, :BDDidAdresse)");
		
					$ajoutpatient->execute(array('text_numSS'=>$text_numSS,
											     'text_nom'=>$text_nom,
											     'text_prenom'=>$text_prenom,
											     'text_dateNaissance'=>$text_dateNaissance,
											     'text_telephone'=>$text_telephone,
											     'text_mail'=>$text_mail,
											     'text_sexe'=>$text_sexe,
											     'text_taille'=>$text_taille,
											     'text_poids'=>$text_poids,
												 'text_commentaires'=>$text_commentaires,
											     'BDDidAdresse'=>$BDDidAdresse));					
					//-------------
					$auth_user->redirect('FichePatientCreer.php?Valide');
				}
			
			
			}
			else
			{
				// -- Ajout dans la table ville 
				$stmtville = $auth_user->conn->prepare("INSERT INTO Villes ( codepostal, nomVilles, departement, pays) 
												VALUES ( :text_codepostal, :text_ville, :text_departement, :text_pays)");	
				$stmtville->execute(array('text_codepostal'=>$text_codepostal,
										  'text_ville'=>$text_ville,
										  'text_departement'=>$text_departement,
										  'text_pays'=>$text_pays));
				$stmt = $auth_user->runQuery("SELECT * FROM Villes 
										WHERE codepostal=:text_codepostal AND nomVilles=:text_ville 
										AND departement=:text_departement AND pays=:text_pays");
				$stmt->execute(array('text_codepostal'=>$text_codepostal, 'text_ville'=>$text_ville, 'text_departement'=>$text_departement, 'text_pays'=>$text_pays));
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				$BDDidVilles=$row['idVilles'];
				// -- Ajout dans adresse
				$stmtAdresses = $auth_user->runQuery("INSERT INTO Adresses (numero, rue, VillesidVilles) 
												VALUES (:text_numero, :text_rue, :BDDidVilles )");	
											
				$stmtAdresses->execute(array('text_numero'=>$text_numero,
											   'text_rue'=>$text_rue, 
											   'BDDidVilles'=>$BDDidVilles));
				$stmt = $auth_user->runQuery("SELECT * FROM Adresses 
											WHERE numero=:text_numero AND rue=:text_rue AND VillesidVilles=:BDDidVilles");
				$stmt->execute(array('text_numero'=>$text_numero, 'text_rue'=>$text_rue, 'BDDidVilles'=>$BDDidVilles));
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				$BDDidAdresse=$row['idAdresse'];
				// -- Ajout Patient
				$ajoutpatient = $auth_user->conn->prepare("INSERT INTO Patients (numSS, nom, prenom, dateNaissance, telephone, mail, sexe, taille_cm, poids_kg, commentaires, AdressesidAdresse) 
															VALUES (:text_numSS, :text_nom, :text_prenom, :text_dateNaissance, :text_telephone, :text_mail, :text_sexe, :text_taille, :text_poids, :text_commentaires, :BDDidAdresse)");
				$ajoutpatient->execute(array('text_numSS'=>$text_numSS,
											 'text_nom'=>$text_nom,
											 'text_prenom'=>$text_prenom,
											 'text_dateNaissance'=>$text_dateNaissance,
											 'text_telephone'=>$text_telephone,
											 'text_mail'=>$text_mail,
											 'text_sexe'=>$text_sexe,
											 'text_taille'=>$text_taille,
											 'text_poids'=>$text_poids,
										     'text_commentaires'=>$text_commentaires,
											 'BDDidAdresse'=>$BDDidAdresse));													
				//-------------
				$auth_user->redirect('FichePatientCreer.php?Valide');
			}	
		}
		catch(PDOException $e)
		{			
			echo $e->getMessage();
		}	
	}
}
if(isset($_POST['redirection']))
{ 
$auth_user->redirect('RDVDemande.php');
}


?>
<!DOCTYPE html PUBLIC >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Bonjour</title>
</head>

<body>


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
                      <i class=""></i>Patient enregistré avec succes<a href='../RDVDemande.php'></br>
					  Demande de rendez-vous.</a>
                 </div>
                 <?php
			}
			?>
			
			
            <div class="form-group" >
			<fieldset>
			<legend> Patient </legend> <!-- Titre du fieldset --> 
			<p>
				<input type="text" class="" name="text_numSS" pattern="[0-9]{15}" title="Caractère numérique, 15 caractères acceptés"        placeholder="Numero Securité Sociale :" value="<?php if(isset($error)){echo $text_numSS;}?>" /><br>
				<input type="text" class="" name="text_nom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"     placeholder="Nom :" value="<?php if(isset($error)){echo $text_nom;}?>" /><br>
				<input type="text" class="" name="text_prenom" pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum"  placeholder="Prénom :" value="<?php if(isset($error)){echo $text_prenom;}?>" /><br>
				<input type="date" class="" name="text_dateNaissance" placeholder="" value="<?php if(isset($error)){echo $text_dateNaissance;}?>" /><br>
				<input type="text" class="" name="text_telephone" pattern="[0-9]{0-15}" title="Caractère numérique, 15 caractères acceptés"    placeholder="Numero de telephone :" value="<?php if(isset($error)){echo $text_telephone;}?>" /><br>
				<input type="text" class="" name="text_mail" placeholder="Mail :" value="<?php if(isset($error)){echo $text_mail;}?>" /><br>
				
				<label   class="form-control" > Sexe :&nbsp;&nbsp;      
				<input type="radio"  name="text_sexe" value="M" checked="checked"  style="display: inline; !important;"/>Masculin&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio"  name="text_sexe" value="F" style="display: inline;!important;" />Feminin
				</label><br>			
				<input type="text" class="" name="text_taille" pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder="Taille en cm :" value="<?php if(isset($error)){echo $text_taille;}?>" /><br>
				<input type="text" class="" name="text_poids"  pattern="[0-9]{0-3}" title="Caractère numérique, 15 caractères acceptés"        placeholder="Poids en kg :" value="<?php if(isset($error)){echo $text_poids;}?>" /><br>
				<input type="text" class="" name="text_commentaires" placeholder="Entrer commentaires :" value="<?php if(isset($error)){echo $text_commentaires;}?>" /><br>
			</p>
			</fieldset>
			
			<fieldset>
			<legend> Adresse du patient </legend> <!-- Titre du fieldset --> 
			<p>
				<input type="text" class="" name="text_numero" pattern="[0-9]{1-6}" title="Caractère numérique, 6 caractères acceptés"         placeholder="Entrer numero de la rue :" value="<?php if(isset($error)){echo $text_numero;}?>" /><br>
				<input type="text" class="" name="text_rue"    pattern="[A-Za-z]{1-100}" title="Caractère alphabetique, 100 caractères maximum" placeholder="Entrer le nom de la rue :" value="<?php if(isset($error)){echo $text_rue;}?>" /><br>
				<input type="text" class="" name="text_ville"  pattern="[A-Za-z]{1-150}" title="Caractère alphabetique, 150 caractères maximum" placeholder="Entrer le nom de la ville :" value="<?php if(isset($error)){echo $text_ville;}?>" /><br>
				<input type="text" class="" name="text_codepostal" pattern="[0-9]{5}" title="Caractère numérique, 5 caractères maximum"       placeholder="Entrer le code postal :" value="<?php if(isset($error)){echo $text_codepostal;}?>" /><br>
				<input type="text" class="" name="text_departement"   pattern="{1-3}" title="3 caractères maximum"   placeholder="Entrer le departement :" value="<?php if(isset($error)){echo $text_departement;}?>" /><br>
				<input type="text" class="" name="text_pays"   pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum" placeholder="Entrer le pays :" value="<?php if(isset($error)){echo $text_pays;}?>" /><br>
			</p>
			</fieldset>
			
			
			</div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-signup">
                	<i class=""></i>Valider
                </button>
				<?php 
				if(isset($_GET['Valide']))
				{
				 ?>
			
				 <button type='submit' class='btn btn-primary' name="redirection">
					<i class=''></i>Demander un RDV
                </button>
                
                 <?php
					}
			?>		
				
            </div>
        </form>
       </div>
</div>

</div>
<?php quitter1() ?>	

</body>

 
</html>
