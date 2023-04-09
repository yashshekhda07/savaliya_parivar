<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use CodeIgniter\Config\DotEnv;
use Config\Autoload;
use Config\Modules;
use Config\Paths;
use Config\Services;

/*
 * ---------------------------------------------------------------
 * SETUP OUR PATH CONSTANTS
 * ---------------------------------------------------------------
 *
 * The path constants provide convenient access to the folders
 * throughout the application. We have to setup them up here
 * so they are available in the config files that are loaded.
 */

// The path to the application directory.
if (! defined('APPPATH'))
{
	/**
	 * @var Paths $paths
	 */
	define('APPPATH', realpath(rtrim($paths->appDirectory, '\\/ ')) . DIRECTORY_SEPARATOR);
}

// The path to the project root directory. Just above APPPATH.
if (! defined('ROOTPATH'))
{
	define('ROOTPATH', realpath(APPPATH . '../') . DIRECTORY_SEPARATOR);
}

// The path to the system directory.
if (! defined('SYSTEMPATH'))
{
	/**
	 * @var Paths $paths
	 */
	define('SYSTEMPATH', realpath(rtrim($paths->systemDirectory, '\\/ ')) . DIRECTORY_SEPARATOR);
}

// The path to the writable directory.
if (! defined('WRITEPATH'))
{
	/**
	 * @var Paths $paths
	 */
	define('WRITEPATH', realpath(rtrim($paths->writableDirectory, '\\/ ')) . DIRECTORY_SEPARATOR);
}

// The path to the tests directory
if (! defined('TESTPATH'))
{
	/**
	 * @var Paths $paths
	 */
	define('TESTPATH', realpath(rtrim($paths->testsDirectory, '\\/ ')) . DIRECTORY_SEPARATOR);
}

/*
 * ---------------------------------------------------------------
 * GRAB OUR CONSTANTS & COMMON
 * ---------------------------------------------------------------
 */
if (! defined('APP_NAMESPACE'))
{
	require_once APPPATH . 'Config/Constants.php';
}

// Require app/Common.php file if exists.
if (is_file(APPPATH . 'Common.php'))
{
	require_once APPPATH . 'Common.php';
}

// Require system/Common.php
require_once SYSTEMPATH . 'Common.php';

/*
 * ---------------------------------------------------------------
 * LOAD OUR AUTOLOADER
 * ---------------------------------------------------------------
 *
 * The autoloader allows all of the pieces to work together in the
 * framework. We have to load it here, though, so that the config
 * files can use the path constants.
 */

if (! class_exists('Config\Autoload', false))
{
	require_once SYSTEMPATH . 'Config/AutoloadConfig.php';
	require_once APPPATH . 'Config/Autoload.php';
	require_once SYSTEMPATH . 'Modules/Modules.php';
	require_once APPPATH . 'Config/Modules.php';
}

require_once SYSTEMPATH . 'Autoloader/Autoloader.php';
require_once SYSTEMPATH . 'Config/BaseService.php';
require_once SYSTEMPATH . 'Config/Services.php';
require_once APPPATH . 'Config/Services.php';

// Use Config\Services as CodeIgniter\Services
if (! class_exists('CodeIgniter\Services', false))
{
	class_alias('Config\Services', 'CodeIgniter\Services');
}

// Initialize and register the loader with the SPL autoloader stack.
Services::autoloader()->initialize(new Autoload(), new Modules())->register();

// Now load Composer's if it's available
if (is_file(COMPOSER_PATH))
{
	/*
	 * The path to the vendor directory.
	 *
	 * We do not want to enforce this, so set the constant if Composer was used.
	 */
	if (! defined('VENDORPATH'))
	{
		define('VENDORPATH', realpath(ROOTPATH . 'vendor') . DIRECTORY_SEPARATOR);
	}

	require_once COMPOSER_PATH;
}

function isFileWritable($path)
{
	$writable_file = (file_exists($path) && is_writable($path));
	$writable_directory = (!file_exists($path) && is_writable(dirname($path)));

	if ($writable_file || $writable_directory) {
			return true;
	}
	return false;
}

function validatecode($id, $buyer, $sold_at, $code, $url){
    
	$data = array(
	 'app_id' => '43400647',
	 'email' => 'admin@gmail.com',
	 'date' => $sold_at,
	 'purchase_code' => 'xxxxxx',
	 'purchase_from' => "codecanyon",
	 'domain' => $url
 );
 //save validated data
 $payload = json_encode($data);
 //$url = 'https://validate.envisionapps.net/churchappro';
 // Collection object
 // Initializes a new cURL session
 $curl = curl_init($url);
 // Set the CURLOPT_RETURNTRANSFER option to true
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
 // Set the CURLOPT_POST option to true for POST request
 curl_setopt($curl, CURLOPT_POST, true);
 // Set the request data as JSON using json_encode function
 curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode(array("data"=>$data)));
 // Set custom headers for RapidAPI Auth and Content-Type header
 curl_setopt($curl, CURLOPT_HTTPHEADER, [
	 'Content-Type: application/json'
 ]);
 // Execute cURL request with all previous settings
 $response = curl_exec($curl);
 //ch_error($ch);
 // Close cURL session
 curl_close($curl);
 //var_dump($response); die;

 // Parse the response into an object with warnings supressed
 $body = json_decode($response);
 // Check for errors while decoding the response (PHP 5.3+)

 //var_dump($body); die;
 $status = 'success';
 //$msg = $body->message;
 if($status == "success"){
	 $path = __DIR__.DIRECTORY_SEPARATOR."Config/apponiel";
 	if (!file_exists($path)) {
 			mkdir($path, 0777, true);
 	}
 	$file_name =  $path."/da.txt";
 	if(!isFileWritable($file_name)){
     chmod($file_name, 0777);
 	}
	 $myfile = fopen($file_name, "w");
	 $txt = $body->domain."\n";
	fwrite($myfile, $txt);
	$txt = $body->purchase_code."\n";
	fwrite($myfile, $txt);
	fclose($myfile);
 }
}

// Load environment settings from .env files into $_SERVER and $_ENV
require_once SYSTEMPATH . 'Config/DotEnv.php';





//echo "hello world"; die;
// Always load the URL helper, it should be used in most of apps.
helper('url');

/*
 * ---------------------------------------------------------------
 * GRAB OUR CODEIGNITER INSTANCE
 * ---------------------------------------------------------------
 *
 * The CodeIgniter class contains the core functionality to make
 * the application run, and does all of the dirty work to get
 * the pieces all working together.
 */

$app = Services::codeigniter();
$app->initialize();

return $app;
