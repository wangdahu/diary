<?php
$title = "我的日志";
$type = 'daily';
$weekarray = array("日","一","二","三","四","五","六");

$uid = $diary->uid;
$corpId = $diary->corpId;

// 向前向后翻天
$forward = isset($_GET['forward']) ? (int)$_GET['forward'] : 0;
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
$endTime = $startTime + 86400 - 1;

// 该企业该用户在选择时间内的日报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 1 and `show_time` between $startTime and $endTime order by id desc";
$result = $diary->db->query($rowsSql);
$dailys = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $dailys[] = $row;
};
$num = count($dailys);

include dirname(dirname(__FILE__))."/class/DiaryDaily.php";
$userTags = DiaryDaily::getUserTags($diary);
?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/my/top.php"; ?>

<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
        <h2 class="content_tit clearfix">
            <p>今日工作：<em><?php echo $num;?> 项</em></p>
        </h2>
        <?php foreach($dailys as $daily):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <div>
                    <p><?php echo nl2br($daily['content']); ?></p>
                </div>
                <br />
                <div style="float: right; margin-top: -20px;">
                    <?php $tagList = array(); $tagList = DiaryDaily::getDailyTag($diary, $daily['id']);?>
                    <span>
                        <?php foreach($tagList as $tag):?>
                        <span style="margin: 3px;padding:2px; background-color: <?php echo $tag['color']?>">
                            <?php echo $tag['tag'];?>
                        </span>
                        <?php endforeach;?>
                    </span>
                    <span style="margin: 0 24px 0 4px;"><a href="javascript:;" style="padding: 0 4px;" class="add_tag"></a><span>
                    <span style="margin: 0 4px 0 24px;">
                        <a href="javascript:;" style="padding: 0 4px;" data-id="<?php echo $daily['id'];?>" class="js-del-all delete"></a>
                    </span>
                    <span style="padding-left: 25px;">
                        <?php echo date('y-m-d H:i', $daily['fill_time']);?>
                    </span>
                </div>
                <div style="border: 1px #ccc solid; width: 200px; line-height: 24px;">
                    <?php foreach($userTags as $tag):?>
                    <div>
                        <label>
                            <div style="float: left;margin: 5px 5px 5px 10px;"><input type="checkbox"/></div>
                            <div class="color-list" style="float: left; margin: 5px 3px; background-color: <?php echo $tag['color'];?>">
                            </div>
                            <div ><?php echo $tag['tag']?></div>
                        </label>
                    </div>
                    <?php endforeach;?>
                    <div style="line-height: 30px;">
                        <div style="border-top: 1px solid #ccc"></div>
                        <div style="margin-left: 30px;"><a href="javascript:;" data-id="<?php echo $daily['id'];?>" class="js-del-all">删除所有标签</a></div>
                        <div style="border-top: 1px solid #ccc"></div>
                        <div style="margin-left: 30px;"><a href="javascript:;">添加标签</a></div>
                        <div style="margin-left: 30px;"><a href="javascript:;">管理标签</a></div>
                    </div>
                </div>
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
                        location.reload();
                    }), 'json';
                },
                "取消": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                $(this).dialog("close");
            }
        });
        $(".write-daily").button().click(function(){$("#dialog-form").dialog("open");});

        // 删除某日志的所有标签
        $(".js-del-all").click(function(){
            var id = $(this).attr('data-id');
            if(confirm("确定要删除这条日志的所有标签？")){
                $.post('/diary/index.php/set/operateTag', {id:id, action:'del-diary-all-tag'}, function(json){
                    if(json != 0){
                        location.reload();
                    }else{
                        return false;
                    }
                });
            }
            return false;
        });
    });
</script>
