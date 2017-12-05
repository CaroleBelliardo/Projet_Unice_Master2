<?php
    function gestionUrgence($auth_user,$idIntervention,$niveauUrgence, $a_infoDateHeure,$a_horaireFermeture)
    {
    // Si la demande respect un certain delais (specifique a chaque niveau d'urgence) alors on ne perturbe pas le planning et on insert le rdv à la suite du planning ou a la place d'un rdv annule
    // Sinon on place le rdv à la suite des rendez vous de même niveau et on décale les suivants au risque de dépasser les horraires d'ouverture du service
        If ($niveauUrgence !=0) //  determine le delais a respecter
        {
            $now=ProchaineHeureArrondie();  // si rdv = urgent -> determine le delais relatif au niveau d'urgence
            echo "2. Rendez vous urgent niveau ",$niveauUrgence,"on est :",$now."<br>";
            
            
            switch ($niveauUrgence) // fixe un delais selon niveau urgence
            {
                case 3:
                    $heureDelais=heurePlus15($now,'+180 minutes');
                    if ($heureDelais < $now)
                    {
                        $dateDelais = date('Y-m-d', strtotime('+1 day'));
                    }else {$dateDelais= date('Y-m-d');}
                    echo $dateDelais.$heureDelais." est le delais imposé par switch delais : cas 3 <br>";
                    break;//alors on insert à la suite 
                case 2:
                    $heureDelais=heurePlus15($now,'+360 minutes');
                    if ($heureDelais < $now)
                    {
                        $dateDelais = date('Y-m-d', strtotime('+1 day'));
                    }else {$dateDelais= date('Y-m-d');}
                    echo $dateDelais.$heureDelais." est le delais imposé par switch delais : cas 2 <br>";
                    break;
                case 1:
                    $dateDelais = date('Y-m-d', strtotime('+1 day'));
                    $heureDelais=$now;
                    echo $dateDelais.$heureDelais." est le delais imposé par switch delais : cas 1 <br>";
                    break;
            }
            echo "...*****************";
            if (($a_infoDateHeure["dateR"] >  $dateDelais   ) or (($a_infoDateHeure["dateR"] =  $dateDelais ) and   
            ($a_infoDateHeure["heureR"] > $heureDelais))) // si premier creneau dispo est hors delais on recherche un autre creneaux dont rdv < urgent et on decale les rendez-vous suivant
            {
                echo ' dateR >> dateDelais ou dateR == dateDelais mais dateR == dateDelais et heureR > heureDelais <br>';
        //-- Recherche le dernier creneau dont niveau d'urgence >= au niveau d'urgence
                $a_infoDateHeureUrgence=prochainCreneauxUrgent($auth_user,$niveauUrgence,$idIntervention ); 
                echo "dernier creneaux ou niveau urgence =< niveau demandé(",$a_infoDateHeureUrgence['niveauUrgenceR'],$a_infoDateHeureUrgence['dateR'],$a_infoDateHeureUrgence['heureR']."<br>";


                if ($a_infoDateHeureUrgence['statutR'] == 'p')
                {
                    $a_infoDateHeure["heureR"]= heurePlus15($a_infoDateHeureUrgence["heureR"],'+15 minutes');					
                    echo "statut creneauUrg = p : Donc creneauUrg + 15 minutes pour creneaux suivant",$a_infoDateHeureUrgence['dateR']." ".$a_infoDateHeureUrgence['heureR']."<br>";
                }
                else
                {
                    $a_infoDateHeure["heureR"]= $a_infoDateHeureUrgence["heureR"];					
                    echo "statut creneauUrg = a donc creneaux suivant = crendeauUrg : ".$a_infoDateHeureUrgence['dateR']." ".$a_infoDateHeureUrgence['heureR']."<br>";
                } 


            //-- Decale rdv suivant  SI niveau urgence != 0
            //recupere tous les creneaux suivant
                echo 'est ce qu on decale les rdv suivant ?';
                $req_CreneauSuivant = $auth_user->runQuery(" Select id_rdv, heure_rdv, statut FROM CreneauxInterventions 
                                                        WHERE InterventionsidIntervention = :idIntervention
                                                        AND date_rdv = :date
                                                        AND heure_rdv >= :heure" ); 
                $req_CreneauSuivant->execute(array('idIntervention'=> $idIntervention,
                                                    'date'=> $a_infoDateHeureUrgence["dateR"] ,
                                                    'heure'=> $a_infoDateHeure["heureR"]));
                
                $a_creneauSuiv= reqToArrayPlusligne($req_CreneauSuivant);
                $req_CreneauSuivant->closeCursor(); 
            
            
            // upDate toutes les heures suivantes 
                $req_upDateHoraire = $auth_user->runQuery(" UPDATE CreneauxInterventions
                                                            SET heure_rdv = :newHeure,
                                                            niveauUrgence = (niveauUrgence + '1') 
                                                            WHERE id_rdv = :id_rdv" );
            
                if (array_key_exists("id_rdv",$a_creneauSuiv))
                echo "il y a des rdv pendant ou après le creneaux selectionné <br>";
                {
                    echo " pour chaque id_rdv : ";
                    foreach($a_creneauSuiv["id_rdv"] as $k=>$v) /////  ???  faire le while sur le fetch ??????????????????????????????????????
                    {
                        echo "id : $k <br>";
                        
                        if ($a_creneauSuiv["statut"][$k]  == 'a')
                        {
                            echo ' rencontre un rdv annulé -> arrete de décalier',$a_creneauSuiv["statut"]["id_rdv"];
                            break;
                        }
                        else
                        {
                            echo ' rencontre un rdv  == décale '.$a_creneauSuiv["id_rdv"][$k];
                            $newHeure= heurePlus15($a_creneauSuiv["heure_rdv"][$k],'+15 minutes');
                            $req_upDateHoraire->execute(array("id_rdv" => $a_creneauSuiv["id_rdv"][$k],
                                                              "newHeure" =>$newHeure
                                                              ));
                            $req_upDateHoraire->closeCursor();
                        }
                    }
                    echo "OKAY : on a decalé les rdv suivants et l'heure du rdv a inserer est : ", $a_infoDateHeure['heureR'];
                }// fin d'instruction si dispo = hors delais
                // test si il y a surbooking	
                Eval_notif_Surbooking ($auth_user,$idIntervention,$a_infoDateHeureUrgence,$a_horaireFermeture);
            } 
                
        } // fin instruction si urgence !=0
    return ($a_infoDateHeure);
    }
?>