<?php
if($_POST){
    if(isset($_POST['action'])) {
        if($_POST['action'] == 'delete') {
            echo deleteTag($diary, $_POST);
        }else if($_POST['action'] == 'del-diary-all-tag') {
            echo deleteDiaryAllTag($diary, $_POST);
        }else if($_POST['action'] == 'del-diary-tag') {
            echo deleteDiaryTag($diary, $_POST);
        }else if($_POST['action'] == 'add-diary-tag') {
            echo addDiaryTag($diary, $_POST);
        }
    }else {
        echo saveTag($diary, $_POST);
    }
}


/**
 * 删除日志单个标签
 */
function addDiaryTag($diary, $post) {
    $diary_id = (int) $post['diary_id'];
    $tag_id = (int) $post['tag_id'];
    $insertSql = "insert into `diary_daily_tag` (`diary_id`, `tag_id`) values ($diary_id, $tag_id)";
    return $diary->db->query($insertSql);
}

/**
 * 删除日志单个标签
 */
function deleteDiaryTag($diary, $post) {
    $diary_id = (int) $post['diary_id'];
    $tag_id = (int) $post['tag_id'];
    $delSql = "delete from `diary_daily_tag` where `diary_id` = $diary_id and `tag_id` = $tag_id";
    return $diary->db->query($delSql);
}

/**
 * 删除日志所有标签
 */
function deleteDiaryAllTag($diary, $post) {
    $diary_id = (int) $post['diary_id'];
    $delSql = "delete from `diary_daily_tag` where `diary_id` = $diary_id";
    return $diary->db->query($delSql);
}

/**
 * 删除单个标签并删除有这个标签的所有日志的标签
 */
function deleteTag($diary, $post) {
    $uid = $diary->uid;
    $id = (int) $post['id'];
    $sql = "delete from `diary_tag` where `uid` = $uid and `id` = $id";
    // 删除所有的日志tag
    $delSql = "delete from `diary_daily_tag` where `tag_id` = $id";
    $diary->db->query($delSql);
    return $diary->db->query($sql);
}

/**
 * 修改和新增标签
 */
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
