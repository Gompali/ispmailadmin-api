<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\App\Query\ListUserQuery;
use App\Common\Infra\Messenger\HandleTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ListUserAction
{
    use HandleTrait;

    /** @var MessageBusInterface */
    private $queryBus;
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    public function __construct(
        MessageBusInterface $queryBus,
        NormalizerInterface $normalizer
    ) {
        $this->queryBus = $queryBus;
        $this->normalizer = $normalizer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $envelope = $this->queryBus->dispatch(new ListUserQuery());
        $results = $this->handle($envelope);
        $normalizedResults = $this->normalizer->normalize($results);
        return new JsonResponse($normalizedResults, 200);
    }
}
