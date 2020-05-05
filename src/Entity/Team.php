<?php

namespace App\Entity;

class Team
{
    public const POSITIONS = [
        'Вратари'       => 'В',
        'Защитники'     => 'З',
        'Полузащитники' => 'П',
        'Нападающие'    => 'Н',
    ];

    private string $name;
    private string $country;
    private string $logo;
    /**
     * @var Player[]
     */
    private array $players;
    private string $coach;
    private int $goals;

    public function __construct(string $name, string $country, string $logo, array $players, string $coach)
    {
        $this->assertCorrectPlayers($players);

        $this->name = $name;
        $this->country = $country;
        $this->logo = $logo;
        $this->players = $players;
        $this->coach = $coach;
        $this->goals = 0;
    }

    public function getPositions(): array
    {
        return Team::POSITIONS;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @return Player[]
     */
    public function getPlayersOnField(): array
    {
        return array_filter($this->players, function (Player $player) {
            return $player->isPlay();
        });
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getPlayer(int $number): Player
    {
        foreach ($this->players as $player) {
            if ($player->getNumber() === $number) {
                return $player;
            }
        }

        throw new \Exception(
            sprintf(
                'Player with number "%d" not play in team "%s".',
                $number,
                $this->name
            )
        );
    }

    public function getCoach(): string
    {
        return $this->coach;
    }

    public function addGoal(): void
    {
        $this->goals += 1;
    }

    public function getGoals(): int
    {
        return $this->goals;
    }

    public function getPlayersByPosition(string $position): array
    {
        $playersByPosition = [];
        foreach ($this->players as $player) {
            if ($player->getPosition() === $position) {
                $playersByPosition[] = $player;
            }
        }

        if (empty($playersByPosition)) {
            throw new \Exception(
                sprintf(
                    'Position "%s" is incorrect. Position should be one of: "%s".',
                    $position,
                    implode(", ", Team::POSITIONS)
                )
            );
        }

        return $playersByPosition;
    }

    public function getTotalTimeByPosition(string $position): int
    {
        $totalTime = 0;
        $players = $this->getPlayersByPosition($position);
        foreach ($players as $player) {
            $totalTime += $player->getPlayTime();
        }

        return $totalTime;
    }

    private function assertCorrectPlayers(array $players)
    {
        foreach ($players as $player) {
            if (!($player instanceof Player)) {
                throw new \Exception(
                    sprintf(
                        'Player should be instance of "%s". "%s" given.',
                        Player::class,
                        get_class($player)
                    )
                );
            }
        }
    }
}