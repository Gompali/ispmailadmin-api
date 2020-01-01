<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\App\Command\CreateUserCommand;
use App\Common\Exception\BadRequestException;
use App\UI\Form\UserType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateUserAction
{
    /** @var MessageBusInterface */
    private $commandBus;

    /** @var FormFactoryInterface */
    private $formFactory;

    public function __construct(
        FormFactoryInterface $formFactory,
        MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request)
    {
        $form = $this->formFactory->create(UserType::class);

        $requestData = json_decode($request->getContent(), true);

        if (null === $requestData) {
            throw new \JsonException('The Json sent for your request is Invalid');
        }

        $form->submit($requestData);

        if (!$form->isValid()) {

            if (!$form->isSubmitted() || !$form->isValid()) {
                throw BadRequestException::createFromForm($form);
            }
        }

        $userId = Uuid::uuid4()->toString();

        $this->commandBus->dispatch(new CreateUserCommand(
            $form->getData(), $userId
        ));

        return new JsonResponse(['id' => $userId], 200);
    }
}
