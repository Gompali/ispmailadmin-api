<?php

declare(strict_types=1);


namespace App\App\Query;


class GetDomainQueryByName
{
    /**
     * @var string
     */
    private $domainName;

    public function __construct(string $domainName)
    {
        $this->domainName = $domainName;
    }

    /**
     * @return string
     */
    public function getDomainName(): string
    {
        return $this->domainName;
    }
}