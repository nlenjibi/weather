<?php 

defined('ROOTPATH') OR exit('Access Denied!');



if($_SERVER['SERVER_NAME'] == "localhost")
{
	/** Database Configuration */
	define('DBNAME', getenv('DB_NAME') ?: 'weather_db');
	define('DBHOST', getenv('DB_HOST') ?: 'localhost');
	define('DBUSER', getenv('DB_USER') ?: 'root');
	define('DBPASS', getenv('DB_PASS') ?: '');
	define('DBDRIVER', getenv('DB_DRIVER') ?: '');

}else
{
	/** Database Configuration */
	define('DBNAME', getenv('DB_NAME') ?: 'weather_db');
	define('DBHOST', getenv('DB_HOST') ?: 'localhost');
	define('DBUSER', getenv('DB_USER') ?: 'root');
	define('DBPASS', getenv('DB_PASS') ?: '');
	define('DBDRIVER', getenv('DB_DRIVER') ?: '');
}


