<?php
$title = "周报-团队日志";
$type = 'weekly';

// 当前周的周一时间戳
$mondayTime = date('w') == 1 ? strtotime("this Monday") : strtotime("-1 Monday");
// 向前向后翻天
$forward = isset($_GET['forward']) ? $_GET['forward'] : 0;
if($forward){
    $forwardWeeks = $forward + 1;
    $backwardWeeks = $forward - 1;
    $startTime = $mondayTime - $forward*86400*7;
}else{
    $forwardWeeks = 1;
    $backwardWeeks = -1;
    $startTime = $mondayTime;
}
$endTime = $startTime + 7*86400 - 1;
// 查看的年份和周
$object = date('Y-W', $endTime);

$weekDate = array();
// 用户设置的工作时间
include dirname(dirname(__FILE__))."/class/DiarySet.php";
$selected = DiarySet::workingTime($diary, $diary->uid);

$weekarray = array("一","二","三","四","五","六","日");
// 当前周的所有工作天
for($i = 6; $i >= 0; $i--){
    $time = $startTime + 86400*$i;
    $weekDate[$weekarray[$i]] = date('y.m.d', $time);
}

$uid = (int) $_GET['uid'];
include dirname(dirname(__FILE__))."/class/User.php";
$user = User::getInfo($uid);
$corpId = $user['corp_id'];

// 该企业该用户在选择时间内的周报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 1 and `show_time` between $startTime and $endTime order by id desc";
$result = $diary->db->query($rowsSql);

$dailys = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $dailys[date('y.m.d', $row['show_time'])][] = $row;
};
$num = count($dailys);

?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/team/view-top.php"; ?>
<div class="content">
    <!--本周工作开始-->
    <?php foreach($weekDate as $k => $v):?>
    <div class="content_bar mb25">
        <div>
            <div style="background: #ccc; height:30px;">
                <p style="line-height:30px;">
                <strong><?php echo "周".$k." ".$v; ?></strong>
                <span>工作：<?php echo count($dailys[$v]);?>项</span>
                </p>
            </div>
            <?php if($dailys[$v]): foreach($dailys[$v] as $date => $daily):?>
            <div class="c_t_1 mt10"></div>
            <div class="c_c_1" >
                <div class="date"><?php echo date('填写y-m-d H:i:s', $daily['fill_time']), date('汇报y-m-d H:i:s', $daily['report_time']);?></div>
                <div class="c_c_c">
                    <p><?php echo nl2br($daily['content']); ?></p>
                </div>
                <?php if($daily['report_time'] > $daily['fill_time']):?>
                <a href="javascript:" class="delete" title="可编辑可删除" data-id="<?php echo $daily['id'];?>"></a>
                <?php endif;?>
            </div>
            <div class="c_b_1"></div>
            <?php endforeach; endif;?>
        </div>

    </div>
    <?php endforeach;?>
    <!--本周工作结束-->
    <?php include "comment.php"; ?>
</div>
<?php include "views/layouts/footer.php"; ?>
