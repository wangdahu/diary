<?php
$title = "标签设置";
$setDefault = 'tag';

include dirname(dirname(__FILE__))."/class/DiaryDaily.php";
// 获取颜色列表
$colorList = DiaryDaily::getColorList($diary);
$tagList = DiaryDaily::getTagList($diary);

$defaultColorId = rand(1,20);
?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/set/top.php"; ?>
<div class="content">
    <div class="set_bar mb25">
        <!--标签设置开始-->
        <h2 class="pt25"><a href="javascript:;" class="tjbq js-add-tag" title="添加标签"></a></h2>

        <div class="mt10"></div>
            <table class="tag">
                <thead>
                    <tr>
                        <td>标签名称</td>
                        <td width="50">工作数</td>
                        <td class="td-right">操作</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tagList as $tag):?>
                    <tr>
                        <td><div class="color-list" style="float: left; margin-right: 5px; background-color: <?php echo $colorList[$tag['color_id']]?>"></div><a href="/diary/index.php/my/tagDaily?tag=<?php echo $tag['id']; ?>"><?php echo $tag['tag'];?></a></td>
                        <td align="center"><?php echo $tag['count'];?></td>
                        <td class="td-right">
                            <a href="javascript:;" class="js-edit-tag" data-color="<?php echo $colorList[$tag['color_id']]; ?>" data-tag="<?php echo $tag['tag']; ?>" data-color_id="<?php echo $tag['color_id']; ?>" data-id="<?php echo $tag['id']; ?>">编辑</a>
                            <a href="javascript:;" class="js-delete-tag" data-id="<?php echo $tag['id']?>" data-tag="<?php echo $tag['tag']?>">删除</a>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
    </div>
</div>
<?php include "views/layouts/footer.php"; ?>
<?php include "views/set/addTag.php"; ?>
