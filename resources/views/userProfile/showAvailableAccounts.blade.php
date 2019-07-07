@extends('layouts.default')
@section('content')
    <h1>Доступные счета</h1>
    @foreach($availableAccounts as $account)
        <div class="card mb-2">
            <div class="card-header">
                <span class="float-left"> # {{$account->id}} </span>
                <span class="float-right"> {{$account->user->name}} </span>
            </div>
            <div class="card-body">
                <span class="float-left">Баланс: {{$account->balance}}</span>
                <a class="btn btn-dark float-right" href="{{route('accountShow', $account->id)}}">Подробнее</a>
            </div>
        </div>
    @endforeach
@endsection