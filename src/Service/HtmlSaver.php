<?php

namespace App\Service;

use App\Entity\Match;
use App\Entity\Player;
use App\Entity\Team;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HtmlSaver
{
    private string $resultDir;
    private Environment $twig;

    public function __construct(string $templateDir, string $resultDir)
    {
        $loader = new FilesystemLoader($templateDir);
        $this->twig = new Environment($loader);

        $this->resultDir = rtrim($resultDir, DIRECTORY_SEPARATOR);
    }

    public function save(Match $match): string
    {
        $content =  $this->twig->render('match.html.twig', [
            'match' => $match,
            'positionStats' => $this->buildPositionStats($match),
            'message' => getenv('MESSAGE') ?? ''
        ]);

        $path = $this->buildPath($match);

        file_put_contents($path, $content);

        return $path;
    }

    private function buildPath(Match $match): string
    {
        return implode(
            DIRECTORY_SEPARATOR,
            [
                $this->resultDir,
                "{$match->getId()}.html"
            ]
        );
    }

    private function buildPositionStats(Match $match): array
    {
        return [
            'homeTeam' => $this->buildTeamPositionStats($match->getHomeTeam()),
            'awayTeam' => $this->buildTeamPositionStats($match->getAwayTeam()),
        ];
    }

    private function buildTeamPositionStats(Team $team): array
    {
        return array_reduce(
            $team->getPlayers(),
            function (array $stats, Player $player) {
                $stats[$player->getPosition()] = $stats[$player->getPosition()] ?? 0;
                $stats[$player->getPosition()] += $player->getPlayTime();

                return $stats;
            },
            []
        );
    }
}