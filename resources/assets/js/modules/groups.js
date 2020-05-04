const axios = require('axios').default

function GroupsModule () {
  if (window.username) {
    $('body')
      .on('click', 'button.group_subscribe_btn', this.subscribeGroup)
      .on('click', 'button.group_block_btn', this.blockGroup)

    $('[data-hover=group_widget]').popover({
      html: true, placement: 'bottom', trigger: 'hover', delay: 500, content: this.renderActionsWidget
    })
  }
}

GroupsModule.prototype.subscribeGroup = function () {
  const button = $(this)
  const name = $(this).parent().attr('data-name')
  const url = laroute.action('GroupController@subscribeGroup', { group: name })

  if (button.hasClass('btn-success')) {
    axios.delete(url).then(() => {
      $(button)
        .removeClass('btn-success')
        .addClass('btn-default')
    })
  } else {
    axios.post(url).then(() => {
      $(button)
        .removeClass('btn-default')
        .addClass('btn-success')
    })
  }
}

GroupsModule.prototype.blockGroup = function () {
  const button = $(this)
  const name = $(this).parent().attr('data-name')
  const url = laroute.action('GroupController@blockGroup', { group: name })

  if (button.hasClass('btn-danger')) {
    axios.delete(url).then(() => {
      $(button)
        .removeClass('btn-danger')
        .addClass('btn-default')
    })
  } else {
    axios.post(url).then(() => {
      $(button)
        .removeClass('btn-default')
        .addClass('btn-danger')
    })
  }
}

GroupsModule.prototype.renderActionsWidget = function () {
  const groupname = $(this).attr('data-group').replace(/^g\//, '')

  return _.tpl['groups-tooltip']({
    groupname: groupname,
    subscribe_class: _.includes(window.subscribed_groups, groupname) ? 'btn-success' : 'btn-secondary',
    block_class: _.includes(window.blocked_groups, groupname) ? 'btn-danger' : 'btn-secondary'
  })
}

export default GroupsModule
