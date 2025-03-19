<?php
require_once 'vendor/autoload.php';

use Alura\Threads\Student\InMemoryStudentRepository;
use Alura\Threads\Student\Student;
use parallel\Runtime;

$runtimes = [];

$repository = new InMemoryStudentRepository();
$studentList  = $repository->all();
$resizeTo200PixelsWidth = function (Student $student) {
    echo "Resizing " . $student->fullName() . " profile picture!" . PHP_EOL;

    [$width, $height] = getimagesize($student->profilePicturePath());

    $ratio = $width / $height;


    $newWidth = 200;
    $newHeight = 200 * $ratio;

    $sourceImage = imagecreatefromjpeg($student->profilePicturePath());
    $destinationImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled(
        $destinationImage,
        $sourceImage,
        0,
        0,
        0,
        0,
        $newWidth,
        $newHeight,
        $width,
        $height
    );
    imagejpeg($destinationImage, __DIR__ . "/storage/resized/" . basename($student->profilePicturePath()));
    
    echo "Finished the resizing of: " . $student->fullName() . PHP_EOL;

};

foreach ($studentList as $i => $student) {

    $runtimes[$i] = new Runtime(__DIR__ . "/vendor/autoload.php");
    $runtimes[$i]->run($resizeTo200PixelsWidth, [$student] );

}

foreach ($runtimes as $runtime) {
    $runtime->close();
}


