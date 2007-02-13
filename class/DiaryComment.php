<?php

class DiaryComment{

    /**
     * 获取用户日志列表
     */
    public static function getObjectComment($diary, $to_uid, $type, $object) {
        $sql = "select * from `diary_comment` where `to_uid` = $to_uid and `type` = '$type' and `object` = '$object'";
        $list = array();
        $result = $diary->db->query($sql);
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $list[] = $row;
        }
        return $list;
    }

    /**
     * 获取用户的当前时间点是否有评论
     */
    public static function checkUserObjectComment($diary, $to_uid, $type, $object){
        $sql = "select * from `diary_comment` where `to_uid` = $to_uid and `type` = '$type' and `object` = '$object' limit 1";
        $result = $diary->db->query($sql);
        while($row = $result->fetch_row()) {
            return true;
        }
        return false;
    }
}