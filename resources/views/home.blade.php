@extends('layouts.main')
@section('main')
<section class="main">
    <div class="main-container">
        <div class="main-row">
            <div class="main-diary">
                <div class="main-diary-content">
                    <h1>
                        Что у тебя сегодня нового?
                    </h1>
                    <a href="{{route('diary')}}">
                        Записать в дневник
                    </a>
                    <div class="main-diary-decoration">
                        <svg width="114" height="112" viewBox="0 0 114 112" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="70.5" cy="44" rx="43.5" ry="44" fill="white" />
                            <ellipse cx="13.5" cy="98" rx="13.5" ry="14" fill="white" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-row flex-box -wrap -just-center">
            <div class="main-finance -colum-50">
                <div class="main-finance-content">
                    <div class="main-finance-decoration">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                        </svg>
                    </div>
                    <div class="main-finance-text">
                        Изменились финансы?
                    </div>
                    <a href="{{route('finance')}}">
                        Просмотреть свои финансы
                    </a>
                </div>
            </div>
            <div class="main-purpose -colum-50 flex-box -center">
                <div class="main-purpose-content">
                    <div class="main-purpose-decoration">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                        </svg>
                    </div>
                    <div class="main-purpose-text">
                        Новая мечта?
                    </div>
                    <a href="{{route('purpose')}}">
                        Поставить цель
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
