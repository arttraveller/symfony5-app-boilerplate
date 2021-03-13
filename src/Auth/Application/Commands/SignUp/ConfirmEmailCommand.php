<?php

namespace App\Auth\Application\Commands\SignUp;

class ConfirmEmailCommand
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
