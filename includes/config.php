<?php

$title = '';
$divider = " | ";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

error_reporting(-1);              // Report all type of errors
ini_set("display_errors", 1);     // Display all errors    

     
// Aktivera autoladdning för att påskynda registreringen av klasser som behövs
spl_autoload_register(function ($class) {
    include '../classes/' . $class . '.class.php';
});


$devMode = false;

if ($devMode) {
    //konstanter för att lagra databas information på server 
    define("DBHOST", "studentmysql.miun.se");
    define("DBUSER", "giin1900");
    define("DBPASS", "p9x6zw6j");
    define("DBDATABASE", "giin1900");
}else{  
    //konstanter för att lagra databas information i lokal 
    define("DBHOST", "localhost");
    define("DBUSER", "dbtest");
    define("DBPASS", "password");
    define("DBDATABASE", "dbtest");
}

