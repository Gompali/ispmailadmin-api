<?php

declare(strict_types=1);


namespace App\UI\Action;


use App\App\Query\GetDomainQueryByName;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class GetDomainAction
{
    /**
     * @var MessageBusInterface
     */
    private $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request, string $domain)
    {
        $domainObject = $this->queryBus->dispatch(new GetDomainQueryByName($domain));

        return new JsonResponse([
            'domain' => $domainObject->getName()
        ], 200);
    }
}