<?php
$title = "写周报";
$type = 'weekly';
// 当前周的周一时间戳
$startTime = date('w') == 1 ? strtotime("this Monday") : strtotime("-1 Monday");

$endTime = $startTime + 7*86400 - 1;
$showTitle = date('y年m月d日', $startTime)."--".date('y年m月d日', $endTime);

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
$dailyNum = 0;
while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $dailyNum ++;
    $dailys[date('y.m.d', $row['show_time'])][] = $row;
};

// 判断是否为补交/未汇报/已汇报
$isReported = DiaryReport::checkReport($diary, $type, $object);
$allowPay  = false;
if(!$isReported) {
    // 是否已过汇报时间
    $reportTime = DiarySet::reportTime($diary);
    $w = date('w') ? date('w') : 7; // 周日转换成7
    $weeklyTime = $reportTime['weeklyReport']['hour'].":".$reportTime['weeklyReport']['minute'];
    if($w > $reportTime['weeklyReport']['w'] || ($w == $reportTime['weeklyReport']['w'] && time() > strtotime(date('Y-m-d')." ".$weeklyTime))) { // 已过汇报时间
        $allowPay  = true;
    }
}
?>

<?php include "views/my/mini-top.php"; ?>

<style>
.content {width: 300px; height: 495px;}
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
            <?php $height = $isReported ? '435px' : '370px';?>
            <iframe name="mainWeekly" src="/diary/index.php/my/main-weekly" style="height: <?php echo $height;?>; margin-left: -10px; width: 302px;" frameborder="0"></iframe>
        </div>
        <?php if(!$isReported):?>
        <div class="content_bar mt10">
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
        <div id="weekly-form">
            <div class="ftextarea">
                <textarea style="width: 270px; height: 40px; line-height: 40px;" placeholder="来，随手记录您本周的工作" contenteditable="true" id="weekly_content" class="textarea_comment" data-limit="1000"></textarea>
                <input type="hidden" value="" id="weekly_id" name="weekly_id"/>
            </div>
            <div class="mt10 insertDaily" style="display: none;">插入：
                <?php foreach($weekarray as $k => $w):?>
                <span class="mr5 p1 <?php echo in_array($allDate[$k], $date_keys) ? 'js-insert-daily' : ''?>" style="border:1px solid #ccc;"><?php echo '周'.$w?></span>
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

</div>
</body>

<script>
    $(function() {
        $('#weekly_content').click(function() {
            window.frames['mainWeekly'].editContent();
            $('.word-limit').show();
        });

        $('#reset').click(function() {
            var content = $('#weekly_content');
            content.val('');
            $('[name=mainWeekly]').css('height', 370);
            content.css('height', '40px').css('line-height', '40px');
            $('.word-limit').hide();
            $('.insertDaily').hide();
            $('#button-list').hide();
        });

        $('#submit').click(function() {
            var content = $("#weekly_content").val(),
            id = $("#weekly_id").val();
            if(!content.length){
                alert('请填写日志内容');
                return false;
            }
            if($("#weekly-form").find('#word_valid').val()){
                alert('输入的文字内容大于所规定的字数');
                return false;
            }
            var currentTime = '<?php echo $startTime; ?>';
            $.post('createWeekly', {content:content, currentTime:currentTime, id:id}, function(json){
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

        // 插入日报
        $('.js-insert-daily').click(function(){
            var html = $(this).next().html();
            TA.insertAtPoint($('#weekly_content')[0], '\n' + html + '\n');
        });

    });
</script>
</html>
