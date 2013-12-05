<?php
error_reporting(E_WARNING); 
ini_set("display_errors", 1); 
// site wide
$SITE_URL = 'http://'.$_SERVER['HTTP_HOST'];

// db info

/**
$DB_IP   = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'initpwd4propelhire';
$DB_NAME = 'propelhire_staging';
**/

$DB_IP   = 'mysql51a.ayera.com';
$DB_USER = 'i_dd61252065';
$DB_PASS = 'dd22i';
$DB_NAME = 'DD61252065';


// path
$CTRL_PATH = '../app/ctrl';
$BL_PATH = '../app/bl';
$DAL_PATH = '../app/dal';
$VIEW_PATH = '../app/view';
$TPL_PATH = '../app/view/html';
$SHARE_PATH = '../share';
$PUBLIC_PATH = '.';
$RESUME_UPLOAD_PATH = '../upload/resume';

// mailer
//$EHLO = 'phdev.propel.com';
//$MTA_IP = '172.16.0.204';
//$RETURN_PATH = 'support@propel.com';

//Constants
define("MAX_CANDIDATES","5");
define("MAX_RECRUITERS","5");
define("ADMIN_ID","1000000");
define("ADMIN_FIRST_NAME","PropelHire");
define("ADMIN_LAST_NAME","Admin");
define("ADMIN_EMAIL","support@propel.com");
define("NOTIFICATION_NAME","Milder Lisondra");
define("NOTIFICATION_EMAIL","mlisondra@dystrick.com");

require_once("$SHARE_PATH/util.php");
//Mailer
require_once("../scripts/phpmailer/class.phpmailer.php");

function __autoload($class)
{
    global $CTRL_PATH, $BL_PATH, $DAL_PATH, $VIEW_PATH, $TPL_PATH, $SHARE_PATH, $PUBLIC_PATH;
    $paths = array($CTRL_PATH, $BL_PATH, $DAL_PATH, $VIEW_PATH, $TPL_PATH, $SHARE_PATH, $PUBLIC_PATH);

    foreach($paths as $path)
    {
        $file_path = $path.'/'.$class.'.php';
        if(file_exists($file_path))
        {
        	require_once("$file_path");
            break;
        }
    }
}
