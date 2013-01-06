<?php

class DiaryViewRecord{

    /**
     * 获取用户的当前时间点是否已查看
     */
    public static function checkUser($diary, $type, $uid, $object) {
        $view_uid = $diary->uid;
        $sql = "select * from `diary_view_record` where `type` = '$type' and `view_uid` = $view_uid and `uid` = $uid and `object` = '$object' limit 1";
        $result = $diary->db->query($sql);
        while($row = $result->fetch_row()) {
            return true;
        }
        return false;
    }

    /**
     * 添加阅读记录
     */
    public static function addRecord($diary, $type, $uid, $object) {
        $view_uid = $diary->uid;
        $view_time = time();
        if(!self::checkUser($diary, $type, $uid, $object)) {
            $sql = "insert into `diary_view_record` (`type`,`view_uid`, `uid`, `object`, `view_time`) values ('$type', $view_uid, $uid, '$object', $view_time)";
            return $diary->db->query($sql);
        }
        return true;
    }

    /**
     * 获取阅读人数
     */
    public static function getViewRecord($diary, $type, $uid, $object) {
        $sql = "select * from `diary_view_record` where `type` = '$type' and `uid` = $uid and `object` = '$object'";
        $result = $diary->db->query($sql);
        $record = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $record[$row['uid']] = $row;
        }
        return $record;
    }
}
