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

    /**
     * 获取当前时间点那些用户有评论
     */
    public static function getDateComments($diary, $type, $object){
        $sql = "select `to_uid` from `diary_comment` where `type` = '$type' and `object` = '$object' group by `to_uid`";
        $result = $diary->db->query($sql);
        $users = array();
        while($row = $result->fetch_assoc()) {
            $users[] = $row['to_uid'];
        }
        return $users;
    }

    /**
     * 获取时间段内哪些时间点有评论
     */
    public static function getWhichDate($diary, $to_uid, $type, $firstDate, $lastDate){
        $sql = "select * from `diary_comment` where `to_uid` = $to_uid and `type` = '$type' and (`object` between '$firstDate' and '$lastDate' ) group by `object`";
        $result = $diary->db->query($sql);
        $list = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $list[] = $row['object'];
        }
        return $list;
    }
}
