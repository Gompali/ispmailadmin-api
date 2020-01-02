<?php

declare(strict_types=1);

namespace App\App\Command;

class DeleteDomainCommand
{
    /** @var string */
    private $domain;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    /** @return string */
    public function getDomain(): string
    {
        return $this->domain;
    }
}
