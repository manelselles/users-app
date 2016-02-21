<?php

namespace UsersApp\Infrastructure\Authentication;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PasswordEncoder
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function encode($userClass, $password)
    {
        return $this->encoderFactory->getEncoder($userClass)->encodePassword($password, null);
    }
}