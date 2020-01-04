<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\App\Command\DeleteAliasCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteAliasAction
{
    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request, string $source, string $destination): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteAliasCommand($source, $destination));

        return new JsonResponse(null, 204);
    }
}
