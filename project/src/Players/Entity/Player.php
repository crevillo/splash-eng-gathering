<?php

namespace App\Players\Entity;

use App\Teams\Entity\Team;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Players\Repository\PlayersRepository")
 * @ORM\Table("players")
 *
 * Someone with more than 50 years can`t earn more than 2M
 */
class Player implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private int $age;

    /**
     * @ORM\Column(type="integer")
     */
    private int $salary;

    /**
     * @ORM\ManyToOne(targetEntity="App\Teams\Entity\Team", inversedBy="players")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
    private Team $team;

    public function __construct(string $name, int $age, int $salary, ?Team $team = null)
    {
        $this->name = $name;
        $this->age = $age;
        $this->salary = $salary;
        if (!is_null($team)) {
            $this->setTeam($team);
        }
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return int
     */
    public function getSalary(): int
    {
        return $this->salary;
    }

    /**
     * @param int $salary
     */
    public function setSalary(int $salary): void
    {
        $this->salary = $salary;
    }

    public function setTeam(Team $team): void
    {
        //$team->addPlayer($this);
        $this->team = $team;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'salary' => $this->salary,
            'team' => $this->team
        ];
    }


}
