<?php

namespace App\Teams\Repository;

use App\Teams\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TeamsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function save(Team $team)
    {
        $this->getEntityManager()->persist($team);
        $this->getEntityManager()->flush();
    }
}
