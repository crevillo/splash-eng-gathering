<?php

namespace App\Controller;

use App\CommandAction\CreatePlayerCommand;
use App\Entity\Player;
use App\Entity\Team;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PlayersController
{
    public function __construct(
        private readonly CommandBus $bus
    ) {
    }

    /**
     * @Route(name="create_player", path="/players", methods={"POST"})
     */
    public function createPlayer(Request $request)
    {
        $requestContent = $request->getContent();

        try {
            $playerData = json_decode($requestContent, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new BadRequestHttpException("bad json passed");
        }

        if (empty($playerData['name']) || empty($playerData['age']) || empty($playerData['salary']) || empty($playerData['team'])) {
            throw new BadRequestHttpException('Name, age, salary and team need to be provided');
        }

        if (!is_numeric($playerData['age']) || !is_numeric($playerData['salary'])) {
            throw new BadRequestHttpException('Age and salary needs to be provided');
        }

        if ((int)$playerData['age'] < 18 || (int)$playerData['age'] > 100) {
            throw new BadRequestHttpException('Age must be between 18 and 100');
        }

        if ((int)$playerData['age'] > 50 &&  (int)$playerData['salary'] > 2000000) {
            throw new BadRequestHttpException('Someone with more than 50 years can`t earn that money');
        }

        if (empty($playerData['team']['name'])) {
            throw new BadRequestHttpException('Name of the team need to be provided');
        }

        $team = new Team($playerData['team']['name']);
        $player = new Player($playerData['name'], $playerData['age'], $playerData['salary'], $team);

        $player = $this->bus->handle(new CreatePlayerCommand($player));
        return new JsonResponse([
            'statusCode' => Response::HTTP_CREATED,
            'message' => json_encode($player)
        ], Response::HTTP_CREATED);
    }

    public function updatePlayer(Request $request)
    {

    }

    public function removePlayer(Request $request)
    {

    }
}
