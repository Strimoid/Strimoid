@foreach ($poll['questions'] as $questionId => $question)
    <div class="question">
        <h4>{{{ $question['title'] }}}</h4>

        <div class="chart" id="{!! $questionId !!}" style="width: 400px"></div>
    </div>
@endforeach

@section('scripts')
    @parent

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.min.js"></script>

    @foreach ($poll['questions'] as $questionId => $question)

    <?php

    $data = array();

    foreach ($question['options'] as $option)
    {
        $data[] = ['name' => e($option['name']), 'votes' => $option['votes']];
    }

    ?>

    <script type="text/javascript">
    $(document).ready(function() {
        Morris.Bar({
            element: '{!! $questionId !!}',
            data: {!! json_encode($data) !!},
            xkey: 'name',
            ykeys: ['votes'],
            labels: ['Liczba głosów'],
            hideHover: 'auto'
        });
    });
    </script>

    @endforeach

@stop

