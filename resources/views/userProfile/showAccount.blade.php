@extends('layouts.default')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="/"> Мой профиль</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{route('availableAccounts')}}">Счета</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$account->name??$account->user->name}}</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-4">
            <dt>ИНН</dt>
            <dd>{{$account->id}}</dd>
        </div>
        <div class="col-4">
            <dt>Баланс</dt>
            <dd>{{$account->balance}}</dd>
        </div>
        <div class="col-4">
            <dt>Экипаж</dt>
            <dd>{{$account->user?$account->user->crew:'-'}}</dd>
        </div>
    </div>
    <h6>Последние транзакции:</h6>
    {{$accountTransactions->links()}}
    @foreach($accountTransactions as $transaction)
        <div class="card mb-2">
            <div class="card-header">
                <span class="float-left"> {{$transaction->created_at->toDateTimeString()}}</span>
                <span class="float-right {{$transaction->account_init_id == $account->id?'text-danger':'text-success'}}">{{$transaction->amount}}</span>
            </div>
            <div class="card-body">

                @if($transaction->account_init_id == $account->id)
                    Кому: <span class="small">{{$transaction->account_target->name??$transaction->account_target->user->name}}</span>
                @else()
                    Отправитель: <span class="small">{{$transaction->account_init->name??$transaction->account_init->user->name}}</span>
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
    @endforeach()
@endsection()