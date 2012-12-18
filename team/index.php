<?php
$title = "我的日志";

$uid = $diary->uid;
$corpId = $diary->corpId;

// 向前向后翻天
$forward = isset($_GET['forward']) ? $_GET['forward'] : 0;
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
$endTime = $startTime + 86400;

// 该企业该用户在选择时间内的日报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 1 and `report_time` between $startTime and $endTime order by id desc";
$result = $diary->db->query($rowsSql);
$dailys = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $dailys[] = $row;
};
$num = count($dailys);

?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/team/top.php"; ?>

<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
        <h2 class="content_tit clearfix">
            <p>今日工作：<em><?php echo $num;?> 项</em></p>
        </h2>
        <?php foreach($dailys as $daily):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="date"><?php echo date('y-m-d H:i', $daily['fill_time']);?></div>
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
</div>
<script>
    $(function(){
        $(".delete").click(function(){
            console.log($(this).attr('data-id'));
        });
    });
</script>
<?php include "views/layouts/footer.php"; ?>
