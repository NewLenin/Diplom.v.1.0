@extends('layouts.main')
@section('main')
    <section class="profile">
        <div class="main-container">
            <div class="main-row">
                <form action="{{route('changeUser')}}" method="post" enctype="multipart/form-data" class="profile-info">
                    @csrf
                    <div class="profile-img -colum-25">
                        <div class="img" style="border-color: {{$user->color}}"><img src="../storage/{{$user->photo}}" alt=""></div>
                        <div class="input-wrapper" style="border-color: {{$user->color}}">
                            <p>Изменить фото</p>
                            <input type="file" name="file">
                        </div>
                    </div>

                    <div class="profile-data -colum-75">
                        <label for="fio">ФИО</label>
                        <input type="text" class="fio" name="fio" placeholder="Ввидите ваше ФИО"
                            value="{{ $user->fio }}" style="border-color: {{$user->color}}">
                        <label for="age">Дата рождения</label>
                        <input type="date" class="age" name="age"
                            value="{{ $user->bithday }}" style="border-color: {{$user->color}}"
                            placeholder="{{ $user->bithday }}">
                        <label for="email">Email</label>
                        <input type="email" class="email" name="email" placeholder="Введите почту"
                            value="{{ $user->email }}" style="border-color: {{$user->color}}">
                        <div class="save">
                            <div class="color-con me-5">
                                <label for="color">Любимый цвет</label>
                                <input type="color" class="color" name="color" value="{{ $user->color }}" style="border-color: {{$user->color}}">
                            </div>
                            <button type="submit" style="border-color: {{$user->color}}">Сохранить изменения</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="statistic">
        <div class="main-container">
            <div class="main-row">
                <h1>Статистика</h1>
            </div>
            <div class="main-row">
                <h2>Дневник</h2>
            </div>
            <div class="main-row">
                <div class="diary-profile-statistic"  style="background-color: {{$user->color}}">
                    <div class="decoration">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                        </svg>
                    </div>
                    <div class="diary-profile-statistic-content">

                        <div>
                            <h3>Хороших дней:<span id="text-success" class="text-success"></span></h3>
                            <h3>Нормальных дней:<span id="text-warning" class="text-warning"></span></h3>
                            <h3>Плохих дней:<span id="text-danger" class="text-danger"></span></h3>
                            <h3>Дней без записей:<span id="text-primary" class="text-primary"></span></h3>
                        </div>
                        <div>
                            <h3>Главная причина переживаний в этом месяце: <span id="warningValue" class="text-danger"><br>
                                    Экзамены</span></h3>
                        </div>
                        <div class="calendar"  style="background-color: {{$user->color}}">
                            <div class="calendar-content">
                                <form action="" method="get">
                                    <input type="date" name="date" id="calendar-diary-profile" value="{{$today}}" class="calendar-input">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-row">
                <h2>Цели</h2>
            </div>
            <div class="main-row">
                <div class="purpose-statistic" style="background-color: {{$user->color}}">
                    <div class="decoration">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                        </svg>
                    </div>
                    <div class="profile-purpose-content flex-box -wrap -just-center">

                        <div class="-colum-33 closed">
                            <h3>Закрытых целей:<span class="text-success"> {{ $pCc->count }}</span></h3>
                            @foreach ($pC as $p)
                                <a href="/purpose" style="background-color: {{$user->color}}">{{ $p->name }}</a>
                            @endforeach

                        </div>
                        <div class="-colum-33 active">
                            <h3>Активных целей:<span class="text-warning"> {{ $pAc->count }}</span></h3>
                            @foreach ($pA as $p)
                                <a href="/purpose" style="background-color: {{$user->color}}">{{ $p->name }}</a>
                            @endforeach



                        </div>
                        <div class="-colum-33 lose">
                            <h3>Невыполненных целей:<span class="text-danger"> {{ $pLc->count }}</span></h3>
                            @foreach ($pL as $p)
                                <a href="/purpose" style="background-color: {{$user->color}}">{{ $p->name }}</a>
                            @endforeach

                        </div>

                    </div>
                </div>
            </div>

            <div class="main-row">
                <h2>Финансы</h2>
            </div>
            <div class="main-row">
                <div class="finance-statistic" style="background-color: {{$user->color}}">
                    <div class="decoration">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                        </svg>
                    </div>
                    <div class="profile-finance-content flex-box -wrap -just-center">
                        <div class="-colum-33 diagram" id="diagram">



                        </div>
                        <div class="-colum-33 balance">
                            @if ($fin && !$fin->isEmpty())
                            <h3>Всего доступно: <br>{{ $sum->sum }} руб.</h3>
                            @foreach ($fin as $f)
                                <div class="balance-data">
                                    <div class="percent">
                                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12.5" cy="12.5" r="12.5" id="{{$f->id}}"
                                                fill="{{ $f->color }}" />
                                        </svg>
                                        <p>{{ number_format($f->percent, 2, '.', ' ') }}%</p>
                                    </div>
                                    <div class="account-balance">
                                        <p>{{ $f->name }}</p>
                                        <h4>{{ $f->balance }} руб.</h4>
                                    </div>
                                </div>
                            @endforeach
                            @else
                            <h1>Счета отсутствуют</h1>
                            @endif



                        </div>
                        <div class="-colum-33 change-balance">

                            @if ($fin && !$fin->isEmpty())
                            @foreach ($fin as $f)
                            <div class="change-balance-content">
                                <div class="change-balance-data">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12.5" cy="12.5" r="12.5"
                                        id="{{$f->id}}" fill="{{ $f->color }}" />
                                    </svg>
                                    <p>{{ number_format($f->percent, 2, '.', ' ') }}% <span>{{ $f->name }}</span></p>

                                </div>
                                <h5>Изменение счёта за последнее время:</h5>
                                @foreach ($trans as $t)
                                @if ($t->fId == $f->id)
                                @if ($t->status == 1)<h4>+{{ $t->sum }} руб.</h4> @endif
                                @if ($t->status == 0)<h4>-{{ $t->sum }} руб.</h4> @endif
                                @endif
                                @endforeach
                            </div>
                            @endforeach
                            {{-- @else
                            <h4>В последнее время транзакций не производилось</h4> --}}
                            @endif



                        </div>




                    </div>
                </div>
            </div>

        </div>

    </section>
@endsection
