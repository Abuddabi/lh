<?php
namespace App\Entity;

class Player
{
    private const PLAY_PLAY_STATUS = 'play';
    private const BENCH_PLAY_STATUS = 'bench';

    private int $number;
    private string $name;
    private string $position;
    private string $playStatus;
    private int $goals;
    private int $yellowCards;
    private int $redCards;
    private int $inMinute;
    private int $playTime;

    public function __construct(int $number, string $name, string $position)
    {
        $this->number = $number;
        $this->name = $name;
        $this->position = $position;
        $this->playStatus = self::BENCH_PLAY_STATUS;
        $this->goals = 0;
        $this->yellowCards = 0;
        $this->redCards = 0;
        $this->inMinute = 0;
        $this->playTime = 0;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getGoals(): int
    {
        return $this->goals;
    }

    public function getYellowCards(): int
    {
        return $this->yellowCards;
    }

    public function getRedCards(): int
    {
        return $this->redCards;
    }

    public function addGoal(): void
    {
        $this->goals++;
    }

    public function addYellowCard(): void
    {
        $this->yellowCards++;
    }

    public function addRedCard(): void
    {
        $this->redCards++;
    }

    public function isPlay(): bool
    {
        return $this->playStatus === self::PLAY_PLAY_STATUS;
    }

    public function getPlayTime(): int
    {
        return $this->playTime;
    }

    public function goToPlay(int $minute): void
    {
        $this->inMinute = $minute;
        $this->playStatus = self::PLAY_PLAY_STATUS;
    }

    public function goToBench(int $minute): void
    {
        $this->playTime += $minute - $this->inMinute + 1;
        $this->playStatus = self::BENCH_PLAY_STATUS;
    }
}