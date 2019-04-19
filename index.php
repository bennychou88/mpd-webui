<?php
require __DIR__.'/vendor/autoload.php';

$methodCall = 'index';
$defaultController = 'player';

$path = filter_input(INPUT_SERVER, 'PATH_INFO', FILTER_SANITIZE_URL);
$segments = explode('/', $path);
$controller = ucfirst($segments[1]);
$params = array_slice($segments, 2);

if(!isset($controller) || empty($controller)){
   $controller = ucfirst($defaultController); 
}

$class_file = __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.$controller.'.php';
if(file_exists($class_file)){
    include $class_file;
    $controller = "\\Controllers\\".$controller;
    $controllerInstance = new $controller();
    // params[0] could be a method call, check if a function exists
    if(count($params) == 0 || empty($params[0])){
        if (method_exists($controllerInstance, $methodCall)) {
            call_user_func(array($controllerInstance, $methodCall));
            exit();
        }else{
            header("HTTP/1.0 404 Not Found");
            echo '404 Cannot find method '.$methodCall;
            exit();
//            sendErrorPage(404);
            //sendResponse(404, 'Cannot find method '.$methodCall);
        }
    }else{
        if($params[0] && is_numeric($params[0])){
            if (method_exists($controllerInstance, $methodCall)) {
                call_user_func_array(array($controllerInstance, $methodCall), $params);
                exit();
            }else{
                header("HTTP/1.0 404 Not Found");
                echo '404 Cannot find method '.$methodCall;
                exit();
                //sendResponse(404, 'Cannot find method '.$methodCall);
            }
        }else{
            // First check if a method exists or else send this as a param to index method
            if(count($params) > 0 && method_exists($controllerInstance, $params[0])){
                $actual_params = array_slice($params, 1);
                call_user_func_array(array($controllerInstance, $params[0]), $actual_params);
                exit();
            }else{
                header("HTTP/1.0 404 Not Found");
                echo '404 Cannot find method '.$params[0];
                exit();
                //sendResponse(404, 'Cannot find method '.$methodCall);
            }
        }
    }
}else{
    header("HTTP/1.0 404 Not Found");
    echo '404 Cannot find method '.$methodCall;
    exit();
    //sendResponse(404, 'Cannot find controller '.$methodCall);
}

function getView($view, $data = '', $str = false) {
    extract($data);
    require 'assets'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$view.'.php';
}