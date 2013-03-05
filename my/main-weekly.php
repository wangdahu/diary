<?php
$title = "写周报";
// 当前周的周一时间戳
$startTime = date('w') == 1 ? strtotime("this Monday") : strtotime("-1 Monday");

$endTime = $startTime + 7*86400 - 1;

$corpId = $diary->corpId;
$uid = $diary->uid;
// 该企业该用户在选择时间内的月报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 2 and `show_time` between $startTime and $endTime";
$result = $diary->db->query($rowsSql);
$weeklys = array();
while($row = $result->fetch_assoc()){
    $weeklys = $row;
};

// 当前时间为 当前年的多少周
$object = date('Y-W', $startTime);
// 判断是否为补交/未汇报/已汇报
$isReported = DiaryReport::checkReport($diary, 'weekly', $object);
?>

<?php include "views/my/mini-top.php"; ?>

<style>
.content {width: 280px; background: #fff; min-height: 0;}
.weekly-content {padding: 5px;}
.c_c { padding:0px; border-radius: 0; width: 260px;}
.daily-date { float: left !important; padding-left: 5px;}
.all-tag-floor {right: 0; left: auto;}
.tag-list { float: left !important; margin-bottom: 3px;}
body{ background: #fff; overflow-x: hidden; }
</style>

<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
        <input type="hidden" id="allowEdit" value="<?php echo $weeklys ? 1 : 0?>">
        <?php if(!$weeklys):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c no-content-daily" style="cursor: pointer;">
                <div>
                    <p style="font-size: 16px;color: red; text-align: center; line-height: 100px;">
                        <strong>还未填写任何日志内容</strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="c_b"></div>
        <?php else:?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <?php if($isReported):?>
                <div class="weekly-content">
                    <p><?php echo nl2br($weeklys['content']); ?></p>
                </div>
                <?php else:?>
                <div data-weekly_id="<?php echo $weeklys['id']; ?>" class="js-edit_diary weekly-content" style="cursor: pointer;width:100%;">
                    <p><?php echo nl2br($weeklys['content']); ?></p>
                    <script type="text/string"><?php echo $weeklys['content'];?></script>
                </div>
                <?php endif;?>
            </div>
        </div>
        <div class="c_b"></div>
        <?php endif;?>
    </div>
</div>
<script>

function editContent() {
    var content = $('.js-edit_diary').find("script").html();
    var weekly_id = $('.js-edit_diary').closest('.diary-content').attr('data-weekly_id');
    var textarea = $('#weekly_content', window.parent.document);
    var iframe = $('[name=mainWeekly]', window.parent.document);
    var insertDaily = $('.insertDaily', window.parent.document);
    var placeholder = $('#placeholder', window.parent.document);
    $('#weekly_id', window.parent.document).val(weekly_id);
    if(textarea.val() == '') {
        textarea.val($.trim(content));
    }

    iframe.css('height', 190);
    textarea.css({ height: 150, lineHeight: 1.8 });
    insertDaily.show();
    textarea.focus();
    window.parent.TA.setCursorPosition(textarea[0], textarea.val().length);
    $('#button-list', window.parent.document).show();
}

$(function() {
    $('.js-edit_diary, .no-content-daily').click(editContent);
});
</script>
