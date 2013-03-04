<?php
$title = "写月报";
$type = 'monthly';

$startTime = mktime(0,0,0,date("m"),1,date("Y"));
$endTime = mktime(0,0,0,date("m")+1,1,date("Y")) - 1;

// 查看的年份和月份
$object = date('Y-m', $endTime);
$uid = $diary->uid;
$corpId = $diary->corpId;

// 判断是否为补交/未汇报/已汇报
$isReported = DiaryReport::checkReport($diary, $type, $object);

$allowPay  = false;
if(!$isReported) {
    // 是否已过汇报时间
    $reportTime = DiarySet::reportTime($diary);
    $dailyTime = $reportTime['monthlyReport']['date']." ".$reportTime['monthlyReport']['hour'].":".$reportTime['monthlyReport']['minute'];
    if(time() > strtotime($object."-".$dailyTime)) { // 已过汇报时间
        $allowPay = true;
    }
}
$showTitle = $currentMonth = date('y年m月');

?>
<?php include "views/my/mini-top.php"; ?>

<style>
body { overflow-x: hidden; }
html,body { padding: 0; margin: 0; }
.content {width: auto; height: 485px;}
</style>

<div class="content">
    <!--今日工作开始-->
    <div class=" mb25">
        <div class="content_bar">
            <h2 class="content_tit clearfix" style="border: none;">
                <div class="data fl clearfix">
                    <a href="/diary/index.php/my/mini-index" class="<?php echo $type == 'daily' ? 'cur' : 'normal'?>">日报</a>
                    <a href="/diary/index.php/my/mini-weekly" class="<?php echo $type == 'weekly' ? 'cur' : 'normal'?>">周报</a>
                    <a href="/diary/index.php/my/mini-monthly" class="<?php echo $type == 'monthly' ? 'cur' : 'normal'?>">月报</a>
                </div>
                <div> <a href="/diary/index.php/my/index" target="_blank" class="enter-daily fr" style="margin-top: 0;"></a> </div>
            </h2>
        </div>
        <div style="background: #E3FFCA; height: 25px; line-height: 25px; text-align: center; font-size: 14px; font-family: 宋体; margin-bottom: 5px; border-top: 1px solid #dadada; border-bottom: 1px solid #dadada;">
            <span class="fl content_bar"><?php echo $showTitle;?></span><span class="fr content_bar" style="color: #006cff;"><?php if($isReported):?>已汇报 <?php elseif($allowPay):?>未汇报<?php else:?> 等待汇报<?php endif;?></span>
        </div>

        <div class="content_bar">
            <?php $height = $isReported ? '435px' : '360px';?>
            <iframe name="mainMonthly" src="/diary/index.php/my/main-monthly" style="height: <?php echo $height;?>; margin-left: -10px; width: 285px;" frameborder="0"></iframe>
        </div>

        <?php if(!$isReported):?>
        <div class="content_bar mt10">
<?php

// 查看当前第一天和最后一天是星期几，补全最开始和结尾的格子
$maxDays = date('t', $startTime);
// 今天的日
$currentMonthDate = date('j');
if($maxDays < $currentMonthDate){
    $currentMonthDate = $maxDays;
}
// 当前月的今日的时间戳
$currentTime = $startTime + ($currentMonthDate - 1)*86400;
$currentWeekth = date('w', $currentTime); // 星期几

// 第一天是星期几，最后一天是星期几
$firstDayWeekth = date('w', $startTime);
$endDayWeekth = date('w', $endTime);
// 第一个格子的日期
if($firstDayWeekth == 0){ // 星期天
    $firstTime = $startTime - (7-1)*86400;
}else{
    $firstTime = $startTime - ($firstDayWeekth-1)*86400;
}
$currentWeek = floor(($currentTime - $firstTime)/(7*86400));
if($firstDayWeekth != 1){
    // 上月第一天的时间戳
    $currentMonthPrev = mktime(0, 0, 0, date("m")-1, 1, date("Y"));
    $prevMonth = date('m', $currentMonthPrev);
    // 计算上月最后一周
    $weekArr = array($prevMonth.'月末周', '第一周','第二周','第三周','第四周','第五周');
}else{
    $weekArr = array('第一周','第二周','第三周','第四周','第五周','第六周');
}
$maxWeek = ceil(($endTime - $firstTime)/(7*86400));
// 当前月历中的开始时间和结束时间
$lastTime = $firstTime + $maxWeek*7*86400 - 1;

        // 获取本月的所有周报
             $weeklys = array();
             $weeklySql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 2 and `show_time` between $firstTime and $lastTime order by `show_time` asc";

             $result = $diary->db->query($weeklySql);
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $weeklys[date('Y-W',$row['show_time'])] = $row;
        }
?>
        <div id="monthly-form">
            <div class="ftextarea" style="width: 100px;">
                <label id="placeholder" style="width: 147px; height: 50px; line-height: 50px; color: rgb(186, 186, 186); position: absolute;">来，随手记录您本月的工作</label>
                <textarea style="width: 260px; height: 40px; line-height: 40px;" contenteditable="true" id="monthly_content" class="textarea_comment" data-limit="1000"></textarea>
                <input type="hidden" value="" id="monthly_id" name="monthly_id"/>
            </div>
            <div class="mt10 insertWeekly" style="display: none;">
                <?php for($w = 0; $w < $maxWeek; $w++):?>
                      <?php
                           $key = date('Y-W', $firstTime + $w*7*86400);
                           $insert = isset($weeklys[$key]);
                           ?>
                      <span class="mr5 p1 <?php echo $insert ? 'js-insert-daily' : '' ?>" style="border:1px solid #ccc;"><?php echo $weekArr[$w]; ?></span>
                      <?php if($insert):?>
<script type="text/string">
<?php echo date('y年m月d日', $firstTime + ($w-1)*7*86400);?> - <?php echo date('y年m月d日', $firstTime + ($w)*7*86400);?>    <?php echo $weekArr[$w]."\n";?>
<?php echo $weeklys[$key]['content'];?>
</script>
                      <?php endif;?>
                <?php endfor;?>
            </div>

            <div id="button-list" style="display: none;">
                <div class="form-action" style="margin-top: 10px;">
                    <a class="a_01 fr" href="javascript:" id="reset">取消</a>
                    <a class="a_01 fr10" href="javascript:" id="submit">确定</a>
                </div>
            </div>
        </div>
        </div>
        <?php endif;?>
    </div>
</div>

<script>
    $(function() {
        $('#monthly_content, #placeholder').click(function() {
            window.frames['mainMonthly'].editContent();
            $('#placeholder').hide();
            $('.word-limit').show();
        });

        $('#reset').click(function() {
            var content = $('#monthly_content');
            content.val('');
            $('[name=mainMonthly]').css('height', 360);
            content.css('height', '40px').css('line-height', '40px');
            $('.word-limit').hide();
            $('.insertWeekly').hide();
            $('#button-list').hide();
            $('#placeholder').show();
        });

        $('#submit').click(function() {
            var content = $("#monthly_content").val(),
            id = $("#monthly_id").val();
            if(!content.length){
                alert('请填写日志内容');
                return false;
            }
            if($("#monthly-form").find('#word_valid').val()){
                alert('输入的文字内容大于所规定的字数');
                return false;
            }
            var currentTime = '<?php echo $startTime; ?>';
            $.post('createMonthly', {content:content, currentTime:currentTime, id:id}, function(json){
                location.reload();
            }), 'json';
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
            var html = $(this).next().html();
            TA.insertAtPoint($('#monthly_content')[0], '\n' + html + '\n');
        });

    });
</script>
