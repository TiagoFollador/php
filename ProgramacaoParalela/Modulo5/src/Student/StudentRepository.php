<?php

namespace Alura\Threads\Student;

use Alura\Threads\Activity\Activity;

interface StudentRepository
{
    /** @return Student[] */
    public function all(): array;

    /** @return Activity[] */
    public function activitiesInADay(Student $student): array;
}