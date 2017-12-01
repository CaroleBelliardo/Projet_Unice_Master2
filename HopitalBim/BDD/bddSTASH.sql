# Date finale de création : 24-nov-2017 
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
  acte                                 varchar(15) NOT NULL, # 1ere lettre Maj : Radio 
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
 
# Table regroupant les créneaux pour chaque intervention  
CREATE TABLE CreneauxInterventions ( 
  id_rdv	int(8) NOT NULL AUTO_INCREMENT, 
  date_rdv                            date NOT NULL, # 2017-11-02 format sql 
  heure_rdv                           time NOT NULL, # 15:00:00 format 24h 
  InterventionsidIntervention         int(8),   # cf.idInterventions auto_increment 
  niveauUrgence                       tinyint(1) UNSIGNED NOT NULL,   
  statut                              char(1) NOT NULL DEFAULT 'p',  # p = prévue / r = réalisée / a = annulée / f = payé 
  PathologiesidPatho                          int(8) ,  
  commentaires                     text,  
  PatientsnumSS                       char(15),  
  EmployesCompteUtilisateursidEmploye char(7), # cf. idEmploye np00000 
  PRIMARY KEY (id_rdv),   
  FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (PatientsnumSS) REFERENCES Patients (numSS) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (EmployesCompteUtilisateursidEmploye) REFERENCES Employes (CompteUtilisateursidEmploye) ON DELETE SET NULL ON UPDATE CASCADE, 
  FOREIGN KEY (PathologiesidPatho) REFERENCES Pathologies (idPatho) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (PatientsnumSS), 
  INDEX (InterventionsidIntervention)); 
 
# Lie les tables Pathologies et Interventions  
CREATE TABLE InterventionsPatho ( 
  PathologiesidPatho          int(8) NOT NULL,  
  InterventionsidIntervention int(8) NOT NULL,  
  niveauUrgenceMax            tinyint(1),     # on ne rentre rien  
  niveauUrgenceMin            tinyint(1) DEFAULT '0',     #  
  PRIMARY KEY (PathologiesidPatho, InterventionsidIntervention), 
  FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention) ON DELETE CASCADE ON UPDATE CASCADE, 
  FOREIGN KEY (PathologiesidPatho) REFERENCES Pathologies (idPatho) ON DELETE CASCADE ON UPDATE CASCADE ); 

# Lie les tables Pathologies et Interventions  
CREATE TABLE Facturation ( 
  idFacture          int(8) NOT NULL ,  
  CreneauxInterventionsidRdv int(8) NOT NULL,  
  FOREIGN KEY (CreneauxInterventionsidRdv) REFERENCES CreneauxInterventions (id_rdv) ON DELETE CASCADE ON UPDATE CASCADE);

# Lie les tables Pathologies et Interventions  
CREATE TABLE Notifications ( 
  CreneauxInterventionsidRdv int(8) NOT NULL,  
  PRIMARY KEY (CreneauxInterventionsidRdv), 
  FOREIGN KEY (CreneauxInterventionsidRdv) REFERENCES CreneauxInterventions (id_rdv) ON DELETE CASCADE ON UPDATE CASCADE);

# Table regroupant les informations relatives aux patients de l hopital  
CREATE TABLE PatientsArchive (  
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
    
# Table regroupant tous les services présents dans l hôpital 
CREATE TABLE ServicesArchive (   
  nomService                  varchar(20) NOT NULL, # Imagerie // Maj en 1ère lettre 
  telephone                   varchar(15),  
  mail                        varchar(60),  
  horaire_ouverture           char(5), # format 08:00
  horaire_fermeture           char(5), # format 18:00
  LocalisationServicesidLocalisation int(8),   # cf idLocalisation : 1
  PRIMARY KEY (nomService), 
  FOREIGN KEY (LocalisationServicesidLocalisation) REFERENCES LocalisationServices (idLocalisation) ON DELETE SET NULL ON UPDATE CASCADE ); 
  
# Table regroupant tous les employés travaillant au sein de l hopital 
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
  
# Table regroupant toutes les interventions  
CREATE TABLE InterventionsArchive (   
  idIntervention                       int(8) NOT NULL AUTO_INCREMENT,  
  acte                                 varchar(15) NOT NULL, # 1ere lettre Maj : Radio 
  ServicesnomService                   varchar(20),          # cf. nomService 
  PRIMARY KEY (idIntervention), 
  FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService) ON DELETE SET NULL ON UPDATE CASCADE, 
  INDEX (ServicesnomService)); 
