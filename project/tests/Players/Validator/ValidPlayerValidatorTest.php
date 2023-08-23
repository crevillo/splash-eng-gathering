<?php

namespace Tests\Players\Validator;

use App\Players\Dto\PlayerInput;
use App\Players\Dto\TeamInput;
use App\Players\Entity\Player;
use App\Players\Validator\ValidPlayer;
use App\Players\Validator\ValidPlayerValidator;
use App\Teams\Entity\Team;
use App\Teams\Repository\TeamsRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ValidPlayerValidatorTest extends TestCase
{
    public function test_will_fail_if_player_older_and_earns_too_much()
    {
        $player = new PlayerInput('p1', 55, 2000002, new TeamInput('Lakers'));

        $this->expectException(BadRequestHttpException::class);

        $validator = new ValidPlayerValidator(
            $this->createMock(TeamsRepository::class)
        );

        $validator->validate($player, new ValidPlayer());
    }

    public function test_will_fail_if_new_young_player_but_there_are_already_6_under_30()
    {
        $player = new PlayerInput('p1', 25, 2000002, new TeamInput('Lakers'));

        $team = new Team('Lakers');
        for ($i = 0; $i < 6; $i++) {
            $team->addPlayer(new Player(
                'player',
                25,
                100000,
                new Team('Lakers')
            ));
        }

        $this->expectException(BadRequestHttpException::class);
        $teamsRepository = $this->createMock(TeamsRepository::class);
        $teamsRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Lakers'])
            ->willReturn($team);

        $validator = new ValidPlayerValidator(
            $teamsRepository
        );

        $validator->validate($player, new ValidPlayer());
    }

    public function test_will_fail_if_new_old_player_but_there_are_already_6_older_than_30()
    {
        $player = new PlayerInput('p1', 55, 2000, new TeamInput('Lakers'));

        $team = new Team('Lakers');
        for ($i = 0; $i < 6; $i++) {
            $team->addPlayer(new Player(
                'player',
                55,
                100000,
                new Team('Lakers')
            ));
        }

        $this->expectException(BadRequestHttpException::class);
        $teamsRepository = $this->createMock(TeamsRepository::class);
        $teamsRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Lakers'])
            ->willReturn($team);

        $validator = new ValidPlayerValidator(
            $teamsRepository
        );

        $validator->validate($player, new ValidPlayer());
    }
}
