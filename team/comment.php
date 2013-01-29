<?php
$commentList = DiaryComment::getObjectComment($diary, $uid, $type, $object);
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

if($isReported) {
    $flag = 0;
    if(in_array($diary->uid, $reportUserIds)) {
        $flag = 1;
    }
    DiaryViewRecord::addRecord($diary, $type, $uid, $object, $flag);
}

$viewRecord = DiaryViewRecord::getViewRecord($diary, $type, $uid, $object);
$viewCount = count($viewRecord);
$typeCommitArr = array('daily' => '日报', 'weekly' => '周报', 'monthly' => '月报');

$allUsers = DiaryUser::getUsers(array_merge($reportUserIds, $commentUserIds));
// 获取表情的title
$emotionsDir = dirname(dirname(__FILE__))."/source/emotions/";
$titleList = parse_ini_file($emotionsDir."faceList.ini");
unset($titleList['count']);
?>
<script src="../../../diary/source/js/wordslimit.js"></script>
<!--评论开始-->
<div class="content_bar">
    <h2 class="content_tit clearfix mb10">
        <p>评论<strong >（<?php echo count($commentList); ?>条）</strong></p>
        <?php if($reportCount):?>
        <a href="javascript:;" class="fr js-view_record">
            汇报：<?php echo $viewCount; ?>/<?php echo $reportCount; ?>人
        </a>
        <?php endif;?>
    </h2>
    <?php foreach($commentList as $comment): ?>
    <?php
        $myselfLogin = URLEncode(Base64_encode($diary->LoginName));
        $userLogin = URLEncode(Base64_encode($allUsers[$comment['uid']]['LoginName']));
        $wiseucUrl = "wisetong://message/?uid=".$userLogin."&myid=".$myselfLogin;
    ?>
    <div class="comment_box">
        <div class="c_t"></div>
        <div class="c_c clearfix">
            <div class="pic"><a href="<?php echo $wiseucUrl;?>"><img src="<?php echo $allUsers[$comment['uid']]['photo']; ?>" alt="" /></a></div>
            <div class="comment_t">
                <h2 class="user-info">
                    <span> <?php echo date('y-m-d H:i', $comment['add_time']); ?> </span>
                    <a href="<?php echo $wiseucUrl;?>"><?php echo $allUsers[$comment['uid']]['UserName']; ?></a>（<?php echo $allUsers[$comment['uid']]['dept_name']; ?>-<?php echo $allUsers[$comment['uid']]['Title'];?>）
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
    <div style="padding: 0 10px;">
        <div class="c_t"></div>
        <div class="c_c">
            <div class="comment_f">
                <a class="a_01 fr" onclick="$(this).closest('form').submit()" href="javascript:">提交</a>
                <div class="emotion-warpper">
                    <a class="insert-emotion" href="javascript:"></a>
                    <ul class="emotion-list clearfix">
                        <?php foreach($titleList as $k => $title): ?>
                        <li><a title="<?php echo $title;?>" href="javascript:" data-id="<?php echo $k;?>"></a></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
            <div class="ftextarea">
                <div contenteditable="true" id="content" class="textarea_comment" data-limit="300"></div>
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
<div id="commit-dialog-form" title="汇报统计" style="display:none;">
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
            if(form.find('#word_valid').val()){
                alert('输入的文字内容大于所规定的字数');
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

        // 显示表情与隐藏
        $(document).click(function(e) {
            var target = $(e.target);
            if(target.is('.insert-emotion')) {
                target.next().toggle();
            } else {
                $('.emotion-list').hide();
            }
        });
        $('.emotion-list').delegate('a', 'click', function() {
            var src = '/diary/source/emotions/' + $(this).data('id') + '.gif';
            var textarea = $('#content')[0];
            textarea.focus();
            textarea.ownerDocument.execCommand('insertImage', false, src);
            $(textarea).trigger('input');
            $(this).closest('.emotion-list').hide();
        }).click(function(e) {
            e.stopPropagation();
        });

        $('#content').wordLimit();
    });
</script>
<!--发表评论结束-->
