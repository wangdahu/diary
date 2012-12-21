<?php
$title = "我的日志";
$currentModule = 'team';

$uid = $diary->uid;
$corpId = $diary->corpId;

// 向前向后翻天
$forward = isset($_GET['forward']) ? $_GET['forward'] : 0;
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
$endTime = $startTime + 86400;

include dirname(dirname(__FILE__))."/class/Set.php";
include dirname(dirname(__FILE__))."/class/User.php";
// 汇报给我的和我订阅的用户
$teamShowObject = Set::teamShowObject($diary, 1);
?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/team/top.php"; ?>
<div class="content">
    <div class="set_bar mb25">
        <!--标签设置开始-->
        <ul class="dy">
            <?php foreach($teamShowObject as $uid): ?>
            <?php $user = User::getInfo($uid);?>
            <li>
                <div class="pic">
                    <a href="/diary/index.php/my/index&uid=<?php echo $uid;?>"><img src="<?php echo $user['photo']; ?>" alt="" /></a>
                </div>
                <p><a href="#"><?php echo $user['username']?></a>（产品部-产品经理）<br />已订阅，<a href="#">取消订阅</a></p></li>
            <?php endforeach;?>
        </ul>
        <!--标签设置结束-->
    </div>
</div>
<script>
    $(function(){
        $(".delete").click(function(){
            console.log($(this).attr('data-id'));
        });
    });
</script>
<?php include "views/layouts/footer.php"; ?>
