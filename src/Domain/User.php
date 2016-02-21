<?php

namespace UsersApp\Domain;

class User
{
    protected $username;
    protected $password;
    protected $active;

    /**
     * @param $username
     * @param $password
     * @param $active
     */
    protected function __construct($username, $password, $active)
    {
        $this->username = $username;
        $this->password = $password;
        $this->active = $active;
    }

    /**
     * @param $username
     * @param $password
     * @return User
     */
    public static function create($username, $password)
    {
        return new static($username, $password, true);
    }

    public function roles()
    {
        return ['ROLE_USER'];
    }

    public function username()
    {
        return $this->username;
    }

    public function password()
    {
        return $this->password;
    }

    public function __toString()
    {
        return (string) $this->username;
    }
}