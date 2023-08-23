<?php

namespace App\Players\ArgumentValueResolver;

use App\Players\Dto\PlayerInput;
use App\Players\Entity\Player;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlayerArgumentResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $request->getPathInfo() == '/players';
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        try {
            $input = $this->serializer->deserialize($request->getContent(), PlayerInput::class, 'json');
        } catch (\Exception $exception) {
            throw new BadRequestHttpException();
        }

        $errors = $this->validator->validate($input);
        if  (count($errors)) {
            throw  new BadRequestHttpException();
        }

        yield $input;
    }
}
