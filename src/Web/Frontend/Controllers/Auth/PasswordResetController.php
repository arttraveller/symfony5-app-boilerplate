<?php

namespace App\Web\Frontend\Controllers\Auth;

use App\Core\Commands\Auth\PasswordReset\PasswordResetHandler;
use App\Core\Commands\Auth\PasswordReset\RequestPasswordResetCommand;
use App\Core\Commands\Auth\PasswordReset\RequestPasswordResetHandler;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\ResetTokenAlreadyRequestedException;
use App\Exceptions\UserNotActiveException;
use App\Web\Forms\Auth\RequestPasswordResetForm;
use App\Web\Frontend\Controllers\FrontendController;
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
            } catch (EntityNotFoundException $exc) {
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
        // TODO
    }

}