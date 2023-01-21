const axios = require('axios').default

function UsersModule () {
  if (window.username) {
    $('body')
      .on('click', 'button.user_observe_btn', this.observeUser)
      .on('click', 'button.user_block_btn', this.blockUser)

    $('[data-hover=user_widget]').popover({
      html: true,
      placement: 'bottom',
      trigger: 'hover',
      delay: 500,
      content: this.renderActionsWidget
    })
  }
}

UsersModule.prototype.observeUser = function () {
  const button = $(this)
  const name = $(this).parent().attr('data-name')

  if (button.hasClass('btn-success')) {
    axios.delete(`/u/${name}/observe`).then(() => {
      $(button)
        .removeClass('btn-success')
        .addClass('btn-default')

      window.observed_users = _.without(window.observed_users, name)
    })
  } else {
    axios.post(`/u/${name}/observe`).then(() => {
      $(button)
        .removeClass('btn-default')
        .addClass('btn-success')

      window.observed_users.push(name)
    })
  }
}

UsersModule.prototype.blockUser = function () {
  const button = $(this)
  const name = $(this).parent().attr('data-name')

  if (button.hasClass('btn-danger')) {
    axios.delete(`/u/${name}/block`).then(() => {
      $(button)
        .removeClass('btn-danger')
        .addClass('btn-default')

      window.blocked_users = _.without(window.blocked_users, name)
    })
  } else {
    axios.post(`/u/${name}/block`).then(() => {
      $(button)
        .removeClass('btn-default')
        .addClass('btn-danger')

      window.blocked_users.push(name)
    })
  }
}

UsersModule.prototype.renderActionsWidget = function () {
  const username = $(this).attr('data-user')
  const template = require('../templates/users/tooltip.html')

  return template({
    username: username,
    observe_class: _.includes(window.observed_users, username) ? 'btn-success' : 'btn-secondary',
    block_class: _.includes(window.blocked_users, username) ? 'btn-danger' : 'btn-secondary'
  })
}

export default UsersModule
