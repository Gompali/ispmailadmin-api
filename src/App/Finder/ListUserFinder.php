<?php

declare(strict_types=1);

namespace App\App\Finder;

use App\App\Query\ListUserQuery;
use App\Domain\Repository\UserRepositoryInterface;

class ListUserFinder
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(ListUserQuery $query)
    {
        if ($query instanceof ListUserQuery) {
            return $this->userRepository->findAll();
        }

        return;
    }
}
