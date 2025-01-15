<?php

namespace Alura\Threads\Activity;

use function Alura\Threads\isPrime;

class Video implements Activity
{
    private int $durationInSeconds;

    public function __construct(\DateInterval $duration)
    {
        if ($duration->y > 0 || $duration->m > 0 || $duration->d > 0 || $duration->h > 0) {
            throw new \InvalidArgumentException('Duration must be less than 1 hour');
        }

        $this->durationInSeconds = $duration->m * 60 + $duration->s;
    }

    public function points(): int
    {
        $durationInSeconds = $this->durationInSeconds;
        $points = $durationInSeconds * 1.6666666666666667;

        if (isPrime($durationInSeconds)) {
            $points += $points / $durationInSeconds / 2;
        }

        return ceil($points);
    }
}
