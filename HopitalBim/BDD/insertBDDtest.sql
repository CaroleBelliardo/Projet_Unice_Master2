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

INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('lm25610', '$2y$10$aAQcmkz0AlBv3kX/bOFb3t.K5VsfrPs7B8kxMGxu1lp2mFuw//SfC') ; 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('ly12454', '$2y$10$gXQcmgz7JlZs3kX/aOFb3r.K2XdsfXs5V9kxXGxu1ol2mVuw//SfC') ; 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('cm14743', '$1y$10$aZZghjz4RlRr3kX/pOCb3t.K5VdsfPs1V5kxJGxu1ju2mFuw//SfC') ; 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('fd78980', '$7y$10$aXQcmkz7JlRy3kX/tORb3o.K3VszsPs5V5kxMXxu1np2mFuw//SfC') ; 

INSERT INTO `Departements` (`departement`, `pays`) VALUES ('01','France'),('02','France'),('03','France'),('04','France'),('05','France'),('06','France'),
('07','France'),('08','France'),('09','France'),('10','France'),('11','France'),('12','France'),('13','France'),('14','France'),('15','France'),('16','France'),
('17','France'),('18','France'),('19','France'),('21','France'),('22','France'),('23','France'),('24','France'),('25','France'),('26','France'),('27','France'),
('28','France'),('29','France'),('2A','France'),('2B','France'),('30','France'),('31','France'),('32','France'),('33','France'),('34','France'),('35','France'),
('36','France'),('37','France'),('38','France'),('39','France'),('40','France'),('41','France'),('42','France'),('43','France'),('44','France'),('45','France'),
('46','France'),('47','France'),('48','France'),('49','France'),('50','France'),('51','France'),('52','France'),('53','France'),('54','France'),('55','France'),
('56','France'),('57','France'),('58','France'),('59','France'),('60','France'),('61','France'),('62','France'),('63','France'),('64','France'),('65','France'),
('66','France'),('67','France'),('68','France'),('69','France'),('70','France'),('71','France'),('72','France'),('73','France'),('74','France'),('75','France'),
('76','France'),('77','France'),('78','France'),('79','France'),('80','France'),('81','France'),('82','France'),('83','France'),('84','France'),('85','France'),
('86','France'),('87','France'),('88','France'),('89','France'),('90','France'),('91','France'),('92','France'),('93','France'),('94','France'),('95','France'),
('971','France'),('972','France'),('973','France'),('974','France'),('975','France'),('976','France');

INSERT INTO `CodesPostaux` (`villes`, `Departementsdepartement`, `codepostal`, `Departementspays`) VALUES ('Nice', '06', '06000', 'France');
INSERT INTO `CodesPostaux` (`villes`, `Departementsdepartement`, `codepostal`, `Departementspays`) VALUES ('Grasse', '06', '06130', 'France');
INSERT INTO `CodesPostaux` (`villes`, `Departementsdepartement`, `codepostal`, `Departementspays`) VALUES ('Draguignan', '83', '83200', 'France');
INSERT INTO `CodesPostaux` (`villes`, `Departementsdepartement`, `codepostal`, `Departementspays`) VALUES ('Sevran', '93', '93270', 'France');
INSERT INTO `CodesPostaux` (`villes`, `Departementsdepartement`, `codepostal`, `Departementspays`) VALUES ('Cannes', '06', '06700', 'France');
INSERT INTO `CodesPostaux` (`villes`, `Departementsdepartement`, `codepostal`, `Departementspays`) VALUES ('Lyon', '69', '69200', 'France');
INSERT INTO `CodesPostaux` (`villes`, `Departementsdepartement`, `codepostal`, `Departementspays`) VALUES ('Paris', '75', '75000', 'France');
INSERT INTO `CodesPostaux` (`villes`, `Departementsdepartement`, `codepostal`, `Departementspays`) VALUES ('Cassis', '13', '13300', 'France');
INSERT INTO `CodesPostaux` (`villes`, `Departementsdepartement`, `codepostal`, `Departementspays`) VALUES ('Lille', '59', '59100', 'France');
INSERT INTO `CodesPostaux` (`villes`, `Departementsdepartement`, `codepostal`, `Departementspays`) VALUES ('Nanterre', '92', '92200', 'France');

INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '11', 'rue_prince_maurice', 'Nice'), (NULL, '112', 'rue paul ricard', 'Nice');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '45', 'place bichot', 'Sevran'), (NULL, '78', 'rue nicolas II', 'Grasse');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '111', 'Boulevard Garnier', 'Nice'), (NULL, '78', 'rue georges clemenceau', 'Grasse');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '171', 'avenue lamartine', 'Sevran'), (NULL, '7', 'rue du malonat', 'Nice');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '87', 'rue michel vaillant', 'Draguignan'), (NULL, '14', 'georges clemenceau', 'Draguignan');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '47', 'rue de lyon', 'Paris'), (NULL, '112', 'avenue foch', 'Paris');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '58', 'boulevard vallier', 'Lyon'), (NULL, '78', 'rue trachard', 'Paris');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '5', 'boulevard jurard', 'Lyon'), (NULL, '78', 'boulevard de gaulle', 'Cassis');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '11', 'avenue bonaparte', 'Lille'), (NULL, '7', 'rue de paris', 'Nice');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '99', 'route de rivesalte', 'Nanterre'), (NULL, '14', 'impasse léon ballanger', 'Lille');

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


