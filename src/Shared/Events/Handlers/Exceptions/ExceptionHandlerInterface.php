<?php

namespace App\Shared\Events\Handlers\Exceptions;

interface ExceptionHandlerInterface
{
    public function handle(): void;
}
