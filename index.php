<?php
     $title = "我的日记";
     $num = "";
?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/daily/top.php"; ?>

<div><span>今日工作：<?php echo $num; ?>项</span><button id="js-write-dialy">写日记</button></div>
<?php include "views/layouts/footer.php"; ?>
<div id="dialog-form" title="写日记">
    <form>
        <fieldset>
            <textarea cols="60" rows="12"></textarea>
        </fieldset>
    </form>
</div>

<script>
    $(function() {

        $( "#dialog-form" ).dialog({
            autoOpen: false,
            height: 300,
            width: 530,
            modal: true,
            buttons: {
                "写日记": function(){
                    alert(11);
                },
                "取消": function() {
                    $(this).dialog( "close" );
                }
            },
            close: function() {
                allFields.val( "" ).removeClass( "ui-state-error" );
            }
        });
        $("#js-write-dialy").button().click(function(){$("#dialog-form").dialog( "open" );});
    });
</script>
