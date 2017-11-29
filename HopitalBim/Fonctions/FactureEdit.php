<style type="text/css">
<!--
table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
-->
</style>

<?php
// ************************************************** REQUETES ******************

include ('../Config/Menupage.php');
include ('../Fonctions/Affichage.php');
include ('../Fonctions/ReqTraitement.php');
require_once("../session.php"); // requis pour se connecter la base de donnée 
require_once("../classe.Systeme.php"); // va permettre d effectuer les requettes sql en orienté objet.
require_once('../html2pdf/html2fpdf.class.php');




//unset($_SESSION["Patient"]); // TEST 
$patient='jjj'; // a recup !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! page precedente
$test_chef=FALSE; // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Marin
$Utilisateur=array('okok','kokoo,','kokopk');


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
$req_adressePatient->execute(array("patient"=>$patient));
$a_patient=[];
//$Req_utilisateur->closeCursor();

// info coordonnees service
$req_service= $auth_user->runQuery("SELECT DISTINCT *
                                            FROM Services JOIN LocalisationServices 
                                            WHERE LocalisationServices.idLocalisation = Services.LocalisationServicesidLocalisation
                                            AND Services.nomService = :service
                                            ");
$req_service->execute(array("service"=>$Utilisateur['2']));
$a_service=[];
//$Req_service->closeCursor();

//info coordonnees Hopital
$req_hopital= $auth_user->runQuery("SELECT  Adresses.idAdresse
                                        FROM Adresses JOIN Villes
                                        WHERE Adresses.VillesidVilles = Villes.idVilles
                                        AND Adresses.idAdresse = '0'
                                        "); 
$req_hopital->execute();
$a_hopital=[];

//$req_hopital->closeCursor();



//info intervention
$req_intervention= $auth_user->runQuery("SELECT  *
                                        FROM Adresses JOIN Villes
                                        WHERE Adresses.VillesidVilles = Villes.idVilles
                                        AND Adresses.idAdresse = '0'
                                        "); 
$req_intervention->execute();
$a_intervention=[];
//$req_intervention->closeCursor();

// ************************************************** REQUETES ******************

?>

<page backcolor="#FEFEFE" backimg="./res/bas_page.png" backimgx="center" backimgy="bottom" backimgw="100%" backtop="0" backbottom="30mm" footer="date;time;page" style="font-size: 12pt">
    <bookmark title="Lettre" level="0" ></bookmark>
    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px">
        <tr>
            <td style="width: 75%;">
            </td>
            <td style="width: 25%; color: #444444;">
                <img style="width: 100%;" src="./res/logo.gif" alt="Logo"><br>
                LOGO
            </td>
        </tr>
    </table>
    <br>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
        <tr>
            <td style="width:50%;"></td>
            <td style="width:14%; ">Client :</td>
            <td style="width:36%"> <?php echo $a_patient['prenom'].$a_patient['nom'] ?> </td>
        </tr>
        <tr>
            <td style="width:50%;"></td>
            <td style="width:14%; ">Adresse :</td>
            <td style="width:36%">
                Résidence perdue<br>
                <?php echo $a_patient['numero'].$a_patient['rue'] ?><br>
                <?php echo $a_patient['codePostal'].$a_patient['ville'] ?><br>
                <?php echo $a_patient['pays'] ?><br>

            </td>
        </tr>
        <tr>
            <td style="width:50%;"></td>
            <td style="width:14%; ">Email :</td>
            <td style="width:36%">nomail@domain.com</td>
        </tr>
        <tr>
            <td style="width:50%;"></td>
            <td style="width:14%; ">Tel :</td>
            <td style="width:36%">33 (0) 1 00 00 00 00</td>
        </tr>
    </table>
    <br>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left;font-size: 10pt">
        <tr>
            <td style="width:50%;"></td>
            <td style="width:50%; ">Spipu Ville, le <?php echo date('d/m/Y'); ?></td>
        </tr>
    </table>
    <br>
    <i>
        <b><u>Objet </u>: &laquo; Bon de Retour &raquo;</b><br>
        Compte client : 00C4520100A<br>
        Référence du Dossier : 71326<br>
    </i>
    <br>
    <br>
    Madame, Monsieur, Cher Client,<br>
    <br>
    <br>
    Nous souhaitons vous informer que le dossier <b>71326</b> concernant un &laquo; Bon de Retour &raquo; pour les articles suivants a été accepté.<br>
    <br>
    <table cellspacing="0" style="width: 100%; border: solid 1px black; background: #E7E7E7; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 12%">Produit</th>
            <th style="width: 52%">Désignation</th>
            <th style="width: 13%">Prix Unitaire</th>
            <th style="width: 10%">Quantité</th>
            <th style="width: 13%">Prix Net</th>
        </tr>
    </table>
<?php
    $nb = rand(5, 11);
    $produits = array();
    $total = 0;
    for ($k=0; $k<$nb; $k++) {
        $num = rand(100000, 999999);
        $nom = "le produit n°".rand(1, 100);
        $qua = rand(1, 20);
        $prix = rand(100, 9999)/100.;
        $total+= $prix*$qua;
        $produits[] = array($num, $nom, $qua, $prix, rand(0, $qua));
?>
    <table cellspacing="0" style="width: 100%; border: solid 1px black; background: #F7F7F7; text-align: center; font-size: 10pt;">
        <tr>
            <td style="width: 12%; text-align: left"><?php echo $num; ?></td>
            <td style="width: 52%; text-align: left"><?php echo $nom; ?></td>
            <td style="width: 13%; text-align: right"><?php echo number_format($prix, 2, ',', ' '); ?> &euro;</td>
            <td style="width: 10%"><?php echo $qua; ?></td>
            <td style="width: 13%; text-align: right;"><?php echo number_format($prix*$qua, 2, ',', ' '); ?> &euro;</td>
        </tr>
    </table>
<?php
    }
?>
    <table cellspacing="0" style="width: 100%; border: solid 1px black; background: #E7E7E7; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 87%; text-align: right;">Total : </th>
            <th style="width: 13%; text-align: right;"><?php echo number_format($total, 2, ',', ' '); ?> &euro;</th>
        </tr>
    </table>
    <br>
    Cette reprise concerne la quantité et les matériels dont la référence figure sur le <a href="#document_reprise">document de reprise joint</a>.<br>
    Nous vous demandons de nous retourner ces produits en parfait état et dans leur emballage d'origine.<br>
    <br>
    Nous vous demandons également de coller impérativement l'autorisation de reprise jointe, sur le colis à reprendre afin de faciliter le traitement à l'entrepôt.<br>
    <br>
    Notre Service Clients ne manquera pas de revenir vers vous dès que l'avoir de ces matériels sera établi.<br>
    <nobreak>
        <br>
        Dans cette attente, nous vous prions de recevoir, Madame, Monsieur, Cher Client, nos meilleures salutations.<br>
        <br>
        <table cellspacing="0" style="width: 100%; text-align: left;">
            <tr>
                <td style="width:50%;"></td>
                <td style="width:50%; ">
                    Mle Jesuis CELIBATAIRE<br>
                    Service Relation Client<br>
                    Tel : 33 (0) 1 00 00 00 00<br>
                    Email : on_va@chez.moi<br>
                </td>
            </tr>
        </table>
    </nobreak>
</page>