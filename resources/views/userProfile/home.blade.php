@extends('layouts.default')
@section('content')
    <h1>{{$user->name}}</h1>
    @if (session('transaction'))
        <div class="card mb-2">
            <div class="card-header">
                <span class="float-left text-success"> Опрерация проведена успешно!</span>
            </div>
            <div class="card-body">
                    Номер транзакции <span class="small">{{session('transaction')['id']}}</span>
                <blockquote class="blockquote mb-0">
                    <footer class="blockquote-footer">{{session('transaction')['message']}}</footer>
                </blockquote>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-6">
            <dt>Твой ИНН</dt>
            <dd>4{{$user->id}}</dd>
        </div>
        <div class="col-6">
            <dt>Баланс</dt>
            <dd>{{$mainAccount->balance}}</dd>
        </div>
    </div>
    <h6>Последние транзакции:</h6>
    @foreach($mainAccount->transactions as $transaction)
        <div class="card mb-2">
            <div class="card-header">
                <span class="float-left"> {{$transaction->created_at->toDateTimeString()}}</span>
                <span class="float-right {{$transaction->account_init_id == $mainAccount->id?'text-danger':'text-success'}}">{{$transaction->amount}}</span>
            </div>
            <div class="card-body">
                    @if($transaction->account_init_id == $mainAccount->id)
                        Кому: <span class="small">{{$transaction->account_target->user->name}} #{{$transaction->account_target->user->id}}</span>
                    @else()
                        От кого: <span class="small">{{$transaction->account_init->user->name}} #{{$transaction->account_init->user->id}}</span>
                    @endif
                <blockquote class="blockquote mb-0">
                    <footer class="blockquote-footer">{{$transaction->message}}</footer>
                </blockquote>
            </div>
        </div>
    @endforeach()
@endsection()