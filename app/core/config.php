<?php 


// Determine if running on localhost
$isLocalhost = (empty($_SERVER['SERVER_NAME']) && php_sapi_name() === 'cli') 
               || (!empty($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost');


/** Root Path */
if ($isLocalhost) {
    define('ROOT', 'http://localhost/weather/public');
} else {
    // Dynamic detection for production
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    define('ROOT', $protocol . '://' . $_SERVER['HTTP_HOST'] . str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME'])));
}

/** Application Metadata */
define('APP_NAME', "myWeatherStation");
define('DEVELOPER', "Timothy");
define('APP_DESC', "Best website on the planet");


define('POST_DATA_URL', 'http://localhost/weather/public/process');

//PROJECT_API_KEY is the exact duplicate of, PROJECT_API_KEY in NodeMCU sketch file
//Both values must be same
define('PROJECT_API_KEY', 'weatherstation');

/** Error Reporting */
if ($isLocalhost || (getenv('DEBUG') === 'true')) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    define('DEBUG', true);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '../logs/error.log');
    define('DEBUG', false);
}


