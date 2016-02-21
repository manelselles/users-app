<?php

namespace spec\UsersApp\Application;

use PhpSpec\ObjectBehavior;
use UsersApp\Application\CreateUser;
use UsersApp\Domain\UserRepository;
use UsersApp\Infrastructure\Authentication\AuthenticationUser;
use UsersApp\Infrastructure\Authentication\PasswordEncoder;

/** @mixin CreateUser */
class CreateUserSpec extends ObjectBehavior
{
    function let(UserRepository $userRepository, PasswordEncoder $passwordEncoder)
    {
        $this->beConstructedWith($userRepository, $passwordEncoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CreateUser::class);
    }

    function it_should_throw_exception_if_invalid_username()
    {
        $this->shouldThrow(\Exception::class)->during('create', ['', 'password']);
    }

    function it_should_throw_exception_if_invalid_password()
    {
        $this->shouldThrow(\Exception::class)->during('create', ['username', '']);
    }

    function it_should_throw_exception_if_user_already_exists(UserRepository $userRepository, AuthenticationUser $user)
    {
        $username = "username";
        $password = "password";

        $userRepository->userOfUsername($username)->willReturn($user);

        $this->shouldThrow(\Exception::class)->during('create', [$username, $password]);
    }

    function it_creates_user_with_encrypted_password(UserRepository $userRepository, PasswordEncoder $passwordEncoder)
    {
        $username = "username";
        $password = "password";
        $encodedPassword = "asdasdasfeirjw45345wefdfw";
        $user = AuthenticationUser::create($username, $encodedPassword);

        $userRepository->userOfUsername($username)->willReturn(null);
        $passwordEncoder->encode(AuthenticationUser::class, $password)->willReturn($encodedPassword);

        $userRepository->save($user)->shouldBeCalled();

        $this->create($username, $password);
    }
}