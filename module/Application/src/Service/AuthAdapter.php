<?php
namespace Application\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

/**
 * Adapter used for authenticating user. It takes login and password on input
 * and checks the database if there is a user with such login and password.
 * If such user exists, the service returns its identity (login). The identity
 * is saved to session and can be retrieved later with Identity view helper provided
 * by ZF3.
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * User login.
     * @var string
     */
    private $login;

    /**
     * Password
     * @var string
     */
    private $password;

    /**
     * Sets user login.
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * Sets password.
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;
    }

    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {
        $login = 'admin';
        $password = 'password';

        if (($this->login == $login) && ($this->password == $password)) {
            return new Result(
                    Result::SUCCESS,
                    $this->login,
                    ['Authenticated successfully.']);
        }

        return new Result(
            Result::FAILURE_IDENTITY_NOT_FOUND,
            null,
            ['Invalid credentials.']);
    }
}
