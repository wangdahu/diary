<?php
class DiaryDaily{
    /**
     * 查找某段时间的日报
     */
    public static function findDaily($startTime, $endTime){

    }

    /**
     * 获取这月的周末列表
     */
    public static function getWeekly($month){

    }

    public static function getColorList($diary){
        $sql = "select * from `diary_tag_color`";
        $result = $diary->db->query($sql);
        $list = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $list[$row['id']] = $row['color'];
        }
        return $list;
    }

    /**
     * 获取用户tag列表
     */
    public static function getTagList($diary){
        $uid = $diary->uid;
        $sql = "select * from `diary_tag` where `uid` = $uid order by id desc";
        $result = $diary->db->query($sql);
        $list = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            // 获取tag的日记数量
            $countSql = "select count(*) from `diary_daily_tag` where `tag_id` = ".$row['id'];
            $countResult = $diary->db->query($countSql);
            $count = $countResult->fetch_row();
            $row['count'] = $count[0];
            $list[] = $row;
        }
        return $list;
    }
}
