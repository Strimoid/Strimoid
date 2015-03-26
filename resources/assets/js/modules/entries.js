function EntriesModule() {

    if (window.username) {
        $('span.save_entry').click(this.saveEntry);

        $('body').on('submit', 'form.entry_add', this.addEntry);
        $('body').on('submit', 'form.entry_add_reply', this.addReply);
        $('body').on('click', '.entry_edit_link', this.editEntry);
    }

}

EntriesModule.prototype.saveEntry = function(e) {
    var button = $(this);
    var entry = $(this).parents('[data-id]').attr('data-id');

    if (button.hasClass('glyphicon-star')) {
        $.post('/ajax/entry/remove_save', { entry: entry }, function(data){
            if (data.status == 'ok') {
                $(button).removeClass('glyphicon-star');
                $(button).addClass('glyphicon-star-empty');
            }
        });
    } else {
        $.post('/ajax/entry/add_save', { entry: entry }, function(data){
            if (data.status == 'ok') {
                $(button).removeClass('glyphicon-star-empty');
                $(button).addClass('glyphicon-star');
            }
        });
    }
};

EntriesModule.prototype.addEntry = function(e) {
    var form = this;

    $(form).find('.form-group').removeClass('has-error');
    $(form).find('.help-block').remove();

    $.post('/ajax/entry/add', $(form).serialize(), function(data){
        if (data.status == 'ok') {
            $(form).trigger('reset');
        } else {
            $(form).find('.form-group').last().addClass('has-error')
                .append('<p class="help-block">'+ data.error +'</p>');
        }
    });

    e.preventDefault();
};

EntriesModule.prototype.addReply = function(e) {
    var form = this;
    var parent = $(form).parent('.entry').prevAll('.entry:not(.entry_reply)').first();

    $(form).find('.form-group').removeClass('has-error');
    $(form).find('.help-block').remove();

    $.post($(form).attr('action'), $(form).serialize(), function(data){
        if (data.status == 'ok') {
            $(form).parent().remove();

            $(parent).nextUntil('.entry:not(.entry_reply)').remove();
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
            $(form).find('.form-group').last().addClass('has-error');
            $(form).find('.form-group').last().append('<p class="help-block">'+ data.error +'</p>');
        }
    });

    e.preventDefault();
};

EntriesModule.prototype.editEntry = function(e) {
    var entry = $(this).parent().parent();
    var entry_id = $(entry).attr('data-id');
    var entry_old_text = $(entry).find('.entry_text').html();
    var type = (entry.hasClass('entry_reply') == true ? 'entry_reply' : 'entry');

    $.post('/ajax/entry/source', { id: entry_id, type: type}, function(data){
        if (data.status == 'ok') {
            $(entry).find('.entry_text').html('<form role="form" accept-charset="UTF-8" class="enter_send entry_edit"><input type="hidden" name="id" value="'+ entry_id +'"><input type="hidden" name="type" value="'+ type +'"><div class="form-group"><textarea name="text" class="form-control" rows="3"></textarea></div><div class="btn-group pull-right"><button type="submit" class="btn btn-sm btn-primary">Zapisz</button><button type="button" class="btn btn-sm entry_edit_close">Anuluj</button></div><div class="clearfix"></div></form>');
            $(entry).find('textarea[name="text"]').val(data.source);
            $(entry).find('.entry_actions').hide();

            $(entry).find('form').submit(function(event) {
                $(entry).find('.form-group').removeClass('has-error');
                $(entry).find('.help-block').remove();

                $.post('/ajax/entry/edit', $(entry).find('form').serialize(), function(data){
                    if (data.status == 'ok') {
                        $(entry).find('.entry_text').html(data.parsed);
                        $(entry).find('.entry_actions').show();
                    } else {
                        $(entry).find('.form-group').addClass('has-error');
                        $(entry).find('.form-group').append('<p class="help-block">'+ data.error +'</p>');
                    }
                });

                event.preventDefault();
            });

            $('.entry_edit_close').click(function() {
                $(entry).find('.entry_text').html(entry_old_text);
                $(entry).find('.entry_actions').show();
            });
        }
    });
};
