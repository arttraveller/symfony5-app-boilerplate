<?php

namespace App\Auth\Application\Commands\PasswordReset;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordResetCommand
{
    /**
     * @Assert\NotBlank
     */
    public string $token;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=8, max=200)
     */
    public string $password = '';


    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
