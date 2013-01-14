<?php
if($_POST) {
    $content = addslashes($_POST['content']);
    $currentTime = $_POST['currentTime'];
    $id = $_POST['id'] ? (int) $_POST['id'] : 0;
    $diaryId = saveDaily($diary, $content, $id);
    echo $diaryId;
}

function saveDaily($diary, $content, $id) {
    $corpId = $diary->corpId;
    $uid = $diary->uid;
    $reportTime = $fillTime = time();
    if($id) {
        $sql = "update `diary_info` set `content` = '".$content."', `report_time` = $reportTime, `fill_time` = $fillTime where `id` = $id";
        return $diary->db->query($sql);
    }else {
        $sql = "insert into `diary_info` (`id`, `corp_id`, `type`, `content`, `uid`, `show_time`, `report_time`, `fill_time`) values(null, $corpId, 3, '".$content."', $uid, $currentTime, $reportTime, $fillTime)";
        $diary->db->query($sql);
        return $diary->db->insert_id;
    }
}
