<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController
{
    public function __invoke()
    {
        return new JsonResponse('ok', 200);
    }
}
