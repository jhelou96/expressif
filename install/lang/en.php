<?php
$lang = array();

#COMMON#

$lang['setupWizard'] = 'Setup wizard';
$lang['goBack'] = 'Go back';
$lang['continue'] = 'Continue';
$lang['invalidInputsSubmitted'] = 'Invalid inputs submitted';
$lang['appInstaller'] = 'Expressif App Installer';
$lang['appInstallerDesc'] = 'Expressif is a Content Management System published under the GNU/GPL license';
$lang['copyright'] = 'All rights reserved';

#STEP 2#

$lang['licenseAgreement'] = 'License agreement';
$lang['licenseTermsMustBeAccepted'] = 'License terms must be accepted.';
$lang['licenseTerms'] = 'License terms';
$lang['step2Desc'] = 'Expressif is published under the GNU/GPL license. In order to proceed with the installation, you need to read and accept the following terms.';
$lang['IAgreeWithLicenseTerms'] = 'I agree with the license terms';

#STEP 3#

$lang['PHPError'] = 'PHP version installed on your version is too old. You need at least PHP 5.5.0 for the application to run properly.';
$lang['MySQLError'] = 'MySQL version installed on your version is too old. You need at least MySQL 5.7 for the application to run properly.';
$lang['PDOError'] = 'PDO extension is not enabled. You need to enable it to proceed with the installation.';
$lang['ConfigDirError'] = 'The config directory is not writable. You need to change its permissions to proceed.';
$lang['serverRequirements'] = 'Server requirements';
$lang['success'] = 'Success';
$lang['serverRequirementsSuccess'] = 'Your server meet the min. requirements';
$lang['step3Desc'] = 'In order to continue, the server where Expressif will be hosted need to meet the following requirements.';
$lang['extension'] = 'Extension';
$lang['minRequired'] = 'Min. required';
$lang['server'] = 'Server';
$lang['enabled'] = 'Enabled';
$lang['folder'] = 'Folder';
$lang['writable'] = 'Writable';

#STEP 4#

$lang['appSettings'] = 'Application settings';
$lang['step4Desc'] = 'Please provide the following information. Don\'t worry, these settings can always be changed later.';
$lang['websiteURL'] = 'Website URL';
$lang['appPath'] = 'Application path';
$lang['appPathDesc'] = 'The path represents the folder where the application is hosted. Leave empty if software is being installed in root folder.';
$lang['websiteName'] = 'Website name';
$lang['timezone'] = 'Timezone';

#STEP 5#

$lang['tablesPrefixError'] = 'Tables prefix should be larger than 2 characters and end with the character "_".';
$lang['databaseConnectionFailed'] = 'Connection to database failed.';
$lang['databaseSettings'] = 'Database settings';
$lang['step5Desc'] = 'Please specify your database settings here. Note also that the database that will be used for the software must be created prior to this step. If you have not created one yet, do so now.';
$lang['dbHost'] = 'Database host';
$lang['dbUser'] = 'Database user';
$lang['dbPassword'] = 'Database password';
$lang['dbName'] = 'Database name';
$lang['dbPrefix'] = 'Tables prefix';

#STEP 6#

$lang['administratorAccount'] = 'Administrator account';
$lang['step6Desc'] = 'You need to set up an administrator account that will allow you to manage your website through a control panel.';
$lang['username'] = 'Username';
$lang['usernameDesc'] = 'Username should be alphanumeric and between 3 and 30 characters.';
$lang['password'] = 'Password';
$lang['passwordDesc'] = 'Password should be larger than 6 characters.';
$lang['email'] = 'Email address';
$lang['emailDesc'] = 'Note that this email address will also be used to send email to users.';

#STEP 7#

$lang['readyToInstall'] = 'Ready to install';
$lang['softwareReadyToInstall'] = 'Software is now ready to be installed. Configuration files will be created and database will be installed and populated.';
$lang['installDirWillBeRemoved'] = 'Once the installation is done, the install folder will be removed and you will be redirected to the software.';
$lang['install'] = 'Install';

?>