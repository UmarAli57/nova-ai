<?php 

/*
 * ======================================================= 
 *  Config file for the project
 * ======================================================= 
*/

use App\Controller\Controller;

if (!defined("BASE_URL")){
    define("BASE_URL", env("BASE_URL"));
}

if (!defined("BASE_PATH")){
    define("BASE_PATH", env("BASE_PATH"));
}

return [
    // Base root url
    "base_url" => BASE_URL,
    
    
    // Base root url
    "base_path" => BASE_PATH,


    // Base controller inherit by multiple controller classes
    "base_controller" => Controller::class,


    // If method not pass by defining the controller route 
    // than default method be used.
    "default_method" => "index",

    
    // View page path
    "view_path" => __DIR__ . "/../pages",


    // Public (assets) folder default and standard path
    "public_path" => __DIR__ . "/../public",


    // Define which type of route parameteric binding will be used by default
    "parameter_binding" => true,


    // Redirect user to page a/c to their type
    "redirect_to" => [
        "user" => "/dashboard",
        "guest" => "/auth/login",
    ],
];



?>