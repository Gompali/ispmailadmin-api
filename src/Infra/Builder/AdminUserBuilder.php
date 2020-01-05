<?php

declare(strict_types=1);

namespace App\Infra\Builder;

use App\Domain\AdminUser;
use App\Domain\DTO\UserDTO;
use App\Domain\Builder\AdminUserBuilderInterface;
use App\Domain\VirtualUsers;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserBuilder implements AdminUserBuilderInterface
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function createFromCredentials(string $id, string $username, string $password): AdminUser
    {
        $adminUser = new AdminUser(
            $id,
            $username
        );

        return $this->encodePassword($adminUser, $password);
    }

    public function encodePassword(AdminUser $user, string $plainPassword): AdminUser
    {
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        return $user;
    }

    public static function createDTO(VirtualUsers $user): UserDTO
    {
        $dto = new UserDTO();
        $dto->email = $user->getEmail();
        $dto->quota = $user->getQuota();
        return $dto;
    }
}
