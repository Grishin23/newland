@extends('layouts.default')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="/"> Мой профиль</a></li>
            <li class="breadcrumb-item active" aria-current="page">Доступные счета</li>
        </ol>
    </nav>
    <h1>Доступные счета</h1>
    @foreach($availableAccounts as $account)
        <div class="card mb-2">
            <div class="card-header">
                <span class="float-left"> # {{$account->id}} </span>
                <span class="float-right"> {{$account->name??$account->user->name}} </span>
            </div>
            <div class="card-body">
                <span class="float-left">Баланс: {{$account->balance}}</span>
                <a class="btn btn-dark float-right" href="{{route('accountShow', $account->id)}}">Подробнее</a>
            </div>
        </div>
    @endforeach
@endsection