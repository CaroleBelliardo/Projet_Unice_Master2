<?php
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
		//
	{
		$a_out_reqToArray=[];
		while ($row = $requete->fetch(PDO::FETCH_ASSOC))
		{
			foreach ( $row as $attribut=>$col)
			{
				if(array_key_exists($attribut,$a_out_reqToArray))
				{
					$tempo=$a_out_reqToArray[$attribut];
					push($tempo,$col);
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
	
	
?>
