<?php

namespace App\Players\Controller;

use App\Players\Dto\PlayerInput;
use App\Players\Entity\Player;
use App\Players\Message\Command\CreatePlayerCommand;
use App\Teams\Entity\Team;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreatePlayer
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $commandBus
    ) {
        $this->messageBus = $commandBus;
    }

    /**
     * @Route(name="create_player", path="/players", methods={"POST"})
     */
    public function __invoke(PlayerInput $playerInput)
    {
        try {
            $player = $this->handle(new CreatePlayerCommand(
                new Player(
                    $playerInput->name,
                    $playerInput->age,
                    $playerInput->salary,
                    new Team($playerInput->teamInput->name)
                )
            ));
        } catch (\Exception $exception) {
            return new JsonResponse([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => ['error' => $exception->getMessage()]
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'statusCode' => Response::HTTP_CREATED,
            'message' => json_encode($player)
        ], Response::HTTP_CREATED);
    }
}
