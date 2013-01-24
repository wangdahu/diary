<?php
class DiaryDaily{

    /**
     * 获取颜色列表
     */
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
    public static function getUserTags($diary, $uid=null){
        $uid = $uid ? $uid : $diary->uid;
        $sql = "select t.*,c.`color` from `diary_tag` as t left join `diary_tag_color` as c on `t`.`color_id` = `c`.`id` where t.`uid` = $uid";
        $result = $diary->db->query($sql);
        $list = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $list[$row['id']] = $row;
        }
        return $list;
    }

    /**
     * 获取日报的tag信息
     */
    public static function getDailyTag($diary, $daily_id) {
        $sql = "select * from `diary_daily_tag` where `diary_id` = $daily_id";
        $result = $diary->db->query($sql);
        $tagList = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $tagSql = "select t.*,c.`color` from `diary_tag` as t left join `diary_tag_color` as c on `t`.`color_id` = `c`.`id` where t.`id` = ".$row['tag_id'];
            $tagResult = $diary->db->query($tagSql);
            if($tagInfo = $tagResult->fetch_assoc()) {
                $tagList[$row['tag_id']] = $tagInfo + $row;
            }
        }
        return $tagList;
    }

    /**
     * 获取当前tag的日报信息
     */
    public static function getTagDailys($diary, $tag_id) {
        $sql = "select * from `diary_info` where `id` in (select `diary_id` from `diary_daily_tag` where `tag_id` = $tag_id)";
        $result = $diary->db->query($sql);
        $dailys = array();
        while($daily = $result->fetch_array(MYSQLI_ASSOC)) {
            $dailys[$daily['id']] = $daily;
        }
        return $dailys;
    }

    /**
     * 获取日报的tag名列表
     */
    public static function getDailyTagName($diary, $daily_id) {
        $sql = "select * from `diary_daily_tag` where `diary_id` = $daily_id";
        $result = $diary->db->query($sql);
        $tagList = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $tagSql = "select * from `diary_tag` where `id` = ".$row['tag_id'];
            $tagResult = $diary->db->query($tagSql);
            if($tagInfo = $tagResult->fetch_assoc()) {
                $tagList[] = $tagInfo['tag'];
            }
        }
        return $tagList;
    }

    public static function getReportDailys($diary, $firstTime, $lastTime, $uid = null) {
        $uid = $uid ? $uid :$diary->uid;
        $sql = "SELECT `date` FROM `diary_report_record` where `uid` = $uid and `type` = 'daily' and `date` between '$firstTime' and '$lastTime' group by `date`";
        $result = $diary->db->query($sql);
        $dailys = array();
        while($row = $result->fetch_assoc()){
            $dailys[] = $row['date'];
        }
        return $dailys;
    }

    public static function getReportWeeklys($diary, $weeks, $uid = null) {
        $uid = $uid ? $uid :$diary->uid;
        $sql = "SELECT `date` FROM `diary_report_record` where `uid` = $uid and `type` = 'weekly' and `date` in ($weeks) group by `date`";
        $result = $diary->db->query($sql);
        $weeklys = array();
        while($row = $result->fetch_assoc()){
            $weeklys[] = $row['date'];
        }
        return $weeklys;
    }

    /**
     * 获取无内容的日报
     */
    public static function getHasContentDates($diary, $firstTime, $lastTime, $type, $uid = null) {
        $uid = $uid ? $uid :$diary->uid;
        $uninStr = '%Y-%m-%d';
        $sql = "select FROM_UNIXTIME(`show_time`, '$uninStr') as new_time from `diary_info` where `type` = $type and `uid` = $uid and (`show_time` between $firstTime and $lastTime) group by `new_time`";
        $result = $diary->db->query($sql);
        $infoList = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $infoList[] = $type == 1 ? $row['new_time'] : date('Y-W', strtoTime($row['new_time']));
        }
        return $infoList;
    }

    /**
     * 获取当前时间点到今天或本周的forward值
     */
    public static function getForward($time, $type = 1){
        $now = strtotime('today');
        $forward = floor(($now - $time)/($type == 1 ? 86400 : 86400*7));
        return $forward;
    }
}
