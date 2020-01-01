<?php

declare(strict_types=1);

namespace App\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class VirtualUsers implements UserInterface
{
    /** @var string */
    private $id;

    /** @var string */
    private $salt;

    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /** @var int */
    private $quota;

    /** @var array */
    private $roles;

    /** @var VirtualDomains */
    private $virtualDomain;

    /**
     * virtualUsers constructor.
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
        $this->salt = null;
        $this->roles[] = 'ROLE_USER';
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

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function addRole(string $role)
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
        return $this;
    }

    public function removeRole(string $role)
    {
        if (in_array($role, $this->roles)) {
            unset($this->roles[$role]);
        }
        return $this;
    }
}
