<?php

declare(strict_types=1);

namespace App\UI\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteDomainAction
{
    /** @var MessageBusInterface */
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request, string $domain)
    {
        $this->commandBus->dispatch(new DeleteDomainCommand($domain));
    }
}
