@extends('layouts.default')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Мой профиль</li>
        </ol>
    </nav>
    <h1>{{$user->name}}</h1>
    @if (session('transaction'))
        <div class="card mb-2">
            <div class="card-header">
                <span class="float-left text-success"> Операция проведена успешно!</span>
            </div>
            <div class="card-body">
                    Номер транзакции: <span class="small">{{session('transaction')['id']}}</span>
                <blockquote class="blockquote mb-0">
                    <footer class="blockquote-footer">{{session('transaction')['message']}}</footer>
                </blockquote>
            </div>
        </div>
    @endif
    @if($mainAccount)
        <div class="row">
            <div class="col-6">
                <dt>ИНН</dt>
                <dd>{{$mainAccount->id}}</dd>
            </div>
            <div class="col-6">
                <dt>Баланс</dt>
                <dd>{{$mainAccount->balance}}</dd>
            </div>
        </div>
        <h6>Последние транзакции:</h6>
        {{$mainTransactions->links()}}
        @foreach($mainTransactions as $transaction)
            <div class="d-sm-block d-lg-none">
                <div class="card mb-2">
                    <div class="card-header">
                        <span class="float-left"> {{$transaction->created_at->toDateTimeString()}}</span>
                        <span class="float-right {{$transaction->account_init_id == $mainAccount->id?'text-danger':'text-success'}}">{{$transaction->amount}}</span>
                    </div>
                    <div class="card-body">
                        @if($transaction->account_init_id == $mainAccount->id)
                            Получатель: <span class="small">{{$transaction->account_target->name??$transaction->account_target->user->name}}</span>
                            <span class="small">{{$transaction->account_target->user?'('.$transaction->account_target->user->crew.' экипаж)':''}}</span>
                        @else()
                            Отправитель: <span class="small">{{$transaction->account_init->name??$transaction->account_init->user->name}}</span>
                            <span class="small">{{$transaction->account_init->user?'('.$transaction->account_init->user->crew.' экипаж)':''}}</span>
                        @endif
                        <br>
                        Номер транзакции: <span class="small">{{$transaction->id??'-'}}</span>
                        <br>
                        Тип операции: <span class="small">{{$transaction->transaction_type->name??'-'}}</span>
                        <blockquote class="blockquote mb-0">
                            <footer class="blockquote-footer">{{$transaction->message}}</footer>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="d-lg-block" style="display: none">
                <div class="card mb-2">
                    <div class="card-body p-2">
                        <span class="float-left"> {{$transaction->created_at->toDateTimeString()}} </span>
                        &nbsp;&nbsp;&nbsp;
                        #{{$transaction->id??'-'}}
                        &nbsp;&nbsp;&nbsp;
                        {{$transaction->transaction_type->name??'-'}}
                        &nbsp;&nbsp;&nbsp;
                        @if($transaction->account_init_id == $mainAccount->id)
                            Получатель: {{$transaction->account_target->name??$transaction->account_target->user->name}}
                        @else()
                            Отправитель: {{$transaction->account_init->name??$transaction->account_init->user->name}}
                        @endif
                        &nbsp;&nbsp;&nbsp;
                        <span class="float-right {{$transaction->account_init_id == $mainAccount->id?'text-danger':'text-success'}}">{{$transaction->amount}}</span>

                        {{--<blockquote class="blockquote mb-0">--}}
                            <footer class="blockquote-footer">{{$transaction->message}}</footer>
                        {{--</blockquote>--}}
                    </div>
                </div>
            </div>
        @endforeach()
        {{$mainTransactions->links()}}
    @endif()
@endsection()