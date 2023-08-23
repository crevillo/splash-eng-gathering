<?php

namespace App\Tests\Players\ArgumentValueResolver;

use App\Players\ArgumentValueResolver\PlayerArgumentResolver;
use App\Players\Dto\PlayerInput;
use App\Players\Dto\TeamInput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ConstraintViolationListNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\TraceableValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlayerArgumentResolverTest extends TestCase
{
    public function setUp(): void
    {
        usleep(250000);
    }

    public function test_will_throw_if_deserialization_fails()
    {
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            "a"
        );

        $this->expectException(BadRequestHttpException::class);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('deserialize')
            ->with("a", PlayerInput::class, 'json')
            ->willThrowException(new \Exception());

        $argumentResolver = new PlayerArgumentResolver(
            $serializer,
            $this->createMock(ValidatorInterface::class)
        );

        $resolve = $argumentResolver->resolve($request, $this->createMock(ArgumentMetadata::class));

        $resolve->next();
    }

    public function test_will_fail_if_serialization_works_but_validation_fails()
    {
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            "a"
        );

        $this->expectException(BadRequestHttpException::class);

        $playerInput = new PlayerInput('Michael Jordan', 28, 150000, new TeamInput('Bulls'));

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('deserialize')
            ->with("a", PlayerInput::class, 'json')
            ->willReturn($playerInput);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('validate')
            ->with($playerInput)
            ->willReturn(new ConstraintViolationList([$this->createMock(ConstraintViolation::class)]));

        $argumentResolver = new PlayerArgumentResolver(
            $serializer,
            $validator
        );

        $resolve = $argumentResolver->resolve($request, $this->createMock(ArgumentMetadata::class));

        $resolve->next();
    }
}
