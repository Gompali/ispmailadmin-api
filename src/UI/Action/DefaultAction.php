<?php

declare(strict_types=1);


namespace App\UI\Action;


use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultAction
{
    public function __invoke()
    {
        return new JsonResponse('ok', 200);
    }
}