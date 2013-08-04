

/!\ CONFIGURATION SERVEUR /!\:
	-Le module mod_rewrite.so est à décommenter du fichier conf/httpd.conf d'apache
	-L'extension php_socket est à décommenter du fichier php.ini
	-Le port 9013 doit également être ouvert et disponible

	
Le site a besoin du fichier dyn/database.inc.php pour se connecter à la base de données.

à l'interieur, y mettre :

<?php
 
$infos = array(
	"adresse" => "localhost",
	"login" => "votre_login_mysql",
	"password" => "votre_pass_mysql",
	"base" => "mots_a_la_pelle"
);

?>

Puis ignorer ce fichier au projet SVN :
- Sous WINDOWS, clic droit sur database.inc.php, Tortoise SVN -> Unversion and add to ignore list -> database.inc.php



Lancer le daemon de jeux:

CLI : 
	- path/to/php php.exe path/to/site/socket/serveur.php
SINON :
	via ajax