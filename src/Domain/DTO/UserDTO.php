<?php

declare(strict_types=1);

namespace App\Domain\DTO;

class UserDTO
{
    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /** @var int */
    public $quota;


    /** @return array<mixed> */
    public function toArray(): array
    {
        $email = $this->email ?? '@';
        $elements = explode('@', $email);
        $domain = end($elements);

        return [
            'email' => $this->email,
            'password' => $this->password,
            'quota' => $this->quota,
            'domain' => trim((string) $domain) ?? null,
        ];
    }
}
