<?php 

namespace App\Core\Session;

use App\Core\Exceptions\OperationNotAllowedException;

class Session {
    protected static ?self $instance = null;

    public SessionCollection $collection;

    public static function setSessionInstance()
    {
        if (self::$instance !== null){
            throw new OperationNotAllowedException("Unable to create a new session instance.");
        }
        self::$instance = new self;
    }

    public static function getSessionInstance(): ?self
    {
        return self::$instance;
    }

    private function __construct()
    {
        $this->collection = new SessionCollection;

        $sessions = $this->all()["session"] ?? [];
        $flash = $this->all()["flash"] ?? [];

        foreach ($sessions as $key => $val){
            $this->collection->addSession($key, $val);
        }
        
        foreach ($flash as $key => $val){
            $this->collection->addFlash($key, $val);
        }
    }

    public function set(string $key, mixed $value): void
    {
        $this->collection->addSession($key, $value);
        $_SESSION["session"][$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->collection->existsSession($key)){
            return $this->collection->allSession()[$key];
        }
        return $default;
    }

    public function delete(string ...$keys): void
    {
        foreach ($keys as $key){
            if ($this->collection->existsSession($key) && isset($_SESSION['session'][$key])){
                unset($_SESSION["session"][$key]);
            }
        }
    }

    public function destroy()
    {
        session_unset();
        session_destroy();
    }

    public function has(string $key): bool
    {
        return in_array($key, array_keys($this->collection->allSession()));
    }

    public function all()
    {
        return $_SESSION ?? [];
    }
}



?>