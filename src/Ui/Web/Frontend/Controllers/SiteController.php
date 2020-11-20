<?php

namespace App\Ui\Web\Frontend\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends FrontendController
{
    /**
     * @Route("/", name="mainpage")
     *
     * @return Response
     */
    public function mainpage()
    {
        return $this->render('frontend/site/main.html.twig', [
        ]);
    }
}