<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\App\Command\CreateAliasCommand;
use App\Common\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateAliasAction
{
    /** @var MessageBusInterface */
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request)
    {
        $parameters = json_decode($request->getContent(), true);

        if (!isset($parameters['source'])) {
            throw new BadRequestException('source alias is missing in request');
        }
        if (!isset($parameters['destination'])) {
            throw new BadRequestException('destination alias is missing in request');
        }


        $this->commandBus->dispatch(new CreateAliasCommand(
            $parameters['source'],
            $parameters['destination'])
        );

        return new JsonResponse(null, 201);
    }
}
