<?php

namespace App\User\Presentation\Web\Controllers;

use App\Shared\Ui\Web\Controllers\FrontendController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends FrontendController
{
    /**
     * @Route("/profile", name="users_profile")
     *
     * @return Response
     */
    public function profile()
    {
        $user = $this->userFetcher->getUser();

        return $this->render('frontend/users/profile.html.twig', [
            'email' => $user->getEmail(),
        ]);
    }
}