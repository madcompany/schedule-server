<?php
namespace app\Controllers;
use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class LectureController
{
    protected $db;

    protected $lecture;

    protected $view;

    public function __construct(ContainerInterface $container){ //ContainerInterface $container
        ini_set('memory_limit',-1);
        
        $this->lecture = $container->get('lecture');
        $this->view = $container->get('renderer');
    }
    
    public function university(Request $request,Response $response){        
        //강의 리스트
        $result  = $this->lecture->univList();

        return $response->withJson(['status' => true, 'univList' => $result , 'test' => $request->getHeaderLine('HTTP_ACCESS_CONTROL_ALLOW_HEADERS')] , 200);
    }

    /**
     * 강의리스트
     * @param Request $request
     * @param Response $response
     * @return static
     */
    public function subject(Request $request, Response $response){
        $univ_idx = $request->getParam('univ_idx');
        //강의 리스트
        $result  = $this->lecture->subjectList($univ_idx);

        return $response->withJson(['status' => true, 'subjectList'=>$result ] , 200);
    }

    /**
     * 시간표요청 호출
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function timeTable(Request $request, Response $response){
        $subject_idx = explode(',', $request->getParam('subject_idx'));
        $limitWeek = explode(',', $request->getParam('week'));
        $limitPeriod = explode(',', $request->getParam('period'));

        //$subject_idx = [1,2,3,4,5,7,8,9]; //임시 데이터

        //강의 시간 리스트
        $timetableList = $this->lecture->timetableList($subject_idx);

        //순열을 사용하여 모든 경우의 수를 생성한다.
        $lectureOrder = $this->pc_permute($subject_idx);
        //시간표 생성
        $timeArray = []; //초기화

        for($i = 0 ; $i < count($lectureOrder); $i++){
            $timeResult = $this->makeTimetable($lectureOrder[$i], $timetableList);
            if($timeResult !== false) {

                if (!in_array($timeResult, $timeArray)) { //중복제거
                    $timeArray[] = $timeResult;
                }                
            }
        }
        if(count($timeArray) > 0){        
            return $response->withJson(['status' => true, 'timetableList'=>$timeArray ] , 200);        
        }else{
            return $response->withJson(['status' => false, 'msg'=>'선택가능한 시간표가 존재하지 않습니다.'] , 400);        
        }
    }

    /**
     * 시간표생성
     * @param $lectureOrder
     * @param $timetableList
     * @return array|bool
     */
    public function makeTimetable($lectureOrder, $timetableList){

        //시간표 정보
        $timeArray = [];

        $sucCnt = 0; //성공카운트
        $lecCnt = count($timetableList); //선택된 강의 카운트

        //선택된 강의의 시간정보 루프
        foreach($lectureOrder as $order){

            $applyCnt = 0;
            $timetableInfo = $timetableList[$order];

            foreach($timetableInfo as $info){

                $week = $info['week']; //주정보
                //시작교시, 종료교시
                list($startPeroid, $endPeriod) = explode(",", $info['period']); //교시정보

                if( empty($timeArray[$week][$startPeroid]) && empty($timeArray[$week][$endPeriod]) ){ //선점되지 않았다면
                    $timeArray[$week][$startPeroid] = $info;
                    $timeArray[$week][$endPeriod] = $info;

                    $applyCnt++;
                    //2학점 짜리 강의면 종료
                    if( $info['grade'] < 3){
                        $sucCnt++;
                        break;
                    }else{ //3점짜리
                        if($applyCnt === 2){
                            $sucCnt++;
                            break;
                        }
                    }
                }
            }
            //한과목이라도 실패하면 종료
            if($applyCnt == 0)  break;
        }

        if($lecCnt === $sucCnt){
            return $timeArray;
        }else{
            return false;
        }
    }

    /**
     * 순열생성
     * @param $items
     * @param array $perms
     * @return array
     */
    function pc_permute($items, $perms = array( )) {
        if (empty($items)) {
            $return = array($perms);
        }  else {
            $return = array();
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                $return = array_merge($return, $this->pc_permute($newitems, $newperms));
            }
        }
        return $return;
    }

}