<?php

declare(strict_types=1);

namespace App\UI\Action;

use App\App\Command\UpdateUserCommand;
use App\Common\Exception\BadRequestException;
use App\Common\Infra\Messenger\HandleTrait;
use App\UI\Form\UserType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateUserAction
{
    use HandleTrait;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var MessageBusInterface */
    private $queryBus;

    /** @var MessageBusInterface */
    private $commandBus;

    public function __construct(
        FormFactoryInterface $formFactory,
        MessageBusInterface $queryBus,
        MessageBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request, string $email)
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

        $this->commandBus->dispatch(new UpdateUserCommand($form->getData(), $email));

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
