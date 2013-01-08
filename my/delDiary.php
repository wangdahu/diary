<?php
if($_POST) {
    $id = (int) $_POST['id'];
    echo delDiary($diary, $id);
}

function delDiary($diary, $id) {
    $uid = $diary->uid;
    $diarySql = "delete from `diary_info` where `id` = $id and `uid` = $uid";
    $tagSql = "delete from `diary_daily_tag` where `diary_id` = $id";

    if($diary->db->query($diarySql)) {
        $diary->db->query($tagSql);
        return 1;
    }else{
        return 0;
    }
}
