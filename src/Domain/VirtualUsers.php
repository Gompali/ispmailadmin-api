<?php

declare(strict_types=1);

namespace App\Domain;

class VirtualUsers
{
    /** @var string */
    private $id;

    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /** @var int */
    private $quota;

    /** @var VirtualDomains */
    private $virtualDomain;

    /**
     * virtualUsers constructor.
     * @param string $id
     * @param string $email
     * @param string $password
     * @param int $quota
     */
    public function __construct(
        string $id,
        string $email,
        string $password,
        int $quota
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->quota = $quota;
    }

    /**
     * @return mixed
     */
    public function getVirtualDomain()
    {
        return $this->virtualDomain;
    }

    /**
     * @param mixed $virtualDomain
     */
    public function setVirtualDomain($virtualDomain): void
    {
        $this->virtualDomain = $virtualDomain;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getQuota(): int
    {
        return $this->quota;
    }

    public function setQuota(int $quota): void
    {
        $this->quota = $quota;
    }


    public function getUsername(): string
    {
        return $this->email;
    }
}
