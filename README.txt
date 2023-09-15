README du projet HOPITAL BIM
* * * Gestion des échanges d’interventions médicales au sein d’un hôpital * * *

-- Résumé –-
	Vous trouverez ci-joint l’ensemble des informations nécessaires pour la bonne mise en place du site web. La plateforme gère les échanges de demandes d’interventions au sein d’un hôpital. 
Pour une description complète, veuillez-vous rediriger vers : https://github.com/CaroleBelliardo/projetm2
Pour soumettre un bug ou suivre l’évolution de la plateforme : https://github.com/CaroleBelliardo/projetm2/issues

-- Requis –-

  1 - Un système d’exploitation Windows, Mac ou Linux. Cependant Ubuntu ou Mageia sont recommandés.

  2 - Un navigateur web, la dernière version fonctionnelle de Chrome, Safari ou Mozilla Firefox. Cependant Mozilla Firefox est recommandé. 

  3 - Il est nécessaire d’avoir un environnement de développement PHP. La distribution Apache, XAMPP est recommandé. Les paquets Php et MySQL doivent être activés et fonctionnels. 
 
  4 - Il est nécessaire de télécharger tous les fichiers qui composent la plateforme web.

  5 - Il faut réaliser l'installation en locale


-- Installation –-
1 - Activer les paquets MySQL et Php à partir de XAMPP.

2 - Extraire les fichiers dans "htdocs" de façon à avoir dans "htdocs" le dossier "projetm2" et dans se dossier l’ensemble des sous dossiers et fichiers. 
	- Exemple d’arborescence acceptée pour le fichier Accueil.php "../htdocs/projetm2/HopitalBim/Accueil.php".

3 - Mettre dans le dossier "htdocs" le fichier ".htaccess" et le fichier "404.php".

4 - Ouvrir PhpMyAdmin et aller sur le menu principal. Il faut importer la base de données. Pour cela il faut importer le fichier "BaseDeDonnée.sql".
	- Le fichier "BaseDeDonnée.sql" est localisé dans "../projetm2". Il permet d'importer la base de données.
	- Vérifier que l'identifiant permettant de se connecter à la base de données situé en localhost est bien "root" et le mot de passe est "". 
	- Le nom d'utilisateur et le mot de passe peuvent être changé. Voir la FAQ.

5 - Ouvrir l'explorateur web et se diriger vers : "http://localhost/projetm2/HopitalBim/Accueil.php"

6 - Pour votre première connexion, connectez-vous en tant que Admin, le nom d'utilisateur est "admin00" et le mot de passe est "123456789". 
	- Il est recommandé de modifier le mot de passe. Voir FAQ.

	
-- CONFIGURATION --

-- FAQ --
 1 - J'ai modifié le mot de passe et l’identifiant de la base de données que faire pour que le site puisse se connecter à celle-ci ?
		- Vous pouvez modifier l'identifiant et le mot de passe. 
			Il faut cependant modifier dans le fichier "htdocs\projetm2\HopitalBim\dbconfig.php" les paramétres suivants :
			    private $username = "Nouveau nom utilisateur"; 
				private $password = "Nouveau mot de passe";
			La modification du mot de passe et du nom d'utilisateur est recommandée.

2 - J'ai modifié le nom de la base de données que faire pour que le site puisse se connecter à celle-ci ?
		- Vous pouvez modifier le nom de la base de données. 
			Il faut cependant modifier dans le fichier "htdocs\projetm2\HopitalBim\dbconfig.php" les paramètres suivants :
				private $db_name = "nouveau nom de la base";
			La modification du nom de la base de données n'est recommandée.

3 - Je souhaite modifier le mot de passe de l'admin. Comment faire ? 
	- Il faut se connecter dans un premier temps comme Admin. 
		Lors de votre première connexion, l'identifiant est "admin00" et le mot de passe est "123456789".
		Ensuite se diriger vers la page "http://localhost/projetm2/HopitalBim/Pages/CompteUtilModifier.php"
		Il faut ensuite sélectionner l'utilisateur "admin00".
		Vous pourrez alors modifier tous informations relatives à l'admin, y-compris le mot de passe.
		Il est recommandé de changer le mot de passe de l'admin. Un mot de passe de plus de 10 caractères est recommandé.
		
				
-- CONTACT –
Liste de diffusion : 
	-	Collaboration :
	Hussam Nachabe : hussamnachabe@gmail.com ; 
	Marine Poullet : poullet.m@hotmail.fr ; 
	Marin Truchi : marint06@laposte.net ;
	Carole Belliardo : carole.belliardo@etu.unice.fr ;

	-	Validation :
	Jean-Paul Comet : comet@unice.fr; 
	Gilles Bernot : bernot@unice.fr; 
	Ingrid Grenet : grenet@unice.fr;
