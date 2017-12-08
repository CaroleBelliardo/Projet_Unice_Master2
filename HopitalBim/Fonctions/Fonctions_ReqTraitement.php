<?php
	function endKey($array)
	{
	end($array);
	return key($array);
	}
	
	function extractReq ($inReq,$manip,$att1,$att2){ //TODO : recupere les valeurs de la première variable = > a supprimant en creant le tableau avant 
		$a_temp=[];
		while ($temp =  $inReq-> fetch(PDO::FETCH_ASSOC))
		{
			$a_temp[$temp[$att1]] = [$manip=>$temp[$att2]];
		}
		return($a_temp);
	}   
	function extractReq2 ($intab,$inReq,$manip,$att1,$att2){ // recupère les valeurs des exp
		while ($temp2 =  $inReq-> fetch(PDO::FETCH_ASSOC))
		{
			$tempo=$intab[$temp2[$att1]];
			$tempo[$manip] = $temp2[$att2];
			$intab[$temp2[$att1]] = $tempo;
		}
		return($intab);
	
	}
	function extractReq3 ($intab,$inReq){
		while ($temp2 =  $inReq-> fetch(PDO::FETCH_ASSOC))
		{
			if ($temp2["ServicesnomService"] != 'Informatique')
			{
				$tempo=$intab[$temp2["ServicesnomService"]]; //stock le tableau des stats du service  
				$a_info= ["idEmploye" => $temp2["EmployesCompteUtilisateursidEmploye"],  
				"Patient" => $temp2["numSS"]]; 
				array_push($tempo,["Med_Patient-MultiUrgence"=>$a_info]); 
				$intab[$temp2["ServicesnomService"]] = $tempo;
			}
		}
		return($intab);
	} 
	
	function reqToArray1Att($requete) //  une requete qui retroune plusieurs tuples (plusieurs lignes) 1 attribut (1 colonne)  -- retourne un tableau contenant toutes les valeurs d'un attribut, tableau 1D
	{
		$a_out_reqToArray=[];
		while ($row = $requete->fetchColumn())
		{
			array_push(	$a_out_reqToArray,$row );
		}
		return ($a_out_reqToArray);
	}
	
	function reqToArrayPlusAtt($requete) //  une requete qui retroune 1 plusieurs attributs  (plusieurs colonnes) pour un tuple (1 ligne) -- retourne un tableau contenant toutes les valeurs des attributs, tableau 1D
	{
		$a_out_reqToArray=[];
		while ($row = $requete->fetch(PDO::FETCH_ASSOC))
		{
			foreach ( $row as $cle=>$valeur)
			{
				array_push($a_out_reqToArray,$valeur);
			}
		
		}
		return ($a_out_reqToArray);
	}
	
	function reqToArrayPlusAttASSO($requete) //  une requete qui retroune 1 plusieurs attributs  (plusieurs colonnes) pour un tuple (1 ligne) -- retourne un tableau contenant toutes les valeurs des attributs, tableau 1D
	{
		$a_out_reqToArray=[];
		while ($row = $requete->fetch(PDO::FETCH_ASSOC))
		{
			foreach ( $row as $cle=>$valeur)
			{
				$a_out_reqToArray[$cle]=$valeur;
			}
		}
		return ($a_out_reqToArray);
	}
	
	function reqToArrayPlusligne($requete) // une requete qui retroune 1 plusieurs attributs  (plusieurs colonnes) pour plusieurs tuples (plusieurs ligne) -- retourne un tableau  de tableau contenant toutes les valeurs des attributs, tableau 2D
	{
		$a_out_reqToArray=[];
		while ($row = $requete->fetch(PDO::FETCH_ASSOC))
		{
			foreach ( $row as $attribut=>$col)
			{
				if(array_key_exists($attribut,$a_out_reqToArray))
				{
					$tempo=$a_out_reqToArray[$attribut];
					array_push($tempo,$col);
					$a_out_reqToArray[$attribut]=$tempo;
				}
				else
				{
					$a_out_reqToArray[$attribut]=[$col];
				}
			}
		
		}
		return ($a_out_reqToArray);
	}
	
		function reqToArray1AttPlusligne1($requete) // une requete qui retroune 1 plusieurs attributs  (plusieurs colonnes) pour plusieurs tuples (plusieurs ligne) -- retourne un tableau  de tableau contenant toutes les valeurs des attributs, tableau 2D
	{
		$a_out_reqToArray=[];
		while ($row = $requete->fetch(PDO::FETCH_ASSOC))
		{
			foreach ( $row as $attribut=>$col)
			{
				//Dumper($row); echo $row[$attribut]."<br>";
					echo $attribut."<br>";
					//echo $col."<br>";
					//echo $v."<br>";
					
					//$a_out_reqToArray[$attribut]=$col;
				
			}
		
		}
		return ($a_out_reqToArray);
	}
	
	
?>
