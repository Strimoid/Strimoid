function PollsModule () {
  $('.poll input').click(this.updateOptions)
}

PollsModule.prototype.updateOptions = function (e) {
  var question = $(this).parents('.question')

  var checked = $(question).find('input:checked').length
  var min = $(question).find('.options').attr('data-min')
  var max = $(question).find('.options').attr('data-max')

  $(question).find('.help').removeClass('error')

  if (checked >= max) {
    $(question).find('.help').text('Zaznaczyłeś maksymalną ilość odpowiedzi')
    $(question).find('input:not(:checked)').attr('disabled', 'disabled')
  } else if (checked < min) {
    $(question).find('.help').text('Musisz zaznaczyć jeszcze ' + (min - checked))
    $(question).find('input:not(:checked)').removeAttr('disabled')
  } else {
    $(question).find('.help').text('Możesz zaznaczyć jeszcze ' + (max - checked))
    $(question).find('input:not(:checked)').removeAttr('disabled')
  }

}

export default PollsModule
