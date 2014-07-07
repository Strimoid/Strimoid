function GroupsModule() {
    $('button.group_subscribe_btn').click(this.subscribeGroup);
    $('button.group_block_btn').click(this.blockGroup);
}

GroupsModule.prototype.subscribeGroup = function () {
    var button = $(this);
    var name = $(this).parent().attr('data-name');

    if (button.hasClass('btn-success')) {
        $.post('/ajax/group/unsubscribe', { name: name }, function(data){
            if (data.status == 'ok') {
                $(button).removeClass('btn-success');
                $(button).addClass('btn-default');
            }
        });
    } else {
        $.post('/ajax/group/subscribe', { name: name }, function(data){
            if (data.status == 'ok') {
                $(button).removeClass('btn-default');
                $(button).addClass('btn-success');
            }
        });
    }
}

GroupsModule.prototype.blockGroup = function () {
    var button = $(this);
    var name = $(this).parent().attr('data-name');

    if (button.hasClass('btn-danger')) {
        $.post('/ajax/group/unblock', { name: name }, function(data){
            if (data.status == 'ok') {
                $(button).removeClass('btn-danger');
                $(button).addClass('btn-default');
            }
        });
    } else {
        $.post('/ajax/group/block', { name: name }, function(data){
            if (data.status == 'ok') {
                $(button).removeClass('btn-default');
                $(button).addClass('btn-danger');
            }
        });
    }
}
