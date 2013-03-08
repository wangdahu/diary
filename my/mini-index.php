<?php
$title = "写日报";
$object = date('Y-m-d',time());
$startTime = strtotime($object);
$type = 'daily';
$weekarray = array("日","一","二","三","四","五","六");
$w = date('w', time());
$showTitle = date('y年m月d日',time()).' 周'.$weekarray[$w];

$isReported = DiaryReport::checkReport($diary, $type, $object);
$allowPay = false;
if(!$isReported) {
    $reportTime = DiarySet::reportTime($diary);
    $dailyTime = $reportTime['dailyReport']['hour'].":".$reportTime['dailyReport']['minute'];
    $isReported = $allowPay = false;
    if(time() > strtotime($object." ".$dailyTime)){ // 已过汇报时间
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
                <div class="data fl clearfix">
                    <a href="/diary/index.php/my/mini-index" class="<?php echo $type == 'daily' ? 'cur' : 'normal'?>">日报</a>
                    <a href="/diary/index.php/my/mini-weekly" class="<?php echo $type == 'weekly' ? 'cur' : 'normal'?>">周报</a>
                    <a href="/diary/index.php/my/mini-monthly" class="<?php echo $type == 'monthly' ? 'cur' : 'normal'?>">月报</a>
                </div>
            </h2>
        </div>
        <div style="background: #E3FFCA; height: 25px; line-height: 25px; text-align: center; font-size: 14px; font-family: 宋体; border-top: 1px solid #dedede; border-bottom: 1px solid #dedede;overflow-x: hidden; _overflow-x: hidden;">
            <span class="fl content_bar"><?php echo $showTitle;?></span><span class="fr content_bar" style="color: #006cff;"><?php if($isReported):?>已汇报 <?php elseif($allowPay):?>未汇报<?php else:?> 等待汇报<?php endif;?></span>
        </div>
        <div class="content_bar" style="border-bottom: 1px solid #dedede;">
            <?php $height = $isReported ? '445px' : '370px';?>
            <iframe name="mainDaily" src="/diary/index.php/my/main-daily" style="height: <?php echo $height;?>; margin-left: -10px; width: 300px;" frameborder="0"></iframe>
        </div>
        <?php if(!$isReported):?>
    <div class="content_bar mt10">
        <div id="daily-form">
            <div class="ftextarea" style="width: 100px;">
                <label id="placeholder" style="width: 247px; padding-left: 5px; height: 40px; line-height: 40px; color: rgb(186, 186, 186); position: absolute;">来，随手记录您今天的工作！</label>
                <input type="hidden" name="daily_id" id="daily_id" />
                <textarea contenteditable="true" id="content" name="content" style="height: 30px; background: #fff; line-height: 30px; width: 280px;" class="textarea_comment" data-limit="300"></textarea>
            </div>
            <div id="button-list" style="display: none;">
                <div class="form-action clearfix" style="margin-top: 10px;">
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
    $(document).click(function() {
        window.frames['mainDaily'].clickDoc();
    });

    $(function() {
        $('#content, #placeholder').click(function() {
            $('#placeholder').hide();
            writeContent();
        });

        function writeContent() {
            var content = $('#content');
            $('[name=mainDaily]').css('height', 250);
            content.css('height', '120px').css('line-height', '22px');
            $('#button-list').show();
            content.wordLimit();
            content.focus();
            $('.word-limit').show();
        }

        $('#submit').click(function() {
            var content = $("#content").val(),
            id = $("#daily-form").find("#daily_id").val();
            if(!content.length){
                alert('请填写日志内容');
                return false;
            }
            if($("#daily-form").find('#word_valid').val()){
                alert('输入的文字内容大于所规定的字数');
                return false;
            }
            var currentTime = <?php echo $startTime; ?>;
            $.post('createDaily', {content:content, currentTime:currentTime, id: id}, function(json){
                location.reload();
            }), 'json';
        });

        $('#reset').click(function() {
            var content = $('#content');
            content.val('');
            $('#daily_id').val('');
            $('[name=mainDaily]').css('height', 370);
            content.css('height', '30px').css('line-height', '30px');
            $('.word-limit').hide();
            $('#button-list').hide();
            $('#placeholder').show();
        });
    });
</script>
</html>
