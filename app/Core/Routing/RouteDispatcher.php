<?php 

namespace App\Core\Routing;

use App\Core\Redirect\Redirect;
use Closure;
use ReflectionFunction;
use ReflectionMethod;
use App\Core\Exceptions\MissingParameterException;

class RouteDispatcher {
    public static function dispatch(string $url, string $method, RouteCollection $collection, array $query = [], array $files = [])
    {
        $route = $collection->findRoute($url, $method);
        $parameters = ((bool) config("parameter_binding")) ? [...$query, ...$files] : [...array_values($query), ...array_values($files)];

        if ($route == null){
            self::resolveFallback($collection, $parameters);
            return;
        }

        if (!empty($route['action'])){
            if ($route['action'] instanceof Closure){
                self::resolveClosure($route['action'], $parameters);
            } else{
                self::resolveController($route['action'], $parameters);
            }
            return;
        }

        if (!empty($route['view'])){
            [$page, $data] = $route['view'];

            self::renderView($page, $data);
            return;
        }

        if (!empty($route['redirectTo'])){
            self::handleRedirect($route['redirectTo'], $parameters);
            return;
        }
    }

    protected static function resolveController(array $controller, array $data = [])
    {
        [$class, $method] = $controller;
        
        $ref = new ReflectionMethod($class, $method);
        $params = $ref->getParameters();
        $args = [];

        if (count($data) < $ref->getNumberOfRequiredParameters()){
            throw new MissingParameterException("Missing parameters in {$class}::{$method} while calling it.");
        }
        
        if ((bool) config("parameter_binding")){
            $keys = array_keys($data);
            
            foreach ($params as $param){
                $name = $param->getName();
                
                if (!in_array($name, $keys) && $param->isOptional()){
                    continue;
                }
                elseif (!in_array($name, $keys) && !$param->isOptional()){
                    throw new MissingParameterException("Missing parameter '{$name}' in {$class}::{$method}");
                }
                elseif (in_array($name, $keys)){
                    $args[$name] = $data[$name];
                }
            }
        } 
        else{
            $min = min(count($data), $ref->getNumberOfParameters());

            for ($i = 0; $i < $min; $i++){
                $args[] = $data[$params[$i]->getName()];
            }
        }

        $obj = new $class;
        $result = $obj->$method(...$args);
        
        if ($result){
            if (is_array($result) || is_object($result)){
                echo "<pre>";
                print_r($result);
                echo "</pre>";
            } else {
                echo $result;
            }
        }
        return;
    }

    protected static function resolveClosure(Closure $callback, array $data = [])
    {
        $ref = new ReflectionFunction($callback);
        $params = $ref->getParameters();
        $args = [];

        if (count($data) < $ref->getNumberOfRequiredParameters()){
            throw new MissingParameterException("Missing parameters in Closure function while calling it.");
        }
        
        if ((bool) config("parameter_binding")){
            $keys = array_keys($data);
            
            foreach ($params as $param){
                $name = $param->getName();
                
                if (!in_array($name, $keys) && $param->isOptional()){
                    continue;
                }
                elseif (!in_array($name, $keys) && !$param->isOptional()){
                    throw new MissingParameterException("Missing parameter '{$name}' in Closure function.");
                }
                else{
                    $args[$name] = $data[$name];
                }
            }
        } 
        else{
            $min = min(count($data), $ref->getNumberOfParameters());
            
            for ($i = 0; $i < $min; $i++){
                $args[] = $data[$params[$i]->getName()];
            }
        }

        $result = $callback(...$args);

        if ($result){
            if (is_array($result) || is_object($result)){
                echo "<pre>";
                print_r($result);
                echo "</pre>";
            } else {
                echo $result;
            }
        }
        return;
    }

    protected static function handleRedirect(string $redirectTo, array $data = [])
    {
        redirect($redirectTo, $data);
        return;
    }

    protected static function renderView(string $viewPath, array $data = [])
    {
        view($viewPath, $data);
        return;
    }

    protected static function resolveFallback(RouteCollection $collection, array $data = [])
    {
        $fallback = $collection->fallback();

        if (empty($fallback)){
            echo "<pre>404 Not found</pre>";
            return;
        }

        if ($fallback instanceof Closure){
            self::resolveClosure($fallback, $data);
        } else{
            self::resolveController($fallback, $data);
        }
        return;
    }
}


?>