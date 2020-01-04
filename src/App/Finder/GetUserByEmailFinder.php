<?php

declare(strict_types=1);

namespace App\App\Finder;

use App\App\Query\GetUserByEmailQuery;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualUsers;

class GetUserByEmailFinder
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(GetUserByEmailQuery $query): ?VirtualUsers
    {
        return $this->userRepository->findOneBy([
            'email' => $query->getEmail(),
        ]);
    }
}
