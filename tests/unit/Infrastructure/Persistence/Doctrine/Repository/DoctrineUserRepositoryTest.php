<?php

namespace Tests\UsersApp\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use UsersApp\Infrastructure\Authentication\AuthenticationUser;
use UsersApp\Infrastructure\Persistence\Doctrine\Repository\DoctrineUserRepository;

class DoctrineUserRepositoryTest extends KernelTestCase
{
    /** @var  DoctrineUserRepository */
    private $doctrineUserRepository;

    public function setUp()
    {
        self::bootKernel();
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $executor = new ORMExecutor($em, new ORMPurger());
        $executor->execute([]);
        $this->doctrineUserRepository = $em->getRepository(AuthenticationUser::class);
    }

    /**
     * @test
     */
    public function itGetsAllUsers()
    {
        $this->assertCount(0, $this->doctrineUserRepository->all());

        $this->doctrineUserRepository->save(AuthenticationUser::create('username', 'password'));

        $this->assertCount(1, $this->doctrineUserRepository->all());

        $this->doctrineUserRepository->save(AuthenticationUser::create('username1', 'password'));
        $this->doctrineUserRepository->save(AuthenticationUser::create('username2', 'password'));

        $this->assertCount(3, $this->doctrineUserRepository->all());
    }

    /**
     * @test
     */
    public function itGetsUserByUsername()
    {
        $username1 = 'username1';
        $username2 = 'username2';
        $this->doctrineUserRepository->save(AuthenticationUser::create($username1, 'password'));
        $this->doctrineUserRepository->save(AuthenticationUser::create($username2, 'password'));

        $user = $this->doctrineUserRepository->userOfUsername($username2);

        $this->assertEquals($user->username(), $username2);
    }

    /**
     * @test
     */
    public function itGetsNullWhenUserOfUsernameNotFound()
    {
        $this->assertNull($this->doctrineUserRepository->userOfUsername('no_username'));
    }
}