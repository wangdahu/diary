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
    $object = date('Y-m-d',time() - $forward*86400);
}else{
    $forwardDays = 1;
    $backwardDays = -1;
    $object = date('Y-m-d',time());
}
$startTime = strtotime($object);
$endTime = $startTime + 86400 - 1;

// 该企业该用户在选择时间内的日报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 1 and `show_time` between $startTime and $endTime order by id desc";
$result = $diary->db->query($rowsSql);
$dailys = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $dailys[] = $row;
};
$num = count($dailys);

$userTags = DiaryDaily::getUserTags($diary);
// 获取颜色列表
$colorList = DiaryDaily::getColorList($diary);
$tagList = DiaryDaily::getTagList($diary);

$defaultColorId = rand(1,20);

$showCommit = false;
// 判断是否为补交/未汇报/已汇报
if($forward < 0) { // 未来
    $isReported = $allowPay = false;
}else if($forward == 0) { // 今天
    // 是否已过汇报时间
    $reportTime = DiarySet::reportTime($diary);
    $dailyTime = $reportTime['dailyReport']['hour'].":".$reportTime['dailyReport']['minute'];
    $isReported = $allowPay = false;
    if(time() > strtotime($object." ".$dailyTime)){ // 已过汇报时间
        $isReported = DiaryReport::checkReport($diary, $type, $object);
        $allowPay  = $isReported ? false : true;
        $showCommit = $isReported;
    }
}else{ // 过去
    $isReported = DiaryReport::checkReport($diary, $type, $object);
    $allowPay = $isReported ? false : true;
    $showCommit = true;
}
if($showCommit){
    // 查询汇报总人数
    $reportCount = DiaryReport::getReportCount($diary, $type, $object);
}
?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/my/top.php"; ?>

<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
        <h2 class="content_tit clearfix">
            <p>今日工作：<em><?php echo $num;?> 项</em></p>
            <?php if($allowPay):?>
            <?php if($num):?>
            <a href="javascript:" class="fr mr10 pay-diary js-pay_diary"></a>
            <?php else:?>
            <a class="fr mr10 pay-disabled"></a>
            <?php endif;?>
            <?php endif;?>
            <?php if(!$isReported):?>
            <a href="javascript:" class="write-<?php echo $type?> fr mr10"></a>
            <?php endif;?>
        </h2>
        <?php if(!$num):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <div>
                    <p style="font-size: 16px;color: red; text-align: center; line-height: 100px;">
                        <strong>还未填写任何日志内容</strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="c_b"></div>
        <?php else:?>
        <?php foreach($dailys as $daily):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <?php if($isReported):?>
                <div>
                    <p><?php echo nl2br($daily['content']); ?></p>
                </div>
                <?php else:?>
                <div data-daily_id="<?php echo $daily['id']; ?>" class="js-edit_diary" style="cursor: pointer">
                    <p><?php echo nl2br($daily['content']); ?></p>
                    <div style="display:none;"><?php echo $daily['content'];?></div>
                </div>
                <?php endif;?>
                <br />
                <div style="float: right; margin-top: -20px;">
    <?php
        $tagList = array();
        $tagList = DiaryDaily::getDailyTag($diary, $daily['id']);
        $tagIds = array_keys($tagList);
    ?>
                    <span id="tag-list-<?php echo $daily['id'];?>">
                        <?php foreach($userTags as $tag):?>
                        <div class="js-tag" id="diary_tag_<?php echo $tag['id'];?>" data-tag_id="<?php echo $tag['id'];?>" data-diary_id="<?php echo $daily['id'];?>" style="float: left; margin: 0 4px; background-color: <?php echo $tag['color']?>; <?php echo in_array($tag['id'], $tagIds) ? '' : 'display: none;'?>">
                            <div title="<?php echo $tag['tag'];?>" id="tag-<?php echo $tag['id'];?>" class="ellipsis" style="max-width: 120px; float: left; ">
                                <span style="margin:4px;">
                                    <?php echo $tag['tag'];?>
                                </span>
                            </div>
                            <div class="js-del_tag" style="float: left; display:none;">
                                <a href="javascript:;">next</a>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </span>
                    <span style="margin: 0 24px 0 4px;">
                        <a href="javascript:;" style="padding: 0 4px;" class="add_tag js-opterate_tag"></a>
                    </span>
                    <span style="margin: 0 4px 0 24px;">
                        <a href="javascript:;" style="padding: 0 4px;" data-diary_id="<?php echo $daily['id'];?>" class="js-del-all delete"></a>
                    </span>
                    <span style="padding-left: 25px;">
                        <?php echo date('y-m-d H:i', $daily['fill_time']);?>
                    </span>
                </div>
                <div class="js-all-tag all-tag-floor" style="">
                    <?php foreach($userTags as $tag):?>
                    <div>
                        <label>
                            <div style="float: left;margin: 5px 5px 5px 10px;">
                                <input type="checkbox" <?php echo in_array($tag['id'], $tagIds) ? 'checked' : ''?> name="tag" class="js-operate_tag" id="tag_<?php echo $tag['id']?>" data-diary_id="<?php echo $daily['id'];?>" data-tag_id="<?php echo $tag['id'];?>"/>
                            </div>
                            <div class="color-list" style="float: left; margin: 5px 3px; background-color: <?php echo $tag['color'];?>">
                            </div>
                            <div ><?php echo $tag['tag']?></div>
                        </label>
                    </div>
                    <?php endforeach;?>
                    <?php include "tag.php"; ?>
                </div>
            </div>
        </div>
        <div class="c_b"></div>
        <?php endforeach;?>
        <?php endif;?>
    </div>
    <!--今日工作结束-->
    <?php if($showCommit):
          include dirname(dirname(__FILE__))."/team/comment.php";
    endif;?>
</div>

<script>
    $(function(){
        $(".delete").click(function(){
            console.log($(this).attr('data-id'));
        });
    });
</script>
<?php include "views/layouts/footer.php"; ?>
<?php include "views/set/addTag.php"; ?>
<?php include "views/my/createDaily.php"; ?>

<script>
    $(function() {
        // 删除某日志的所有标签
        $(".js-del-all").click(function(){
            var diary_id = $(this).attr('data-diary_id');
            if(confirm("确定要删除这条日志的所有标签？")){
                $.post('/diary/index.php/set/operateTag', {diary_id:diary_id, action:'del-diary-all-tag'}, function(json){
                    if(json != 0){
                        location.reload();
                    }else{
                        return false;
                    }
                });
            }
            return false;
        });

        // 单个标签操作
        $(".js-tag").mouseover(function(){
            $(this).find('.js-del_tag').toggle();
        }).mouseout(function(){
            $(this).find('.js-del_tag').toggle();
        });

        $('.js-opterate_tag').click(function(){
            $(this).parent().parent().next().toggle();
        });

        // 标签列表的标签操作
        $(".js-del_tag").click(function(){
            var js_tag = $(this).closest('.js-tag'),
            diary_id = js_tag.attr('data-diary_id'),
            tag_id = js_tag.attr('data-tag_id');
            var tag_input = js_tag.parent().parent().next().find("#tag_"+tag_id);
            $.post('/diary/index.php/set/operateTag', {diary_id:diary_id, tag_id:tag_id, action:'del-diary-tag'}, function(json) {
                if(json != 0) {
                    tag_input.attr('checked', false);
                    js_tag.remove();
                }else {
                    alert('操作失败');
                    return false;
                }
            });
            return false;
        });

        // 删除/添加 某日志的某个标签
        $(".js-operate_tag").change(function(){
            var diary_id = $(this).attr('data-diary_id'),
            tag_id = $(this).attr('data-tag_id'),
            action = !!$(this).attr('checked') ? 'add-diary-tag' : 'del-diary-tag';
            var len = $(this).closest('.js-all-tag').find(':checked').length;
            if(len > 5){
                $(this).attr('checked', false);
                alert('单个日志不能超过五个标签哦');
                return false;
            }
            var color = $(this).parent().next().css('background-color'),
            tag = $(this).parent().next().next().html();
            $.post('/diary/index.php/set/operateTag', {diary_id:diary_id, tag_id:tag_id, action:action}, function(json) {
                if(json != 0) {
                    $('#tag-list-'+diary_id).find('#diary_tag_'+tag_id).toggle();
                }else {
                    alert('操作失败');
                    return false;
                }
            });
            return false;
        });

    });
</script>
