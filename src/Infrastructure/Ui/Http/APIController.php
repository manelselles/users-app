<?php

namespace UsersApp\Infrastructure\Ui\Http;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use UsersApp\Domain\UserRepository;

class APIController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function indexAction()
    {
        $users = $this->userRepository->all();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'username' => $user->username(),
                'roles' => $user->roles(),
            ];
        }

        return JsonResponse::create($data);
    }
}