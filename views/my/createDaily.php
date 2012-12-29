<div id="daily-dialog-form" title="写日志">
    <form>
        <fieldset>
            <textarea cols="60" rows="12" id="daily_content"></textarea>
        </fieldset>
    </form>
</div>

<script>
    $(function() {
        $("#daily-dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 530,
            modal: true,
            buttons: {
                "写日志": function(){
                    var content = $("#daily_content").val();
                    if(!content.length){
                        alert('请填写日志内容');
                        return false;
                    }
                    var currentTime = <?php echo $startTime; ?>;
                    $.post('createDaily', {content:content, currentTime:currentTime}, function(json){
                        location.reload();
                    }), 'json';
                },
                "取消": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                $(this).dialog("close");
            }
        });
        $(".write-daily").button().click(function(){$("#daily-dialog-form").dialog("open");});
    });
</script>
