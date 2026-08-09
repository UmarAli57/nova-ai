<?php 

namespace App\Support;

use App\Core\Session\Flash;
use App\Core\Session\Session as CoreSession;

class Session {
    public static function all()
    {
        return CoreSession::getSessionInstance()->all();
    }

    public static function set(string $key, mixed $value)    
    {
        return CoreSession::getSessionInstance()->set($key, $value);
    }
    
    public static function get(string $key, mixed $default = null)    
    {
        return CoreSession::getSessionInstance()->get($key, $default);
    }

    public static function user(): ?array
    {
        return self::get("user", default: null);
    }

    public static function has(string $key)
    {
        return CoreSession::getSessionInstance()->has($key);
    }

    public static function flash()    
    {
        return (new Flash);
    }

    public static function delete(string ...$keys)
    {
        return CoreSession::getSessionInstance()->delete(...$keys);
    }

    public static function destroy()
    {
        return CoreSession::getSessionInstance()->destroy();
    }
}


?>