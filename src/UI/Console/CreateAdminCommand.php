<?php

declare(strict_types=1);

namespace App\UI\Console;

use App\Domain\Repository\DomainRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualDomains;
use App\Domain\VirtualUsers;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'create:admin';

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var string */
    private $adminUsername;

    /** @var string */
    private $adminPassword;

    /** @var int */
    private $adminQuota;

    /** @var DomainRepositoryInterface */
    private $domainRepository;

    public function __construct(
        DomainRepositoryInterface $domainRepository,
        UserRepositoryInterface $userRepository,
        UserPasswordEncoderInterface $encoder,
        string $adminUsername,
        string $adminPassword,
        int $adminQuota,
        string $name = null
    ) {
        parent::__construct($name);
        $this->domainRepository = $domainRepository;
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->adminUsername = $adminUsername;
        $this->adminPassword = $adminPassword;
        $this->adminQuota = $adminQuota;
    }

    protected function configure()
    {
        $this->setDescription('Initilalise le compte administrateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->userRepository->findOneBy([
            'email' => $this->adminUsername,
        ]);

        $plainPassword = $this->adminPassword;
        $password = '{BLF_CRYPT}'.password_hash($plainPassword, PASSWORD_BCRYPT);

        if ($user instanceof VirtualUsers) {
            $user->setPassword($password);
            $user->setQuota($this->adminQuota);
        }

        if (!$user instanceof VirtualUsers) {
            $user = new VirtualUsers(
                Uuid::uuid4()->toString(),
                $this->adminUsername,
                $password,
                $this->adminQuota
            );

            $domainElements = explode('@', $this->adminUsername);
            $domainName = trim(end($domainElements));
            $domain = $this->domainRepository->findOneBy([
                'name' => $domainName,
            ]);

            if (!$domain instanceof VirtualDomains) {
                throw new \InvalidArgumentException('Domain not found : '.$domain);
            }

            $user->setVirtualDomain($domain);
        }

        $user->addRole('ROLE_ADMIN');

        $this->userRepository->save($user);

        return 0;
    }
}
