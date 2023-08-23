<?php

namespace App\Teams\Controller;

use App\Players\Entity\Player;
use App\Teams\Entity\Team;
use App\Teams\Message\Command\CreateTeamCommand;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class TeamsController
{
    use HandleTrait;
    public function __construct(
        MessageBusInterface $bus
    ) {
        $this->messageBus = $bus;
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

        $team = $this->handle(new CreateTeamCommand($team));

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
