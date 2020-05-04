function FoldersModule () {

  if (window.username) {
    $('input.create_folder').keyup(this.createFolder)
    $('input.modify_folder').change(this.modifyFolder)
    $('button.folder_publish').click(this.publishFolder)
    $('button.folder_remove').click(this.removeFolder)
    $('form.folder_add_group').submit(this.addGroup)
    $('button.folder_remove_group').click(this.removeGroup)
  }

}

FoldersModule.prototype.createFolder = function (e) {
  // Return if key is not enter
  if (e.keyCode != 13)
    return

  var input = this
  var group = $(input).parents('.folder-menu').first().attr('data-group')

  $.post('/ajax/folder/create', { name: $(input).val(), groupname: group }, function (data) {
    if (data.status == 'ok') {
      var template = _.template('<li><label><input type="checkbox" data-id="<%= id %>" checked> <%= name %></label></li>')
      var html = template({ id: data.id, name: $(input).val() })

      $(html).insertBefore($(input).parents().eq(1).find('.divider'))
      $(input).val('')
    }
  })
}

FoldersModule.prototype.modifyFolder = function () {
  var input = this
  var group = $(input).parents('.folder-menu').first().attr('data-group')

  if (this.checked) {
    $.post('/ajax/folder/add_group', { folder: $(this).attr('data-id'), group: group }, function (data) {
      if (data.status == 'ok')
        $(input).prop('disabled', false)
    })

  } else {
    $.post('/ajax/folder/remove_group', { folder: $(this).attr('data-id'), group: group }, function (data) {
      if (data.status == 'ok')
        $(input).prop('disabled', false)
    })
  }

  $(input).prop('disabled', true)
}

FoldersModule.prototype.publishFolder = function () {
  var button = $(this)
  var id = $(this).parent().attr('data-id')

  if (button.hasClass('btn-success')) {
    $.post('/ajax/folder/edit', { folder: id, public: 'false' }, function (data) {
      if (data.status == 'ok') {
        $(button).removeClass('btn-success')
        $(button).addClass('btn-default')
      }

    })
  } else {
    $.post('/ajax/folder/edit', { folder: id, public: 'true' }, function (data) {
      if (data.status == 'ok') {
        $(button).removeClass('btn-default')
        $(button).addClass('btn-success')
      }
    })
  }
}

FoldersModule.prototype.removeFolder = function () {
  var id = $(this).parent().attr('data-id')

  bootbox.confirm('Na pewno chcesz usunąć wybrany folder?', function (result) {
    if (result)
      $.post('/ajax/folder/remove', { folder: id }, function (data) {
        if (data.status == 'ok')
          window.location.href = '/'
      })
  })
}

FoldersModule.prototype.addGroup = function () {
  var form = $(this)
  var folder = $(this).parent().parent().attr('data-folder')
  var group = $(this).find('input[name="group"]').val()

  if (!group)
    return false

  $.post('/ajax/folder/add_group', { folder: folder, group: group }, function (data) {
    if (data.status == 'ok') {
      var template = _.template('<li class="list-group-item" style="padding: 5px 15px" ><a href="/g/<%= group %>"><%= group %></a><button type="button" class="btn btn-xs btn-danger folder_remove_group pull-right" data-group="<%= group %>"><span class="glyphicon glyphicon-trash"></span></button></li>')
      var html = template({ group: group })

      var lastChild = $('.folder_groups').children().last()

      $(html).insertBefore(lastChild)

      $(form)[0].reset()
    }
  })

  return false
}

FoldersModule.prototype.removeGroup = function () {
  var row = $(this).parent()
  var folder = $(this).parent().parent().attr('data-folder')
  var group = $(this).attr('data-group')

  $.post('/ajax/folder/remove_group', { folder: folder, group: group }, function (data) {
    if (data.status == 'ok')
      $(row).remove()
  })
}

export default FoldersModule
