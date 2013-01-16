<?php
$user = DiaryUser::getInfo($uid);
$corpId = $user['corp_id'];

// 该企业该用户在选择时间内的月报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 2 and `show_time` between $startTime and $endTime";
$result = $diary->db->query($rowsSql);
$weeklys = array();
while($row = $result->fetch_assoc()){
    $weeklys = $row;
};
$wiseucUrl = "wisetong://message/?uid=".$user['LoginName']."&myid=".$diary->LoginName;
?>
<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
        <?php if(isset($from)):?>
        <h2 class="content_tit clearfix"><a href="<?php echo $backUrl; ?>" style="display:inline-block" class="a_01 mg10">返回</a>  <a href="<?php echo $wiseucUrl;?>"><?php echo $user['UserName'];?></a><?php echo "（".$user['dept_name']."）";?>
        </h2>
        <?php endif;?>
        <h2 class="content_tit clearfix">
            <p>周报</p>
            <?php if(!isset($from) && $allowPay):?>
            <?php if($weeklys):?>
            <a href="javascript:" class="fr mr10 pay-diary js-pay_diary"></a>
            <?php else:?>
            <a class="fr mr10 pay-disabled"></a>
            <?php endif;?>
            <?php endif;?>
            <?php if(!isset($from) && !$isReported):?>
            <a href="javascript:" class="write-<?php echo $type?> fr mr10"></a>
            <?php endif;?>
        </h2>

        <?php if(!$weeklys || (isset($from) && !$isReported)):?>
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
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <?php if($isReported):?>
                <div>
                    <p><?php echo nl2br($weeklys['content']); ?></p>
                </div>
                <?php else:?>
                <div data-weekly_id="<?php echo $weeklys['id']; ?>" class="js-edit_diary" style="cursor: pointer">
                    <p><?php echo nl2br($weeklys['content']); ?></p>
                    <div style="display:none;"><?php echo $weeklys['content'];?></div>
                </div>
                <?php endif;?>
            </div>
        </div>
        <div class="c_b"></div>
        <?php endif;?>
        <?php if(!isset($from) && $weekDate): foreach($weekDate as $k => $v):?>
        <div>
            <div class="c_t mt10"></div>
            <div class="c_c">
                <div>
                    <p style="line-height:30px;">
                        <strong><?php echo "周".$k." ".$v; ?></strong>
                        <span>工作：<?php echo isset($dailys[$v]) ? count($dailys[$v]) : 0;?>项</span>
                        <?php
                             $dateForward = DiaryDaily::getForward($dateForwards[$v]);
                             $url = "/diary/index.php/my/index?forward=".$dateForward;
                        ?>
                        <a href="<?php echo $url;?>" style="text-decoration: none;"><button style="cursor: pointer" class="fr" type="button">进入</button></a>
                    </p>
                </div>

                <?php if(isset($dailys[$v])): foreach($dailys[$v] as $date => $daily):?>
                <?php
                     $tagList = array();
                     $tagList = DiaryDaily::getDailyTag($diary, $daily['id']);
                     $tagIds = array_keys($tagList);
                ?>
                <div class="c_c_c "  style="padding: 10px 0;border-top: 1px solid #DADADA">
                    <div>
                        <p><?php echo nl2br($daily['content']);?></p>
                    </div>
                    <div class="clearfix diary-operation" >
                        <span class="daily-date"><?php echo date('y-m-d H:i', $daily['fill_time']);?></span>
                        <span class="tag-list">
                            <?php foreach($tagList as $tag):?>
                            <div style="float: left; margin: 0 4px; background-color: <?php echo $tag['color']?>;">
                                <div title="<?php echo $tag['tag']?>" class="ellipsis" style="max-width: 120px; float: left; ">
                                <?php $url = "/diary/index.php/my/tagDaily?tag=".$tag['id']; ?>
                                <a style="text-decoration: none;" href="<?php echo $url;?>">
                                    <span style="margin:4px;"><?php echo $tag['tag']?></span>
                                </a>
                                </div>
                            </div>
                            <?php endforeach;?>
                        </span>
                    </div>
                </div>
                <?php endforeach; endif;?>
            </div>
            <div class="c_b"></div>
        </div>

        <?php endforeach; endif;?>
    </div>
    <!--今日工作结束-->
    <?php if($showCommit):
          include dirname(dirname(dirname(__FILE__)))."/team/comment.php";
    endif;?>
</div>
