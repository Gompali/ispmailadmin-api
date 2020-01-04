<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\Domain\AdminUser;
use App\Domain\Repository\AdminUserRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class CreateTokenAction
{
    /** @var UserRepositoryInterface */
    private $adminUserRepository;

    /** @var JWTEncoderInterface */
    private $encoder;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(
        AdminUserRepositoryInterface $adminUserRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        JWTTokenManagerInterface $encoder
    ) {
        $this->adminUserRepository = $adminUserRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->encoder = $encoder;
    }

    public function __invoke(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (null === $data) {
            throw new \InvalidArgumentException('Invalid Json sent in request', 400);
        }

        $username = $data['username'];

        $user = $this->adminUserRepository->findOneBy([
            'username' => $username,
        ]);

        if (!$user instanceof AdminUser) {
            throw new AuthenticationException('Invalid Credentials', 401);
        }

        $match = $this->passwordEncoder->isPasswordValid($user, $data['password']);

        if (!$match) {
            throw new AuthenticationException('Invalid Credentials', 401);
        }

        $jwt = $this->encoder->create($user);

        return new JsonResponse(['token' => $jwt], 200);
    }
}
