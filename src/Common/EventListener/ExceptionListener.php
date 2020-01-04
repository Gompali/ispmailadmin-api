<?php

namespace App\Common\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

/**
 * Class ExceptionListener.
 */
class ExceptionListener
{
    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /** @var string */
    private $environment;

    /**
     * ExceptionListener constructor.
     */
    public function __construct(LoggerInterface $logger, string $environment)
    {
        $this->logger = $logger;
        $this->environment = $environment;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $isMessengerException = $exception instanceof HandlerFailedException;
        if ($isMessengerException) {
            $exception = $exception->getPrevious();
        }

        $code = ($exception instanceof HttpException) ? $exception->getStatusCode() : 500;
        $message = $this->resolveExceptionMessage($exception);

        if (null !== ($decodedMessage = json_decode($message, true))) {
            $message = $decodedMessage;
        }

        $response = new JsonResponse(
            [
                'error_message' => $message,
                'status_code' => $code,
            ],
            $code
        );

        $this->logger->error($exception->getMessage());
        $event->setResponse($response);
    }

    /**
     * Hide error 5xx messages in prod environment.
     *
     * @return string
     */
    protected function resolveExceptionMessage(\Throwable $exception)
    {
        return 'prod' === $this->environment && $exception->getCode() >= 500
            ? 'Internal server error'
            : $exception->getMessage();
    }
}
