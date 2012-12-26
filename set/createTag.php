<?php
if($_POST){
    echo saveTag($diary, $_POST);
}

function saveTag($diary, $post){
    $uid = $diary->uid;
    $color_id = (int) $post['color_id'];
    $tag = addslashes($post['tag']);

    $insertSql = "insert into `diary_tag` (`id`, `tag`, `uid`, `color_id`) values(null, '".$tag."', $uid, $color_id)";
    $diary->db->query($insertSql);
    return $diary->db->insert_id;
}
