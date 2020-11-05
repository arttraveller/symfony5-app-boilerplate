<?php

namespace App\Core\Commands\Auth\SignUp;

use Symfony\Component\Validator\Constraints as Assert;

class SignUpCommand
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max=255)
     */
    // TODO unique email
    public string $email = '';

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6, max=150)
     */
    public string $password = '';
}
