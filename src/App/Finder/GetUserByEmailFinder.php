<?php

declare(strict_types=1);

namespace App\App\Finder;

use App\App\Query\GetUserByEmailQuery;
use App\Domain\Repository\UserRepositoryInterface;

class GetUserByEmailFinder
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(GetUserByEmailQuery $query)
    {
        return $this->userRepository->findOneBy([
            'email' => $query->getEmail(),
        ]);
    }
}
