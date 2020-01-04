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

    /** @var Collection<VirtualUsers> */
    private $users;

    /** @var Collection<VirtualAliases> */
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

    public function addUser(VirtualUsers $user): VirtualDomains
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(VirtualUsers $user): VirtualDomains
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function addAlias(VirtualAliases $alias): VirtualDomains
    {
        if (!$this->aliases->contains($alias)) {
            $this->aliases->add($alias);
        }

        return $this;
    }

    public function removeAlias(VirtualAliases $alias): VirtualDomains
    {
        if ($this->aliases->contains($alias)) {
            $this->aliases->removeElement($alias);
        }

        return $this;
    }
}
