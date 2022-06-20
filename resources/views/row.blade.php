<div class="group-row">
    @if($filters)
    @foreach($filters as $index => $filter)
        {!! $filter->render() !!}
    @endforeach
    @endif
</div>