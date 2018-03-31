<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//DB

// Service factory for the ORM
$container['db'] = function ($container) {
    $dbCon = $container->get('settings')['database'];

    $dsn = $dbCon['driver'].':host='.$dbCon['host'].';dbname='.$dbCon['database'].';charset='.$dbCon['charset'];
    $usr = $dbCon['username'];
    $pwd = $dbCon['password'];

    $pdo = new Slim\PDO\Database($dsn, $usr, $pwd);

    return $pdo;
};

//  Model
$container['lecture'] = function ($container) {
    return new \app\Models\LectureModel($container);
};
