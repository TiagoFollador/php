<?php

namespace Alura\Threads;

function isPrime(float $number): bool
{
    if ($number === 2) {
        return true;
    }

    if ($number % 2 === 0) {
        return false;
    }

    for ($divisor = 3; $divisor < ($number / 2); $divisor += 2) {
        if ($number % $divisor === 0) {
            return false;
        }
    }

    return true;
}
