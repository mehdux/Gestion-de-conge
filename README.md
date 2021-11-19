Bonjour ,

Pour éxecuter l'application il vaut faut un environement wamp, xampp ou lamp avec un sgbdr mysql ou maria database.

Décompressez l'archive puis récupérez le dossier nommé "gestion_conges", ce dossier contient deux dossiers nommés "api" et "frontend" + un fichier sql pour la bdd

Mettez le dossier "gestion_conges" dans htdocs de xampp, www de wamp ou lamp

Créez une bdd nommez la "gestion_conges" dans mysql puis executez le script sql ce dernier créera toutes les tables (il y a déjà des données exploitables).

Lancer la partie client de l'application via l'url suivante : http://127.0.0.1/gestion_conges/frontend/ bien sur après avoir lancer 
le serveur d'application (wamp, xampp ou lamp)

Le numéro d'identification pour : 
	- un responsable est     : 12345
	- un manager est         : 00045
	- un simple salarié est  : 12300 

Dans le fichier config.php de la partie api vous pouvez introduire un mot de passe si votre sgbdr a été configuré avec un mot passe sinon le user c'est root.

Merci.
