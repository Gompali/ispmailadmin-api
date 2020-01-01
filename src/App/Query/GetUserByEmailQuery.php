<?php

declare(strict_types=1);

namespace App\App\Query;

class GetUserByEmailQuery
{
    /** @var string */
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
