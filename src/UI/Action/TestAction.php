<?php

declare(strict_types=1);


namespace App\UI\Action;


use Symfony\Component\HttpFoundation\JsonResponse;

class TestAction
{
    public function __invoke()
    {
        $disableIpCheck = getenv('APP_DISABLE_IP_CHECK');
        return new JsonResponse($disableIpCheck, 200);
    }

}