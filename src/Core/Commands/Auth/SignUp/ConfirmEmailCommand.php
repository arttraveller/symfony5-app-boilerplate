<?php

namespace App\Core\Commands\Auth\SignUp;

class ConfirmEmailCommand
{
    public string $token;


    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
