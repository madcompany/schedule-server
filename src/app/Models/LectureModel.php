<?php
use Interop\Container\ContainerInterface;

class LectureController
{
    protected $db;

    public function __construct(ContainerInterface $container)
    {
        $this->db = $container['db'];
    }
}