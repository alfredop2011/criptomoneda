<?php
namespace App\Api;



class Router
{

    /**
     * Devuelve información de la petición recibida
     */

    public static function get()
    {
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            return ["method" => $_SERVER['REQUEST_METHOD'], "uri" => str_replace('/backend/index.php/', '', $_SERVER['REQUEST_URI'])];
        }elseif($_SERVER['REQUEST_METHOD'] == "GET"){
            $vars = strpos($_SERVER['REQUEST_URI'], "?");
            if($vars !== false){
                $url = substr($_SERVER['REQUEST_URI'], 0, $vars);
                return ["method" => $_SERVER['REQUEST_METHOD'], "uri" => str_replace('/backend/index.php/', '', $url)];
            }else{
                return ["method" => $_SERVER['REQUEST_METHOD'], "uri" => str_replace('/backend/index.php/', '', $_SERVER['REQUEST_URI'])];
            }
            
        }
    }

    /**
     * Devuelve los parámetros obtenidos en la petición
     * 
     * @param String $method    Método de la petición
     * 
     * @return StdClass
     */
    public static function params($method)
    {
        switch($method){
            case "POST":
                $params = [];
                $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

                if ($contentType === "application/json") {

                    $content = trim(file_get_contents("php://input"));
                    $params = json_decode($content);
                    $obj_name = array_keys(get_object_vars($params))[0];

                    return $params->{$obj_name};
                }

            break;
            case "GET":
                return $_GET;
            break;
        }
    }


    public static function go($request, $params, $db){
        $routes = require_once 'web/routes.php';
        $controllers = require_once 'app/Core/controllers.php';
        $route = $routes[$request["method"]][$request["uri"]];
        $controller = $controllers[$route["controller"]].'\\'.$route["controller"];
        $action = new $controller($db);

        $action->{$route["method"]}($params);
    }
}
