function GroupsModule() {
    if (window.username) {
        $('body').on('click', 'button.group_subscribe_btn', this.subscribeGroup);
        $('body').on('click', 'button.group_block_btn', this.blockGroup);

        $('a.entry_group').popover({
            html: true, placement: 'bottom', trigger: 'hover', delay: 500, content: this.renderActionsWidget
        });
    }
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

GroupsModule.prototype.renderActionsWidget = function() {
    var widget = $(this);
    var groupname = $(this).parent().find('.entry_group').text();

    groupname = groupname.replace(/^g\//, '');

    var template = _.template('<div class="btn-group" data-name="<%= groupname %>"><button class="group_subscribe_btn btn btn-sm <%= subscribe_class %>"><span class="glyphicon glyphicon-eye-open"></span></button><button class="group_block_btn btn btn-sm <%= block_class %>"><span class="glyphicon glyphicon-ban-circle"></span></button></div>');

    return template({
        groupname: groupname,
        subscribe_class: _.contains(window.subscribed_groups, groupname) ? 'btn-success' : 'btn-default',
        block_class: _.contains(window.blocked_groups, groupname) ? 'btn-danger' : 'btn-default'
    });
}
