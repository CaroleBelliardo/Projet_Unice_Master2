<?php

require_once('dbconfig.php');

class Systeme
{	

	public $conn; //	private $conn; # acces bdd -- dbconfig.php

	
	public function __construct()# construction -- 1 instance de USER pour acces a bdd **** NE PAS SUPPRIMER ***
	{
		$database = new Basededonnee();
		$db = $database->bddConnection();
		$this->conn = $db;
    }
			public function runQuery($sql) ## verifie connection avant requete 
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function creerUtilisateur($UtilisateurNom,$text_motdepasse) ## creation compte utilisateur
	{
		try
		{
			$Crypt_motdepasse = password_hash($text_motdepasse, PASSWORD_DEFAULT);
			
			$stmt = $this->conn->prepare("INSERT INTO CompteUtilisateurs(idEmploye,passwd) 
		                                               VALUES(:UtilisateurNom, :text_motdepasse)");
												  
			$stmt->bindparam(":UtilisateurNom", $UtilisateurNom);
			$stmt->bindparam(":text_motdepasse", $Crypt_motdepasse);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e) # erreur
		{
			echo $e->getMessage();
		}				
	}
	
	
	public function authentification($uname,$upass) # test si deja connecté
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT idEmploye, passwd FROM CompteUtilisateurs WHERE idEmploye=:uname");
			$stmt->execute(array(':uname'=>$uname));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(password_verify($upass, $userRow['passwd']))
				{
					$_SESSION['idEmploye'] = $userRow['idEmploye'];
					return true;
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
	
	public function estConnecte()
	{
		if(isset($_SESSION['idEmploye']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function seDeconnecter()
	{
		session_destroy();
		unset($_SESSION['idEmploye']);
		return true;
	}
		public function viderSession()
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