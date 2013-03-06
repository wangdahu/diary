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
body { overflow-x: hidden; _overflow-x: hidden; }
html,body { padding: 0; margin: 0;overflow-y: hidden; _overflow-y: hidden; overflow-x: hidden; _overflow-x: hidden;}
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
            </h2>
        </div>
        <div style="background: #E3FFCA; height: 25px; line-height: 25px; text-align: center; font-size: 14px; font-family: 宋体; margin-bottom: 5px; border-top: 1px solid #dadada; border-bottom: 1px solid #dadada;overflow-x: hidden; _overflow-x: hidden;">
            <span class="fl content_bar"><?php echo $showTitle;?></span><span class="fr content_bar" style="color: #006cff;"><?php if($isReported):?>已汇报 <?php elseif($allowPay):?>未汇报<?php else:?> 等待汇报<?php endif;?></span>
        </div>
        <div class="content_bar">
            <?php $height = $isReported ? '445px' : '360px';?>
            <iframe name="mainDaily" src="/diary/index.php/my/main-daily" style="height: <?php echo $height;?>; margin-left: -10px; width: 300px;" frameborder="0"></iframe>
        </div>
        <?php if(!$isReported):?>
    <div class="content_bar mt10">
        <div id="daily-form">
            <div class="ftextarea" style="width: 100px;">
                <label id="placeholder" style="width: 147px; height: 50px; line-height: 50px; color: rgb(186, 186, 186); position: absolute;">来，随手记录您今天的工作</label>
                <input type="hidden" name="daily_id" id="daily_id" />
                <textarea contenteditable="true" id="content" name="content" style="height: 40px; background: #fff; line-height: 40px; width: 280px;" class="textarea_comment" data-limit="300"></textarea>
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
            $('[name=mainDaily]').css('height', 360);
            content.css('height', '40px').css('line-height', '40px');
            $('.word-limit').hide();
            $('#button-list').hide();
            $('#placeholder').show();
        });
    });
</script>
</html>
