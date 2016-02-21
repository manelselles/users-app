<?php

namespace UsersApp\Infrastructure\Ui\Http;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig_Environment;
use UsersApp\Application\CreateUser;
use UsersApp\Domain\UserRepository;

class IndexController extends Controller
{
    /**
     * @var Twig_Environment
     */
    private $engine;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var CreateUser
     */
    private $createUser;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGeneratorInterface;

    public function __construct(
        Twig_Environment $engine,
        UserRepository $userRepository,
        CreateUser $createUser,
        Session $session,
        UrlGeneratorInterface $urlGeneratorInterface
    ) {
        $this->engine = $engine;
        $this->userRepository = $userRepository;
        $this->createUser = $createUser;
        $this->session = $session;
        $this->urlGeneratorInterface = $urlGeneratorInterface;
    }

    public function indexAction()
    {
        $users = $this->userRepository->all();
        $content = $this->engine->render('::index.html.twig', ['users' => $users]);

        return Response::create($content);
    }

    public function createUserAction(Request $request)
    {
        $error = '';
        $username = $request->get('username', '');
        $password = $request->get('password', '');

        if ($request->isMethod('POST')) {
            try {
                $this->createUser->create($username, $password);
                $this->session->getFlashBag()->add('success', sprintf('Created user with username %s', $username));

                return RedirectResponse::create($this->urlGeneratorInterface->generate('app_index'));
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $content = $this->engine->render('::create_user.html.twig', [
            'error' => $error,
            'username' => $username,
            'password' => $password,
        ]);

        return Response::create($content);
    }
}