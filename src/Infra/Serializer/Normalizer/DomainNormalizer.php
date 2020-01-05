<?php

declare(strict_types=1);


namespace App\Infra\Serializer\Normalizer;


use App\Domain\VirtualDomains;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class DomainNormalizer implements SerializerAwareInterface, NormalizerInterface
{
    use SerializerAwareTrait;

    /**
     * @inheritDoc
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /** @var VirtualDomains $object */
        return [
            'id' => $object->getId(),
            'name' => $object->getName()
        ];
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null)
    {
       return ($data instanceof VirtualDomains) && $format === 'json';
    }
}