function VotesModule () {

  if (window.username) {
    $('body').on('click', 'button.vote-btn-up', this.addUpvote)
      .on('click', 'button.vote-btn-down', this.addDownvote)
  }

  $('body').popover({
    html: true,
    selector: 'button.vote-btn-up, button.vote-btn-down',
    trigger: 'hover',
    content: 'ładowanie...',
    placement: 'right',
    delay: 500
  })

  // fix context menu instead of popover for mobile devices:
  $('body').on('contextmenu', 'button.vote-btn-up, button.vote-btn-down', function (event) {
    return false
  })

  $('body').on('show.bs.popover', 'button.vote-btn-up, button.vote-btn-down', function () {
    var button = this
    var item = $(this).parent()
    var cid = $(item).attr('data-id')
    var type = $(item).attr('data-type')

    // Prevent loading votes multiple times
    if ($(button).data('loading'))
      return

    $(button).data('loading', true)

    var filter

    if ($(this).hasClass('vote-btn-up'))
      filter = 'up'
    else
      filter = 'down'

    $.post('/ajax/vote/get_voters', { id: cid, type: type, filter: filter }, function (data) {
      if (data.status == 'ok') {
        var content = ''

        data.voters.forEach(function (vote) {
          content += '<div style="font-size: 11px">'

          if (vote.up)
            content += '<span class="fa fa-arrow-up vote-up"></span>'
          else
            content += '<span class="fa fa-arrow-down vote-down"></span>'

          content += ' <a href="/u/' + vote.username + '">' + vote.username + '</a> '
          content += '<span style="color: #929292" title="' + vote.time + '">(' + vote.time_ago + ')</span></div>'
        })

        var popover = $(button).data('bs.popover')

        if (content == '')
          popover.config.content = 'brak głosów'
        else
          popover.config.content = content

        popover.config.animation = false
        popover.show()
        popover.config.animation = true
      }
    })
  })
}

VotesModule.prototype.addUpvote = function () {
  var content = $(this).parent()
  var cid = $(content).attr('data-id')
  var state = $(content).attr('state')
  var type = $(content).attr('data-type')

  if (state == 'uv') {
    $.post('/ajax/vote/remove', { id: cid, type: type }, function (data) {
      if (data.status == 'ok') {
        $(content).find('.vote-btn-up').removeClass('btn-success')
        $(content).attr('state', 'none')
      }

      $(content).find('.vote-btn-up .count').text(data.uv)
      $(content).find('.vote-btn-down .count').text(data.dv)
    })
  } else if (state == 'dv') {
    $.post('/ajax/vote/remove', { id: cid, type: type }, function (data) {
      if (data.status == 'ok') {
        $(content).find('.vote-btn-down').removeClass('btn-danger')
        $(content).attr('state', 'none')

        $.post('/ajax/vote/add', { id: cid, type: type, up: 'true' }, function (data) {
          if (data.status == 'ok') {
            $(content).find('.vote-btn-up').addClass('btn-success')
            $(content).attr('state', 'uv')
          }

          $(content).find('.vote-btn-up .count').text(data.uv)
          $(content).find('.vote-btn-down .count').text(data.dv)
        })
      }
    })
  } else if (state == 'none') {
    $.post('/ajax/vote/add', { id: cid, type: type, up: 'true' }, function (data) {
      if (data.status == 'ok') {
        $(content).find('.vote-btn-up').addClass('btn-success')
        $(content).attr('state', 'uv')
      }

      $(content).find('.vote-btn-up .count').text(data.uv)
      $(content).find('.vote-btn-down .count').text(data.dv)
    })
  }
}

VotesModule.prototype.addDownvote = function () {
  var content = $(this).parent()
  var cid = $(content).attr('data-id')
  var state = $(content).attr('state')
  var type = $(content).attr('data-type')

  if (state == 'uv') {
    $.post('/ajax/vote/remove', { id: cid, type: type }, function (data) {
      if (data.status == 'ok') {
        $(content).find('.vote-btn-up').removeClass('btn-success')
        $(content).attr('state', 'none')

        $.post('/ajax/vote/add', { id: cid, type: type, up: 'false' }, function (data) {
          if (data.status == 'ok') {
            $(content).find('.vote-btn-down').addClass('btn-danger')
            $(content).attr('state', 'dv')
          }

          $(content).find('.vote-btn-up .count').text(data.uv)
          $(content).find('.vote-btn-down .count').text(data.dv)
        })
      }
    })
  } else if (state == 'dv') {
    $.post('/ajax/vote/remove', { id: cid, type: type }, function (data) {
      if (data.status == 'ok') {
        $(content).find('.vote-btn-down').removeClass('btn-danger')
        $(content).attr('state', 'none')
      }

      $(content).find('.vote-btn-up .count').text(data.uv)
      $(content).find('.vote-btn-down .count').text(data.dv)
    })
  } else if (state == 'none') {
    $.post('/ajax/vote/add', { id: cid, type: type, up: 'false' }, function (data) {
      if (data.status == 'ok') {
        $(content).find('.vote-btn-down').addClass('btn-danger')
        $(content).attr('state', 'dv')
      }

      $(content).find('.vote-btn-up .count').text(data.uv)
      $(content).find('.vote-btn-down .count').text(data.dv)
    })
  }
}

export default VotesModule
