<?php

declare(strict_types=1);


namespace App\Infra\Serializer\Normalizer;


use App\Domain\VirtualAliases;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class AliasNormalizer implements SerializerAwareInterface, NormalizerInterface
{
    use SerializerAwareTrait;

    /**
     * @inheritDoc
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /** @var VirtualAliases $object */
        return [
            'id' => $object->getId(),
            'source' => $object->getSource(),
            'destination' => $object->getDestination(),
            'domain' => $object->getDomain()
        ];
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof VirtualAliases) && $format === 'json';
    }
}