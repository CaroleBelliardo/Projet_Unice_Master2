<?php

require_once('dbconfig.php');

class FICHEPATIENT
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function register($text_idAdresse,$text_numero ,$text_rue,$text_CodePostauxcodepostal,$text_numSS,$text_nom ,$text_prenom,$text_dateNaissance,$text_telephone,$text_mail ,$text_sexe,$text_taille,$text_poids,$text_commentaires,$text_AdressesidAdresse)
	{
		try
		{			
			$stmtpatients = $this->conn->prepare("INSERT INTO Patients (numSS, nom, prenom, dateNaissance, telephone, mail, sexe, taille, poids, commentaires, AdressesidAdresse) 
		                                               VALUES(:text_numSS, :text_nom, :text_prenom, :text_dateNaissance, :text_telephone, :text_mail, :text_sexe, :text_taille, :text_poids, :text_commentaires, :text_AdressesidAdresse)");
			$stmtadresse = $this->conn->prepare("INSERT INTO Adresses (idAdresse, numero, rue, CodePostauxcodepostal) 
		                                               VALUES(:text_idAdresse, :text_numero, :text_rue, :text_CodePostauxcodepostal) ");
			



				
			$stmtadresse->bindparam(":text_idAdresse", $text_idAdresse);
			$stmtadresse->bindparam(":text_numero", $text_numero);
			$stmtadresse->bindparam(":text_rue", $text_rue);
			$stmtadresse->bindparam(":text_CodePostauxcodepostal", $text_CodePostauxcodepostal);
			
			$stmtpatients->bindparam(":text_numSS", $text_numSS);
			$stmtpatients->bindparam(":text_nom", $text_nom);
			$stmtpatients->bindparam(":text_prenom", $text_prenom);
			$stmtpatients->bindparam(":text_dateNaissance", $text_dateNaissance , PDO::PARAM_STR);
			$stmtpatients->bindparam(":text_telephone", $text_telephone);
			$stmtpatients->bindparam(":text_mail", $text_mail);
			$stmtpatients->bindparam(":text_sexe", $text_sexe);
			$stmtpatients->bindparam(":text_taille", $text_taille);
			$stmtpatients->bindparam(":text_poids", $text_poids);
			$stmtpatients->bindparam(":text_commentaires", $text_commentaires);
			$stmtpatients->bindparam(":text_AdressesidAdresse", $text_AdressesidAdresse);


			$stmtadresse->execute();	
			$stmtpatients->execute();	

			return array($stmtadresse, $stmtpatients);	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	
	public function authentification($uname,$upass)
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
	
}
?>