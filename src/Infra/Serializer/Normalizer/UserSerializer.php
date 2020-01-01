<?php

declare(strict_types=1);

namespace App\Infra\Serializer\Normalizer;

use App\Domain\VirtualUsers;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class UserSerializer implements SerializerAwareInterface, NormalizerInterface
{
    use SerializerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /* @var VirtualUsers $object */
        return [
            'id' => $object->getId(),
            'email' => $object->getEmail(),
            'quota' => $object->getQuota(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof VirtualUsers) && $format = 'json';
    }
}
