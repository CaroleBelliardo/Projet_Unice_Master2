<?php

// -- requetes

    // info coordonnees patient
    $req_adressePatient= $auth_user->runQuery("SELECT DISTINCT nom, prenom, numSS, telephone, mail,
                                                numero, rue, nomVilles, departement , codepostal, pays
                                                FROM Patients JOIN Adresses JOIN Villes
                                                WHERE Patients.AdressesidAdresse = Adresses.idAdresse
                                                AND Adresses.VillesidVilles = Villes.idVilles
                                                AND Patients.numSS = :patient
                                                "); 
    $req_adressePatient->execute(array("patient"=>$_SESSION['patient'])); // remplacer par $_SESSION['patient']
    $a_patient=reqToArrayPlusAttASSO($req_adressePatient);
    $req_adressePatient->closeCursor();
    
    // info coordonnees service
    $req_service= $auth_user->runQuery("SELECT DISTINCT *
                                                FROM Services JOIN LocalisationServices 
                                                WHERE LocalisationServices.idLocalisation = Services.LocalisationServicesidLocalisation
                                                AND Services.nomService = :service
                                                ");
    $req_service->execute(array("service"=>$_SESSION['service']));
    $a_service=reqToArrayPlusAttASSO($req_service);
    //Dumper($a_service);
    $req_service->closeCursor();
    
    //info coordonnees Hopital
    $req_hopital= $auth_user->runQuery("SELECT *
                                       FROM Adresses JOIN Villes
                                       WHERE Adresses.VillesidVilles = Villes.idVilles
                                       AND Adresses.idAdresse = '1' 
                                        "); 
    $req_hopital->execute();
    $a_hopital=reqToArrayPlusAttASSO($req_hopital);
    //Dumper($a_hopital);
    $req_hopital->closeCursor();
    
    
    
    //info intervention
    $req_intervention= $auth_user->runQuery("SELECT  *
                                        FROM CreneauxInterventions JOIN Interventions JOIN Pathologies
                                        WHERE CreneauxInterventions.InterventionsidIntervention= Interventions.idIntervention
                                        AND CreneauxInterventions.PathologiesidPatho= Pathologies.idPatho
                                        AND Interventions.ServicesnomService = :service
                                        AND CreneauxInterventions.PatientsnumSS = :patient
                                        AND CreneauxInterventions.statut = 'r'
                                        "); 
    $req_intervention->execute(array('service'=> $_SESSION['service'],
                                     'patient'=>  $_SESSION['patient']));
    $a_infoInterv=reqToArrayPlusligne($req_intervention);
    $req_intervention->closeCursor();
    
    
    
    // numero de facture
    if (array_key_exists( "id_rdv", $a_infoInterv))
    { 
        $req_facturation= $auth_user->runQuery("SELECT MAX(idFacture) +1
                                                FROM Facturation   "); 
        $req_facturation->execute(); // remplacer par $_SESSION['patient']
        $idfacture = $req_facturation->fetchColumn()  ;
        $req_facturation->closeCursor();
        
        
        //insert num facture
        
        $req_insertFacturation= $auth_user->runQuery("INSERT INTO Facturation (idFacture,CreneauxInterventionsidRdv) 
                                                        VALUES (:idfacture, :idRdv)");    
        foreach ($a_infoInterv["id_rdv"] as $cle=>$id) 
        {
            $req_insertFacturation->execute(array("idfacture"=> $idfacture,
                                                "idRdv"=> $id
                                                )); // remplacer par $_SESSION['patient']
            $req_insertFacturation->closeCursor();
        }
    
    // ************************************************** REQUETES ******************
 
?>
    <!--logo-->
    <table cellspacing="0" style="width: 80%; text-align: center; font-size: 14px">
        <tr>
            <td style="width: 75%;"></td>
            <td style="width: 25%; color: #444444;">
                <img style="width: 70%;" src="../Images/logoFacture2.png" alt="Logo"><br>
            </td>
        </tr>
    </table>
    
    <br>
    <br>
    <!--info patient-->
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
        <tr>
            <td style="width:50%;"></td>
            <td style="width:14%; ">Client : </td>
            <td style="width:36%"> <?php echo $a_patient['prenom']." ".$a_patient['nom'] ?> </td>
        </tr>
        <tr>
            <td style="width:50%;"></td>
            <td style="width:14%; ">Adresse :</td>
            <td style="width:36%">
                <?php echo $a_patient['numero']." ".$a_patient['rue'] ?><br>
                <?php echo $a_patient['codepostal']." ".$a_patient['nomVilles'] ?><br>
                <?php echo $a_patient['pays'] ?><br>

            </td>
        </tr>
        <tr>
            <td style="width:50%;"></td>
            <td style="width:14%; ">Email :  </td>
            <td style="width:36%"> <?php echo $a_patient['mail'] ?> </td>
        </tr>
        <tr>
            <td style="width:50%;"></td>
            <td style="width:14%; ">Tel :</td>
            <td style="width:36%"> <?php echo $a_patient['telephone'] ?> </td>
        </tr>
    </table>
    
    <br>
    <br>
    <!--info hopital-->
    <table cellspacing="0" style="width: 100%; text-align: left;font-size: 10pt">
        <tr>
            <td style="width:50%;"></td>
            <td style="width:50%; "><?php echo $a_hopital['nomVilles'] ?>, le <?php echo date('d/m/Y'); ?></td>
        </tr>
    </table>
    
    <br>
    
    <!--entete-->
    <i>
        <b><u>Objet </u>: &laquo; Facture  <?php //echo $a_utilisateur['ServicesnomService'] ?> &raquo;</b><br><br>
        N° de Sécurité Sociale : <?php echo $a_patient['numSS'] ?> <br>
        N° de la facture : <?php echo $idfacture ?> <br>
    </i>
    
    <br><br>
    Madame, Monsieur,<br><br><br>
    Les interventions suivantes ont été acquitées à ce jour.<br>
    <br>
    
    <table cellspacing="0" style="width: 100%; border: solid 1px black; background: #E7E7E7; text-align: center; font-size: 10pt;">
       
       <!--en tete -->
       <?php 
       $a_entete= ["n° RDV"=>$a_infoInterv["id_rdv"],
                   "Date"=>$a_infoInterv["date_rdv"],
                   "Heure"=>$a_infoInterv["heure_rdv"],
                   "Heure"=>$a_infoInterv["acte"],
                   "Pathologie"=>$a_infoInterv["nomPathologie"],
                   "Information Pathologie"=>$a_infoInterv["indication"],
                   "Niveau d'urgence" =>$a_infoInterv["niveauUrgence"]]
       ?>        <tr>
             <?php Dumper($a_entete);
             foreach ($a_entete as $k=>$v)
             {
            ?>
            <th>
          <?php echo "k".$k ;echo "v".$v['1'] ?>
            </th>
             <?php
             
             }
             ?>
        </tr>
        <tr>
           <?php
           //foreach (  //$a_infoInterv["id_rdv"] as $rdv=>$value)
           //  {
            ?>
            <th>
          <?php //echo $value ;?>
            </th>
             <?php
             
             //}
             ?> 
           
           
            <th>
          
            </th>
            <th>
          
            </th>
        </tr>
        <!--corps du tableau-->
        <!--<tr>-->
        <!--    <td>-->
        <!--  -->
        <!--    </td>-->
        <!--    <td>-->
        <!--  -->
        <!--    </t>-->
        <!--</tr>-->
        <!---->
   
   
    </table>
<?php
	
          ?>
  

?>
    <table cellspacing="0" style="width: 100%; border: solid 1px black; background: #F7F7F7; text-align: center; font-size: 10pt;">
        <tr>
            <td style="width: 50%; text-align: left"><?php //echo $id ; ?></td>
            <td style="width: 50%; text-align: left"><?php //echo $id; ?></td>
    <!--        <td style="width: 13%; text-align: right"><?php echo number_format(intval($id), 2, ',', ' '); ?> &euro;</td>
            <td style="width: 10%"><?php echo $qua; ?></td>
            <td style="width: 13%; text-align: right;"><?php echo number_format(intval($id)*$qua, 2, ',', ' '); ?> &euro;</td>
        --></tr>
    </table>
<?php
//	$total=$total+intval($id);
    
?>
    <table cellspacing="0" style="width: 100%; border: solid 1px black; background: #E7E7E7; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 87%; text-align: right;">Total : </th>
            <th style="width: 13%; text-align: right;"><?php echo number_format($total, 2, ',', ' '); ?> &euro;</th>
        </tr>
    </table>
        <br>
        <table cellspacing="0" style="width: 100%; text-align: left;">
            <tr>
                <td style="width:65%;"></td>
                <td style="width:40%; ">
                    <?php echo $a_utilisateur['prenom']." ".$a_utilisateur['nom']." <br> Responsable du service ".$a_utilisateur['ServicesnomService'] ?> <br>
					<?php  $a_service ?> 
                    <?php echo 'tel. : '.$a_service["telephone"]."<br>".
'mail : '.$a_service["mail"]."<br>".
'ouvert de '.$a_service["horaire_ouverture"].
'à '.$a_service["horaire_fermeture"]."<br>".
'batiement '.$a_service["batiment"].
', aile '.$a_service["aile"].
', au '.$a_service["etage"].'étage'
?>
					
					
					
					
					
					
					
					
					
                    <br>
                </td>
            </tr>
        </table>


<!--<style type="text/css">

table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }

</style>-->
<?php } else echo  "Pas de facture disponible pour ce patient actuellement." ;?> 
