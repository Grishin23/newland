@extends('layouts.default')
@section('title')
    Перевод онлайн
@endsection
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="/"> Мой профиль</a></li>
            <li class="breadcrumb-item active" aria-current="page">Перевод онлайн</li>
        </ol>
    </nav>
    <form method="post" action="{{route('moneyTransfer')}}" class="js-money-transfer-form">
        @csrf
        @if($available_accounts_edit->count())
            <div class="form-group">
                <label for="init_id">ИНН Отправителя</label>
                <span class="float-right" id="init_id_suggestion" style="display: none"></span>
                <select class="form-control" id="init_id" name="init_id" onchange="getBalance($(this).val())">
                    @if(request()->user()->main_account)
                        <option value="{{request()->user()->main_account->id}}">#{{request()->user()->main_account->id}} {{request()->user()->main_account->name??request()->user()->name}}</option>
                    @endif
                    @foreach($available_accounts_edit as $account)
                        <option value="{{$account->id}}">#{{$account->id}} {{$account->name??$account->user->name??''}}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if($target_user)
            <div class="alert alert-dark" role="alert">
                Получатель: {{$target_user->name}} (#{{$target_user->main_account->id}})
            </div>
        @else()
            <div class="form-group">
                <label for="transaction_type_id">Тип операции</label>
                <select class="form-control" id="transaction_type_id" name="transaction_type_id" onchange="getTransactionTypeInfo($(this).val())">
                    @foreach($transactionTypes as $transactionType)
                        @if(!$transactionType->show_only || $transactionType->show_only == request()->user()->user_role_id)
                            <option value="{{$transactionType->id}}">{{$transactionType->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="target_id">ИНН Получателя</label>
                <span class="float-right" id="target_id_suggestion" style="display: none"></span>
                <input type="number" class="@error('target_id') is-invalid @enderror form-control" id="target_id"
                       onchange="getAccountInfo(jQuery(this).val(),'target')"
                       name="target_id" placeholder="ИНН" value="{{old('target_id')}}">
                @error('target_id')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Проверяй внимательно! Рудол не воробей, вылетит, не поймаешь.</small>
            </div>
        @endif
        <div class="form-group">
            <label for="amount">Сумма</label>
            <input type="number" class="@error('amount') is-invalid @enderror form-control" id="amount" name="amount" placeholder="0" value="{{old('amount')}}">
            @error('amount')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="message">Комментарий</label>
            <textarea class="@error('message') is-invalid @enderror form-control" id="message" placeholder="Сообщение получателю" name="message" rows="3">{{old('message')}}</textarea>
            @error('message')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary" onclick="$(this).attr('disabled',true); $('.js-money-transfer-form').submit();">Перевести</button>
    </form>
@endsection()
@section('afterLoadPage')
    <script>
        function getAccountInfo(uid,prefix){
            jQuery.ajax({
                type:'GET',
                url:'/account-info/'+uid,
                success:function(data){
                    jQuery("#"+prefix+"_id_suggestion").html(data.msg).show();
                }
            });
        }
        function getTransactionTypeInfo(transactionTypeID){
            jQuery.ajax({
                type:'GET',
                url:'/transaction-type-info/'+transactionTypeID,
                success:function(data){
                    if (data!=null){
                        jQuery("#target_id").val(data.account_id).trigger('onchange');
                        jQuery("#message").html(data.message);
                    }
                }
            });
        }
        function getBalance(accountID){
            jQuery.ajax({
                type:'GET',
                url:'/account-balance/'+accountID,
                success:function(data){
                    jQuery("#init_id_suggestion").html(data.msg).show();
                }
            });
        }
        getTransactionTypeInfo($("#transaction_type_id").val());
        getBalance($("#init_id").val());
    </script>
@endsection
