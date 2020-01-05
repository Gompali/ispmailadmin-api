<?php

declare(strict_types=1);

namespace App\UI\Console;

use App\Domain\AdminUser;
use App\Domain\Builder\AdminUserBuilderInterface;
use App\Domain\Repository\AdminUserRepositoryInterface;
use App\Domain\Repository\DomainRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'create:admin';

    /** @var AdminUserRepositoryInterface */
    private $adminUserRepository;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var AdminUserBuilderInterface */
    private $adminUserBuilder;


    public function __construct(
        AdminUserBuilderInterface $adminUserBuilder,
        AdminUserRepositoryInterface $adminUserRepository,
        string $username,
        string $password,
        string $name = null
    ) {
        parent::__construct($name);
        $this->adminUserBuilder = $adminUserBuilder;
        $this->adminUserRepository = $adminUserRepository;
        $this->username = $username;
        $this->password = $password;
    }

    protected function configure(): void
    {
        $this->setDescription('Initilalise le compte administrateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->adminUserRepository->findOneBy([
            'username' => $this->username,
        ]);

        if (!$user instanceof AdminUser) {
            $user = $this->adminUserBuilder->createFromCredentials(
                Uuid::uuid4()->toString(),
                $this->username,
                $this->password
            );
        }

        $this->adminUserRepository->save($user);

        return 0;
    }
}
