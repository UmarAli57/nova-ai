<?php 

namespace App\Support;

use App\Core\Redirect\Redirect as CoreRedirect;

class Redirect {
    public static function to(string $url, array $with = [])
    {
        return (new CoreRedirect)->to($url, $with);
    }
    
    
    public static function route(string $name, array $with = [])
    {
        return (new CoreRedirect)->route($name, $with);
    }
    

    public static function back()
    {
        return (new CoreRedirect)->back();
    }
    
    
    public static function view(string $pagePath, array $with = [])
    {
        return (new CoreRedirect)->view($pagePath, $with);
    }
}


?>