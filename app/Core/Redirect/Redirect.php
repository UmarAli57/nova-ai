<?php 

namespace App\Core\Redirect;

use App\Core\Exceptions\InvalidRouteException;
use App\Core\Routing\RouteCollection;
use App\Core\Routing\Router;

class Redirect {
    protected ?RouteCollection $collection = null;

    public function __construct()
    {
        if ($this->collection === null){
            $this->collection = Router::getRouterInstance()->collection;
        }
    }


    public function to(string $url, array $with = [])
    {
        header("Location: " . generateFullURL($url) . "?". http_build_query($with), true, 302);
        exit;
    }
    

    public function route(string $name, array $with = []): void
    {
        $route = $this->collection->findRouteThroughName($name);

        if ($route == null){
            throw new InvalidRouteException("Name route not defined.");
        }

        $this->to($route['url'], $with);
    }


    public function back(): void
    {
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
        $this->to($referer, []);
    }
    
    
    public function view(string $pagePath, array $with = []): void
    {
        view($pagePath, $with);
    }
}


?>