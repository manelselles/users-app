<?php

namespace UsersApp\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use UsersApp\Domain\User;
use UsersApp\Domain\UserRepository;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{
    /**
     * @return User[]
     */
    public function all()
    {
        return $this->findAll();
    }

    /**
     * @param $username
     * @return User
     */
    public function userOfUsername($username)
    {
        return $this->findOneBy(['username' => $username]);
    }

    /**
     * @param User $user
     */
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush($user);
    }
}