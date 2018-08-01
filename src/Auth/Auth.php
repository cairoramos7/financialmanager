<?php
declare(strict_types=1);

namespace CROFin\Auth;

class Auth implements AuthInterface
{
    /**
     * @var JasnyAuth
     */
    private $jasnyAuth;

    /**
     * Auth constructor.
     * @param JasnyAuth $jasnyAuth
     */
    public function __construct(JasnyAuth $jasnyAuth)
    {
        $this->jasnyAuth = $jasnyAuth;
        $this->sessionStart();
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function login(array $credentials): bool
    {
        list('email' => $email, 'password' => $password) = $credentials;

        return $this->jasnyAuth->login($email, $password) !== null;
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        file_put_contents('session.txt', json_encode($_SESSION));
        return $this->jasnyAuth->user() !== null;
    }

    /**
     *
     */
    public function logout(): void
    {}

    /**
     * @param string $password
     * @return string
     */
    public function hashPassword(string $password): string
    {
        return $this->jasnyAuth->hashPassword($password);
    }

    protected function sessionStart() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}