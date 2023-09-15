<?php

require_once('dbconfig.php');

class Systeme
{	
/*
 Cette classe qui compose notre site web possède 8 fonctions essentiels à la bonne exécution de l’ensemble des services proposés par le site. Elle permet d'ouvrir une connexion au serveur MySQL et possède plusieurs fonctions.
    Une fonction “__construct() ” de construction de la classe Systeme est appelé à chaque création d’une nouvelle instance de l’objet. Cette fonction est nécessaire pour toutes les initialisations dont l'objet a besoin avant d'être utilisé. De cet façon, à chaque utilisation des fonctions associés à cette classe, la fonction “__construct() ” va établir la bonne connection à la base de donnée en appelant la classe “Basededonnee”. 
    La fonction “runQuery()” qui permet de préparer le requetes SQL tout en s’assurant la bonne connection à la base de donnée.
    La fonction “creerUtilisateur()” permet de récupérer en entrée le nom d’utilisateur et son mot de passe. Le mot de passe sera alors crypté grâce à une clé de qui utilise un hachage fort et irréversible. Seul le résultat est stocké dans la base de donnée. 
    La fonction “authentification()” permet de s’authentifier au site. Elle vérifie que le mot de passe correspond à l’utilisateur qui souhaite s’identifier. 
    La fonction “estConnecté()” permet de tester si l’utilisateur est bien connecté.
    La fonction “redirect” permet de rediriger vers une autre page qui peut être donnée en argument.
    La fonction “viderSession()” qui permet de supprimer des variables associé à la session en cours.  Principalement utilisé lorsque l’utilisateur souhaite abandonner une action entreprise.
    La fonction “seDeconnecter()” qui permet à l’utilisateur de se déconnecter en toute sécurité

*/

	public $conn;

	
	public function __construct()# construction -- 1 instance de USER pour acces a bdd **** NE PAS SUPPRIMER ***
	{
		$database = new Basededonnee();
		$db = $database->bddConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql) // Permet de preparer les requetes SQL
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function creerUtilisateur($UtilisateurNom,$text_motdepasse) ## creation compte utilisateur
	{
		try
		{
			$Crypt_motdepasse = password_hash($text_motdepasse, PASSWORD_DEFAULT); // permet de crypter le mot de passe 
			
			$stmt = $this->conn->prepare("INSERT INTO CompteUtilisateurs(idEmploye,passwd) 
		                                               VALUES(:UtilisateurNom, :text_motdepasse)");
												  
			$stmt->bindparam(":UtilisateurNom", $UtilisateurNom);
			$stmt->bindparam(":text_motdepasse", $Crypt_motdepasse);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e) # gestion des erreurs 
		{
			echo $e->getMessage();
		}				
	}
	
	
	public function authentification($uname,$upass) # Permet de s'authentifier
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT idEmploye, passwd FROM CompteUtilisateurs WHERE idEmploye=:uname");
			$stmt->execute(array(':uname'=>$uname));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)   // verifie que le mot de passe concorde avec le nom d utilisateur 
			{ 
				if(password_verify($upass, $userRow['passwd']))
				{
					$_SESSION['idEmploye'] = $userRow['idEmploye'];
					return true; // connection reussi 
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function estConnecte()  // permet de savoir si l utilisateur est connecté 
	{
		if(isset($_SESSION['idEmploye']))
		{
			return true;
		}
	}
	 
	public function redirect($url) // redirige l utilisateur 
	{
		header("Location: $url");
	}
	
	public function seDeconnecter() // permet de se deconnecter 
	{
		session_destroy();
		unset($_SESSION['idEmploye']);
		return true;
	}
		public function viderSession() // permet de vider les variables associées a session 
	{
		unset($_SESSION["patient"]);
		unset($_SESSION["serviceModifier"]);
		unset($_SESSION['utilisateurModifier']);
		unset($_SESSION["dateModifier"]);
		unset($_SESSION["servicePlanning"]);
		unset($_SESSION["rdvModifier"]);
		if ($_SESSION["contact"] = 1 )
		{
			unset($_SESSION["rdvModifier"]);
		}
		if ($_SESSION["conditionsUtilisation"] = 1 )
		{
			unset($_SESSION["ConditionUtilisation"]);
		}
	}
}
?>