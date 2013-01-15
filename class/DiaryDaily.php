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
    public static function getUserTags($diary){
        $uid = $diary->uid;
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

    public static function noReportDaily($diary, $firstTime, $lastTime, $type, $uid = null) {
        $uid = $uid ? $uid :$diary->uid;
        $uninStr = '%Y-%m-%d';
        $sql = "select FROM_UNIXTIME(`show_time`, '$uninStr') as new_time from `diary_info` where `type` = $type and `uid` = $uid and (`show_time` between $firstTime and $lastTime) group by `new_time`";
        $result = $diary->db->query($sql);
        $infoList = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $infoList[] = $type == 1 ? $row['new_time'] : date('Y-W', strtoTime($row['new_time']));
        }

        $typeArr = array(1=>'daily', 2=>'weekly', 3=> 'monthly');
        $firstDate = $type == 1 ? date('Y-m-d', $firstTime) : date('Y-W', $firstTime);
        $lastDate = $type == 1 ? date('Y-m-d', $lastTime) : date('Y-W', $lastTime);

        if($infoList){
            $reportList = array();
            $reportSql = "select * from `diary_report_record` where `uid` = $uid and `type` = '$typeArr[$type]' and (`date` between '$firstDate' and '$lastDate') group by `date`";
            $reportResult = $diary->db->query($reportSql);
            while($reportRow = $reportResult->fetch_array(MYSQLI_ASSOC)) {
                $reportList[] = $reportRow['date'];
            }
            return array_values(array_diff($infoList,$reportList));
        }
        return $infoList;
    }

    /**
     * 获取无内容的日报
     */
    public static function noContentDaily($diary, $firstTime, $lastTime, $type, $uid = null) {
        $uid = $uid ? $uid :$diary->uid;
        $uninStr = '%Y-%m-%d';
        $sql = "select FROM_UNIXTIME(`show_time`, '$uninStr') as new_time from `diary_info` where `type` = $type and `uid` = $uid and (`show_time` between $firstTime and $lastTime) group by `new_time`";
        $result = $diary->db->query($sql);
        $infoList = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $infoList[] = $type == 1 ? $row['new_time'] : date('Y-W', strtoTime($row['new_time']));
        }
        $lastTime = $lastTime > strtotime('today') ? strtoTime('today') : $lastTime; // 大于今天则值匹配今天之前的
        if($type == 1) {
            $allDateArr = array_map(function($value) {
                    return date('Y-m-d', $value);
                }, range($firstTime, $lastTime, 86400));
        }elseif($type == 2) {
            $allDateArr = array_map(function($value) {
                    return date('Y-W', $value);
                }, range($firstTime, $lastTime, 86400*7));
        }

        return array_values(array_diff($allDateArr, $infoList));
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
