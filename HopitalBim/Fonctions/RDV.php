

	
	
	function VerificationPathologie ($auth_user,$niveauUrgence, $idPatho,$idIntervention )
	{
	
		$recupUrgMaxMin=$auth_user->runQuery("SELECT niveauUrgenceMax, niveauUrgenceMin
										FROM InterventionsPatho  
										WHERE PathologiesidPatho =:idPatho 
										AND InterventionsidIntervention=:idIntervention "); 
		$recupUrgMaxMin->execute(array('idPatho'=>$idPatho,
									'idIntervention'=>$idIntervention));

		$var=$recupUrgMaxMin->fetch(PDO::FETCH_ASSOC);
	return ($var);
	}