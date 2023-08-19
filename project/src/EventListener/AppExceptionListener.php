<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AppExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof BadRequestHttpException) {
            $event->setResponse(
                new JsonResponse(
                    ['code' => $exception->getStatusCode(), 'message' => $exception->getMessage()]
                )
            );
        }
    }
}
