<?php 

namespace App\Core\Session;

class SessionCollection {
    protected array $sessions = [];
    
    protected array $flash = [];


    public function addSession(string $key, mixed $value): void
    {
        $this->sessions[$key] = $value;
    }

    public function addFlash(string $key, mixed $value): void
    {
        $this->flash[$key] = $value;
    }

    public function allSession(): array
    {
        return $this->sessions;
    }

    public function allFlash(): array
    {
        return $this->flash;
    }

    public function existsSession(string $key): bool
    {
        return (bool) isset($this->allSession()[$key]);
    }

    public function existsFlash(string $key): bool
    {
        return (bool) isset($this->allFlash()[$key]);
    }
}



?>