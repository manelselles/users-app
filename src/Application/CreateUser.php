<?php

namespace UsersApp\Application;

use UsersApp\Domain\UserRepository;
use UsersApp\Infrastructure\Authentication\AuthenticationUser;
use UsersApp\Infrastructure\Authentication\PasswordEncoder;

class CreateUser
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PasswordEncoder
     */
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository, PasswordEncoder $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function create($username, $password)
    {
        if ($username == '') {
            throw new \Exception('Invalid username length');
        }

        if ($password == '') {
            throw new \Exception('Invalid password length');
        }

        if ($this->userRepository->userOfUsername($username)) {
            throw new \Exception('User already registered');
        }

        $encodedPassword = $this->passwordEncoder->encode(AuthenticationUser::class, $password);
        $user = AuthenticationUser::create($username, $encodedPassword);
        $this->userRepository->save($user);

        return $user;
    }
}