import { last, template } from 'lodash'

function ContentsModule () {

  if (window.username) {
    $('span.save_content').click(this.saveContent)
    $('.content_add_form input[name="url"]').on('paste', this.findDuplicates)
    $('.content_add_form input[name="groupname"]').on('input', this.showSidebar)
      .on('typeahead:selected', this.showSidebar)
  }

}

ContentsModule.prototype.saveContent = function (e) {
  var button = $(this)
  var content = $(this).parents('[data-id]').attr('data-id')

  if (button.hasClass('glyphicon-star')) {
    $.post('/ajax/content/remove_save', { content: content }, function (data) {
      if (data.status === 'ok') {
        $(button).removeClass('glyphicon-star')
        $(button).addClass('glyphicon-star-empty')
      }
    })
  } else {
    $.post('/ajax/content/add_save', { content: content }, function (data) {
      if (data.status === 'ok') {
        $(button).removeClass('glyphicon-star-empty')
        $(button).addClass('glyphicon-star')
      }
    })
  }
}

ContentsModule.prototype.findDuplicates = function () {
  var input = this

  setTimeout(function () {
    var url = $('input[name="url"]').val()
    var group = $('input[name="groupname"]').val()

    $('.duplicate_info').remove()
    $(input).parent().append('<p class="help-block duplicate_info"><span class="glyphicon glyphicon-refresh spinner"></span> Ładowanie informacji...</p>')

    $.post('/ajax/utils/get_title', { url: url, group: group }, function (data) {
      $('.duplicate_info').remove()

      if (data.status === 'ok') {
        if (!$('input[name="title"]').val())
          $('input[name="title"]').val(data.title)

        if (!$('textarea[name="description"]').val())
          $('textarea[name="description"]').val(data.description)

        if (data.duplicates.length) {
          var last = last(data.duplicates)

          var render = template('<p class="help-block duplicate_info"><span class="glyphicon glyphicon-info-sign"></span> Link został już dodany do wybranej grupy:<br><a href="/c/<%= id %>"><%= title %></a></p>')
          var html = render({ id: last._id, title: last.title })

          $(input).parent().append(html)
        }
      }
    })
  }, 1)
}

ContentsModule.prototype.showSidebar = function () {
  window.clearTimeout($(this).data('timeout'))

  var groupName = $('.content_add_form input[name="groupname"]').val()

  $(this).data('timeout', setTimeout(function () {
    $.get('/ajax/group/' + groupName + '/sidebar', function (data) {
      $('.sidebar .well').html(data.sidebar)
    })
  }, 500))
}

export default ContentsModule
