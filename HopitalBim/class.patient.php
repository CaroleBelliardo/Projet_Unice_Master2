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
	

	public function ajouterdepartements($text_departement,$text_pays )
	{
		try
		{			
			$stmtdepartements = $this->conn->prepare("INSERT INTO Departements (departement, pays) 
														VALUES (:text_departement, :text_pays) ");
		
			$stmtdepartements->bindparam(":text_departement", $text_departement );
			$stmtdepartements->bindparam(":text_pays", $text_pays);
			
			$stmtdepartements->execute();

			return $stmtdepartements;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function ajouterville($text_ville,$text_departement,$text_codepostal)
	{
		try
		{			
			$stmtville = $this->conn->prepare("INSERT INTO Villes (ville, Departementsdepartement, codepostal) 
													VALUES (:text_ville, :text_departement, :text_codepostal)" );

			$stmtville->bindparam(":text_ville", $text_ville);
			$stmtville->bindparam(":text_departement", $text_departement);
			$stmtville->bindparam(":text_codepostal", $text_codepostal);
	
			$stmtville->execute();
	
			return $stmtville;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function ajouteradresse($text_idAdresse,$text_numero ,$text_rue,$text_codepostal)
	{
		try
		{			
			$stmtadresse = $this->conn->prepare("INSERT INTO Adresses (idAdresse, numero, rue, CodePostauxcodepostal) 
		                                               VALUES(:text_idAdresse, :text_numero, :text_rue, :text_codepostal) ");
			
			$stmtadresse->bindparam(":text_idAdresse", $text_idAdresse);
			$stmtadresse->bindparam(":text_numero", $text_numero);
			$stmtadresse->bindparam(":text_rue", $text_rue);
			$stmtadresse->bindparam(":text_codepostal", $text_codepostal);
			
			$stmtadresse->execute();		

			return $stmtadresse;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function ajouterpatients($text_numSS,$text_nom ,$text_prenom,$text_dateNaissance,$text_telephone,$text_mail ,$text_sexe,$text_taille,$text_poids,$text_commentaires,$text_idAdresse)
	{
		try
		{			
			$stmtpatients = $this->conn->prepare("INSERT INTO Patients (numSS, nom, prenom, dateNaissance, telephone, mail, sexe, taille, poids, commentaires, AdressesidAdresse) 
		                                               VALUES(:text_numSS, :text_nom, :text_prenom, :text_dateNaissance, :text_telephone, :text_mail, :text_sexe, :text_taille, :text_poids, :text_commentaires, :text_idAdresse)");
		
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
			$stmtpatients->bindparam(":text_idAdresse", $text_idAdresse);
	
			$stmtpatients->execute();	

			return $stmtpatients;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
}
?>