<?php 

require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//echo $_ENV["URL"];

use App\Controllers\SQLiteConnection;
use App\Api\Router;

$request = Router::get();

$db = new SQLiteConnection();

$params = Router::params($request["method"]);
Router::go($request, $params, $db->connect());
$db->close();

