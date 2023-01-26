const axios = require('axios').default

function GroupsModule () {
  if (window.username) {
    $('body')
      .on('click', 'button.group_subscribe_btn', this.subscribeGroup)
      .on('click', 'button.group_block_btn', this.blockGroup)

    $('[data-hover=group_widget]').popover({
      html: true, sanitize: false, placement: 'bottom', trigger: 'hover', delay: 500, content: this.renderActionsWidget
    })
  }
}

GroupsModule.prototype.subscribeGroup = function () {
  const button = $(this)
  const name = $(this).parent().attr('data-name')
  const url = `/g/${name}/subscription`

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
  const url = `/g/${name}/block`

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
  const template = require('../templates/groups/tooltip.html')

  return template({
    groupname: groupname,
    subscribe_class: window.subscribed_groups.includes(groupname) ? 'btn-success' : 'btn-light',
    block_class: window.blocked_groups.includes(groupname) ? 'btn-danger' : 'btn-light'
  })
}

export default GroupsModule
