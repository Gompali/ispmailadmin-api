<?php

declare(strict_types=1);


namespace App\UI\Action;

use App\App\Query\ListAliasesQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ListAliasesAction
{
    /** @var MessageBusInterface */
    private $queryBus;

    /** @var NormalizerInterface */
    private $normalizer;

    public function __construct(
        MessageBusInterface $queryBus,
        NormalizerInterface $normalizer
    ) {
        $this->queryBus = $queryBus;
        $this->normalizer = $normalizer;
    }

    public function __invoke()
    {
        $envelope = $this->queryBus->dispatch(new ListAliasesQuery());
        $results = $this->handle($envelope);
        $normalizedResults = $this->normalizer->normalize($results);

        return new JsonResponse($normalizedResults, 200);
    }
}
