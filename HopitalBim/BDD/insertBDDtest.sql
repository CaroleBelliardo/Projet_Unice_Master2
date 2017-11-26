# Insert SQL pour test

INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'B', 'a', '2');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'B', 'a', '2');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'C', NULL, '-1');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'C', 'c', '1');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'A', 'e', '0');

INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Imagerie', '0492071873', 'imagerie@hopitalbim.fr','08:00','17:00', '1');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`, `LocalisationServicesidLocalisation`) VALUES ('Gériatrie', '0492769548', 'geriatrie@hopitalbim.fr','06:00','17:00', '2');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`, `LocalisationServicesidLocalisation`) VALUES ('Pneumologie', '0492794516', 'pneumologie@hopitalbim.fr','08:00','18:00', '3');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`, `LocalisationServicesidLocalisation`) VALUES ('Informatique', '0492798271', 'servicesinfo@hopitalbim.fr','10:00','17:00', '4');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`, `LocalisationServicesidLocalisation`) VALUES ('Cardiologie', '0492112589', 'cardiologie@hopitalbim.fr','07:30','18:30', '5');

INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('admin00', '$2y$10$aXQcmkz0JlTy3kX/gOFb3u.K5VhyaPs5V5kxMGxu1np2mFuw//SfC') ; 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('lm25610', '$2y$10$aXQcmkz0JlTy3kX/gOFb3u.K5VhyaPs5V5kxMGxu1np2mFuw//SfC') ; 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('ly12454', '$2y$10$aXQcmkz0JlTy3kX/gOFb3u.K5VhyaPs5V5kxMGxu1np2mFuw//SfC') ; 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('cm14743', '$2y$10$aXQcmkz0JlTy3kX/gOFb3u.K5VhyaPs5V5kxMGxu1np2mFuw//SfC') ; 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('fd78980', '$2y$10$aXQcmkz0JlTy3kX/gOFb3u.K5VhyaPs5V5kxMGxu1np2mFuw//SfC') ; 

INSERT INTO `Villes` (`idVilles`, `codepostal`, `nomVilles`,`departement`, `pays`) VALUES (NULL, '06000','Nice', '06', 'France');
INSERT INTO `Villes` (`idVilles`, `codepostal`, `nomVilles`,`departement`, `pays`) VALUES (NULL, '06130','Grasse', '06', 'France');
INSERT INTO `Villes` (`idVilles`, `codepostal`, `nomVilles`,`departement`, `pays`) VALUES (NULL, '83200','Draguignan', '83', 'France');
INSERT INTO `Villes` (`idVilles`, `codepostal`, `nomVilles`,`departement`, `pays`) VALUES (NULL, '93270','Sevran', '93', 'France');
INSERT INTO `Villes` (`idVilles`, `codepostal`, `nomVilles`,`departement`, `pays`) VALUES (NULL, '06700','Cannes', '06', 'France');
INSERT INTO `Villes` (`idVilles`, `codepostal`, `nomVilles`,`departement`, `pays`) VALUES (NULL, '69200','Lyon', '69', 'France');
INSERT INTO `Villes` (`idVilles`, `codepostal`, `nomVilles`,`departement`, `pays`) VALUES (NULL, '75000','Paris', '75', 'France');
INSERT INTO `Villes` (`idVilles`, `codepostal`, `nomVilles`,`departement`, `pays`) VALUES (NULL, '13300','Marseille', '13', 'France');
INSERT INTO `Villes` (`idVilles`, `codepostal`, `nomVilles`,`departement`, `pays`) VALUES (NULL, '59100','Lille', '59', 'France');
INSERT INTO `Villes` (`idVilles`, `codepostal`, `nomVilles`,`departement`, `pays`) VALUES (NULL, '92200','Nanterre', '92', 'France');

INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '11', 'rue_prince_maurice', '1'), (NULL, '112', 'rue paul ricard', '1');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '45', 'place bichot', '4'), (NULL, '78', 'rue nicolas II', '2');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '111', 'Boulevard Garnier', '1'), (NULL, '78', 'rue georges clemenceau', '2');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '171', 'avenue lamartine', '4'), (NULL, '7', 'rue du malonat', '1');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '87', 'rue michel vaillant', '3'), (NULL, '14', 'georges clemenceau', '3');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '47', 'rue de lyon', '7'), (NULL, '112', 'avenue foch', '7');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '58', 'boulevard vallier', '6'), (NULL, '78', 'rue trachard', '7');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '5', 'boulevard jurard', '6'), (NULL, '78', 'boulevard de gaulle', '8');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '11', 'avenue bonaparte', '9'), (NULL, '7', 'rue de paris', '1');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '99', 'route de rivesalte', '10'), (NULL, '14', 'impasse léon ballanger', '9');

INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('193040608833380', 'Pagnol', 'Marcel', '1993-04-20', '0493968228', 'marcelloudu06@hotmail.fr', 'M', '184', '86', 'fracture du genou en faisant du ski', '1');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('178854747412138', 'Pagnol', 'Marcel', '1978-07-10', '0493968333', 'pagnolm@free.fr', 'M', '178', '71', 'infection virale', '2');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('178945687887447', 'Poulet', 'Lucas', '1978-12-09', '0653968228', 'pouletlulu@gmail.com', 'M', '174', '79', 'allergique au gluten', '3');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('289158744895244', 'El Ghazi', 'Safia', '1989-11-01', '0498765435', 'elghazi@hotmail.fr', 'F', '164', '59', 'fracture du métatarse du pied droit', '4');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('289886784555147', 'Lotito', 'Sarah', '1989-08-07', '0493555228', 'sarah.lotito6@hotmail.fr', 'F', '163', '55', 'fragilité aux cervicales', '5');

INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('admin00', 'Marcelin', 'Lionel', '0675732947', 'lionel.marcelin@hopitalbim.fr', 'Informatique', '6');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('lm25610', 'Lotard', 'Marie', '0645467898', 'marie.lotard@hopitalbim.fr', 'Gériatrie', '7');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('ly12454', 'Lio', 'Yang', '0678451228', 'yang.lio@hopitalbim.fr', 'Imagerie', '8');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('cm14743', 'Cohen', 'David', '0698946525', 'david.cohen@hopitalbim.fr', 'Gériatrie', '9');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('fd78980', 'Filippi', 'Didier', '0677441215', 'filippi@hopitalbim.fr', 'Pneumologie', '10');

INSERT INTO `ChefServices` (`EmployesCompteUtilisateursidEmploye`, `ServicesnomService`) VALUES ('admin00', 'Informatique');
INSERT INTO `ChefServices` (`EmployesCompteUtilisateursidEmploye`, `ServicesnomService`) VALUES ('lm25610', 'Gériatrie');
INSERT INTO `ChefServices` (`EmployesCompteUtilisateursidEmploye`, `ServicesnomService`) VALUES ('fd78980', 'Pneumologie');

INSERT INTO `Pathologies` (`idPatho`, `nomPathologie`, `indication`, `precautions`) VALUES (NULL, 'Grippe', 'H1N1', 'mettre un masque');
INSERT INTO `Pathologies` (`idPatho`, `nomPathologie`, `indication`, `precautions`) VALUES (NULL, 'Bronchite ', 'chronique', 'éviter exposition à des substances irritantes');
INSERT INTO `Pathologies` (`idPatho`, `nomPathologie`, `indication`, `precautions`) VALUES (NULL, 'Myocardiopathie', 'extrinsèque', NULL);
INSERT INTO `Pathologies` (`idPatho`, `nomPathologie`, `indication`, `precautions`) VALUES (NULL, 'Adénocarcinome', 'pulmonaire', NULL);
INSERT INTO `Pathologies` (`idPatho`, `nomPathologie`, `indication`, `precautions`) VALUES (NULL, 'Pneumonie', DEFAULT, 'mettre un masque, se laver les mains régulièrement');

INSERT INTO `Interventions` (`idIntervention`, `acte`, `indication`, `ServicesnomService`) VALUES (NULL, 'Transplantation','cardiaque','Cardiologie'), 
(NULL, 'Operation','fémur','Gériatrie'), 
(NULL, 'Hospitalisation',DEFAULT, 'Pneumologie'),
(NULL, 'IRM', 'thorax','Imagerie'), 
(NULL, 'Radiologie','main','Imagerie');

# Pour les floats, il faut mettre des '.' et non pas des ','
INSERT INTO `Tarifications` (`InterventionsidIntervention`, `tarif_euros`) VALUES ('1', '76'), 
('2', '134.4'), ('3', '132.0'), ('4', '15'), ('5', '434');

INSERT INTO `InterventionsPatho` (`PathologiesidPatho`, `InterventionsidIntervention`, `niveauUrgenceMax`, `niveauUrgenceMin`) VALUES ('1', '3', NULL, NULL), 
('2', '4', NULL, NULL), 
('3', '1', NULL, NULL), 
('4', '3', NULL, NULL), 
('5', '3', NULL, NULL);

INSERT INTO `CreneauxInterventions` (`date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `pathologie`, `commentaires`, `VerifCoherencePathoUrgences`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES ('2017-11-01', '03:00:00', '1', '3', 'r', 'Cardiomyopathie', 'Transplantation cardiaque à 3h du matin. Très Urgent', NULL, '178945687887447', 'cm14743'), 
('2017-11-02', '12:30:00', '3', '3', 'a', 'Pneumonie', 'Patient décédé', 'OUI', '289886784555147', 'lm25610'),
('2017-11-04', '15:00:00', '4', '1', DEFAULT, NULL, 'le patient a mal aux niveau des côtes', NULL, '178854747412138', 'cm14743');


