<?php


namespace App\Infra\Validator;


interface EmailUniqueConstraintInterface
{
    public function getMessage(): string;
}