function CommentsModule() {

    if (window.username) {
        $('body').on('submit', 'form.comment_add', this.addComment);
        $('body').on('submit', 'form.comment_add_reply', this.addReply);
    }

}

CommentsModule.prototype.addComment = function(e) {
    var form = this;
    var id = $(form).find('input[name=id]').val();

    $(form).find('.form-group').removeClass('has-error');
    $(form).find('.help-block').remove();

    var url = laroute.action('CommentController@addComment', { content: id });

    $.post(url, $(form).serialize(), function(data){
        if (data.status == 'ok') {
            $(form).trigger('reset');

            $('.comments').append(data.comment);
            $('.comments p.no_comments').remove();
        } else {
            $(form).find('.form-group').last().addClass('has-error')
                .append('<p class="help-block">'+ data.error +'</p>');
        }
    });

    e.preventDefault();
};

CommentsModule.prototype.addReply = function(e) {
    var form = this;
    var parent = $(form).parent('.comment').prevAll('.comment:not(.comment_reply)').first();

    $(form).find('.form-group').removeClass('has-error');
    $(form).find('.help-block').remove();

    $.post($(form).attr('action'), $(form).serialize(), function(data){
        if (data.status == 'ok') {
            $(form).parent().remove();

            $(parent).nextUntil('.comment:not(.comment_reply)').remove();
            $(parent).after(data.replies);

            $('.md a[href*="youtube.com"]').each(function()
            {
                var url = $(this).attr('href');
                var regex = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:[^#]+)?(?:#)?(?:t=(\d+))?(?:\S+)?$/i;

                var found = url.match(regex);

                if (found)
                    $(this).addClass('yt-video').data('yt-id', found[1]).data('yt-time', found[2]);
            });
        } else {
            $(form).find('.form-group').last().addClass('has-error')
                .append('<p class="help-block">'+ data.error +'</p>');
        }
    });

    e.preventDefault();
};

export default CommentsModule
