<?php

namespace App\CommandHandler;

use App\CommandAction\CreateTeamCommand;
use App\Entity\Team;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateTeamHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function handle(CreateTeamCommand $createTeamCommand): Team
    {
        foreach ($createTeamCommand->team->getPlayers() as $player) {
            $this->entityManager->persist($player);
        }
        $this->entityManager->persist($createTeamCommand->team);
        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new BadRequestHttpException('There is already a team with that name');
        }

        return $createTeamCommand->team;
    }
}
