<?php

namespace UsersApp\Infrastructure\Authentication;

use Symfony\Component\Security\Core\User\UserInterface;
use UsersApp\Domain\User;

class AuthenticationUser extends User implements UserInterface
{
    public function getRoles()
    {
        return $this->roles();
    }

    public function getUsername()
    {
        return $this->username();
    }

    public function getPassword()
    {
        return $this->password();
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }
}