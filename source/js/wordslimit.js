
$.fn.extend({
    wordLimit: function() {
        this.each(function() {
            var textarea = $(this), limit = textarea.data('limit'), wordIndicator;
            if(textarea.data('init')) {
                return textarea.trigger('input');
            }
            textarea.data('init', 1);
            textarea.parent().css('position', 'relative');
            wordIndicator = $('<span class="word-limit"><span>0</span> / <span>' + limit + '</span><input id="word_valid" type="hidden"/></span>').css('right', 2 + textarea.parent().width() - textarea.width()).insertAfter(textarea);
            textarea.bind('input keyup', function() {
                var len = (this.nodeName == 'TEXTAREA' ? this.value
                    : this.innerHTML.replace(/<img[^>]*>/gi, '1').replace(/<\/?\w+[^>]*>|&nbsp;/g, '')).replace(/\s+/g, '').length;
                wordIndicator.find('span:first').text(len);
                wordIndicator.toggleClass('word-exceed', len > limit);
                wordIndicator.find('input[type=hidden]').val(len > limit ? 1 : '');
            }).trigger('input');
        });
        return this;
    }
});
