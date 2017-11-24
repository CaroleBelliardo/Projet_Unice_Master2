# Date finale de création : 22-nov-2017 
# Type de serveur : MariaDB 
# Version du serveur : 10.2.21-MariaDB-Source distribution 
# Version du protocole : 10 
# Utilisateur : root@localhost 
# Version du client de base de données : libmysql - mysqlnd 5.0.12-dev - 20150407 - $Id: b396954eeb2d1d9ed7902b8bae237b287f21ad9e $ 
# Version de PHP : 7.1.1  
# Version de phpMyAdmin : 4.6.5.2, dernière version stable : 4.7.5 
# 
# 
# 
# Table départements 
CREATE TABLE Departements (  
  departement varchar(3) NOT NULL,  # Contraintes majuscule pour les lettres -> 06 / corse : 2A / 974 
  pays        varchar(25) NOT NULL, # France 
  PRIMARY KEY (departement, pays));  

# Table Codes postaux  
CREATE TABLE CodesPostaux ( 
  villes                 varchar(150) NOT NULL, # Nice // 1ère lettre maj 
  Departementsdepartement varchar(3),           # 06   
  codepostal             char(5),               # 06000 
  Departementspays       varchar(25),           # France // 1ère lettre maj 
  PRIMARY KEY (villes), 
  FOREIGN KEY (Departementsdepartement, Departementspays) REFERENCES Departements (departement, pays) ON DELETE SET NULL ON UPDATE CASCADE ); 
 
# Table Adresse (pour les patients, les employés et l'hopital - 1ere valeur -)  
CREATE TABLE Adresses (  
  idAdresse            int(8) NOT NULL AUTO_INCREMENT, # 1 
  numero               varchar(6),                       
  rue                  varchar(100) NOT NULL,          # avenue thiers // tout en minuscule  
  CodesPostauxvilles    varchar(150),                  # Nice 
  PRIMARY KEY (idAdresse), 
  FOREIGN KEY (CodesPostauxvilles) REFERENCES CodesPostaux (villes) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (CodesPostauxvilles));  
 
# Table regroupant les informations relatives aux patients de l'hopital  
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
  AdressesidAdresse int(8),  # Numéro de l'adresse du patient  
  PRIMARY KEY (numSS),  
  FOREIGN KEY (AdressesidAdresse) REFERENCES Adresses (idAdresse) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (nom, prenom)); 
   
# Table regroupant les utilisateurs du site : employés de l'hopital 
CREATE TABLE CompteUtilisateurs (  
  idEmploye char(7) NOT NULL,  # np00000 # nom, prénom, chiffres  // admin : admin00 
  passwd    varchar(255) NOT NULL,  
  PRIMARY KEY (idEmploye)); 
   
# Table indiquant la localisation de chaque service de l'hopital (= bureau d'accueil du service) 
CREATE TABLE LocalisationServices (   
  idLocalisation int(8) NOT NULL AUTO_INCREMENT, # 1
  batiment       varchar(15) NOT NULL, # Majuscule 
  aile           varchar(10),          # Minuscule 
  etage          varchar(2),           # si c'est en sous-sol "-1" accepté.   
  PRIMARY KEY (idLocalisation)); 
 
# Table regroupant tous les services présents dans l'hôpital 
CREATE TABLE Services (   
  nomService                  varchar(20) NOT NULL, # Imagerie // Maj en 1ère lettre 
  telephone                   varchar(15),  
  mail                        varchar(60),  
  horaire_ouverture           char(5), # format 08:00
  horaire_fermeture           char(5), # format 18:00
  LocalisationServicesidLocalisation int(8),   # cf idLocalisation : 1
  PRIMARY KEY (nomService), 
  FOREIGN KEY (LocalisationServicesidLocalisation) REFERENCES LocalisationServices (idLocalisation) ON DELETE SET NULL ON UPDATE CASCADE ); 
  
# Table regroupant tous les employés travaillant au sein de l'hôpital 
CREATE TABLE Employes ( 
  CompteUtilisateursidEmploye varchar(10) NOT NULL, # cf idEmployé 
  nom                         varchar(25) NOT NULL, # Maj au début 
  prenom                      varchar(25) NOT NULL, # Maj au début 
  telephone                   varchar(15),  
  mail                        varchar(60),   
  ServicesnomService          varchar(20), # cf. NomService 
  AdressesidAdresse           int(8),       # cf. idAdresse 
  PRIMARY KEY (CompteUtilisateursidEmploye),  
  FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (AdressesidAdresse) REFERENCES Adresses (idAdresse) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (ServicesnomService), 
  INDEX (nom)); 
 
# Table indiquant les responsables de chaque service   
CREATE TABLE ChefServices (   
  EmployesCompteUtilisateursidEmploye varchar(10) NOT NULL,   # cf. idEmployé 
  ServicesnomService                  varchar(20) NOT NULL,   # cf. nomService 
  PRIMARY KEY (EmployesCompteUtilisateursidEmploye,ServicesnomService), 
  FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService) ON DELETE CASCADE ON UPDATE CASCADE, 
  FOREIGN KEY (EmployesCompteUtilisateursidEmploye) REFERENCES Employes (CompteUtilisateursidEmploye) ON DELETE CASCADE ON UPDATE CASCADE); 
 
# Table regroupant toutes les pathologies  
CREATE TABLE Pathologies (  
  idPatho       int(8) NOT NULL AUTO_INCREMENT, # 1 
  nomPathologie varchar(100) NOT NULL,          # 1ere lettre Maj : Fracture 
  indication     varchar(30) NOT NULL DEFAULT 'standard', # en un mot : coude 
  precautions   text,  
  PRIMARY KEY (idPatho),  
  INDEX (nomPathologie)); 
 
# Table regroupant toutes les interventions  
CREATE TABLE Interventions (   
  idIntervention                       int(8) NOT NULL AUTO_INCREMENT,  
  acte                                 varchar(15) NOT NULL, # 1ere lettre Maj : Radio 
  indication                            varchar(30) NOT NULL DEFAULT 'standard',  # coude 
  ServicesnomService                   varchar(20),          # cf. nomService 
  PRIMARY KEY (idIntervention), 
  FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (ServicesnomService)); 
 
# Table regroupant les tarifs liés à chaque intervention 
CREATE TABLE Tarifications (  
  InterventionsidIntervention int(8) NOT NULL, # cf.idInterventions auto_increment 
  tarif_euros                 float UNSIGNED,       # 30.02 // pas de virgule mais des "." 
  PRIMARY KEY (InterventionsidIntervention), 
  FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention) ON DELETE CASCADE ON UPDATE CASCADE, 
  INDEX (InterventionsidIntervention)); 
 
# Table regroupant les créneaux pour chaque intervention  
CREATE TABLE CreneauxInterventions ( 
  date_rdv                            date NOT NULL, # 2017-11-02 format sql 
  heure_rdv                           time NOT NULL, # 15:00:00 format 24h 
  InterventionsidIntervention         int(8),   # cf.idInterventions auto_increment 
  niveauUrgence                       tinyint(1) UNSIGNED NOT NULL,   
  statut                              char(1) NOT NULL DEFAULT 'p',  # p = prévue / r = réalisée / a = annulée 
  pathologie                          varchar(100),  
  commentaires                        text,  
  VerifCoherencePathoUrgences         varchar(100), # OUI = cohérence entre niveau urgence et pathologie / NON = incohérence / INCONNUE = maladie non présente dans la table pathologies 
  PatientsnumSS                        char(15),  
  EmployesCompteUtilisateursidEmploye varchar(10), # cf. idEmploye np00000 
  PRIMARY KEY (date_rdv, heure_rdv),   
  FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (PatientsnumSS) REFERENCES Patients (numSS) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (EmployesCompteUtilisateursidEmploye) REFERENCES Employes (CompteUtilisateursidEmploye) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (EmployesCompteUtilisateursidEmploye), 
  INDEX (pathologie)); 
 
# Lie les tables Pathologies et Interventions  
CREATE TABLE InterventionsPatho ( 
  PathologiesidPatho          int(8) NOT NULL,  
  InterventionsidIntervention int(8) NOT NULL,  
  niveauUrgenceMax            tinyint(1),     # on ne rentre rien  
  niveauUrgenceMin            tinyint(1) DEFAULT '0',     #  
  PRIMARY KEY (PathologiesidPatho, InterventionsidIntervention), 
  FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention) ON DELETE CASCADE ON UPDATE CASCADE, 
  FOREIGN KEY (PathologiesidPatho) REFERENCES Pathologies (idPatho) ON DELETE CASCADE ON UPDATE CASCADE ); 
 
# Premier insert dans la table du futur admin de la base de données 
INSERT INTO `CompteUtilisateurs` (`idEmploye`, `passwd`) VALUES ('Admin', '$2y$10$aXQcmkz0JlTy3kX/gOFb3u.K5VhyaPs5V5kxMGxu1np2mFuw//SfC') ; 