<?php declare(strict_types=1);

use App\Core\Routing\RouteDispatcher;
use App\Core\Routing\Router;
use App\Core\Session\Session;
use BuildQL\Database\Query\DB;

require_once "./vendor/autoload.php";

session_start();
Router::setRouterInstance();
Session::setSessionInstance();
DB::boot();

require_once "./routes/web.php";

$base_path = config("base_path");
$curr_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$url = str_replace($base_path, "", $curr_uri);
$query = !empty($_POST) ? $_POST : $_GET;

RouteDispatcher::dispatch(
    url: $url, 
    method: $method,
    collection: Router::getRouterInstance()->collection,
    query: $query,
    files: $_FILES ?? []
);

session()->flash()->destroy();
// session()->destroy();
exit;
?>