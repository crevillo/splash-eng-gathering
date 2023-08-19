<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamsRepository")
 * @ORM\Table("teams")
 */
class Team implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="team")
     */
    private $players;

    /**
     * @param int|null $id
     * @param string $name
     */
    public function __construct(string $name, ?array $players = null)
    {
        $this->name = $name;
        if (!is_null($players)) {
            foreach ($players as $player) {
                $this->addPlayer($player);
            }
        } else {
            $this->players = new ArrayCollection();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addPlayer(Player $player): void
    {
        $this->players[] = $player;
        $player->setTeam($this);
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'players' => $this->getPlayers()
        ];
    }
}
