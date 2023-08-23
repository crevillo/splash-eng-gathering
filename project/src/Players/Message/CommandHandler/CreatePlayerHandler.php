<?php

namespace App\Players\Message\CommandHandler;

use App\Players\Entity\Player;
use App\Players\Message\Command\CreatePlayerCommand;
use App\Teams\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreatePlayerHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CreatePlayerCommand $createPlayerCommand): Player
    {
        $teamsRepository = $this->entityManager->getRepository(Team::class);
        if ($team = $teamsRepository->findOneBy(['name' => $createPlayerCommand->player->getTeam()->getName()])) {
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
