<?php

declare(strict_types=1);

namespace App\UI\Doc;

use Symfony\Component\HttpFoundation\Response;

class ApiDocController
{
    public function __invoke()
    {
        return new Response(include 'swagger.html');
    }
}
