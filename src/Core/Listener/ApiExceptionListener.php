<?php

declare(strict_types=1);

namespace App\Core\Listener;

use App\Core\ExceptionHandler\ExceptionMapping;
use App\Core\ExceptionHandler\ExceptionMappingResolver;
use App\Core\Response\ErrorResponse;
use App\Core\Response\ForbiddenResponse;
use App\Core\Response\NotFoundResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class ApiExceptionListener
{
    public function __construct(
        private ExceptionMappingResolver $resolver,
        private LoggerInterface          $logger,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        if ($throwable instanceof AccessDeniedException) {
            $response = new ForbiddenResponse();
            $event->setResponse($response);

            return;
        }

        if ($throwable instanceof NotFoundHttpException) {
            $response = new NotFoundResponse();
            $event->setResponse($response);

            return;
        }

        $mapping = $this->resolver->resolve(get_class($throwable));

        if (null === $mapping) {
            $mapping = ExceptionMapping::fromCode(
                $throwable->getCode() ? $throwable->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        $this->logger->error($throwable->getMessage(), [
            'trace' => $throwable->getTraceAsString(),
            'previous' => null !== $throwable->getPrevious() ? $throwable->getPrevious()->getMessage() : '',
        ]);

        if ($mapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR || $mapping->isLoggable()) {
            $this->logger->error($throwable->getMessage(), [
                'trace' => $throwable->getTraceAsString(),
                'previous' => null !== $throwable->getPrevious() ? $throwable->getPrevious()->getMessage() : '',
            ]);
        }

        $message = $throwable->getMessage();
        $code =  $throwable->getCode();
        $status = $code < 200 || $code > 502 ? Response::HTTP_BAD_REQUEST : $code;
        $response = new ErrorResponse($code, $status, $message);
        $event->setResponse($response);
    }
}

