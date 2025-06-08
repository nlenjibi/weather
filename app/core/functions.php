<?php 

// function query(string $query, array $data = [])
// {

//     $string = "mysql:hostname=".DBHOST.";dbname=". DBNAME;
//     $con = new PDO($string, DBUSER, DBPASS);

//     $stm = $con->prepare($query);
//     $stm->execute($data);

//     $result = $stm->fetchAll(PDO::FETCH_ASSOC);
//     if(is_array($result) && !empty($result))
//     {
//         return $result;
//     }

//     return false;

// }
function query(string $query, array $data = [])
{
    try {
        // Database connection string
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $con = new PDO($dsn, DBUSER, DBPASS);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the query
        $stm = $con->prepare($query);
        $stm->execute($data);

        // Determine query type
        $queryType = strtolower(explode(' ', trim($query))[0]);
        if (in_array($queryType, ['select', 'show'])) {
            // Return result set for SELECT or SHOW queries
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        }

        // Return number of affected rows for INSERT, UPDATE, DELETE
        return $stm->rowCount();

    } catch (PDOException $e) {
        // Handle exceptions and log errors
        error_log("Database Query Error: " . $e->getMessage());
        return false;
    }
}

function URL($key=null){
    $aray = explode("/", trim($_GET['url'] ?? 'home', "/"));
    if(!is_numeric($key))
        return $aray;
    return $aray[$key ?? null];
}
function remove_images_from_content($content, $folder = 'uploads/')
{

	preg_match_all("/<img[^>]+/", $content, $matches);

	if(is_array($matches[0]) && count($matches[0]) > 0)
	{
		foreach ($matches[0] as $img) {

			if(!strstr($img, "data:"))
			{
				continue;
			}

			preg_match('/src="[^"]+/', $img, $match);
			$parts = explode("base64,", $match[0]);

			preg_match('/data-filename="[^"]+/', $img, $file_match);

			$filename = $folder.str_replace('data-filename="', "", $file_match[0]);

			file_put_contents($filename, base64_decode($parts[1]));
			$content = str_replace($match[0], 'src="'.$filename, $content);
			

		}
	}
	return $content;
}


function add_root_to_images($content)
{

	preg_match_all("/<img[^>]+/", $content, $matches);

	if(is_array($matches[0]) && count($matches[0]) > 0)
	{
		foreach ($matches[0] as $img) {

			preg_match('/src="[^"]+/', $img, $match);
			$new_img = str_replace('src="', 'src="'.ROOT."/", $img);
			$content = str_replace($img, $new_img, $content);

		}
	}
	return $content;
}

function remove_root_from_content($content)
{
	
	$content = str_replace(ROOT, "", $content);

	return $content;
}


function query_row(string $query, array $data = [])
{

	$string = "mysql:hostname=".DBHOST.";dbname=". DBNAME;
	$con = new PDO($string, DBUSER, DBPASS);

	$stm = $con->prepare($query);
	$stm->execute($data);

	$result = $stm->fetchAll(PDO::FETCH_ASSOC);
	if(is_array($result) && !empty($result))
	{
		return $result[0];
	}

	return false;

}
function get_admin_total_count($table, $role = 'admin') {
    $query = "SELECT COUNT(id) as total FROM " . $table . " WHERE role = :role";
    $result = query($query, ['role' => $role]);
    return $result[0]['total'] ?? 0;
  }

function get_total_count($table) {
    $query = "SELECT COUNT(id) as total FROM " . $table;
    $result = query($query);
    return $result[0]['total'] ?? 0;
}
function get_total_count_user($table, ) {
    $query = "SELECT COUNT(id) as total FROM " . $table . " WHERE user_id = :user_id";
    $result = query($query, ['user_id' => $_SESSION['USER']['id']]);
    return $result[0]['total'] ?? 0;
}


function redirect($page)
{

	header('Location: '.ROOT. '/' . $page);
	die;
}

function old_value($key, $default = '')
{
	if(!empty($_POST[$key]))
		return $_POST[$key];

	return $default;
}

function old_checked($key, $default = '')
{
	if(!empty($_POST[$key]))
		return " checked ";
	
	return "";
}

function old_select($key, $value, $default = '')
{
	if(!empty($_POST[$key]) && $_POST[$key] == $value)
		return " selected ";
	
	if($default == $value)
		return " selected ";
	
	return "";
}

function get_image($file)
{
	$file = $file ?? '';
	if(file_exists($file))
	{
		return ROOT.'/'.$file;
	}

	return ROOT.'/assets/images/no_image.jpg';
}

function str_to_url($url)
{

   $url = str_replace("'", "", $url);
   $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
   $url = trim($url, "-");
   $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
   $url = strtolower($url);
   $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
   
   return $url;
}
// Define the `esc` function if not already defined
if (!function_exists('esc')) {
    function esc($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

function authenticate($row)
{
	$_SESSION['USER'] = $row;
}

function user($key = '')
{
	if(empty($key))
		return $_SESSION['USER'];

	if(!empty($_SESSION['USER'][$key]))
		return $_SESSION['USER'][$key];

	return '';
}

function shortenText($text, $chars=20) {
	if(strlen($text) > $chars) {
		$text = substr($text, 0, $chars);
		$text = substr($text, 0, strrpos($text, ' '));
		$text = $text." ...";
	}
	return $text;
}
    
function logged_in()
{
	if(!empty($_SESSION['USER']))
		return true;

	return false;
}
function is_admin_or_author()
{
    if(!empty($_SESSION['USER']) && ($_SESSION['USER']['role'] == 'admin' || $_SESSION['USER']['role'] == 'author') && $_SESSION['USER']['active'] == 1)
        return true;

    return false;
}
function is_admin()
{
    if(!empty($_SESSION['USER']) && ($_SESSION['USER']['role'] == 'admin' ) && $_SESSION['USER']['active'] == 1)
        return true;

    return false;
}
function is_user()
{
    if(!empty($_SESSION['USER']) && $_SESSION['USER']['role'] == 'user')
        return true;

    return false;
}
function get_pagination_url($page_number)
{
	return $_SERVER['PHP_SELF'].'?page='.$page_number;
}

function render_pagination($PAGE)
{
    ob_start();
    ?>
    <div class="text-center m-auto">
        <ul class="pagination pagination-separated">
            <?php if ($PAGE['page_number'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=$PAGE['base_link']?>?page=1" aria-label="First">
                    <i class="bi bi-chevron-left"></i>
                        <span aria-hidden="true" class="mdi mdi-chevron-double-left mr-1"></span> First
                        <!-- <span class="sr-only">First</span> -->
                        
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($PAGE['page_number'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=$PAGE['prev_link']?>" aria-label="Previous">
                        <span aria-hidden="true" class="mdi mdi-chevron-left mr-1"></span> Prev
                        <!-- <span class="sr-only">Previous</span> -->
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $PAGE['total_pages']; $i++): ?>
                <li class="page-item <?=($i == $PAGE['page_number']) ? 'active' : ''?>">
                    <a class="page-link" href="<?=$PAGE['base_link']?>?page=<?=$i?>"><?=$i?></a>
                </li>
            <?php endfor; ?>

            <?php if ($PAGE['page_number'] < $PAGE['total_pages']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=$PAGE['next_link']?>" aria-label="Next">
                        Next
                        <span aria-hidden="true" class="mdi mdi-chevron-right ml-1"></span>
                        <!-- <span class="sr-only">Next</span> -->
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($PAGE['page_number'] < $PAGE['total_pages']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=$PAGE['base_link']?>?page=<?=$PAGE['total_pages']?>" aria-label="Last">
                        Last
                        <span aria-hidden="true" class="mdi mdi-chevron-double-right ml-1"></span>
                        <!-- <span class="sr-only">Last</span> -->
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}
function get_pagination_vars( $total_items=50, $items_per_page = 10)
{

    /** set pagination vars **/
    $page_number = $_GET['page'] ?? 1;
    $page_number = empty($page_number) ? 1 : (int)$page_number;
    $page_number = $page_number < 1 ? 1 : $page_number;

    $current_link = $_GET['url'] ?? 'home';
    $current_link = ROOT . "/" . $current_link;
    $query_string = "";

    foreach ($_GET as $key => $value)
    {
        if($key != 'url')
            $query_string .= "&".$key."=".$value;
    }

    if(!strstr($query_string, "page="))
    {
        $query_string .= "&page=".$page_number;
    }

    $query_string = trim($query_string,"&");
    $current_link .= "?".$query_string;

    $current_link = preg_replace("/page=.*/", "page=".$page_number, $current_link);
    $next_link = preg_replace("/page=.*/", "page=".($page_number+1), $current_link);
    $first_link = preg_replace("/page=.*/", "page=1", $current_link);
    $prev_page_number = $page_number < 2 ? 1 : $page_number - 1;
    $prev_link = preg_replace("/page=.*/", "page=".$prev_page_number, $current_link);

    $base_link = preg_replace("/\?.*/", "", $current_link);
   // dd($base_link);

    
    $total_pages = ceil($total_items / $items_per_page);
    $result = [
        'current_link'  => $current_link,
        'next_link'     => $next_link,
        'prev_link'     => $prev_link,
        'first_link'    => $first_link,
        'page_number'   => $page_number,
        'total_pages'   => $total_pages,
        'base_link'     => $base_link,
    ];


	
	return $result;
}


//create_tables();
function create_tables()
{

	$string = "mysql:hostname=".DBHOST.";";
	$con = new PDO($string, DBUSER, DBPASS);

	$query = "create database if not exists ". DBNAME;
	$stm = $con->prepare($query);
	$stm->execute();

	$query = "use ". DBNAME;
	$stm = $con->prepare($query);
	$stm->execute();

	/** users table **/
	$query = "create table if not exists users(

		id int primary key auto_increment,
		username varchar(50) not null,
		email varchar(100) not null,
		password varchar(255) not null,
		image varchar(1024) null,
		date datetime default current_timestamp,
		role varchar(10) not null,

		key username (username),
		key email (email)

	)";
	$stm = $con->prepare($query);
	$stm->execute();

	/** categories table **/
	$query = "create table if not exists categories(

		id int primary key auto_increment,
		category varchar(50) not null,
		slug varchar(100) not null,
		disabled tinyint default 0,

		key slug (slug),
		key category (category)

	)";
	$stm = $con->prepare($query);
	$stm->execute();

	/** posts table **/
	$query = "create table if not exists posts(

		id int primary key auto_increment,
		user_id int,
		category_id int,
		title varchar(100) not null,
		content text null,
		image varchar(1024) null,
		date datetime default current_timestamp,
		slug varchar(100) not null,

		key user_id (user_id),
		key category_id (category_id),
		key title (title),
		key slug (slug),
		key date (date)

	)";
	$stm = $con->prepare($query);
	$stm->execute();


}


function resize_image($filename, $max_size = 1000)
{
	
	if(file_exists($filename))
	{
		$type = mime_content_type($filename);
		switch ($type) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg($filename);
				break;
			case 'image/png':
				$image = imagecreatefrompng($filename);
				break;
			case 'image/gif':
				$image = imagecreatefromgif($filename);
				break;
			case 'image/webp':
				$image = imagecreatefromwebp($filename);
				break;
			default:
				return;
				break;
		}

		$src_width 	= imagesx($image);
		$src_height = imagesy($image);

		if($src_width > $src_height)
		{
			if($src_width < $max_size)
			{
				$max_size = $src_width;
			}

			$dst_width 	= $max_size;
			$dst_height = ($src_height / $src_width) * $max_size;
		}else{
			
			if($src_height < $max_size)
			{
				$max_size = $src_height;
			}

			$dst_height = $max_size;
			$dst_width 	= ($src_width / $src_height) * $max_size;
		}

		$dst_height = round($dst_height);
		$dst_width 	= round($dst_width);

		$dst_image = imagecreatetruecolor($dst_width, $dst_height);
		imagecopyresampled($dst_image, $image, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
		
		switch ($type) {
			case 'image/jpeg':
				imagejpeg($dst_image, $filename, 90);
				break;
			case 'image/png':
				imagepng($dst_image, $filename, 90);
				break;
			case 'image/gif':
				imagegif($dst_image, $filename, 90);
				break;
			case 'image/webp':
				imagewebp($dst_image, $filename, 90);
				break;

		}

	}
}
if (!function_exists('dd')) {
    function dd($data){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }
}

if (!function_exists('URL')) {
    function URL($key=null){
        $aray = explode("/", trim($_GET['url'] ?? 'home', "/"));
        if(!is_numeric($key))
            return $aray;
        return $aray[$key ?? null];
    }
}

if (!function_exists('APP')) {
    function APP($key = null, $value = null) {
        global $APP;
        if (is_array($key)) {
            $APP = array_merge($APP, $key);
            return $APP;
        } elseif ($key && $value) {
            $APP[$key] = $value;
            return $APP[$key];
        } elseif ($key) {
            return $APP[$key] ?? null;
        }
        return $APP;
    }
}

if (!function_exists('view')) {
    function view($file, $data = []){
        extract($data);
        if(file_exists($file))
            require_once "$file";
        return '';
    }
}
if (!function_exists('includes')) {
    function includes($str){
        $file='../app/views/includes/'. $str;
        if(file_exists($file))
            include $file;
        return '';
    }
}
function method(): string
{
    return $_SERVER['REQUEST_METHOD'] ?? 'GET';
}

if (!function_exists('ensureDirectoryExists')) {
    function ensureDirectoryExists($directory){
        if (!file_exists($directory)) {
            if (!mkdir($directory, 0755, true)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('session')) {
    function session($key = null, $value = null) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (is_array($key)) {
            $_SESSION = array_merge($_SESSION, $key);
            return $_SESSION;
        } elseif ($key && $value) {
            $_SESSION[$key] = $value; return $_SESSION[$key];
        } elseif ($key) {
            return $_SESSION[$key] ?? null;
        }
        return $_SESSION;
    }
}

if (!function_exists('ROOT')) {
    function ROOT($path){
        return ROOT."/$path";
    }
}




if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        $token = csrf_token();
        return '<input type="hidden" name="csrf_token" value="'.$token.'">';
    }
}

if (!function_exists('validate_csrf')) {
    function validate_csrf($token) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('oldValue')) {
    function oldValue($key, $default = null) {
        return $_POST[$key] ?? $default;
    }
}

if (!function_exists('sanitize')) {
    function sanitize($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('cleanupOldFiles')) {
    function cleanupOldFiles($baseDir, $maxAge){
        foreach (glob($baseDir . '*', GLOB_ONLYDIR) as $userDir) {
            if (basename($userDir) !== session_id()) {
                continue;
            }

            foreach (glob($userDir . '/*') as $file) {
                if (filemtime($file) < time() - $maxAge) {
                    unlink($file);
                    if (count(glob($userDir . '/*')) === 0) {
                        rmdir($userDir);
                    }
                }
            }
        }
    }
}

if (!function_exists('respondWithError')) {
    function respondWithError($message) {
        global $response;
        $response['message'] = $message;
        echo json_encode($response);
        exit;
    }
}
if (!function_exists('handleZip')) {
    function handleZip($folder, &$response)
{
    $zipName = trim($_POST['zipName']) != '' ? $_POST['zipName'] : 'newzip';
    $zipName = preg_replace('/[^a-zA-Z0-9 -_]/', '', $zipName) ?: 'newzip';

    $zipName = $folder . $zipName . '.zip';

    $newfiles = array();
    foreach ($_FILES as $file_array) {
        if ($file_array['error'] == UPLOAD_ERR_OK) {
            if ($file_array['size'] > 0) {
                move_uploaded_file($file_array['tmp_name'], $folder . $file_array['name']);
                $newfiles[] = $folder . $file_array['name'];
            } else {
                $response['message'] = 'Error: File is empty.';
                return;
            }
        }
    }

    $zip = new ZipArchive();
    if ($zip->open($zipName, ZipArchive::CREATE) === TRUE) {
        foreach ($newfiles as $key => $newfile) {
            $zip->addFile($newfile, basename($newfile));
        }
        if ($zip->close()) {
            $response['success'] = true;
            $response['message'] = 'Zip file created successfully.';
            foreach ($newfiles as $key => $newfile) {
                unlink($newfile);
            }
        } else {
            $response['message'] = 'Error creating zip file.';
        }
    } else {
        $response['message'] = 'Error creating zip file.';
    }
}
}
if (!function_exists('convertFile')) {
    
// Function to convert files
function convertFile($filename, $type, $convertTo)
{
    if (!file_exists($filename)) return false;

    $pathInfo = pathinfo($filename);
    $newExtension = str_replace('image/', '', $convertTo);
    $newExtension = ($newExtension === 'vnd.microsoft.icon') ? 'ico' : $newExtension;
    $newFileName = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '.' . $newExtension;

    try {
        if (in_array($convertTo, ['application/pdf', 'image/tiff', 'image/ico'])) {
            // Advanced conversions using Imagick
            $imagick = new Imagick($filename);
            $imagick->setImageFormat($newExtension);

            if ($convertTo === 'application/pdf') {
                $imagick->setResolution(300, 300);
            } elseif ($convertTo === 'image/tiff') {
                $imagick->setImageCompression(Imagick::COMPRESSION_LZW);
            }

            $imagick->writeImage($newFileName);
            $imagick->clear();
            unlink($filename); // Remove the original file
            return true;
        } else {
            // Standard conversions using GD library
            $image = match ($type) {
                'image/jpeg' => imagecreatefromjpeg($filename),
                'image/png' => imagecreatefrompng($filename),
                'image/webp' => imagecreatefromwebp($filename),
                'image/gif' => imagecreatefromgif($filename),
                'image/bmp' => imagecreatefrombmp($filename),
                default => false
            };

            if (!$image) return false;

            $success = match ($convertTo) {
                'image/jpeg' => imagejpeg($image, $newFileName),
                'image/png' => imagepng($image, $newFileName),
                'image/webp' => imagewebp($image, $newFileName),
                'image/gif' => imagegif($image, $newFileName),
                'image/bmp' => imagebmp($image, $newFileName),
                default => false
            };

            imagedestroy($image);
            unlink($filename); // Remove the original file
            return $success;
        }
    } catch (Exception $e) {
        error_log("Conversion error: " . $e->getMessage());
        return false;
    }
}


}
if(!function_exists('resize_images')) {
    /**
 * Resize an image to a specific width and height or maintain aspect ratio.
 * 
 * @param string $filename The file name of the image to resize
 * @param int|null $new_width The new width of the image (optional)
 * @param int|null $new_height The new height of the image (optional)
 * @param int $max_size The maximum size for maintaining aspect ratio (optional)
 * 
 * @return string The filename of the resized image
 */
function resize_images($filename, $new_width = null, $new_height = null, $max_size = 700)
{
    // Get the type of the file
    $type = mime_content_type($filename);

    // Check if the file exists
    if (file_exists($filename)) {
        // Load the image based on its MIME type
        switch ($type) {
            case 'image/png':
                $image = imagecreatefrompng($filename);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($filename);
                break;
            case 'image/jpeg':
                $image = imagecreatefromjpeg($filename);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($filename);
                break;
            default:
                return $filename; // Unsupported image type
        }

        // Get the original width and height
        $width = imagesx($image);
        $height = imagesy($image);

        // Determine new dimensions
        if ($new_width && $new_height) {
            // Use provided width and height
            $resize_width = $new_width;
            $resize_height = $new_height;
        } else {
            // Maintain aspect ratio
            if ($width > $height) { // Landscape
                $resize_width = $max_size;
                $resize_height = $height * ($resize_width / $width);
            } else { // Portrait
                $resize_height = $max_size;
                $resize_width = $width * ($resize_height / $height);
            }
        }

        // Create a new blank image with the new dimensions
        $new_image = imagecreatetruecolor($resize_width, $resize_height);

        // Preserve transparency for PNG and GIF
        if ($type == 'image/png' || $type == 'image/gif') {
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
        }

        // Resample the original image into the new image
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $resize_width, $resize_height, $width, $height);

        // Save the resized image
        switch ($type) {
            case 'image/png':
                imagepng($new_image, $filename, 8);
                break;
            case 'image/gif':
                imagegif($new_image, $filename);
                break;
            case 'image/jpeg':
                imagejpeg($new_image, $filename, 90);
                break;
            case 'image/webp':
                imagewebp($new_image, $filename, 90);
                break;
            default:
                imagejpeg($new_image, $filename, 90);
                break;
        }

        // Free memory
        imagedestroy($image);
        imagedestroy($new_image);
    } else {
        return $filename; // File does not exist
    }
}

}

if (!function_exists('cropImage')) {
    function cropImage($response, $cropDir, $uploadDir) {
        foreach ($_FILES as $file_array) {
            if ($file_array['error'] == UPLOAD_ERR_OK) {
                if ($file_array['size'] > 0) {
                    $filename = $uploadDir . $file_array['name'];
                    move_uploaded_file($file_array['tmp_name'], $filename);
                } else {
                    $response['message'] = 'Error: File is empty.';
                    return;
                }
            }
        }
        // APP('filename', $filename);
        if (!file_exists($filename)) {
            $response['message'] = 'Source file does not exist.';
            return;
        }

        $type = mime_content_type($filename);
        $image = match ($type) {
            'image/png' => imagecreatefrompng($filename),
            'image/gif' => imagecreatefromgif($filename),
            'image/jpeg' => imagecreatefromjpeg($filename),
            'image/webp' => imagecreatefromwebp($filename),
            default => null,
        };

        if (!$image) {
            $response['message'] = 'Unsupported image type.';
            return;
        }
        $destinationWidth = 500;
        $destinationHeight = 500;
        $x = isset($_POST['x']) ? (int)$_POST['x'] : 0;
        $y = isset($_POST['y']) ? (int)$_POST['y'] : 0;
        $sourceWidth = isset($_POST['width']) ? (int)$_POST['width'] : $destinationWidth;
        $sourceHeight = isset($_POST['height']) ? (int)$_POST['height'] : $destinationHeight;

        $destination = imagecreatetruecolor($destinationWidth, $destinationHeight);
        if (!$destination) {
            imagedestroy($image);
            $response['message'] = 'Failed to create destination image.';
            return;
        }

        if ($type === 'image/png') {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
        }

        if (!imagecopyresampled($destination, $image, 0, 0, $x, $y, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight)) {
            imagedestroy($image);
            imagedestroy($destination);
            $response['message'] = 'Failed to resample the image.';
            return;
        }

        $saveResult = match ($type) {
            'image/png' => imagepng($destination, $filename),
            'image/gif' => imagegif($destination, $filename),
            'image/jpeg' => imagejpeg($destination, $filename),
            'image/webp' => imagewebp($destination, $filename),
            default => false,
        };
        $pathInfo = pathinfo($filename);
        $newFilename = $cropDir . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '_crop_' . rand(1000, 9999) . '.' . $pathInfo['extension'];
        rename($filename, $newFilename);
        $filename = $newFilename;

        // imagedestroy($image);
        imagedestroy($destination);

        if ($saveResult) {
            $response['success'] = true;
            $response['message'] = 'Image cropped successfully.';
            $response['filename'] = $filename.'?' . rand(0, 100);
            session('cropFilename', $filename);
        } else {
            $response['message'] = 'Failed to save the cropped image.';
        }
    }
}

if (!function_exists('handleDelete')) {
    function handleDelete(&$response) {
        $file = $_POST['filename'] ?? null;

        if ($file && file_exists($file)) {
            unlink($file);
            $response['success'] = true;
            $response['message'] = 'File deleted successfully.';
        } else {
            $response['message'] = 'File does not exist.';
        }
    }
}

function escape_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}