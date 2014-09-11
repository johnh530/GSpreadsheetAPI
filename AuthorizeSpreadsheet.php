<?php
// This is a working example for use with google spreadsheet api code - 
//   Can be used as a template for other api - see particulars which need to
//   be changed for other api with //### Other APIs 

// keys to access calendars from google api console credentials
$CLIENTID = "768355868856-4ij8415st5ok1kp8fjugr5eh6hgvsegm.apps.googleusercontent.com";
$CLIENTSECRET = "S_UnsA5-JdBLG08VKr1hY_bo";

// URL to come back to
$LocalURL = "http://". $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
session_start();

// Reference google api php client - this assumes that ../google-api-php-client
// directory is in the parent directory of this project
set_include_path("../google-api-php-client/src/" . PATH_SEPARATOR . get_include_path());
require_once('Google/Client.php');
//### Other APIs -- pick up objects for code - change for other APIs

$client = new Google_Client();
$client->setClientId($CLIENTID);
$client->setClientSecret($CLIENTSECRET);
$client->setRedirectUri($LocalURL);
//## Other APIs -- the scope (permissions) change for other APIs
$client->addScope("https://spreadsheets.google.com/feeds");
//## Other APIs -- primary object for service change for other APIs

if (isset($_GET['logout'])){
    unset($_SESSION['token']);
}
if (isset($_GET['code'])){
    if (strval($_SESSION['state']) !== strval($_GET['state'])){
        die("The sessions states did not match.");
    }
    $client->authenticate($_GET['code']);
    $_SESSION['token'] = $client->getAccessToken();
    header('Location: ' . filter_var($LocalURL, FILTER_SANITIZE_URL));
    exit;
}

if (isset($_SESSION['token'])){
    $client->setAccessToken($_SESSION['token']);
}

if (!$client->getAccessToken()){
    $state = mt_rand();
    $client->setState($state);
    $_SESSION['state'] = $state;
    $authUrl = $client->createAuthUrl();
//### Other APIs -- authorize link - change for other APIs
    print "<a class='login' href='$authUrl'>Click here to authorize to work on Grade Spreadsheets</a>";
    exit;
}
$Line= $_SESSION['token'];
if (preg_match('/"access_token":"([^"]*)"/',$Line,$Matches )){
    $accessToken = $Matches[1];
}else $accessToken = "NO MATCH";

require_once("vendor/autoload.php");
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

$serviceRequest = new DefaultServiceRequest($accessToken);
ServiceRequestFactory::setInstance($serviceRequest);
$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
// fall through and start to work with $spreadsheetService object
?>
