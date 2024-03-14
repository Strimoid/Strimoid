{{ html()->form(action: action('PollController@addVote', $content->hashId())->class(['poll'])->open() }}
@foreach ($poll['questions'] as $questionId => $question)
    <div class="question">
        <h4>{{{ $question['title'] }}}</h4>

        @if ($errors->has($questionId))
            <small class="help error">{!! $errors->first($questionId) !!}</small>
        @else
            <small class="help">Zaznacz przynajmniej {!! $question['min_selections'] !!}, lecz nie więcej niż {!! $question['max_selections'] !!}</small>
        @endif

        <div class="options" data-min="{!! $question['min_selections'] !!}" data-max="{!! $question['max_selections'] !!}">
            @foreach ($question['options'] as $option)
                @include('global.form.input_checkbox', ['name' => $questionId .'[]', 'label' => e($option['name']), 'value' => $option['_id']])
            @endforeach
        </div>
    </div>
@endforeach

<button type="submit" class="btn btn-primary">Zagłosuj</button>
{{ html()->form()->close() }}
