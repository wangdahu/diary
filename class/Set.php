<?php
/**
 * 查询设置的不同类别的设置
 *
 */
class Set{
    /**
     * 查询工作时间
     */
    public static function workingTime($uid = $Diary->uid){
        $sql = "select `working_time` from `diary_set` where `uid` = $uid";
        if($result = $diary->db->query($sql)){
            $row = $result->fetch_row();
            if($row){
                return json_decode($row);
            }else{
                return $diary->workingTime;
            }
        }
    }
}
