<?php

define("targets","https://dev.adlepomed.hu/");
define("fixtargets","/home/rzvgdubh/adlepomed.hu/test/");
define("domain", "adlepomed.hu");

error_reporting(E_ALL ^ E_NOTICE);
if (defined("config.php"))	return;
	
define("config.php", "1");

DEFINE("HOST_NAME", 'localhost');
DEFINE("USER_NAME", 'rzvgdubh_trsadmin');
DEFINE("PASSWD", 'koju;te89(z(');
DEFINE("DB_NAME", 'rzvgdubh_trsadmin');

global $prefix;
$prefix="trs";

global $domain;
$domain="dev.adlepomed.hu";

global $defaultmail;
$defaultmail="noreply@adlepomed.hu";

$absp=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER["HTTP_HOST"];
$fullurl=$absp.$_SERVER["REQUEST_URI"];

ini_set('session.bug_compat_warn',0);

//éles környezet változó beállítása
if($_SERVER["SERVER_NAME"] == "akarmi.hu")
    define("PROD", false);
else
    define("PROD", true);

if(!PROD){
    ini_set("display_startup_errors", 1);
    ini_set("display_errors", 1);
    error_reporting(-1);
}

DEFINE("SESSION_NAME", "trsdash");

session_set_cookie_params(array(
    'lifetime' => 28800,
    'path' => '/',
    'domain' => domain,
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'));

ini_set('session.gc_maxlifetime', 28800);

?>