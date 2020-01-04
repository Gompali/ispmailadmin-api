<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\App\Command\CreateDomainCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateDomainAction
{
    /** @var MessageBusInterface */
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request, string $domain): JsonResponse
    {
        $this->commandBus->dispatch(new CreateDomainCommand(trim($domain)));

        return new JsonResponse(null, 201);
    }
}
