<?php

declare(strict_types=1);

namespace App\App\Command;

class CreateAliasCommand
{
    /** @var string */
    private $source;

    /** @var string */
    private $destination;

    public function __construct(string $source, string $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getDestination(): string
    {
        return $this->destination;
    }
}
