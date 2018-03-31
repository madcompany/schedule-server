<?php
namespace app\Models;

use Interop\Container\ContainerInterface;

class LectureModel
{
    protected $db;

    public function __construct(ContainerInterface $container)
    {
        $this->db = $container['db'];
    }

    public function test(){

        $query = $this->db->select()->from('subject');

        $stmt = $query->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }


}