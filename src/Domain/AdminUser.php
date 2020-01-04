<?php

declare(strict_types=1);

namespace App\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class AdminUser implements UserInterface
{
    /** @var string */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var array */
    private $roles;

    public function __construct(
        string $id,
        string $username
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->roles = ['ROLE_ADMIN'];
    }

    public function getId(): string
    {
        return $this->id;
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
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
