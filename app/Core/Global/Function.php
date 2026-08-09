<?php

use App\Core\Redirect\Redirect as CoreRedirect;
use App\Support\Redirect;
use App\Core\Routing\Router;
use App\Support\Session;

/**
 *  ===============================================================================  
 *      Global helpers function for the projects 
 *  ===============================================================================  
*/

const ROOT_LEVEL = 3;


function base_path(string $path = ""){
    return rtrim(
        dirname(__DIR__, ROOT_LEVEL) . "/" . ltrim($path, "/"), "/"
    );
}


function config_path(string $path = ""): string {
    return base_path("/config/" . ltrim($path, "/"));
}


function view_path(string $path = ""): string {
    return base_path("/pages/" . ltrim($path, "/"));
}


function public_path(string $path = ""): string {
    return base_path("/public/" . ltrim($path, "/"));
}


function asset(string $path): string {
    $config = require config_path("app.php");
    $base_url = $config['base_url'];

    return rtrim(
        $base_url . "/public/" . ltrim($path, "/"), "/"
    );
}


function config(string $key): mixed {
    $config = require config_path("app.php");
    return $config[strtolower($key)];
}

function env(string $key) : mixed {
    return $_ENV[strtoupper($key)] ?? null;
}

function view(string $path, array $data = []): void {
    extract($data);
    require view_path($path);
}

function route(string $name, array $query = []): ?string {
    $collection = Router::getRouterInstance()->collection;
    
    if (!$collection->existsNameRoute($name)){
        throw new Exception("Name Route <br>'$name'</b> doesn't exists.");
    }

    return generateFullURL($collection->findRouteThroughName($name)['url'], $query);
}

function redirect(string $url = "", array $with = []){
    if (empty($url)) return new CoreRedirect;
    Redirect::to($url, $with);
}

function generateFullURL(string $url, array $query = []): string {
    $strings = http_build_query($query);
    if (preg_match("/^((https?:\/\/|www\.)?[\w\.\-\_]+(\.\w{2,8})?)(\S| )*$/i", $url)){
        return $url . (empty($query) ? "" : "?" . $strings);
    } 
    elseif (preg_match("/^\/(?!.*\/\/).*$/i", $url)){
        return config("BASE_URL") . $url . (empty($query) ? "" : "?" . $strings);
    }
    return "";
}


function session(string|array $key = "", mixed $default = null){
    if (is_array($key)){
        foreach ($key as $k => $v){
            Session::set($k, $v);
        }
    } 
    elseif (!empty($key)){
        return Session::get($key, $default);
    } 
    else {
        return new Session;
    }
}

function user(): ?array {
    return Session::user();
}


?>