<?php

namespace App\Tests\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayersRepository;
use App\Repository\TeamsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\NativeHttpClient;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class PlayersControllerTest extends WebTestCase
{
    use ResetDatabase, Factories;

    public function setUp(): void
    {
        usleep(250000);
    }

    public function test_will_fail_if_no_valid_json_passed()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            'a'
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function test_will_fail_if_no_all_required_fields_are_passed()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            json_encode(
                [
                    'name' => 'Michael Jordan',
                ]
            )
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function test_will_fail_if_no_name_passed_for_the_player()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            json_encode(
                [
                    'name' => '',
                    'age' => 30,
                    'salary' => 5000,
                    'team' => [
                        'name' => 'Lakers'
                    ]
                ]
            )
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function test_will_fail_if_age_is_not_an_string()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            json_encode(
                [
                    'name' => 'Michael Jordan',
                    'age' => 'ab',
                    'salary' => 5000,
                    'team' => [
                        'name' => 'Lakers'
                    ]
                ]
            )
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function test_will_fail_if_salary_is_not_an_string()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            json_encode(
                [
                    'name' => 'Michael Jordan',
                    'age' => 30,
                    'salary' => 'a5000',
                    'team' => [
                        'name' => 'Lakers'
                    ]
                ]
            )
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function test_will_fail_if_age_is_less_than_18()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            json_encode(
                [
                    'name' => 'Michael Jordan',
                    'age' => 12,
                    'salary' => 5000,
                    'team' => [
                        'name' => 'Lakers'
                    ]
                ]
            )
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function test_will_fail_if_age_is_greater_than_100()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            json_encode(
                [
                    'name' => 'Michael Jordan',
                    'age' => 120,
                    'salary' => 5000,
                    'team' => [
                        'name' => 'Lakers'
                    ]
                ]
            )
        );

        $this->assertResponseStatusCodeSame(400);

    }

    public function test_will_fail_if_age_is_greater_than_50_and_salary_greather_than_2M()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            json_encode(
                [
                    'name' => 'Michael Jordan',
                    'age' => 52,
                    'salary' => 5000000,
                    'team' => [
                        'name' => 'Lakers'
                    ]
                ]
            )
        );

        $this->assertResponseStatusCodeSame(400);

    }

    public function test_will_fail_if_team_name_is_empty()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            json_encode(
                [
                    'name' => 'Michael Jordan',
                    'age' => 32,
                    'salary' => 5000,
                    'team' => [
                        'name' => ''
                    ]
                ]
            )
        );

        $this->assertResponseStatusCodeSame(400);

    }

    public function test_will_fail_if_adding_a_player_with_more_than_30_years_and_already_6_players_with_more_than_30_years()
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
        $player6 = new Player('player 6', 35, 200);
        $team->addPlayer($player6);

        $em->persist($team);
        $em->persist($player1);
        $em->persist($player2);
        $em->persist($player3);
        $em->persist($player4);
        $em->persist($player5);
        $em->persist($player6);

        $em->flush();

        $client->request(
            'POST',
            'http://nginx/players',
            [],
            [],
            [],
            json_encode([
                'name' => 'Michael',
                'age' => 35,
                'salary' => 35000000,
                'team' => [
                    'name' => 'Lakers'
                ]
            ])
        );

        $this->assertResponseStatusCodeSame(400);
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
