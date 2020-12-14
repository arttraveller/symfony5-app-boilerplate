<?php

namespace App\Domain\Services\Auth;

use App\Domain\Entities\User\ResetToken;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class TokenSender
{
    private MailerInterface $mailer;
    private Environment $twig;


    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }


    public function sendConfirmToken(string $toEmail, string $confirmToken): void
    {
        // TODO
        $fromEmail = $_ENV['APP_FROM_EMAIL'];
        $subject = 'Thank you for registering!';

        $email = (new TemplatedEmail())
            ->from($fromEmail)
            ->to($toEmail)
            ->subject($subject)
            ->htmlTemplate('email/auth/confirm_reg.html.twig')
            ->context([
                'confirmToken' => $confirmToken,
            ]);

        $this->mailer->send($email);
    }


    public function sendResetToken(string $toEmail, ResetToken $resetToken): void
    {
        // TODO
        $fromEmail = $_ENV['APP_FROM_EMAIL'];
        $subject = 'You requested password reset';

        $email = (new TemplatedEmail())
            ->from($fromEmail)
            ->to($toEmail)
            ->subject($subject)
            ->htmlTemplate('email/auth/password_reset.html.twig')
            ->context([
                'resetToken' => $resetToken->getToken(),
            ]);

        $this->mailer->send($email);
    }

}
