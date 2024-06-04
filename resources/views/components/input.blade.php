<input
    value='{{old($name)??$val}}'
    type='{{$type ?? 'number'}}'
    name='{{$name}}'
    id='{{$id ?? $name}}'
    class='form-control' />

{!! $ers->first($name, ':message') !!}
