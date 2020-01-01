<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\App\Command\DeleteUserCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteUserAction
{
    /** @var MessageBusInterface */
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request, string $email)
    {
        $this->commandBus->dispatch(new DeleteUserCommand($email));

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
