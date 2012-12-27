<?php
if($_POST){
    if(isset($_POST['action'])) {
        if($_POST['action'] == 'delete') {
            echo deleteTag($diary, $_POST);
        }else if($_POST['action'] == 'del-diary-all-tag') {
            echo deleteDiaryAllTag($diary, $_POST);
        }
    }else {
        echo saveTag($diary, $_POST);
    }
}

function deleteDiaryAllTag($diary, $post) {
    $id = (int) $post['id'];
    $delsql = "delete from `diary_daily_tag` where `diary_id` = $id";
    return $diary->db->query($delsql);
}

function deleteTag($diary, $post) {
    $uid = $diary->uid;
    $id = (int) $post['id'];
    $sql = "delete from `diary_tag` where `uid` = $uid and `id` = $id";
    // 删除所有的日志tag
    $delsql = "delete from `diary_daily_tag` where `tag_id` = $id";
    $diary->db->query($delsql);
    return $diary->db->query($sql);
}

function saveTag($diary, $post) {
    $uid = $diary->uid;
    $color_id = (int) $post['color_id'];
    $tag = addslashes($post['tag']);
    $id = (int) $post['id'];
    if($id){ // 编辑
        $selectSql = "select * from `diary_tag` where `tag` = '".$tag."' and `uid` = $uid and `id` != $id";
        $result = $diary->db->query($selectSql);
        if($result->fetch_row()){
            return 0;
        }
        $updateSql = "update `diary_tag` set `tag` = '".$tag."', `color_id` = $color_id where `id` = $id";
        return $diary->db->query($updateSql);
    }else{ // 新增
        $selectSql = "select * from `diary_tag` where `tag` = '".$tag."' and `uid` = $uid";
        $result = $diary->db->query($selectSql);
        if($result->fetch_row()){
            return 0;
        }
        $insertSql = "insert into `diary_tag` (`id`, `tag`, `uid`, `color_id`) values(null, '".$tag."', $uid, $color_id)";
        $diary->db->query($insertSql);
        return $diary->db->insert_id;
    }

}
