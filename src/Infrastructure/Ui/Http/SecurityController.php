<?php

namespace UsersApp\Infrastructure\Ui\Http;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig_Environment;

class SecurityController extends Controller
{
    /**
     * @var Twig_Environment
     */
    private $engine;

    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    public function __construct(Twig_Environment $engine, AuthenticationUtils $authenticationUtils)
    {
        $this->engine = $engine;
        $this->authenticationUtils = $authenticationUtils;
    }

    public function loginAction()
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $content = $this->engine->render('::login.html.twig', ['error' => $error]);

        return Response::create($content);
    }
}