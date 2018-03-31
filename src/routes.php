<?php

use app\Controllers\LectureController;
use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->group('/api', function(){
    $this->get('/lectures', LectureController::class . ':lecture')->setName('lecture.lecture');

    $this->get('/timetable', LectureController::class . ':timetable')->setName('lecture.timetable');
});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
