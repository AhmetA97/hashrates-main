@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('result')}}" method="post"> @csrf
                        <div class="row mb-3">
                            <div class="form-group col-md-3">
                                <label>Тариф</label>
                                <x-input name="tariff" val="{{$request->tariff??''}}" :ers="$errors"/>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Потребление</label>
                                <x-input name="consumption" val="{{$request->consumption??''}}" :ers="$errors"/>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Дата начала расчета</label>
                                <x-input name="start_date" val="{{$request->start_date??''}}" type="date" :ers="$errors"/>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Дата окончания расчета</label>
                                <x-input name="end_date" val="{{$request->end_date??''}}" type="date" :ers="$errors"/>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-end">Вперед!</button>
                    </form>
                </div>
            </div>


            @isset($result)
                <div class="card mt-5">
                    <div class="card-header">Отчет по воркерам</div>
                    <div class="card-body">
                        @if(!$result['dates'])
                            Не найдено
                        @else
                            Клиент: {{auth()->user()->name}}

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">Воркер</th>
                                    <th scope="col">Модель</th>
                                    <th scope="col">Итого</th>
                                    @foreach($result['dates'] as $date)
                                        <th scope="col" colspan="2">{{$date}}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @php $priceSumOfAllWorkers = 0; @endphp
                                @foreach($result['workers'] as $worker)
                                    <tr>
                                        <td>{{$worker['worker_name']}}</td>
                                        <td>{{$worker['worker_name']}} M</td>
                                        <td>{{$worker['price_sum']}}</td>
                                        @foreach($worker['hashrates'] as $hash)
                                            <td>{{$hash['hashrate'] ? $hash['hashrate'].' TH/s' : ''}} </td>
                                            <td>{{$hash['price'] ? $hash['price'].' руб.' : ''}} </td>
                                        @endforeach
                                    </tr>
                                    <tr><td colspan="100%">Итого по модели {{$worker['model_name']}}: {{$worker['price_sum']}} руб.</td></tr>
                                    @php $priceSumOfAllWorkers += $worker['price_sum']; @endphp
                                @endforeach
                                </tbody>
                            </table>

                            Обший итог: {{$priceSumOfAllWorkers}} руб.
                        @endif
                    </div>
                </div>
            @endisset
        </div>
    </div>
</div>
@endsection
