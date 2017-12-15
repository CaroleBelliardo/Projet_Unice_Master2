# Date finale de création : 24-nov-2017 
# Type de serveur : MariaDB 
# Version du serveur : 10.2.21-MariaDB-Source distribution 
# Version du protocole : 10 
# Utilisateur : root@localhost 
# Version du client de base de données : libmysql - mysqlnd 5.0.12-dev - 20150407 - $Id: b396954eeb2d1d9ed7902b8bae237b287f21ad9e $ 
# Version de PHP : 7.1.1  
# Version de phpMyAdmin : 4.6.5.2, dernière version stable : 4.7.5 
# 

# Creation de la base de données
CREATE DATABASE bdd;
USE bdd;

# Table Villes
CREATE TABLE Villes ( 
  idVilles               int(8) NOT NULL AUTO_INCREMENT, #1
  codepostal             varchar(20),           # 06000
  nomVilles              varchar(150) NOT NULL, # Nice // 1ère lettre maj 
  departement            varchar(3),            # 06    
  pays                    varchar(25),          # France // 1ère lettre maj 
  PRIMARY KEY (idVilles)); 
  
# Table Adresse (pour les patients, les employés et l hopital - 1ere valeur -)  
CREATE TABLE Adresses (  
  idAdresse            int(8) NOT NULL AUTO_INCREMENT, # 1 
  numero               varchar(6),                       
  rue                  varchar(100) NOT NULL,          # avenue thiers // tout en minuscule  
  VillesidVilles       int(8),                  # Nice 
  PRIMARY KEY (idAdresse), 
  FOREIGN KEY (VillesidVilles) REFERENCES Villes (idVilles) ON DELETE SET NULL ON UPDATE CASCADE);  
 
# Table regroupant les informations relatives aux patients de l hopital  
CREATE TABLE Patients (  
  numSS             char(15) NOT NULL,  # Numéro sécurité sociale  
  nom               varchar(25) NOT NULL, # 1ère lettre Maj 
  prenom            varchar(25) NOT NULL, # 1ère lettre Maj 
  dateNaissance     date NOT NULL,  
  telephone         varchar(15),  
  mail              varchar(60),   
  sexe              char(1) NOT NULL,  # F / M 
  taille_cm         int(3),  
  poids_kg          int(3),  
  commentaires      text,  
  AdressesidAdresse int(8),  # Numéro de l adresse du patient  
  PRIMARY KEY (numSS),  
  FOREIGN KEY (AdressesidAdresse) REFERENCES Adresses (idAdresse) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (nom, prenom)); 
   
# Table regroupant les utilisateurs du site : employés de l hopital 
CREATE TABLE CompteUtilisateurs (  
  idEmploye char(7) NOT NULL,  # np00000 # nom, prénom, chiffres  // admin : admin00 
  passwd    varchar(255) NOT NULL,  
  PRIMARY KEY (idEmploye)); 
   
# Table indiquant la localisation de chaque service de l hopital (= bureau d accueil du service) 
CREATE TABLE LocalisationServices (   
  idLocalisation int(8) NOT NULL UNIQUE AUTO_INCREMENT, # 1
  batiment       varchar(15) NOT NULL, # Majuscule 
  aile           varchar(10),          # Minuscule 
  etage          varchar(2),           # si c est en sous-sol "-1" accepté.   
  PRIMARY KEY (idLocalisation)); 
 
# Table regroupant tous les services présents dans l hôpital 
CREATE TABLE Services (   
  nomService                  varchar(20) NOT NULL, # Imagerie // Maj en 1ère lettre 
  telephone                   varchar(15),  
  mail                        varchar(60),  
  horaire_ouverture           char(5), # format 08:00
  horaire_fermeture           char(5), # format 18:00
  LocalisationServicesidLocalisation int(8),   # cf idLocalisation : 1
  PRIMARY KEY (nomService), 
  FOREIGN KEY (LocalisationServicesidLocalisation) REFERENCES LocalisationServices (idLocalisation) ON DELETE SET NULL ON UPDATE CASCADE ); 
  
# Table regroupant tous les employés travaillant au sein de l hopital 
CREATE TABLE Employes ( 
  idEmploye int(8) NOT NULL AUTO_INCREMENT, # 1
  CompteUtilisateursidEmploye char(7) , # cf idEmployé 
  nom                         varchar(25) NOT NULL, # Maj au début 
  prenom                      varchar(25) NOT NULL, # Maj au début 
  telephone                   varchar(15),  
  mail                        varchar(60),   
  ServicesnomService          varchar(20), # cf. NomService 
  AdressesidAdresse           int(8),       # cf. idAdresse 
  PRIMARY KEY (idEmploye),  
  FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (AdressesidAdresse) REFERENCES Adresses (idAdresse) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (CompteUtilisateursidEmploye) REFERENCES CompteUtilisateurs (idEmploye) ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX (nom)); 
 
# Table indiquant les responsables de chaque service   
CREATE TABLE ChefServices (   
  EmployesCompteUtilisateursidEmploye char(7) NOT NULL,   # cf. idEmployé 
  ServicesnomService                  varchar(20) NOT NULL,   # cf. nomService 
  PRIMARY KEY (EmployesCompteUtilisateursidEmploye,ServicesnomService), 
  FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService) ON DELETE CASCADE ON UPDATE CASCADE, 
  FOREIGN KEY (EmployesCompteUtilisateursidEmploye) REFERENCES Employes (CompteUtilisateursidEmploye) ON DELETE CASCADE ON UPDATE CASCADE); 
 
# Table regroupant toutes les pathologies  
CREATE TABLE Pathologies (  
  idPatho       int(8) NOT NULL AUTO_INCREMENT, # 1 
  nomPathologie varchar(100) NOT NULL,          # 1ere lettre Maj : Fracture 
  indication     varchar(30) NOT NULL DEFAULT 'standard', # en un mot : coude 
  PRIMARY KEY (idPatho),  
  INDEX (nomPathologie)); 
 
# Table regroupant toutes les interventions  
CREATE TABLE Interventions (   
  idIntervention                       int(8) NOT NULL AUTO_INCREMENT,  
  acte                                 varchar(35) NOT NULL, # 1ere lettre Maj : Radio 
  ServicesnomService                   varchar(20),          # cf. nomService 
  PRIMARY KEY (idIntervention), 
  FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (ServicesnomService)); 
 
# Table regroupant les tarifs liés à chaque intervention 
CREATE TABLE Tarifications (  
  InterventionsidIntervention int(8) NOT NULL,      # cf.idInterventions auto_increment 
  tarif_euros                 float UNSIGNED,       # 30.02 // pas de virgule mais des "." 
  PRIMARY KEY (InterventionsidIntervention), 
  FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention) ON DELETE CASCADE ON UPDATE CASCADE); 
 
# Table regroupant les tarifs liés à chaque intervention 
CREATE TABLE TarificationsArchive (  
  InterventionsidIntervention int(8) NOT NULL,      # cf.idInterventions auto_increment 
  tarif_euros                 float UNSIGNED,       # 30.02 // pas de virgule mais des "." 
  PRIMARY KEY (InterventionsidIntervention), 
  FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention) ON DELETE CASCADE ON UPDATE CASCADE); 

# Table regroupant les créneaux pour chaque intervention  
CREATE TABLE CreneauxInterventions (
  id_rdv	int(8) NOT NULL AUTO_INCREMENT, 
  date_rdv                            date NOT NULL, # 2017-11-02 format sql 
  heure_rdv                           time NOT NULL, # 15:00:00 format 24h 
  InterventionsidIntervention         int(8) ,   # cf.idInterventions auto_increment 
  niveauUrgence                       tinyint(1) UNSIGNED NOT NULL,   
  statut                              char(1) NOT NULL DEFAULT 'p',  # p = prévue / r = réalisée / a = annulée / f = payé 
  PathologiesidPatho                  int(8) ,  
  commentaires                        text,  
  PatientsnumSS                       char(15),  
  EmployesCompteUtilisateursidEmploye char(7) , # cf. idEmploye np00000 
  PRIMARY KEY (id_rdv),   
  FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (PatientsnumSS) REFERENCES Patients (numSS) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (EmployesCompteUtilisateursidEmploye) REFERENCES Employes (CompteUtilisateursidEmploye) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (PathologiesidPatho) REFERENCES Pathologies (idPatho) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (PatientsnumSS), 
  INDEX (InterventionsidIntervention),
  INDEX (EmployesCompteUtilisateursidEmploye)); 
 
# Lie les tables Pathologies et Interventions  
CREATE TABLE InterventionsPatho (
  PathologiesidPatho       int(8) NOT NULL, # 1  
  InterventionsidIntervention int(8) NOT NULL,  
  niveauUrgenceMax            tinyint(1) DEFAULT 0,    # on ne rentre rien  
  niveauUrgenceMin            tinyint(1) DEFAULT 0,     #  
  FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention) ON DELETE CASCADE ON UPDATE CASCADE, 
  FOREIGN KEY (PathologiesidPatho) REFERENCES Pathologies (idPatho) ON DELETE CASCADE ON UPDATE CASCADE ); 

# Lie les tables Pathologies et Interventions  
CREATE TABLE Facturation ( 
  idFacture          int(8) NOT NULL ,  
  CreneauxInterventionsidRdv int(8) NOT NULL,  
  FOREIGN KEY (CreneauxInterventionsidRdv) REFERENCES CreneauxInterventions (id_rdv) ON DELETE CASCADE ON UPDATE CASCADE);

# Lie les tables Pathologies et Interventions  
CREATE TABLE Notifications ( 
  idNotification int(8) NOT NULL AUTO_INCREMENT,
  CreneauxInterventionsidRdv int(8) NOT NULL, 
  ServicesnomService                  varchar(20) NOT NULL, # Imagerie // Maj en 1ère lettre  
  indication                  varchar(10) , 
  PRIMARY KEY (idNotification), 
  FOREIGN KEY (CreneauxInterventionsidRdv) REFERENCES CreneauxInterventions (id_rdv) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService) ON DELETE CASCADE ON UPDATE CASCADE);



    
# Table qui contient les services archivés par l'administrateur s'ils n'existent plus à l'hopital
CREATE TABLE ServicesArchive (   
  nomService                  varchar(20) NOT NULL, # Imagerie // Maj en 1ère lettre 
  telephone                   varchar(15),  
  mail                        varchar(60),  
  horaire_ouverture           char(5), # format 08:00
  horaire_fermeture           char(5), # format 18:00
  LocalisationServicesidLocalisation int(8),   # cf idLocalisation : 1
  PRIMARY KEY (nomService), 
  FOREIGN KEY (LocalisationServicesidLocalisation) REFERENCES LocalisationServices (idLocalisation) ON DELETE SET NULL ON UPDATE CASCADE ); 
  
#  Table qui contient les employés qui ne sont plus membre de l'hopital, dont les infos sont archivées par l'administrateur
CREATE TABLE EmployesArchive ( 
  idEmploye int(8) NOT NULL AUTO_INCREMENT, # 1
  CompteUtilisateursidEmploye char(7) , # cf idEmployé 
  nom                         varchar(25) NOT NULL, # Maj au début 
  prenom                      varchar(25) NOT NULL, # Maj au début 
  telephone                   varchar(15),  
  mail                        varchar(60),   
  ServicesnomService          varchar(20), # cf. NomService 
  AdressesidAdresse           int(8),       # cf. idAdresse 
  PRIMARY KEY (idEmploye),  
  FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (AdressesidAdresse) REFERENCES Adresses (idAdresse) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (CompteUtilisateursidEmploye) REFERENCES CompteUtilisateurs (idEmploye) ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX (nom)); 
  
#  Table qui contient les actes médicaux archivés par l'administrateur s'ils ne sont plus réalisés
CREATE TABLE InterventionsArchive (   
  idIntervention                       int(8) NOT NULL AUTO_INCREMENT,  
  acte                                 varchar(15) NOT NULL, # 1ere lettre Maj : Radio 
  ServicesnomService                   varchar(20),          # cf. nomService 
  PRIMARY KEY (idIntervention), 
  FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (ServicesnomService)); 

  
  
  # Ajout des inserts 
  
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

INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('admin00', 'Marcelin', 'Lionel', '0675732947', 'admin00@hopitalbim.fr', 'Informatique', '6');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('LM25610', 'Lotard', 'Marie', '0645467898', 'LM25610@hopitalbim.fr', 'Geriatrie', '7');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('LY12454', 'Lio', 'Yang', '0678451228', 'LY12454@hopitalbim.fr', 'Imagerie', '8');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('CM14743', 'Cohen', 'Michel', '0698946525', 'CM14743@hopitalbim.fr', 'Geriatrie', '9');
INSERT INTO `Employes` (`CompteUtilisateursidEmploye`, `nom`, `prenom`, `telephone`, `mail`, `ServicesnomService`, `AdressesidAdresse`) VALUES ('FD78980', 'Filippi', 'Didier', '0677441215', 'FD78980@hopitalbim.fr', 'Pneumologie', '5');

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
('2', '4', '0', '3'), 
('3', '1', '0', '3'), 
('4', '3', '0', '3'), 
('5', '3', '0', '3');

INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-02', '15:00:00', '4', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-02', '15:30:00', '14', '0', 'p', '4', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-02', '15:45:00', '24', '0', 'p', '3', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-03', '15:30:00', '41', '0', 'p', '2', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-04', '15:00:00', '40', '0', 'p', '1', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-04', '15:30:00', '46', '0', 'p', '5', NULL, '178854747412138', 'fd78980');

INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '8:00:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '8:15:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '8:30:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '8:45:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');

INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '9:00:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '9:15:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '9:30:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '9:45:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');

INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '10:00:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '10:15:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '10:30:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '10:45:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');

INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '11:00:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '11:15:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '11:30:00', '14', '0', 'r', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '11:45:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '12:00:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '12:15:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '12:30:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '12:45:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');

INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '13:15:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '13:30:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '13:45:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');


INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '14:00:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');

INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '14:15:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '14:30:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '14:45:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');

INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '15:00:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '15:15:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '15:30:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '15:45:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '16:00:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '16:15:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '16:30:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '16:45:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');

INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '17:00:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '17:15:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
INSERT INTO `CreneauxInterventions` (`id_rdv`, `date_rdv`, `heure_rdv`, `InterventionsidIntervention`, `niveauUrgence`, `statut`, `PathologiesidPatho`, `commentaires`, `PatientsnumSS`, `EmployesCompteUtilisateursidEmploye`) VALUES (NULL, '2017-12-11', '17:30:00', '14', '0', 'p', '5', NULL, '178854747412138', 'fd78980');
