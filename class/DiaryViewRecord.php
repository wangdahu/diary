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
     * 获取用户的当前时间点是否已查看
     */
    public static function getDateViews($diary, $type, $object, $uid) {
        $sql = "select `uid` from `diary_view_record` where `type` = '$type' and `view_uid` = $uid and `object` = '$object' group by `uid`";
        $result = $diary->db->query($sql);
        $users = array();
        while($row = $result->fetch_assoc()) {
            $users[] = $row['uid'];
        }
        return $users;
    }

    /**
     * 添加阅读记录
     */
    public static function addRecord($diary, $type, $uid, $object, $flag) {
        $view_uid = $diary->uid;
        $view_time = time();
        if(!self::checkUser($diary, $type, $uid, $object)) {
            $sql = "insert into `diary_view_record` (`type`,`view_uid`, `uid`, `object`, `view_time`, `flag`) values ('$type', $view_uid, $uid, '$object', $view_time, $flag)";
            return $diary->db->query($sql);
        }
        return true;
    }

    /**
     * 获取阅读人数
     */
    public static function getViewRecord($diary, $type, $uid, $object) {
        $sql = "select * from `diary_view_record` where `type` = '$type' and `uid` = $uid and `object` = '$object' and `flag` = 1";
        $result = $diary->db->query($sql);
        $record = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $record[$row['view_uid']] = $row;
        }
        return $record;
    }
}
