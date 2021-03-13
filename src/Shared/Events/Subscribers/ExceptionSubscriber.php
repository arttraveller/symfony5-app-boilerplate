<?php

namespace App\Shared\Events\Subscribers;

use App\Shared\Events\Handlers\Exceptions\ApiValidationExceptionHandler;
use App\Shared\Exceptions\ApiValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
        $excHandler = null;
        switch ($exception) {
            case $exception instanceof ApiValidationException:
                $excHandler = new ApiValidationExceptionHandler($event, $exception);
                break;
        }
        // If exception handler created then handle exception
        if ($excHandler) {
            $excHandler->handle();
        }
    }
}