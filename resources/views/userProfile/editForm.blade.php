@extends('layouts.default')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="/"> Мой профиль</a></li>
            <li class="breadcrumb-item active" aria-current="page">Настройки</li>
        </ol>
    </nav>
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            Изменения сохранены
        </div>
    @endif
    @if(session('password'))
        <div class="alert alert-success" role="alert">
            Пароль изменен
        </div>
    @endif
    <form method="post" action="{{route('profileUpdate')}}">
        @csrf

        <div class="form-group">
            <label for="name">ФИО</label>
            <input type="text" class="@error('name') is-invalid @enderror form-control" id="name" name="name"  value="{{old('name')??$user->name}}">
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="crew">Номер экипажа</label>
            <input type="number" class="@error('crew') is-invalid @enderror form-control" id="crew" name="crew" value="{{old('crew')??$user->crew}}">
            @error('crew')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{route('passwordEditForm')}}" class="btn btn-info float-right">Изменить пароль</a>
    </form>

@endsection