<?php

use App\Domain\AdminUser;
use App\Domain\Repository\AdminUserRepositoryInterface;
use Behat\Behat\Tester\Exception\PendingException;
use Behatch\Context\BaseContext;
use Behatch\HttpCall\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 * Class AuthenticationContext.
 */
class AuthenticationContext extends BaseContext
{
    /** @var Request */
    protected $request;

    /** @var JWTTokenManagerInterface */
    private $JWTTokenManager;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    public function __construct(
        Request $request,
        JWTTokenManagerInterface $JWTTokenManager,
        \Doctrine\ORM\EntityManagerInterface $entityManager
    ){
        $this->request = $request;
        $this->JWTTokenManager = $JWTTokenManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @Given The user :arg1 is authenticated with a JWT authorization header
     */
    public function theUserIsAuthenticatedWithAJwtAuthorizationHeader($arg1)
    {
        $repository = $this->entityManager->getRepository(AdminUser::class);

        /** @var AdminUser $user */
        $user = $repository->findOneBy([
            'username' => $arg1
            ]
        );

        $jwt = $this->JWTTokenManager->create($user);
        $this->request->setHttpHeader('authorization', 'Bearer '.$jwt);
    }

}
