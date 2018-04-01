<?php

use app\Controllers\LectureController;
use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->group('/api', function(){
    $this->get('/universitys', LectureController::class . ':university')->setName('lecture.university');
    $this->get('/subjects', LectureController::class . ':subject')->setName('lecture.subject');

    $this->get('/timetables', LectureController::class . ':timetable')->setName('lecture.timetable');
});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
