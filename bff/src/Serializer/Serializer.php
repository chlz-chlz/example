<?php

declare(strict_types=1);

namespace App\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class Serializer implements SerializerInterface, DenormalizerInterface, NormalizerInterface
{
    private SerializerInterface $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(
            new AnnotationLoader(
                new AnnotationReader()
            )
        );
        $extractor = new PropertyInfoExtractor(
            typeExtractors: [
                new PhpDocExtractor(),
                new ReflectionExtractor(),
            ]
        );
        $encoders = [new JsonEncoder()];
        $normalizers = [
            new DateTimeNormalizer(),
            new GetSetMethodNormalizer(
                classMetadataFactory: $classMetadataFactory
            ),
            new ObjectNormalizer(
                classMetadataFactory: $classMetadataFactory,
                propertyTypeExtractor: $extractor
            ),
            new ArrayDenormalizer(),
        ];

        $this->serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return $this->serializer->supportsDenormalization($data, $type, $format);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        return $this->serializer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $this->serializer->supportsNormalization($data, $format);
    }

    public function deserialize($data, string $type, string $format, array $context = [])
    {
        return $this->serializer->serialize($data,$type, $format, $context = []);
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): object
    {

        return $this->serializer->denormalize(
            data: $data,
            type: $type,
            format: 'array',
            context: [
                ...$context,
                DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
            ]
        );
    }

    public function serialize($data, string $format, array $context = [])
    {
        return $this->serializer->serialize($data, $format, $context);
    }
}