<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>写日志</title>
    <script src="../../../../diary/source/jqueryUI/js/jquery-1.9.0.min.js"></script>
    <script src="../../../../diary/source/jqueryUI/js/jquery-ui-1.10.0.custom.min.js"></script>
    <link rel="stylesheet" href="../../../../diary/source/jqueryUI/css/smoothness/jquery-ui-1.10.0.custom.min.css">
    <link rel="stylesheet" href="../../../../diary/source/css/base.css">
    <link rel="stylesheet" href="../../../../diary/source/css/module.css">
    <link rel="stylesheet" href="../../../../diary/source/css/popup.css">
</head>

<body>
    <div>

<?php
if($isReported){
    $url = "../../../../diary/source/images/already-report.png";
    $reportStatus = "已汇报";
}else{
    $url = "../../../../diary/source/images/no-report.png";
    $reportStatus = "未汇报";
}
?>
<script>
$.fn.extend({
    wordLimit: function() {
        this.each(function() {
            var textarea = $(this), limit = textarea.data('limit'), wordIndicator;
            if(textarea.data('init')) {
                return textarea.trigger('input');
            }
            textarea.data('init', 1);
            textarea.parent().css('position', 'relative');
            wordIndicator = $('<span class="word-limit"><span>0</span> / <span>' + limit + '</span><input id="word_valid" type="hidden"/></span>').insertAfter(textarea);
            textarea.bind('input keyup', function() {
                var len = this.value.length;
                wordIndicator.find('span:first').text(len);
                wordIndicator.toggleClass('word-exceed', len > limit);
                wordIndicator.find('input[type=hidden]').val(len > limit ? 1 : '');
            }).trigger('input');
        });
        return this;
    }
});
</script>
