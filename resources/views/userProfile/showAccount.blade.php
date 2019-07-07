@extends('layouts.default')
@section('content')
    <h1>{{$account->user->name}}</h1>
    <div class="row">
        <div class="col-6">
            <dt>Ваш ИНН</dt>
            <dd>{{$account->user->id}}</dd>
        </div>
        <div class="col-6">
            <dt>Баланс</dt>
            <dd>{{$account->balance}}</dd>
        </div>
    </div>
    <h6>Последние транзакции:</h6>
    @foreach($account->transactions as $transaction)
        <div class="card mb-2">
            <div class="card-header">
                <span class="float-left"> {{$transaction->created_at->toDateTimeString()}}</span>
                <span class="float-right {{$transaction->account_init_id == $account->id?'text-danger':'text-success'}}">{{$transaction->amount}}</span>
            </div>
            <div class="card-body">
                @if($transaction->account_init_id == $account->id)
                    Кому: <span class="small">{{$transaction->account_target->user->name}}</span>
                @else()
                    От кого: <span class="small">{{$transaction->account_init->user->name}}</span>
                @endif
                <blockquote class="blockquote mb-0">
                    <footer class="blockquote-footer">{{$transaction->message}}</footer>
                </blockquote>
            </div>
        </div>
    @endforeach()
@endsection()