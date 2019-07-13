@extends('layouts.default')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="/"> Мой профиль</a></li>
            <li class="breadcrumb-item active" aria-current="page">Счета</li>
        </ol>
    </nav>

    <form id="go-to-account" class="mb-1" action="">
        <div class="form-group">
            <label for="account_id">ИНН</label>
            <input type="number" class="form-control" id="account_id"
                   onchange="getAccountInfo(jQuery(this).val(),'target')"
                   name="account_id" placeholder="ИНН">

        </div>
        <button class="btn btn-dark" onclick="$('#go-to-account').attr('action','/user-profile/account/'+$('#account_id').val())">Перейти к счету</button>
    </form>
    <h1>Счета:</h1>
    @foreach($availableAccounts as $account)
        <div class="card mb-2">
            <div class="card-header">
                <span class="float-left"> # {{$account->id}} </span>:
                <span class=""> {{$account->name??$account->user->name}} </span>
            </div>
            <div class="card-body">
                <span class="float-left">Баланс: {{$account->balance}}</span>
                <a class="btn btn-dark float-right" href="{{route('accountShow',['id'=>$account->id])}}">Подробнее</a>
            </div>
        </div>
    @endforeach
@endsection