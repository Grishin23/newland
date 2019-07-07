@extends('layouts.default')
@section('content')
    <h1>Перевод</h1>
    <form method="post" action="{{route('moneyTransfer')}}">
        @csrf
        @if($full_access)
            <div class="form-group">
                <label for="init_id">ИНН Отправителя</label>
                <span class="float-right" id="init_id_suggestion" style="display: none"></span>
                <input type="number" class="@error('init_id') is-invalid @enderror form-control" id="init_id"
                       name="init_id" placeholder="Введи ИНН"
                       onchange="getUserInfo(jQuery(this).val(),'init')"
                       value="{{old('init_id')}}">
                @error('init_id')
                    <small class="form-text text-muted text-danger">{{ $message }}</small>
                @enderror
            </div>
        @endif
        @if($target_user)
            <div class="alert alert-dark" role="alert">
                Получатель: {{$target_user->name}} (#{{$target_user->id}})
            </div>
        @else()
            <div class="form-group">
                <label for="target_id">ИНН Получателя</label>
                <span class="float-right" id="target_id_suggestion" style="display: none"></span>
                <input type="number" class="@error('target_id') is-invalid @enderror form-control" id="target_id"
                       onchange="getUserInfo(jQuery(this).val(),'target')"
                       name="target_id" placeholder="Введи ИНН" value="{{old('target_id')}}">
                @error('target_id')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Проверяйте вниметельно! Рудол не воробей, вылетит, не поймаешь.</small>
            </div>
        @endif
        <div class="form-group">
            <label for="amount">Сумма</label>
            <input type="number" class="@error('amount') is-invalid @enderror form-control" id="amount" name="amount" placeholder="А что по денюшке?" value="{{old('amount')}}">
            @error('amount')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="message">Комментарий</label>
            <textarea class="@error('message') is-invalid @enderror form-control" id="message" placeholder="Расскажи за что" name="message" rows="3">{{old('message')}}</textarea>
            @error('message')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Перевести</button>
    </form>
@endsection()
<script>
    function getUserInfo(uid,prefix){
        jQuery.ajax({
            type:'GET',
            url:'/user-info/'+uid,
            success:function(data){
                console.log("#"+prefix+"_id_suggestion");
                jQuery("#"+prefix+"_id_suggestion").html(data.msg).show();
            }
        });
    }
</script>