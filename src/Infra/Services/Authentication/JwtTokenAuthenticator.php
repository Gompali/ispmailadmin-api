<?php

declare(strict_types=1);

namespace App\Infra\Services\Authentication;

use App\Domain\Repository\AdminUserRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Contracts\Translation\TranslatorInterface;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    /** @var JWTEncoderInterface */
    private $jwtEncoder;

    /** @var UserProviderInterface */
    private $userProvider;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(
        JWTEncoderInterface $jwtEncoder,
        TranslatorInterface $translator,
        AdminUserRepositoryInterface $userRepository,
        UserProviderInterface $userProvider
    ) {
        $this->jwtEncoder = $jwtEncoder;
        $this->translator = $translator;
        $this->userProvider = $userProvider;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        if (!$token) {
            throw new AuthenticationException('Token not found');
        }

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $data = $this->jwtEncoder->decode($credentials);
        } catch (JWTDecodeFailureException $exception) {
            throw new AuthenticationException($exception->getMessage());
        }

        if (false === $data) {
            throw new CustomUserMessageAuthenticationException('Invalid token');
        }

        $username = $data['username'];

        return $this->userRepository->findOneBy(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => $this->translator->trans('Authentication Required'),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }
}
