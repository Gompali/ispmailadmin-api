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

class CreateOwnerAccountCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'create:owner';

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var string */
    private $ownerUsername;

    /** @var string */
    private $ownerPassword;

    /** @var int */
    private $ownerQuota;

    /** @var DomainRepositoryInterface */
    private $domainRepository;

    public function __construct(
        DomainRepositoryInterface $domainRepository,
        UserRepositoryInterface $userRepository,
        UserPasswordEncoderInterface $encoder,
        string $ownerUsername,
        string $ownerPassword,
        int $ownerQuota,
        string $name = null
    ) {
        parent::__construct($name);
        $this->domainRepository = $domainRepository;
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->ownerUsername = $ownerUsername;
        $this->ownerPassword = $ownerPassword;
        $this->ownerQuota = $ownerQuota;
    }

    protected function configure()
    {
        $this->setDescription('Initilalise le compte owner');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->userRepository->findOneBy([
            'email' => $this->ownerUsername,
        ]);

        $plainPassword = $this->ownerPassword;
        $password = '{BLF_CRYPT}'.password_hash($plainPassword, PASSWORD_BCRYPT);

        if ($user instanceof VirtualUsers) {
            $user->setPassword($password);
            $user->setQuota($this->ownerQuota);
            $arrayRoles = $user->getRoles();
            $arrayRoles[] = 'ROLE_AMDIN';
            $user->setRoles(array_unique($arrayRoles));
        }

        if (!$user instanceof VirtualUsers) {
            $user = new VirtualUsers(
                Uuid::uuid4()->toString(),
                $this->ownerUsername,
                $password,
                $this->ownerQuota
            );

            $domainElements = explode('@', $this->ownerUsername);
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
