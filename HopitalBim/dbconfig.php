<?php
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