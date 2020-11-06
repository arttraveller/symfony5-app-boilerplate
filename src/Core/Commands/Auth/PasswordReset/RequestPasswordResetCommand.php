<?php

namespace App\Core\Commands\Auth\PasswordReset;

use Symfony\Component\Validator\Constraints as Assert;

class RequestPasswordResetCommand
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max=255)
     */
    public string $email = '';
}
