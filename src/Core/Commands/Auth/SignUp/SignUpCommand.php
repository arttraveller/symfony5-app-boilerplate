<?php

namespace App\Core\Commands\Auth\SignUp;

use App\Core\Validators\Auth\UniqueEmail;
use Symfony\Component\Validator\Constraints as Assert;

class SignUpCommand
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max=255)
     * @UniqueEmail()
     */
    public string $email = '';

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6, max=150)
     */
    public string $password = '';
}
