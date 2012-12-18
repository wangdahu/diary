<?php
if($_POST){
    $content = addslashes($_POST['content']);
    $currentTime = $_POST['currentTime'];
    $diaryId = saveDaily($diary, $content, $currentTime);
    echo $diaryId;
}

function saveDaily($diary, $content, $currentTime){
    $corpId = $diary->corpId;
    $uid = $diary->uid;
    // 该用户设置的汇报时间
    $setSql = "select `daily` from `diary_send_set` where `uid` = $uid";
    if($result = $diary->db->query($setSql)){
        $row = $result->fetch_row();
        if($row){
            $reportTime = $currentTime; // 需要修改
        }else{
            $reportTime = strtotime(date('Y-m-d', $currentTime)." ".$diary->dailySend);
        }
    }
    if($reportTime < time()){ // 已过汇报时间，为补交,马上汇报
        $reportTime = $fillTime = time();
    }else{
        $fillTime = time();
    }

    $insertSql = "insert into `diary_info` (`id`, `corp_id`, `content`, `uid`, `show_time`, `report_time`, `fill_time`) values(null, $corpId, '".$content."', $uid, $currentTime, $reportTime, $fillTime)";
    $diary->db->query($insertSql);
    return $diary->db->insert_id;
}

function insertCorn($diaryId){
    return true;
}
