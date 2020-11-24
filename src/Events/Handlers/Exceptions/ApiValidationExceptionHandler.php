<?php

namespace App\Events\Handlers\Exceptions;

use App\Exceptions\ApiValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiValidationExceptionHandler implements ExceptionHandlerInterface
{
    private ExceptionEvent $event;
    private ApiValidationException $exc;


    public function __construct(ExceptionEvent $event, ApiValidationException $exc)
    {
        $this->event = $event;
        $this->exc = $exc;
    }

    public function handle(): void
    {
        $message = count($this->exc->getValidationErrors()) > 0 ? $this->exc->getValidationErrors() : $this->exc->getMessage();
        $this->event->setResponse(new JsonResponse([
            'error' => [
                'code' => 400,
                'message' => $message,
            ]
        ], 400));
    }
}