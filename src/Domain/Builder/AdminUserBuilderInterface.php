<?php


namespace App\Domain\Builder;


use App\Domain\AdminUser;
use App\Domain\DTO\UserDTO;
use App\Domain\VirtualUsers;

interface AdminUserBuilderInterface
{
    public function encodePassword(AdminUser $user, string $plainPassword): AdminUser;

    public static function createDTO(VirtualUsers $user): UserDTO;

    public function createFromCredentials(string $id, string $username, string $password): AdminUser;
}