<?php

declare(strict_types=1);

namespace App\Infra\Validator;

use Symfony\Component\Validator\Constraint;

class EmailUniqueConstraint extends Constraint
{
    public $message = "'{{ email }}' is already registered";

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }

    public function getMessage()
    {
        return $this->message;
    }
}
