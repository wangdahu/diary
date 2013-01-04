<?php
$title = "月报";
$type = 'monthly';

// 向前向后翻月
$forward = isset($_GET['forward']) ? (int)$_GET['forward'] : 0;
if($forward){
    $forwardMonths = $forward + 1;
    $backwardMonths = $forward - 1;
    $currentMonth = date('y年m月', mktime(0, 0, 0, date("m")-$forward, 1, date("Y")));
    $startTime = mktime(0,0,0,date("m")-$forward,1,date("Y"));
    $endTime = mktime(0,0,0,date("m")-$forward+1,1,date("Y")) - 1;
}else{
    $forwardMonths = 1;
    $backwardMonths = -1;
    $currentMonth = date('y年m月');
    $startTime = mktime(0,0,0,date("m")-$forward,1,date("Y"));
    $endTime = mktime(0,0,0,date("m")+1,1,date("Y")) - 1;
}

// 查看的年份和月份
$object = date('Y-m', $endTime);
$uid = $diary->uid;

// 判断是否为补交/未汇报/已汇报
if($forward < 0) { // 未来
    $isReported = $allowPay = false;
}else if($forward == 0) { // 本月
    // 是否已过汇报时间
    include dirname(dirname(__FILE__))."/class/DiarySet.php";
    $reportTime = DiarySet::reportTime($diary);
    $dailyTime = $reportTime['monthlyReport']['date']." ".$reportTime['monthlyReport']['hour'].":".$reportTime['monthlyReport']['minute'];
    $isReported = $allowPay = false;
    if(time() > strtotime($object."-".$dailyTime)){ // 未到汇报时间
        include dirname(dirname(__FILE__))."/class/DiaryReport.php";
        $isReported = DiaryReport::checkReport($diary, $type, $currentDate);
        $allowPay  = $isReported ? false : true;
    }
}else{ // 过去
    include dirname(dirname(__FILE__))."/class/DiaryReport.php";
    $isReported = DiaryReport::checkReport($diary, $type, $currentDate);
    $allowPay = $isReported ? false : true;
}
if($isReported){
    // 查询汇报总人数
    $reportCount = DiaryReport::getReportCount($diary, $type, $currentDate);
}
?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/my/top.php"; ?>
<?php include "views/team/monthly.php"; ?>
<?php include "views/layouts/footer.php"; ?>

<div id="dialog-form" title="写月报">
    <form>
        <fieldset>
            <textarea cols="60" rows="12" id="daily_content"></textarea>
        </fieldset>
        <div >
            <?php for($w = 0; $w < 5; $w++):?>
            <span >
                <?php echo $weekArr[$w]?>
            </span>
            <?php endfor;?>
        </div>
    </form>
    <div></div>
</div>

<script>
    $(function() {
        $("#dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 530,
            modal: true,
            buttons: {
                "写月报": function(){
                    var content = $("#daily_content").val();
                    if(!content.length){
                        alert('请填写日志内容');
                        return false;
                    }
                    var currentTime = <?php echo $startTime; ?>;
                    $.post('createDaily', {content:content, currentTime:currentTime}, function(json){
                        location.reload();
                    }), 'json';
                },
                "取消": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                allFields.val("").removeClass("ui-state-error");
            }
        });
        $(".write-monthly").button().click(function(){$("#dialog-form").dialog("open");});
    });
</script>
