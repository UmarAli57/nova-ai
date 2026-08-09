<?php 

namespace App\Core\Session;

use App\Core\Session\Session;

class Flash {
    protected Session $session;

    protected SessionCollection $collection;

    public function __construct()
    {
        $this->session = Session::getSessionInstance();
        $this->collection = Session::getSessionInstance()->collection;
    }
    
    public function set(string $key, mixed $value): void
    {
        $this->collection->addFlash($key, $value);
        $_SESSION['flash'][$key] = $value;
    }
    
    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->collection->existsFlash($key)){
            return $this->collection->allFlash()[$key];
        }
        return $default;
    }

    public function has(string $key): bool
    {
        return in_array($key, array_keys($this->collection->allFlash()));
    }

    public function destroy(): void
    {
        foreach ($this->collection->allFlash() as $key => $val){
            if ($this->collection->existsFlash($key) && isset($_SESSION['flash'][$key])){
                unset($_SESSION['flash'][$key]);
            }
        }
    }
}


?>