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
$allowPay  = $existsContent = false;
if(!$isReported) {
    // 是否已过汇报时间
    $reportTime = DiarySet::reportTime($diary);
    $w = date('w') ? date('w') : 7; // 周日转换成7
    $weeklyTime = $reportTime['weeklyReport']['hour'].":".$reportTime['weeklyReport']['minute'];
    if($w > $reportTime['weeklyReport']['w'] || ($w == $reportTime['weeklyReport']['w'] && time() > strtotime(date('Y-m-d')." ".$weeklyTime))) { // 已过汇报时间
		$existsContent = DiaryDaily::existsContent($diary, $startTime, $endTime, 2);
        $allowPay = true;
    }
}
?>
<?php include "views/my/mini-top.php"; ?>

<style>
html,body {background: #bfc0c5; padding: 0; margin: 0;overflow-y: hidden; _overflow-y: hidden; overflow-x: hidden; _overflow-x: hidden; border: none;}
.mini-content {width: auto; height: 487px; }
</style>

<div class="mini-content">
    <!--今日工作开始-->
    <div class=" mb25">
        <div class="content_bar">
            <h2 class="content_tit clearfix" style="border: none;">
                <?php if($allowPay && $existsContent):?>
                <a href="javascript:" class="fr pay-diary js-pay_diary" style="margin-top: -2px;"></a>
                <p class="fl showObject" style="display: none;"><?php echo date('Y年m月d日', $startTime);?>--<?php echo date('Y年m月d日', $endTime);?></p>
                <?php endif;?>
                <div class="data fl clearfix">
                    <a href="/diary/index.php/my/mini-index" class="<?php echo $type == 'daily' ? 'cur' : 'normal'?>">日报</a>
                    <a href="/diary/index.php/my/mini-weekly" class="<?php echo $type == 'weekly' ? 'cur' : 'normal'?>">周报</a>
                    <a href="/diary/index.php/my/mini-monthly" class="<?php echo $type == 'monthly' ? 'cur' : 'normal'?>">月报</a>
                </div>
            </h2>
        </div>
        <div style="background: #E3FFCA; height: 25px; line-height: 25px; text-align: center; font-size: 14px; font-family: 宋体; border-top: 1px solid #dedede; border-bottom: 1px solid #dedede;">
            <span class="fl content_bar"><?php echo $showTitle;?></span><span class="fr content_bar" style="color: #006cff;"><?php if($isReported):?>已汇报 <?php elseif($allowPay):?>未汇报<?php else:?> 等待汇报<?php endif;?></span>
        </div>

        <div class="content_bar" style="border-bottom: 1px solid #dedede;">
            <?php $height = $isReported ? '435px' : '370px';?>
            <iframe name="mainWeekly" src="/diary/index.php/my/main-weekly" style="height: <?php echo $height;?>; margin-left: -10px; width: 300px;" frameborder="0"></iframe>
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
            <div class="ftextarea" style="width: 100px;">
                <label id="placeholder" style="width: 247px; height: 40px; padding-left: 5px; line-height: 40px; color: rgb(186, 186, 186); position: absolute;">来，随手记录您本周的工作！</label>
                <textarea style="width: 280px; height: 30px; line-height: 30px;" contenteditable="true" id="weekly_content" class="textarea_comment" data-limit="1000"></textarea>
                <input type="hidden" value="" id="weekly_id" name="weekly_id"/>
            </div>
            <div class="mt10 insertDaily" style="display: none;">
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
};
 $(function() {
        function edit() {
            window.frames['mainWeekly'].editContent();
            $('#placeholder').hide();
            $('#weekly_content').wordLimit();
            $('.word-limit').show();
        };
        $('#weekly_content').focus(edit);
        $('#placeholder').click(edit);

        $('#reset').click(function() {
            var content = $('#weekly_content');
            content.val('');
            $('[name=mainWeekly]').css('height', 370);
            content.css('height', '30px').css('line-height', '30px');
            $('.word-limit').hide();
            $('.insertDaily').hide();
            $('#button-list').hide();
            $('#placeholder').show();
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

        // 插入日报
        $('.js-insert-daily').click(function(){
            var html = $(this).next().html();
            TA.insertAtPoint($('#weekly_content')[0], '\n' + html + '\n');
        });

        // 补交
        $('.js-pay_diary').click(function() {
            var type = '<?php echo $type;?>',
            currentDate = '<?php echo $object; ?>',
            startTime = '<?php echo $startTime; ?>',
            showObject = $('.showObject').html();
            $.post('/diary/index.php/my/payDiary', {currentDate:currentDate, type:type, showObject:showObject, startTime: startTime}, function(json) {
                if(json == 0) {
                    alert('补交失败，请设置汇报对象');
                }else {
                    location.reload();
                }
            });
        });

    });
</script>
</html>
