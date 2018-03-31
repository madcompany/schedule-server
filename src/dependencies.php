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
    $dbCon = $container['settings']['database'];

    $dsn = $dbCon['DB_CONNECTION'].':host='.$dbCon['DB_HOST'].';dbname='.$dbCon['DB_NAME'].';charset='.$dbCon['charset'];
    $usr = $dbCon['username'];
    $pwd = $dbCon['password'];

    $pdo = new Database($dsn, $usr, $pwd);

    return $pdo;
};