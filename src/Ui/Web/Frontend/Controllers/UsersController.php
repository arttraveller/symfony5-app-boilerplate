<?php

namespace App\Ui\Web\Frontend\Controllers;

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
        return $this->render('frontend/users/profile.html.twig', [
        ]);
    }
}