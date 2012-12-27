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

    /**
     * 获取用户tag列表
     */
    public static function getUserTags($diary){
        $uid = $diary->uid;
        $sql = "select t.*,c.`color` from `diary_tag` as t left join `diary_tag_color` as c on `t`.`color_id` = `c`.`id` where t.`uid` = $uid";
        $result = $diary->db->query($sql);
        $list = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $list[] = $row;
        }
        return $list;
    }

    public static function getDailyTag($diary, $daily_id) {
        $sql = "select * from `diary_daily_tag` where `diary_id` = $daily_id";
        $result = $diary->db->query($sql);
        $tagList = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $tagSql = "select t.*,c.`color` from `diary_tag` as t left join `diary_tag_color` as c on `t`.`color_id` = `c`.`id` where t.`id` = ".$row['tag_id'];
            $tagResult = $diary->db->query($tagSql);
            if($tagInfo = $tagResult->fetch_assoc()) {
                $tagList[] = $tagInfo + $row;
            }
        }
        return $tagList;
    }
}
