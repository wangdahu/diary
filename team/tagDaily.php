<?php
$tagId = (int)$_GET['tag'];
$uid = (int)$_GET['uid'];
$userInfo = DiaryUser::getInfo($uid);
$title = $userInfo['UserName']."的标签";
$type = 'daily';
// 获取当前tag的所有日志
$dailys = DiaryDaily::getTagDailys($diary, $tagId);

$num = count($dailys);
$userTags = DiaryDaily::getUserTags($diary, $uid);
$tagName = $userTags[$tagId]['tag'];
?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/team/tag-top.php"; ?>

<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
        <h2 class="content_tit clearfix" style="border: none;"></h2>
        <?php foreach($dailys as $daily):?>
        <?php
             // $isReported = DiaryReport::checkReport($diary, $type, date('Y-m-d', $daily['show_time']));
        ?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <div>
                    <p><?php echo nl2br($daily['content']); ?></p>
                </div>
                <div class="clearfix diary-operation" >
    <?php
        $tagList = array();
        $tagList = DiaryDaily::getDailyTag($diary, $daily['id']);
        $tagIds = array_keys($tagList);
    ?>
                    <span class="daily-date"><?php echo date('y-m-d H:i', $daily['fill_time']);?></span>
                    <span class="tag-list" id="tag-list-<?php echo $daily['id'];?>">
                        <?php foreach($userTags as $tag):?>
                        <div class="js-tag" id="diary_tag_<?php echo $tag['id'];?>" data-tag_id="<?php echo $tag['id'];?>" data-diary_id="<?php echo $daily['id'];?>" style="float: left; margin: 0 4px; background-color: <?php echo $tag['color']?>; <?php echo in_array($tag['id'], $tagIds) ? '' : 'display: none;'?>">
                            <div title="<?php echo $tag['tag'];?>" id="tag-<?php echo $tag['id'];?>" class="ellipsis" style="max-width: 120px; float: left; ">
                                <?php $url = "/diary/index.php/team/tagDaily?tag=".$tag['id']."&uid=".$daily['uid']; ?>
                                <a style="text-decoration: none;" href="<?php echo $url;?>">
                                <span style="margin:4px;">
                                    <?php echo $tag['tag'];?>
                                </span>
                                </a>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </span>
                </div>
            </div>
        </div>
        <div class="c_b"></div>
        <?php endforeach;?>
    </div>
</div>

<?php include "views/layouts/footer.php"; ?>
