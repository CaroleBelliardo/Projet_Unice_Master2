INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'B', 'a', '2');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'D', 'a', '1');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'C', 'b', '-1');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'C', 'a', '1');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'A', 'a', '0');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'B', 'b', '2');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'D', 'a', '2');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'C', 'a', '-1');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'C', 'a', '3');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'A', 'e', '0');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'B', 'a', '1');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'D', 'b', '2');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'C', 'd', '-1');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'C', 'c', '1');
INSERT INTO `LocalisationServices` (`idLocalisation`, `batiment`, `aile`, `etage`) VALUES (NULL, 'A', 'a', '1');

INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Imagerie', '0492071873', 'imagerie@hopitalbim.fr','08:00','17:00', '1');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Geriatrie', '0492769548', 'geriatrie@hopitalbim.fr','06:00','17:00', '2');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Pneumologie', '0492794516', 'pneumologie@hopitalbim.fr','08:00','18:00', '3');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Informatique', '0492798271', 'servicesinfo@hopitalbim.fr','10:00','17:00', '4');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Cardiologie', '0492112589', 'cardiologie@hopitalbim.fr','07:30','18:30', '5');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Gynecologie', '0492011873', 'gynecologie@hopitalbim.fr','08:00','17:00', '6');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Dermatologie', '0492739548', 'dermato@hopitalbim.fr','06:00','17:00', '7');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Ophtalmologie', '0492798516', 'ophtalmo@hopitalbim.fr','08:00','18:00', '8');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Pediatrie', '0492791271', 'pediatrie@hopitalbim.fr','10:00','17:00', '9');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Urologie', '0492111889', 'urologie@hopitalbim.fr','07:30','18:30', '10');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Dentisterie', '0492077873', 'dentisterie@hopitalbim.fr','08:00','19:00', '11');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Reanimation', '0492789873', 'reanimation@hopitalbim.fr','08:00','23:00', '12');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Psychiatrie', '0492769548', 'psychiatrie@hopitalbim.fr','06:00','17:00', '13');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Phlebologie', '0492711216', 'phlebologie@hopitalbim.fr','08:00','18:00', '14');
INSERT INTO `Services` (`nomService`, `telephone`, `mail`,`horaire_ouverture`,`horaire_fermeture`,`LocalisationServicesidLocalisation`) VALUES ('Endocrinologie', '0492112139', 'endocrino@hopitalbim.fr','07:30','16:30', '15');

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

INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '9', 'avenue thiers', '1'), (NULL, '112', 'rue paul ricard', '1');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '11', 'rue_prince_maurice', '1'), (NULL, '118', 'rue paul ricard', '1');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '45', 'place bichot', '4'), (NULL, '78', 'rue nicolas II', '2');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '111', 'Boulevard Garnier', '1'), (NULL, '78', 'rue georges clemenceau', '2');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '171', 'avenue lamartine', '4'), (NULL, '7', 'rue du malonat', '1');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '87', 'rue michel vaillant', '3'), (NULL, '14', 'georges clemenceau', '3');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '47', 'rue de lyon', '7'), (NULL, '112', 'avenue foch', '7');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '58', 'boulevard vallier', '6'), (NULL, '78', 'rue trachard', '7');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '5', 'boulevard jurard', '6'), (NULL, '78', 'boulevard de gaulle', '8');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '11', 'avenue bonaparte', '9'), (NULL, '7', 'rue de paris', '1');
INSERT INTO `Adresses` (`idAdresse`, `numero`, `rue`, `VillesidVilles`) VALUES (NULL, '99', 'route de rivesalte', '9'), (NULL, '14', 'impasse leon ballanger', '9');

INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('193040608833380', 'Pagnol', 'Marcel', '1993-04-20', '0493968228', 'marcelloudu06@hotmail.fr', 'M', '184', '86', 'fracture du genou en faisant du ski', '1');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('178854747412138', 'Pagnol', 'Marcel', '1978-07-10', '0493968333', 'pagnolm@free.fr', 'M', '178', '71', 'infection virale', '2');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('178945687887447', 'Poulet', 'Lucas', '1978-12-09', '0653968228', 'pouletlulu@gmail.com', 'M', '174', '79', 'allergique au gluten', '3');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('289158744895244', 'El Ghazi', 'Safia', '1989-11-01', '0498765435', 'elghazi@hotmail.fr', 'F', '164', '59', 'fracture du metatarse du pied droit', '4');
INSERT INTO `Patients` (`numSS`, `nom`, `prenom`, `dateNaissance`, `telephone`, `mail`, `sexe`, `taille_cm`, `poids_kg`, `commentaires`, `AdressesidAdresse`) VALUES ('289886784555147', 'Lotito', 'Sarah', '1989-08-07', '0493555228', 'sarah.lotito6@hotmail.fr', 'F', '163', '55', 'fragilite aux cervicales', '5');

INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('admin00', 'Marcelin', 'Lionel', '0675732947', 'lionel.marcelin@hopitalbim.fr', 'Informatique', '6');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('lm25610', 'Lotard', 'Marie', '0645467898', 'marie.lotard@hopitalbim.fr', 'Geriatrie', '7');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('ly12454', 'Lio', 'Yang', '0678451228', 'yang.lio@hopitalbim.fr', 'Imagerie', '8');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('cm14743', 'Cohen', 'Michel', '0698946525', 'david.cohen@hopitalbim.fr', 'Geriatrie', '9');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('fd78980', 'Filippi', 'Didier', '0677441215', 'filippi@hopitalbim.fr', 'Pneumologie', '5');

INSERT INTO `ChefServices` (`EmployesCompteUtilisateursidEmploye`, `ServicesnomService`) VALUES ('admin00', 'Informatique');
INSERT INTO `ChefServices` (`EmployesCompteUtilisateursidEmploye`, `ServicesnomService`) VALUES ('lm25610', 'Geriatrie');
INSERT INTO `ChefServices` (`EmployesCompteUtilisateursidEmploye`, `ServicesnomService`) VALUES ('fd78980', 'Pneumologie');

INSERT INTO `Pathologies` ( `nomPathologie`, `indication`) VALUES ( 'Grippe', 'H1N1');
INSERT INTO `Pathologies` (`nomPathologie`, `indication`) VALUES ( 'Bronchite ', 'chronique');
INSERT INTO `Pathologies` (`nomPathologie`, `indication`) VALUES ( 'Myocardiopathie', 'extrinsèque');
INSERT INTO `Pathologies` ( `nomPathologie`, `indication`) VALUES ( 'Adenocarcinome', '');
INSERT INTO `Pathologies` (`nomPathologie`, `indication`) VALUES ( 'Pneumonie', DEFAULT);

INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Transplantation','Cardiologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Coronarographie','Cardiologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Electrocardiogramme','Cardiologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Echocardiographie','Cardiologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Doppler','Cardiologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Angioplastie','Cardiologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Operation','Geriatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Prise de sang','Geriatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Hospitalisation','Geriatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Reeducation','Geriatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Auscultation','Geriatrie'); 
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Hospitalisation', 'Pneumologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Scanner', 'Pneumologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Fibroscopie-bronchique', 'Pneumologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Thoracoscopie', 'Pneumologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Auscultation', 'Pneumologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'IRM', 'Imagerie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'MEG', 'Imagerie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Radiographie','Imagerie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'CT-SCAN','Imagerie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Angiographie','Imagerie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'PET','Imagerie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'SPECT','Imagerie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Scanner','Imagerie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'TMS','psychiatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'ergotherapie','psychiatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'ECT','psychiatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Lobotomie','psychiatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'packing','psychiatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Auscultation','psychiatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'echographie', 'Gynecologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'hysteroscopie', 'Gynecologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'hysteroscopie', 'Gynecologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'echographie', 'Gynecologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'VPH', 'Gynecologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'FCU', 'Gynecologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Auscultation', 'Gynecologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Colposcopie', 'Gynecologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'IVG', 'Gynecologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Auscultation', 'Dermatologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Biopsie', 'Dermatologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Dermabrasion', 'Dermatologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Exerese-cutanee', 'Dermatologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Traitement-Azote-liquide', 'Dermatologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'OCT','Ophtalmologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Chirurgie-cataracte','Ophtalmologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Greffe-cornee','Ophtalmologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Exploration-DMLA','Ophtalmologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Bilan','Ophtalmologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Consultation','Pediatrie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Cystoscopie','Urologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Cystographie-retrograde','Urologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'debimetrie','Urologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'urographie','Urologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'ureteroscopie','Urologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Bilan-urodynamique','Urologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Plombage','Dentisterie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Pose-couronne','Dentisterie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Curetage-peri-radiculaire','Dentisterie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Resection-apicale','Dentisterie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Implant','Dentisterie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Monitorage-hemodynamique','Reanimation');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Endoscopie digestive','Reanimation');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Support-cardio-respiratoire','Reanimation');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Tracheotomie','Reanimation');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Ventilation artificielle','Reanimation');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Transfusion','Reanimation');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Doppler','Phlebologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Echo-Doppler','Phlebologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Capillaroscopie','Phlebologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Drainage lymphatique','Phlebologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Echo-sclerose','Phlebologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Eveinage','Phlebologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Microsclerose','Phlebologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Pressotherapie','Phlebologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Phlebectomie','Phlebologie');
INSERT INTO `Interventions` (`idIntervention`, `acte`, `ServicesnomService`) VALUES (NULL, 'Consultation','Endocrinologie');










# Pour les floats, il faut mettre des '.' et non pas des ','
INSERT INTO `Tarifications` (`InterventionsidIntervention`, `tarif_euros`) VALUES ('1', '76'), 
('2', '134.4'), ('3', '132.0'), ('4', '15'), ('5', '78.9'), ('6', '208.7'), ('7', '21'), ('8', '43'), ('9', '40.3'), ('10', '40'), ('11', '53'), ('12', '200'), ('13', '58.8'), ('14', '50'), ('15', '59.9'), ('16', '37'), ('17', '57'), ('18', '38'), ('19', '96'), ('20', '25.5525'), ('21', '31.2'), ('22', '89'), ('23', '81'), ('24', '19.9'), ('25', '37'), ('26', '32'), ('27', '42'), ('28', '49'),('29', '71'),
('30', '43.4'), ('31', '74'), ('32', '80'), ('33', '96.3'), ('34', '45.9'), ('35', '76'), ('36', '454'), ('37', '74'), ('38', '43.8'), ('39', '48.34'), ('40', '65'), ('41', '21'), ('42', '28'), ('43', '23.36'), ('44', '27.22'), ('45', '62'), ('46', '23'), ('47', '27'), ('48', '39'),('49', '39.45'),
('50', '134'), ('51', '414'), ('52', '71.9'), ('53', '31'), ('54', '69.8'), ('55', '14'), ('56', '534'), ('57', '79'), ('58', '81.74'), ('59', '57'), ('60', '102'), ('61', '31'), ('62', '48'), ('63', '48.4'), ('64', '93'), ('65', '18.8'), ('66', '28.8'), ('67', '28'), ('68', '19.7'),('69', '37.88'),
('70', '96.2'), ('71', '98.7'), ('72', '41'), ('73', '29.9'), ('74', '32'), ('75', '24'), ('76', '17.8'), ('77', '32.9');

INSERT INTO `InterventionsPatho` (`PathologiesidPatho`, `InterventionsidIntervention`, `niveauUrgenceMax`, `niveauUrgenceMin`) VALUES ('1', '3', NULL, NULL), 
('2', '4', NULL, NULL), 
('3', '1', NULL, NULL), 
('4', '3', NULL, NULL), 
('5', '3', NULL, NULL);

INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-02', '15:00:00', '4', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-02', '15:30:00', '14', '0', 'r', '4', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-02', '15:45:00', '24', '0', 'r', '3', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-03', '15:30:00', '41', '0', 'r', '2', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-04', '15:00:00', '40', '0', 'r', '1', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-04', '15:30:00', '46', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
