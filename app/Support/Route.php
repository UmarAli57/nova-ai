<?php 

namespace App\Support;

use App\Core\Routing\RouteDefinition;
use App\Core\Routing\Router;
use Closure;

class Route {
    public static function request(string $url, string|array|Closure $action, string $method): RouteDefinition
    {
        return Router::getRouterInstance()->request($url, $action, $method);
    }
    
    public static function get(string $url, string|array|Closure $action): RouteDefinition
    {
        return Router::getRouterInstance()->get($url, $action);
    }
    
    public static function post(string $url, string|array|Closure $action): RouteDefinition
    {
        return Router::getRouterInstance()->post($url, $action);
    }
    
    public static function put(string $url, string|array|Closure $action): RouteDefinition
    {
        return Router::getRouterInstance()->put($url, $action);
    }
    
    public static function delete(string $url, string|array|Closure $action): RouteDefinition
    {
        return Router::getRouterInstance()->delete($url, $action);
    }
    
    public static function view(string $url, string $pagePath, array $data = []): RouteDefinition
    {
        return Router::getRouterInstance()->view($url, $pagePath, $data);
    }
    
    public static function redirect(string $url, string $redirectTo): RouteDefinition
    {
        return Router::getRouterInstance()->redirect($url, $redirectTo);
    }
    
    public static function fallback(Closure|array $fallback): RouteDefinition
    {
        return Router::getRouterInstance()->fallback($fallback);
    }
    
    public static function name(string $nameRoute): Router
    {
        return Router::getRouterInstance()->name($nameRoute);
    }
}


?>