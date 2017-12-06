<?php
    function gestionUrgence($auth_user,$idIntervention,$niveauUrgence, $a_infoDateHeure,$a_horaireFermeture)
    {
    // Si la demande respect un certain delais (specifique a chaque niveau d'urgence) alors on ne perturbe pas le planning et on insert le rdv à la suite du planning ou a la place d'un rdv annule
    // Sinon on place le rdv à la suite des rendez vous de même niveau et on décale les suivants au risque de dépasser les horraires d'ouverture du service
        If ($niveauUrgence !=0) //  determine le delais a respecter
        {
            $now=ProchaineHeureArrondie();  // si rdv = urgent -> determine le delais relatif au niveau d'urgence
            $day = date('Y-m-d');
            echo "2. Rendez vous urgent niveau ",$niveauUrgence,"on est :",$now."<br>";
            
            
            switch ($niveauUrgence) // fixe un delais selon niveau urgence
            {
                case 3:
                    $heureDelais=heurePlus15($now,'+180 minutes');
                    if ($heureDelais < $now)
                    {
                        $dateDelais = date('Y-m-d', strtotime('+1 day'));
                    }else {$dateDelais= date('Y-m-d');}
                    echo $day.$heureDelais." est le delais imposé par switch delais : cas 3 <br>";
                    break;//alors on insert à la suite
                
                case 2:
                    $heureDelais=heurePlus15($now,'+360 minutes');
                    if ($heureDelais < $now)
                    {
                        $dateDelais = date('Y-m-d', strtotime('+1 day'));
                    }else {$day= date('Y-m-d');}
                    echo $day.$heureDelais." est le delais imposé par switch delais : cas 2 <br>";
                    break;
            
                case 1:
                    $day = date('Y-m-d', strtotime('+1 day'));
                    $heureDelais=$now;
                    echo $day.$heureDelais." est le delais imposé par switch delais : cas 1 <br>";
                    break;
            }
            echo "...*****************<br>";
       
       // delais pas respecté mais le service est fermé
       
       
       
       
            if (($a_infoDateHeure["dateR"] >  $day   )
                or (($a_infoDateHeure["dateR"] =  $day ) and  ($a_infoDateHeure["heureR"] > $heureDelais))) // si premier creneau dispo est hors delais on recherche un autre creneaux dont rdv < urgent et on decale les rendez-vous suivant
        
            {
                echo '1.1 dateR = dateDelais et dateR > dateDelais ou   dateR > dateDelais et heureR > heureDelais; on appel la fonction gestion d urgences <br>';
                $a_infoDateHeureUrgence=CreneauxUrgent($auth_user,$niveauUrgence,$idIntervention ); 
       
            //-- Recherche le dernier creneau dont niveau d'urgence >= au niveau d'urgence
                Dumper($a_infoDateHeureUrgence);
                if ($a_infoDateHeureUrgence != FALSE)
                {
                    echo "dernier creneaux ou niveau urgence >= niveau demandé(".$a_infoDateHeureUrgence['dateR'].$a_infoDateHeureUrgence['heureR']."<br>";

                    //$a_infoDateHeureUrgence = ["dateR"=>" ", "heureR"=>" ",  "statutR"=>"p",    "niveauUrgenceR"=>$nivUrg];
                    echo " 1.2 il y a un rdv urgent de meme niveau d'urgence ou de niveau > ou un rdv annulé  <br>";
                    Dumper ($a_infoDateHeureUrgence);
                    if ($a_infoDateHeureUrgence['statutR'] == 'p')
                    {
                        $a_infoDateHeure["heureR"]= heurePlus15($a_infoDateHeureUrgence["heureR"],'+15 minutes');					
                        echo "1.3 statut creneauUrg = p : Donc creneauUrg + 15 minutes pour creneaux suivant",$a_infoDateHeureUrgence['dateR']." ".$a_infoDateHeureUrgence['heureR']."<br>";
                    }
                    else
                    {
                        $a_infoDateHeure["heureR"]= $a_infoDateHeureUrgence["heureR"];					
                        echo " 1.3 statut creneauUrg = a donc creneaux suivant = crendeauUrg : ".$a_infoDateHeureUrgence['dateR']." ".$a_infoDateHeureUrgence['heureR']."<br>";
                    } 
                }
                else
                {
                   if (($now > $a_horaireFermeture['horaire_fermeture']) and ($now > $a_horaireFermeture['horaire_ouverture']))
                   {
                    echo "Il n'y a pas de rendez vous prevu d'urgence identique ou > donc on va mettre le rdv mtn " ;
                    Dumper ($a_infoDateHeureUrgence);
                    $a_infoDateHeureUrgence['dateR']= date('Y-m-d', strtotime('+1 day')); /// $day + 1 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

                    $a_infoDateHeureUrgence['heureR']= $a_horaireFermeture['horaire_ouverture'];
                    echo " 1.2 : ".$a_infoDateHeureUrgence['dateR']." ".$a_infoDateHeureUrgence['heureR']."<br>";
                    echo " 1.2 donc il y a un rdv de meme niveau urgence => on prend le creneaux suivant <br>";
                   }
                   elseif (($now <  $a_horaireFermeture['horaire_fermeture']) and ($now < $a_horaireFermeture['horaire_ouverture']))
                   {
                     $a_infoDateHeureUrgence['dateR']= $day; 
                    $a_infoDateHeureUrgence['heureR']= $a_horaireFermeture['horaire_ouverture'];
                   }
                   else
                    echo "Il n'y a pas de rendez vous prevu d'urgence identique ou > donc on va mettre le rdv mtn " ;
                    Dumper ($a_infoDateHeureUrgence);
                    $a_infoDateHeureUrgence['dateR']= $day; 
                    $a_infoDateHeureUrgence['heureR']= $now;
                    echo " 1.2 : ".$a_infoDateHeureUrgence['dateR']." ".$a_infoDateHeureUrgence['heureR']."<br>";
                    echo " 1.2 donc il y a un rdv de meme niveau urgence => on prend le creneaux suivant <br>";
               }
                
                
            
                


            //-- Decale rdv suivant  SI niveau urgence != 0
                //recupere tous les creneaux suivant
                echo 'est ce qu on decale les rdv suivant ?'.'<br>';
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
                
            
                if (array_key_exists("id_rdv",$a_creneauSuiv))
                {
                echo "il y a des rdv pendant ou après le creneaux selectionné <br>";
                $req_upDateHoraire = $auth_user->runQuery(" UPDATE CreneauxInterventions
                                                            SET heure_rdv = :newHeure,
                                                            niveauUrgence = (niveauUrgence + '1') 
                                                            WHERE id_rdv = :id_rdv" );
                    echo " pour chaque id_rdv : ";
                    foreach($a_creneauSuiv["id_rdv"] as $k=>$v) /////  ???  faire le while sur le fetch ??????????????????????????????????????
                    {
                        echo "id : $k <br>";
                        
                        if ($a_creneauSuiv["statut"][$k]  == 'a')
                        {
                            echo ' rencontre un rdv annulé -> arrete de décalier',$a_creneauSuiv["statut"]["id_rdv"]."<br>";
                            break;
                        }
                        else
                        {
                            echo ' rencontre un rdv  == décale '.$a_creneauSuiv["id_rdv"][$k]."<br>";
                            $newHeure= heurePlus15($a_creneauSuiv["heure_rdv"][$k],'+15 minutes');
                            $req_upDateHoraire->execute(array("id_rdv" => $a_creneauSuiv["id_rdv"][$k],
                                                              "newHeure" =>$newHeure
                                                              ));
                            $req_upDateHoraire->closeCursor();
                        }
                    }
                    echo "OKAY : on a decalé les rdv suivants et l'heure du rdv a inserer est : ", $a_infoDateHeure['heureR']."<br>";
                }// fin d'instruction si dispo = hors delais
                // test si il y a surbooking	
                Eval_notif_Surbooking ($auth_user,$idIntervention,$a_infoDateHeureUrgence,$a_horaireFermeture);
            } else { echo " 1.1 on est dans le delais <br>" ;
                
            }
        } // fin instruction si urgence !=0
    return ($a_infoDateHeure);
    }
?>