<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\App\Command\CreateDomainCommand;
use App\Common\Exception\BadRequestException;
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

    public function __invoke(Request $request): JsonResponse
    {
        $requestContent = json_decode((string) $request->getContent(), true);
        if(null === $requestContent){
            throw new BadRequestException('Invalid Json');
        }

        if(!array_key_exists('name', $requestContent)){
            throw new BadRequestException('Parameter domain is missing');
        }

        $domain = $requestContent['name'];
        $this->commandBus->dispatch(new CreateDomainCommand(trim($domain)));

        return new JsonResponse(null, 201);
    }
}
