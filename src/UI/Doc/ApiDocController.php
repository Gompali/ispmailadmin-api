<?php

declare(strict_types=1);

namespace App\UI\Doc;

use Symfony\Component\HttpFoundation\Response;

class ApiDocController
{
    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        return new Response(include 'swagger.html');
    }
}
