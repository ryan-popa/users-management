<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users", name="create_user", methods={"POST"})
     */
    public function createUser(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setName($data['name']);
        $user->setBlocked(false);

        $this->userRepository->save($user, true);

        return $this->json($user);
    }

    /**
     * @Route("/users/{id}", name="get_user", methods={"GET"})
     */
    public function getUserById($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->json($user);
    }

    /**
     * @Route("/users", name="list_users", methods={"GET"})
     */
    public function listUsers(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->json($users);
    }

    /**
     * @Route("/users/{id}/block", name="block_user", methods={"PUT"})
     */
    public function blockUser($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setBlocked(true);

        $entityManager = $this->userRepository->getManager();
        $entityManager->flush();

        return $this->json($user);
    }
}