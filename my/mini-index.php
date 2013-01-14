<?php
$title = "写日志";
$type = 'daily';
$mini = true;
$weekarray = array("日","一","二","三","四","五","六");

$uid = $diary->uid;
$corpId = $diary->corpId;

$object = date('Y-m-d',time());
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

// 是否已过汇报时间
$reportTime = DiarySet::reportTime($diary);
$dailyTime = $reportTime['dailyReport']['hour'].":".$reportTime['dailyReport']['minute'];
$isReported = $allowPay = false;
if(time() > strtotime($object." ".$dailyTime)){ // 已过汇报时间
    $isReported = DiaryReport::checkReport($diary, $type, $object);
    $allowPay  = $isReported ? false : true;
    $showCommit = $isReported;
}
?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/my/mini-top.php"; ?>

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
                <div class="clearfix diary-operation" >
    <?php
        $tagList = array();
        $tagList = DiaryDaily::getDailyTag($diary, $daily['id']);
        $tagIds = array_keys($tagList);
    ?>
                    <span class="daily-date"><?php echo date('y-m-d H:i', $daily['fill_time']);?></span>
                    <?php if(!$isReported):?>
                    <a href="javascript:;" data-diary_id="<?php echo $daily['id'];?>" class="delete js-del_daily"></a>
                    <div class="add-tag-wrapper">
                        <a href="javascript:;"  class="add_tag js-opterate_tag"></a>
                        <div class="js-all-tag all-tag-floor" >
                            <?php foreach($userTags as $tag):?>
                            <div>
                                <label>
                                    <div style="float: left;margin: 5px 5px 5px 10px;">
                                        <input type="checkbox" <?php echo in_array($tag['id'], $tagIds) ? 'checked' : ''?> name="tag" class="js-operate_tag" id="tag_<?php echo $tag['id']?>" data-diary_id="<?php echo $daily['id'];?>" data-tag_id="<?php echo $tag['id'];?>"/>
                                    </div>
                                    <div class="color-list" style="float: left; margin: 6px 3px; background-color: <?php echo $tag['color'];?>">
                                    </div>
                                    <div ><?php echo $tag['tag']?></div>
                                </label>
                            </div>
                            <?php endforeach;?>
                            <?php include "tag.php"; ?>
                        </div>
                    </div>
                    <?php endif;?>
                    <span class="tag-list" id="tag-list-<?php echo $daily['id'];?>">
                        <?php foreach($userTags as $tag):?>
                        <div class="js-tag" id="diary_tag_<?php echo $tag['id'];?>" data-tag_id="<?php echo $tag['id'];?>" data-diary_id="<?php echo $daily['id'];?>" style="float: left; margin: 0 4px; background-color: <?php echo $tag['color']?>; <?php echo in_array($tag['id'], $tagIds) ? '' : 'display: none;'?>">
                            <div title="<?php echo $tag['tag'];?>" id="tag-<?php echo $tag['id'];?>" class="ellipsis" style="max-width: 120px; float: left; ">
                                <span style="margin:4px;">
                                    <?php echo $tag['tag'];?>
                                </span>
                            </div>
                            <?php if(!$isReported):?>
                            <div class="js-del_tag" style="float: left; display:none;">
                                <a href="javascript:;" class="dtag"></a>
                            </div>
                            <?php endif;?>
                        </div>
                        <?php endforeach;?>
                    </span>
                </div>
            </div>
        </div>
        <div class="c_b"></div>
        <?php endforeach;?>
        <?php endif;?>
    </div>
</div>

<?php include "views/layouts/footer.php"; ?>
<?php include "views/set/addTag.php"; ?>
<?php include "views/my/createDaily.php"; ?>

<script>
    $(function() {
        // 删除日志
        $(".js-del_daily").click(function(){
            var id = $(this).attr('data-diary_id');
            if(confirm("确定要删除这条日志？")){
                $.post('/diary/index.php/my/delDiary', {id:id}, function(json){
                    if(json != 0){
                        location.reload();
                    }else{
                        return false;
                    }
                });
            }
        });

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

        // 操作标签
        $('.js-opterate_tag').click(function(){
            $(this).next().toggle();
        });
        // 点击其他地方隐藏
        $(document).click(function(e) {
            var target = $(e.target);
            if(target && target.is('.js-opterate_tag')) {
                return;
            }
            $('.js-opterate_tag').next().hide();
        });
        $('.js-all-tag').click(function(e) {
            e.stopPropagation();
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