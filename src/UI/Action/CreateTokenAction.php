<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualUsers;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class CreateTokenAction
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var JWTEncoderInterface */
    private $encoder;

    public function __construct(
        UserRepositoryInterface $userRepository,
        JWTTokenManagerInterface $encoder
    ) {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    public function __invoke(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (null === $data) {
            throw new \InvalidArgumentException('Invalid Json sent in request', 400);
        }

        $email = $data['email'];

        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if (!$user instanceof VirtualUsers) {
            throw new BadCredentialsException();
        }

        $plainPassword = $data['password'];
        $dbPassword = substr($user->getPassword(), 11);
        $match = password_verify($plainPassword, $dbPassword);

        if (!$match) {
            throw new BadCredentialsException();
        }

        $jwt = $this->encoder->create($user);

        return new JsonResponse($jwt, 200);
    }
}
