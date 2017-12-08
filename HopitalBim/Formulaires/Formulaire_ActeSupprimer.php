<?php
	if(isset($_POST['btn-supprimerActe']))
	{	 
		// ici je pense faire un include de $dep a $adresse tout foutre dans un seul et meme document car c est chiant a regarder 
			 // Gestion des erreurs : 
		if ($_POST['text_idIntervention']==""){$error[] = "Il faut un sélectionner l'acte médical d'un service !"; }
		else 
		{
			$text_idIntervention = preg_replace("/[^0-9]/", "",trim($_POST['text_idIntervention'], ' '));
			$req_existeActe = $auth_user->runQuery("SELECT idIntervention 
							FROM Interventions
							WHERE idIntervention = :id");
			$req_existeActe->execute(array("id"=>$text_idIntervention));
			$existeActe=$req_existeActe-> fetch(PDO::FETCH_ASSOC);
			if ($existeActe == FALSE){$error[] = "Cet acte n'existe pas pour ce service !"; }
			else
			{
				try 
				{
					//recupere tarif
					$req_archivertarif = $auth_user->runQuery("SELECT Tarif_euros
																FROM Tarifications
																WHERE InterventionsidIntervention=:idIntervention");
					$req_archivertarif->execute(array('idIntervention'=>$text_idIntervention));
					$archivertarif=$req_archivertarif-> fetchColumn();
					$req_archivertarif->closeCursor();
					
					// supprime tarif
					$req_supprimerActeTarif = $auth_user->conn->prepare("DELETE FROM Tarifications
															   WHERE InterventionsidIntervention=:idIntervention");
					$req_supprimerActeTarif->execute(array('idIntervention'=>$text_idIntervention));
					$req_supprimerActeTarif->closeCursor();
					
					// deplace acte
					$req_archiverActe = $auth_user->runQuery("INSERT INTO InterventionsArchive 
																SELECT *   
																FROM Interventions
																WHERE idIntervention=:idIntervention");
					$req_archiverActe->execute(array('idIntervention'=>$text_idIntervention));
					$req_archiverActe->closeCursor();
					 
					// insert tarifArchive
					$req_archiverActe = $auth_user->runQuery("INSERT INTO TarificationsArchive (InterventionsidIntervention, tarif_euros ) 
															VALUES (:idIntervention, :tarif)");
					$req_archiverActe->execute(array('idIntervention'=>$text_idIntervention,
													 'tarif'=>$archivertarif));
					$req_archiverActe->closeCursor();

					//supprime l'intervention
					$req_supprimerActe = $auth_user->conn->prepare("DELETE FROM Interventions
																	WHERE idIntervention=:idIntervention;");
											$req_supprimerActe->execute(array('idIntervention'=>$text_idIntervention));
					$req_supprimerActe->closeCursor();
					
					$auth_user->redirect('ActeSupprimer.php?Valide');
				}
				catch(PDOException $e)
				{			
					echo $e->getMessage();
				}				
			}

		}
	}
?>

<div class="containerFormu">
    <form method="post" class="form-signin">
        <h2 class="form-signin-heading">Supprimer un acte médical</h2><hr />
    <?php
        if(isset($error))
        {
            foreach($error as $error)
            {
    ?>
                <div id="error"> &nbsp; <?php echo $error; ?> </div> 
    <?php
            }
        }
        else if(isset($_GET['Valide']))
        {
    ?>
            <div id="valide"> <!-- Alert alert-danger-->
                Acte supprimé avec succés ! <a href='../Pageprincipale.php'>Page principale</a>
            </div>
    <?php
        }
    ?>
        <div class="form-group" >
            <fieldset>
                <legend> Rechercher un acte médical </legend><br> <!-- Titre du fieldset --> 		

            <!-- Affichage formulaire : moteur recherche-->
                <label for="text_numSS">Interventions <em>* </em> </label>
                <input list="text_idIntervention" name="text_idIntervention" size='1000'> 
                <datalist id="text_idIntervention" >
                    <?php 
                        $req_serviceacte = $auth_user->runQuery("SELECT idIntervention, acte, ServicesnomService FROM Interventions"); // permet de rechercher le nom d utilisateur 
                        $req_serviceacte->execute(); // la meme 
                        while ($row_serviceacte = $req_serviceacte->fetch(PDO::FETCH_ASSOC))
                        {
                            echo "<option label='".$row_serviceacte['acte']." ".$row_serviceacte['ServicesnomService']."' 
                            value='"."(".$row_serviceacte['idIntervention'].")"."  ".$row_serviceacte['acte']." -- ".$row_serviceacte['ServicesnomService']."'>".$row_serviceacte['acte']." ".$row_serviceacte['ServicesnomService']."</option>";
                        }
                    ?>
                </datalist> </br >
            </fieldset> <br>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="btn-supprimerActe"> Valider </button>
        </div>
    </form> <!-- form-signin -->
</div> <!-- containerFormu -->

<?php quitter1(); ?>

