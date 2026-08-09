<?php 

namespace App\Core\Routing;

class RouteDefinition {
    protected Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function name(string $nameRoute)
    {
        $this->router->name($nameRoute);
        return $this;
    }

    public function generate(): void
    {
        $fallback = $this->router->getFallback();

        if (!empty($fallback)){
            $this->router->collection->setFallback($fallback);
        } else{
            $route = $this->router->getRouterParameters();
            $this->router->collection->add($route);
        }
        $this->router->resetRouterParameters();
    }
}


?>