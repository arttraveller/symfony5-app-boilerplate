<?php

namespace App\User\Presentation\Api\Controllers;

use App\Shared\Ui\Api\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends ApiController
{
    /**
     * @Route("profile", name="users_profile", methods={"GET"})
     * @return Response
     */
    public function profile(Request $request): Response
    {
        $user = $this->userFetcher->getUser();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }
}