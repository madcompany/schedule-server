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
        $this->lecture = $container->get('lecture');
        $this->view = $container->get('renderer');
    }

    /**
     * 강의리스트
     * @param Request $request
     * @param Response $response
     * @return static
     */
    public function lecture(Request $request, Response $response){

        //강의 리스트
        $result  = $this->lecture->lectureList();

        return $response->withJson(['status' => true, 'lectureList'=>$result ] , 200);
    }

    /**
     * 시간표요청 호출
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function timeTable(Request $request, Response $response){
        //$lecture = $request->getParam('lecture');

        $lecture = [1,2,3,4]; //임시 데이터

        //강의 시간 리스트
        $timetableList = $this->lecture->timetableList($lecture);

        //순열을 사용하여 모든 경우의 수를 생성한다.
        $lectureOrder = $this->pc_permute($lecture);
        //시간표 생성
        $timeArray = []; //초기화

        for($i = 0 ; $i < count($lectureOrder); $i++){
            $timeResult = $this->makeTimetable($lectureOrder[$i], $timetableList);
            if($timeResult !== false) {

                if (!in_array($timeResult, $timeArray)) { //중복제거
                    $timeArray[] = $timeResult;
                }
                $tempArray[] = $timeResult;
            }
        }

        return $this->view->render($response, 'timetable.phtml', ['timeArray' => $timeArray]);
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