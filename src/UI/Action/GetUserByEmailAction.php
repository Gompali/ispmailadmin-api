<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\App\Query\GetUserByEmailQuery;
use App\Common\Infra\Messenger\HandleTrait;
use App\Domain\VirtualUsers;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GetUserByEmailAction
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

    public function __invoke(Request $request, string $email)
    {
        $envelope = $this->queryBus->dispatch(new GetUserByEmailQuery($email));
        $user = $this->handle($envelope);

        if (!$user instanceof VirtualUsers) {
            throw new EntityNotFoundException('User not found with email : '.$email);
        }

        return new JsonResponse(
            $this->normalizer->normalize($user), 200
        );
    }
}
