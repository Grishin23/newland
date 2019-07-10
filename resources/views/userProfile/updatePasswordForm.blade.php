@extends('layouts.default')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"> Мой профиль</a></li>
            <li class="breadcrumb-item"><a href="{{route('profileEditForm')}}">Настройки</a></li>
            <li class="breadcrumb-item active" aria-current="page">Изменить пароль</li>
        </ol>
    </nav>
    <form method="post" action="{{route('passwordEdit')}}">
        @csrf
        <div class="form-group">
            <label for="now_password">Текущий пароль</label>
            <input type="password" class="@error('now_password') is-invalid @enderror form-control" id="now_password" name="now_password" value="{{old('now_password')}}">
            @error('now_password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="password">Новый пароль</label>
            <input type="password" class="@error('password') is-invalid @enderror form-control" id="password" name="password" value="">
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Подтверждение пароля</label>
            <input type="password" class="@error('password_confirmation') is-invalid @enderror form-control" id="password_confirmation" name="password_confirmation" value="">
            @error('password_confirmation')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
@endsection