<?php
$title = "写月报";
$uid = $diary->uid;
$user = DiaryUser::getInfo($uid);
$corpId = $user['corp_id'];

$startTime = mktime(0,0,0,date("m"),1,date("Y"));
$endTime = mktime(0,0,0,date("m")+1,1,date("Y")) - 1;
// 该企业该用户在选择时间内的月报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 3 and `show_time` between $startTime and $endTime";
$result = $diary->db->query($rowsSql);
$monthlys = array();
while($row = $result->fetch_assoc()){
    $monthlys = $row;
};
$object = date('Y-m');
$type = "monthly";
$isReported = DiaryReport::checkReport($diary, $type, $object);
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
</style>

<div class="content">
    <!--本月总结开始-->
    <div class="content_bar mb25">
        <?php if(!$monthlys):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <div class="diary-content">
                    <p style="font-size: 16px;color: red; text-align: center; line-height: 100px;">
                        <strong>还未填写任何日志内容</strong>
                    </p>
                </div>
            </div>
        </div>
        <?php else:?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <?php if($isReported):?>
                <div class="diary-content">
                    <p><?php echo nl2br($monthlys['content']); ?></p>
                </div>
                <?php else:?>
                <div data-monthly_id="<?php echo $monthlys['id']; ?>" class="js-edit_diary diary-content" style="cursor: pointer">
                    <p><?php echo nl2br($monthlys['content']); ?></p>
                    <script type="text/string"><?php echo $monthlys['content'];?></script>
                </div>
                <?php endif;?>
            </div>
        </div>
        <?php endif;?>
        <div class="c_b"></div>
    </div>
    <!--本月总结结束-->
</div>
<script>
function editContent() {
    var content = $('.js-edit_diary').find("script").html();
    var monthly_id = $('.js-edit_diary').closest('.diary-content').attr('data-monthly_id');
    var textarea = $('#monthly_content', window.parent.document);
    var iframe = $('[name=mainMonthly]', window.parent.document);
    var insertDaily = $('.insertWeekly', window.parent.document);
    $('#monthly_id', window.parent.document).val(monthly_id);
    if(textarea.val() == '') {
        textarea.val($.trim(content));
    }

    textarea.wordLimit();
    iframe.css('height', 190);
    textarea.css('height', '150px').css('line-height', '22px');
    insertDaily.show();
    $('#button-list', window.parent.document).show();
}

$(function() {
    $('.js-edit_diary').click(function() {
        editContent();
    });
});
</script>
