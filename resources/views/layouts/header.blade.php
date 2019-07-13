<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="{{route('userProfileShow')}}">Банк Ньюландии</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ Route::is('userProfileShow') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('userProfileShow')}}">Мой профиль</a>
            </li>
            <li class="nav-item {{ Route::is('profileEditForm') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('userProfileEdit')}}">Настройки</a>
            </li>
            <li class="nav-item {{ Route::is('moneyTransferForm') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('moneyTransferForm')}}">Перевод онлайн</a>
            </li>
            @if(request()->user()->available_accounts->count())
            <li class="nav-item {{ Route::is('availableAccounts') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('availableAccounts')}}">Счета</a>
            </li>
            @endif()
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();return false;">
                    Выйти
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>