<?php

namespace App\Players\ArgumentValueResolver;

use App\Players\Dto\PlayerInput;
use App\Players\Entity\Player;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class PlayerArgumentResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $request->getPathInfo() == '/players';
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->serializer->deserialize($request->getContent(), PlayerInput::class, 'json');
    }
}
