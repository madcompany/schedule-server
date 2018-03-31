<?php
namespace app\Controllers;
//use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;


class LectureController
{
    protected $db;

    protected $lecture;

    public function __construct($container){ //ContainerInterface $container
        $this->db = $container->get('db');

        $this->lecture = $container->get('lecture');
    }

    public function lecture(Request $request, Response $response){


        return $response->withJson(['errors' => ['email or password' => ['is invalid']]], 422);
    }

    public function index(Request $request, Response $response){

        $result  = $this->lecture->test();
        echo "<pre>";
        print_r($result);
        echo "</pre>";

        return $response->withJson(['errors' => ['email or password' => ['is invalid']]], 422);
    }
}