<?php
if($_POST){
    echo saveComment($diary, $_POST);
}

function saveComment($diary, $post){
    $uid = $diary->uid;
    $to_uid = (int) $post['to_uid'];
    $content = addslashes($_POST['content']);
    $object = addslashes($post['object']);
    $type = addslashes($post['type']);
    $add_time = time();

    $insertSql = "insert into `diary_comment` (`id`, `type`, `object`, `uid`, `to_uid`, `content`, `add_time`) values(null, '".$type."', '".$object."', $uid, $to_uid, '".$content."', $add_time)";
    $diary->db->query($insertSql);
    return $diary->db->insert_id;
}
