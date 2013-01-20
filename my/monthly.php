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
$corpId = $diary->corpId;

// 判断是否为补交/未汇报/已汇报
$showCommit = false;
if($forward < 0) { // 未来
    $isReported = $allowPay = false;
}else if($forward == 0) { // 本月
    // 是否已过汇报时间
    $reportTime = DiarySet::reportTime($diary);
    $dailyTime = $reportTime['monthlyReport']['date']." ".$reportTime['monthlyReport']['hour'].":".$reportTime['monthlyReport']['minute'];
    $isReported = $allowPay = false;
    if(time() > strtotime($object."-".$dailyTime)) { // 已过汇报时间
        $isReported = DiaryReport::checkReport($diary, $type, $object);
        $allowPay  = $isReported ? false : true;
        $showCommit = true;
    }
}else { // 过去
    $isReported = DiaryReport::checkReport($diary, $type, $object);
    $allowPay = $isReported ? false : true;
    $showCommit = true;
}

?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/my/top.php"; ?>
<?php include "views/team/monthly.php"; ?>
<?php include "views/layouts/footer.php"; ?>

<?php

// 获取本月的所有周报
$weeklys = array();
$weeklySql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 2 and `show_time` between $firstTime and $lastTime order by `show_time` asc";

$result = $diary->db->query($weeklySql);
while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $weeklys[date('Y-W',$row['show_time'])] = $row;
}
?>
<div id="monthly-dialog-form" title="写月报" style="display: none">
    <form>
        <fieldset>
            <textarea style="width: 95%; height: 225px;" id="monthly_content" data-limit="1000"></textarea>
            <input type="hidden" value="" id="monthly_id" name="monthly_id"/>
        </fieldset>
        <div class="mt10">插入日报：
            <?php for($w = 0; $w < $maxWeek; $w++):?>
                  <?php
                       $key = date('Y-W', $firstTime + $w*7*86400);
                       $insert = isset($weeklys[$key]);
                  ?>
            <span class="ml10 p3 <?php echo $insert ? 'js-insert-daily' : '' ?>" style="border:1px solid #ccc;">
                <?php echo $weekArr[$w]; ?>
                <?php if($insert):?>
<script type="text/string" class="insert_weekly"><?php echo date('y年m月d日', $firstTime + ($w-1)*7*86400);?> - <?php echo date('y年m月d日', $firstTime + ($w)*7*86400);?>    <?php echo $weekArr[$w]."\n";?>
<?php echo $weeklys[$key]['content'];?>
</script>
                <?php endif;?>
            </span>
            <?php endfor;?>
        </div>
    </form>
    <div></div>
</div>

<script>
    $(function() {
        $("#monthly-dialog-form").dialog({
            autoOpen: false,
            height: 350,
            width: 520,
            modal: true,
            open: function(){
                $("#monthly_content").select();
                $('#monthly_content').wordLimit();
            },
            buttons: {
                "写月报": function(){
                    var content = $("#monthly_content").val();
                    if(!content.length){
                        alert('请填写日志内容');
                        return false;
                    }
                    if($("#monthly-dialog-form").find('#word_valid').val()){
                        alert('输入的文字内容大于所规定的字数');
                        return false;
                    }
                    var currentTime = '<?php echo $startTime; ?>',
                    id = $("#monthly-dialog-form").find("#monthly_id").val();
                    $.post('createMonthly', {content:content, currentTime:currentTime, id:id}, function(json){
                        location.reload();
                    }), 'json';
                },
                "取消": function() {
                    $(this).dialog("close");
                }
            },
        });

        // 新增和编辑月报
        $(".write-monthly").click(function(){
            if($('.js-edit_diary').length){
                $(".js-edit_diary").click();
            }else{
                $("#monthly_content").html('');
                $("#monthly_id").val(0);
                $("#monthly-dialog-form").dialog("open");
            }
        });

        // 编辑月报
        $(".js-edit_diary").click(function(){
            var content = $(this).find("div").html();
            $("#monthly_content").val(content);
            $("#monthly-dialog-form").find("#monthly_id").val($(this).attr('data-monthly_id'));
            $("#monthly-dialog-form").dialog("open");
        });


        var TA = {
            select: function(textarea, start, end){
                if(document.selection){
                    var range = textarea.createTextRange();
                    range.moveEnd('character', -textarea.value.length);
                    range.moveEnd('character', end);
                    range.moveStart('character', start);
                    range.select();
                }else{
                    textarea.setSelectionRange(start, end);
                    textarea.focus();
                }

            },
            setCursorPosition: function(textarea, position) {
                this.select(textarea, position, position);
            },
            insertAtPoint: function(textarea, txt) {
                var val = textarea.value;
                if(document.selection){
                    textarea.focus()
                    document.selection.createRange().text = txt;
                } else {
                    var curPosition = textarea.selectionStart,
                    oldLength = textarea.value.length;
                    textarea.value = textarea.value.substring(0, curPosition) + txt + textarea.value.substring(curPosition, oldLength);
                    this.setCursorPosition(textarea, curPosition + txt.length);
                }
            }
        }

        // 插入周报
        $('.js-insert-daily').click(function(){
            var html = $(this).find('.insert_weekly').html();
            TA.insertAtPoint($('#monthly_content')[0], '\n' + html + '\n');
        });

    });
</script>
