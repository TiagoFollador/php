<?php

namespace Alura\Threads\Activity;

class Course implements Activity
{
    /** @var Video[] */
    private array $videos;

    public function __construct(array $videos)
    {
        $this->videos = $videos;
    }

    public function points(): int
    {
        return array_reduce($this->videos, fn (int $total, Video $video) => $video->points() + $total, 0);
    }
}
