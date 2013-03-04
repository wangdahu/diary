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

$isReported = DiaryReport::checkReport($diary, $type, $object);
// js加载限制
$dialogTrue = true;
?>

<?php include "views/my/mini-top.php"; ?>
<style>
.content {width: 280px; background: #fff; min-height: 0;}
.diary-content {padding: 5px;}
.c_c { padding:0px; border-radius: 0; width: 260px;}
.daily-date { float: left !important; padding-left: 5px;}
.all-tag-floor {right: 0; left: auto;}
.tag-list { float: left !important; margin-bottom: 3px;}
body{ background: #fff; overflow-x: hidden; }
html,body { padding: 0; margin: 0; }
</style>
<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
        <?php if(!$num):?>
        <div class="c_c mt10">
            <div class="c_c_c no-content-daily" style="cursor: pointer;">
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
            <div class="c_c_c" style="padding-bottom: 5px;">
                <?php if($isReported):?>
                <div class="diary-content">
                    <p><?php echo nl2br($daily['content']); ?></p>
                </div>
                <?php else:?>
                <div data-daily_id="<?php echo $daily['id']; ?>" class="js-edit_diary diary-content" style="cursor: pointer">
                    <p><?php echo nl2br($daily['content']); ?></p>
                    <script type="text/string"><?php echo $daily['content'];?></script>
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
                                    <div title="<?php echo $tag['tag']?>" ><?php echo $tag['tag']?></div>
                                </label>
                            </div>
                            <?php endforeach;?>
                            <div class="manage-tag" >
                                <div style="border-top: 1px solid #ccc"></div>
                                <div ><a href="javascript:;" class="js-add-tag" data-daily_id="<?php echo $daily['id']?>">新建标签并标记</a></div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                    <br/>
                    <span class="tag-list" id="tag-list-<?php echo $daily['id'];?>">
                        <?php foreach($userTags as $tag):?>
                        <div class="js-tag" id="diary_tag_<?php echo $tag['id'];?>" data-tag_id="<?php echo $tag['id'];?>" data-diary_id="<?php echo $daily['id'];?>" style="float: left; height:20px; margin: 2px 4px; background-color: <?php echo $tag['color']?>; <?php echo in_array($tag['id'], $tagIds) ? '' : 'display: none;'?>">
                            <div title="<?php echo $tag['tag'];?>" id="tag-<?php echo $tag['id'];?>" style="max-width: 120px; min-width: 30px; float: left; ">
                                <?php $url = "/diary/index.php/my/tagDaily?tag=".$tag['id']; ?>
                                <a style="text-decoration: none;" href="javascript:">
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
</div>
<?php include "views/set/addTag.php"; ?>

</div>
</body>
</html>

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
        $(".js-add-tag").click(function(){
            clickDoc();
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
        var zIndex = 9;
        $(document).click(function(e) {
            var target = $(e.target);
            if(target.is('.js-opterate_tag')) {
                target.next().toggle();
                if(target.next().is(':visible')) {
                    target.closest('.c_c').css('z-index', ++zIndex);
                }
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
            var tag_input = js_tag.parent().prev().prev().find("#tag_"+tag_id);
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

        $('.no-content-daily').click(function() {
            var textarea = $('#content', window.parent.document);
            var placeholder = $('#placeholder', window.parent.document);
            var iframe = $('[name=mainDaily]', window.parent.document);
            textarea.val('');
            placeholder.hide();

            iframe.css('height', 250);
            textarea.css('height', '120px').css('line-height', '22px');
            textarea.focus();
            $('#button-list', window.parent.document).show();
        });

        $('.js-edit_diary').click(function() {
            var content = $(this).find("script").html();
            var diary_id = $(this).closest('.diary-content').attr('data-daily_id');
            var textarea = $('#content', window.parent.document);
            var placeholder = $('#placeholder', window.parent.document);
            var iframe = $('[name=mainDaily]', window.parent.document);
            $('#daily_id', window.parent.document).val(diary_id);
            textarea.val($.trim(content));
            placeholder.hide();

            iframe.css('height', 250);
            textarea.css('height', '120px').css('line-height', '22px');
            textarea.focus();
            $('#button-list', window.parent.document).show();
        });

        var body = document.documentElement;
        $(window).resize(function() {
            $(".c_c").width(body.scrollHeight > body.clientHeight ? 245 : 260);
        }).resize();
    });
clickDoc = function() { $(document).click(); }
</script>
