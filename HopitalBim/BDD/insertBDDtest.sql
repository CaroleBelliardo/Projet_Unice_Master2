# Insert SQL pour test

INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`, `porte`) VALUES ('Ba214', 'B', 'a', '2', '14');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`, `porte`) VALUES ('Ba218', 'B', 'a', '2', '18');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`, `porte`) VALUES ('Ca-111', 'C', 'a', '-1', '11');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`, `porte`) VALUES ('Cc144', 'C', 'c', '1', '44');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`, `porte`) VALUES ('Ae0105', 'A', 'e', '0', '105');

INSERT INTO `Services` (`nomService`, `telephone`, `mail`, `LocalisationServicesidLocalisation`) VALUES ('Imagerie', '0492071873', 'imagerie@hopitalbim.fr', 'Ba214');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`, `LocalisationServicesidLocalisation`) VALUES ('Gériatrie', '0492769548', 'geriatrie@hopitalbim.fr', 'Ba218');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`, `LocalisationServicesidLocalisation`) VALUES ('Pneumologie', '0492794516', 'pneumologie@hopitalbim.fr', 'Ca-111');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`, `LocalisationServicesidLocalisation`) VALUES ('Informatique', '0492798271', 'servicesinfo@hopitalbim.fr', 'Cc144');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`, `LocalisationServicesidLocalisation`) VALUES ('Cardiologie', '0492112589', 'cardiologie@hopitalbim.fr', 'Ae0105');

INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('lm2561', '$2y$10$aAQcmkz0AlBv3kX/bOFb3t.K5VsfrPs7B8kxMGxu1lp2mFuw//SfC') ; 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('ly1245', '$2y$10$gXQcmgz7JlZs3kX/aOFb3r.K2XdsfXs5V9kxXGxu1ol2mVuw//SfC') ; 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('cm1474', '$1y$10$aZZghjz4RlRr3kX/pOCb3t.K5VdsfPs1V5kxJGxu1ju2mFuw//SfC') ; 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('fd7898', '$7y$10$aXQcmkz7JlRy3kX/tORb3o.K3VszsPs5V5kxMXxu1np2mFuw//SfC') ; 

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
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '45', 'Place Bichot', 'Sevran'), (NULL, '78', 'rue nicolas II', 'Grasse');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '111', 'Boulevard Garnier', 'Nice'), (NULL, '78', 'rue Georges Clemenceau', 'Grasse');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '171', 'avenue Lamartine', 'Sevran'), (NULL, '7', 'Rue du Malonat', 'Nice');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '87', 'rue michel vaillant', 'Draguignan'), (NULL, '14', 'Georges Clemenceau', 'Draguignan');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '47', 'rue de Lyon', 'Paris'), (NULL, '112', 'avenue Foch', 'Paris');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '58', 'boulevard Vallier', 'Lyon'), (NULL, '78', 'rue Trachard', 'Paris');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '5', 'Boulevard Jurard', 'Lyon'), (NULL, '78', 'Boulevard De Gaulle', 'Cassis');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '11', 'avenue Bonaparte', 'Lille'), (NULL, '7', 'Rue de Paris', 'Nice');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `CodesPostauxvilles`) VALUES (NULL, '99', 'Route de Rivesalte', 'Nanterre'), (NULL, '14', 'Impasse Léon Ballanger', 'Lille');

INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('193040608833380', 'Pagnol', 'Marcel', '1993-04-20', '0493968228', 'marcelloudu06@hotmail.fr', 'M', '184', '86', 'fracture du genou en faisant du ski', '1');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('178854747412138', 'Pagnol', 'Marcel', '1978-07-10', '0493968333', 'pagnolm@free.fr', 'M', '178', '71', 'infection virale', '2');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('178945687887447', 'Poulet', 'Lucas', '1978-12-09', '0653968228', 'pouletlulu@gmail.com', 'M', '174', '79', 'allergique au gluten', '3');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('289158744895244', 'El Ghazi', 'Safia', '1989-11-01', '0498765435', 'elghazi@hotmail.fr', 'F', '164', '59', 'fracture du métatarse du pied droit', '4');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('289886784555147', 'Lotito', 'Sarah', '1989-08-07', '0493555228', 'sarah.lotito6@hotmail.fr', 'F', '163', '55', 'fragilité aux cervicales', '5');

INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('Admin', 'Marcelin', 'Lionel', '0675732947', 'lionel.marcelin@hopitalbim.fr', 'Informatique', '6');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('lm2561', 'Lotard', 'Marie', '0645467898', 'marie.lotard@hopitalbim.fr', 'Gériatrie', '7');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('ly1245', 'Lio', 'Yang', '0678451228', 'yang.lio@hopitalbim.fr', 'Imagerie', '8');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('cm1474', 'Cohen', 'David', '0698946525', 'david.cohen@hopitalbim.fr', 'Gériatrie', '9');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('fd7898', 'Filippi', 'Didier', '0677441215', 'filippi@hopitalbim.fr', 'Pneumologie', '10');

INSERT INTO `ChefServices` (`EmployesCompteUtilisateursidEmploye`, `ServicesnomService`) VALUES ('Admin', 'Informatique');
INSERT INTO `ChefServices` (`EmployesCompteUtilisateursidEmploye`, `ServicesnomService`) VALUES ('lm2561', 'Gériatrie');
INSERT INTO `ChefServices` (`EmployesCompteUtilisateursidEmploye`, `ServicesnomService`) VALUES ('fd7898', 'Pneumologie');

INSERT INTO `Pathologies` (`idPatho`, `nomPathologie`, `origine`, `souche`, `developpement`, `transmission`, `precautions`) VALUES ('GrippeA12', 'GrippeA', 'virale', 'H1N1', 'aigue', 'orale', 'mettre un masque');
INSERT INTO `Pathologies` (`idPatho`, `nomPathologie`, `origine`, `souche`, `developpement`, `transmission`, `precautions`) VALUES ('MPOC4', 'bronchite ', 'viro-bactérienne', 'inconnue', 'chronique', NULL, 'éviter exposition à des substances irritantes');
INSERT INTO `Pathologies` (`idPatho`, `nomPathologie`, `origine`, `souche`, `developpement`, `transmission`, `precautions`) VALUES ('CardioVasculaire12', 'myocardiopathie', NULL, NULL, 'chronique', NULL, 'myocardiopathie ischémique');
INSERT INTO `Pathologies` (`idPatho`, `nomPathologie`, `origine`, `souche`, `developpement`, `transmission`, `precautions`) VALUES ('Cancerbronchopulmonaire1', 'Adénocarcinome', NULL, NULL, NULL, NULL, 'cancer bronchopulmonaire à petites cellules');
INSERT INTO `Pathologies` (`idPatho`, `nomPathologie`, `origine`, `souche`, `developpement`, `transmission`, `precautions`) VALUES ('IRA2.9', 'pneumonie', 'bactérienne', 'Mycoplasma pneumonia', 'aigue', 'aeroportée', 'mettre un masque, se laver les mains régulièrement');

INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES ('TransCoeur', 'Transplantation', 'Cardiologie'), ('OperationFemurD', 'Operation', 'Gériatrie'), ('HospPoumonsDG', 'Hospitalisation', 'Pneumologie'), ('IRMTete', 'IRM', 'Imagerie'), ('RadioPoignetD', 'Radiologie', 'Imagerie');

# Pour les floats, il faut mettre des '.' et non pas des ','
INSERT INTO `Tarifications` (`InterventionsidIntervention`, `tarif`) VALUES ('HospPoumonsDG', '76'), ('IRMTete', '134.4'), ('OperationFemurD', '132.0'), ('RadioPoignetD', '15'), ('TransCoeur', '434');

INSERT INTO `InterventionsPatho` (`PathologiesidPatho`, `InterventionsidIntervention`, `niveauUrgenceMax`, `niveauUrgenceMin`) VALUES ('IRA2.9', 'HospPoumonsDG', '3', '1'), ('CardioVasculaire12', 'TransCoeur', '3', '3'), ('GrippeA12', 'HospPoumonsDG', '2', '1'), ('Cancerbronchopulmonaire1', 'HospPoumonsDG', 'NULL', 'NULL'), ('MPOC4', 'HospPoumonsDG', '1', '2');

INSERT INTO `CreneauxInterventions` (`date_rdv`, `heure`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `pathologie`, `commentaires`, `VerifCoherencePathologieRef`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES ('2017-11-01', '03:00:00', 'TransCoeur', '3', 'realisee', 'Cardiomyopathie', 'Transplantation cardiaque a 3h du matin. Très Urgent', '?????????', '178945687887447', 'cm1474'), ('2017-11-02', '12:30:00', 'HospPoumonsDG', '3', 'annulée', 'Pneumonie', 'Patient décédé', NULL, '289886784555147', 'lm2561');
INSERT INTO `CreneauxInterventions` (`date_rdv`, `heure`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `pathologie`, `commentaires`, `VerifCoherencePathologieRef`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES ('2017-11-04', '15:00:00', 'IRMTete', '1', 'reportée', 'Céphalée', 'le patient n\'a plus mal à la tête', NULL, '178854747412138', 'cm1474');


