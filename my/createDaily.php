<?php
if($_POST) {
    $content = addslashes($_POST['content']);
    $currentTime = $_POST['currentTime'];
    $id = $_POST['id'] ? (int) $_POST['id'] : 0;
    $diaryId = saveDaily($diary, $content, $currentTime, $id);
    echo $diaryId;
}

function saveDaily($diary, $content, $currentTime, $id) {
    $corpId = $diary->corpId;
    $uid = $diary->uid;
    // 该用户设置的汇报时间
    $reportTime = $fillTime = time();
    if($id) {
        $sql = "update `diary_info` set `content` = '".$content."', `report_time` = $reportTime, `fill_time` = $fillTime where `id` = $id";
        return $diary->db->query($sql);
    }else {
        $sql = "insert into `diary_info` (`id`, `corp_id`, `content`, `uid`, `show_time`, `report_time`, `fill_time`) values(null, $corpId, '".$content."', $uid, $currentTime, $reportTime, $fillTime)";
        $diary->db->query($sql);
        return $diary->db->insert_id;
    }

}

