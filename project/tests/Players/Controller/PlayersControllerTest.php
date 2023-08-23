<?php

namespace App\Players\Tests\Controller;

use App\Players\Entity\Player;
use App\Teams\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class PlayersControllerTest extends WebTestCase
{
    use ResetDatabase, Factories;

    public function setUp(): void
    {
        usleep(250000);
    }

    public function test_will_success_if_adding_a_player_with_more_than_30_years_and_no_more_than_6_players_with_more_than_30_years()
    {
        $client = static::createClient();
        $container = self::$container;
        $team = new Team('Lakers');
        $em = $container->get(EntityManagerInterface::class);

        $player1 = new Player('player 1', 35, 200);
        $team->addPlayer($player1);
        $player2 = new Player('player 2', 35, 200);
        $team->addPlayer($player2);
        $player3 = new Player('player 3', 35, 200);
        $team->addPlayer($player3);
        $player4 = new Player('player 4', 35, 200);
        $team->addPlayer($player4);
        $player5 = new Player('player 5', 35, 200);
        $team->addPlayer($player5);

        $em->persist($team);
        $em->persist($player1);
        $em->persist($player2);
        $em->persist($player3);
        $em->persist($player4);
        $em->persist($player5);

        $em->flush();

        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            json_encode([
                'name' => 'Michael',
                'age' => 25,
                'salary' => 35000000,
                'team' => [
                    'name' => 'Lakers'
                ]
            ])
        );

        $this->assertResponseStatusCodeSame(201);

        $this->assertCount(6, $team->getPlayers()->toArray());
    }
}
