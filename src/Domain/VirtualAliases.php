<?php

declare(strict_types=1);

namespace App\Domain;

use Doctrine\Common\Collections\Collection;

class VirtualAliases
{
    /** @var string */
    private $id;

    /** @var string */
    private $source;

    /** @var string */
    private $destination;

    /** @var Collection */
    private $domain;

    /**
     * virtualAliases constructor.
     */
    public function __construct(string $id, string $source, string $destination)
    {
        $this->id = $id;
        $this->source = $source;
        $this->destination = $destination;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }

    /**
     * @return Collection
     */
    public function getDomain(): Collection
    {
        return $this->domain;
    }

    /**
     * @param Collection $domain
     */
    public function setDomain(Collection $domain): void
    {
        $this->domain = $domain;
    }
}
