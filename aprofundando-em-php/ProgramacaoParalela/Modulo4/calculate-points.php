<?php

use Alura\Threads\Activity\Activity;
use Alura\Threads\Student\InMemoryStudentRepository;
use Alura\Threads\Student\Student;
use parallel\Runtime;
use parallel\Future;
use parallel\Channel;

require_once 'vendor/autoload.php';

$repository = new InMemoryStudentRepository();
$studentList = $repository->all();
$totalPoints = 0;
$runtime = [];
$futures = [];
$channel = Channel::make('points');

foreach ($studentList as $i => $student) {
    $activities = $repository->activitiesInADay($student);
    
    $runtime[$i] = new Runtime(__DIR__ . '/vendor/autoload.php');
    

    $futures[$i] = $runtime[$i]->run(function (array $activities, Student $student, Channel $channel) {
        $points = array_reduce(
            $activities,
            fn(int $total, Activity $activity) => $total + $activity->points(),
            0
        );
        $channel->send($points);

        printf('%s made %d points %s', $student->fullName(), $points, PHP_EOL);

        return $points;
    
    }, [$activities, $student, $channel]);

}

$totalPointsChannel = 0;
for ($i = 0; $i < count($studentList); $i++) {
    $totalPointsChannel += $channel->recv();
}
$channel->close();
$totalPoints = array_reduce($futures, (fn (int $total, Future $future) => $total = $future->value() + $total) , 0);

printf('We had total of %d points %s', $totalPoints, PHP_EOL);
printf('We had total of %d points (Channel) %s', $totalPointsChannel, PHP_EOL);