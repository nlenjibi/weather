<?php
session_start();
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);

foreach (glob(ROOTPATH . "../app/core/*.php") as $filename)   
        require $filename;
foreach (glob(ROOTPATH . "../app/models/*.php") as $filename)   
        require $filename;
// Get URL and sanitize
$PAGE = get_pagination_vars();
$url = $_GET['url'] ?? 'home';
$url = strtolower($url);
$url = explode("/", filter_var($url, FILTER_SANITIZE_URL));

// Initialize APP metadata
$APP = [];
$APP['page'] = URL(0);
$APP['sitename'] = 'ModernTech';
$APP['description'] = 'ModernTech IT Services';

// Handle AJAX requests separately
if ($url[0] === 'ajax') {
    require ROOTPATH . "../app/api/ajax.php";
    exit;
} 
if ($url[0] === 'fetch') {
    require ROOTPATH . "../app/api/api.php";
    exit;
} 


// Construct the file path
$file = ROOTPATH . '../app/views/' . URL(0) . '.php';

// Check if the file exists
if (file_exists($file)) {
    // Prepare and pass data to the view
    try {
        view($file, [
            'APP' => $APP,
            'PAGE' => $PAGE,
            'url' => $url
        ]);
    } catch (Exception $e) {
        // Log the error and show a generic error page
        error_log("Error loading view: " . $e->getMessage());
        view(ROOTPATH . '../app/views/500.php', ['error' => $e->getMessage()]);
    }
} else {
    // Route to 404 page if the file does not exist
    view(ROOTPATH . '../app/views/404.php', ['APP' => $APP, 'PAGE' => $PAGE, 'url' => $url]);
}
