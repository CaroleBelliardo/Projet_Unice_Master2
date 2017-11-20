CREATE TABLE CompteUtilisateurs (
  idEmploye varchar(10) NOT NULL, 
  passwd    varchar(255) NOT NULL, 
  PRIMARY KEY (idEmploye));

CREATE TABLE Departements (
  departement varchar(3) NOT NULL, 
  pays  varchar(25) NOT NULL, 
  PRIMARY KEY (departement));

CREATE TABLE Villes (
  ville varchar(500) NOT NULL, 
  Departementsdepartement  varchar(3) NOT NULL, 
  codepostal  int(5) NOT NULL, 
  PRIMARY KEY (ville),
  FOREIGN KEY (`Departementsdepartement`) REFERENCES `Departements`(`departement`));

CREATE TABLE Adresses (
  idAdresse             varchar(10) NOT NULL, 
  numero                char(6), 
  rue                   varchar(50) NOT NULL, 
  CodePostauxcodepostal int(5) NOT NULL, 
  PRIMARY KEY (idAdresse));
CREATE TABLE LocalisationServices (
  idLocalisation varchar(10) NOT NULL, 
  batiment       varchar(15), 
  aile           varchar(10), 
  etage          char(2), 
  porte          varchar(10));
CREATE TABLE Services (
  nomService                  varchar(20) NOT NULL, 
  telephone                   varchar(15), 
  mail                        varchar(100), 
  LocalisationsidLocalisation varchar(10) NOT NULL, 
  PRIMARY KEY (nomService));
CREATE TABLE Employes (
  CompteUtilisateursidEmploye varchar(10) NOT NULL, 
  nom                         varchar(35) NOT NULL, 
  prenom                      varchar(25) NOT NULL, 
  telephone                   varchar(15), 
  mail                        varchar(100), 
  ServicesnomService          varchar(20) NOT NULL, 
  AdressesidAdresse           varchar(10) NOT NULL, 
  PRIMARY KEY (CompteUtilisateursidEmploye),
  FOREIGN KEY (`CompteUtilisateursidEmploye`) REFERENCES `CompteUtilisateurs`(`idEmploye`),
  INDEX ( ServicesnomService, AdressesidAdresse),
  FOREIGN KEY (`ServicesnomService`) REFERENCES `Services`(`nomService`),
  FOREIGN KEY (`AdressesidAdresse`) REFERENCES `Adresses`(`idAdresse`));
CREATE TABLE Patients (
  numSS             char(15) NOT NULL, 
  nom               varchar(25) NOT NULL, 
  prenom            varchar(25) NOT NULL, 
  dateNaissance     date NOT NULL, 
  telephone         varchar(15), 
  mail              varchar(30), 
  sexe              char(1) NOT NULL, 
  taille            int(3), 
  poids             int(3), 
  commentaires      varchar(1000), 
  AdressesidAdresse varchar(10) NOT NULL, 
  PRIMARY KEY (numSS),
  INDEX (AdressesidAdresse),
  FOREIGN KEY (`AdressesidAdresse`) REFERENCES `Adresses`(`idAdresse`));
CREATE TABLE Pathologies (
  idPatho       varchar(100) NOT NULL, 
  nomPathologie varchar(100) NOT NULL, 
  origine       varchar(35), 
  souche        int(10), 
  developpement varchar(10), 
  transmission  varchar(35), 
  précautions   varchar(500), 
  PRIMARY KEY (idPatho));
CREATE TABLE Interventions (
  idIntervention                      varchar(15) NOT NULL, 
  acte                                varchar(15) NOT NULL, 
  ServicesnomService                  varchar(20) NOT NULL, 
  PRIMARY KEY (idIntervention),
  INDEX (ServicesnomService), 
  FOREIGN KEY (`ServicesnomService`) REFERENCES `Services`(`nomService`));
CREATE TABLE Tarifications (
  InterventionsidIntervention varchar(15) NOT NULL, 
  tarif                       int(10),
  PRIMARY KEY (InterventionsidIntervention),
  INDEX (InterventionsidIntervention),
  FOREIGN KEY (`InterventionsidIntervention`) REFERENCES `Interventions`(`idIntervention`));
CREATE TABLE InterventionPatho (
  PathologiesidPatho          varchar(100) NOT NULL, 
  InterventionsidIntervention varchar(15) NOT NULL, 
  niveauUrgenceMax            int(1), 
  niveauUrgenceMin            int(1), 
  PRIMARY KEY (PathologiesidPatho, 
  InterventionsidIntervention),
  INDEX ( InterventionsidIntervention, PathologiesidPatho),
  FOREIGN KEY (`InterventionsidIntervention`) REFERENCES `Interventions`(`idIntervention`),
  FOREIGN KEY (`PathologiesidPatho`) REFERENCES `Pathologies`(`idPatho`));
CREATE TABLE CreneauxInterventions (
  dateRdv                              DATE NOT NULL, 
  heure                                TIME NOT NULL, 
  InterventionsidIntervention         varchar(15) NOT NULL, 
  niveauUrgence                       char(1), 
  statut                              varchar(10) NOT NULL, 
  pathologie                          int(10), 
  commentaires                        varchar(1000), 
  PatientnumSS                        char(15) NOT NULL, 
  EmployesCompteUtilisateursidEmploye varchar(10) NOT NULL, 
  PRIMARY KEY (dateRDV, InterventionsidIntervention,
  heure),
  FOREIGN KEY (`InterventionsidIntervention`) REFERENCES `Interventions`(`idIntervention`),
  FOREIGN KEY (`PatientnumSS`) REFERENCES `Patients`(`numSS`),
  FOREIGN KEY (`EmployesCompteUtilisateursidEmploye`) REFERENCES `Employes`(`CompteUtilisateursidEmploye`));
CREATE TABLE ChefServices (
  EmployesCompteUtilisateursidEmploye varchar(10) NOT NULL, 
  ServicesnomService                  varchar(20) NOT NULL);







ALTER TABLE CreneauxInterventions ADD INDEX occupe (PatientnumSS), ADD CONSTRAINT occupe FOREIGN KEY (PatientnumSS) REFERENCES Patients (numSS);
ALTER TABLE Interventions ADD INDEX propose (ServicesnomService), ADD CONSTRAINT propose FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService);
ALTER TABLE CreneauxInterventions ADD INDEX correspond (InterventionsidIntervention), ADD CONSTRAINT correspond FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention);
ALTER TABLE Patients ADD INDEX reside (AdressesidAdresse), ADD CONSTRAINT reside FOREIGN KEY (AdressesidAdresse) REFERENCES Adresses (idAdresse);
ALTER TABLE Employes ADD INDEX habite (AdressesidAdresse), ADD CONSTRAINT habite FOREIGN KEY (AdressesidAdresse) REFERENCES Adresses (idAdresse);
ALTER TABLE ChefServices ADD INDEX gere (ServicesnomService), ADD CONSTRAINT gere FOREIGN KEY (ServicesnomService) REFERENCES Services (nomService);
ALTER TABLE CreneauxInterventions ADD INDEX reserve (EmployesCompteUtilisateursidEmploye), ADD CONSTRAINT reserve FOREIGN KEY (EmployesCompteUtilisateursidEmploye) REFERENCES Employes (CompteUtilisateursidEmploye);
ALTER TABLE Tarifications ADD INDEX associé (InterventionsidIntervention), ADD CONSTRAINT associé FOREIGN KEY (InterventionsidIntervention) REFERENCES Interventions (idIntervention);


