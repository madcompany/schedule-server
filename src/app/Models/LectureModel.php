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
    
    public function univList(){

        $query = $this->db->select()->from('university');

        $stmt = $query->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }

    public function subjectList($univ_idx = 1){        

        $query = $this->db->select()->from('subject')->where('univ_idx','=', $univ_idx);        
        
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
        
        $result = [];
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[$row['subject_idx']][] = $row;
        }

        return $result;
    }


}