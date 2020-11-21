<?php

namespace App\Events;

use App\Exceptions\ApiValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 10],
                // ['logException', 0],
            ],
        ];
    }

    public function processException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        switch ($exception) {
            case $exception instanceof ApiValidationException:
                $this->processApiValidationException($event, $exception);
                break;
        }
    }


    private function processApiValidationException(ExceptionEvent $event, ApiValidationException $exc)
    {
        $message = count($exc->getValidationErrors()) > 0 ? $exc->getValidationErrors() : $exc->getMessage();
        $event->setResponse(new JsonResponse([
            'error' => [
                'code' => 400,
                'message' => $message,
            ]
        ], 400));
    }
}