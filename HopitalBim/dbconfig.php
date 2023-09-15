<?php
//- le fichier “dbconfig.php” qui contient la classe “Basededonnee”. Cette classe permet de récupérer de façon privé l’ensemble des informations permettant l'établissement de la connection avec la base de donnée. La localisation, le nom, le nom d’utilisateur et le mot de passe sont déclaré dans cette classe. La fonction “bddConnection()” permet de réaliser la connection à la base de donnée. De plus, les erreurs de connection sont récupérées et peuvent être affichées.

class Basededonnee
{   
    private $host = "localhost";
    private $db_name = "bdd";
    private $username = "root";
    private $password = "";
    public $conn;
     
    public function bddConnection()
	{
     
	    $this->conn = null;    
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
		{
            echo "Erreur de connection " . $exception->getMessage();
        }
         
        return $this->conn;
    }
}
?>