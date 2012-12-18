<?php $weekarray=array("日","一","二","三","四","五","六");?>
<!--功能操作开始-->
<div class="todo clearfix">
    <a href="" class="a_01 fl">刷新</a>
    <div class="pags fl clearfix"><a href="index?forward=<?php echo $forwardDays;?>" class="up" title="上一天"></a><a href="index?forward=<?php echo $backwardDays;?>" class="down" title="下一天"></a></div>
     <p class="fl"><?php echo date('Y年m月d日', $startTime);?>（周<?php echo $weekarray[date("w", $startTime)];?>）</p>
    <div class="data fr clearfix">
        <a href="index" class="cur">今日</a>
        <a href="javascript:">本周</a>
        <a href="javascript:">本月</a>
    </div>
    <a href="javascript:" class="a_02 fr mr10 js-write-dialy"></a>
</div>
<!--功能操作结束-->

<div id="dialog-form" title="写日志">
    <form>
        <fieldset>
            <textarea cols="60" rows="12" id="daily_content"></textarea>
        </fieldset>
    </form>
</div>

<script>
    $(function() {
        $("#dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 530,
            modal: true,
            buttons: {
                "写日志": function(){
                    var content = $("#daily_content").val();
                    if(!content.length){
                        alert('请填写日志内容');
                        return false;
                    }
                    var currentTime = <?php echo $startTime; ?>;
                    $.post('createDaily', {content:content, currentTime:currentTime}, function(json){
                        // location.reload();
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
        $(".js-write-dialy").button().click(function(){$("#dialog-form").dialog("open");});
    });
</script>
