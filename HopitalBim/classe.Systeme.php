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
	
	public function register($uname,$upass) ## creation compte utilisateur
	{
		try
		{
			$new_password = password_hash($upass, PASSWORD_DEFAULT);
			
			$stmt = $this->conn->prepare("INSERT INTO CompteUtilisateurs(idEmploye,passwd) 
		                                               VALUES(:uname, :upass)");
												  
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":upass", $new_password);										  
				
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
	}
	
	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['idEmploye']);
		return true;
	}
}
?>