<?php
namespace app\Controllers;
//use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class LectureController
{
    protected $db;

    public function __construct(){ //ContainerInterface $container
        //$this->db = $container->get('db');
    }

    public function lecture(Request $request, Response $response){


        return $response->withJson(['errors' => ['email or password' => ['is invalid']]], 422);
    }

    public function index(Request $request, Response $response){
        return $response->withJson(['errors' => ['email or password' => ['is invalid']]], 422);
    }
}