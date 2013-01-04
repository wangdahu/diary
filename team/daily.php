<?php
$title = "日报-团队日志";
$type = 'daily';
$weekarray = array("日","一","二","三","四","五","六");

// 向前向后翻天
$forward = isset($_GET['forward']) ? (int)$_GET['forward'] : 0;
$showDiary = $froward < 0 ? false : true;
if($forward){
    $forwardDays = $forward + 1;
    $backwardDays = $forward - 1;
    $currentDate = date('Y-m-d',time() - $forward*86400);
}else{
    $forwardDays = 1;
    $backwardDays = -1;
    $currentDate = date('Y-m-d',time());
}
$startTime = strtotime($currentDate);
$endTime = $startTime + 86400 - 1;

$uid = (int) $_GET['uid'];
?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/team/view-top.php"; ?>
<?php if($forward):?>
<?php
// 查看的日期
$object = date('Y-m-d', $endTime);

include dirname(dirname(__FILE__))."/class/User.php";
$user = User::getInfo($uid);
$corpId = $user['corp_id'];

// 该企业该用户在选择时间内的日报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 1 and `show_time` between $startTime and $endTime order by id desc";
$result = $diary->db->query($rowsSql);
$dailys = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $dailys[] = $row;
};
$num = count($dailys);
?>
<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
        <h2 class="content_tit clearfix">
            <p>今日工作：<em><?php echo $num;?> 项</em></p>
        </h2>
        <?php foreach($dailys as $daily):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="date"><?php echo date('填写y-m-d H:i:s', $daily['fill_time']), date('汇报y-m-d H:i:s', $daily['report_time']);?></div>
            <div class="c_c_c">
                <p><?php echo nl2br($daily['content']); ?></p>
            </div>
            <?php if($daily['report_time'] > $daily['fill_time']):?>
            <a href="javascript:" class="delete" title="可编辑可删除" data-id="<?php echo $daily['id'];?>"></a>
            <?php endif;?>
        </div>
        <div class="c_b"></div>
        <?php endforeach;?>
    </div>
    <!--今日工作结束-->
    <?php include "comment.php"; ?>
</div>
<?php endif;?>
<?php include "views/layouts/footer.php"; ?>
