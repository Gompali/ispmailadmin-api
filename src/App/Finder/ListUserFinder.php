<?php

declare(strict_types=1);

namespace App\App\Finder;

use App\App\Query\ListUserQuery;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualUsers;
use Doctrine\Common\Collections\Collection;

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


    /**
     * @param ListUserQuery $query
     * @return Collection<VirtualUsers>|null
     */
    public function __invoke(ListUserQuery $query): ?Collection
    {
        if (!$query instanceof ListUserQuery) {
            throw new \LogicException('Invalid query');
        }

        return $this->userRepository->findAll();
    }
}
