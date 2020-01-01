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

class CreateSystemAccountCommand  extends Command
{
    /** @var string */
    protected static $defaultName = 'create:system';

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var string */
    private $systemUsername;

    /** @var string */
    private $systemPassword;

    /** @var int */
    private $systemQuota;

    /** @var DomainRepositoryInterface */
    private $domainRepository;

    public function __construct(
        DomainRepositoryInterface $domainRepository,
        UserRepositoryInterface $userRepository,
        UserPasswordEncoderInterface $encoder,
        string $systemUsername,
        string $systemPassword,
        int $systemQuota,
        string $name = null
    ) {
        parent::__construct($name);
        $this->domainRepository = $domainRepository;
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->systemUsername = $systemUsername;
        $this->systemPassword = $systemPassword;
        $this->systemQuota = $systemQuota;
    }

    protected function configure()
    {
        $this->setDescription('Initilalise le compte system');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->userRepository->findOneBy([
            'email' => $this->systemUsername,
        ]);

        $plainPassword = $this->systemPassword;
        $password = '{BLF_CRYPT}'.password_hash($plainPassword, PASSWORD_BCRYPT);

        if ($user instanceof VirtualUsers) {
            $user->setPassword($password);
            $user->setQuota($this->systemQuota);
        }

        if (!$user instanceof VirtualUsers) {
            $user = new VirtualUsers(
                Uuid::uuid4()->toString(),
                $this->systemUsername,
                $password,
                $this->systemQuota
            );

            $domainElements = explode('@', $this->systemUsername);
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
