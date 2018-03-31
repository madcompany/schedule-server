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

    public function lectureList(){

        $query = $this->db->select()->from('subject');

        $stmt = $query->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }

    public function timetableList($lecture){
        $query = $this->db->select()->from('subject_detail AS SD')
            ->whereIn('SD.subject_idx', $lecture);

        $query->join('subject AS S', 'SD.subject_idx', '=', 'S.subject_idx');

        $stmt = $query->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[$row['subject_idx']][] = $row;
        }

        return $result;
    }


}