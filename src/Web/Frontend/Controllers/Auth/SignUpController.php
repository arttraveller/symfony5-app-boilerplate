<?php

namespace App\Web\Frontend\Controllers\Auth;

use App\Core\Commands\Auth\SignUp\SignUpCommand;
use App\Core\Commands\Auth\SignUp\SignUpHandler;
use App\Web\Forms\Auth\SignUpForm;
use App\Web\Frontend\Controllers\FrontendController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends FrontendController
{
    private LoggerInterface $logger;


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @Route("/signup", name="auth_signup")
     * @param Request $request
     * @param SignUpHandler $handler
     * @return Response
     */
    public function signUp(Request $request, SignUpHandler $handler): Response
    {
        $command = new SignUpCommand();
        $form = $this->createForm(SignUpForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);
            $this->addFlash('success', 'Registration completed successfully. A confirmation link has been sent to your email.');

            return $this->redirectToRoute('mainpage');
        }

        return $this->render('frontend/auth/signUp/signUp.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}