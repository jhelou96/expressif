<?PHP

/* ------------------------------------------------------------------------- */
/* -------- DEVELOPER-SETUP (User make no changes after this line!) -------- */
/* ------------------------------------------------------------------------- */


   /* ----- Requirements 
      A webserver or web hosting account running on any major Operating System 
	  with support for PHP Version 4.1 or above and PECL zip >= 1.0.0
	  ON ERROR TEXT: PHP 4.1 or greater is required. Operation Aborted
    */


   // ----- USE ON THE DEVELOPMENT WEB-SERVER 

   // Domain and Directory of your Development-Server (TESTED UNDER WIN/LINUX&OSX)
     $updatesDataServerUrlPrefix = 'http://expressif.net/zirkon/'; // don't forget the slash at the end

   // Text file with all Version Numbers, one Number in each Line
   // ON ERROR TEXT: Could Not Read New-Versions. Operation Aborted.
      $filenameAllReleaseVersions = 'current-release-versions.txt';

   // Save all of your Updates as ZIP Data in this Directory, named: version.number.zip (e.g. 1.01.zip)
   // ON ERROR TEXT: Could Not Read Update.zip Operation Aborted
      $directoryAllUpdatePackages = 'update-packages/'; // don't forget the slash at the end
	  
   // USE version.number.txt (e.g. 1.01.txt) 
   //     in the $directoryAllUpdatePackages to show "What's New" Info in the Script update.php
   // USE upgrade.php in the root of your ZIP to do stuff after update e.g. del_tree('dir') or 
   //     print_message('OK','DB Updated','green') or to Setup/Update your Database etc.


   // ----- USE ON THIS SCRIPT LOCAL

   // In this File the Current used Version-Number of your local Script is stored
      $localVersionFile = 'version.txt';

   // In this Directory the Update ZIPs are stored
      $localDownloadDir = 'vendor/update/downloads/'; // don't forget the slash at the end

   // If set to TRUE the Script scrolls automatically to the bottom of the page
   // SET to FALSE if you don't want this!
	  $automaticallyScrollToTheBottomOfThePage = TRUE;


/* ------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------- */
