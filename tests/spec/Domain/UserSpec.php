<?php

namespace spec\UsersApp\Domain;

use PhpSpec\ObjectBehavior;
use UsersApp\Domain\User;

/** @mixin User */
class UserSpec extends ObjectBehavior
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
        $this->shouldHaveType(User::class);
    }

    function it_has_role_user()
    {
        $this->roles()->shouldBe(['ROLE_USER']);
    }

    function it_should_retrieve_its_username()
    {
        $this->username()->shouldBe($this->username);
    }

    function it_should_retrieve_its_password()
    {
        $this->password()->shouldBe($this->password);
    }

    function it_should_be_transformed_to_string()
    {
        $this->__toString()->shouldBe($this->username);
    }
}