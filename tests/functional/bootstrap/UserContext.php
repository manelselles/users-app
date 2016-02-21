<?php

use Assert\Assertion;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use UsersApp\Application\CreateUser;
use UsersApp\Domain\UserRepository;

class UserContext extends MinkContext
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var CreateUser
     */
    private $createUser;
    /**
     * @var string
     */
    private $error;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @param UserRepository $userRepository
     * @param CreateUser $createUser
     * @param SessionInterface $session
     */
    public function __construct(UserRepository $userRepository, CreateUser $createUser, SessionInterface $session)
    {
        $this->userRepository = $userRepository;
        $this->createUser = $createUser;
        $this->session = $session;
    }

    /**
     * @When I create a user with username :username and password :password
     */
    public function iCreateAUserWithUsernameAndPassword($username, $password)
    {
        try {
            $this->user = $this->createUser->create($username, $password);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * @Then I have a user with username :username
     */
    public function iHaveAUserWithUsername($username)
    {
        Assertion::notNull($this->userRepository->userOfUsername($username));
    }

    /**
     * @Then I have an error with message :errorMessage
     */
    public function iHaveAnErrorWithMessage($errorMessage)
    {
        Assertion::eq($errorMessage, $this->error);
    }

    /**
     * @Given I am authenticated with username :username password :password
     */
    public function iAmAuthenticatedWith($username, $psw)
    {
        $this->loginWithTokenAndCookie('admin');
    }

    private function loginWithForm($username, $psw)
    {
        $this->visit('/login');
        $this->fillField('_username', $username );
        $this->fillField('_password', $psw);
        $this->pressButton('_submit_login');
    }

    private function loginWithTokenAndCookie($firewall)
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof BrowserKitDriver) {
            throw new UnsupportedDriverActionException('This step is only supported by the BrowserKitDriver', $driver);
        }

        $client = $driver->getClient();
        $client->getCookieJar()->set(new Cookie(session_name(), true));

        $token = new UsernamePasswordToken($this->user, null, $firewall, $this->user->getRoles());

        $this->session->set('_security_'.$firewall, serialize($token));
        $this->session->save();

        $cookie = new Cookie($this->session->getName(), $this->session->getId());
        $client->getCookieJar()->set($cookie);
    }
}