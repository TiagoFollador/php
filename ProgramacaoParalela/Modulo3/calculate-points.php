<?php

use Alura\Threads\Activity\Activity;
use Alura\Threads\Student\InMemoryStudentRepository;
use Alura\Threads\Student\Student;
use parallel\Runtime;
use parallel\Future;


require_once 'vendor/autoload.php';

$repository = new InMemoryStudentRepository();
$studentList = $repository->all();
$totalPoints = 0;
$runtime = [];
$futures = [];

foreach ($studentList as $i => $student) {
    $activities = $repository->activitiesInADay($student);
    
    $runtime[$i] = new Runtime(__DIR__ . '/vendor/autoload.php');
    

    $futures[$i] = $runtime[$i]->run(function (array $activities, Student $student, ) {
        $points = array_reduce(
            $activities,
            fn(int $total, Activity $activity) => $total + $activity->points(),
            0
        );
        printf('%s made %d points %s', $student->fullName(), $points, PHP_EOL);

        return $points;
    
    }, [$activities, $student]);

}
$totalPoints = array_reduce($futures, fn (int $total, Future $future) => $total = $future->value() + $total, 0);

printf('We had total of %d points %s', $totalPoints, PHP_EOL);