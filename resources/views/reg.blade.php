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
    <link rel="stylesheet" href="{{asset('public/css/site.css')}}" asp-append-version="true" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

<body>
    <section class="register">
        <div class="form-cont">
            <form method="post" id="regForm" name="reg" action="{{ route('register') }}"  onsubmit="formAction(this, event)">
                <div class="modal-body">
                    <h1>Регистрация</h1>
                    @csrf
                    <input type="email" name="email" id="emailInput" placeholder="Почта" class="form-control i mt-2">
                    <div class="invalid-feedback" id="emailError"></div>
                    <input type="password" name="pass1" id="pass1Input" class="form-control  mt-2"
                        aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback" placeholder="Пароль">
                    <input type="password" name="pass2" id="pass2Input" class="form-control  mt-2"
                        aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback"
                        placeholder="Повторите пароль">
                    <div id="pass1Error" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer d-flex justify-content-between mt-2">
                    <a href="/" class="btn btn-secondary me-2" >Закрыть</a>
                    <button type="submit" id="reg" class="btn btn-success">Зарегистрироваться</button>
                </div>
            </form>
            <a class="d-flex justify-content-end text-primary areg" href="{{route('login')}}">Авторизироваться</a>
        </div>
    </section>
</body>
<script src="{{asset('public/js/jquery-3.6.4.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
</script>
<script src="{{asset('public/js/site.js')}}"></script>

</html>
