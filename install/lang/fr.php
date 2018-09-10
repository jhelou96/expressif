<?php
$lang = array();

#COMMON#

$lang['setupWizard'] = 'Outil d\'installation';
$lang['goBack'] = 'Retourner en arrière';
$lang['continue'] = 'Continuer';
$lang['invalidInputsSubmitted'] = 'Données soumises invalides';
$lang['appInstaller'] = 'Installateur - Expressif';
$lang['appInstallerDesc'] = 'Expressif est un Système de Gestion de Contenu publié sous licence GNU/GPL';
$lang['copyright'] = 'Tous droits réservés';

#STEP 2#

$lang['licenseAgreement'] = 'Acceptation des termes de la licence';
$lang['licenseTermsMustBeAccepted'] = 'Les termes de la licence doivent être acceptés.';
$lang['licenseTerms'] = 'Termes de la licence';
$lang['step2Desc'] = 'Expressif est publié sous licence GNU/GPL. Afin de continuer l\'installation, vous devez lire et accepter les termes ci-dessous.';
$lang['IAgreeWithLicenseTerms'] = 'Je confirme avoir lu et accepté les termes de la licence';

#STEP 3#

$lang['PHPError'] = 'La version PHP installée est dépassée. Vous avez besoin de PHP 5.5.0 ou supérieur pour que l\'application puisse fonctionner correctement.';
$lang['MySQLError'] = 'La version MySQL installée est dépassée. Vous avez besoin de MySQL 5.7 ou supérieur pour que l\'application puisse fonctionner correctement.';
$lang['PDOError'] = 'L\'extension PDO est désactivée. Vous devez l\'activer pour continuer l\'installation.';
$lang['ConfigDirError'] = 'Le dossier config n\'est pas disponible en mode écriture. Vous devez changer ses permissions pour pouvoir continuer l\'installation.';
$lang['serverRequirements'] = 'Vérification de la configuration du serveur';
$lang['success'] = 'Félicitation';
$lang['serverRequirementsSuccess'] = 'Votre serveur répond aux exigences minimales requises.';
$lang['step3Desc'] = 'Afin de continuer, la configuration du serveur qui va héberger Expressif va être vérifiée.';
$lang['extension'] = 'Extension';
$lang['minRequired'] = 'Min. requis';
$lang['server'] = 'Serveur';
$lang['enabled'] = 'Activé';
$lang['folder'] = 'Dossier';
$lang['writable'] = 'Inscriptible';

#STEP 4#

$lang['appSettings'] = 'Paramètres de l\'application';
$lang['step4Desc'] = 'Merci de remplir les informations suivantes. Ne vous inquiétez pas, vous pourrez toujours les changer par la suite.';
$lang['websiteURL'] = 'URL site web';
$lang['appPath'] = 'Chemin vers l\'application';
$lang['appPathDesc'] = 'Le chemin représente le dossier dans lequel l\'application est hébérgée. Laissez vide si l\'application se situe dans le dossier root.';
$lang['websiteName'] = 'Nom site web';
$lang['timezone'] = 'Fuseau horaire';

#STEP 5#

$lang['tablesPrefixError'] = 'Le préfixe des tables doit être composé de 2 caractères au moins et doit se terminer par le symbole "_".';
$lang['databaseConnectionFailed'] = 'Echec de la connexion à la base de données.';
$lang['databaseSettings'] = 'Paramètres base de données';
$lang['step5Desc'] = 'Merci de renseigner les informations concernant votre base de données ici. Veuillez noter que la base de données utilisée pour l\'application doit être crée avant de compléter cette étape. Si ça n\'est pas déjà fait, veuillez la créer maintenant.';
$lang['dbHost'] = 'Hôte';
$lang['dbUser'] = 'Utilisateur';
$lang['dbPassword'] = 'Mot de passe';
$lang['dbName'] = 'Nom de la base de données';
$lang['dbPrefix'] = 'Préfixe des tables';

#STEP 6#

$lang['administratorAccount'] = 'Compte administrateur';
$lang['step6Desc'] = 'Vous devez créer un compte administrateur qui vous permettra de gérer l\'application depuis un panel d\'administration.';
$lang['username'] = 'Pseudo';
$lang['usernameDesc'] = 'Le pseudo doit être composé de 3 à 30 caractères alphanumériques.';
$lang['password'] = 'Mot de passe';
$lang['passwordDesc'] = 'Le mot de passe doit être composé de 6 caractères ou plus.';
$lang['email'] = 'Adresse email';
$lang['emailDesc'] = 'Cette adresse email sera également utilisée par le système pour envoyer des mails aux utilisateurs.';

#STEP 7#

$lang['readyToInstall'] = 'Prêt à installer';
$lang['softwareReadyToInstall'] = 'L\'application est maintenant prête à être installée. Les fichiers de configuration vont être crées et la base de données va être installée et peuplée.';
$lang['installDirWillBeRemoved'] = 'Une fois l\'installation terminée, le dossier d\'installation va être supprimé et vous serez automatiquement redirigé vers la page d\'accueil de l\'application.';
$lang['install'] = 'Installer';

?>