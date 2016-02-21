<?php

namespace spec\UsersApp\Infrastructure\Authentication;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use UsersApp\Domain\User as DomainUser;
use Symfony\Component\Security\Core\User\User as SymfonyCoreUser;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use UsersApp\Domain\UserRepository;
use UsersApp\Infrastructure\Authentication\AuthenticationUser;
use UsersApp\Infrastructure\Authentication\CustomUserProvider;

/** @mixin CustomUserProvider */
class CustomUserProviderSpec extends ObjectBehavior
{
    function let(UserRepository $userRepository)
    {
        $this->beConstructedWith($userRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CustomUserProvider::class);
    }

    function it_implements_user_interface()
    {
        $this->shouldBeAnInstanceOf(UserProviderInterface::class);
    }

    function it_supports_authentication_user_class()
    {
        $this->supportsClass(AuthenticationUser::class)->shouldBe(true);
        $this->supportsClass(SymfonyCoreUser::class)->shouldBe(false);
        $this->supportsClass(DomainUser::class)->shouldBe(false);
    }

    function it_throws_exception_if_user_not_found_when_loads_by_username(UserRepository $userRepository)
    {
        $username = 'username';
        $userRepository->userOfUsername($username)->willReturn(null);
        $this->shouldThrow(UsernameNotFoundException::class)->during('loadUserByUsername', [$username]);
    }

    function it_loads_by_username(AuthenticationUser $user, UserRepository $userRepository)
    {
        $username = 'username';
        $userRepository->userOfUsername($username)->willReturn($user);
        $this->loadUserByUsername($username)->shouldBe($user);
    }

    function it_throws_exception_if_user_not_supported_when_refresh_user(UserInterface $user)
    {
        $this->shouldThrow(UnsupportedUserException::class)->during('refreshUser', [$user]);
    }

    function it_refresh_user(AuthenticationUser $user, UserRepository $userRepository)
    {
        $username = 'username';
        $user->getUsername()->willReturn($username);
        $userRepository->userOfUsername($username)->willReturn($user);

        $this->refreshUser($user);
    }
}