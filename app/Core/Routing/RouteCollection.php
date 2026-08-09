<?php 

namespace App\Core\Routing;

use Closure;

class RouteCollection {
    protected array $routes = [];

    protected Closure|array $fallback = [];

    public function setFallback(Closure|array $fallback): void
    {
        $this->fallback = $fallback;
    }

    public function fallback(): array|Closure
    {
        return $this->fallback;
    }

    public function add(array $route): void
    {
        $this->routes[] = $route;
    }

    public function all(): array
    {
        return $this->routes;
    }

    public function exists(string $url, string $method): bool
    {
        return (bool) $this->findRoute($url, $method);
    }

    public function existsNameRoute(string $name): bool
    {
        foreach ($this->routes as $route){
            if ($route['name'] === $name){
                return true;
            }
        }
        return false;
    }

    public function findRoute(string $url, string $method): ?array
    {
        foreach ($this->routes as $route){
            if (
                (strtolower($url) === strtolower($route['url'])) &&
                (strtolower($method) === strtolower($route['method']))
            ){
                return $route;
            }
        }
        return null;
    }

    public function findRouteThroughName(string $name): ?array
    {
        foreach ($this->routes as $route){
            if ($route['name'] == $name){
                return $route;
            }
        }
        return null;
    }
}



?>