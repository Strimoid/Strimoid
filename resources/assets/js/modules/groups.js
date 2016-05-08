function GroupsModule() {
    if (window.username) {
        $('body').on('click', 'button.group_subscribe_btn', this.subscribeGroup);
        $('body').on('click', 'button.group_block_btn', this.blockGroup);

        $('[data-hover=group_widget]').popover({
            html: true, placement: 'bottom', trigger: 'hover', delay: 500, content: this.renderActionsWidget
        });
    }
}

GroupsModule.prototype.subscribeGroup = function () {
    var button = $(this);
    var name = $(this).parent().attr('data-name');
    var url = laroute.action('GroupController@subscribeGroup', {group: name});

    if (button.hasClass('btn-success')) {
        $.delete(url, function(data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-success');
                $(button).addClass('btn-default');
            }
        });
    } else {
        $.post(url, function(data){
            if (data.status == 'ok') {
                $(button).removeClass('btn-default');
                $(button).addClass('btn-success');
            }
        });
    }
};

GroupsModule.prototype.blockGroup = function () {
    var button = $(this);
    var name = $(this).parent().attr('data-name');
    var url = laroute.action('GroupController@blockGroup', {group: name});

    if (button.hasClass('btn-danger')) {
        $.delete(url, function(data){
            if (data.status == 'ok') {
                $(button).removeClass('btn-danger');
                $(button).addClass('btn-default');
            }
        });
    } else {
        $.post(url, function(data){
            if (data.status == 'ok') {
                $(button).removeClass('btn-default');
                $(button).addClass('btn-danger');
            }
        });
    }
};

GroupsModule.prototype.renderActionsWidget = function() {
    var groupname = $(this).attr('data-group').replace(/^g\//, '')

    return _.tpl['groups-tooltip']({
        groupname: groupname,
        subscribe_class: _.includes(window.subscribed_groups, groupname) ? 'btn-success' : 'btn-secondary',
        block_class: _.includes(window.blocked_groups, groupname) ? 'btn-danger' : 'btn-secondary'
    });
};
