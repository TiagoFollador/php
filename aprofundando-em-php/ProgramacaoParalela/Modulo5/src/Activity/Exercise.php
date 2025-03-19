<?php

namespace Alura\Threads\Activity;

class Exercise implements Activity
{
    public function points(): int
    {
        return ceil(rand(1, 100) * 0.8);
    }
}
