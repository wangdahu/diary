<?php
$commentList = DiaryComment::getObjectComment($diary, $uid, $type, $object);
DiaryViewRecord::addRecord($diary, $type, $uid, $object);
$viewRecord = DiaryViewRecord::getViewRecord($diary, $type, $uid, $object);
$viewCount = count($viewRecord);
$commentUserIds = array();
foreach($commentList as $comment) {
    $commentUserIds[] = $comment['uid'];
}

// 查询汇报总人数
$reportList = DiaryReport::getReportList($diary, $type, $object, $uid);
$reportCount = count($reportList);
$reportUserIds = array();
foreach($reportList as $report) {
    $reportUserIds[] = $report['object'];
}
$typeCommitArr = array('daily' => '日报', 'weekly' => '周报', 'monthly' => '月报');

$allUsers = DiaryUser::getUsers($reportUserIds+$commentUserIds);

// 获取表情的title
$emotionsDir = dirname(dirname(__FILE__))."/source/emotions/";
$titleList = parse_ini_file($emotionsDir."faceList.ini");
unset($titleList['count']);
?>
<!--评论开始-->
<div class="content_bar">
    <h2 class="content_tit clearfix mb10">
        <p class="p_icon">评论（<?php echo count($commentList); ?>）</p>
        <?php if($reportCount):?>
        <a href="javascript:;" class="fr js-view_record">
            汇报：<?php echo $viewCount; ?>/<?php echo $reportCount; ?>人
        </a>
        <?php endif;?>
    </h2>
    <?php foreach($commentList as $comment): ?>
    <div class="comment_box">
        <div class="c_t"></div>
        <div class="c_c clearfix">
            <div class="pic"><img src="<?php echo $allUsers[$comment['uid']]['photo']; ?>" alt="" /></div>
            <div class="comment_t">
                <h2>
                    <a href="javascript:;"><?php echo $allUsers[$comment['uid']]['UserName']; ?>（<?php echo $allUsers[$comment['uid']]['dept_name']; ?>）</a>
                    <span>
                        <?php echo date('y-m-d H:i', $comment['add_time']); ?>
                    </span>
                </h2>
                <p><?php echo nl2br($comment['content']); ?></p>
            </div>
        </div>
        <div class="c_b"></div>
    </div>
    <?php endforeach;?>
</div>
<!--评论结束-->
<!--发表评论开始-->
<form method="post" id="comment-form">
    <div class="content_bar">
        <div class="c_t"></div>
        <div class="c_c">
            <div class="comment_f">
                <button class="fr" type="submit">评论</button>
                <div class="emotion-warpper">
                    <a class="insert-emotion"></a>
                    <ul class="emotion-list clearfix">
                        <?php foreach($titleList as $k => $title): ?>
                        <li><a title="<?php echo $title;?>" href="javascript:">
                                <img src="/diary/source/emotions/<?php echo $k?>_s.bmp"/>
                        </a></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
            <div class="ftextarea">
                <div contenteditable="true" id="content" class="textarea_comment" ></div>
                <textarea name="content" style="display: none;"></textarea>
            </div>
            <input type="hidden" value="<?php echo $type;?>" name="type" id="type"/>
            <input type="hidden" value="<?php echo $uid;?>" name="to_uid" id="to_uid"/>
            <input type="hidden" value="<?php echo $object;?>" name="object" id="object"/>
        </div>
        <div class="c_b"></div>
    </div>
</form>
<style>
#commit-dialog-form table {width: 375px;}
#commit-dialog-form td{ border: 1px solid #ccc; text-align: center;}
</style>
<div id="commit-dialog-form" title="汇报统计">
    <div style="text-align: center;margin-bottom:10px; padding:5px; border-bottom: 1px solid #000;">
        <h3><?php echo $object;?><?php echo $typeCommitArr[$type];?></h3>
    </div>
    <table>
        <tr>
            <td>汇报（<?php echo $reportCount; ?>）</td>
            <td>是否查阅</td>
            <td>查阅时间</td>
        </tr>
        <?php foreach($reportList as $report):?>
        <tr>
            <td><?php echo $allUsers[$report['object']]['UserName']; ?>（<?php echo $allUsers[$report['object']]['dept_name']; ?>）</td>
            <td><?php echo isset($viewRecord[$report['object']]) ? '已阅' : '未阅'?></td>
            <td><?php echo isset($viewRecord[$report['object']]) ? date('Y-m-d H:i', $viewRecord[$report['object']]['view_time']) : '--'?></td>
        </tr>
        <?php endforeach;?>
    </table>
</div>

<script>
    $(function() {
        $("#commit-dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 410,
            modal: true,
            open: function(){
                $("#daily_content").select();
            },
            buttons: {
                "取消": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                $(this).dialog("close");
            }
        });

        $('.js-view_record').click(function(){
            $("#commit-dialog-form").dialog("open");
        });

        $("#comment-form").submit(function(){
            var form = $(this), content = $('#content').html();
            if(!content.length){
                alert('请填写内容');
                $('#content').select();
                return false;
            }
            $('[name=content]').val(content); // sync div content to textarea
            $.post('/diary/index.php/team/createComment', form.serialize(), function(json){
                if(json){
                    location.reload();
                }else{
                    alert("评论失败，请重试");
                }
            }), 'json';
            return false;
        });


        $('.insert-emotion').click(function() {
            $(this).next().toggle();
        });
        $('.emotion-list').delegate('a', 'click', function() {
            var src = $(this).children()[0].src;
            console.log(src);
            var textarea = $('#content')[0];
            textarea.focus();
            textarea.ownerDocument.execCommand('insertImage', false, src);
            $(this).closest('.emotion-list').hide();
        });

    });
</script>
<!--发表评论结束-->
