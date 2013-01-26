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

$defaultColorId = rand(1,20);
$showCommit = false;
// 判断是否为补交/未汇报/已汇报
if($forward < 0) { // 未来
    $isReported = $allowPay = false;
}else if($forward == 0) { // 今天
    $isReported = DiaryReport::checkReport($diary, $type, $object);
    if($isReported) {
        $allowPay  = false;
        $showCommit = true;
    }else {
        // 是否已过汇报时间
        $reportTime = DiarySet::reportTime($diary);
        $dailyTime = $reportTime['dailyReport']['hour'].":".$reportTime['dailyReport']['minute'];
        $allowPay = false;
        if(time() > strtotime($object." ".$dailyTime)){ // 已过汇报时间
            $allowPay = $showCommit = true;
        }
    }
}else{ // 过去
    $isReported = DiaryReport::checkReport($diary, $type, $object);
    $allowPay = $isReported ? false : true;
    $showCommit = true;
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
            <a href="javascript:" class="fr pay-diary js-pay_diary"></a>
            <?php else:?>
            <a class="fr pay-disabled"></a>
            <?php endif;?>
            <?php endif;?>
            <?php if(!$isReported):?>
            <a href="javascript:" class="write-<?php echo $type?> fr"></a>
            <?php endif;?>
        </h2>
        <?php if(!$num):?>
        <div class="c_c mt10">
            <div class="c_c_c">
                <div>
                    <p style="font-size: 16px;color: red; text-align: center; line-height: 100px;">
                        <strong>还未填写任何日志内容</strong>
                    </p>
                </div>
            </div>
        </div>
        <?php else:?>
        <?php foreach($dailys as $daily):?>
        <div class="c_c mt10">
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
                                <label class="clearfix" style="cursor:default;">
                                    <input type="checkbox" <?php echo in_array($tag['id'], $tagIds) ? 'checked' : ''?> name="tag" class="js-operate_tag" id="tag_<?php echo $tag['id']?>" data-diary_id="<?php echo $daily['id'];?>" data-tag_id="<?php echo $tag['id'];?>" style="line-height:24px;height:24px;float: left;margin: 0 5px 0 10px;"/>
                                    <div class="color-list" style="float: left; margin: 6px 10px 6px 5px; background-color: <?php echo $tag['color'];?>">
                                    </div>
                                    <div class="ellipsis" title="<?php echo $tag['tag']?>" ><?php echo $tag['tag']?></div>
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
                                <?php $url = "/diary/index.php/my/tagDaily?tag=".$tag['id']; ?>
                                <a style="text-decoration: none;" href="<?php echo $url;?>">
                                <span style="margin:4px;">
                                    <?php echo $tag['tag'];?>
                                </span>
                                </a>
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
        <?php endforeach;?>
        <?php endif;?>
    </div>
    <!--今日工作结束-->
    <?php if($showCommit):
          include dirname(dirname(__FILE__))."/team/comment.php";
    endif;?>
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
            var diary_id = $(this).attr('data-diary_id'),
            all_tag = $(this).closest('.add-tag-wrapper').next(),
            all_tag_floor = $(this).closest('.all-tag-floor');
            if(confirm("确定要取消这条日志的所有标签？")){
                $.post('/diary/index.php/set/operateTag', {diary_id:diary_id, action:'del-diary-all-tag'}, function(json){
                    if(json != 0){
                        all_tag_floor.find('.js-operate_tag').prop('checked', false);
                        all_tag.find('.js-tag').hide();
                    }else{
                        return false;
                    }
                });
            }
            return false;
        });

        // 单个标签操作
        $(".js-tag").mouseover(function(){
            $(this).find('.js-del_tag').show();
        }).mouseout(function(){
            $(this).find('.js-del_tag').hide();
        });

        // 标签操作，点击其他地方隐藏
        $(document).click(function(e) {
            var target = $(e.target);
            if(target.is('.js-opterate_tag')) {
                target.next().toggle();
            }
            $('.js-opterate_tag').not(target).next().hide();
        });
        $('.js-all-tag').click(function(e) {
            e.stopPropagation();
        });

        // 标签列表的标签操作
        $(".js-del_tag").click(function(){
            var js_tag = $(this).closest('.js-tag'),
            diary_id = js_tag.attr('data-diary_id'),
            tag_id = js_tag.attr('data-tag_id');
            var tag_input = js_tag.parent().prev().find("#tag_"+tag_id);
            $.post('/diary/index.php/set/operateTag', {diary_id:diary_id, tag_id:tag_id, action:'del-diary-tag'}, function(json) {
                if(json != 0) {
                    tag_input.prop('checked', false);
                    js_tag.toggle();
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
            action = !!$(this).prop('checked') ? 'add-diary-tag' : 'del-diary-tag';
            var len = $(this).closest('.js-all-tag').find(':checked').length;
            if(len > 5){
                this.checked = false;
                alert('单个日志不能超过五个标签哦');
                return false;
            }
            $.post('/diary/index.php/set/operateTag', {diary_id:diary_id, tag_id:tag_id, action:action}, function(json) {
                if(json != 0) {
                    $('#tag-list-'+diary_id).find('#diary_tag_'+tag_id).toggle();
                }else {
                    alert('操作失败');
                }
            });
        });

        $('.c_c').mouseenter(function() {
            $(this).find('.add-tag-wrapper,.delete').css('visibility', 'visible');
        }).mouseleave(function() {
            $(this).find('.add-tag-wrapper,.delete').css('visibility', 'hidden');
        });
    });
</script>
