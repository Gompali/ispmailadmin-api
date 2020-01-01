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

class CreateContactAccountCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'create:contact';

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var string */
    private $contactUsername;

    /** @var string */
    private $contactPassword;

    /** @var int */
    private $contactQuota;

    /** @var DomainRepositoryInterface */
    private $domainRepository;

    public function __construct(
        DomainRepositoryInterface $domainRepository,
        UserRepositoryInterface $userRepository,
        UserPasswordEncoderInterface $encoder,
        string $contactUsername,
        string $contactPassword,
        int $contactQuota,
        string $name = null
    ) {
        parent::__construct($name);
        $this->domainRepository = $domainRepository;
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->contactUsername = $contactUsername;
        $this->contactPassword = $contactPassword;
        $this->contactQuota = $contactQuota;
    }

    protected function configure()
    {
        $this->setDescription('Initilalise le compte contact');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->userRepository->findOneBy([
            'email' => $this->contactUsername,
        ]);

        $plainPassword = $this->contactPassword;
        $password = '{BLF_CRYPT}'.password_hash($plainPassword, PASSWORD_BCRYPT);

        if ($user instanceof VirtualUsers) {
            $user->setPassword($password);
            $user->setQuota($this->contactQuota);
        }

        if (!$user instanceof VirtualUsers) {
            $user = new VirtualUsers(
                Uuid::uuid4()->toString(),
                $this->contactUsername,
                $password,
                $this->contactQuota
            );

            $domainElements = explode('@', $this->contactUsername);
            $domainName = trim(end($domainElements));
            $domain = $this->domainRepository->findOneBy([
                'name' => $domainName,
            ]);

            if (!$domain instanceof VirtualDomains) {
                throw new \InvalidArgumentException('Domain not found : '.$domain);
            }

            $user->setVirtualDomain($domain);
        }

        $user->addRole('ROLE_USER');

        $this->userRepository->save($user);

        return 0;
    }
}
