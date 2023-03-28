<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MyDiscipline</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jura&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/site.css" asp-append-version="true" />
    <link rel="stylesheet" href="../Diplom.styles.css" asp-append-version="true" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    <header>
        <div class="main-container">
            <div class="main-row">
                <div class="header">
                    <a href="/">
                        MyDiscipline <svg width="29" height="28" viewBox="0 0 29 28" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 27L10.5 13.5L14.5 21L26.5 2M26.5 2L28 12M26.5 2L17.5 5.5" stroke="white"
                                stroke-width="2" />
                        </svg>
                    </a>
                    @if (Auth::check())
                        <a class="profile-button" href="{{ route('profile') }}">
                            Профиль
                        </a>
                        <a class="profile-button" href="{{ route('logout') }}">
                            Выход
                        </a>
                    @else
                        <a class="profile-button" href="{{ route('login') }}">
                            Вход
                        </a>
                        <a class="profile-button" href="{{ route('registration') }}">
                            Регистрация
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </header>

    @yield('main')

    <footer>
        <div class="main-container">
            <div class="main-row">
                <ul>
                    <li>
                        <a href="/">
                            MyDiscipline
                            <svg width="29" height="28" viewBox="0 0 29 28" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 27L10.5 13.5L14.5 21L26.5 2M26.5 2L28 12M26.5 2L17.5 5.5" stroke="white"
                                    stroke-width="2" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <div class="help">
                            <a href="/">
                                Поддержка
                            </a>
                        </div>
                    </li>
                </ul>


            </div>
        </div>
        <div class="copy">
            Создано Ильичём 2022
        </div>

    </footer>
    <script src="../js/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
    <script src="../js/site.js"></script>

</body>

</html>