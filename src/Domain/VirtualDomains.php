<?php

declare(strict_types=1);

namespace App\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class VirtualDomains
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var Collection */
    private $users;

    /** @var Collection */
    private $aliases;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->users = new ArrayCollection();
        $this->aliases = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addUser(VirtualUsers $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(VirtualUsers $user)
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function addAlias(VirtualAliases $alias)
    {
        if (!$this->aliases->contains($alias)) {
            $this->aliases->add($alias);
        }

        return $this;
    }

    public function removeAlias(VirtualAliases $alias)
    {
        if ($this->aliases->contains($alias)) {
            $this->aliases->removeElement($alias);
        }

        return $this;
    }
}
