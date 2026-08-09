<?php 

namespace App\Core\Routing;

use App\Core\Exceptions\InvalidRouteException;
use App\Core\Exceptions\MethodNotAllowedException;
use App\Core\Exceptions\RouteAlreadyExistsException;

class RouteValidator {
    protected const ALLOWED_HTTP_METHOD = [
        "GET", "POST", "PUT", "DELETE"
    ];

    public function validateURL(string $url): void
    {
        if (empty($url)){
            throw new InvalidRouteException("Route must not be empty.");
        } 
        // elseif (!preg_match("/^(\/(?!\/)[\w\.\-\_\+ ]*)+(\?[\S]*)?$/i", $url)){
        elseif (!(
            preg_match("/^((https?:\/\/|www\.)?[\w\.\-\_]+\.\w{2,8})(\S| )*$/i", $url) ||
            preg_match("/^\/(?!.*\/\/).*$/i", $url)
        )){
            throw new InvalidRouteException("Invalid URL pattern.");
        }
    }

    public function validateMethod(string $method): void
    {
        if (!in_array(strtoupper($method), self::ALLOWED_HTTP_METHOD)){
            throw new MethodNotAllowedException("Method <b>'$method'</b> not allowed.");
        }
    }

    public function validateNameRoute(string $name): void
    {
        if (!preg_match("/^[a-z0-9\.\_\-]+$/i", $name)){
            throw new InvalidRouteException("Invalid named route pattern. Name route only contains letter, integer, underscore(_), hyphen(-), and dot(.)");
        }
    }

    public function ensureRouteIsUnique(string $url, string $method, RouteCollection $collection): void
    {
        if ($collection->exists($url, $method)){
            throw new RouteAlreadyExistsException("Route already exists.");
        }
    }
    
    public function ensureNameIsUnique(string $name, RouteCollection $collection): void
    {
        if ($collection->existsNameRoute($name)){
            throw new RouteAlreadyExistsException("Name Route already exists.");
        }
    }
}



?>