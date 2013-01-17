<?php
if($_POST){
    $currentDate = $_POST['currentDate'];
    $type = $_POST['type'];
    $showObject = $_POST['showObject'];
    echo payDiary($diary, $type, $currentDate, $showObject);
}

function payDiary($diary, $type, $currentDate, $showObject) {
    // 要发送汇报消息的用户列表
    $diaryType = $type == 'daily' ? 1 : ($type == 'weekly' ? 2 : 3);
    $allUsers = DiarySet::getAllObject($diary, $diaryType);
    $uid = $diary->uid;
    $reportTime = time();
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
    $url = "http://".$config['host']."/diary/team/".$type."?uid=".$uid;
    DiaryMsg::send($allUsers, $title, $content, $url);
    return 1;
}
