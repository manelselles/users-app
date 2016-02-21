<?php

namespace UsersApp\Domain;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function all();

    /**
     * @param $username
     * @return User|null
     */
    public function userOfUsername($username);

    /**
     * @param User $user
     */
    public function save(User $user);
}