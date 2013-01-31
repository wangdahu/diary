<?php
if($_POST){
    $currentDate = $_POST['currentDate'];
    $startTime = (int)$_POST['startTime'];
    $type = $_POST['type'];
    $showObject = $_POST['showObject'];
    echo payDiary($diary, $type, $currentDate, $showObject, $startTime);
}

function payDiary($diary, $type, $currentDate, $showObject, $startTime) {
    if($type == 'daily') {
        $diaryType = 1;
    }else if($type == 'weekly') {
        $diaryType = 2;
    }else{
        $diaryType = 3;
    }
    // 要发送汇报消息的用户列表
    $allUsers = DiarySet::getAllObject($diary, $diaryType);
    $uid = $diary->uid;
    $reportTime = time();
    if($allUsers) {
        foreach($allUsers as $object) {
            $selectSql = "select * from `diary_report_record` where `uid` = $uid and `type` = '$type' and `object` = $object and `date` = '$currentDate'";
            $result = $diary->db->query($selectSql);
            if(!$result->fetch_assoc()) {
                $sql = "insert into `diary_report_record` (`uid`, `type`, `object`, `date`, `report_time`, `repay`) values ($uid, '$type', $object, '$currentDate', $reportTime, 1)";
                $diary->db->query($sql);
            }
        }
        $content = $showObject;
        $title = "补交工作日志";
        $config = Diary::getConfig();
        $url = "http://".$config['host']."/diary/index.php/team/".$type."?uid=".$uid."&startTime=".$startTime;
        DiaryMsg::send($allUsers, $title, $content, $url);
        return 1;
    }else {
        return 0;
    }
}
