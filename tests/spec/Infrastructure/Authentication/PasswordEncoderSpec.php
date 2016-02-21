<?php

namespace spec\UsersApp\Infrastructure\Authentication;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use UsersApp\Infrastructure\Authentication\AuthenticationUser;
use UsersApp\Infrastructure\Authentication\PasswordEncoder;

/** @mixin PasswordEncoder */
class PasswordEncoderSpec extends ObjectBehavior
{
    function let(EncoderFactoryInterface $encoderFactory)
    {
        $this->beConstructedWith($encoderFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PasswordEncoder::class);
    }

    function it_encode_password(EncoderFactoryInterface $encoderFactory, PasswordEncoderInterface $passwordEncoder)
    {
        $password = "password";
        $encodedPassword = "asdasdasfeirjw45345wefdfw";
        $userClass = AuthenticationUser::class;

        $encoderFactory->getEncoder($userClass)->willReturn($passwordEncoder);
        $passwordEncoder->encodePassword($password, null)->willReturn($encodedPassword);

        $this->encode($userClass, $password)->shouldBe($encodedPassword);
    }

}