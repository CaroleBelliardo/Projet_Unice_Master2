<?php

require_once('dbconfig.php');

class Systeme
{	

	public $conn; //	private $conn; # acces bdd -- dbconfig.php

	
	public function __construct()# construction -- 1 instance de USER pour acces a bdd **** A Suppr. ****
	{
		$database = new Database();
		$db = $database->dbConnection();
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
	
	public function is_loggedin()
	{
		if(isset($_SESSION['idEmploye']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
		exit;
	}
	
	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['idEmploye']);
		return true;
	}
}
?>