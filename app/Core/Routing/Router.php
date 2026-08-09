<?php 

namespace App\Core\Routing;

use Closure;
use App\Core\Exceptions\OperationNotAllowedException;

class Router {
    protected string $url = "";

    protected string $method = "";

    protected string $name = "";

    protected Closure|array $action = [];
    
    protected string $redirectTo = "";
    
    protected array $view = [];

    protected Closure|array $fallback = [];

    protected static ?self $instance = null;

    public RouteCollection $collection;

    protected RouteValidator $validator;

    
    private function __construct(){
        $this->collection = new RouteCollection;
        $this->validator = new RouteValidator;
    }


    public static function setRouterInstance(): void
    {
        if (static::$instance !== null){
            throw new OperationNotAllowedException("Unable to create a new router instance.");
        }
        static::$instance = new self;
    }

    public static function getRouterInstance(): ?self
    {
        return static::$instance;
    }

    public function request(string $url, string|array|Closure $action, string $method): RouteDefinition
    {
        $this->validator->validateURL($url);
        $this->validator->validateMethod($method);
        $this->validator->ensureRouteIsUnique($url, $method, $this->collection);

        $this->url = $url;
        $this->method = strtolower($method);

        if (is_string($action)){
            $this->action = [
                $action, config("default_method")
            ];
        } else{
            $this->action = $action;
        }

        return (new RouteDefinition($this));
    }
    
    public function get(string $url, string|array|Closure $action): RouteDefinition
    {
        return $this->request($url, $action, "get");
    }
    
    public function post(string $url, string|array|Closure $action): RouteDefinition
    {
        return $this->request($url, $action, "post");
    }
    
    public function put(string $url, string|array|Closure $action): RouteDefinition
    {
        return $this->request($url, $action, "put");
    }
    
    public function delete(string $url, string|array|Closure $action): RouteDefinition
    {
        return $this->request($url, $action, "delete");
    }
    
    public function view(string $url, string $pagePath, array $data = []): RouteDefinition
    {
        $this->validator->validateURL($url);
        $this->url = $url;
        $this->method = "get";
        $this->view = [$pagePath, $data];
        return (new RouteDefinition($this));
    }

    public function redirect(string $url, string $redirectTo): RouteDefinition
    {
        $this->validator->validateURL($url);
        $this->validator->validateURL($redirectTo);

        $this->url = $url;
        $this->redirectTo = $redirectTo;
        return (new RouteDefinition($this));
    }

    public function fallback(Closure|array $fallback): RouteDefinition
    {
        $this->fallback = $fallback;
        return (new RouteDefinition($this));
    }

    public function name(string $nameRoute): self
    {
        $this->validator->validateNameRoute($nameRoute);
        $this->validator->ensureNameIsUnique($nameRoute, $this->collection);

        $this->name = $nameRoute;
        return $this;
    }

    public function getRouterParameters(): array
    {
        return [
            "url" => $this->url,
            "method" => $this->method,
            "name" => $this->name,
            "action" => $this->action,
            "view" => $this->view,
            "redirectTo" => $this->redirectTo,
        ];
    }

    public function getFallback(): array|Closure
    {
        return $this->fallback;
    }

    public function resetRouterParameters(): void
    {
        $this->url = "";
        $this->method = "";
        $this->name = "";
        $this->action = [];
        $this->redirectTo = "";
        $this->view = [];
        $this->fallback = [];
    }
}

?>