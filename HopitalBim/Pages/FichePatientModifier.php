<?php

include ('../Config/Menupage.php');
include ('../Fonctions/Affichage.php');
require_once("../session.php"); // requis pour se connecter la base de donnée 
require_once("../classe.Systeme.php"); // va permettre d effectuer les requettes sql en orienté objet.
$auth_user = new Systeme(); // PRIMORDIAL pour les requetes 
$user_id = $_SESSION['idEmploye']; // permet de conserver la session
$stmt = $auth_user->runQuery("SELECT * FROM CompteUtilisateurs WHERE idEmploye=:user_name"); // permet de rechercher le nom d utilisateur 
$stmt->execute(array(":user_name"=>$user_id)); // la meme 
$userRow=$stmt->fetch(PDO::FETCH_ASSOC); // permet d afficher l identifiant du gars sur la page, ce qui faudrai c est le nom
	
	
if(isset($_POST['btn-selectionpatient']))
{	 
	$text_numSS=$_POST['text_numSS'];
	$echo $text_numSS ;
	 
	// ici je pense faire un include de $dep a $adresse tout foutre dans un seul et meme document car c est chiant a regarder 
		 // Gestion des erreurs : 
	if ($text_utilisateur==""){$error[] = "Il faut un selectionner un utilisateur !"; }
	else if($text_utilisateur=="admin00")	{$error[] = "Impossible de supprimer l'Admin"; }
	else 
	{ 
		
		try 
		{
			$ajoutchef = $auth_user->conn->prepare("DELETE FROM CompteUtilisateurs WHERE 
													idEmploye=:text_utilisateur");
			$ajoutchef->bindparam(":text_utilisateur", $text_utilisateur);
			$ajoutchef->execute();
			$auth_user->redirect('CompteUtilSupprimer.php?Valide');
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


    <p class="h4">Session : <?php print($userRow['idEmploye']); ?></p> 
    <p class="" style="margin-top:5px;">
<div class="signin-form">

<form method="post" class="form-signin">
Rechercher un patient :
			<input list="text_numSS" name="text_numSS" size='35'> 
			<datalist id="text_numSS" >
            <?php 
			$stmt = $auth_user->runQuery("SELECT numSS, nom, prenom FROM Patients"); // permet de rechercher le nom d utilisateur 
			$stmt->execute(); // la meme 
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			echo "<option value='".$row['numSS']."'>".$row['numSS']." ".$row['nom']." ".$row['prenom']."</option>";
			}?></datalist>
</form >

            	<button type="submit" class="btn btn-primary" name="btn-signup">
                	<i class=""></i>Valider
                </button>


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
                      <i class=""></i>Patient enregistré avec succes<a href='../Pageprincipale.php'>Page principale</a>
                 </div>
                 <?php
			}
			?>
			
			
            <div class="form-group" >
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
            <input type="text" class="" name="text_numero" pattern="[0-9]{1-6}" title="Caractère numérique, 6 caractères acceptés"         placeholder="Entrer numero de la rue :" value="<?php if(isset($error)){echo $text_numero;}?>" /><br>
            <input type="text" class="" name="text_rue"    pattern="[A-Za-z]{1-100}" title="Caractère alphabetique, 100 caractères maximum" placeholder="Entrer le nom de la rue :" value="<?php if(isset($error)){echo $text_rue;}?>" /><br>
			<input type="text" class="" name="text_ville"  pattern="[A-Za-z]{1-150}" title="Caractère alphabetique, 150 caractères maximum" placeholder="Entrer le nom de la ville :" value="<?php if(isset($error)){echo $text_ville;}?>" /><br>
            <input type="text" class="" name="text_codepostal" pattern="[0-9]{5}" title="Caractère numérique, 5 caractères maximum"       placeholder="Entrer le code postal :" value="<?php if(isset($error)){echo $text_codepostal;}?>" /><br>
            <input type="text" class="" name="text_departement" pattern="[0-9]{2}" title="Caractère numérique, 5 caractères maximum"      placeholder="Entrer le departement :" value="<?php if(isset($error)){echo $text_departement;}?>" /><br>
			<input type="text" class="" name="text_pays"   pattern="[A-Za-z]{1-25}" title="Caractère alphabetique, 25 caractères maximum" placeholder="Entrer le pays :" value="<?php if(isset($error)){echo $text_pays;}?>" /><br>

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
<?php quitter1() ?>	

</body>


</html>
