<?php

declare(strict_types=1);

namespace App\Domain;

class VirtualAliases
{
    /** @var string */
    private $id;

    /** @var string */
    private $source;

    /** @var string */
    private $destination;

    /** @var VirtualDomains */
    private $virtualDomain;

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
     * @return VirtualDomains
     */
    public function getVirtualDomain(): VirtualDomains
    {
        return $this->virtualDomain;
    }

    /**
     * @param VirtualDomains $virtualDomain
     */
    public function setVirtualDomain(VirtualDomains $virtualDomain): void
    {
        $this->virtualDomain = $virtualDomain;
    }
}
