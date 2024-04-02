<?php

DEFINE("targets","https://dev.adlepomed.hu/");
DEFINE("fixtargets","/home/rzvgdubh/adlepomed.hu/test/");
DEFINE("domain", "adlepomed.hu");

if (defined("config.php"))	return;
DEFINE("config.php", "1");

DEFINE("HOST_NAME", "localhost");
DEFINE("USER_NAME", "rzvgdubh_trsadmin");
DEFINE("PASSWD", "koju;te89(z(");
DEFINE("DB_NAME", "rzvgdubh_trsadmin");

DEFINE("prefix", "trs");
DEFINE("lang", "hun");
DEFINE("url", "https://test.adlepomed.hu");
DEFINE("adminurl", url."/wp-admin/");
DEFINE("defaultmail", "noreply@adlepomed.hu");

$baseurl=$_SERVER["SERVER_NAME"];
$absp=(isset($_SERVER["HTTPS"]) ? "https" : "http") . "://".$_SERVER["HTTP_HOST"];
$fullurl=$absp.$_SERVER["REQUEST_URI"];

$osszesnyelv=array("hun"=>"Magyar","eng"=>"Angol","ger"=>"Német");

DEFINE("SESSION_NAME", "trsdash");

session_set_cookie_params(array(
    'lifetime' => 28800,
    'path' => '/',
    'domain' => domain,
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
));

ini_set("session.gc_maxlifetime", 28800);
ini_set("session.bug_compat_warn",0);

?>