<?php

include ('../Config/Menupage.php');
include ('../Fonctions/Affichage.php');
include ('../Fonctions/ReqTraitement.php');
require_once("../session.php"); // requis pour se connecter la base de donnée 
require_once("../classe.Systeme.php"); // va permettre d effectuer les requettes sql en orienté objet.
require_once('../html2pdf/html2fpdf.class.php');

//unset($_SESSION["Patient"]); // TEST 
$patient='jjj'; // a recup !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! page precedente
$test_chef=FALSE; // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Marin


// variables 
$auth_user = new Systeme(); // Connection bdd 
$user_id = $_SESSION['idEmploye']; // IDENTIFIANT compte utilisateur !!!!!

// info coordonnees patient
$req_adressePatient= $auth_user->runQuery("SELECT DISTINCT nom,prenom, telephone, mail, numero, rue, nomVilles, departement , codepostal, pays
                                            FROM Patients JOIN Adresses JOIN Villes
                                            WHERE Patients.AdressesidAdresse = Adresses.idAdresse
                                            AND Adresses.VillesidVilles = Villes.idVilles
                                            AND Patients.numSS = :patient
                                            "); 
$Req_utilisateur->execute(array("patient"=>$patient));
$Req_utilisateur->closeCursor();

// info coordonnees service
$req_service= $auth_user->runQuery("SELECT DISTINCT *
                                            FROM Services JOIN LocalisationServices 
                                            WHERE LocalisationServices.idLocalisation = Services.LocalisationServicesidLocalisation
                                            AND Services.nomService = :service
                                            "); 
$Req_service->execute(array("service"=>$Utilisateur['2']));
$Req_service->closeCursor();

//info coordonnees Hopital
$req_service= $auth_user->runQuery("SELECT  Adresses.idAdresse
                                        FROM Adresses JOIN Villes
                                        WHERE Adresses.VillesidVilles = Villes.idVilles
                                        AND Adresses.idAdresse = '0'
                                            "); 
$Req_service->execute();
$Req_service->closeCursor();



$a_utilisateur= reqToArrayPlusAtt($Req_utilisateur);  // Nom prénom et service utilisateur 
$AdresseService




$Req_ = $auth_user->runQuery("SELECT DISTINCT nom,prenom,ServicesnomService
                                    FROM CompteUtilisateurs JOIN Employes
                                    WHERE CompteUtilisateurs.idEmploye=Employes.CompteUtilisateursidEmploye
                                    AND CompteUtilisateurs.idEmploye= :user_name
                                    "); // NOM utilisateur = >> à mettre dans menuPage !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! TOUTES PAGES 
$Req_utilisateur->execute(array("user_name"=>$user_id
                            ));
$a_utilisateur= reqToArrayPlusAtt($Req_utilisateur);  // Nom prénom et service utilisateur 







// *** pdf

//$pdf = new HTML2FPDF('L','A3','en');
//
//ob_start();
//
//?>
//<table> <tr>
//    <td>
//        Mon premier fichier pdf
//    </td>
//</tr>
//<?php
//$cont=ob_get_clean();
//try{
//    $pdf = new HTML2FPDF('A4');
//    $pdf ->WriteHTML($cont);
//}
//catch(HTML2FPDF_Exeption $e)
//{
//    echo 'Erreur :'.$e ->getMessage();
//}
?>



?>