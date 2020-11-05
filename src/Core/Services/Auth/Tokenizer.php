<?php

namespace App\Core\Services\Auth;

class Tokenizer
{
    public function generateConfirmToken(): string
    {
        return bin2hex(random_bytes(48));
    }

    public function generateResetToken(): string
    {
        return bin2hex(random_bytes(48));
    }
}
