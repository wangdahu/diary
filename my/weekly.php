<?php
$title = "周报";
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
// 当前时间为 当前年的多少周
$object = date('Y-W', $startTime);
$weekDate = array();
// 用户设置的工作时间
$selected = DiarySet::workingTime($diary, $diary->uid);
$weekarray = array("一","二","三","四","五","六","日");

// 当前周的所有工作天
for($i = 6; $i >= 0; $i--){
    $time = $startTime + 86400*$i;
    $weekDate[$weekarray[$i]] = date('y.m.d', $time);
    $dateForwards[date('y.m.d', $time)] = $time;
}
$corpId = $diary->corpId;
$uid = $diary->uid;

// 该企业该用户在选择时间内的日报
$dailySql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 1 and `show_time` between $startTime and $endTime order by id desc";
$result = $diary->db->query($dailySql);

$dailys = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $dailys[date('y.m.d', $row['show_time'])][] = $row;
};

$showCommit = false;
// 判断是否为补交/未汇报/已汇报
if($forward < 0) { // 未来
    $isReported = $allowPay = false;
}else if($forward == 0) { // 本周
    $isReported = $allowPay = false;
    // 是否已过汇报时间
    $reportTime = DiarySet::reportTime($diary);
    $w = date('w') ? date('w') : 7; // 周日转换成7
    $weeklyTime = $reportTime['weeklyReport']['hour'].":".$reportTime['weeklyReport']['minute'];
    if($w > $reportTime['weeklyReport']['w'] || ($w == $reportTime['weeklyReport']['w'] && time() > strtotime(date('Y-m-d')." ".$weeklyTime))) { // 已过汇报时间
        $isReported = DiaryReport::checkReport($diary, $type, $object);
        $allowPay  = $isReported ? false : true;
        $showCommit = true;
    }
}else{ // 过去
    $isReported = DiaryReport::checkReport($diary, $type, $object);
    $allowPay = $isReported ? false : true;
    $showCommit = true;
}

?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/my/top.php"; ?>
<?php include "views/team/weekly.php"; ?>
<?php include "views/layouts/footer.php"; ?>
<?php
$date_keys = array_keys($dailys);
foreach($dailys as $date => $daily){
    foreach($daily as $k => $one){
        $tagStr = '';
        $tagNameList = DiaryDaily::getDailyTagName($diary, $one['id']);
        foreach($tagNameList as $tagName){
            $tagStr .= '【'.$tagName.'】';
        }
        $dailys[$date][$k]['tagStr'] = $tagStr;
        $dailys[$date][$k]['filltime'] = date('H:i', $one['fill_time']);
    }
}

for($i = $startTime; $i < $endTime; $i+=86400) {
    $key = date('y.m.d', $i);
    $allDate[] = $key;
}

?>
<div id="weekly-dialog-form" title="写周报" style="display: none">
    <form>
        <fieldset>
            <textarea style="width: 95%; height: 220px;" id="weekly_content" data-limit="1000"></textarea>
            <input type="hidden" value="" id="weekly_id" name="weekly_id"/>
        </fieldset>
        <div class="mt10">插入日报：
            <?php foreach($weekarray as $k => $w):?>
            <span class="mr10 p3 <?php echo in_array($allDate[$k], $date_keys) ? 'js-insert-daily' : ''?>" style="border:1px solid #ccc;"><?php echo '周'.$w?></span>
<?php if(in_array($allDate[$k], $date_keys)):?>
<script type="text/string"><?php echo '周'.$w.' '.$allDate[$k]."\n"?>
<?php foreach($dailys[$allDate[$k]] as $one):?>
<?php echo $one['filltime'].' '.$one['tagStr']."\n"?>
<?php echo $one['content']."\n"?>
<?php endforeach;?>
</script>
<?php endif;?>
            <?php endforeach;?>
        </div>
    </form>
    <div></div>
</div>

<script>
    $(function() {
        $("#weekly-dialog-form").dialog({
            autoOpen: false,
            height: 350,
            width: 520,
            modal: true,
            open: function(){
                $("#weekly_content").select();
                $('#weekly_content').wordLimit();
            },
            buttons: {
                "写周报": function(){
                    var content = $("#weekly_content").val(),
                    id = $("#weekly-dialog-form").find("#weekly_id").val();
                    if(!content.length){
                        alert('请填写日志内容');
                        return false;
                    }
                    if($("#weekly-dialog-form").find('#word_valid').val()){
                        alert('输入的文字内容大于所规定的字数');
                        return false;
                    }
                    var currentTime = '<?php echo $startTime; ?>';
                    $.post('createWeekly', {content:content, currentTime:currentTime, id:id}, function(json){
                        location.reload();
                    }), 'json';
                },
                "取消": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
            }
        });

        // 新增和编辑周报
        $(".write-weekly").button().click(function(){
            if($('.js-edit_diary').length){
                $(".js-edit_diary").click();
            }else{
                $("#weekly_content").html('');
                $("#weekly_id").val(0);
                $("#weekly-dialog-form").dialog("open");
            }
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

        // 插入日报
        $('.js-insert-daily').click(function(){
            var html = $(this).next().html();
            TA.insertAtPoint($('#weekly_content')[0], '\n' + html + '\n');
        });

        // 编辑周报
        $(".js-edit_diary").click(function(){
            var content = $(this).find("div").html();
            $("#weekly_content").val(content);
            $("#weekly-dialog-form").find("#weekly_id").val($(this).attr('data-weekly_id'));
            $("#weekly-dialog-form").dialog("open");
        });

    });
</script>
