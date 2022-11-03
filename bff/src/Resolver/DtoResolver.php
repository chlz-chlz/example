<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Dto\DtoInterface;
use App\Dto\Exception\ErrorDto;
use App\Dto\Exception\ExceptionDto;
use App\Exception\ValidationException;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;

final class DtoResolver implements ArgumentValueResolverInterface
{
    private ArrayCollection $errors;

    public function __construct(
        private readonly DenormalizerInterface $denormalizer
    ) {
        $this->errors = new ArrayCollection();
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() !== null && is_subclass_of($argument->getType(), DtoInterface::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $data = ('' !== $request->getContent()) ? $request->toArray() : [];
        $data = array_merge($data, $request->query->all());

        try {
            /** @psalm-suppress PossiblyNullArgument */
            $dto = $this->denormalizer->denormalize($data, $argument->getType());
        } catch (PartialDenormalizationException $e) {
            /** @var NotNormalizableValueException $exception */
            foreach ($e->getErrors() as $exception) {
                try {
                    [$field, $expectedType, $givenType] = $this->parseDataFromExceptionMessage($exception->getMessage());

                    $errorDto = new ErrorDto([
                        'field' => $field,
                        'description' => sprintf(
                            'Поле %s должно быть типа "%s" (указан тип "%s").',
                            $field,
                            $expectedType,
                            $givenType
                        ),
                        'value' => $data[$field] ?? null,
                    ]);
                } catch (\Throwable) {
                    $path = $exception->getPath();
                    $errorDto = new ErrorDto([
                        'field' => $path,
                        'description' => sprintf('Некорректные входные данные для поля %s', $path ?? ''),
                        'value' => $path ? $data[$path] ?? null : null,
                    ]);
                }

                $errorDto->message = 'Ошибка валидации';
                $this->errors->add($errorDto);
            }

            $dto = $e->getData();
        }

        $this->validateDTO($dto);

        if ($this->errors->isEmpty() === false) {
            throw new ValidationException(new ExceptionDto([
                'errors' => $this->errors,
            ]));
        }

        yield $dto;
    }

    /**
     * @throws ValidationException
     */
    private function validateDTO(mixed $dto): void
    {
        $errors = $this->validator->validate($dto);
        if (0 !== $errors->count()) {
            /** @psalm-var ConstraintViolation $violation */
            foreach ($errors as $violation) {
                $this->errors->add(new ErrorDto([
                    'field' => $violation->getPropertyPath(),
                    'message' => 'Ошибка валидации',
                    'description' => sprintf((string)$violation->getMessage(), $violation->getPropertyPath()),
                    'value' => $violation->getInvalidValue(),
                ]));
            }
        }
    }

    private function parseDataFromExceptionMessage(string $message): array
    {
        $result = [];

        preg_match_all(
            '/Expected argument of type "(.+)", "(.+)" given at property path "(.+)"/',
            $message,
            $result
        );

        return [$result[3][0], $result[1][0], $result[2][0]];
    }
}