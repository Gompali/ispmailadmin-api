<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\App\Command\PatchUserCommand;
use App\Common\Exception\BadRequestException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualUsers;
use App\Infra\Builder\AdminUserBuilder;
use App\Infra\Builder\AdminUserFactory;
use App\UI\Form\UpdateUserType;
use App\UI\Form\UserType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class PatchUserAction
{
    /** @var MessageBusInterface */
    private $commandBus;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var FormFactoryInterface */
    private $formFactory;

    public function __construct(
        FormFactoryInterface $formFactory,
        MessageBusInterface $commandBus,
        UserRepositoryInterface $userRepository
    ) {
        $this->commandBus = $commandBus;
        $this->userRepository = $userRepository;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request, string $email): JsonResponse
    {
        $requestContent = json_decode((string) $request->getContent(), true);
        if (null === $requestContent) {
            throw new BadRequestException('Invalid Json sent to request');
        }

        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if (!$user instanceof VirtualUsers) {
            throw new BadRequestException('User not found');
        }

        $dto = AdminUserBuilder::createUpdateDTO($user);
        $form = $this->formFactory->create(UpdateUserType::class, $dto);
        $clearMissing = 'PATCH' != $request->getMethod();
        $form->submit($requestContent, $clearMissing);

        $this->commandBus->dispatch(new PatchUserCommand($form->getData(), $user->getId()));

        return new JsonResponse(null, 200);
    }
}
