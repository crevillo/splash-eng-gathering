<?php

namespace App\Players\Message\CommandHandler;

use App\Players\Entity\Player;
use App\Players\Message\Command\CreatePlayerCommand;
use App\Teams\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreatePlayerHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function handle(CreatePlayerCommand $createPlayerCommand): Player
    {
        $teamsRepository = $this->entityManager->getRepository(Team::class);
        if ($team = $teamsRepository->findOneBy(['name' => $createPlayerCommand->player->getTeam()->getName()])) {
            $underOr30 = $moreThan30 = 0;
            foreach ($team->getPlayers() as $player) {
                if ($player->getAge() <= 30) {
                    $underOr30++;
                } else {
                    $moreThan30++;
                }
            }

            if ($underOr30 >=6 && $createPlayerCommand->player->getAge() <= 30) {
                throw new BadRequestHttpException("Team has already 6 players under 30");
            }

            if ($moreThan30 >=6 && $createPlayerCommand->player->getAge() > 30) {
                throw new BadRequestHttpException("Team has already 6 players older than 30");
            }

            $createPlayerCommand->player->setTeam($team);
            $createPlayerCommand->player->getTeam()->addPlayer($createPlayerCommand->player);
        } else {
            $this->entityManager->persist($createPlayerCommand->player->getTeam());
        }
        $this->entityManager->persist($createPlayerCommand->player);
        $this->entityManager->flush();

        return $this->entityManager->find(Player::class, $createPlayerCommand->player->getId());
    }
}
