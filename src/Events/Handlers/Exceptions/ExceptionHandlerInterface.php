<?php

namespace App\Events\Handlers\Exceptions;

interface ExceptionHandlerInterface
{
    public function handle(): void;
}
