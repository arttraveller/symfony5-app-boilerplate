<?php

namespace App\Auth\Presentation\Web\Controllers;

use App\Auth\Application\Commands\PasswordReset\PasswordResetCommand;
use App\Auth\Application\Commands\PasswordReset\PasswordResetHandler;
use App\Auth\Application\Commands\PasswordReset\RequestPasswordResetCommand;
use App\Auth\Application\Commands\PasswordReset\RequestPasswordResetHandler;
use App\Shared\Exceptions\ResetTokenAlreadyRequestedException;
use App\Shared\Exceptions\UserNotActiveException;
use App\Shared\Ui\Web\Controllers\FrontendController;
use App\Auth\Presentation\Web\Forms\PasswordResetForm;
use App\Auth\Presentation\Web\Forms\RequestPasswordResetForm;
use App\Shared\Exceptions\UserNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordResetController extends FrontendController
{
    /**
     * @Route("/password/reset", name="auth_password_reset_request")
     * @param Request $request
     * @param RequestPasswordResetHandler $handler
     * @return Response
     */
    public function request(Request $request, RequestPasswordResetHandler $handler): Response
    {
        $command = new RequestPasswordResetCommand();
        $form = $this->createForm(RequestPasswordResetForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'The email with further instructions was sent to the submitted email address.');
            } catch (UserNotFoundException $exc) {
                $this->addFlash('error', 'Sorry, user not found.');
            } catch (UserNotActiveException $exc) {
                $this->addFlash('error', 'Please confirm your email first.');
            } catch (ResetTokenAlreadyRequestedException $exc) {
                $this->addFlash('error', 'Reset token was already requested.');
            }

            return $this->redirectToRoute('mainpage');
        }

        return $this->render('frontend/auth/passwordReset/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/password/reset/{resetToken}", name="auth_password_reset_reset")
     * @param string $resetToken
     * @return Response
     */
    public function reset(string $resetToken, Request $request, PasswordResetHandler $handler): Response
    {
        $command = new PasswordResetCommand($resetToken);
        $form = $this->createForm(PasswordResetForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);
            $this->addFlash('success', 'Password was successfully changed.');
            return $this->redirectToRoute('mainpage');
        }

        return $this->render('frontend/auth/passwordReset/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}