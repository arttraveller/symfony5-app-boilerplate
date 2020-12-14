<?php

namespace App\Ui\Web\Frontend\Controllers\Auth;

use App\Domain\Commands\Auth\SignUp\ConfirmEmailCommand;
use App\Domain\Commands\Auth\SignUp\ConfirmEmailHandler;
use App\Domain\Commands\Auth\SignUp\SignUpCommand;
use App\Domain\Commands\Auth\SignUp\SignUpHandler;
use App\Exceptions\EntityNotFoundException;
use App\Ui\Web\Frontend\Forms\Auth\SignUpForm;
use App\Ui\Web\Frontend\Controllers\FrontendController;
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


    /**
     * @Route("/signup/confirm/{confirmToken}", name="auth_signup_confirm")
     * @param string $confirmToken
     * @param ConfirmEmailHandler $handler
     * @return Response
     */
    public function confirm(string $confirmToken, ConfirmEmailHandler $handler): Response
    {
        $command = new ConfirmEmailCommand($confirmToken);
        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email is successfully confirmed.');
        } catch (EntityNotFoundException $exc) {
            $this->addFlash('error', 'Sorry, user not found.');
        }

        return $this->redirectToRoute('mainpage');
    }

}