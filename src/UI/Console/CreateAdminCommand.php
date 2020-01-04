<?php

declare(strict_types=1);

namespace App\UI\Console;

use App\Domain\AdminUser;
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

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var DomainRepositoryInterface */
    private $domainRepository;

    public function __construct(
        DomainRepositoryInterface $domainRepository,
        AdminUserRepositoryInterface $adminUserRepository,
        UserPasswordEncoderInterface $encoder,
        string $username,
        string $password,
        string $name = null
    ) {
        parent::__construct($name);
        $this->domainRepository = $domainRepository;
        $this->adminUserRepository = $adminUserRepository;
        $this->encoder = $encoder;
        $this->username = $username;
        $this->password = $password;
    }

    protected function configure()
    {
        $this->setDescription('Initilalise le compte administrateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->adminUserRepository->findOneBy([
            'username' => $this->username,
        ]);


        if (!$user instanceof AdminUser) {
            $user = new AdminUser(
                Uuid::uuid4()->toString(),
                $this->username
            );
        }

        $plainPassword = $this->password;
        $password = $this->encoder->encodePassword($user, $plainPassword);

        if ($user instanceof AdminUser) {
            $user->setPassword($password);
        }

        $this->adminUserRepository->save($user);

        return 0;
    }
}
