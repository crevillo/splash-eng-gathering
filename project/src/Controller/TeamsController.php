<?php

namespace App\Controller;

use App\CommandAction\CreateTeamCommand;
use App\Entity\Player;
use App\Entity\Team;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TeamsController
{
    public function __construct(
        private readonly CommandBus $bus
    ) {
    }

    /**
     * @Route(name="create_team", path="/teams", methods={"POST"})
     */
    public function createTeam(Request $request)
    {
        $requestContent = $request->getContent();

        try {
            $playerData = json_decode($requestContent, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new BadRequestHttpException("bad json passed");
        }

        if (empty($playerData['name'])) {
            throw new BadRequestHttpException('Team Name needs to be provided');
        }

        $teamPlayers = [];
        if (!empty($playerData['players'])) {
            foreach($playerData['players'] as $index => $player) {
                if (empty($player['name']) || empty($player['age']) || empty($player['salary'])) {
                    throw new BadRequestHttpException('Name, age, salary and team need to be provided');
                }

                if (!is_numeric($player['age']) || !is_numeric($player['salary'])) {
                    throw new BadRequestHttpException('Age and salary needs to be provided');
                }

                if ((int)$player['age'] < 18 || (int)$player['age'] > 100) {
                    throw new BadRequestHttpException('Age must be between 18 and 100');
                }

                if ((int)$player['age'] > 50 &&  (int)$player['salary'] > 2000000) {
                    throw new BadRequestHttpException('Someone with more than 50 years can`t earn that money');
                }

                $teamPlayers[] = new Player($player['name'], $player['age'], $player['salary']);
            }
        }

        $team = new Team($playerData['name'], $teamPlayers);

        $team = $this->bus->handle(new CreateTeamCommand($team));

        return new JsonResponse([
            'statusCode' => Response::HTTP_CREATED,
            'message' => json_encode($team)
        ], Response::HTTP_CREATED);
    }

    public function updateTeam(Request $request)
    {

    }

    public function removeTeam(Request $request)
    {

    }
}
