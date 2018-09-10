<?php
namespace config;

use \PDO;
use \DateTime;
use IntlDateFormatter;
use \DateTimeZone;

/**
 * Class Config
 * Application configuration class
 * @package config
 */
class Config {
    /**
     * Initializes the connection with the DB
     * @return PDO PDO object with DB connection informations
     */
    public static function getDBInfos() {
		try
		{
			//Read JSON config file
			$json = file_get_contents('config/db.json');
			$json_data = json_decode($json,true);
		
			$db = new PDO('mysql:host=' . $json_data['host'] . ';dbname=' . $json_data['dbname'] . ';charset=utf8', $json_data['user'], $json_data['password']);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $db;
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
	}

    /**
     * Returns the prefix for the DB tables
     * @return string
     */
    public static function getDBTablesPrefix() {
		//Read JSON config file
        $json = file_get_contents('config/db.json');
        $json_data = json_decode($json,true);

	    return $json_data['prefix'];
    }

    /**
     * Returns the name of the website
     * @return string
     */
    public static function getName() {
	    //Read JSON config file
        $json = file_get_contents('config/config.json');
        $json_data = json_decode($json,true);

	    return $json_data['website_name'];
    }

    /**
     * Returns the URL of the website
     * @return string
     */
    public static function getURL() {
        //Read JSON config file
        $json = file_get_contents('config/config.json');
        $json_data = json_decode($json,true);

	    return $json_data['website_url'];
    }

    /**
     * Returns the folder in which the application is installed
     * @return string
     */
    public static function getPath() {
        //Read JSON config file
        $json = file_get_contents('config/config.json');
        $json_data = json_decode($json,true);
		
		return $json_data['website_path'];
	}

    /**
     * Returns the lang used for the website
     * @return string
     */
    public static function getLang() {
        //Read JSON config file
        $json = file_get_contents('config/config.json');
        $json_data = json_decode($json,true);
		
		return $json_data['website_lang'];
	}
	
	/**
     * Returns the email address used by the system to send mails
     * @return string
     */
    public static function getEmail() {
        //Read JSON config file
        $json = file_get_contents('config/config.json');
        $json_data = json_decode($json,true);
		
		return $json_data['website_email'];
	}

    /**
     * Returns the folder of the frontend template used
     * @return string
     */
    public static function getFrontendTheme() {
        //Read JSON config file
        $json = file_get_contents('config/config.json');
        $json_data = json_decode($json,true);

	    return $json_data['website_frontTheme'];
    }

    /**
     * Returns the folder of the backend template used
     * @return string
     */
    public static function getBackendTheme() {
        //Read JSON config file
        $json = file_get_contents('config/config.json');
        $json_data = json_decode($json,true);

	    return $json_data['website_backTheme'];
    }

    /**
     * @return bool True if website is closed, false otherwise
     */
    public static function checkIfSiteInMaintenance() {
        //Read JSON config file
        $json = file_get_contents('config/config.json');
        $json_data = json_decode($json,true);

        if($json_data['website_maintenance'] == 1)
            return true;
        else
            return false;
    }

    /**
     * Generates and return a random sequence of alphanumeric chars
     * @param int $length Length of the sequence to generate
     * @return string Generated sequence of characters
     */
    public static function random($length) {
		$string = "";
		$char = "abcdefghijklmnpqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
		
		srand((double)microtime()*1000000);
		
		for($i=0; $i < $length; $i++)
			$string .= $char[rand()%strlen($char)];
		
		return $string;
	}

    /**
     * Replaces special characters in a string so it can work properly as a URL
     * @param string $url
     * @return string Formatted string so it can be used in the URL (example article title)
     */
    public static function urlFormat($url) {
		$url = preg_replace('#Ç#', 'C', $url);
		$url = preg_replace('#ç#', 'c', $url);
		$url = preg_replace('#è|é|ê|ë#', 'e', $url);
		$url = preg_replace('#È|É|Ê|Ë#', 'E', $url);
		$url = preg_replace('#à|á|â|ã|ä|å#', 'a', $url);
		$url = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $url);
		$url = preg_replace('#ì|í|î|ï#', 'i', $url);
		$url = preg_replace('#Ì|Í|Î|Ï#', 'I', $url);
		$url = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $url);
		$url = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $url);
		$url = preg_replace('#ù|ú|û|ü#', 'u', $url);
		$url = preg_replace('#Ù|Ú|Û|Ü#', 'U', $url);
		$url = preg_replace('#ý|ÿ#', 'y', $url);
		$url = preg_replace('#Ý#', 'Y', $url);
		$url = preg_replace('# #', '-', $url);
		$url = strtolower($url);
		
		return $url;
	}

    /**
     * Formats a date properly
     * @param string $date
     * @return string Simple date
     */
    public static function dateFormat($date) {
        //Read JSON config file
        $json = file_get_contents('config/config.json');
        $json_data = json_decode($json,true);
        $timezone = $json_data['website_timezone'];

		$date = new DateTime($date);
		$formatter = new IntlDateFormatter('en_US', IntlDateFormatter::LONG, IntlDateFormatter::NONE, $timezone,  IntlDateFormatter::GREGORIAN );
		
		return $formatter->format($date);
	}

    /**
     * Formats a datetime properly
     * @param string $date
     * @return string Detailed date and time
     */
    public static function dateTimeFormat($date) {
        //Read JSON config file
        $json = file_get_contents('config/config.json');
        $json_data = json_decode($json,true);
        $timezone = $json_data['website_timezone'];
		$timezone = new DateTimeZone($timezone);
		$given = new DateTime($date, $timezone);
		$current = new DateTime('', $timezone);
		
		if($current->diff($given)->format('%d') == 1) {
			$format_time = new IntlDateFormatter('fr_FR', IntlDateFormatter::NONE, IntlDateFormatter::SHORT, $timezone,  IntlDateFormatter::GREGORIAN );
			
			return ('Yesterday at ' . str_replace(':', 'h', $format_time->format($given)));
		} elseif($current->diff($given)->format('%d') == 0) {
			if($current->diff($given)->format('%h') > 0)
				return($current->diff($given)->format('%h') . ' ' . (($current->diff($given)->format('%h') > 1) ? 'hours' : 'hour') . ' ago');
			else
				return((($current->diff($given)->format('%i') == 0) ? '1' : $current->diff($given)->format('%i')) . ' ' . (($current->diff($given)->format('%i') > 1) ? 'minutes' : 'minute') . ' ago');
		} else {
			$format_date = new IntlDateFormatter('en_US', IntlDateFormatter::FULL, IntlDateFormatter::NONE, $timezone,  IntlDateFormatter::GREGORIAN );
			$format_time = new IntlDateFormatter('en_US', IntlDateFormatter::NONE, IntlDateFormatter::SHORT, $timezone,  IntlDateFormatter::GREGORIAN );
		
			return (ucfirst($format_date->format($given)) . ' at ' . str_replace(':', 'h', $format_time->format($given)));
		}
	}

    /**
     * Deletes all the files and folders inside a specific folder
     * @param string $target
     */
    public static function deleteFiles($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

            foreach( $files as $file )
            {
                Config::deleteFiles( $file );
            }

            rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );
        }
    }

    /**
     * Adds the DB tables prefix to a SQL file
     * @param string $file
     */
    public static function DBTables_addPrefix($file) {
        $prefix = Config::getDBTablesPrefix();

        // Search and replace
        $search = array("TABLES `", "TABLE `", "EXISTS `", "INTO `", "REFERENCES `");
        $replace = array("TABLES `$prefix", "TABLE `$prefix", "EXISTS `$prefix", "INTO `$prefix", "REFERENCES `$prefix");

        $fileContent = file_get_contents($file);
        $fileContent = str_ireplace($search, $replace, $fileContent);

        file_put_contents($file, $fileContent);
    }

    public static function checkIfModuleActive($module) {
		if(PHP_OS == "Windows" OR PHP_OS == "WINNT") //Path to JSON file for Windows
			$jsonFile = $module . '\module.json';
		else
			$jsonFile = $module . '/module.json'; //Path to JSON file for other OS
		
        if(file_exists($jsonFile)) {
            $json      = file_get_contents($jsonFile); // Read JSON file
            $json_data = json_decode($json, true); //Decode JSON

            if($json_data['activated'] == 1)
                return true;
            else
                return false;
        } else
            return false;
    }
}