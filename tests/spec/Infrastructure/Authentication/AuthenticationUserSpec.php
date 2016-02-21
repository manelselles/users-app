<?php

namespace spec\UsersApp\Infrastructure\Authentication;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\User\UserInterface;
use UsersApp\Domain\User;
use UsersApp\Infrastructure\Authentication\AuthenticationUser;

/** @mixin AuthenticationUser */
class AuthenticationUserSpec extends ObjectBehavior
{
    private $username;
    private $password;

    function let()
    {
        $this->username = 'username';
        $this->password = 'password';
        $this->beConstructedThrough('create', [$this->username, $this->password]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AuthenticationUser::class);
    }

    function it_is_a_user()
    {
        $this->shouldHaveType(User::class);
    }

    function it_implements_user_interface()
    {
        $this->shouldBeAnInstanceOf(UserInterface::class);
    }

    function it_has_role_user()
    {
        $this->getRoles()->shouldBe($this->roles());
    }

    function it_should_retrieve_its_username()
    {
        $this->getUsername()->shouldBe($this->username());
    }

    function it_should_retrieve_its_password()
    {
        $this->getPassword()->shouldBe($this->password());
    }

    function it_does_not_use_salt()
    {
        $this->getSalt()->shouldBe(null);
    }

    function it_does_not_use_erase_credentials()
    {
        $this->eraseCredentials();
    }
}